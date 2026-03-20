<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $department_id = $request->query('department_id');
        $classroom_id = $request->query('classroom_id');
        $subject_id = $request->query('subject_id');

        // Step 1: List all departments
        if (!$department_id) {
            $departments = \App\Models\Department::withCount(['classrooms', 'teachers'])->get();
            return view('departments.index', compact('departments'));
        }

        // Step 2: List classrooms in a department
        if (!$classroom_id) {
            $department = \App\Models\Department::with(['classrooms' => function($q) {
                $q->withCount('registrations');
            }])->findOrFail($department_id);
            return view('departments.classrooms', compact('department'));
        }

        // Step 3: List subjects in a classroom
        if (!$subject_id) {
            $classroom = \App\Models\Classroom::with(['department', 'schedules.subject'])->findOrFail($classroom_id);
            $subjects = $classroom->schedules->map->subject->unique('id');
            return view('departments.subjects', compact('classroom', 'subjects'));
        }

        // Step 4: List students registered for a subject in a classroom
        $classroom = \App\Models\Classroom::with('department')->findOrFail($classroom_id);
        $subject = \App\Models\Subject::findOrFail($subject_id);
        
        $schedule = \App\Models\Schedule::where('classroom_id', $classroom_id)
            ->where('subject_id', $subject_id)
            ->first();

        // Get registrations instead of just students to have access to registration IDs for unenrollment
        $registrations = $schedule ? $schedule->registrations()->with('student')->get() : collect();
        $registeredStudentIds = $registrations->pluck('student_id');

        // Available students are those in the classroom but not in the schedule
        $availableStudents = \App\Models\Student::where('classroom_id', $classroom_id)
            ->whereNotIn('id', $registeredStudentIds)
            ->get();

        return view('departments.students', compact('classroom', 'subject', 'registrations', 'schedule', 'availableStudents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('departments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:departments,code',
        ]);

        \App\Models\Department::create($validated);

        return redirect()->route('departments.index')->with('success', 'Khoa đã được thêm thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $department = \App\Models\Department::with(['teachers', 'classrooms'])->findOrFail($id);
        return view('departments.show', compact('department'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $department = \App\Models\Department::findOrFail($id);
        return view('departments.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(\Illuminate\Http\Request $request, string $id)
    {
        $department = \App\Models\Department::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:departments,code,' . $id,
        ]);

        $department->update($validated);

        return redirect()->route('departments.index')->with('success', 'Thông tin khoa đã được cập nhật!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $department = \App\Models\Department::findOrFail($id);
        
        // Optional: Check if department has teachers or classrooms before deleting
        if($department->teachers()->count() > 0 || $department->classrooms()->count() > 0) {
            return redirect()->route('departments.index')->with('error', 'Không thể xóa khoa đang có giảng viên hoặc lớp học!');
        }

        $department->delete();

        return redirect()->route('departments.index')->with('success', 'Khoa đã được xóa!');
    }
}
