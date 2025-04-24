<!-- resources/views/registrations/create.blade.php -->
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ลงทะเบียนแก้ผลการเรียน</title>
    <style>
        /* CSS ของคุณ */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: auto;
        }

        h2, h3 {
            text-align: center;
        }

        label {
            display: block;
            margin: 10px 0 5px;
        }

        input, select, button {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        .remove-btn {
            background-color: #dc3545;
            margin-top: 5px;
        }

        .remove-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="container">
        @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
        <h2>แบบฟอร์มลงทะเบียนแก้ผลการเรียน</h2>
        
    

        <form action="{{ route('registrations.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- ฟอร์มข้อมูล -->
            <label>ชื่อ-สกุล *</label>
            <input type="text" name="fullname" required>

            <label>ระดับชั้น *</label>
            <select name="level" required>
                <option value="ปวช.ปีที่ 1">ปวช.ปีที่ 1</option>
                <option value="ปวช.ปีที่ 2">ปวช.ปีที่ 2</option>
                <option value="ปวช.ปีที่ 3">ปวช.ปีที่ 3</option>
                <option value="ปวส.ปีที่ 1">ปวส.ปีที่ 1</option>
                <option value="ปวส.ปีที่ 2">ปวส.ปีที่ 2</option>
                <option value="ปวส.สมทบ ปีที่ 1">ปวส.สมทบ ปีที่ 1</option>
                <option value="ปวส.สมทบ ปีที่ 2">ปวส.สมทบ ปีที่ 2</option>
            </select>

            <label>ประเภทวิชา *</label>
            <select name="courseType" required>
                <option value="อุตสาหกรรม">อุตสาหกรรม</option>
                <option value="พาณิชยกรรม">พาณิชยกรรม</option>
                <option value="บริหารธุรกิจ">บริหารธุรกิจ</option>
            </select>

            <label>สาขาวิชา *</label>
            <select name="major" required>
                <option value="การบัญชี">การบัญชี</option>
                <option value="การตลาด">การตลาด</option>
                <option value="เทคโนโลยีสารสนเทศ">เทคโนโลยีสารสนเทศ</option>
                <option value="ช่างยนต์">ช่างยนต์</option>
                <option value="ช่างไฟฟ้า">ช่างไฟฟ้า</option>
                <option value="ช่างอิเล็กทรอนิกส์">ช่างอิเล็กทรอนิกส์</option>
            </select>
            <label>ปีการศึกษา *</label>
            <input type="number" name="academicYear" required>
            
            <label>ภาคเรียน *</label>
            <select name="semester" required>
                <option value="1">ภาคเรียนที่ 1</option>
                <option value="2">ภาคเรียนที่ 2</option>
            </select>
            
            
            <div id="subjectsContainer">
                <h3>รายวิชา</h3>
                <button type="button" onclick="addSubject()">+ เพิ่มวิชา</button>
            </div>

            <label>วันที่ลงทะเบียนแก้ *</label>
            <input type="date" name="registerDate" required>

            <label>ใบเสร็จรับเงิน *</label>
            <input type="file" name="receipt" accept="application/pdf, image/*" required>

            <button type="submit">ส่งข้อมูล</button>
        </form>
    </div>

    <script>
       function addSubject() {
    const container = document.getElementById("subjectsContainer");
    const index = document.querySelectorAll(".subject-item").length + 1;

    const subjectDiv = document.createElement("div");
    subjectDiv.classList.add("subject-item");

    subjectDiv.innerHTML = `
        <hr>
        <label>วิชา (${index}) *</label>
        <input type="text" name="subject[]" required>

        <label>รหัสวิชา (${index}) *</label>
        <input type="text" name="subject_code[]" required>

        <label>ผลการเรียน *</label>
        <select name="grade[]" required>
            <option value="มส">มส</option>
            <option value="ขร">ขร</option>
            <option value="ขส">ขส</option>
            <option value="0">0</option>
        </select>

        <label>อาจารย์ผู้สอน *</label>
        <input type="text" name="teacher[]" required>

        <button type="button" class="remove-btn" onclick="removeSubject(this)">ลบวิชา</button>
    `;

    container.appendChild(subjectDiv);
}


        function removeSubject(button) {
            button.parentElement.remove();
        }
    </script>
</body>
</html>
