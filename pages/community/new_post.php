<?php
session_start();
include '../../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /creativityfreaks/index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $user_id = $_SESSION['user_id'];

    // Validate required fields
    if (empty($title) || empty($content)) {
        $_SESSION['error'] = "Title and content are required.";
        header("Location: new_post.php");
        exit;
    }

    // Handle image upload
    $image = NULL;
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $maxSize = 10 * 1024 * 1024; // 10MB

        if (in_array($_FILES['image']['type'], $allowedTypes) && $_FILES['image']['size'] <= $maxSize) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $imageName = time() . '_' . bin2hex(random_bytes(5)) . '.' . $ext;
            $targetPath = '../../uploads/community/' . $imageName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $image = $imageName;
            } else {
                $_SESSION['error'] = "Failed to upload image.";
                header("Location: new_post.php");
                exit;
            }
        } else {
            $_SESSION['error'] = "Invalid image file or size too large (max 10MB).";
            header("Location: new_post.php");
            exit;
        }
    }

    // Insert post into DB
    $stmt = $conn->prepare("INSERT INTO community_posts (user_id, title, content, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $title, $content, $image);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Post submitted successfully!";
        header("Location: community.php");
        exit;
    } else {
        $_SESSION['error'] = "Database error: " . $stmt->error;
        header("Location: new_post.php");
        exit;
    }
}
?>

<?php
// Include your layout here if needed, or keep simple for now
?>

<div class="new-post-container">
  <a href="/creativityfreaks/pages/community/community.php" class="btn-back">← Back to Community</a>

  <?php if (isset($_SESSION['error'])): ?>
    <div class="error-message"><?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?></div>
  <?php endif; ?>

  <form action="" method="post" enctype="multipart/form-data" class="new-post-form">
    <input type="text" name="title" placeholder="Post title" required><br>
    <textarea name="content" placeholder="Share your thoughts..." rows="5" required></textarea><br>
    <label>Upload an image (optional)</label>
    <input type="file" name="image" accept="image/*"><br>
    <button type="submit">Publish Post</button>
  </form>
</div>

<style>
.new-post-container {
  max-width: 600px;
  margin: 2rem auto;
  padding: 1rem;
}
.btn-back {
  display: inline-block;
  margin-bottom: 1rem;
  color: #6C63FF;
  text-decoration: none;
}
.new-post-form input[type="text"],
.new-post-form textarea {
  width: 100%;
  padding: 0.5rem;
  margin-bottom: 1rem;
  border-radius: 6px;
  border: 1px solid #ccc;
  font-size: 1rem;
}
.new-post-form button {
  background-color: #6C63FF;
  color: white;
  border: none;
  padding: 0.75rem 1.5rem;
  border-radius: 8px;
  cursor: pointer;
  font-size: 1.1rem;
}
.error-message {
  background: #fdd;
  color: #900;
  padding: 0.75rem;
  border-radius: 6px;
  margin-bottom: 1rem;
  font-weight: bold;
}
</style>
