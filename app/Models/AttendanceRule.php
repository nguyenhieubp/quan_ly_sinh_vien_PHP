<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceRule extends Model
{
    protected $fillable = ['credits', 'max_absent', 'max_late', 'absent_deduction', 'late_deduction'];
}
