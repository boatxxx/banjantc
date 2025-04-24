<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{

    // ระบุชื่อของตาราง
    protected $table = 'registrations';

    // ระบุคอลัมน์ที่สามารถกรอกข้อมูลได้
    protected $fillable = [
        'fullname',
        'academicYear',
        'semester',
        'level',
        'courseType',
        'major',
        'academicYear',
        'registerDate',
        'receipt',
    ];

    // ความสัมพันธ์กับ Subjects
    public function subjects()
    {
        return $this->hasMany(Subject::class); // Registration มีหลาย Subject
    }}
