<style>
    /* สไตล์พื้นฐาน */
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
    /* สไตล์วันที่ */
    .report-date {
        text-align: center;
        font-size: 1.2rem;
        color: #333;
        margin-bottom: 20px;
    }
    /* สไตล์ตาราง */
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
        background-color: #e10600; /* สีแดง */
        color: #fff;
    }
    tr:nth-child(even) {
        background-color: #e5e5e5; /* สีเทาอ่อน */
    }
    /* สไตล์ผลรวม */
    tfoot tr {
        background-color: #e10600;
        color: #fff;
        font-weight: bold;
    }
    /* รองรับการแสดงผลบนมือถือ */
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
        $year = date('Y', $timestamp) + 543; // แปลงปี ค.ศ. เป็น พ.ศ.
        $thaiMonths = [
            '01' => 'มกราคม',
            '02' => 'กุมภาพันธ์',
            '03' => 'มีนาคม',
            '04' => 'เมษายน',
            '05' => 'พฤษภาคม',
            '06' => 'มิถุนายน',
            '07' => 'กรกฎาคม',
            '08' => 'สิงหาคม',
            '09' => 'กันยายน',
            '10' => 'ตุลาคม',
            '11' => 'พฤศจิกายน',
            '12' => 'ธันวาคม'
        ];
        $monthThai = $thaiMonths[$month];
        return $day . ' ' . $monthThai . ' ' . $year;
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
                <th>มาเรียน</th>
                <th>สาย</th>
                <th>ขาด</th>
                <th>ลา</th>

            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $data)
                <tr>
                    <td>{{ $data['classroom'] }}</td>
                    <td>{{ $data['teacher'] }}</td>
                    <td>{{ $data['total_students'] }}</td>
                    <td>{{ $data['present'] }}</td>
                    <td>{{ $data['late'] }}</td>
                    <td>{{ $data['absent'] }}</td>
                    <td>{{ $data['leave'] }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">ผลรวม</td>
                <td>{{ $totalStudents }}</td>
                <td>{{ $totalPresent }}</td>
                <td>{{ $totalLate }}</td>
                <td>{{ $totalAbsent }}</td>
                <td>{{ $totalLeave }}</td>
            </tr>
        </tfoot>
    </table>
 <table border="1" cellpadding="8" cellspacing="0" width="100%">
    <thead style="background-color: #343a40; color: #fff;">
        <tr>
            <th>ห้องเรียน</th>
            <th>ครูประจำชั้น</th>
            <th>จำนวนนักเรียน</th>
            <th>มา</th>
            <th>สาย</th>
            <th>ขาด</th>
            <th>ลา</th>
        </tr>
    </thead>
    <tbody>
        @php
            // แยกกลุ่มตามชั้นปี เช่น พ.101 → 01
            $grouped = collect($reportData)->groupBy(function($item) {
                return mb_substr($item['classroom'],1,2);
            });
        @endphp

        @foreach($grouped as $gradeGroup => $classrooms)
            <tr style="background-color: #ffc107; color: #000;">
                <td colspan="7" style="text-align: left;"><strong>ชั้นปี: {{ $gradeGroup }}</strong></td>
            </tr>

            @php
                // เตรียมตัวแปรสะสมยอดรวม
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
                    <td>{{ $data['present'] }}</td>
                    <td>{{ $data['late'] }}</td>
                    <td>{{ $data['absent'] }}</td>
                    <td>{{ $data['leave'] }}</td>
                </tr>

                @php
                    // สะสมยอดรวม
                    $total_students += $data['total_students'];
                    $total_present += $data['present'];
                    $total_late += $data['late'];
                    $total_absent += $data['absent'];
                    $total_leave += $data['leave'];
                @endphp
            @endforeach

            {{-- แสดงยอดรวมของชั้นปี --}}
            <tr style="background-color: #d1ecf1; color: #0c5460; font-weight: bold;">
                <td colspan="2" style="text-align: right;">รวมชั้นปี {{ $gradeGroup }}</td>
                <td>{{ $total_students }}</td>
                <td>{{ $total_present }}</td>
                <td>{{ $total_late }}</td>
                <td>{{ $total_absent }}</td>
                <td>{{ $total_leave }}</td>
            </tr>
        @endforeach
    </tbody>
</table>


</div>
