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
        Schema::create('attendance_rules', function (Blueprint $table) {
            $table->id();
            $table->integer('credits')->unique();
            $table->integer('max_absent')->default(3);
            $table->integer('max_late')->default(1);
            $table->decimal('absent_deduction', 4, 2)->default(1.0);
            $table->decimal('late_deduction', 4, 2)->default(0.5);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_rules');
    }
};
