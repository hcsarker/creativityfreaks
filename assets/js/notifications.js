// /creativityfreaks/assets/js/notifications.js
document.addEventListener('DOMContentLoaded', function() {
    const notifIcon = document.querySelector('.notification-icon');
    if (!notifIcon) return;
    
    // Toggle dropdown
    notifIcon.addEventListener('click', function(e) {
        e.stopPropagation();
        const dropdown = document.getElementById('notification-dropdown');
        dropdown.classList.toggle('show');
        
        // Mark all as read when opened
        if (dropdown.classList.contains('show')) {
            document.querySelectorAll('.notification-item.unread').forEach(item => {
                markAsRead(item.dataset.id);
                item.classList.remove('unread');
            });
            document.getElementById('notification-count').textContent = '0';
        }
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.notification-icon')) {
            document.getElementById('notification-dropdown').classList.remove('show');
        }
    });
    
    // Initial load
    fetchNotifications();
    
    // Refresh every 30 seconds
    setInterval(fetchNotifications, 30000);
});

function fetchNotifications() {
    fetch('/creativityfreaks/includes/notifications.php')
        .then(handleResponse)
        .then(updateNotifications)
        .catch(handleError);
}

function handleResponse(response) {
    if (!response.ok) throw new Error('Network error');
    return response.json();
}

function updateNotifications(data) {
    if (!data.success) throw new Error(data.error || 'Failed to load notifications');
    
    // Update badge count
    const badge = document.getElementById('notification-count');
    if (badge) badge.textContent = data.count;
    
    // Update notification list
    const list = document.getElementById('notification-list');
    if (list) {
        list.innerHTML = data.notifications.length > 0 
            ? data.notifications.map(createNotificationItem).join('')
            : '<div class="no-notifications">No notifications found</div>';
        
        // Add click handlers
        document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', function() {
                if (!this.classList.contains('read')) {
                    window.location.href = getNotificationLink(this.dataset);
                }
            });
        });
    }
}

function createNotificationItem(notif) {
    return `
        <div class="notification-item ${notif.is_read ? 'read' : 'unread'}" 
             data-id="${notif.id}"
             data-type="${notif.type}"
             data-related="${notif.related_id}">
            <div class="notification-header">
                <span class="notification-icon">${notif.icon}</span>
                <span class="notification-type">${notif.type}</span>
                <span class="notification-time">${notif.time}</span>
            </div>
            <div class="notification-message">${notif.message}</div>
        </div>
    `;
}

function getNotificationLink(data) {
    const baseUrl = '/creativityfreaks/pages/';
    switch(data.type) {
        case 'course': return `${baseUrl}courses/view.php?id=${data.related}`;
        case 'community': return `${baseUrl}community/post.php?id=${data.related}`;
        case 'message': return `${baseUrl}messages/view.php?id=${data.related}`;
        default: return `${baseUrl}notifications.php`;
    }
}

function markAsRead(id) {
    if (!id) return;
    fetch('/creativityfreaks/includes/mark_read.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=${id}`
    }).catch(console.error);
}

function handleError(error) {
    console.error('Notification error:', error);
    const list = document.getElementById('notification-list');
    if (list) list.innerHTML = '<div class="notification-error">Failed to load notifications</div>';
}