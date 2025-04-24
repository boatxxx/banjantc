self.addEventListener('push', function (event) {
    console.log("📩 Push Received:", event);

    event.waitUntil((async () => {
        let pushData = {};
        try {
            pushData = event.data ? event.data.json() : {};
        } catch (e) {
            console.error("❌ Error parsing push ", e);
            pushData = { body: await event.data.text() };
        }

        console.log("📨 Push Payload:", pushData);

        const title = pushData.title || "🚨 แจ้งเตือน";
        const options = {
            body: pushData.body || "ทดสอบแจ้งเตือนจาก Web Push API",
            icon: "/images/icon.png",
            badge: "/images/badge.png",
            requireInteraction: true,
            data: {
                url: pushData.url || '/'
            }
        };

        await self.registration.showNotification(title, options);
    })());
});
