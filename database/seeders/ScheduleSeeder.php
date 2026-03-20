<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Schedule::create([
            'classroom_id' => 1,
            'subject_id' => 1,
            'teacher_id' => 1,
            'day_of_week' => 2, // Monday
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
            'room' => 'A101'
        ]);
    }
}
