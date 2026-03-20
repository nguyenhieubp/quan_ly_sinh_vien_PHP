<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;
use App\Models\Semester;
use App\Models\AcademicYear;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first academic year (2025 - 2026) and active semester
        $academicYear = AcademicYear::first() ?? AcademicYear::create(['name' => '2025 - 2026']);
        $semester = Semester::where('is_active', true)->first() ?? Semester::first();

        Schedule::create([
            'classroom_id' => 1,
            'subject_id' => 1,
            'teacher_id' => 1,
            'semester_id' => $semester->id,
            'academic_year_id' => $academicYear->id,
            'day_of_week' => 2, // Monday
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
            'room' => 'A101',
            'total_periods' => 3,
            'start_period' => 1,
            'end_period' => 3
        ]);

        Schedule::create([
            'classroom_id' => 1,
            'subject_id' => 2,
            'teacher_id' => 1,
            'semester_id' => $semester->id,
            'academic_year_id' => $academicYear->id,
            'day_of_week' => 4, // Wednesday
            'start_time' => '13:00:00',
            'end_time' => '15:00:00',
            'room' => 'B202',
            'total_periods' => 3,
            'start_period' => 7,
            'end_period' => 9
        ]);
    }
}
