<div class="container">
    <h2>รายชื่อนักเรียนห้อง {{ $grade }}</h2>

    @if (session('swap_first_id'))
        <div class="alert alert-info">
            เลือกคนที่สองเพื่อสลับกับนักเรียน ID: {{ session('swap_first_id') }}
            <a href="{{ route('students.cancelSwap') }}" class="btn btn-sm btn-danger ms-2">ยกเลิก</a>
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>ชื่อ</th>
                <th>นามสกุล</th>
                <th>ID</th>
                <th>🔁 สลับ</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $index => $student)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->last_name }}</td>
                    <td>{{ $student->id }}</td>
                    <td>
                        <form method="POST" action="{{ route('students.swapSelect') }}">
                            @csrf
                            <input type="hidden" name="student_id" value="{{ $student->id }}">
                            <button class="btn btn-outline-secondary btn-sm">เลือก</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
