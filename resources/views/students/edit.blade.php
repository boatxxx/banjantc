<style>
    body {
        background-color: #fff;
        color: #b30000;
        font-family: 'Prompt', sans-serif;
        padding: 1rem;
    }
    
    .container {
        max-width: 600px;
        margin: 0 auto;
        padding: 2rem;
        background-color: #fff;
        border: 1px solid #f0f0f0;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(179, 0, 0, 0.1);
    }
    
    h2 {
        color: #b30000;
        margin-bottom: 1.5rem;
        text-align: center;
    }
    
    label {
        font-weight: bold;
        color: #b30000;
    }
    
    .form-control {
        border: 1px solid #b30000;
        border-radius: 4px;
        padding: 0.5rem;
    }
    
    .form-control:focus {
        box-shadow: 0 0 0 2px rgba(179, 0, 0, 0.2);
        border-color: #b30000;
        outline: none;
    }
    
    .btn-success {
        background-color: #b30000;
        border-color: #b30000;
        color: #fff;
        width: 100%;
    }
    
    .btn-success:hover {
        background-color: #990000;
        border-color: #990000;
    }
    
    @media (max-width: 576px) {
        .container {
            padding: 1rem;
        }
    
        .btn-success {
            font-size: 0.9rem;
        }
    }
    </style>
    
<div class="container">
    <h2>แก้ไขข้อมูลนักเรียน</h2>
    <form action="{{ route('students.update', $student->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>ชื่อ</label>
            <input type="text" name="name" value="{{ $student->name }}" class="form-control">
        </div>
        <div class="mb-3">
            <label>สกุล</label>
            <input type="text" name="last_name" value="{{ $student->last_name }}" class="form-control">
        </div>
        <div class="mb-3">
            <label>ห้องเรียน</label>
            <select name="grade" class="form-control">
                @foreach($classrooms as $classroom)
                    <option value="{{ $classroom->id }}" {{ $student->grade == $classroom->grade ? 'selected' : '' }}>
                        {{ $classroom->grade }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-success">บันทึก</button>
    </form>
</div>
