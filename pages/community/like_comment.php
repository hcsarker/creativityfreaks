<?php
session_start();
include '../../includes/db.php';

$user_id = $_SESSION['user_id'];
$comment_id = (int)$_POST['comment_id'];

$conn->query("INSERT INTO comment_likes (comment_id, user_id, action) 
VALUES ($comment_id, $user_id, 'like')
ON DUPLICATE KEY UPDATE action='like'");

$result = $conn->query("SELECT COUNT(*) AS likes FROM comment_likes WHERE comment_id = $comment_id AND action='like'");
$likes = $result->fetch_assoc()['likes'];
echo json_encode(['success' => true, 'likes' => $likes]);


