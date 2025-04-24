<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'activity_id',
        'grade',
        'acticar_name',
        'time',
        'status',
        'statts_id',
        'lecturer_id',
    ];

}
