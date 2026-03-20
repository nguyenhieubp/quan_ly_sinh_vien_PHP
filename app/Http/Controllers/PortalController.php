<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PortalController extends Controller
{
    public function dashboard($student_id)
    {
        $student = \App\Models\Student::with(['classroom'])->findOrFail($student_id);
        $grades = \App\Models\Grade::where('student_id', $student_id)->get();
        
        $gpa = $grades->avg('total_score') / 2.5; // Simple scale 10 to 4.0
        $credits = \App\Models\Grade::where('student_id', $student_id)
                    ->where('grade_letter', '!=', 'F')
                    ->join('subjects', 'grades.subject_id', '=', 'subjects.id')
                    ->sum('subjects.credits');

        $schedules = \App\Models\Schedule::where('classroom_id', $student->classroom_id)
                    ->with(['subject', 'teacher'])
                    ->get();

        return view('portal.dashboard', compact('student', 'gpa', 'credits', 'schedules'));
    }

    public function grades($student_id)
    {
        $student = \App\Models\Student::findOrFail($student_id);
        $grades = \App\Models\Grade::where('student_id', $student_id)
                    ->with(['subject', 'semester'])
                    ->orderBy('semester_id')
                    ->get();
        return view('portal.grades', compact('student', 'grades'));
    }

    public function schedule($student_id)
    {
        $student = \App\Models\Student::findOrFail($student_id);
        $schedules = \App\Models\Schedule::where('classroom_id', $student->classroom_id)
                    ->with(['subject', 'teacher'])
                    ->get();
        return view('portal.schedule', compact('student', 'schedules'));
    }

    public function registration($student_id)
    {
        $student = \App\Models\Student::findOrFail($student_id);
        
        // Show schedules (subjects in classrooms) instead of just raw subjects
        $schedules = \App\Models\Schedule::with(['subject', 'teacher', 'classroom'])->get();
        
        $registrations = \App\Models\CourseRegistration::where('student_id', $student_id)->with('schedule.subject')->get();
        return view('portal.registration', compact('student', 'schedules', 'registrations'));
    }

    public function store_registration(Request $request, $student_id)
    {
        $validated = $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
        ]);

        $schedule = \App\Models\Schedule::findOrFail($validated['schedule_id']);

        // Get active semester
        $semester = \App\Models\Semester::where('is_active', true)->first();
        if (!$semester) {
             return back()->with('error', 'Hiện không có học kỳ nào đang mở để đăng ký.');
        }

        // Check for duplicate registration
        $exists = \App\Models\CourseRegistration::where('student_id', $student_id)
            ->where('schedule_id', $schedule->id)
            ->where('semester_id', $semester->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Bạn đã đăng ký môn học này trong học kỳ này rồi.');
        }

        $allSchedules = \App\Models\Schedule::where('classroom_id', $schedule->classroom_id)
            ->where('subject_id', $schedule->subject_id)
            ->get();

        foreach ($allSchedules as $s) {
            \App\Models\CourseRegistration::firstOrCreate([
                'student_id' => $student_id,
                'subject_id' => $s->subject_id,
                'schedule_id' => $s->id,
                'semester_id' => $semester->id,
                'status' => 'Pending'
            ]);
        }
        
        return back()->with('success', 'Đăng ký môn học thành công! Vui lòng chờ giáo vụ phê duyệt.');
    }
}
