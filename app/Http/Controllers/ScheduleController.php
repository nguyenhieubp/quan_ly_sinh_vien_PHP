<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(\Illuminate\Http\Request $request)
    {
        $department_id = $request->query('department_id');
        $classroom_id = $request->query('classroom_id');

        if (!$department_id) {
            $departments = \App\Models\Department::withCount('classrooms')->get();
            return view('schedules.index', compact('departments'));
        }

        if (!$classroom_id) {
            $department = \App\Models\Department::with(['classrooms' => function($q) {
                $q->withCount('schedules');
            }])->findOrFail($department_id);
            return view('schedules.index', compact('department'));
        }

        $classroom = \App\Models\Classroom::with(['department', 'schedules.subject', 'schedules.teacher'])->findOrFail($classroom_id);
        $schedules = $classroom->schedules->groupBy('subject_id');

        return view('schedules.index', compact('classroom', 'schedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classrooms = \App\Models\Classroom::all();
        $subjects = \App\Models\Subject::all();
        $teachers = \App\Models\Teacher::all();
        return view('schedules.create', compact('classrooms', 'subjects', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required',
            'end_time' => 'required',
            'room' => 'required|string|max:50',
        ]);

        \App\Models\Schedule::create($validated);

        return redirect()->route('schedules.index')->with('success', 'Lịch học đã được sắp xếp thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $schedule = \App\Models\Schedule::with(['classroom', 'subject', 'teacher'])->findOrFail($id);
        return response()->json($schedule);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $schedule = \App\Models\Schedule::findOrFail($id);
        $classrooms = \App\Models\Classroom::all();
        $subjects = \App\Models\Subject::all();
        $teachers = \App\Models\Teacher::all();
        return view('schedules.edit', compact('schedule', 'classrooms', 'subjects', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(\Illuminate\Http\Request $request, string $id)
    {
        $schedule = \App\Models\Schedule::findOrFail($id);
        
        $validated = $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required',
            'end_time' => 'required',
            'room' => 'required|string|max:50',
        ]);

        $schedule->update($validated);

        return redirect()->route('schedules.index')->with('success', 'Lịch học đã được cập nhật!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $schedule = \App\Models\Schedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('schedules.index')->with('success', 'Lịch học đã được xóa!');
    }
}
