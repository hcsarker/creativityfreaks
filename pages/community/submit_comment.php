<?php
require_once '../../includes/init.php';
require_once '../../includes/db.php';

if (!isset($_SESSION['user_id'])) {
  http_response_code(403);
  echo "You must be logged in to comment.";
  exit;
}

$isValidCsrf = csrf_verify();
if (!$isValidCsrf) {
  http_response_code(400);
  echo "Invalid CSRF token.";
  exit;
}

$user_id = $_SESSION['user_id'];
$post_id = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
$comment = trim($_POST['comment'] ?? '');
$image = null;

// Upload image if available
if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
  $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
  $maxSize = 10 * 1024 * 1024; // 10MB
  $finfo = new finfo(FILEINFO_MIME_TYPE);
  $mime = $finfo->file($_FILES['image']['tmp_name']);
  if (in_array($mime, $allowedTypes) && $_FILES['image']['size'] <= $maxSize) {
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $imageName = time() . '_' . bin2hex(random_bytes(5)) . '.' . $ext;
    $targetDir = realpath(__DIR__ . '/../../uploads/community');
    if ($targetDir === false) { $targetDir = __DIR__ . '/../../uploads/community'; }
    if (!is_dir($targetDir)) { @mkdir($targetDir, 0755, true); }
    $targetPath = $targetDir . DIRECTORY_SEPARATOR . $imageName;
    if (is_uploaded_file($_FILES['image']['tmp_name']) && move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
      $image = $imageName;
    }
  }
}

// Insert comment
$stmt = $conn->prepare("INSERT INTO community_comments (post_id, user_id, comment, image) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiss", $post_id, $user_id, $comment, $image);
$stmt->execute();

// Now fetch updated comments directly
$stmt = $conn->prepare("
  SELECT c.comment, c.image, c.created_at, u.name, u.avatar 
  FROM community_comments c
  JOIN users u ON c.user_id = u.id
  WHERE c.post_id = ?
  ORDER BY c.created_at ASC
");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
  echo '<div class="comment">';
  echo '<img src="/creativityfreaks/uploads/avatars/' . htmlspecialchars($row['avatar'] ?: 'default.png') . '" class="avatar" />';
  echo '<div><strong>' . htmlspecialchars($row['name']) . '</strong><br>';
  echo '<p>' . nl2br(htmlspecialchars($row['comment'])) . '</p>';
  if ($row['image']) {
    echo '<img src="/creativityfreaks/uploads/community/' . htmlspecialchars($row['image']) . '" style="max-width:200px;">';
  }
  echo '<small>' . date('M j, Y g:i A', strtotime($row['created_at'])) . '</small>';
  echo '</div></div>';
}
