<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRule;
use Illuminate\Http\Request;

class AttendanceRuleController extends Controller
{
    public function index()
    {
        $rules = AttendanceRule::orderBy('credits')->get();
        return view('attendance_rules.index', compact('rules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'credits' => 'required|integer|min:1',
            'max_absent' => 'required|integer|min:0',
            'max_late' => 'required|integer|min:0',
            'absent_deduction' => 'required|numeric|min:0',
            'late_deduction' => 'required|numeric|min:0',
        ]);

        AttendanceRule::updateOrCreate(
            ['credits' => $request->credits],
            $request->only(['max_absent', 'max_late', 'absent_deduction', 'late_deduction'])
        );

        return redirect()->back()->with('success', 'Cấu hình đã được lưu thành công.');
    }
}
