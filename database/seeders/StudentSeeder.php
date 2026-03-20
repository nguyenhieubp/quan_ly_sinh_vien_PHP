<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Student::create([
            'classroom_id' => 1,
            'name' => 'Lê Văn C',
            'email' => 'lvc@example.com',
            'student_code' => 'SV001',
            'password' => Hash::make('123456')
        ]);
        \App\Models\Student::create([
            'classroom_id' => 1,
            'name' => 'Phạm Thị D',
            'email' => 'ptd@example.com',
            'student_code' => 'SV002',
            'password' => Hash::make('123456')
        ]);
    }
}
