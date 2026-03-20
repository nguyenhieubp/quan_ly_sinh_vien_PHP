<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_registrations', function (Blueprint $规划) {
            $规划->id();
            $规划->foreignId('student_id')->constrained()->onDelete('cascade');
            $规划->foreignId('subject_id')->constrained()->onDelete('cascade');
            $规划->foreignId('semester_id')->constrained()->onDelete('cascade');
            $规划->enum('status', ['Pending', 'Approved', 'Rejected'])->default('Pending');
            $规划->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_registrations');
    }
};
