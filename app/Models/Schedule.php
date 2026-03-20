<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'classroom_id', 
        'subject_id', 
        'teacher_id', 
        'semester_id',
        'academic_year_id',
        'total_periods',
        'start_period',
        'end_period',
        'day_of_week', 
        'start_time', 
        'end_time', 
        'room'
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function registrations()
    {
        return $this->hasMany(CourseRegistration::class);
    }

    public function students()
    {
        return $this->hasManyThrough(Student::class, CourseRegistration::class, 'schedule_id', 'id', 'id', 'student_id');
    }
}
