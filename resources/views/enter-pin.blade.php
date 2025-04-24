<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ใส่รหัสเข้าใช้งาน</title>
  <style>
    body {
      font-family: sans-serif;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      margin: 0;
      background-color: #f4f4f4;
    }

    h2 {
      margin-bottom: 20px;
      color: #333;
    }

    form {
      background: white;
      padding: 20px 30px;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      width: 90%;
      max-width: 400px;
    }

    input[type="password"] {
      font-size: 2rem;
      text-align: center;
      padding: 10px;
      width: 100%;
      box-sizing: border-box;
      margin-bottom: 20px;
      border: 2px solid #ddd;
      border-radius: 8px;
      outline: none;
      transition: border-color 0.3s ease;
    }

    input[type="password"]:focus {
      border-color: #007bff;
    }

    button {
      width: 100%;
      padding: 12px;
      font-size: 1.2rem;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #0056b3;
    }

    .error {
      color: red;
      margin-bottom: 10px;
      text-align: center;
    }
  </style>
</head>
<body>
  <h2>ใส่รหัสผ่านเพื่อเข้าสู่ระบบ</h2>

  @if($errors->any())
    <p class="error">{{ $errors->first('pin') }}</p>
  @endif

  <form method="POST" action="/verify-pin">
    @csrf
    <input type="password" name="pin" maxlength="4" pattern="\d{4}" required>
    <button type="submit">ยืนยัน</button>
  </form>
</body>
</html>
