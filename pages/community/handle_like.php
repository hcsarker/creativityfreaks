<?php
session_start();
include '../../includes/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['success' => false, 'message' => 'You must be logged in.']);
  exit;
}

$userId = $_SESSION['user_id'];
$postId = $_POST['post_id'] ?? null;
$action = $_POST['action'] ?? null;

if (!$postId || !in_array($action, ['like', 'dislike'])) {
  echo json_encode(['success' => false, 'message' => 'Invalid input.']);
  exit;
}

// Check existing like/dislike
$stmt = $conn->prepare("SELECT id, action FROM community_likes WHERE user_id = ? AND type = 'post' AND target_id = ?");
$stmt->bind_param("ii", $userId, $postId);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows > 0) {
  $row = $res->fetch_assoc();
  if ($row['action'] === $action) {
    // Toggle off
    $del = $conn->prepare("DELETE FROM community_likes WHERE id = ?");
    $del->bind_param("i", $row['id']);
    $del->execute();
  } else {
    // Update opposite action
    $upd = $conn->prepare("UPDATE community_likes SET action = ? WHERE id = ?");
    $upd->bind_param("si", $action, $row['id']);
    $upd->execute();
  }
} else {
  // Insert new
  $ins = $conn->prepare("INSERT INTO community_likes (user_id, type, target_id, action) VALUES (?, 'post', ?, ?)");
  $ins->bind_param("iis", $userId, $postId, $action);
  $ins->execute();
}

// Return updated counts
$count = $conn->prepare("SELECT 
  SUM(CASE WHEN action = 'like' THEN 1 ELSE 0 END) AS likes, 
  SUM(CASE WHEN action = 'dislike' THEN 1 ELSE 0 END) AS dislikes 
  FROM community_likes WHERE type = 'post' AND target_id = ?");
$count->bind_param("i", $postId);
$count->execute();
$counts = $count->get_result()->fetch_assoc();

echo json_encode([
  'success' => true,
  'likes' => $counts['likes'] ?? 0,
  'dislikes' => $counts['dislikes'] ?? 0
]);
