<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id', // Foreign key to Registration table
        'name',        // Add semester here
        'last_name',       
        'subject_code',    // Subject name
        'grade',           // Grade
        'teacher',   
            'level', // ✅ เพิ่มตรงนี้
      // Teacher name
    ];

    // ความสัมพันธ์กับ Registration
    public function registration()
    {
        return $this->belongsTo(Registration::class); // Subject เชื่อมกับ Registration
    }
    public function classroom()
{
    return $this->belongsTo(Classroom::class, 'grade', 'grade');
}

}
