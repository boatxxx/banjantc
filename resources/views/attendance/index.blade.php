<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบบันทึกข้อมูลแจ้งเตือนไลน์</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <style>
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
        <h1><i class="fas fa-bell icon"></i>ระบบบันทึกข้อมูลแจ้งเตือนไลน์</h1>
        <form action="{{ route('attendance.index1') }}" method="post">
            @if(isset($mode))
            <input type="hidden" name="mode" value="{{ $mode }}">
        @endif
            @csrf
            <label for="classroom">ห้องเรียน:</label>
            <select name="classroom" id="classroom">
                @foreach($classrooms as $classroom)
                <option value="{{ $classroom->id }}">{{ $classroom->grade }}</option>
                @endforeach
            </select>

            <label for="activity">กิจกรรม:</label>
            <select name="activity" id="activity">
                @foreach($activities as $activity)
                    <option value="{{ $activity->id }}">{{ $activity->activity }}</option>
                @endforeach
            </select>

            <label for="lecturer">อาจารย์ผู้สอน:</label>
            <select name="lecturer" id="lecturer">
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->lecturer }}</option>
                @endforeach
            </select>

            <button type="submit"><i class="fas fa-download icon"></i>ดึงข้อมูล</button>
        </form>
    </div>
</body>
</html>
