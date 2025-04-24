<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentNotification extends Model
{
    protected $table = 'parent_notifications';
    protected $fillable = ['parent_id', 'token', 'room_id'];
    
}
