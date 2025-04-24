<?php

namespace App\Models;
use App\Events\NotificationCreated;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    
    protected $table = 'notifications'; // ชื่อตารางในฐานข้อมูล

    protected $fillable = [
        'classroom_id', // รหัสห้องเรียน
        'title',        // หัวข้อแจ้งเตือน
        'message',      // เนื้อหาแจ้งเตือน
        'is_read',      // สถานะการอ่าน
    ];

    // ความสัมพันธ์กับตาราง Classroom
    public function classroom()
    {
        return $this->belongsTo(Classroom::class, 'classroom_id');
    }
    protected static function booted()
    {
        static::created(function ($notification) {
            event(new NotificationCreated($notification));  // Trigger Event เมื่อมีการบันทึกข้อมูลใหม่
        });
    }
}
