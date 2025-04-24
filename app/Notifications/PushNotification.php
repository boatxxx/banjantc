<?php
// app/Notifications/PushNotification.php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\WebPushMessage;

class PushNotification extends Notification
{
    protected $payload;

    /**
     * Create a new notification instance.
     *
     * @param array $payload
     * @return void
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Determine the channels the notification will be sent on.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // การส่ง Notification ผ่าน Web Push
        return ['webpush'];
    }

    /**
     * Get the Web Push representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\WebPushMessage
     */
    public function toWebPush($notifiable)
    {
        return (new WebPushMessage)
            ->title($this->payload['title'])  // กำหนดชื่อหัวข้อ Notification
            ->body($this->payload['body'])    // กำหนดข้อความหลักใน Notification
            ->action('View Details', $this->payload['url']); // กำหนดปุ่มที่ผู้ใช้สามารถคลิกได้
    }

    /**
     * Optional: Customize the push notification's options, such as its icon.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => $this->payload['body'],
            'url' => $this->payload['url']
        ];
    }
}


