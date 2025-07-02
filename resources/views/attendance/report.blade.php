<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }
    .container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 15px;
    }
    h1 {
        text-align: center;
        margin: 10px 0;
    }
    .report-date {
        text-align: center;
        font-size: 1.2rem;
        color: #333;
        margin-bottom: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        background-color: #fff;
    }
    th, td {
        padding: 10px;
        text-align: center;
        border: 1px solid #ddd;
    }
    th {
        background-color: #e10600;
        color: #fff;
    }
    tr:nth-child(even) {
        background-color: #e5e5e5;
    }
    tfoot tr {
        background-color: #e10600;
        color: #fff;
        font-weight: bold;
    }
    @media (max-width: 768px) {
        th, td {
            padding: 8px;
            font-size: 0.9rem;
        }
        h1 {
            font-size: 1.5rem;
        }
        .report-date {
            font-size: 1rem;
        }
    }
</style>

<div class="container">
    <h1>รายงานการเข้าเรียน</h1>

    @php
        function thai_date($dateStr) {
            $timestamp = strtotime($dateStr);
            $day = date('d', $timestamp);
            $month = date('m', $timestamp);
            $year = date('Y', $timestamp) + 543;
            $thaiMonths = [
                '01' => 'มกราคม', '02' => 'กุมภาพันธ์', '03' => 'มีนาคม',
                '04' => 'เมษายน', '05' => 'พฤษภาคม', '06' => 'มิถุนายน',
                '07' => 'กรกฎาคม', '08' => 'สิงหาคม', '09' => 'กันยายน',
                '10' => 'ตุลาคม', '11' => 'พฤศจิกายน', '12' => 'ธันวาคม'
            ];
            return $day . ' ' . $thaiMonths[$month] . ' ' . $year;
        }
    @endphp

    <h1 class="report-date">วันที่: {{ thai_date($selectedDate) }}</h1>

    @php
        $totalStudents = 0;
        $totalPresent = 0;
        $totalLate = 0;
        $totalAbsent = 0;
        $totalLeave = 0;
        foreach($reportData as $data) {
            $totalStudents += $data['total_students'];
            $totalPresent += $data['present'];
            $totalLate += $data['late'];
            $totalAbsent += $data['absent'];
            $totalLeave += $data['leave'];
        }
    @endphp

    <table>
        <thead>
            <tr>
                <th>ห้องเรียน</th>
                <th>อาจารย์</th>
                <th>จำนวนนักเรียนทั้งหมด</th>
                <th>มา/สาย</th>
                <th>ขาด/ลา</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $data)
                <tr>
                    <td>{{ $data['classroom'] }}</td>
                    <td>{{ $data['teacher'] }}</td>
                    <td>{{ $data['total_students'] }}</td>
                    <td>{{ $data['present'] + $data['late'] }}</td>
                    <td>{{ $data['absent'] + $data['leave'] }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">ผลรวม</td>
                <td>{{ $totalStudents }}</td>
                <td>{{ $totalPresent + $totalLate }}</td>
                <td>{{ $totalAbsent + $totalLeave }}</td>
            </tr>
        </tfoot>
    </table>

    <br>

    <table border="1" cellpadding="8" cellspacing="0" width="100%">
        <thead style="background-color: #343a40; color: #fff;">
            <tr>
                <th>ห้องเรียน</th>
                <th>ครูประจำชั้น</th>
                <th>จำนวนนักเรียน</th>
                <th>มา/สาย</th>
                <th>ขาด/ลา</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grouped = collect($reportData)->groupBy(function($item) {
                    return mb_substr($item['classroom'],1,2);
                });
            @endphp

            @foreach($grouped as $gradeGroup => $classrooms)
                <tr style="background-color: #ffc107; color: #000;">
                    <td colspan="5" style="text-align: left;"><strong>ชั้นปี: {{ $gradeGroup }}</strong></td>
                </tr>

                @php
                    $total_students = 0;
                    $total_present = 0;
                    $total_late = 0;
                    $total_absent = 0;
                    $total_leave = 0;
                @endphp

                @foreach($classrooms as $data)
                    <tr>
                        <td>{{ $data['classroom'] }}</td>
                        <td>{{ $data['teacher'] }}</td>
                        <td>{{ $data['total_students'] }}</td>
                        <td>{{ $data['present'] + $data['late'] }}</td>
                        <td>{{ $data['absent'] + $data['leave'] }}</td>
                    </tr>

                    @php
                        $total_students += $data['total_students'];
                        $total_present += $data['present'];
                        $total_late += $data['late'];
                        $total_absent += $data['absent'];
                        $total_leave += $data['leave'];
                    @endphp
                @endforeach

                <tr style="background-color: #d1ecf1; color: #0c5460; font-weight: bold;">
                    <td colspan="2" style="text-align: right;">รวมชั้นปี {{ $gradeGroup }}</td>
                    <td>{{ $total_students }}</td>
                    <td>{{ $total_present + $total_late }}</td>
                    <td>{{ $total_absent + $total_leave }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>