<?php
require_once '../../includes/init.php';
require_once '../../includes/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
	echo json_encode(['success' => false, 'message' => 'Unauthorized']);
	exit;
}

if (!csrf_verify()) {
	http_response_code(400);
	echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
	exit;
}

$user_id = (int)$_SESSION['user_id'];
$comment_id = isset($_POST['comment_id']) ? (int)$_POST['comment_id'] : 0;
if ($comment_id <= 0) {
	echo json_encode(['success' => false, 'message' => 'Invalid comment']);
	exit;
}

// Upsert like action safely
$stmt = $conn->prepare("INSERT INTO comment_likes (comment_id, user_id, action) VALUES (?, ?, 'like') ON DUPLICATE KEY UPDATE action='like'");
$stmt->bind_param('ii', $comment_id, $user_id);
$stmt->execute();

$stmt2 = $conn->prepare("SELECT COUNT(*) AS likes FROM comment_likes WHERE comment_id = ? AND action='like'");
$stmt2->bind_param('i', $comment_id);
$stmt2->execute();
$likes = (int)$stmt2->get_result()->fetch_assoc()['likes'];
echo json_encode(['success' => true, 'likes' => $likes]);


