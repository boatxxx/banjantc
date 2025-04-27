// ✅ Import เฉพาะที่จำเป็น
const { Sequelize, DataTypes, Op } = require("sequelize");
const webPush = require("web-push");

// ✅ เชื่อมต่อฐานข้อมูล
const sequelize = new Sequelize({
  dialect: "mysql",
  host: "localhost",
  username: "root",
  password: "",
  database: "boatsucro"
});

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

// ✅ สร้าง Models
const Notification = sequelize.define('Notification', {
  id: {
    type: DataTypes.INTEGER,
    primaryKey: true,
  },
  title: DataTypes.STRING,
  message: DataTypes.STRING,
  classroom_id: DataTypes.INTEGER,
  created_at: DataTypes.DATE
}, {
  timestamps: false,
  tableName: 'notifications' // เผื่อมีการตั้งชื่อตารางเอง
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

// ✅ ตัวแปรเก็บ id การแจ้งเตือนล่าสุด
let lastCheckedNotificationId = 0;

// ✅ ฟังก์ชันเช็คและส่งการแจ้งเตือน
async function checkAndSendNotifications() {
  try {
    const latest = await Notification.findOne({
      order: [['created_at', 'DESC']],
    });

    if (!latest || latest.id === lastCheckedNotificationId) return;

    lastCheckedNotificationId = latest.id;

    const classroomId = latest.classroom_id;
    const title = latest.title || "📢 คุณมีการแจ้งเตือนใหม่";
    const url = "https://www.google.com"; // หรือ URL ที่ต้องการจริง ๆ

    const subscriptions = await PushSubscription.findAll({
      where: { classroom_id: classroomId }
    });

    const payload = JSON.stringify({
      title: title,
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

// ✅ เชื่อมฐานข้อมูลและเริ่มวนลูปเช็คทุก 30 วินาที
(async () => {
  try {
    await sequelize.authenticate();
    console.log("✅ Database connected");

    setInterval(async () => {
      console.log("🔍 กำลังตรวจสอบการแจ้งเตือนใหม่...");
      await checkAndSendNotifications();
    }, 30 * 1000); // 30 วินาที
  } catch (error) {
    console.error("❌ Database connection error:", error);
  }
})();
