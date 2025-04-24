<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;
    protected $fillable = [
        // ฟิลด์อื่น ๆ
        'activity_id',
        'level'
    ];
    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }

    // ความสัมพันธ์กับ Student
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    // ความสัมพันธ์กับ Lecturer (อาจจะเป็น optional)
    public function lecturer()
    {
        return $this->belongsTo(Teacher::class, 'lecturer_id');
    }
}
