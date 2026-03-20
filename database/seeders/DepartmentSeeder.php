<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Department::create(['name' => 'Công nghệ thông tin', 'code' => 'CNTT']);
        \App\Models\Department::create(['name' => 'Kinh tế', 'code' => 'KT']);
        \App\Models\Department::create(['name' => 'Điện tử viễn thông', 'code' => 'DTVT']);
    }
}
