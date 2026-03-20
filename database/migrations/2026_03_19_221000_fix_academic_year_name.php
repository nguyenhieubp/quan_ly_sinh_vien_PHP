<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('academic_years')
            ->where('name', '2024 - 2026')
            ->orWhere('name', '2024-2026')
            ->update(['name' => '2024 - 2025']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('academic_years')
            ->where('name', '2024 - 2025')
            ->update(['name' => '2024 - 2026']);
    }
};
