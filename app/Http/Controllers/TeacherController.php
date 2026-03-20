<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = \App\Models\Teacher::with('department')->get();
        return view('teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = \App\Models\Department::all();
        return view('teachers.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email',
            'phone' => 'required|string|max:20',
            'department_id' => 'required|exists:departments,id',
        ]);

        \App\Models\Teacher::create($validated);

        return redirect()->route('teachers.index')->with('success', 'Giảng viên đã được thêm thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $teacher = \App\Models\Teacher::with('department')->findOrFail($id);
        return response()->json($teacher);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $teacher = \App\Models\Teacher::findOrFail($id);
        $departments = \App\Models\Department::all();
        return view('teachers.edit', compact('teacher', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(\Illuminate\Http\Request $request, string $id)
    {
        $teacher = \App\Models\Teacher::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email,' . $id,
            'phone' => 'required|string|max:20',
            'department_id' => 'required|exists:departments,id',
        ]);

        $teacher->update($validated);

        return redirect()->route('teachers.index')->with('success', 'Thông tin giảng viên đã được cập nhật!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $teacher = \App\Models\Teacher::findOrFail($id);
        
        if($teacher->schedules()->count() > 0) {
            return redirect()->route('teachers.index')->with('error', 'Không thể xóa giảng viên đang có lịch dạy!');
        }

        $teacher->delete();

        return redirect()->route('teachers.index')->with('success', 'Giảng viên đã được xóa!');
    }
}
