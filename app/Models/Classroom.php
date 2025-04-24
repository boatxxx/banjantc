<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;
protected $fillable = ['grade', 'teacher_id', /* คอลัมน์อื่นๆ */];
public function teacher()
{
    return $this->belongsTo(Teacher::class, 'teacher_id');
}
}
