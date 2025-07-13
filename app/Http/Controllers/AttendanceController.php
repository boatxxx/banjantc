<?php

namespace App\Http\Controllers;
use Carbon\Carbon;

use Illuminate\Http\Request;
use App\Models\AttendanceRecord;
use App\Models\Student;
use App\Models\Activity;
use App\Models\Teacher;
use App\Models\Classroom;
use GuzzleHttp\Client;
use App\Models\Level;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;


use App\Models\Notification;
use App\Models\UserNotification;
use App\Events\NewNotification;
use App\Models\User;
use App\Models\ParentNotification;
use App\Notifications\AttendanceNotification;
use App\Events\ClassAttendanceRecorded; // เพิ่มบรรทัดนี้
use Illuminate\Support\Facades\Http;
use App\Services\MQTTService;
use Illuminate\Support\Facades\Log;


class AttendanceController extends Controller
{

    public function importExcel2(Request $request)
    {
        $data = Excel::toArray(new ExcelImportController, $request->file('file'));

        // วน loop ผ่านแต่ละคอลัมน์
        foreach ($data[0] as $column) {
            // $column[0] คือค่าในแถวแรกของคอลัมน์นั้น ๆ
            // ใช้เงื่อนไขตามต้องการ
            if ($column[1] !== null) {
                // สร้างข้อมูลใน activities
                Activity::create([
                    'activity' => $column[0],
                    'level' => $column[1], // มอบหมาย level
                ]);
            }
        }


        return redirect()->back()->with('success', 'Import successful!');
    }



    public function welcome()
    {

        return view('welcome');

    }
    public function index(Request $request, $mode = null) 
{
    $level = $request->input('level'); // รับค่า level ที่ส่งมาจาก URL
    $mode = $request->input('mode', $mode);

    if ($level == 6) {
        // แสดงทุกห้องเรียนเมื่อค่าที่ส่งมาเป็น 6
        $classrooms = Classroom::all();
    } else {
        // ดึงข้อมูลห้องเรียนที่มี level ตรงกับค่าที่ส่งมา
        $classrooms = Classroom::where('level', $level)->get();
    }

    if ($mode == '007') {
        // ถ้า mode เป็น 007 ให้แสดงเฉพาะกิจกรรมที่มี id = 282
        $activities = Activity::where('id', 282)->get();
    } else {
        // ดึงข้อมูลกิจกรรมที่มี level ตรงกับค่าที่ส่งมา
        $activities = Activity::where('level', $level)->get();
    }

    $teachers = Teacher::all();

    return view('attendance.index', compact('classrooms', 'activities', 'teachers', 'mode'));
}

    
    public function index3()
    {

        $classrooms = Classroom::all();

        $activities = Activity::all();

        $teachers = Teacher::all();


        return view('report', compact('classrooms', 'activities', 'teachers'));
    }
    public function index1(Request $request)
    {
        $request->validate([
            'classroom' => 'required|exists:classrooms,id',
            'activity' => 'required|exists:activities,id',
            'lecturer' => 'required|exists:teachers,id',
     
        ]);
        $mode = $request->input('mode'); // รับค่าจาก request

        $classroomId = $request->input('classroom');
        $activityId = $request->input('activity');
        $lecturerId = $request->input('lecturer');
        $classroom = Classroom::find($classroomId);
        $activity = Activity::find($activityId);
        $lecturer = Teacher::find($lecturerId);
        $students = Student::where('grade', $classroomId)->get();

        return view('attendance.index1', compact('classroomId','mode','activityId', 'lecturerId', 'students', 'classroom', 'activity', 'lecturer'));

    }
    public function port(Request $request)
    {
        $request->validate([
            'classroom' => 'required|exists:classrooms,id',
            'activity' => 'required|exists:activities,id',
            'lecturer' => 'required|exists:teachers,id',
        ]);
  		$startDate = $request->input('start_date');
    	$endDate = $request->input('end_date');
        $classroomId = $request->input('classroom');
        $activityId = $request->input('activity');
        $lecturerId = $request->input('lecturer');

        $classroom = Classroom::find($classroomId);
        $activity = Activity::find($activityId);
        $lecturer = Teacher::find($lecturerId);
        $students = Student::where('grade', $classroomId)->get();
        $attendanceRecords = AttendanceRecord::where('activity_id', $activityId)
                                          ->where('grade', $classroomId)
                                          ->where('lecturer_id', $lecturerId)
                                          ->whereBetween('time', [$startDate, $endDate]) // แทน 'date_column' ด้วยชื่อคอลัมน์วันที่ในฐานข้อมูลของคุณ
                                          ->get();

// เก็บ student_ids ทั้งหมดไว้ใน array
$studentIds = $attendanceRecords->pluck('student_id');

// ดึงข้อมูลของนักเรียนจากฐานข้อมูล
$students = Student::whereIn('id', $studentIds)->get();

// วนลูปเพื่อเพิ่มชื่อและนามสกุลของนักเรียนลงในข้อมูลการเข้าร่วม
foreach ($attendanceRecords as $record) {
    $student = $students->where('id', $record->student_id)->first();
    $record->name = $student->name;
    $record->last_name = $student->last_name;
}

            return view('port', compact('classroomId', 'activityId', 'lecturerId', 'students', 'classroom', 'activity', 'lecturer', 'attendanceRecords', 'startDate', 'endDate'));

    }

