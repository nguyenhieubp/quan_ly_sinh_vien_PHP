<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cntt = \App\Models\Department::where('code', 'CNTT')->first() ?? \App\Models\Department::first();
        
        if ($cntt) {
            \App\Models\Subject::updateOrCreate(
                ['code' => 'WEB01'],
                ['name' => 'Lập trình Web', 'credits' => 3, 'department_id' => $cntt->id]
            );
            \App\Models\Subject::updateOrCreate(
                ['code' => 'DB01'],
                ['name' => 'Cơ sở dữ liệu', 'credits' => 3, 'department_id' => $cntt->id]
            );
            \App\Models\Subject::updateOrCreate(
                ['code' => 'AI01'],
                ['name' => 'Trí tuệ nhân tạo', 'credits' => 4, 'department_id' => $cntt->id]
            );
        }
    }
}
