<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\Subject;
class RegistrationController extends Controller
{
      // ฟังก์ชันสำหรับแสดงฟอร์มบันทึกข้อมูล
      public function create()
      {
          return view('registrations.create');
      }
  
    
      public function store(Request $request)
      {
          // Validation
          $request->validate([
              'fullname' => 'required|string|max:255',
              'level' => 'required|string',
              'courseType' => 'required|string',
              'major' => 'required|string',
              'registerDate' => 'required|date',
              'receipt' => 'required|file|mimes:pdf,jpeg,png,jpg',
              'academicYear' => 'required|integer',
              'semester' => 'required|string',
              'subject' => 'required|array',
              'subject_code' => 'required|array',
              'grade' => 'required|array',
              'teacher' => 'required|array',
          ]);
      
          // Save the registration data
          $data = $request->only([
              'academicYear', 'semester', 'fullname', 'level', 'courseType', 'major', 'registerDate'
          ]);
      
          // Handle the file upload (ใบเสร็จรับเงิน)
          if ($request->hasFile('receipt')) {
              $receiptPath = $request->file('receipt')->store('receipts', 'public');
              $data['receipt'] = $receiptPath;
          }
      
          // Save the main registration data
          $registration = Registration::create($data);
      
          // Save subjects related to the registration
          foreach ($request->subject as $index => $subject) {
              $subjectData = [
                  'registration_id' => $registration->id,
                  'subject' => $subject,
                  'semester' => $registration->semester,
                  'subject_code' => $request->subject_code[$index],
                  'grade' => $request->grade[$index],
                  'teacher' => $request->teacher[$index],
              ];
              Subject::create($subjectData);
          }
      
          return redirect()->route('registrations.create')->with('success', 'ลงทะเบียนแก้ผลการเรียนสำเร็จ');
      }
      
      
      
      public function updateStatus($id)
{
    $subject = Subject::findOrFail($id); // หาวิชาที่ต้องการอัปเดตสถานะ
    $subject->status = 'completed'; // เปลี่ยนสถานะเป็น completed
    $subject->save(); // บันทึกการเปลี่ยนแปลง

    return redirect()->back()->with('status', 'สถานะถูกอัปเดตเป็น "completed"');
}
  
      // ฟังก์ชันสำหรับแสดงข้อมูลทั้งหมด
 
public function index(Request $request)
{
    $query = Registration::with('subjects');

    // เงื่อนไขค้นหาชื่อ
    if ($request->filled('fullname')) {
        $query->where('fullname', 'like', '%' . $request->fullname . '%');
    }

    // เงื่อนไขค้นหาวิชา (ผ่าน relation)
    if ($request->filled('subject')) {
        $query->whereHas('subjects', function($q) use ($request) {
            $q->where('subject', 'like', '%' . $request->subject . '%');
        });
    }

    // เงื่อนไขค้นหาปีการศึกษา
    if ($request->filled('academicYear')) {
        $query->where('academicYear', $request->academicYear);
    }

    $registrations = $query->get();

    return view('registrations.index', compact('registrations'));
}
}
