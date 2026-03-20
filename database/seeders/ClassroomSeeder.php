<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Classroom::create([
            'department_id' => 1,
            'name' => 'Công nghệ thông tin 01',
            'code' => 'CNTT01'
        ]);
        \App\Models\Classroom::create([
            'department_id' => 1,
            'name' => 'Công nghệ thông tin 02',
            'code' => 'CNTT02'
        ]);
    }
}
