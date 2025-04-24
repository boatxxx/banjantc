<style>
    /* ตัวอย่าง CSS สำหรับฟอร์ม */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f4;
}

h1 {
    font-size: 2rem;
    color: #e10600; /* สีแดง */
    font-weight: 700;
}

.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.form-label {
    font-size: 1rem;
    color: #333;
    font-weight: 600;
}

.form-control, .form-select {
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 10px;
    font-size: 1rem;
    background-color: #f8f8f8;
    transition: border-color 0.3s;
}

.form-control:focus, .form-select:focus {
    border-color: #e10600; /* สีแดง */
    background-color: #fff;
    outline: none;
}

.btn {
    font-size: 1rem;
    padding: 10px 20px;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.btn-danger {
    background-color: #e10600; /* สีแดง */
    color: #fff;
    border: none;
}

.btn-danger:hover {
    background-color: #c10500;
}

.mb-3 {
    margin-bottom: 1.5rem;
}

@media (max-width: 768px) {
    h1 {
        font-size: 1.8rem;
    }

    .container {
        padding: 15px;
    }

    .form-label, .form-control, .form-select {
        font-size: 0.9rem;
    }

    .btn {
        font-size: 1.2rem;
    }
}

    </style>
<div class="container py-4">
        <h1 class="text-center text-danger mb-4">เลือกกิจกรรมและห้องเรียน</h1>

        <!-- ฟอร์มสำหรับเลือกกิจกรรมและวันที่ -->
        <form action="{{ route('attendance.report') }}" method="GET">
            @csrf
            <div class="row">
                <!-- กิจกรรม -->
                <div class="col-12 col-md-4 mb-3">
                    <label for="activity" class="form-label">กิจกรรม:</label>
                    <select name="activity" id="activity" class="form-select">
                        <option value="">เลือกกิจกรรม</option>
                        @foreach($activities as $activity)
                            <option value="{{ $activity->id }}">{{ $activity->activity }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- วันที่ -->
                <div class="col-12 col-md-4 mb-3">
                    <label for="date" class="form-label">วันที่:</label>
                    <input type="date" name="date" id="date" class="form-control" required>
                </div>

                <!-- ห้องเรียน -->
                <div class="col-12 col-md-4 mb-3">
                    <label for="classroom" class="form-label">ห้องเรียน:</label>
                    <select name="classroom" id="classroom" class="form-select">
                        <option value="">เลือกห้องเรียน</option>
                        @foreach($classrooms as $classroom)
                            <option value="{{ $classroom->id }}">{{ $classroom->grade }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- ปุ่ม Submit -->
            <div class="text-center">
                <button type="submit" class="btn btn-danger w-100 w-md-auto">แสดงรายงาน</button>
            </div>
        </form>
    </div>
