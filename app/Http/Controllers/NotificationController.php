<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\NewNotification;
use App\Models\Notification; // Import model
use App\Models\Classroom;
use Pusher\Pusher;
use App\Models\UserNotification; // Import model
use App\Models\User;
use Illuminate\Support\Facades\Log; // ✅ ใช้ Log เพื่อตรวจสอบ
use App\Models\ParentNotification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;
use App\Models\PushSubscription;


class NotificationController extends Controller
{

    public function store(Request $request)
    {
        $parentId = $request->input('parent_id'); // หรือสามารถรับจาก $request->parent_id ได้
        $token = Str::random(60); // ตัวอย่างการสร้างโทเค็น

        // บันทึกข้อมูลการแจ้งเตือน
        ParentNotification::create([
            'parent_id' => $parentId,  // ใช้ parent_id ที่มาจากคำขอ
            'token' => $token,
            'room_id' => $request->room_id,  // รหัสห้องที่เลือก
        ]);

        return response()->json(['token' => $token, 'message' => 'เปิดแจ้งเตือนสำเร็จ']);
    }
    public function index()
    {
        // ดึงข้อมูลการแจ้งเตือนจากฐานข้อมูลหรือแหล่งข้อมูลอื่น ๆ
       // ดึงห้องเรียนทั้งหมดจากฐานข้อมูล
       $classrooms = Classroom::all();

       // ส่งข้อมูลห้องเรียนไปยัง view
       return view('notifications.index', compact('classrooms'));
    }
    
    public function toggle(Request $request)
    {
        Log::info("📌 Request Data:", $request->all());

        if (!$request->has('classroom_id')) {
            Log::error("❌ classroom_id is missing!");
            return response()->json(['error' => '❌ classroom_id is required'], 400);
        }
    
        $classroomId = $request->input('classroom_id');
        Log::info("📌 Received classroom_id: " . $classroomId);
    
        try {
            $isEnabled = request()->cookie('notification_enabled') === 'true';
            $newStatus = !$isEnabled;
            $cookie = cookie('notification_enabled', $newStatus ? 'true' : 'false', 60);
    
            return response()->json([
                'status' => $newStatus ? 'enabled' : 'disabled'
            ])->cookie($cookie);
        } catch (\Exception $e) {
            Log::error("❌ Error: " . $e->getMessage());
            return response()->json(['error' => '❌ Internal Server Error'], 500);
        }
    }
    public function deleteOldSubscription($classroomId)
    {
        // ลบโทเค็นเก่าที่เชื่อมกับห้องเรียน
        PushSubscription::where('classroom_id', $classroomId)->delete();
        
        return response()->json(['message' => 'Old subscription deleted']);
    }    
    
    public function show(Classroom $classroom)
    {
        $notifications = Notification::where('classroom_id', $classroom->id)
        ->orderBy('created_at', 'desc') // เรียงลำดับจากใหม่ไปเก่า
        ->limit(15) // ดึงมาแค่ 15 รายการล่าสุด
        ->get();
        return view('notifications.show', compact('classroom', 'notifications'));
    }
    public function saveSubscription(Request $request)
    {
        $validated = $request->validate([
            'subscription.endpoint' => 'required|string',
            'subscription.keys.p256dh' => 'required|string',
            'subscription.keys.auth' => 'required|string',
            'classroom_id' => 'required|integer',

        ]);

        // บันทึกข้อมูล subscription ลงฐานข้อมูล
        $subscription = new PushSubscription();
        $subscription->endpoint = $validated['subscription']['endpoint'];
        $subscription->keys_p256dh = $validated['subscription']['keys']['p256dh'];
        $subscription->keys_auth = $validated['subscription']['keys']['auth'];
        $subscription->classroom_id = $validated['classroom_id'];  // เพิ่ม classroom_id ที่ได้รับมาจาก request

        // คุณสามารถเพิ่มฟิลด์อื่นๆ เช่น user_id หรือ classroom_id ถ้าจำเป็น
        $subscription->save();

        return response()->json(['status' => 'Subscription saved successfully!']);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'classroom_id' => 'required|integer',
            'subscription.endpoint' => 'required|string',
            'subscription.keys.p256dh' => 'required|string',
            'subscription.keys.auth' => 'required|string',
        ]);

        // หา subscription จาก endpoint
        $subscription = PushSubscription::where('endpoint', $data['subscription']['endpoint'])->first();

        if ($subscription) {
            // เจอ subscription เดิม > แค่อัปเดต classroom_id
            $subscription->classroom_id = $data['classroom_id'];
            $subscription->save();
        } else {
            // ถ้ายังไม่เคยมี subscription นี้ > สร้างใหม่
            PushSubscription::create([
                'endpoint' => $data['subscription']['endpoint'],
                'keys_p256dh' => $data['subscription']['keys']['p256dh'],
                'keys_auth' => $data['subscription']['keys']['auth'],
                'classroom_id' => $data['classroom_id'],
            ]);
        }

        return response()->json(['message' => 'Subscription updated successfully']);
    }
      
    public function changeClassroom(Request $request)
    {
        $request->validate([
            'endpoint' => 'required|string',
            'classroom_id' => 'required|integer',
        ]);
    
        $subscription = PushSubscription::where('endpoint', $request->endpoint)->first();
    
        if (!$subscription) {
            return response()->json(['success' => false, 'message' => 'ไม่พบข้อมูล subscription'], 404);
        }
    
        $subscription->classroom_id = $request->classroom_id;
        $subscription->save();
    
        return response()->json(['success' => true]);
    }
    


}
