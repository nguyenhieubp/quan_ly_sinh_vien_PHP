<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicYear;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ensure academic years are generated first
        AcademicYear::getRelevantYears();

        $this->call([
            AdminSeeder::class,
            AcademicSeeder::class,
            DepartmentSeeder::class,
            TeacherSeeder::class,
            ClassroomSeeder::class,
            StudentSeeder::class,
            SubjectSeeder::class,
            ScheduleSeeder::class,
            CourseRegistrationSeeder::class,
            GradeSeeder::class,
            AttendanceRuleSeeder::class,
        ]);
    }
}
