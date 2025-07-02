<?php

namespace App\Http\Controllers;
use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
public function create()
{
    $classrooms = Classroom::all(); // ใช้สำหรับเลือกระดับชั้น
    return view('students.create', compact('classrooms'));
}

// บันทึกข้อมูลนักเรียนใหม่
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'grade' => 'required|string',
    ]);
    $validated['level'] = 'เทอม 1 2568';

    Student::create($validated);

    return redirect()->route('students.manage')->with('success', 'เพิ่มนักเรียนเรียบร้อยแล้ว');
}
    public function manage(Request $request)
    {
            $classroomss = Student::select('grade')->distinct()->get();

        $search = $request->input('search');
    
        $students = Student::with(['registration', 'classroom.teacher'])
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('grade', 'like', "%{$search}%");       
                         })
            ->get();
    
        $classrooms = Classroom::with('teacher')->get();
    
        return view('students.manage', compact('students', 'classrooms','classroomss'));
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
public function viewByClassroom($grade)
{
    $students = Student::where('grade', $grade)->orderBy('id')->get();
    $classrooms = Classroom::all();

    return view('students.by_classroom', compact('students', 'grade','classrooms'));
}
public function shuffleIdsInClassroom($grade)
{
    $students = Student::where('grade', $grade)->orderBy('id')->get();

    if ($students->count() < 2) {
        return redirect()->back()->with('error', 'มีนักเรียนไม่พอสำหรับการสลับ ID');
    }

    DB::beginTransaction();
    try {
        $originalIds = $students->pluck('id')->toArray();
        $shuffledIds = $originalIds;
        shuffle($shuffledIds);

        // เปลี่ยน id เป็นค่าลบชั่วคราว
        foreach ($originalIds as $i => $id) {
            DB::table('students')->where('id', $id)->update(['id' => -1 * ($i + 1)]);
        }

        // เปลี่ยนกลับเป็น id ใหม่แบบสลับ
        foreach ($shuffledIds as $i => $newId) {
            DB::table('students')->where('id', -1 * ($i + 1))->update(['id' => $newId]);
        }

        DB::commit();
        return redirect()->route('students.byClassroom', $grade)->with('success', 'สลับ ID ในห้อง ' . $grade . ' เรียบร้อย');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
    }
}
public function selectSwap(Request $request)
{
    $selectedId = $request->input('student_id');

    // ถ้ายังไม่มี ID แรก → เก็บลง session
    if (!session()->has('swap_first_id')) {
        session(['swap_first_id' => $selectedId]);
        return back()->with('message', 'เลือกคนแรกแล้ว');
    }

    // ถ้ามี ID แรกแล้ว → ดำเนินการสลับ
    $firstId = session('swap_first_id');
    $secondId = $selectedId;

    if ($firstId == $secondId) {
        return back()->with('error', 'ไม่สามารถสลับกับตัวเองได้');
    }

    // ดึงนักเรียนทั้งสอง
    $student1 = Student::findOrFail($firstId);
    $student2 = Student::findOrFail($secondId);

    // สลับ ID (ใช้วิธีสลับด้วย temporary ID)
    DB::transaction(function () use ($student1, $student2) {
        $tempId = 999999999;

        $student1->id = $tempId;
        $student1->save();

        $student2->id = $student1->getOriginal('id');
        $student2->save();

        $student1->id = $student2->getOriginal('id');
        $student1->save();
    });

    session()->forget('swap_first_id');
    return back()->with('success', 'สลับเรียบร้อยแล้ว');
}

public function cancelSwap()
{
    session()->forget('swap_first_id');
    return back()->with('message', 'ยกเลิกการเลือกแล้ว');
}

}
