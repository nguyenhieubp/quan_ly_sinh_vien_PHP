<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $department_id = $request->query('department_id');
        $classroom_id = $request->query('classroom_id');

        if (!$department_id) {
            $departments = \App\Models\Department::withCount('classrooms')->get();
            return view('grades.index', compact('departments'));
        }

        if (!$classroom_id) {
            $department = \App\Models\Department::with(['classrooms' => function($q) {
                $q->withCount('registrations');
            }])->findOrFail($department_id);
            return view('grades.classrooms', compact('department'));
        }

        $classroom = \App\Models\Classroom::with(['department', 'schedules.subject'])->findOrFail($classroom_id);
        
        // Get unique subjects from classroom schedules
        $subjects = $classroom->schedules->map->subject->unique('id');

        return view('grades.subjects', compact('classroom', 'subjects'));
    }

    public function create()
    {
        $classrooms = \App\Models\Classroom::all();
        $subjects = \App\Models\Subject::all();
        $semesters = \App\Models\Semester::all();
        return view('grades.create', compact('classrooms', 'subjects', 'semesters'));
    }
    public function enterBulk(Request $request)
    {
        $classroom_id = $request->query('classroom_id');
        $subject_id = $request->query('subject_id');
        $semester_id = $request->query('semester_id');

        if (!$classroom_id) {
            return redirect()->route('grades.index')->with('error', 'Vui lòng chọn lớp học.');
        }

        if (!$subject_id) {
            $classroom = \App\Models\Classroom::findOrFail($classroom_id);
            return redirect()->route('grades.index', [
                'department_id' => $classroom->department_id, 
                'classroom_id' => $classroom_id
            ])->with('error', 'Vui lòng chọn môn học.');
        }

        $classroom = \App\Models\Classroom::findOrFail($classroom_id);
        $subjects = \App\Models\Subject::all();
        $semesters = \App\Models\Semester::all();

        $students = collect();
        $existingGrades = collect();
        $calculatedAttendanceScores = []; // Format: [student_id => score]
        $subject = \App\Models\Subject::findOrFail($subject_id);
        $semester = null;

        if ($semester_id) {
            $semester = \App\Models\Semester::findOrFail($semester_id);
            
            // Find schedule for this context
            $schedule = \App\Models\Schedule::where('classroom_id', $classroom_id)
                ->where('subject_id', $subject_id)
                ->first();

            if ($schedule) {
                // Get registered students
                $students = \App\Models\Student::whereIn('id', function($query) use ($schedule, $semester_id) {
                    $query->select('student_id')
                        ->from('course_registrations')
                        ->where('schedule_id', $schedule->id)
                        ->where('semester_id', $semester_id);
                })->get();

                $existingGrades = \App\Models\Grade::where('subject_id', $subject_id)
                    ->where('semester_id', $semester_id)
                    ->get()
                    ->keyBy('student_id');

                // Fetch Attendance Rule
                $attendanceRule = \App\Models\AttendanceRule::where('credits', $subject->credits)->first()
                    ?? new \App\Models\AttendanceRule([
                        'absent_deduction' => 1.0, 
                        'late_deduction' => 0.5
                    ]);

                // Calculate current attendance scores
                foreach ($students as $student) {
                    $absences = \App\Models\Attendance::where('schedule_id', $schedule->id)
                        ->where('student_id', $student->id)
                        ->where('status', 'absent')
                        ->count();
                    
                    $lates = \App\Models\Attendance::where('schedule_id', $schedule->id)
                        ->where('student_id', $student->id)
                        ->where('status', 'late')
                        ->count();

                    $score = 10 - ($absences * $attendanceRule->absent_deduction) - ($lates * $attendanceRule->late_deduction);
                    $calculatedAttendanceScores[$student->id] = max(0, round($score, 1));
                }
            }
        }

        return view('grades.bulk', compact('classroom', 'subjects', 'semesters', 'subject', 'semester', 'students', 'existingGrades', 'calculatedAttendanceScores'));
    }

    public function storeBulk(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'semester_id' => 'required|exists:semesters,id',
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:students,id',
            'grades.*.midterm' => 'nullable|numeric|min:0|max:10',
            'grades.*.final' => 'nullable|numeric|min:0|max:10',
            'grades.*.attendance' => 'nullable|numeric|min:0|max:10',
        ]);

        foreach ($request->grades as $data) {
            $mid = $data['midterm'] ?? 0;
            $fin = $data['final'] ?? 0;
            $att = $data['attendance'] ?? 0;
            
            $total = ($att * 0.1) + ($mid * 0.3) + ($fin * 0.6);
            $grade_letter = 'F';
            if ($total >= 8.5) $grade_letter = 'A';
            elseif ($total >= 7.0) $grade_letter = 'B';
            elseif ($total >= 5.5) $grade_letter = 'C';
            elseif ($total >= 4.0) $grade_letter = 'D';

            \App\Models\Grade::updateOrCreate(
                [
                    'student_id' => $data['student_id'], 
                    'subject_id' => $request->subject_id, 
                    'semester_id' => $request->semester_id
                ],
                [
                    'midterm' => $data['midterm'],
                    'final' => $data['final'],
                    'attendance' => $data['attendance'],
                    'total_score' => round($total, 1),
                    'grade_letter' => $grade_letter
                ]
            );
        }

        return redirect()->route('grades.index')->with('success', 'Đã cập nhật bảng điểm thành công!');
    }

    public function destroy(string $id)
    {
        $grade = \App\Models\Grade::findOrFail($id);
        $grade->delete();
        return redirect()->route('grades.index')->with('success', 'Đã xóa bản ghi điểm!');
    }
}
