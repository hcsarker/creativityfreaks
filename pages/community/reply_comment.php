<?php
require_once '../../includes/init.php';
require_once '../../includes/db.php';

if (!isset($_SESSION['user_id'])) {
	http_response_code(403);
	echo 'Unauthorized';
	exit;
}

if (!csrf_verify()) {
	http_response_code(400);
	echo 'Invalid CSRF token';
	exit;
}

$user_id = (int)$_SESSION['user_id'];
$comment_id = isset($_POST['comment_id']) ? (int)$_POST['comment_id'] : 0;
$reply = trim($_POST['reply'] ?? '');
if ($comment_id <= 0 || $reply === '') {
	http_response_code(400);
	echo 'Invalid input';
	exit;
}

$stmt = $conn->prepare("INSERT INTO comment_replies (comment_id, user_id, reply) VALUES (?, ?, ?)");
$stmt->bind_param('iis', $comment_id, $user_id, $reply);
$stmt->execute();

$userStmt = $conn->prepare("SELECT name, avatar FROM users WHERE id = ?");
$userStmt->bind_param('i', $user_id);
$userStmt->execute();
$user = $userStmt->get_result()->fetch_assoc();

echo '<div class="reply">';
echo '<img src="/creativityfreaks/uploads/avatars/' . htmlspecialchars($user['avatar'] ?? 'default.png') . '" class="avatar">';
echo '<div><strong>' . htmlspecialchars($user['name']) . '</strong>';
echo '<p>' . nl2br(htmlspecialchars($reply)) . '</p>';
echo '<small>Just now</small></div></div>';
