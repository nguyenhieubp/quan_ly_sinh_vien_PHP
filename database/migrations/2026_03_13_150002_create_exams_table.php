<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $规划) {
            $规划->id();
            $规划->foreignId('subject_id')->constrained()->onDelete('cascade');
            $规划->foreignId('semester_id')->constrained()->onDelete('cascade');
            $规划->dateTime('exam_date');
            $规划->string('room');
            $规划->enum('exam_type', ['Midterm', 'Final', 'Retake'])->default('Final');
            $规划->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
