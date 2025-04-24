<?php

namespace App\Http\Controllers;
use App\Models\ParentNotification;

use Illuminate\Http\Request;

class ParentNotificationController extends Controller
{
    public function store(Request $request)
    {
        // วาลิเดตข้อมูลที่รับมา
        $request->validate([
            'parent_id' => 'required|integer',
            'token' => 'required|string',
            'room_id' => 'required|integer',
            'classroom_id' => 'required|integer',

        ]);

        // สร้าง Parent Notification ใหม่
        ParentNotification::create([
            'parent_id' => $request->input('parent_id'),
            'token' => $request->input('token'),
            'room_id' => $request->input('room_id'),
        ]);

        // ส่งกลับไปยังหน้าอื่น ๆ หรือหน้าแสดงผลสำเร็จ
        return redirect()->back()->with('success', 'บันทึกข้อมูลสำเร็จแล้ว');
    }
}
