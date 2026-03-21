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
        \App\Models\Teacher::updateOrCreate(
            ['email' => 'nva@example.com'],
            [
                'department_id' => 1,
                'name' => 'Nguyễn Văn A',
                'phone' => '0901234567',
                'password' => bcrypt('123456')
            ]
        );
        \App\Models\Teacher::updateOrCreate(
            ['email' => 'ttb@example.com'],
            [
                'department_id' => 1,
                'name' => 'Trần Thị B',
                'phone' => '0907654321',
                'password' => bcrypt('123456')
            ]
        );
    }
}
