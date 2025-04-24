// 🔁 ส่วน import และ setup
const express = require("express");
const http = require("http");
const socketIo = require("socket.io");
const webPush = require("web-push");
const bodyParser = require("body-parser");
const cors = require("cors");
const { Sequelize, DataTypes } = require("sequelize");

// ✅ เชื่อมต่อฐานข้อมูล
const sequelize = new Sequelize({
  dialect: "mysql",
  host: "localhost",
  username: "root",
  password: "",
  database: "boatsucro"
});

const Notification = sequelize.define('Notification', {
  id: {
    type: Sequelize.INTEGER,
    primaryKey: true,
  },
  title: Sequelize.STRING, // ให้แน่ใจว่า `title` มีอยู่ใน Model

  message: Sequelize.STRING,
  classroom_id: Sequelize.INTEGER,
}, {
  timestamps: false, // ปิดการใช้งาน createdAt และ updatedAt
});


// ✅ สร้าง Model Subscription
const Subscription = sequelize.define("Subscription", {
  endpoint: {
    type: DataTypes.STRING,
    allowNull: false,
  },
  keys: {
    type: DataTypes.JSON,
    allowNull: false,
  },
  classroom_id: {
    type: DataTypes.INTEGER,
    allowNull: false,
  }
});

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

// 📌 ฟังก์ชันดึง subscriptions ของห้องเรียน
async function getSubscriptionsByClassroom(classroomId) {
  return await Subscription.findAll({ where: { classroom_id: classroomId } });
}

// ✅ สมัครรับการแจ้งเตือน
app.post("/subscribe", async (req, res) => {
  const subscription = req.body;

  const existing = await Subscription.findOne({
    where: { endpoint: subscription.endpoint }
  });

  if (!existing) {
    await Subscription.create({
      endpoint: subscription.endpoint,
      keys: subscription.keys,
      classroom_id: subscription.classroom_id
    });
  }

  res.status(201).json({ message: "Subscribed successfully!" });
});

// 📢 ส่ง Push Notification
async function sendPushNotifications(subscriptions, message) {
  const payload = JSON.stringify({
    title: "📢 การแจ้งเตือนการเข้าเรียน",
    body: message || "คุณมีการแจ้งเตือนเข้าเรียนใหม่"
  });

  for (const sub of subscriptions) {
    try {
      await webPush.sendNotification(sub.endpoint, payload);
    } catch (err) {
      console.error("❌ Error sending notification:", err);
    }
  }
}

// ✅ บันทึกแจ้งเตือน + ส่ง
app.post("/save-notification", async (req, res) => {
  const { message, classroomId } = req.body;

  await Notification.create({
    message: message,
    classroom_id: classroomId,
    created_at: new Date(),
  });

  const subscriptions = await getSubscriptionsByClassroom(classroomId);
  await sendPushNotifications(subscriptions, message);

  res.status(201).json({ message: "Notification saved and sent!" });
});

// 🎯 Socket.io รับข้อความและแจ้งเตือน
io.on("connection", (socket) => {
  console.log("✅ A client connected");

  socket.on("sendMessage", async (message) => {
    console.log("📩 Message from client:", message);

    const classroomId = message.classroom_id;
    const subscriptions = await getSubscriptionsByClassroom(classroomId);

    const payload = JSON.stringify({
      title: "📢 ข้อความใหม่!",
      body: message.body
    });

    for (const sub of subscriptions) {
      try {
        await webPush.sendNotification(sub.endpoint, payload);
      } catch (err) {
        console.error("❌ Error sending push notification:", err);
      }
    }

    io.emit(`newMessage_${classroomId}`, message);
  });
});

// 🔍 Route เช็คหน้าเว็บ
app.get("/", (req, res) => {
  res.send("📢 Socket Server is running!");
});

app.get("/send-notification", (req, res) => {
  res.send("Hello, this is the send-notification route!");
});

// 🚀 เริ่มต้น server
server.listen(3000, async () => {
  try {
    await sequelize.authenticate();
    await sequelize.sync();
    console.log("🚀 Server is running on port 3000 and DB is connected");
  } catch (error) {
    console.error("❌ Unable to connect to the database:", error);
  }
});
const { Op } = require("sequelize");

// 👇 เพิ่ม Model ใหม่สำหรับ push_subscriptions
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
let lastCheckedNotificationId = 0; // กำหนดค่าเริ่มต้น

// ✅ ฟังก์ชันดึงการแจ้งเตือนใหม่ล่าสุด และส่ง Push
async function checkAndSendNotifications() {
  try {
    const latest = await Notification.findOne({
      order: [['created_at', 'DESC']],
    });

    if (!latest || latest.id === lastCheckedNotificationId) return;

    lastCheckedNotificationId = latest.id; // อัปเดตว่าอันล่าสุดคืออันนี้

    const classroomId = latest.classroom_id;
    const title = latest.title || "คุณมีการแจ้งเตือนใหม่";
    const url = "https://www.google.com"; // URL ชั่วคราว

    const subscriptions = await PushSubscription.findAll({
      where: { classroom_id: classroomId }
    });

    const payload = JSON.stringify({
      title: `📢 ${title}`,
      body: "คลิกเพื่อดูรายละเอียด",
      url: url
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
        console.log(`📨 ส่งแจ้งเตือนถึง ${sub.endpoint}`);
      } catch (error) {
        console.error("❌ ส่งไม่สำเร็จ:", error);
      }
    }
  } catch (error) {
    console.error("❌ เกิดข้อผิดพลาดใน checkAndSendNotifications:", error);
  }
}

setInterval(async () => {
  try {
    console.log("🔍 กำลังตรวจสอบการแจ้งเตือนใหม่...");
    await checkAndSendNotifications();
    console.log("✅ การตรวจสอบเสร็จสิ้น!");
  } catch (error) {
    console.error("❌ เกิดข้อผิดพลาดในการตรวจสอบการแจ้งเตือน:", error);
  }
}, 1 * 30 * 1000); // ทุก 10 นาที (10 * 60 * 1000 มิลลิวินาที)
