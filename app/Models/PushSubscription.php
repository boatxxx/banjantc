<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PushSubscription extends Model
{
    protected $fillable = [
        'endpoint',
        'keys_p256dh',
        'keys_auth',
        'classroom_id',
    ];
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
    }