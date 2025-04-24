<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
protected $fillable = ['lecturer', /* คอลัมน์อื่นๆ */];
public function classrooms()
{
    return $this->hasMany(Classroom::class, 'teacher_id');
}
}
