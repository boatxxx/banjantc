<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

<style>
    body {
    background-color: #fff5f5; /* โทนอ่อนแดงนิด ๆ */
    font-family: 'Prompt', sans-serif; /* ฟอนต์ไทยดูดีบนมือถือ */
}

h2 {
    font-weight: bold;
}

form {
    background-color: #ffffff;
    border: 1px solid #ffcccc;
}

input.form-control,
select.form-select {
    border-width: 2px;
    transition: 0.2s ease-in-out;
}

input.form-control:focus,
select.form-select:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

.btn-danger:hover {
    background-color: #c82333;
    border-color: #bd2130;
}

.btn-outline-danger:hover {
    background-color: #f8d7da;
    color: #dc3545;
}

@media (max-width: 576px) {
    h2 {
        font-size: 1.5rem;
    }
    .form-control,
    .form-select,
    .btn {
        font-size: 1rem;
        padding: 0.75rem;
    }
}

</style><div class="container py-4 px-3" style="max-width: 500px;">
    <h2 class="mb-4 text-danger text-center">เพิ่มนักเรียนใหม่</h2>

    <form action="{{ route('students.store') }}" method="POST" class="bg-white p-4 rounded shadow-sm">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label text-danger fw-bold">ชื่อ</label>
            <input type="text" name="name" class="form-control border-danger" required>
        </div>

        <div class="mb-3">
            <label for="last_name" class="form-label text-danger fw-bold">นามสกุล</label>
            <input type="text" name="last_name" class="form-control border-danger" required>
        </div>

        <div class="mb-4">
            <label for="grade" class="form-label text-danger fw-bold">ห้องเรียน</label>
            <select name="grade" class="form-select border-danger" required>
                <option value="">-- กรุณาเลือก --</option>
                @foreach ($classrooms as $room)
                    <option value="{{ $room->id }}">{{ $room->grade }}</option>
                @endforeach
            </select>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-danger btn-lg">💾 บันทึก</button>
            <a href="{{ route('students.manage') }}" class="btn btn-outline-danger btn-lg">↩️ กลับ</a>
        </div>
    </form>
</div>
