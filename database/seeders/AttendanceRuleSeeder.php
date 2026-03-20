<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendanceRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\AttendanceRule::updateOrCreate(['credits' => 1], [
            'max_absent' => 1,
            'max_late' => 0,
            'absent_deduction' => 1.0,
            'late_deduction' => 0.5
        ]);

        \App\Models\AttendanceRule::updateOrCreate(['credits' => 2], [
            'max_absent' => 2,
            'max_late' => 1,
            'absent_deduction' => 1.0,
            'late_deduction' => 0.5
        ]);

        \App\Models\AttendanceRule::updateOrCreate(['credits' => 3], [
            'max_absent' => 3,
            'max_late' => 1,
            'absent_deduction' => 1.0,
            'late_deduction' => 0.5
        ]);
        
        \App\Models\AttendanceRule::updateOrCreate(['credits' => 4], [
            'max_absent' => 4,
            'max_late' => 2,
            'absent_deduction' => 1.0,
            'late_deduction' => 0.5
        ]);
    }
}
