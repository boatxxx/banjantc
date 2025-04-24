<?php

namespace App\Services;
use GuzzleHttp\Client;

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use Illuminate\Support\Facades\Log;
use PhpMqtt\Client\Exceptions\MqttClientException;

class MQTTService
{
    protected $fcmServerKey;

    public function __construct()
    {
        // ใช้ Server Key ของ Firebase
        $this->fcmServerKey = env('FCM_SERVER_KEY'); // กำหนดค่าใน .env
    }

    public function sendPushNotification($subscription, $title, $body)
    {
        $client = new Client();

        // ข้อมูลการแจ้งเตือน
        $response = $client->post('https://fcm.googleapis.com/fcm/send', [
            'json' => [
                'to' => $subscription['endpoint'],  // ใช้ endpoint ที่ได้รับจากการสมัคร
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                    'icon' => '/icon.png',  // ไอคอนของการแจ้งเตือน
                ],
                'data' => [
                    'extraData' => 'ข้อมูลเพิ่มเติม' // ข้อมูลเสริมใน notification
                ]
            ],
            'headers' => [
                'Authorization' => 'key=' . $this->fcmServerKey,  // ใช้ Server Key จาก Firebase Console
                'Content-Type' => 'application/json',
            ]
        ]);

        // ตรวจสอบผลลัพธ์
        return $response->getBody();
    }
}
