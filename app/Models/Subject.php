<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';

    // ระบุคอลัมน์ที่สามารถกรอกข้อมูลได้
    protected $fillable = [
        'registration_id', // Foreign key to Registration table
        'subject',         // Subject name
        'semester',    
        'subject_code',    
        'grade',           // Grade
        'teacher',         // Teacher name
    ];

}
