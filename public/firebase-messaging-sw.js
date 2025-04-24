// ตรวจสอบว่าเบราว์เซอร์รองรับการแจ้งเตือนหรือไม่
if ('serviceWorker' in navigator && 'Notification' in window) {
    navigator.serviceWorker.register('/firebase-messaging-sw.js')
        .then(function(registration) {
            console.log('Service Worker registered:', registration);
            requestNotificationPermission();
        })
        .catch(function(error) {
            console.error('Service Worker registration failed:', error);
        });
}

// ขอสิทธิ์การแจ้งเตือนจากผู้ใช้
function requestNotificationPermission() {
    Notification.requestPermission().then(permission => {
        if (permission === 'granted') {
            console.log('Notification permission granted.');
            getFirebaseToken();
        } else {
            console.log('Notification permission denied.');
        }
    });
}

// ดึง Token จาก Firebase
function getFirebaseToken() {
    firebase.messaging().getToken({ vapidKey: "YOUR_VAPID_KEY_HERE" })
        .then(token => {
            if (token) {
                console.log("FCM Token:", token);
                saveTokenToServer(token);
            } else {
                console.log("No registration token available.");
            }
        })
        .catch(error => {
            console.error("Error getting token:", error);
        });
}

// บันทึก Token และ Room ID ลงฐานข้อมูล
function saveTokenToServer(token) {
    let room_id = localStorage.getItem("room_id") || "default_room"; // ใช้ค่าห้องเรียนที่กำหนดไว้

    fetch("/store-token", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ token: token, room_id: room_id })
    })
    .then(response => response.json())
    .then(data => console.log("Token saved:", data))
    .catch(error => console.error("Error saving token:", error));
}
