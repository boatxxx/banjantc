<?php

namespace App\Http\Controllers;
use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Http\Request;

class StudentController extends Controller
{

    public function manage(Request $request)
    {
        $search = $request->input('search');
    
        $students = Student::with(['registration', 'classroom.teacher'])
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('grade', 'like', "%{$search}%");       
                         })
            ->get();
    
        $classrooms = Classroom::with('teacher')->get();
    
        return view('students.manage', compact('students', 'classrooms'));
    }
    
    public function move(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $student->grade = $request->input('new_grade');
        $student->save();
    
        return redirect()->back()->with('success', 'ย้ายห้องเรียนเรียบร้อยแล้ว');
    }
// อยู่ใน App\Http\Controllers\StudentController
public function edit($id)
{
    $student = Student::findOrFail($id);
    $classrooms = Classroom::all(); // หากคุณต้องการย้ายห้องเรียน

    return view('students.edit', compact('student', 'classrooms'));
}

public function update(Request $request, $id)
{
    $student = Student::findOrFail($id);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'grade' => 'required|string',
        // เพิ่มฟิลด์ที่จำเป็นอื่นๆ ตาม model ของน้อง
    ]);

    $student->update($validated);

    return redirect()->route('students.manage')->with('success', 'อัปเดตข้อมูลนักเรียนเรียบร้อยแล้ว');
}
public function destroy($id)
{
    $student = Student::findOrFail($id);
    $student->delete();

    return redirect()->route('students.manage')->with('success', 'ลบนักเรียนเรียบร้อยแล้ว');
}
}
