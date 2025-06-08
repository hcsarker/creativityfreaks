<?php
session_start();
include '../../includes/db.php';

$user_id = $_SESSION['user_id'];
$comment_id = (int)$_POST['comment_id'];
$reply = trim($_POST['reply']);

$conn->query("INSERT INTO comment_replies (comment_id, user_id, reply) VALUES ($comment_id, $user_id, '$reply')");

$user = $conn->query("SELECT name, avatar FROM users WHERE id = $user_id")->fetch_assoc();

echo '<div class="reply">';
echo '<img src="/creativityfreaks/uploads/avatars/' . htmlspecialchars($user['avatar'] ?? 'default.png') . '" class="avatar">';
echo '<div><strong>' . htmlspecialchars($user['name']) . '</strong>';
echo '<p>' . nl2br(htmlspecialchars($reply)) . '</p>';
echo '<small>Just now</small></div></div>';
