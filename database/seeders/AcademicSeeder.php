<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Semester;
use App\Models\AcademicYear;

class AcademicSeeder extends Seeder
{
    public function run(): void
    {
        $year2526 = AcademicYear::where('name', '2025 - 2026')->first() ?? AcademicYear::create(['name' => '2025 - 2026']);
        $year2627 = AcademicYear::where('name', '2026 - 2027')->first() ?? AcademicYear::create(['name' => '2026 - 2027']);

        Semester::create([
            'name' => 'Học kỳ 1 2025-2026', 
            'is_active' => false,
            'start_date' => '2025-09-08',
            'academic_year_id' => $year2526->id
        ]);
        Semester::create([
            'name' => 'Học kỳ 2 2025-2026', 
            'is_active' => true,
            'start_date' => '2026-02-16',
            'academic_year_id' => $year2526->id
        ]);
        Semester::create([
            'name' => 'Học kỳ Hè 2026', 
            'is_active' => false,
            'start_date' => '2026-07-06',
            'academic_year_id' => $year2526->id
        ]);

        Semester::create([
            'name' => 'Học kỳ 1 2026-2027', 
            'is_active' => false,
            'start_date' => '2026-09-07',
            'academic_year_id' => $year2627->id
        ]);
    }
}
