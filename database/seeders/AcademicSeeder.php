<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Semester;

class AcademicSeeder extends Seeder
{
    public function run(): void
    {
        Semester::create(['name' => 'Học kỳ 1 2023-2024', 'is_active' => false]);
        Semester::create(['name' => 'Học kỳ 2 2023-2024', 'is_active' => true]);
        Semester::create(['name' => 'Học kỳ Hè 2024', 'is_active' => false]);
    }
}
