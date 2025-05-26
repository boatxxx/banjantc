<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
	<meta property="og:title" content="Notifications from วิทยาลัยเทคโนโลยีบ้านจั่น" />
<meta property="og:description" content="คลิกเพื่อดูการแจ้งเตือนล่าสุดจากวิทยาลัยเทคโนโลยีบ้านจั่น" />
<meta property="og:image" content="https://www.banjantc.com/images/image.png" />
<meta property="og:url" content="https://www.banjantc.com/notifications" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="วิทยาลัยเทคโนโลยีบ้านจั่น" />

  <title>Notifications</title>
  <style>
    /* === CSS เก็บจากของเดิม + ปรับปรุงนิดหน่อยให้กระชับ === */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
    }
    .navbar {
      background-color: #e10600;
      padding: 10px 20px;
      color: #fff;
      position: sticky;
      top: 0;
      z-index: 1000;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .navbar-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      max-width: 800px;
      margin: 0 auto;
    }
    .menu-toggle {
      background: none;
      border: none;
      color: white;
      font-size: 1.2rem;
      cursor: pointer;
    }
    .menu-items {
      display: none;
      flex-direction: column;
      gap: 10px;
      margin-top: 10px;
    }
    .menu-items button {
      background-color: #fff;
      color: #e10600;
      border: none;
      border-radius: 5px;
      padding: 10px;
      font-size: 1rem;
      cursor: pointer;
    }
    .menu-items button:hover {
      background-color: #f5f5f5;
    }
    .container {
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
      background-color: #fff;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      border-radius: 10px;
    }
    h2 {
      text-align: center;
      font-size: 1.8rem;
      background-color: #e10600;
      color: #fff;
      padding: 20px;
      border-radius: 4px;
    }
    .notification-status {
      font-size: 1rem;
      color: #333;
      background-color: #e0e0e0;
      padding: 10px;
      border-radius: 5px;
      text-align: center;
      margin-bottom: 20px;
    }
    .alert {
      padding: 15px;
      background: #fefefe;
      border-left: 5px solid #007BFF;
      border-radius: 8px;
      margin-bottom: 15px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .notification-title {
      font-weight: bold;
      font-size: 1.1rem;
      color: #333;
    }
    .message-text {
      margin: 10px 0;
      color: #555;
      line-height: 1.6;
      white-space: pre-line;
      word-break: break-word;
    }
    .copy-btn {
      background-color: #4CAF50;
      color: #fff;
      border: none;
      padding: 10px 15px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 1rem;
      transition: background-color 0.3s ease;
    }
    .copy-btn:hover {
      background-color: #45a049;
    }
    @media (max-width: 767px) {
      .menu-items {
        display: flex;
      }
      .menu-toggle {
        display: none;
      }
      .container {
        padding: 16px;
        margin: 10px;
      }
      h2 {
        font-size: 1.5rem;
      }
    }
  </style>
</head>

<body>
  <header class="navbar">
    <div class="navbar-container">
      <button class="menu-toggle" onclick="toggleMenu()">☰ เมนู</button>
      <div class="menu-items" id="menuItems">
        <button id="changeRoomBtn">เปลี่ยนห้องเรียน</button>
      </div>
    </div>
  </header>

  <div class="container">
    <div class="notification-status" id="notification-status">กำลังโหลดสถานะ...</div>
    <h2>การแจ้งเตือนของห้อง: <span id="currentClassroom">{{ $classroom->id }}</span></h2>

    <div id="notification-container">
      @foreach($notifications as $notification)
        <div class="alert">
          <div class="notification-title">{{ $notification->title }}</div>
          <div class="message-text">{!! nl2br(e($notification->message)) !!}</div>
          <button class="copy-btn" data-message="{{ $notification->message }}">คัดลอกข้อความ</button>
        </div>
      @endforeach
    </div>
  </div>

<script>
  function toggleMenu() {
    const menu = document.getElementById("menuItems");
    menu.style.display = (menu.style.display === "flex") ? "none" : "flex";
  }

  document.addEventListener("DOMContentLoaded", async function () {
    if (!("serviceWorker" in navigator) || !("PushManager" in window)) {
      console.error("Push notifications are not supported.");
      return;
    }

    const registration = await navigator.serviceWorker.register("/service-worker.js");
    let subscription = await registration.pushManager.getSubscription();
    const statusDiv = document.getElementById('notification-status');
    const currentClassroomSpan = document.getElementById('currentClassroom');

    if (!subscription) {
      if (Notification.permission !== 'granted') {
        await Notification.requestPermission();
      }
      subscription = await registration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: urlBase64ToUint8Array("BB7Gi4jGcM0nuyNx2XYIwXu4Gv10z6yuTszxgTtOxOgGsk_Yo3IC0hXKP3XnPy4IRAptcVP0F8nlVsgD8oxdIx8")
      });
    }

    statusDiv.textContent = "สมัครรับการแจ้งเตือนแล้ว";

    document.getElementById('changeRoomBtn').addEventListener('click', async function () {
      const newClassroomId = "{{ $classroom->id }}"; // ใช้ ID ของห้องเรียนจากตัวแปรที่ส่งมาใน Blade
      await updateClassroom(subscription, newClassroomId);
      currentClassroomSpan.textContent = newClassroomId;
      alert("เปลี่ยนห้องเรียนสำเร็จ!");
    });
  });

  async function updateClassroom(subscription, classroomId) {
    await fetch("/update-subscription", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({
        classroom_id: classroomId,
        subscription: {
          endpoint: subscription.endpoint,
          keys: {
            p256dh: btoa(String.fromCharCode(...new Uint8Array(subscription.getKey('p256dh')))),
            auth: btoa(String.fromCharCode(...new Uint8Array(subscription.getKey('auth'))))
          }
        }
      })
    });
  }

  function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
    const rawData = atob(base64);
    return Uint8Array.from([...rawData].map(char => char.charCodeAt(0)));
  }
</script>

<script>
  document.addEventListener("click", function (e) {
    if (e.target.classList.contains("copy-btn")) {
      const text = e.target.getAttribute("data-message");
      navigator.clipboard.writeText(text).then(() => {
        alert("คัดลอกข้อความเรียบร้อยแล้ว");
      });
    }
  });
</script>

</body>
</html>