      public function reportAttendance(Request $request)
    {
        // ดึงข้อมูลกิจกรรมทั้งหมด
        $activities = Activity::all();
    
        // เตรียมข้อมูลสรุป
        $reportData = [];
    
        // รับค่ากิจกรรมและวันที่จากฟอร์ม
        $activityId = $request->input('activity');
        $selectedDate = $request->input('date');
    
        // ตรวจสอบว่ามีการเลือกกิจกรรมและวันที่หรือไม่
        if ($activityId && $selectedDate) {
            // ดึงข้อมูลห้องเรียนทั้งหมด
            $classrooms = Classroom::with('teacher')
    ->whereNotBetween('id', [27, 32]) // กรองห้องช่วง 27-32
    ->where(function ($query) {
        $query->whereNotBetween('id', [35, 57]) // กรองเฉพาะ 35-53
              ->orWhere('id', 54);              // แสดงห้อง 54 ได้
    })
    ->get();
            // สร้างรายงานสำหรับแต่ละห้องเรียน
            foreach ($classrooms as $classroom) {
                // ดึงจำนวนนักเรียนทั้งหมดในห้อง
                $totalStudents = Student::where('grade', $classroom->id)->count();
    
                // ดึงข้อมูลการเข้าเรียนจาก AttendanceRecord
           $attendanceRecords = AttendanceRecord::select('attendance_records.*')
    ->join(DB::raw("(SELECT MAX(id) as latest_id
                    FROM attendance_records
                    WHERE activity_id = $activityId
                      AND DATE(time) = '$selectedDate'
                      AND grade = {$classroom->id}
                    GROUP BY student_id) as latest_records"), 
        'attendance_records.id', '=', 'latest_records.latest_id')
    ->get();
    
                // นับจำนวนที่มาเรียน
                $presentCount = $attendanceRecords->where('status', 'มา')->count();
                $lateCount = $attendanceRecords->where('status', 'สาย')->count();
                $absentCount = $attendanceRecords->where('status', 'ขาด')->count();
                $leaveCount = $attendanceRecords->where('status', 'ลา')->count();
    
                // เพิ่มข้อมูลเข้า array
                $reportData[] = [
                    'classroom' => $classroom->grade,
                    'teacher' => $classroom->teacher->lecturer ?? 'ไม่มีข้อมูล',
                    'total_students' => $totalStudents,
                    'present' => $presentCount,
                    'late' => $lateCount,
                    'absent' => $absentCount,
                    'leave' => $leaveCount,
                ];
            }
        }

        // ส่งข้อมูลไปยัง Blade view เพื่อแสดงรายงาน
        return view('attendance.report', compact('reportData', 'activities','selectedDate'));
    }
    
    
   
