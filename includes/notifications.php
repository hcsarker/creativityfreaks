<?php
// /creativityfreaks/includes/notifications.php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

$response = [
    'success' => false,
    'count' => 0,
    'notifications' => []
];
<?php echo 'Session user_id: ' . ($_SESSION['user_id'] ?? 'not set'); ?>

try {
    if (!isset($_SESSION['user_id'])) {
        throw new Exception('User not logged in');
    }

    $userId = $_SESSION['user_id'];
    
    // Get unread count
    $countQuery = $conn->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = FALSE");
    $countQuery->bind_param("i", $userId);
    $countQuery->execute();
    $response['count'] = (int)$countQuery->get_result()->fetch_row()[0];
    
    // Get 10 most recent notifications (5 unread first, then 5 read)
    $notifQuery = $conn->prepare("
        SELECT id, type, message, is_read, created_at, related_id
        FROM notifications 
        WHERE user_id = ?
        ORDER BY is_read ASC, created_at DESC
        LIMIT 10
    ");
    $notifQuery->bind_param("i", $userId);
    $notifQuery->execute();
    $result = $notifQuery->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $icon = match($row['type']) {
            'course' => '📚',
            'community' => '💬',
            'message' => '✉️',
            default => '🔔'
        };
        
        $response['notifications'][] = [
            'id' => $row['id'],
            'type' => $row['type'],
            'icon' => $icon,
            'message' => htmlspecialchars($row['message']),
            'time' => timeAgo($row['created_at']),
            'is_read' => (bool)$row['is_read'],
            'related_id' => $row['related_id']
        ];
    }
    
    $response['success'] = true;

} catch (Exception $e) {
    $response['error'] = $e->getMessage();
    http_response_code(401);
}

echo json_encode($response);

function timeAgo($datetime) {
    $time = strtotime($datetime);
    $diff = time() - $time;
    
    if ($diff < 60) return 'Just now';
    if ($diff < 3600) return floor($diff/60) . ' min ago';
    if ($diff < 86400) return floor($diff/3600) . ' hours ago';
    if ($diff < 2592000) return floor($diff/86400) . ' days ago';
    return date('M j, Y', $time);
}