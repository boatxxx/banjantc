<style>
    .report-container {
        max-width: 100%;
        padding: 1rem;
        font-family: Arial, sans-serif;
    }
    .report-container h3 {
        text-align: center;
        color: #333;
    }
    .report-container form {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .report-container form label {
        width: 100%;
        font-weight: bold;
    }
    .report-container form select,
    .report-container form input[type="date"],
    .report-container form button {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    .report-container form button {
        background-color: #4CAF50;
        color: white;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    .report-container form button:hover {
        background-color: #45a049;
    }
    .report-container table {
        width: 100%;
        border-collapse: collapse;
    }
    .report-container table th,
    .report-container table td {
        border: 1px solid #ddd;
        padding: 0.75rem;
        text-align: left;
    }
    .report-container table th {
        background-color: #f2f2f2;
    }
    @media (min-width: 600px) {
        .report-container form label,
        .report-container form select,
        .report-container form input[type="date"],
        .report-container form button {
            width: auto;
            flex: 1;
        }
        .report-container form label {
            flex-basis: 100px;
            align-self: center;
        }
    }
</style>

<div class="report-container">
    <h3>รายชื่อนักเรียน ขาด + ลา เกิน 3 วัน</h3>

    <form method="GET" action="{{ route('attendance.report.absence_over3days') }}">
        <label>กิจกรรม:</label>
        <select name="activity">
            <option value="">--ทั้งหมด--</option>
            @foreach ($activities as $activity)
                <option value="{{ $activity->id }}" {{ request('activity') == $activity->id ? 'selected' : '' }}>
                    {{ $activity->activity }}
                </option>
            @endforeach
        </select>

        <label>จากวันที่:</label>
        <input type="date" name="start_date" value="{{ $startDate }}">

        <label>ถึงวันที่:</label>
        <input type="date" name="end_date" value="{{ $endDate }}">

        <button type="submit">ค้นหา</button>
    </form>

    @if($reportData)
        <table>
            <thead>
                <tr>
                    <th>ชื่อนักเรียน</th>
                    <th>ห้องเรียน</th>
                    <th>อาจารย์ที่ปรึกษา</th>
                    <th>จำนวน ขาด+ลา</th>
                    <th>วันที่ ขาดและลา </th>

                </tr>
            </thead>
            <tbody>
                @foreach ($reportData as $data)
                <tr>
                    <td>{{ $data['student_name'] }}</td>
                    <td>{{ $data['classroom_name'] }}</td>
                            <td>{{ $data['teacher_name'] }}</td>

                    <td>{{ $data['total_absence'] }}</td>
                    <td>
            @foreach ($data['absence_dates'] as $date)
                {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}<br>
            @endforeach
        </td>
        
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>ยังไม่มีข้อมูล</p>
    @endif
</div>
