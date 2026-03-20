<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('semesters', function (Blueprint $table) {
            $table->date('start_date')->nullable()->after('is_active');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->integer('total_periods')->default(45)->after('academic_year_id');
            $table->integer('start_period')->nullable()->after('total_periods');
            $table->integer('end_period')->nullable()->after('start_period');
        });
    }

    public function down(): void
    {
        Schema::table('semesters', function (Blueprint $table) {
            $table->dropColumn('start_date');
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn(['total_periods', 'start_period', 'end_period']);
        });
    }
};
