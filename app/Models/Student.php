<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'student_code',
        'name',
        'email',
        'phone',
        'address',
        'classroom_id',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function registrations()
    {
        return $this->hasMany(CourseRegistration::class);
    }
}
