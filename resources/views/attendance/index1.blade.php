<style>
    body {
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    min-height: 100vh;
    background-color: #f0f0f0;
    font-family: Arial, sans-serif;
}

.container {
    width: 90%;
    max-width: 1200px;
    margin-top: 20px;
    margin-bottom: 20px;
    background: white;
    padding: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    box-sizing: border-box;
}

.header {
    background-color: #d32f2f;
    color: white;
    padding: 15px;
    border-radius: 5px 5px 0 0;
    text-align: center;
    font-size: 20px;
    font-weight: bold;
}

.content {
    margin-top: 20px;
}

.content h3 {
    margin: 0;
    font-size: 18px;
    font-weight: bold;
}

.content p {
    margin: 5px 0;
    font-size: 16px;
    color: #555;
}

form {
    margin-top: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

thead {
    background-color: #f1f1f1;
}

th,
td {
    padding: 15px;
    text-align: center;
    font-size: 16px;
}

th {
    font-weight: bold;
}

td {
    border-bottom: 1px solid #ddd;
}

td input[type="radio"] {
    transform: scale(1.2);
}

.submit-button {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.submit-button button {
    background-color: #1e88e5;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s;
}

.submit-button button:hover {
    background-color: #1565c0;
}

/* Media Queries */

/* Large Desktops */
@media (min-width: 1200px) {
    .container {
        padding: 40px;
    }

    .header h1 {
        font-size: 24px;
        padding: 10px;
    }

    .content h3 {
        font-size: 22px;
    }

    .content p {
        font-size: 18px;
    }

    th,
    td {
        padding: 20px;
        font-size: 18px;
    }

    .submit-button button {
        padding: 15px 30px;
        font-size: 18px;
    }
}

/* Tablets and Small Desktops */
@media (min-width: 768px) and (max-width: 1199px) {
    .container {
        padding: 30px;
    }

    .header {
        font-size: 22px;
    }
    .header h1 {
        font-size: 22px;
        padding: 10px;
    }
    .content h3 {
        font-size: 20px;
    }

    .content p {
        font-size: 16px;
    }

    th,
    td {
        padding: 18px;
        font-size: 16px;
    }

    .submit-button button {
        padding: 12px 25px;
        font-size: 16px;
    }
}

/* Mobile Devices */
@media (max-width: 767px) {
    body {
        padding: 10px;
    }

    .container {
        width: 100%;
        padding: 20px;
    }

    .header {
        font-size: 18px;
        padding: 10px;
    }
    .header h1 {
        font-size: 18px;
        padding: 10px;
    }
    .content h3 {
        font-size: 16px;
    }

    .content p {
        font-size: 14px;
    }

    th,
    td {
        padding: 10px;
        font-size: 14px;
    }

    .submit-button button {
        width: 100%;
        padding: 12px;
        font-size: 16px;
    }

.logo {
        max-width: 100%;
        max-height: 50px;
        margin-bottom: 10px; /* เพิ่มระยะห่างของโลโก้กับข้อความฝ่ายพัฒนา */
    }
}

    </style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Data</title>
</head>
<body>
    <div class="container">
        <div class="header">

            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="logo">
            <form action="{{ route('attendance.submitAttendance') }}" method="POST">
                @csrf
                @if(isset($mode))
                <input type="hidden" name="mode" value="{{ $mode }}">
            @endif
            <li>ฝ่ายพัฒนากิจการนักเรียน-นักศึกษา</li>
                     <ul>
                <li><strong>ระดับชั้น:</strong name="classroomId"> {{ $classroom->grade }}</li>
            </ul>
        </div>
        <div class="content">
            <h3 name="activityId">วิชา: {{ $activity->activity }}</h3>
            <h3 name="lecturerId">อาจารย์ผู้สอน: {{ $lecturer->lecturer }}</h3>
            <input type="hidden" name="classroomId" value="{{ $classroomId }}">
            <input type="hidden" name="activityId" value="{{ $activityId }}">
            <input type="hidden" name="lecturerId" value="{{ $lecturerId }}">

                <table>
                    <thead>
                        <tr>
                            <th>ลำดับ </th>
                            <th>ชื่อ - นามสกุล*</th>
                            <th>มา</th>
                            <th>สาย</th>
                            <th>ขาด</th>
                            <th>ลา</th>
                            @if($activityId == 118)
                            <th>รถรับส่ง</th>
                        @endif
                        
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                            @if($student->grade == $classroomId)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $student->name }} {{ $student->last_name }}</td>
                                    <input type="hidden" name="studentID[]" value="{{ $student->id }}">
                                    <input type="hidden" name="studentName[]" value="{{ $student->name }}">
                                    <input type="hidden" name="studentLastName[]" value="{{ $student->last_name }}">
                                    <input type="hidden" name="studentLevel[]" value="{{ $student->level }}">
                                    <td><input type="radio" name="attendance{{ $loop->index }}" value="มา"></td>
                                    <td><input type="radio" name="attendance{{ $loop->index }}" value="สาย"></td>
                                    <td><input type="radio" name="attendance{{ $loop->index }}" value="ขาด"></td>
                                    <td><input type="radio" name="attendance{{ $loop->index }}" value="ลา"></td>
                                    <td>
                                        @if($activityId == 118)
                                            <input type="radio" name="attendance{{ $loop->index }}" value="รถรับส่ง">
                                        @endif
                                    </td>
                                    
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <div class="submit-button">
                    <button type="submit"  onclick="checkAttendance()">ส่งข้อมูล</button>
                </div>
            </form>

        </div>
    </div>


</body>
</html>
