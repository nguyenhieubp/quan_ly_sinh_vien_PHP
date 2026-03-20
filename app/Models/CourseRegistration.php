<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseRegistration extends Model
{
    protected $fillable = ['student_id', 'subject_id', 'schedule_id', 'semester_id', 'status'];

    public function student() { return $this->belongsTo(Student::class); }
    public function subject() { return $this->belongsTo(Subject::class); }
    public function schedule() { return $this->belongsTo(Schedule::class); }
    public function semester() { return $this->belongsTo(Semester::class); }
}
