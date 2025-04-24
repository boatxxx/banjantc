<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{
    protected $table = 'user_notifications';

    // กำหนด fields ที่อนุญาตให้กรอกข้อมูล (mass assignable)
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'is_read',
    ];

    // สร้างความสัมพันธ์กับโมเดล User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
