<?php
session_start();
include '../../includes/db.php';


$userId = $_SESSION['user_id'] ?? 0;

if (!$userId) {
    echo "<p>Please log in to view your posts.</p>";
    exit;
}

// Function to create notification

// $message = "$postOwnerName commented on your post.";
// $stmt = $conn->prepare("INSERT INTO notifications (user_id, type, message, related_id) VALUES (?, 'community', ?, ?)");
// $stmt->bind_param("isi", $postOwnerId, $message, $postId);
// $stmt->execute();


$query = "
  SELECT 
    p.id, p.title, p.content, p.image, p.created_at, p.user_id,
    u.name AS user_name, u.avatar,
    SUM(CASE WHEN cl.action = 'like' THEN 1 ELSE 0 END) AS likes,
    SUM(CASE WHEN cl.action = 'dislike' THEN 1 ELSE 0 END) AS dislikes,
    (SELECT COUNT(*) FROM community_comments c WHERE c.post_id = p.id) AS comments_count
  FROM community_posts p
  JOIN users u ON p.user_id = u.id
  LEFT JOIN community_likes cl ON cl.type = 'post' AND cl.target_id = p.id
  WHERE p.user_id = ?
  GROUP BY p.id, p.title, p.content, p.image, p.created_at, p.user_id, u.name, u.avatar
  ORDER BY p.created_at DESC
";


$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0):
    while ($post = $result->fetch_assoc()):
        echo "<p>DEBUG: Showing post ID {$post['id']} by user ID {$post['user_id']} (Logged-in ID: {$userId})</p>";

?>

<!-- Each post block -->
<div class="post" data-post-id="<?= $post['id']; ?>">
    <div class="post-card">
        <div class="post-user">
            <img src="<?= htmlspecialchars(!empty($post['avatar']) ? '/creativityfreaks/uploads/avatars/' . $post['avatar'] : '/creativityfreaks/uploads/avatars/default.png'); ?>" alt="Avatar" class="avatar">
            <div>
                <strong><?= htmlspecialchars($post['user_name']); ?></strong>
                <p class="post-time"><?= date('M j, Y \a\t g:i A', strtotime($post['created_at'])); ?></p>
            </div>
        </div>

        <h3><?= htmlspecialchars($post['title']); ?></h3>
        <p class="post-content"><?= nl2br(htmlspecialchars($post['content'])); ?></p>

        <?php if ($post['image']): ?>
            <img src="/creativityfreaks/uploads/community/<?= htmlspecialchars($post['image']); ?>" alt="Post Image" class="post-image">
        <?php endif; ?>

        <div class="post-actions">
            <button class="like-btn" data-post-id="<?= $post['id']; ?>" data-action="like">
                <i class="fas fa-thumbs-up"></i> <?= $post['likes'] ?? 0 ?>
            </button>
            <button class="dislike-btn" data-post-id="<?= $post['id']; ?>" data-action="dislike">
                <i class="fas fa-thumbs-down"></i> <?= $post['dislikes'] ?? 0 ?>
            </button>
            <button class="comment-btn" data-post-id="<?= $post['id']; ?>">
                <i class="fas fa-comment"></i> <?= $post['comments_count'] ?? 0 ?> Comments
            </button>
        </div>
    </div>

    <!-- Comments Section -->
    <div class="comments-section" style="display: none;" data-post-id="<?= $post['id']; ?>">
        <div class="comments-container">
            <!-- Comments will be loaded here by AJAX -->
        </div>
        <form class="comment-form" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="post_id" value="<?= $post['id']; ?>">
            <textarea name="comment" placeholder="Write a comment..." required></textarea>
            <input type="file" name="image" accept="image/*">
            <button type="submit">Post Comment</button>
        </form>
    </div>
</div>

<?php
    endwhile;
else:
    echo "<p>You haven't posted anything yet.</p>";
endif;
?>
