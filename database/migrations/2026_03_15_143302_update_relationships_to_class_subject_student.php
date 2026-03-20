<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('course_registrations', function (Blueprint $table) {
            $table->foreignId('schedule_id')->nullable()->after('student_id')->constrained()->onDelete('cascade');
        });

        Schema::table('grades', function (Blueprint $table) {
            $table->foreignId('schedule_id')->nullable()->after('student_id')->constrained()->onDelete('cascade');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('classroom_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_registrations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('schedule_id');
        });

        Schema::table('grades', function (Blueprint $table) {
            $table->dropConstrainedForeignId('schedule_id');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('classroom_id')->nullable(false)->change();
        });
    }
};
