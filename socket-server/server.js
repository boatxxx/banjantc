const express = require("express");
const http = require("http");
const socketIo = require("socket.io");
const webPush = require("web-push");
const bodyParser = require("body-parser");
const cors = require("cors");
const { Sequelize, DataTypes, Op } = require("sequelize");

// ✅ เชื่อมต่อฐานข้อมูล (ข้อมูลใหม่ที่คุณให้มา)
const sequelize = new Sequelize(
  "banjantc_xxxxxx",         // Database Name
  "banjantc_boatxxx",         // Database Username
  "0821209508asd",            // Database Password
  {
    host: "127.0.0.1",
    port: 3306,
    dialect: "mysql",
    logging: false,
  }
);

// ✅ สร้าง Models
const Notification = sequelize.define('Notification', {
  id: {
    type: Sequelize.INTEGER,
    primaryKey: true,
  },
  title: Sequelize.STRING,
  message: Sequelize.STRING,
  classroom_id: Sequelize.INTEGER,
  created_at: Sequelize.DATE,
}, {
  tableName: 'notifications',
  timestamps: false,
});

const PushSubscription = sequelize.define("PushSubscription", {
  endpoint: {
    type: DataTypes.STRING,
    allowNull: false,
  },
  keys_p256dh: {
    type: DataTypes.STRING,
    allowNull: false,
  },
  keys_auth: {
    type: DataTypes.STRING,
    allowNull: false,
  },
  classroom_id: {
    type: DataTypes.INTEGER,
    allowNull: false,
  }
}, {
  tableName: 'push_subscriptions',
  timestamps: false
});

// ✅ ตัวแปร log เก็บการทำงาน
let logs = [];

// ✅ สร้าง express app + socket server
const app = express();
const server = http.createServer(app);
const io = socketIo(server);

// ✅ Middleware
app.use(cors());
app.use(bodyParser.json());

// ✅ ตั้งค่า Web Push
const vapidKeys = {
  publicKey: "BB7Gi4jGcM0nuyNx2XYIwXu4Gv10z6yuTszxgTtOxOgGsk_Yo3IC0hXKP3XnPy4IRAptcVP0F8nlVsgD8oxdIx8",
  privateKey: "g3dKSfkfWaDJcJeW8c1XjFqdugOuNWn8tTzYK4c_F9c"
};

webPush.setVapidDetails(
  "mailto:boatsucro01@gmail.com",
  vapidKeys.publicKey,
  vapidKeys.privateKey
);

// ✅ ฟังก์ชันดึงการแจ้งเตือนใหม่ล่าสุด และส่ง Push
let lastCheckedNotificationId = 0;

async function checkAndSendNotifications() {
  try {
    const latest = await Notification.findOne({
      order: [['created_at', 'DESC']],
    });

    if (!latest || latest.id === lastCheckedNotificationId) {
      logs.push(`[${new Date().toISOString()}] ไม่มีแจ้งเตือนใหม่`);
      return;
    }

    lastCheckedNotificationId = latest.id;

    const classroomId = latest.classroom_id;
    const title = latest.title || "📢 คุณมีการแจ้งเตือนใหม่";
    const url = "https://www.google.com"; // ปรับ url ตามจริงได้

    const subscriptions = await PushSubscription.findAll({
      where: { classroom_id: classroomId }
    });

    const payload = JSON.stringify({
      title: title,
      body: "คลิกเพื่อดูรายละเอียด",
      url: url,
    });

    for (const sub of subscriptions) {
      const pushData = {
        endpoint: sub.endpoint,
        keys: {
          p256dh: sub.keys_p256dh,
          auth: sub.keys_auth
        }
      };

      try {
        await webPush.sendNotification(pushData, payload);
        logs.push(`[${new Date().toISOString()}] ส่งแจ้งเตือนถึง: ${sub.endpoint}`);
      } catch (error) {
        logs.push(`[${new Date().toISOString()}] ❌ ส่งไม่สำเร็จ: ${error.message}`);
      }
    }
  } catch (error) {
    logs.push(`[${new Date().toISOString()}] ❌ เกิดข้อผิดพลาด: ${error.message}`);
  }
}

// ✅ เรียก check ทุก 1 นาที
setInterval(async () => {
  await checkAndSendNotifications();
}, 1 * 60 * 1000); // 1 นาที

// ✅ สร้างหน้าแรก สำหรับดู logs
app.get("/", (req, res) => {
  res.send(`
    <h1>📋 ระบบตรวจสอบแจ้งเตือน</h1>
    <p>Log ล่าสุด:</p>
    <pre style="background:#f4f4f4;padding:10px;">${logs.slice(-50).join("\n")}</pre>
  `);
});

// 🎯 socket รับข้อความสดๆ
io.on("connection", (socket) => {
  console.log("✅ Client connected");

  socket.on("sendMessage", async (message) => {
    const classroomId = message.classroom_id;
    const subscriptions = await PushSubscription.findAll({
      where: { classroom_id: classroomId }
    });

    const payload = JSON.stringify({
      title: "📢 ข้อความใหม่!",
      body: message.body
    });

    for (const sub of subscriptions) {
      try {
        await webPush.sendNotification({
          endpoint: sub.endpoint,
          keys: {
            p256dh: sub.keys_p256dh,
            auth: sub.keys_auth
          }
        }, payload);
      } catch (err) {
        console.error("❌ Error sending push notification:", err);
      }
    }

    io.emit(`newMessage_${classroomId}`, message);
  });
});

// 🚀 เริ่มต้น server
server.listen(3000, async () => {
  try {
    await sequelize.authenticate();
    await sequelize.sync();
    console.log("🚀 Server started on port 3000");
    logs.push(`[${new Date().toISOString()}] Server started on port 3000`);
  } catch (error) {
    console.error("❌ Database connection failed:", error);
    logs.push(`[${new Date().toISOString()}] ❌ Database connection failed: ${error.message}`);
  }
});
