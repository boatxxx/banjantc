<!DOCTYPE html> 
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลการลงทะเบียน</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 1200px;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin: 40px auto;
        }

        h2 {
            text-align: center;
            font-size: 28px;
            color: #444;
        }

        .search-form {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin-bottom: 30px;
        }

        .search-form input[type="text"],
        .search-form input[type="number"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 200px;
            font-size: 14px;
        }

        .search-form button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .search-form button:hover {
            background-color: #218838;
        }

        table {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            color: #555;
        }

        td {
            background-color: #fafafa;
            color: #333;
        }

        tr:nth-child(even) td {
            background-color: #f9f9f9;
        }

        .action-buttons {
            margin-top: 30px;
            text-align: center;
        }

        .action-buttons a {
            padding: 12px 24px;
            font-size: 16px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .action-buttons a:hover {
            background-color: #0056b3;
        }

        .subject-item {
            padding: 15px;
            border-radius: 8px;
            background-color: #f9f9f9;
            margin-bottom: 15px;
        }

        .subject-item strong {
            font-weight: bold;
            color: #555;
        }

        .status-button {
            background-color: #ffc107;
            border: none;
            color: #fff;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .status-button:hover {
            background-color: #e0a800;
        }
    </style>
</head>
<body>
    @if (!Session::get('pin_verified'))
        @include('enter-pin')
    @else
    <div class="container">
        <h2>ข้อมูลการลงทะเบียนทั้งหมด</h2>

        <!-- แบบฟอร์มค้นหา -->
        <form method="GET" action="{{ route('registrations.index') }}" class="search-form">
            <input type="text" name="fullname" placeholder="ค้นหาชื่อ" value="{{ request('fullname') }}">
            <input type="text" name="subject" placeholder="ค้นหารายวิชา" value="{{ request('subject') }}">
            <input type="number" name="academicYear" placeholder="ปีการศึกษา" value="{{ request('academicYear') }}">
            <button type="submit">ค้นหา</button>
        </form>

        <!-- ตารางแสดงข้อมูลการลงทะเบียน -->
        <table>
            <thead>
                <tr>
                    <th>ชื่อ-สกุล</th>
                    <th>ระดับชั้น</th>
                    <th>ประเภทวิชา</th>
                    <th>สาขาวิชา</th>
                    <th>ปีการศึกษา</th>
                    <th>วันที่ลงทะเบียน</th>
                    <th>ภาคเรียน</th>
                    <th>วิชา</th>
                    <th>รหัสวิชา</th>
                    <th>ผลการเรียน</th>
                    <th>อาจารย์ผู้สอน</th>
                    <th>รูปใบเสร็จ</th>
                    <th>เมนู</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($registrations as $registration)
                    @foreach ($registration->subjects as $subject)
                    <tr>
                        <td>{{ $registration->fullname }}</td>
                        <td>{{ $registration->level }}</td>
                        <td>{{ $registration->courseType }}</td>
                        <td>{{ $registration->major }}</td>
                        <td>{{ $registration->academicYear }}</td>
                        <td>{{ $registration->registerDate }}</td>
                        <td>{{ $registration->semester }}</td>
                        <td>{{ $subject->subject }}</td>
                        <td>{{ $subject->subject_code }}</td>
                        <td>{{ $subject->grade }}</td>
                        <td>{{ $subject->teacher }}</td>
                        <td>
                            <img src="{{ asset('storage/' . $registration->receipt) }}" alt="Receipt Image" width="200">
                        </td>
                        <td>
                            @if ($subject->status == 'completed')
                                <span>คีย์ข้อมูลแล้ว</span>
                            @else
                                <form action="{{ route('subjects.updateStatus', $subject->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="status-button">คีย์ข้อมูลแล้ว</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>

        <!-- ปุ่มเพิ่มข้อมูล -->
        <div class="action-buttons">
            <a href="{{ route('registrations.create') }}">เพิ่มข้อมูลการลงทะเบียน</a>
        </div>
    </div>
    @endif
</body>
</html>
