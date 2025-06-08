<?php
include '../../includes/db.php';

if (!isset($_GET['post_id']) || !is_numeric($_GET['post_id'])) {
    http_response_code(400);
    echo "Invalid Post ID";
    exit;
}

$post_id = (int)$_GET['post_id'];

$stmt = $conn->prepare("
  SELECT c.id, c.comment, c.image, c.created_at, u.name, u.avatar 
  FROM community_comments c
  JOIN users u ON c.user_id = u.id
  WHERE c.post_id = ?
  ORDER BY c.created_at ASC
");

$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
  echo '<div class="comment" data-comment-id="' . $row['id'] . '">';
  echo '<img src="/creativityfreaks/uploads/avatars/' . htmlspecialchars($row['avatar'] ?: 'default.png') . '" class="avatar" />';
  echo '<div><strong>' . htmlspecialchars($row['name']) . '</strong><br>';
  echo '<p>' . nl2br(htmlspecialchars($row['comment'])) . '</p>';
  if ($row['image']) {
    echo '<img src="/creativityfreaks/uploads/community/' . htmlspecialchars($row['image']) . '" style="max-width:200px;">';
  }

  // Like + Reply buttons
  echo '<div class="comment-actions">';
  echo '<button class="comment-like-btn" data-comment-id="' . $row['id'] . '"><i class="fas fa-thumbs-up"></i> <span class="like-count">0</span></button>';
  echo '<button class="reply-toggle-btn" data-comment-id="' . $row['id'] . '"><i class="fas fa-reply"></i> Reply</button>';
  echo '</div>';

  // Reply form (hidden initially)
  echo '<form class="reply-form" style="display:none;" data-comment-id="' . $row['id'] . '">';
  echo '<textarea name="reply" placeholder="Write a reply..." required></textarea>';
  echo '<button type="submit">Post Reply</button>';
  echo '</form>';

  // Replies container (load via AJAX later)
  echo '<div class="replies-container" data-comment-id="' . $row['id'] . '"></div>';

  echo '<small>' . date('M j, Y g:i A', strtotime($row['created_at'])) . '</small>';
  echo '</div></div>';
}

