<style>
    /* ✅ พื้นหลังขาว ตัวอักษรโทนแดง */
    body {
        background-color: #fff;
        color: #b30000;
        font-family: 'Prompt', sans-serif;
        padding: 1rem;
    }
    
    /* ✅ Container ชิดขอบนิดนึงบนจอเล็ก */
    .container {
        max-width: 100%;
        padding: 1rem;
    }
    
    /* ✅ ปรับ table ให้ scroll ได้บนมือถือ */
    .table-responsive {
        overflow-x: auto;
    }
    
    /* ✅ Table โทนแดงขาว */
    .table {
        width: 100%;
        border-collapse: collapse;
    }
    .table thead {
        background-color: #b30000;
        color: white;
    }
    .table th, .table td {
        padding: 0.75rem;
        border: 1px solid #ddd;
    }
    
    /* ✅ Input และ select โทนแดง */
    .form-control, .form-select {
        border: 1px solid #b30000;
        border-radius: 4px;
        padding: 0.5rem;
    }
    .form-control:focus, .form-select:focus {
        outline: none;
        box-shadow: 0 0 0 2px rgba(179, 0, 0, 0.2);
    }
    
    /* ✅ ปุ่ม */
    .btn-primary {
        background-color: #b30000;
        border-color: #b30000;
    }
    .btn-danger {
        background-color: #ff4d4d;
        border-color: #ff4d4d;
    }
    .btn:hover {
        opacity: 0.9;
    }
    
    /* ✅ Mobile responsive tweaks */
    @media (max-width: 576px) {
        h2 {
            font-size: 1.25rem;
        }
    
        .table th, .table td {
            font-size: 0.875rem;
            padding: 0.5rem;
        }
    
        .btn, .form-select {
            font-size: 0.8rem;
        }
    }
    </style>
    
<div class="container">
    <h2 class="mb-4">ระบบจัดการนักเรียน</h2>
<a href="{{ route('students.create') }}" class="btn btn-primary mb-3">➕ เพิ่มนักเรียน</a>

    {{-- 🔍 แบบฟอร์มค้นหา --}}
    <form method="GET" action="{{ route('students.manage') }}" class="mb-3">
        <input type="text" name="search" class="form-control" placeholder="ค้นหาชื่อนักเรียน / ห้อง / ครู" value="{{ request('search') }}">
    </form>
<form class="d-flex mb-3" id="classroomSelectForm">
    <label class="me-2">📚 เลือกห้องเรียน:</label>
    <select name="grade" class="form-select w-auto" onchange="redirectToClassroom(this.value)">
        <option value="">-- เลือกห้อง --</option>
        @foreach ($classroomss as $room)
            <option value="{{ $room->grade }}">{{ $room->grade }}</option>
        @endforeach
    </select>
</form>

<script>
    function redirectToClassroom(grade) {
        if (grade) {
            window.location.href = "{{ url('students-by-classroom') }}/" + grade;
        }
    }
</script>



    {{-- 🧾 ตารางแสดงนักเรียน --}}
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>ชื่อ</th>
                <th>ห้องเรียน</th>
                <th>จัดการ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $index => $student)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $student->name }} {{ $student->last_name }}</td>
                    <td>{{ $student->grade ?? '-' }}</td>
                    <td>
                        <div class="d-flex flex-column flex-sm-row gap-1">
                    
                            {{-- ✏️ ปุ่มแก้ไข --}}
                            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-outline-primary">
                                ✏️ แก้ไข
                            </a>
                    
                            {{-- ❌ ปุ่มลบ --}}
                            <form action="{{ route('students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบ?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">❌ ลบ</button>
                            </form>
                    
                            {{-- 🔄 ย้ายห้อง --}}
                            <form action="{{ route('students.move', $student->id) }}" method="POST">
                                @csrf
                                <select name="new_grade" onchange="this.form.submit()" class="form-select form-select-sm">
                                    <option value="">🔄 ย้ายห้อง</option>
                                    @foreach ($classrooms as $room)
                                        <option value="{{ $room->id }}">{{ $room->grade }}</option>
                                    @endforeach
                                </select>
                            </form>
                    
                        </div>
                    </td>
                    
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
