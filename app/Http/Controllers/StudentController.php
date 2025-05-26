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
                      ->orWhere('grade', 'like', "%{$search}%")
                      ->orWhere('teacher', 'like', "%{$search}%");
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
    }}
