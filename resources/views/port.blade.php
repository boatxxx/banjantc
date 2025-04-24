<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายงาน</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0.5in;
        }

        @media print {
            .green {
                background-color: green;
                color: white;
            }

            .red {
                background-color: red;
                color: white;
            }

            .yellow {
                background-color: yellow;
                color: black;
            }

            body {
                font-size: 8px;
                line-height: 1;
            }

            * {
                box-sizing: border-box;
            }

            html, body {
                width: 100%;
                height: 100%;
                margin: 0;
                padding: 0;
            }

            .no-print {
                display: none;
            }

            table {
                width: auto;
                font-size: 8px;
                border-collapse: collapse;
                page-break-inside: auto;
                border-spacing: 0;
            }

            th, td {
                padding: 2px;
                border: 1px solid black;
                word-wrap: break-word;
                line-height: 1;
            }

            th:first-child, td:first-child {
                border-left: none;
            }

            th:last-child, td:last-child {
                border-right: none;
            }

            img {
                max-width: 100%;
                height: auto;
            }

            .header-content {
                display: block;
                text-align: center;
                margin-bottom: 10px;
            }

            .header-space {
                height: 0;
            }

            thead {
                display: table-header-group;
            }

            tr {
                page-break-inside: avoid;
            }

            body::after {
                content: "";
                display: block;
                page-break-before: always;
            }
        }

        table {
            width: 100%;
            border: 1px solid black;
            border-collapse: collapse;
            margin-top: 0;
        }

        th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
        }

        h1 {
            font-size: 14px;
            margin: 0;
        }

        p {
            font-size: 12px;
            margin: 5px 0;
        }

        body {
            font-size: 16px;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            line-height: 1.5;
            color: black;
            background-color: white;
        }

        .container {
            width: 21cm;
            margin: 0 auto;
            padding: 2cm 1cm;
            text-align: center;
        }

        h1 {
            font-size: 18px;
            margin-bottom: 20px;
            text-align: center;
        }

        p {
            text-align: center;
            margin-bottom: 10px;
        }

        th, td {
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .green {
            background-color: green;
            color: white;
        }

        .red {
            background-color: red;
            color: white;
        }

        .yellow {
            background-color: yellow;
            color: black;
        }

        .highlight-red {
            background-color: red;
            color: white;
        }
    </style>
</head>
<body>

<div class="header-content">
    <h1>วิทยาลัยเทคโนโลยีบ้านจั่น ภาคเรียนที่ 1 ปีการศึกษา 2567</h1>
    <p>รหัส: ______________ วิชา: {{ $activity->activity }} จำนวนหน่วยกิต: __ จำนวนเต็มคาบ: __ อาจารย์ผู้สอน: {{ $lecturer->lecturer }}</p>
    <p>ระดับชั้น: ปีที่ {{ $classroom->grade[4] }} ห้อง: {{ $classroom->grade }} ประเภทวิชา:
        <?php
        $classroom_id = $classroom->id;

        if (in_array($classroom_id, [2, 3, 10, 34, 17])) {
            $variable1 = "อุตสาหกรรมดิจิทัลและเทคโนโลยีสารสนเทศ";
            $variable2 = "เทคโนโลยีธุรกิจดิจิทัล";
        } elseif (in_array($classroom_id, [1, 9, 16])) {
            $variable1 = "บริหารธุรกิจ";
            $variable2 = "การบัญชี";
        } elseif (in_array($classroom_id, [4, 11])) {
            $variable1 = "บริหารธุรกิจ";
            $variable2 = "การตลาด";
        } elseif (in_array($classroom_id, [5, 16, 12, 14])) {
            $variable1 = "อุตสาหกรรม";
            $variable2 = "ช่างยนต์";
        } elseif ($classroom_id == 19) {
            $variable1 = "อุตสาหกรรม";
            $variable2 = "เทคนิคเครื่องกล";
        } elseif (in_array($classroom_id, [7, 13])) {
            $variable1 = "อุตสาหกรรม";
            $variable2 = "ช่างไฟฟ้า";
        } elseif (in_array($classroom_id, [8, 15])) {
            $variable1 = "อุตสาหกรรม";
            $variable2 = "อิเล็กทรอนิกส์";
        } elseif ($classroom_id == 20) {
            $variable1 = "อุตสาหกรรม";
            $variable2 = "เทคโนโลยีอิเล็กทรอนิกส์";
        } else {
            $variable1 = "";
            $variable2 = "";
        }
        echo $variable1;
        ?>
        สาขา:
        <?php
        echo $variable2;
        ?>
    </p>
    <p>ตั้งแต่วันที่ {{ \Carbon\Carbon::parse($startDate)->locale('th')->translatedFormat('d F Y') }}</p>
    <p>จนถึงวันที่ {{ \Carbon\Carbon::parse($endDate)->locale('th')->translatedFormat('d F Y') }}</p>
</div>

<div class="header-space"></div>
<table class="table table-bordered">
    <thead>
        <tr>
            <th rowspan="2">ที่</th>
            <th rowspan="2">ชื่อ-สกุล</th>
            <th colspan="{{ $attendanceRecords->groupBy(function($record) { return \Carbon\Carbon::parse($record->time)->format('Y-m-d'); })->count() }}">วันที่-เดือน-ปี เปิดเทอมวันที่ 27 พฤษาคม 2567</th>
            <th rowspan="2">รวม</th>
            <th rowspan="2">หมายเหตุ</th>
        </tr>
        <tr>
            @foreach($attendanceRecords->groupBy(function($record) {
                \Carbon\Carbon::setLocale('th');
                $date = \Carbon\Carbon::parse($record->time);
                return $date->format('j') . ' ' . $date->translatedFormat('M') . ' ' . ($date->year + 543);
            }) as $date => $records)
                <th>{{ $date }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @php
            $students = $attendanceRecords->groupBy('student_id');
            $index = 1;
        @endphp
        @foreach($students as $student_id => $records)
            @php
                $lateCount = $records->where('status', 'สาย')->count();
                $absentCount = $records->where('status', 'ขาด')->count();
                $leaveCount = $records->where('status', 'ลา')->count();
            @endphp
            <tr>
                <td>{{ $index++ }}</td>
                <td>{{ $records->first()->name }} {{ $records->first()->last_name }}</td>
                @foreach($attendanceRecords->groupBy(function($record) {
                    return \Carbon\Carbon::parse($record->time)->format('Y-m-d');
                }) as $date => $dateRecords)
                    @php
                        $record = $records->firstWhere(function($r) use ($date) {
                            return \Carbon\Carbon::parse($r->time)->format('Y-m-d') === $date;
                        });
                    @endphp
                    <td class="{{ $record ? ($record->status == 'สาย' ? 'green' : ($record->status == 'ขาด' ? 'red' : ($record->status == 'ลา' ? 'yellow' : ''))) : '' }}">
                        {{ $record ? $record->status : '-' }}
                    </td>
                @endforeach
                <td>{{ $records->count() }}</td>
                <td></td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Summary Section -->
<div class="summary">
    <h2>สรุปยอด</h2>
    <table>
        <thead>
            <tr>
                <th>ที่</th>
                <th>ชื่อ-สกุล</th>
                <th>สาย</th>
                <th>ขาด</th>
                <th>ลา</th>
                <th>สถานะ</th>
            </tr>
        </thead>
        <tbody>
            @php
                $summaryIndex = 1;
            @endphp
            @foreach($students as $student_id => $records)
                @php
                    $lateCount = $records->where('status', 'สาย')->count();
                    $absentCount = $records->where('status', 'ขาด')->count();
                    $leaveCount = $records->where('status', 'ลา')->count();
                    $total = $lateCount + $absentCount + $leaveCount;
                @endphp
                @if($total > 20)
                    <tr class="highlight-red">
                        <td>{{ $summaryIndex++ }}</td>
                        <td>{{ $records->first()->name }} {{ $records->first()->last_name }}</td>
                        <td>{{ $lateCount }}</td>
                        <td>{{ $absentCount }}</td>
                        <td>{{ $leaveCount }}</td>
                        <td>ติดกิจกรรม</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>

</body>
</html>
