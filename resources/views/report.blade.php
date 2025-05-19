<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบบันทึกข้อมูลแจ้งเตือนไลน์</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
		        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        form {
            background: #fff;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        select, input[type="date"], button {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
        }

        .container {
            width: 90%;
            max-width: 600px;
            margin-top: 20px;
            padding: 20px;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }

        h1 {
            font-size: 1.8rem;
            text-align: center;
            margin: 20px 0;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        select {
            width: 100%;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            margin-bottom: 20px;
        }

        button {
            width: 100%;
            padding: 15px;
            background-color: #e74c3c;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
        }

        button:hover {
            background-color: #c0392b;
        }

        .icon {
            margin-right: 10px;
        }

        @media (max-width: 600px) {
            .container {
                width: 100%;
                padding: 10px;
            }

            h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-bell icon"></i>ระบบสรุปข้อมูล</h1>
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    
    @if (session('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
    @endif
    

        <form action="{{ route('port') }}" method="post">
            @csrf
            <label for="classroom">ห้องเรียน:</label>
            <select name="classroom" id="classroom">
                @foreach($classrooms as $classroom)
                <option value="{{ $classroom->id }}">{{ $classroom->grade }}</option>
                @endforeach
            </select>

            <label for="activity">วิชา:</label>
            <select name="activity" id="activity">
                @foreach($activities as $activity)
              <option value="{{ $activity->id }}">{{ $activity->activity }} ชั้นปีที่: 
@if ($activity->level == 6)
    ทุกชั้นปี
@elseif ($activity->level == 7)
    กิจกรรมเทอมที่ 1
@elseif ($activity->level == 8)
    วิชาเทอมที่ 1 ชั้นปีที่ 1
@elseif ($activity->level == 9)
    วิชาเทอมที่ 1 ชั้นปีที่ 2
@elseif ($activity->level == 10)
    วิชาเทอมที่ 1 ปวส 1
@else
    {{ $activity->level }}
@endif
</option>

                @endforeach
            </select>

            <label for="lecturer">อาจารย์ผู้สอน:</label>
            <select name="lecturer" id="lecturer">
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->lecturer }}</option>
                @endforeach
            </select>
<label for="start_date">ตั้งแต่วันที่:</label>
    <input type="date" name="start_date" id="start_date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">

    <label for="end_date">ถึงวันที่:</label>
    <input type="date" name="end_date" id="end_date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">

            <button type="submit"><i class="fas fa-download icon"></i>ดึงข้อมูล</button>
        </form>
    </div>
</body>
</html>
