import './bootstrap';
Echo.channel('user.' + userId)
    .listen('ClassroomNotificationEvent', (event) => {
        alert(event.title + '\n' + event.message);
    });
