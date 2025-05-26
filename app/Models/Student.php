<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_id', // Foreign key to Registration table
        'semester',        // Add semester here
        'subject',       
        'subject_code',    // Subject name
        'grade',           // Grade
        'teacher',         // Teacher name
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
