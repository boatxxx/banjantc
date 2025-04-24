<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="path/to/your/script.js"></script>

  <title>Notifications</title>
  <style>
    .alert {
  padding: 15px;
  border: 1px solid #ccc;
  margin-bottom: 20px;
  background-color: #fefefe;
  border-left: 5px solid #007BFF;
  border-radius: 8px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
}

.notification-title {
  font-weight: bold;
  font-size: 1.1rem;
  color: #333;
}

.message-text {
  white-space: pre-line;
  word-break: break-word;
  line-height: 1.6;
  margin-top: 10px;
  margin-bottom: 10px;
  color: #555;
  font-size: 1rem; /* ขนาดตัวอักษรพอดี */
}

@media (max-width: 767px) {
  .message-text {
    font-size: 1.1rem; /* เพิ่มขนาดตัวอักษรให้เหมาะสมบนมือถือ */
    padding: 10px; /* เพิ่ม padding ให้ข้อความดูไม่ติดขอบ */
    margin-top: 12px;
    margin-bottom: 12px;
    background-color: #f9f9f9; /* เพิ่มพื้นหลังเล็กน้อย */
    border-radius: 8px; /* ทำมุมให้โค้ง */
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1); /* เพิ่มเงาให้ดูมีมิติ */
  }

  .alert {
    padding: 12px; /* เพิ่ม padding ให้กับกล่องข้อความ */
    margin-bottom: 15px; /* เพิ่มระยะห่างระหว่างกล่อง */
  }

  .notification-title {
    font-size: 1.2rem; /* ขนาดตัวอักษรหัวข้อใหญ่ขึ้นเล็กน้อย */
    font-weight: bold;
    color: #333;
  }

  .copy-btn {
    font-size: 0.9rem; /* ขนาดของปุ่มพอดี */
    padding: 6px 10px;
    background-color: #007BFF;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 10px;
  }
}


    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f4f4f4;
    }

    .container {
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
      background-color: #fff;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      border-radius: 10px;
    }

    h2 {
      text-align: center;
      font-size: 1.8rem;
      color: #fff;
      background-color: #e10600;
      padding: 20px;
      border-radius: 4px;
    }

    .alert {
      padding: 15px;
      margin-bottom: 15px;
      border-radius: 4px;
      background-color: #e5e5e5;
      color: #333;
      word-wrap: break-word;
    }

    button {
      padding: 12px 20px;
      background-color: #e10600;
      border: none;
      color: #fff;
      font-size: 1.2rem;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #c10500;
    }

    .copy-btn {
      padding: 10px 15px;
      margin-top: 10px;
      background-color: #4CAF50;
      border: none;
      color: white;
      font-size: 1rem;
      border-radius: 5px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .copy-btn:hover {
      background-color: #45a049;
    }

    .message-text {
      margin: 10px 0;
    }

    .notification-status {
      font-size: 1rem;
      color: #333;
      padding: 10px;
      background-color: #e0e0e0;
      border-radius: 5px;
      text-align: center;
      margin-bottom: 20px;
    }

    @media (max-width: 600px) {
      .container {
        padding: 16px;
        margin: 10px;      }

      h2 {
        font-size: 1.4rem;
  line-height: 1.4;
  word-break: break-word;      }

      .alert {
        padding: 12px;
      }

      button, .copy-btn {
        width: 100%;
    font-size: 1rem;
    margin-top: 10px;
      }
      .message-text {
  word-break: break-word;
  overflow-wrap: break-word;
}
.copy-btn {
  min-height: 44px; /* ตาม WCAG accessibility guideline */
}

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

.brand-title {
  font-size: 1.2rem;
  font-weight: bold;
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
  background-color: #ffffff;
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

@media (min-width: 768px) {
  .menu-toggle {
    display: none;
  }

  .menu-items {
    display: flex !important;
    flex-direction: row;
    gap: 10px;
    margin-top: 0;
  }
}

  </style>
</head>

<body>
  <header class="navbar">
    <div class="navbar-container">
      <button class="menu-toggle" onclick="toggleMenu()">☰ เมนู</button>
      <div class="menu-items" id="menuItems">
        <button id="subscribeBtn" data-classroom="{{ $classroom->id }}">สมัครรับการแจ้งเตือน</button>
        <button id="unsubscribeBtn">ลบการสมัครการแจ้งเตือน</button>
      </div>
    </div>
  </header>
  
  <div class="container">
    <div class="notification-status" id="notification-status">Status: Not Subscribed</div>
   
    <h2>การแจ้งเตือนของห้อง: {{ $classroom->id }}</h2>

    <div id="notification-container">
      @foreach($notifications as $notification)
        <div class="alert">
          <strong class="notification-title">{{ $notification->title }}</strong><br>
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
        console.error("Push notifications are not supported in this browser.");
        return;
      }

      try {
        const registration = await navigator.serviceWorker.register("/service-worker.js");
        console.log("✅ Service Worker registered:", registration);

        let subscription = await registration.pushManager.getSubscription();
        const classroomId = document.getElementById("subscribeBtn").getAttribute("data-classroom");

        if (!subscription) {
          if (Notification.permission !== 'granted') {
            Notification.requestPermission().then(permission => {
              const statusDiv = document.getElementById('notification-status');
              statusDiv.textContent = (permission === "granted") ? "Permission granted for notifications" : "Permission denied for notifications";
            });
          }

          subscription = await registration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: urlBase64ToUint8Array("BB7Gi4jGcM0nuyNx2XYIwXu4Gv10z6yuTszxgTtOxOgGsk_Yo3IC0hXKP3XnPy4IRAptcVP0F8nlVsgD8oxdIx8")
          });

          console.log("📬 Push Subscription:", subscription);
          await saveSubscription(subscription, classroomId);
        } else {
          const statusDiv = document.getElementById('notification-status');
          statusDiv.textContent = "สมัครการแจ้งเตือนแล้ว";
        }
      } catch (error) {
        console.error("🚨 Error registering Service Worker:", error);
      }
    });

    function urlBase64ToUint8Array(base64String) {
      const padding = "=".repeat((4 - (base64String.length % 4)) % 4);
      const base64 = (base64String + padding).replace(/-/g, "+").replace(/_/g, "/");
      const rawData = atob(base64);
      return new Uint8Array([...rawData].map(char => char.charCodeAt(0)));
    }

    async function saveSubscription(subscription, classroomId) {
      const subscriptionData = {
        classroom_id: classroomId,
        subscription: {
          endpoint: subscription.endpoint,
          keys: {
            p256dh: btoa(String.fromCharCode(...new Uint8Array(subscription.getKey("p256dh")))),
            auth: btoa(String.fromCharCode(...new Uint8Array(subscription.getKey("auth"))))
          }
        },
      };

      try {
        // ลบโทเค็นเก่าออกก่อน
        await deleteOldSubscription(classroomId);
        const response = await fetch("/save-push-subscription", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
          },
          body: JSON.stringify(subscriptionData)
        });
        const data = await response.json();
        console.log("✅ Subscription saved:", data);
      } catch (error) {
        console.error("❌ Error saving subscription:", error);
      }
    }

    async function deleteOldSubscription(classroomId) {
      try {
        const response = await fetch(`/delete-old-subscription/${classroomId}`, {
          method: "DELETE",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
          }
        });
        const data = await response.json();
        console.log("✅ Old subscription deleted:", data);
      } catch (error) {
        console.error("❌ Error deleting old subscription:", error);
      }
    }

    document.getElementById("subscribeBtn").addEventListener("click", async function () {
      const classroomId = this.getAttribute("data-classroom");

      try {
        const registration = await navigator.serviceWorker.ready;
        let subscription = await registration.pushManager.getSubscription();
        if (!subscription) {
          subscription = await registration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: urlBase64ToUint8Array("BB7Gi4jGcM0nuyNx2XYIwXu4Gv10z6yuTszxgTtOxOgGsk_Yo3IC0hXKP3XnPy4IRAptcVP0F8nlVsgD8oxdIx8")
          });
          console.log("✅ สมัครรับการแจ้งเตือนสำเร็จ:", subscription);
          await saveSubscription(subscription, classroomId);
        }
      } catch (error) {
        console.error("❌ การสมัครรับการแจ้งเตือนล้มเหลว:", error);
      }
    });

 document.getElementById("unsubscribeBtn").addEventListener("click", async function () {
      try {
        const registration = await navigator.serviceWorker.ready;
        let subscription = await registration.pushManager.getSubscription();
        if (subscription) {
          await subscription.unsubscribe();
          console.log("✅ โทเค็นถูกลบออกจากเบราว์เซอร์แล้ว");
        } else {
          console.log("❌ ไม่พบโทเค็นในการสมัคร");
        }
      } catch (error) {
        console.error("❌ การลบโทเค็นจากเบราว์เซอร์ล้มเหลว:", error);
      }
    });

    document.querySelectorAll('.copy-btn').forEach(button => {
  button.addEventListener('click', function () {
    const message = this.getAttribute('data-message');
    copyMessage(message);
  });
});

function copyMessage(message) {
  const currentUrl = window.location.href; // ดึง URL ปัจจุบัน
  const fullMessage = message + " ดูรายละเอียดวิชาอื่น: " + currentUrl + "";  const textarea = document.createElement('textarea');
  textarea.value = fullMessage; // << ใช้ fullMessage ตรงนี้
  document.body.appendChild(textarea);
  textarea.select();
  document.execCommand('copy');
  document.body.removeChild(textarea);
  alert('ข้อความถูกคัดลอก');
}

  </script>
</body>

</html>
