<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Semester;
use App\Models\AcademicYear;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $student = Student::where('student_code', 'SV001')->first();
        if (!$student) return;

        $semester1 = Semester::where('name', 'Học kỳ 1 2025-2026')->first();
        $semester2 = Semester::where('name', 'Học kỳ 2 2025-2026')->first();
        $ay2526 = AcademicYear::where('name', '2025 - 2026')->first();

        $subjects = Subject::all();

        if ($semester1 && $ay2526 && $subjects->count() > 0) {
            Grade::updateOrCreate(
                ['student_id' => $student->id, 'subject_id' => $subjects[0]->id, 'semester_id' => $semester1->id],
                [
                    'academic_year_id' => $ay2526->id,
                    'midterm' => 8.5,
                    'final' => 9.0,
                    'attendance' => 10,
                    'total_score' => 9.1,
                    'grade_letter' => 'A'
                ]
            );

            if ($subjects->count() > 1) {
                Grade::updateOrCreate(
                    ['student_id' => $student->id, 'subject_id' => $subjects[1]->id, 'semester_id' => $semester1->id],
                    [
                        'academic_year_id' => $ay2526->id,
                        'midterm' => 7.0,
                        'final' => 7.5,
                        'attendance' => 9,
                        'total_score' => 7.6,
                        'grade_letter' => 'B'
                    ]
                );
            }
        }
    }
}
