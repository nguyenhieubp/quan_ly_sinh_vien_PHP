<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'student_id',
        'subject_id',
        'semester_id',
        'academic_year_id',
        'midterm',
        'final',
        'attendance',
        'total_score',
        'grade_letter'
    ];

    public function student() { return $this->belongsTo(Student::class); }
    public function subject() { return $this->belongsTo(Subject::class); }
    public function semester() { return $this->belongsTo(Semester::class); }
    public function academicYear() { return $this->belongsTo(AcademicYear::class); }
}
