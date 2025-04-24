<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เลือกห้องเรียน</title>
    <style>
        /* ใช้ฟอนต์พื้นฐาน */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e5e5e5; /* สีเทาอ่อน */
        }

        /* ตั้งค่าให้เน้นสีแดงและสีเทา */
        h2 {
            text-align: center;
            font-size: 1.8rem;
            color: #f4f4f4; /* สีขาว */
            padding: 20px;
            background-color: #e10600; /* สีแดง */
            margin-top: 0;
            border-bottom: 2px solid #ccc;
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        li {
            margin: 10px 0;
            padding: 10px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        a {
            text-decoration: none;
            color: #e10600; /* สีแดง */
            font-size: 1.2rem;
            display: block;
            text-align: center;
            padding: 10px;
            background-color: #f4f4f4; /* สีเทาอ่อน */
            color: #333;
            border-radius: 8px;
            transition: background-color 0.3s;
        }

        a:hover {
            background-color: #e10600; /* สีแดง */
            color: white;
        }

        /* ปรับให้เหมาะสมกับมือถือ */
        @media (max-width: 768px) {
            h2 {
                font-size: 1.5rem;
                padding: 15px;
            }

            li {
                margin: 8px 0;
            }

            a {
                font-size: 1rem;
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    
    @if (!Session::get('pin_verified'))
    @include('enter-pin') <!-- ดึงฟอร์มการกรอกรหัส PIN -->
@else
    <div class="container">
        <h2>เลือกห้องเรียนเพื่อดูการแจ้งเตือน</h2>

        <ul>
            @foreach($classrooms as $classroom)
                <li>
                    <a href="{{ route('notifications.show', $classroom->id) }}">
                        ห้องเรียน: {{ $classroom->grade }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    @endif

</body>
</html>
