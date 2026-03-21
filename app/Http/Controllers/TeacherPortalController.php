<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Classroom;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Semester;
use App\Models\AttendanceRule;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TeacherPortalController extends Controller
{
    public function dashboard(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        $yearId = $request->query('year');

        $query = Schedule::with(['subject', 'classroom', 'academicYear'])
            ->where('teacher_id', $teacher->id);

        if ($yearId) {
            $query->where('academic_year_id', $yearId);
        }

        $schedules = $query->get();
        $academicYears = \App\Models\AcademicYear::orderBy('name', 'desc')->get();

        return view('teacher.dashboard', compact('teacher', 'schedules', 'academicYears'));
    }

    public function schedule(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        $yearId = $request->query('year');

        $query = Schedule::with(['subject', 'classroom', 'academicYear'])
            ->where('teacher_id', $teacher->id);

        if ($yearId) {
            $query->where('academic_year_id', $yearId);
        }

        $schedules = $query->orderBy('day_of_week')->get();
        $academicYears = \App\Models\AcademicYear::orderBy('name', 'desc')->get();

        return view('teacher.schedule', compact('teacher', 'schedules', 'academicYears'));
    }

    public function subjects(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        $query = Schedule::with(['subject', 'classroom', 'academicYear'])
            ->where('teacher_id', $teacher->id);

        if ($request->has('year') && $request->year != '') {
            $query->where('academic_year_id', $request->year);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('subject', function($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%")
                           ->orWhere('code', 'like', "%{$search}%");
                    })
                    ->orWhereHas('classroom', function($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $schedules = $query->paginate(20);
        $academicYears = \App\Models\AcademicYear::orderBy('name', 'desc')->get();

        return view('teacher.subjects', compact('schedules', 'academicYears'));
    }

    public function students(Schedule $schedule, Request $request)
    {
        // Check if this schedule belongs to the teacher
        if ($schedule->teacher_id !== Auth::guard('teacher')->id()) {
            abort(403);
        }

        $subject = $schedule->subject;
        $classroom = $schedule->classroom;
        
        $query = Student::where('classroom_id', $classroom->id)
            ->whereHas('registrations', function($q) use ($subject) {
                $q->where('subject_id', $subject->id);
            });

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('student_code', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $students = $query->get();

        return view('teacher.students', compact('schedule', 'students', 'subject', 'classroom'));
    }

    public function attendance(Schedule $schedule, Request $request)
    {
        if ($schedule->teacher_id !== Auth::guard('teacher')->id()) {
            abort(403);
        }

        $subject = $schedule->subject;
        $classroom = $schedule->classroom;
        
        // Use provided date or default to today
        $date = $request->has('date') ? Carbon::parse($request->date) : Carbon::today();
        
        $students = Student::where('classroom_id', $classroom->id)
            ->whereHas('registrations', function($q) use ($subject) {
                $q->where('subject_id', $subject->id);
            })
            ->get();

        $existingAttendance = Attendance::where('schedule_id', $schedule->id)
            ->whereDate('attendance_date', $date->toDateString())
            ->get()
            ->keyBy('student_id');

        // Fetch all previously recorded dates for this schedule
        $recordedDates = Attendance::where('schedule_id', $schedule->id)
            ->distinct()
            ->pluck('attendance_date')
            ->sortDesc();

        return view('teacher.attendance', compact('schedule', 'students', 'existingAttendance', 'date', 'recordedDates', 'classroom', 'subject'));
    }

    public function saveAttendance(Request $request, Schedule $schedule)
    {
        if ($schedule->teacher_id !== Auth::guard('teacher')->id()) {
            abort(403);
        }

        $request->validate([
            'attendance_date' => 'required|date',
            'attendance' => 'required|array',
        ]);

        $date = $request->attendance_date;

        foreach ($request->attendance as $student_id => $status) {
            Attendance::updateOrCreate(
                [
                    'schedule_id' => $schedule->id,
                    'student_id' => $student_id,
                    'attendance_date' => $date,
                ],
                [
                    'status' => $status,
                    'session_number' => 1, // Defaulting session number for legacy support
                ]
            );
        }

        // Trigger automatic Grade Synchronization for this class
        $this->syncAttendanceGrades($schedule);

        return redirect()->back()->with('success', 'Đã lưu điểm danh ngày ' . $date . ' và đồng bộ điểm chuyên cần.');
    }

    private function syncAttendanceGrades(Schedule $schedule)
    {
        $subject = $schedule->subject;
        $semester_id = $schedule->semester_id;
        
        // Find relevant Attendance Rule based on credits or just first
        $rule = \App\Models\AttendanceRule::where('credits', $subject->credits)->first() 
             ?? \App\Models\AttendanceRule::first();

        // Get all students in this schedule
        $students = Student::where('classroom_id', $schedule->classroom_id)
            ->whereHas('registrations', function($q) use ($subject) {
                $q->where('subject_id', $subject->id);
            })->get();

        foreach ($students as $student) {
            // Count absences and lates for this student IN THIS CLASS
            $stats = Attendance::where('schedule_id', $schedule->id)
                ->where('student_id', $student->id)
                ->selectRaw('SUM(status = "absent") as absent_count, SUM(status = "late") as late_count')
                ->first();

            $absentCount = $stats->absent_count ?? 0;
            $lateCount = $stats->late_count ?? 0;

            // Calculate Grade (scale 10)
            $deduction = ($absentCount * ($rule->absent_deduction ?? 1.0)) + ($lateCount * ($rule->late_deduction ?? 0.5));
            $attendanceGrade = max(0, 10 - $deduction);

            // Update or Create Grade record
            $grade = \App\Models\Grade::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'subject_id' => $subject->id,
                    'semester_id' => $semester_id,
                ],
                [
                    'academic_year_id' => $schedule->academic_year_id,
                    'attendance' => $attendanceGrade,
                ]
            );

            // Update Total Score and Letter Grade
            $midterm = $grade->midterm ?? 0;
            $final = $grade->final ?? 0;
            $total = ($attendanceGrade * 0.1) + ($midterm * 0.3) + ($final * 0.6);
            
            $letter = 'F';
            if ($total >= 8.5) $letter = 'A';
            elseif ($total >= 7.0) $letter = 'B';
            elseif ($total >= 5.5) $letter = 'C';
            elseif ($total >= 4.0) $letter = 'D';

            $grade->update([
                'total_score' => $total,
                'grade_letter' => $letter
            ]);
        }
    }

    public function grades(Schedule $schedule, Request $request)
    {
        if ($schedule->teacher_id !== Auth::guard('teacher')->id()) {
            abort(403);
        }

        $subject = $schedule->subject;
        $classroom = $schedule->classroom;

        $query = Student::where('classroom_id', $classroom->id)
            ->whereHas('registrations', function($q) use ($subject) {
                $q->where('subject_id', $subject->id);
            });

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('student_code', 'like', "%{$search}%");
            });
        }

        $students = $query->get();

        $existingGrades = \App\Models\Grade::where('subject_id', $subject->id)
            ->where('semester_id', $schedule->semester_id)
            ->get()
            ->keyBy('student_id');

        return view('teacher.grades', compact('schedule', 'students', 'existingGrades', 'subject', 'classroom'));
    }

    public function saveGrades(Request $request, Schedule $schedule)
    {
        if ($schedule->teacher_id !== Auth::guard('teacher')->id()) {
            abort(403);
        }

        $request->validate([
            'grades' => 'required|array',
            'grades.*.midterm' => 'nullable|numeric|min:0|max:10',
            'grades.*.final' => 'nullable|numeric|min:0|max:10',
        ]);

        foreach ($request->grades as $student_id => $data) {
            $midterm = $data['midterm'] ?? 0;
            $final = $data['final'] ?? 0;

            // Fetch existing grade to preserve auto-synced attendance
            $gradeRecord = \App\Models\Grade::where([
                'student_id' => $student_id,
                'subject_id' => $schedule->subject_id,
                'semester_id' => $schedule->semester_id,
            ])->first();

            $attendance = $gradeRecord->attendance ?? 0;

            // Simple weightage: 10% Attendance, 30% Midterm, 60% Final
            $total = ($attendance * 0.1) + ($midterm * 0.3) + ($final * 0.6);
            
            // Letter grade logic
            $letter = 'F';
            if ($total >= 8.5) $letter = 'A';
            elseif ($total >= 7.0) $letter = 'B';
            elseif ($total >= 5.5) $letter = 'C';
            elseif ($total >= 4.0) $letter = 'D';

            \App\Models\Grade::updateOrCreate(
                [
                    'student_id' => $student_id,
                    'subject_id' => $schedule->subject_id,
                    'semester_id' => $schedule->semester_id,
                ],
                [
                    'academic_year_id' => $schedule->academic_year_id,
                    'midterm' => $midterm,
                    'final' => $final,
                    'total_score' => $total,
                    'grade_letter' => $letter,
                ]
            );
        }

        return redirect()->back()->with('success', 'Đã cập nhật điểm thi (Giữa kỳ & Cuối kỳ) thành công.');
    }

    public function profile()
    {
        $teacher = Auth::guard('teacher')->user();
        return view('teacher.profile', compact('teacher'));
    }

    public function updateProfile(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:teachers,email,' . $teacher->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $teacher->name = $request->name;
        $teacher->email = $request->email;
        $teacher->phone = $request->phone;

        if ($request->filled('password')) {
            $teacher->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $teacher->save();

        return redirect()->back()->with('success', 'Cập nhật hồ sơ cá nhân thành công.');
    }
}