    public function select(Request $request)
    {
        // ดึงข้อมูลห้องเรียนและกิจกรรมทั้งหมด (หรือกรองตามเงื่อนไขที่ต้องการ)
        $classrooms = Classroom::all();
        $activities = Activity::all();
    
        return view('report.select', compact('classrooms', 'activities'));
    }
    
    public function submitAttendance(Request $request)
    {
        try {

        $mode = $request->input('mode'); // รับค่าจาก request
        $classrooms = Classroom::all();
        $activities = Activity::all();
        $teachers = Teacher::all();
        $students = $request->students;
        $classroomId = $request->input('classroomId');
        $activityId = $request->input('activityId');
        $lecturerId = $request->input('lecturerId');
        $studentNames = $request->input('studentName');
        $studentLastNames = $request->input('studentLastName');
        $studentLevels = $request->input('studentLevel');
        $studentID = $request->input('studentID');
        $roomId = $request->input('classroomId'); // ห้องที่บันทึกการเข้าเรียน
    
        // ตรวจสอบว่ามีข้อมูลห้องเรียนหรือกิจกรรมหรืออาจารย์ที่เลือกมาหรือไม่
        $classroom = Classroom::find($classroomId);
        $activity = Activity::find($activityId);
        $lecturer = Teacher::find($lecturerId);
    
        // ตรวจสอบว่ามีข้อมูลหรือไม่
        if (!$classroom || !$activity || !$lecturer) {
            return view('welcome')->with('error', 'ข้อมูลไม่ถูกต้อง กรุณาตรวจสอบอีกครั้ง');
        }
    
        // กำหนดค่าเริ่มต้นของการนับสถานะการเข้าเรียน
        $attendanceCounts = [
            'มา' => 0,
            'สาย' => 0,
            'ขาด' => 0,
            'ลา' => 0,
            'รถรับส่ง' => 0,
        ];
    
        $absentStudents = [];
        $leaveStudents = [];
        $attendanceRecords = []; // เก็บข้อมูลสำหรับ insert แบบครั้งเดียว
    
        foreach ($studentNames as $index => $name) {
            $attendance = $request->input('attendance' . $index);
            $studentFullName = $name . ' ' . $studentLastNames[$index];
    
            // ตรวจสอบว่ามีการเลือกสถานะหรือไม่
    
            // เตรียมข้อมูลสำหรับบันทึกการเข้าเรียน
            $attendanceRecords[] = [
                'activity_id'   => $activityId,
                'student_id'    => $studentID[$index],
                'grade'         => $classroomId,
                'time'          => ($mode === '007') ? now()->subDay() : now(),
                'status'        => $attendance,
                'lecturer_id'   => $lecturerId,
                'created_at'    => now(),
                'updated_at'    => now(),
            ];
    
            // นับจำนวนตามสถานะ
            if (isset($attendanceCounts[$attendance])) {
                $attendanceCounts[$attendance]++;
            }
    
            // แยกรายชื่อของนักเรียนที่ขาดและลา
            if ($attendance == 'ขาด') {
                $absentStudents[] = $studentFullName;
            } elseif ($attendance == 'ลา') {
                $leaveStudents[] = $studentFullName;
            } elseif ($attendance == 'รถรับส่ง') {
                $transportStudents[] = $studentFullName;
            }
        }
    
        // ทำการ insert ข้อมูลการเข้าเรียนทั้งหมดในครั้งเดียว
        AttendanceRecord::insert($attendanceRecords);
    
        // สร้างข้อความแจ้งเตือน
        $message = "\n ศูนย์เทคโนโลยีบ้านจั่น:\n";
        $message .= "📌 วันที่ " . (($mode === '007') ? now()->subDay() : now())->format('d/m/Y') . "\n";
        $message .= "ระดับชั้น:  $classroom->grade \n";
        $message .= "วิชา: $activity->activity\n";
        $message .= "อาจารย์ผู้สอน: $lecturer->lecturer\n";
        $message .= "📢 นักเรียนทั้งหมด: " . count($studentNames) . " คน\n";
        $message .= "✅ มาเรียน: " . $attendanceCounts['มา'] . " คน\n";
        $message .= "⏰ มาสาย: " . $attendanceCounts['สาย'] . " คน\n";
        $message .= "⏰ รถรับส่ง: " . $attendanceCounts['รถรับส่ง'] . " คน\n";
        $message .= "❌ ขาดเรียน: " . $attendanceCounts['ขาด'] . " คน\n";
        $message .= "🏖 ลา: " . $attendanceCounts['ลา'] . " คน\n";
        $message .= "📊 รายชื่อนักเรียน (สาย, ขาด, ลา) มีดังนี้:\n";
    
        if (!empty($leaveStudents)) {
            $message .= "🏖 ลา:\n";
            foreach ($leaveStudents as $leaveStudent) {
                $message .= "- $leaveStudent\n";
            }
        }
    
        if (!empty($absentStudents)) {
            $message .= "❌ ขาด:\n";
            foreach ($absentStudents as $absentStudent) {
                $message .= "- $absentStudent\n";
            }
        }
        if (!empty($transportStudents)) {
            $message .= "✅ รถรับส่ง:\n";
            foreach ($transportStudents as $absentStudent) {
                $message .= "- รถรับส่ง $absentStudent\n";
            }
        }
        // บันทึกข้อมูลการแจ้งเตือนลงในฐานข้อมูล
        $notification = Notification::create([
            'classroom_id' => $classroomId,
            'title' => "แจ้งเตือนการเข้าเรียน - " . $activity->activity,
            'message' => $message,
        ]);
 // **ส่งแจ้งเตือนไปยัง MQTT (Topic ตามห้องเรียน)**
$topic = "classroom/{$classroomId}";
$messageData = json_encode([
    'title' => 'แจ้งเตือนการเข้าเรียน',
    'message' => $message
]);



return redirect()->route('record')->with('success', 'บันทึกข้อมูลสำเร็จแล้ว');
} catch (\Exception $e) {

    return redirect()->route('record')->with('error', 'ส่งข้อมูลไม่สำเร็จ กรุณาทำการส่งใหม่และกรอกข้อมูลให้ครบถ้วน');
}
    }
    protected $mqttService;

