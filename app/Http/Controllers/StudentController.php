<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $department_id = $request->query('department_id');
        $classroom_id = $request->query('classroom_id');

        // Level 0: List all Departments
        if (!$department_id && !$classroom_id) {
            $departments = \App\Models\Department::withCount('classrooms')->get();
            return view('students.index', compact('departments'));
        }

        // Level 1: List Classrooms in a specific Department
        if ($department_id && !$classroom_id) {
            $department = \App\Models\Department::findOrFail($department_id);
            $classrooms = \App\Models\Classroom::where('department_id', $department_id)
                ->withCount('students')
                ->get();
            return view('students.index', compact('department', 'classrooms'));
        }

        // Level 2: List Students in a specific Classroom
        $classroom = \App\Models\Classroom::with('department')->findOrFail($classroom_id);
        $students = \App\Models\Student::where('classroom_id', $classroom_id)
            ->with([
                'registrations.schedule.subject',
                'registrations.schedule.teacher',
                'registrations.schedule.semester',
                'registrations.schedule.academicYear'
            ])->get();
        
        $semesters = \App\Models\Semester::all();
        $academicYears = \App\Models\AcademicYear::getRelevantYears();
        
        return view('students.index', compact('classroom', 'students', 'semesters', 'academicYears'));
    }

    public function create()
    {
        $departments = \App\Models\Department::all();
        $classrooms = \App\Models\Classroom::all();
        return view('students.create', compact('departments', 'classrooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'student_code' => 'required|string|unique:students,student_code',
            'classroom_id' => 'required|exists:classrooms,id',
        ]);

        \App\Models\Student::create($validated);

        return redirect()->route('students.index')->with('success', 'Sinh viên đã được thêm thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student = \App\Models\Student::with('classroom')->findOrFail($id);
        return response()->json($student);
    }

    public function edit(string $id)
    {
        $student = \App\Models\Student::with('classroom.department')->findOrFail($id);
        $departments = \App\Models\Department::all();
        $classrooms = \App\Models\Classroom::all();
        return view('students.edit', compact('student', 'departments', 'classrooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(\Illuminate\Http\Request $request, string $id)
    {
        $student = \App\Models\Student::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $id,
            'student_code' => 'required|string|unique:students,student_code,' . $id,
            'classroom_id' => 'required|exists:classrooms,id',
        ]);

        $student->update($validated);

        return redirect()->route('students.index')->with('success', 'Thông tin sinh viên đã được cập nhật!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = \App\Models\Student::findOrFail($id);
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Sinh viên đã được xóa!');
    }
}
