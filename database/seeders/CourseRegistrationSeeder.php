<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CourseRegistration;
use App\Models\Student;
use App\Models\Schedule;

class CourseRegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $student = Student::where('student_code', 'SV001')->first();
        $schedules = Schedule::all();

        if ($student && $schedules->count() > 0) {
            foreach ($schedules as $schedule) {
                CourseRegistration::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'schedule_id' => $schedule->id,
                    ],
                    [
                        'subject_id' => $schedule->subject_id,
                        'semester_id' => $schedule->semester_id,
                        'status' => 'Approved'
                    ]
                );
            }
        }
    }
}
