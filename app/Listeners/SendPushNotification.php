<?php

namespace App\Listeners;

use App\Events\NotificationCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Notification; // Import model
use App\Notifications\AttendanceNotification;

use App\Models\PushSubscription;
use App\Models\Classroom;
class SendPushNotification
{


}
