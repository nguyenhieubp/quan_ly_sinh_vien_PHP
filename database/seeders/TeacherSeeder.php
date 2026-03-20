<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Teacher::create([
            'department_id' => 1,
            'name' => 'Nguyễn Văn A',
            'email' => 'nva@example.com',
            'phone' => '0901234567'
        ]);
        \App\Models\Teacher::create([
            'department_id' => 1,
            'name' => 'Trần Thị B',
            'email' => 'ttb@example.com',
            'phone' => '0907654321'
        ]);
    }
}
