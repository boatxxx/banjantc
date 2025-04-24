<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParentNotification;

class FCMController extends Controller
{
    public function storeToken(Request $request)
    {
        try {

        $request->validate([
            'token' => 'required|string',
            'room_id' => 'required|integer',
        ]);

        // สร้าง Parent Notification ใหม่
        ParentNotification::create([
            'token' => $request->input('token'),
            'room_id' => $request->input('room_id'),
        ]);
        return response()->json(['message' => 'Token saved successfully.']);

    } catch (\Exception $e) {
        return response()->json(['error' => 'เกิดข้อผิดพลาดที่เซิร์ฟเวอร์'], 500);
    }
    
}
}
