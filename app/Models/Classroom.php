<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $fillable = ['department_id', 'name', 'code'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Get all registrations for this classroom through its schedules.
     * This replaces the direct student relationship.
     */
    public function registrations()
    {
        return $this->hasManyThrough(
            CourseRegistration::class,
            Schedule::class,
            'classroom_id', // Foreign key on schedules table
            'schedule_id',   // Foreign key on course_registrations table
            'id',            // Local key on classrooms table
            'id'             // Local key on schedules table
        );
    }

    /**
     * Get all students belonging to this classroom.
     */
    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
