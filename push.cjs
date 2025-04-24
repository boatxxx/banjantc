// push.js
const webPush = require('web-push');

const vapidKeys = {
  publicKey: "BB7Gi4jGcM0nuyNx2XYIwXu4Gv10z6yuTszxgTtOxOgGsk_Yo3IC0hXKP3XnPy4IRAptcVP0F8nlVsgD8oxdIx8",
  privateKey: "g3dKSfkfWaDJcJeW8c1XjFqdugOuNWn8tTzYK4c_F9c"
};

webPush.setVapidDetails(
  "mailto:boatsucro01@gmail.com",
  vapidKeys.publicKey,
  vapidKeys.privateKey
);

const subscription = {
  endpoint: "https://fcm.googleapis.com/fcm/send/efvhU0YoXW4:APA91bHH_K6GYQgxvIV0vkOP0_wy6nxh6DR_CmGMBIVDip9jUQk5GQ-LEADYYPEmK0fpWdirnwMq_yE_I0tBjdC1ebugb3SuOsn0btb8PgBGvVPqE2K3uFcbBV2MsYPx8r_uyi1at6I9",
  keys: {
    p256dh: "BIGNBcyDs5xB9maEKRoNig4VRuPw65E8M6+3xmm0jD5nTHVQXaBnuDfYVfQC49wljleCvQmXm/3N8TGMrGpas7Y=",
    auth: "M8V4P9sAabyCBABFEm2pPA=="
  }
};

const payload = JSON.stringify({
  title: "🚨 แจ้งเตือน",
  body: "ทดสอบแจ้งเตือนจาก Web Push API"
});

webPush.sendNotification(subscription, payload)
  .then(response => {
    console.log("✅ Notification sent successfully", response);
  })
  .catch(err => {
    console.error("❌ Error sending notification", err);
  });
