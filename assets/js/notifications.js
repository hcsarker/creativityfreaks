// /creativityfreaks/assets/js/notifications.js
document.addEventListener('DOMContentLoaded', function() {
    const notifIcon = document.querySelector('.notification-icon');
    if (!notifIcon) return;
    
    // Toggle dropdown
    notifIcon.addEventListener('click', function(e) {
        e.stopPropagation();
        const dropdown = document.getElementById('notification-dropdown');
        dropdown.classList.toggle('show');
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
                const target = this;
                const wasUnread = target.classList.contains('unread');
                if (wasUnread) {
                    markAsRead(target.dataset.id).finally(() => {
                        target.classList.remove('unread');
                        target.classList.add('read');
                        decrementBadge();
                        window.location.href = getNotificationLink(target.dataset);
                    });
                } else {
                    window.location.href = getNotificationLink(target.dataset);
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
            'X-CSRF-Token': getCsrfToken()
        },
        body: `id=${encodeURIComponent(id)}&csrf_token=${encodeURIComponent(getCsrfToken())}`
    }).catch(console.error);
}

function handleError(error) {
    console.error('Notification error:', error);
    const list = document.getElementById('notification-list');
    if (list) list.innerHTML = '<div class="notification-error">Failed to load notifications</div>';
}

function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '';
}

function decrementBadge() {
    const badge = document.getElementById('notification-count');
    if (!badge) return;
    const current = parseInt(badge.textContent || '0', 10);
    if (Number.isFinite(current) && current > 0) {
        badge.textContent = String(current - 1);
    }
}