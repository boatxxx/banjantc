<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\AttendanceRecord;
use App\Models\Student;
use App\Models\Activity;
use App\Models\Teacher;
use App\Models\Classroom;
use App\Models\Level;

class ExcelImportController extends Controller
{
  //** */  public function importExcel2(Request $request)
  //  {
 //       $data = Excel::toArray(new Level, $request->file('file'));
//
        // วน loop ผ่านแต่ละคอลัมน์
        //foreach ($data[0] as $column) {
            // $column[0] คือค่าในแถวแรกของคอลัมน์นั้น ๆ
            // ใช้เงื่อนไขตามต้องการ
          //  if ($column[1] !== null) {
                // สร้างข้อมูลใน activities
           //     Activity::create([
               //     'name' => $column[0],
                    // ข้อมูลอื่น ๆ
           //     ]);
       //     }
     //   }
/////
   //     return redirect()->back()->with('success', 'Import successful!');
 //   }
    public function showImportForm(){
    return view('import');
    }
}
