<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $规划) {
            $规划->id();
            $规划->foreignId('student_id')->constrained()->onDelete('cascade');
            $规划->foreignId('subject_id')->constrained()->onDelete('cascade');
            $规划->foreignId('semester_id')->constrained()->onDelete('cascade');
            $规划->decimal('midterm', 4, 1)->nullable();
            $规划->decimal('final', 4, 1)->nullable();
            $规划->decimal('attendance', 4, 1)->nullable();
            $规划->decimal('total_score', 4, 1)->nullable();
            $规划->string('grade_letter', 2)->nullable();
            $规划->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
