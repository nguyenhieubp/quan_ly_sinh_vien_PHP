<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $department_id = $request->query('department_id');

        if ($department_id) {
            $department = \App\Models\Department::findOrFail($department_id);
            $subjects = $department->subjects()->get();
            return view('subjects.index', compact('subjects', 'department'));
        }

        $departments = \App\Models\Department::withCount('subjects')->get();
        return view('subjects.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $department_id = $request->query('department_id');
        $departments = \App\Models\Department::all();
        return view('subjects.create', compact('departments', 'department_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:subjects,code',
            'credits' => 'required|integer|min:1',
            'department_id' => 'required|exists:departments,id',
        ]);

        \App\Models\Subject::create($validated);

        return redirect()->route('subjects.index', ['department_id' => $validated['department_id']])
            ->with('success', 'Môn học đã được thêm thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $subject = \App\Models\Subject::with('schedules')->findOrFail($id);
        return response()->json($subject);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $subject = \App\Models\Subject::findOrFail($id);
        $departments = \App\Models\Department::all();
        return view('subjects.edit', compact('subject', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(\Illuminate\Http\Request $request, string $id)
    {
        $subject = \App\Models\Subject::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:subjects,code,' . $id,
            'credits' => 'required|integer|min:1',
            'department_id' => 'required|exists:departments,id',
        ]);

        $subject->update($validated);

        return redirect()->route('subjects.index', ['department_id' => $validated['department_id']])
            ->with('success', 'Thông tin môn học đã được cập nhật!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subject = \App\Models\Subject::findOrFail($id);
        $dept_id = $subject->department_id;
        
        if($subject->schedules()->count() > 0) {
            return redirect()->back()->with('error', 'Không thể xóa môn học đang có trong lịch giảng dạy!');
        }

        $subject->delete();

        return redirect()->route('subjects.index', ['department_id' => $dept_id])
            ->with('success', 'Môn học đã được xóa!');
    }
}
