<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Salman\Mqtt\MqttClass\Mqtt;
use App\Services\MQTTService;
use Illuminate\Support\Facades\Http;

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
class MqttController extends Controller
{
    public function sendPushNotification($deviceToken, $title, $message)
    {
        // ดึงค่า Server Key จาก config
        $serverKey = config('services.fcm.server_key');
        
        // กำหนดข้อมูลการแจ้งเตือน
        $data = [
            'to' => $deviceToken,  // Device token ที่คุณต้องการส่งแจ้งเตือน
            'notification' => [
                'title' => $title,    // หัวข้อ
                'body' => $message,   // ข้อความ
                'sound' => 'default'  // เสียงแจ้งเตือน
            ]
        ];

        // ส่งคำขอ POST ไปยัง Firebase Cloud Messaging API
        $response = Http::withHeaders([
            'Authorization' => 'key=' . $serverKey,
            'Content-Type' => 'application/json'
        ])->post('https://fcm.googleapis.com/fcm/send', $data);

        // ตรวจสอบผลลัพธ์
        if ($response->successful()) {
            return response()->json(['status' => 'success', 'message' => 'Notification sent successfully']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to send notification']);
        }
    }

}
