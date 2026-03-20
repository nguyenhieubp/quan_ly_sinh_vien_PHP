<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $classroom_id = $request->query('classroom_id');
        $subject_id = $request->query('subject_id');

        // Step 1: List all classrooms
        if (!$classroom_id) {
            $classrooms = \App\Models\Classroom::with(['department'])->withCount('students')->get();
            return view('classrooms.index', compact('classrooms'));
        }

        // Step 2: List subjects for a specific classroom
        if (!$subject_id) {
            $classroom = \App\Models\Classroom::with(['department', 'schedules.subject'])->findOrFail($classroom_id);
            // Get unique subjects from classroom schedules
            $subjects = $classroom->schedules->map->subject->unique('id');
            return view('classrooms.subjects', compact('classroom', 'subjects'));
        }

        // Step 3: List students registered for a specific subject in this classroom
        $classroom = \App\Models\Classroom::findOrFail($classroom_id);
        $subject = \App\Models\Subject::findOrFail($subject_id);

        $schedule = \App\Models\Schedule::where('classroom_id', $classroom_id)
            ->where('subject_id', $subject_id)
            ->first();

        $date = $request->query('date', now()->toDateString());

        // Get registrations instead of just students to have access to registration IDs for unenrollment
        $registrations = $schedule ? $schedule->registrations()->with('student')->get() : collect();
        $registeredStudentIds = $registrations->pluck('student_id');

        // Fetch existing attendance for this schedule and date
        $attendanceData = [];
        if ($schedule) {
            $attendanceData = \App\Models\Attendance::where('schedule_id', $schedule->id)
                ->where('attendance_date', $date)
                ->pluck('status', 'student_id')
                ->toArray();
        }

        // Available students are those in the classroom but not in the schedule
        $availableStudents = \App\Models\Student::where('classroom_id', $classroom_id)
            ->whereNotIn('id', $registeredStudentIds)
            ->get();

        return view('classrooms.students', compact('classroom', 'subject', 'registrations', 'schedule', 'availableStudents', 'date', 'attendanceData'));
    }

    /**
     * Save attendance for a specific date and schedule
     */
    public function saveAttendance(\Illuminate\Http\Request $request, string $schedule_id)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*' => 'required|in:present,absent,late',
            'notes' => 'nullable|array',
        ]);

        foreach ($validated['attendance'] as $studentId => $status) {
            \App\Models\Attendance::updateOrCreate(
                [
                    'schedule_id' => $schedule_id,
                    'student_id' => $studentId,
                    'attendance_date' => $validated['date'],
                ],
                [
                    'status' => $status,
                    'notes' => $validated['notes'][$studentId] ?? null,
                ]
            );
        }

        return redirect()->back()->with('success', 'Đã lưu điểm danh ngày ' . $validated['date'] . ' thành công!');
    }
    /**
     * Enroll a student into a specific schedule
     */
    public function enrollStudent(\Illuminate\Http\Request $request, string $schedule_id)
    {
        $schedule = \App\Models\Schedule::findOrFail($schedule_id);
        
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $activeSemester = \App\Models\Semester::where('is_active', true)->first();

        $allSchedules = \App\Models\Schedule::where('classroom_id', $schedule->classroom_id)
            ->where('subject_id', $schedule->subject_id)
            ->get();

        foreach ($allSchedules as $s) {
            \App\Models\CourseRegistration::firstOrCreate([
                'student_id' => $validated['student_id'],
                'schedule_id' => $s->id,
                'subject_id' => $s->subject_id,
                'semester_id' => $activeSemester ? $activeSemester->id : null,
            ]);
        }
        
        return redirect()->back()->with('success', 'Đã ghi danh sinh viên vào môn học!');
    }

    /**
     * Unenroll a student (Delete the registration record)
     */
    public function unenrollStudent(string $registration_id)
    {
        $reg = \App\Models\CourseRegistration::findOrFail($registration_id);
        
        // Remove all registrations for this student in all sessions of this subject/classroom
        \App\Models\CourseRegistration::where('student_id', $reg->student_id)
            ->where('subject_id', $reg->subject_id)
            ->whereHas('schedule', function($q) use ($reg) {
                $q->where('classroom_id', $reg->schedule->classroom_id);
            })
            ->delete();
        
        return redirect()->back()->with('success', 'Đã gỡ sinh viên khỏi môn học!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = \App\Models\Department::all();
        return view('classrooms.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:classrooms,code',
            'department_id' => 'required|exists:departments,id',
        ]);

        \App\Models\Classroom::create($validated);

        return redirect()->route('classrooms.index')->with('success', 'Lớp học đã được thêm thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $classroom = \App\Models\Classroom::with(['department'])->withCount('registrations')->findOrFail($id);
        return response()->json($classroom);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $classroom = \App\Models\Classroom::with(['schedules.subject', 'schedules.teacher'])->findOrFail($id);
        
        // Get schedule items with full details
        $subjects = $classroom->schedules->map(function($schedule) {
            return [
                'id' => $schedule->subject->id,
                'code' => $schedule->subject->code,
                'name' => $schedule->subject->name,
                'credits' => $schedule->subject->credits,
                'teacher' => $schedule->teacher->name,
                'semester' => $schedule->semester ? $schedule->semester->name : 'N/A',
                'academic_year' => $schedule->academicYear ? $schedule->academicYear->name : 'N/A',
                'day_of_week' => $this->getDayDisplay($schedule->day_of_week),
                'periods' => $schedule->start_period . ' - ' . $schedule->end_period,
                'room' => $schedule->room,
                'schedule_id' => $schedule->id,
                'student_count' => $schedule->registrations()->count(),
            ];
        });

        $allSubjects = \App\Models\Subject::where('department_id', $classroom->department_id)->get();
        $allTeachers = \App\Models\Teacher::where('department_id', $classroom->department_id)->get();
        $departments = \App\Models\Department::all();
        $semesters = \App\Models\Semester::all();
        $academicYears = \App\Models\AcademicYear::getRelevantYears();
        
        return view('classrooms.edit', compact('classroom', 'departments', 'subjects', 'allSubjects', 'allTeachers', 'semesters', 'academicYears'));
    }

    public function showAssignSubject(string $id)
    {
        $classroom = \App\Models\Classroom::findOrFail($id);
        $allSubjects = \App\Models\Subject::where('department_id', $classroom->department_id)->get();
        $allTeachers = \App\Models\Teacher::where('department_id', $classroom->department_id)->get();
        $semesters = \App\Models\Semester::all();
        $academicYears = \App\Models\AcademicYear::getRelevantYears();

        return view('classrooms.assign_subject', compact('classroom', 'allSubjects', 'allTeachers', 'semesters', 'academicYears'));
    }

    public function editSubjectConfiguration(string $id, string $subject_id)
    {
        $classroom = \App\Models\Classroom::findOrFail($id);
        $subject = \App\Models\Subject::findOrFail($subject_id);
        
        $schedules = \App\Models\Schedule::where('classroom_id', $id)
            ->where('subject_id', $subject_id)
            ->get();
            
        if ($schedules->isEmpty()) {
            return redirect()->route('classrooms.edit', $id)->with('error', 'Không tìm thấy cấu hình cho môn học này.');
        }
        
        $firstSchedule = $schedules->first();
        $allTeachers = \App\Models\Teacher::where('department_id', $classroom->department_id)->get();
        $semesters = \App\Models\Semester::all();
        $academicYears = \App\Models\AcademicYear::getRelevantYears();

        return view('classrooms.edit_subject_configuration', compact('classroom', 'subject', 'schedules', 'firstSchedule', 'allTeachers', 'semesters', 'academicYears'));
    }

    public function updateSubjectConfiguration(\Illuminate\Http\Request $request, string $id, string $subject_id)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'semester_id' => 'required|exists:semesters,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'total_periods' => 'required|integer|min:1',
            'sessions' => 'required|array|min:1',
            'sessions.*.day_of_week' => 'required|integer|between:0,6',
            'sessions.*.start_period' => 'required|integer|min:1|max:15',
            'sessions.*.end_period' => 'required|integer|min:1|max:15|gte:sessions.*.start_period',
            'sessions.*.start_time' => 'required',
            'sessions.*.end_time' => 'required',
            'sessions.*.room' => 'required|string|max:50',
        ]);

        // Automatically enroll ALL students belonging to this classroom
        $studentIds = \App\Models\Student::where('classroom_id', $id)->pluck('id');

        // Delete old schedules
        \App\Models\Schedule::where('classroom_id', $id)->where('subject_id', $subject_id)->delete();

        // Create new schedules
        foreach ($validated['sessions'] as $sessionData) {
            $newSchedule = \App\Models\Schedule::create([
                'classroom_id' => $id,
                'subject_id' => $subject_id,
                'teacher_id' => $validated['teacher_id'],
                'semester_id' => $validated['semester_id'],
                'academic_year_id' => $validated['academic_year_id'],
                'total_periods' => $validated['total_periods'],
                'day_of_week' => $sessionData['day_of_week'],
                'start_period' => $sessionData['start_period'],
                'end_period' => $sessionData['end_period'],
                'start_time' => $sessionData['start_time'],
                'end_time' => $sessionData['end_time'],
                'room' => $sessionData['room'],
            ]);

            // Sync registrations for each new session slot for ALL classroom students
            foreach ($studentIds as $sid) {
                \App\Models\CourseRegistration::firstOrCreate([
                    'student_id' => $sid,
                    'schedule_id' => $newSchedule->id,
                    'subject_id' => $subject_id,
                    'semester_id' => $validated['semester_id'],
                ]);
            }
        }

        return redirect()->route('classrooms.index', ['classroom_id' => $id])->with('success', 'Đã cập nhật cấu hình môn học và đồng bộ sĩ số thành công!');
    }

    /**
     * Assign a subject to a classroom (Create a Schedule record)
     */
    public function assignSubject(\Illuminate\Http\Request $request, string $id)
    {
        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'semester_id' => 'required|exists:semesters,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'total_periods' => 'required|integer|min:1',
            'sessions' => 'required|array|min:1',
            'sessions.*.day_of_week' => 'required|integer|between:0,6',
            'sessions.*.start_period' => 'required|integer|min:1|max:15',
            'sessions.*.end_period' => 'required|integer|min:1|max:15|gte:sessions.*.start_period',
            'sessions.*.start_time' => 'required',
            'sessions.*.end_time' => 'required',
            'sessions.*.room' => 'required|string|max:50',
        ]);

        // Automatically enroll ALL students belonging to this classroom
        $studentIds = \App\Models\Student::where('classroom_id', $id)->pluck('id');

        foreach ($validated['sessions'] as $sessionData) {
            $newSchedule = \App\Models\Schedule::create([
                'classroom_id' => $id,
                'subject_id' => $validated['subject_id'],
                'teacher_id' => $validated['teacher_id'],
                'semester_id' => $validated['semester_id'],
                'academic_year_id' => $validated['academic_year_id'],
                'total_periods' => $validated['total_periods'],
                'day_of_week' => $sessionData['day_of_week'],
                'start_period' => $sessionData['start_period'],
                'end_period' => $sessionData['end_period'],
                'start_time' => $sessionData['start_time'],
                'end_time' => $sessionData['end_time'],
                'room' => $sessionData['room'],
            ]);

            // Create registrations for ALL classroom students
            foreach ($studentIds as $sid) {
                \App\Models\CourseRegistration::firstOrCreate([
                    'student_id' => $sid,
                    'schedule_id' => $newSchedule->id,
                    'subject_id' => $validated['subject_id'],
                    'semester_id' => $validated['semester_id'],
                ]);
            }
        }

        return redirect()->route('classrooms.edit', $id)->with('success', 'Môn học đã được cấu hình và tự động gán cho ' . $studentIds->count() . ' sinh viên!');
    }
    /**
     * Preview schedule sessions
     */
    public function previewSchedule(Request $request)
    {
        $semesterId = $request->query('semester_id');
        $totalPeriods = (int)$request->query('total_periods');
        $sessionSlots = $request->query('sessions', []);

        $semester = \App\Models\Semester::find($semesterId);
        if (!$semester || !$semester->start_date) {
            return response()->json(['error' => 'Vui lòng cấu hình ngày bắt đầu cho học kỳ này.'], 400);
        }

        if (empty($sessionSlots)) {
            return response()->json(['error' => 'Vui lòng thêm ít nhất một buổi học.'], 400);
        }

        $periodsPerWeek = 0;
        foreach ($sessionSlots as $slot) {
            $periodsPerWeek += (int)$slot['end_period'] - (int)$slot['start_period'] + 1;
        }

        if ($periodsPerWeek <= 0) {
            return response()->json(['error' => 'Tổng số tiết/tuần không hợp lệ.'], 400);
        }

        $totalSessionsNeeded = ceil($totalPeriods / $periodsPerWeek) * count($sessionSlots);
        $sessions = [];
        
        $startDate = \Carbon\Carbon::parse($semester->start_date);
        
        // We generate sessions week by week until totalPeriods is exhausted
        $currentTotalPeriods = 0;
        $weekOffset = 0;
        
        while ($currentTotalPeriods < totalPeriods) {
            $weeklySessions = [];
            foreach ($sessionSlots as $slot) {
                $dayOfWeek = (int)$slot['day_of_week'];
                $s_period = (int)$slot['start_period'];
                $e_period = (int)$slot['end_period'];
                $p_count = $e_period - $s_period + 1;

                $sessionDate = $startDate->copy()->addWeeks($weekOffset);
                while ($sessionDate->dayOfWeek !== $dayOfWeek) {
                    $sessionDate->addDay();
                }

                $weeklySessions[] = [
                    'date_obj' => $sessionDate,
                    'date' => $sessionDate->format('d/m/Y'),
                    'day' => $this->getDayDisplay($dayOfWeek),
                    'periods' => "$s_period - $e_period",
                    'p_count' => $p_count
                ];
            }

            // Sort weekly sessions by date to keep global timeline chronological
            usort($weeklySessions, function($a, $b) {
                return $a['date_obj']->timestamp <=> $b['date_obj']->timestamp;
            });

            foreach ($weeklySessions as $s) {
                if ($currentTotalPeriods >= $totalPeriods) break;
                
                $sessions[] = [
                    'index' => count($sessions) + 1,
                    'date' => $s['date'],
                    'day' => $s['day'],
                    'periods' => $s['periods']
                ];
                $currentTotalPeriods += $s['p_count'];
            }
            
            $weekOffset++;
            
            // Safety break to prevent infinite loop
            if ($weekOffset > 52) break;
        }

        return response()->json([
            'sessions' => $sessions,
            'periodsPerWeek' => $periodsPerWeek,
            'totalSessions' => count($sessions)
        ]);
    }

    private function getDayDisplay($day)
    {
        $days = [
            0 => 'Chủ nhật',
            1 => 'Thứ 2',
            2 => 'Thứ 3',
            3 => 'Thứ 4',
            4 => 'Thứ 5',
            5 => 'Thứ 6',
            6 => 'Thứ 7',
        ];
        return $days[$day] ?? '';
    }

    /**
     * Remove a subject from a classroom (Delete the Schedule record)
     */
    public function removeSubject(string $id, string $schedule_id)
    {
        $schedule = \App\Models\Schedule::where('classroom_id', $id)->findOrFail($schedule_id);
        $schedule->delete();

        return redirect()->back()->with('success', 'Đã gỡ môn học khỏi lớp!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(\Illuminate\Http\Request $request, string $id)
    {
        $classroom = \App\Models\Classroom::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:classrooms,code,' . $id,
            'department_id' => 'required|exists:departments,id',
        ]);

        $classroom->update($validated);

        return redirect()->route('classrooms.index')->with('success', 'Thông tin lớp học đã được cập nhật!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $classroom = \App\Models\Classroom::findOrFail($id);
        
        // Use the new relationship logic to check for registrations in any subject of this class
        if($classroom->registrations()->count() > 0) {
            return redirect()->route('classrooms.index')->with('error', 'Không thể xóa lớp học đang có sinh viên đăng ký các môn học!');
        }

        $classroom->delete();

        return redirect()->route('classrooms.index')->with('success', 'Lớp học đã được xóa!');
    }
}
