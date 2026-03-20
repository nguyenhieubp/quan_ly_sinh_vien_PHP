<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'classroom_id', // Now nullable via migration
        'name', 
        'email', 
        'student_code'
    ];

    public function getFullNameAttribute()
    {
        return $this->name;
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function registrations()
    {
        return $this->hasMany(CourseRegistration::class);
    }
}
