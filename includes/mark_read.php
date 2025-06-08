<?php
session_start();
require_once 'db.php';

header('Content-Type: application/json');

$response = ['success' => false];

try {
    if (!isset($_SESSION['user_id']) || !isset($_POST['id'])) {
        throw new Exception('Invalid request');
    }

    $userId = $_SESSION['user_id'];
    $notificationId = (int)$_POST['id'];
    
    $stmt = $conn->prepare("UPDATE notifications SET is_read = TRUE WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $notificationId, $userId);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        $response['success'] = true;
    } else {
        throw new Exception('Notification not found');
    }

} catch (Exception $e) {
    $response['error'] = $e->getMessage();
    http_response_code(400);
}

echo json_encode($response);