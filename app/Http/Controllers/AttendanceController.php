<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\Student;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $department_id = $request->query('department_id');
        $classroom_id = $request->query('classroom_id');

        // Step 1: List all departments
        if (!$department_id) {
            $departments = \App\Models\Department::withCount('classrooms')->get();
            return view('attendance.index', compact('departments'));
        }

        // Step 2: List classrooms in a department
        if (!$classroom_id) {
            $department = \App\Models\Department::with(['classrooms' => function($q) {
                $q->withCount('students');
            }])->findOrFail($department_id);
            return view('attendance.classrooms', compact('department'));
        }

        // Step 3: List subjects in a classroom
        $classroom = \App\Models\Classroom::with(['department', 'schedules.subject'])->findOrFail($classroom_id);
        $subjects = $classroom->schedules->map->subject->unique('id');
        
        return view('attendance.subjects', compact('classroom', 'subjects'));
    }

    public function create(Request $request)
    {
        $classroom_id = $request->query('classroom_id');
        $subject_id = $request->query('subject_id');
        $academic_year_id = $request->query('academic_year_id');
        $date_str = $request->query('date', Carbon::today()->toDateString());
        $date = Carbon::parse($date_str);

        if (!$classroom_id || !$subject_id) {
            return redirect()->route('attendance.index')->with('error', 'Vui lòng chọn đầy đủ thông tin lớp và môn học.');
        }

        $classroom = \App\Models\Classroom::findOrFail($classroom_id);
        $subject = \App\Models\Subject::findOrFail($subject_id);
        
        \App\Models\AcademicYear::getRelevantYears();
        $academicYears = \App\Models\AcademicYear::orderBy('name', 'asc')->get();
        
        // Find current/selected academic year
        $activeSem = \App\Models\Semester::where('is_active', true)->first();
        $defaultYearId = $activeSem ? $activeSem->academic_year_id : ($academicYears->first()->id ?? null);
        $selectedYearId = $academic_year_id ?: $defaultYearId;
        
        // Find appropriate semester in that year (active if belongs, else first)
        $semester = null;
        if ($selectedYearId) {
            $yearSemesters = \App\Models\Semester::where('academic_year_id', $selectedYearId)->get();
            $semester = $yearSemesters->where('is_active', true)->first() ?: $yearSemesters->first();
        }
        $semester_id = $semester ? $semester->id : null;
        $students = collect();
        $existingAttendance = collect();
        $schedule_id = null; // Initialize early to avoid undefined variable error

        // Find schedule for this context to link attendance
        $schedule = \App\Models\Schedule::where('classroom_id', $classroom_id)
            ->where('subject_id', $subject_id);
        
        if ($semester_id) {
            $schedule = $schedule->where('semester_id', $semester_id);
        }

        $schedule = $schedule->first();

        // Fallback: If no schedule for this specific semester, just find ANY schedule for this classroom/subject
        if (!$schedule) {
            $schedule = \App\Models\Schedule::where('classroom_id', $classroom_id)
                ->where('subject_id', $subject_id)
                ->first();
        }

        $attendanceRule = null;
        $recordedDates = collect();

        if ($schedule) {
            $schedule_id = $schedule->id;
            
            // Get registered students for this SPECIFIC schedule
            $students = \App\Models\Student::whereIn('id', function($query) use ($schedule) {
                $query->select('student_id')
                    ->from('course_registrations')
                    ->where('schedule_id', $schedule->id);
            })->get();

            $existingAttendance = Attendance::where('schedule_id', $schedule->id)
                ->where('attendance_date', $date->toDateString())
                ->get()
                ->keyBy('student_id');

            // Fetch all unique dates already recorded for this schedule
            $recordedDates = Attendance::where('schedule_id', $schedule->id)
                ->select('attendance_date')
                ->distinct()
                ->orderBy('attendance_date', 'desc')
                ->pluck('attendance_date');

            // Fetch historical attendance SUMMARY for all students in this schedule
            $historicalStats = Attendance::where('schedule_id', $schedule->id)
                ->where('attendance_date', '<', $date->toDateString())
                ->selectRaw('student_id, 
                    SUM(CASE WHEN status = "absent" THEN 1 ELSE 0 END) as total_absent,
                    SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END) as total_late')
                ->groupBy('student_id')
                ->get()
                ->keyBy('student_id');

            // Calculate Session Number
            $previousDatesCount = Attendance::where('schedule_id', $schedule->id)
                ->where('attendance_date', '<', $date->toDateString())
                ->distinct('attendance_date')
                ->count();
            $currentSessionNumber = $previousDatesCount + 1;

            // Calculate Total Sessions
            $periodsPerSession = (int)$schedule->end_period - (int)$schedule->start_period + 1;
            $totalSessions = $periodsPerSession > 0 ? ceil((int)$schedule->total_periods / $periodsPerSession) : 0;

            $attendanceRule = \App\Models\AttendanceRule::where('credits', $subject->credits)->first();
        }

        return view('attendance.create', compact(
            'classroom', 
            'subject', 
            'academicYears', 
            'selectedYearId', 
            'semester', 
            'date', 
            'students', 
            'existingAttendance', 
            'schedule_id', 
            'historicalStats', 
            'currentSessionNumber', 
            'totalSessions', 
            'attendanceRule', 
            'recordedDates'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'attendance_date' => 'required|date',
            'session_number' => 'required|integer',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:students,id',
            'attendance.*.status' => 'required|in:present,absent,late',
        ]);

        // Lock check
        $exists = Attendance::where('schedule_id', $request->schedule_id)
            ->where('attendance_date', $request->attendance_date)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'error' => 'Dữ liệu đã được khóa.'
            ], 403);
        }

        foreach ($request->attendance as $att) {
            Attendance::updateOrCreate(
                [
                    'schedule_id' => $request->schedule_id,
                    'student_id' => $att['student_id'],
                    'attendance_date' => $request->attendance_date,
                ],
                [
                    'status' => $att['status'],
                    'notes' => $att['notes'] ?? null,
                    'session_number' => $request->session_number,
                ]
            );
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Điểm danh đã được lưu thành công.'
            ]);
        }

        return redirect()->back()->with('success', 'Điểm danh đã được lưu thành công.');
    }

    public function export(Request $request)
    {
        $schedule_id = $request->query('schedule_id');
        if (!$schedule_id) return redirect()->back()->with('error', 'Thiếu ID lịch học.');

        $schedule = Schedule::with(['subject', 'classroom.department'])->findOrFail($schedule_id);

        $records = Attendance::with(['student'])
            ->where('schedule_id', $schedule_id)
            ->orderBy('attendance_date', 'asc')
            ->orderBy('student_id', 'asc')
            ->get();

        $fileName = 'Bao_cao_diem_danh_' . str_replace(' ', '_', $schedule->subject->name) . '_' . date('Ymd') . '.csv';
        
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['STT', 'Ma SV', 'Ho ten', 'Ngay học', 'Buoi', 'Trang thai', 'Ghi chu'];

        $callback = function() use($records, $columns, $schedule) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, ['BÁO CÁO CHUYÊN CẦN']);
            fputcsv($file, ['Môn học:', $schedule->subject->name]);
            fputcsv($file, ['Lớp:', $schedule->classroom->name]);
            fputcsv($file, ['Khoa:', $schedule->classroom->department->name]);
            fputcsv($file, []);
            fputcsv($file, $columns);

            $statusMap = ['present' => 'Đi học', 'absent' => 'Vắng mặt', 'late' => 'Đi muộn'];

            foreach ($records as $index => $record) {
                fputcsv($file, [
                    $index + 1,
                    $record->student->student_code,
                    $record->student->name,
                    Carbon::parse($record->attendance_date)->format('d/m/Y'),
                    $record->session_number,
                    $statusMap[$record->status] ?? $record->status,
                    $record->notes
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