    public function __construct(MQTTService $mqttService)
    {
        $this->mqttService = $mqttService;
    }

    public function sendNotification()
    {
        $topic = "classroom/1";
        $message = json_encode([
            'title' => 'แจ้งเตือนใหม่',
            'message' => 'มีการอัปเดตในห้องเรียนของคุณ'
        ]);


        return response()->json(['status' => 'Message sent']);
    }
    
    private function sendPushNotification($tokens, $title, $body)
{
    $url = 'https://fcm.googleapis.com/fcm/send';
    $serverKey = 'BKPzXydtmxwqdXeEYMFGMq5hzqds5OIV0xaHT0hM4QRegQzxUDRSnfZsarCuiCYzxuzUWdJzQMjmTj9AkUrnWlk';

    $data = [
        "registration_ids" => $tokens,
        "notification" => [
            "title" => $title,
            "body" => $body,
            "icon" => url('images/icon.png'),
            "click_action" => url('/')
        ]
    ];

    $response = Http::withHeaders([
        'Authorization' => 'key=' . $serverKey,
        'Content-Type' => 'application/json',
    ])->post($url, $data);

    return $response->json();
}
    public function store(Request $request)
    {
       // Check if $request->attendance exists and is not null
       if ($request->has('attendance') && !is_null($request->attendance)) {
        // Process and store attendance records
        foreach ($request->attendance as $index => $attendance) {
            AttendanceRecord::create([
                'activity_id' => $request->activityId,
                'student_id' => $request->studentId[$index],
                'grade' => $request->grade[$index],
                'time' => now(), // Assuming this is the current time
                'status' => $attendance,
                // Add more fields as needed
            ]);
        }

        // ส่งกลับไปยังหน้าแบบฟอร์มพร้อมกับข้อความแจ้งเตือนเมื่อบันทึกสำเร็จ
        return redirect()->back()->with('success', 'บันทึกข้อมูลสำเร็จแล้ว');
    } else {
        // ส่งกลับไปยังหน้าแบบฟอร์มพร้อมกับข้อความแจ้งเตือนเมื่อเกิดข้อผิดพลาด
        return redirect()->back()->with('error', 'ไม่พบข้อมูลการเข้าร่วมหรือมีข้อผิดพลาดในการบันทึกข้อมูล');
    }
    }

public function reportAbsenceOver3Days(Request $request)
{
    $activities = Activity::all();
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');
    $activityId = $request->input('activity');

    $reportData = [];

    if ($startDate && $endDate) {
        // สร้างช่วงวันที่ทั้งหมดในช่วงนั้น
        $allDates = [];
        $date = Carbon::parse($startDate);
        while ($date->lte(Carbon::parse($endDate))) {
            $allDates[] = $date->toDateString();
            $date->addDay();
        }

        // Subquery: ดึงรอบล่าสุดต่อวัน ที่มี status = ขาด/ลา
        $subQuery = AttendanceRecord::select(
                'student_id',
                'grade',
                DB::raw('DATE(time) as date'),
                'status',
                'activity_id',
                'time',
                DB::raw('ROW_NUMBER() OVER (PARTITION BY student_id, DATE(time) ORDER BY time DESC) as rn')
            )
            ->whereBetween(DB::raw('DATE(time)'), [$startDate, $endDate])
            ->whereIn('status', ['ขาด', 'ลา']);

        if ($activityId) {
            $subQuery->where('activity_id', $activityId);
        }

        $records = DB::table(DB::raw("({$subQuery->toSql()}) as latest_records"))
            ->mergeBindings($subQuery->getQuery())
            ->where('rn', 1)
            ->get();

        // Group ข้อมูลตาม student
        $studentAbsences = [];
        foreach ($records as $record) {
            $studentAbsences[$record->student_id]['grade'] = $record->grade;
            $studentAbsences[$record->student_id]['dates'][] = $record->date;
        }

        foreach ($studentAbsences as $studentId => $data) {
            $absenceDates = collect($data['dates'])->sort()->values(); // วันที่ขาด/ลา
            $absenceSet = collect($absenceDates)->flip(); // ใช้สำหรับเช็คเร็ว
            $totalAbsence = $absenceDates->count();

            // ตรวจสอบ "ติดต่อกัน >= 3 วัน"
            $consecutiveCount = 0;
            $maxConsecutive = 0;
            $consecutiveDates = [];

            $currentRun = [];

            foreach ($allDates as $date) {
                if ($absenceSet->has($date)) {
                    $consecutiveCount++;
                    $currentRun[] = $date;
                    if ($consecutiveCount > $maxConsecutive) {
                        $maxConsecutive = $consecutiveCount;
                        $consecutiveDates = $currentRun;
                    }
                } else {
                    $consecutiveCount = 0;
                    $currentRun = [];
                }
            }

            // ตรวจสอบเงื่อนไข: ขาด/ลาติดกัน >= 3 วัน หรือ รวมเกิน 3 วัน
            if ($totalAbsence >= 3 || $maxConsecutive >= 3) {
                $student = Student::find($studentId);
                $classroom = Classroom::with('teacher')->find($data['grade']);

                $reportData[] = [
                    'student_id' => $student->id ?? '-',
                    'student_name' => ($student->name ?? '-') . ' ' . ($student->last_name ?? '-'),
                    'classroom_name' => $classroom->grade ?? 'ไม่พบชื่อห้อง',
                    'teacher_name' => $classroom->teacher->lecturer ?? 'ไม่มีข้อมูล',
                    'total_absence' => $totalAbsence,
                    'absence_dates' => $absenceDates->toArray(),
                    'max_consecutive' => $maxConsecutive,
                    'consecutive_dates' => $consecutiveDates,
                ];
            }
        }
    }

    return view('attendance.absence_over3days', compact('reportData', 'activities', 'startDate', 'endDate', 'activityId'));
}

}
