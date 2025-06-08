<?php

include '../../includes/db.php';


// Fetch posts with user info
$query = "
  SELECT 
    p.id, p.title, p.content, p.image, p.created_at, 
    u.name AS user_name, u.avatar,
    SUM(CASE WHEN cl.action = 'like' THEN 1 ELSE 0 END) AS likes,
    SUM(CASE WHEN cl.action = 'dislike' THEN 1 ELSE 0 END) AS dislikes,
    (SELECT COUNT(*) FROM community_comments c WHERE c.post_id = p.id) AS comments_count
  FROM community_posts p
  JOIN users u ON p.user_id = u.id
  LEFT JOIN community_likes cl ON cl.type = 'post' AND cl.target_id = p.id
  GROUP BY p.id
  ORDER BY p.created_at DESC
";

$result = $conn->query($query);
?>

<div class="community-container" data-aos="fade-up">
    <div class="community-header">
        <h1> <i class="fas fa-users"> </i> Community</h1>
        <div class="community-actions">
            <button class="tab-button active" data-tab="all-posts">All Posts</button>
            <button class="tab-button" data-tab="my-posts">My Posts</button>
            <a href="/creativityfreaks/pages/community/new_post.php" class="btn-new-post">
                <i class="fas fa-plus"></i> New Post
            </a>
        </div>
    </div>

    <!-- All Posts Tab -->
    <div class="tab-content active" id="all-posts">
        <div class="posts-list">
            <?php if ($result->num_rows > 0): ?>
            <?php while ($post = $result->fetch_assoc()): ?>
            <div class="post-card">
                <div class="post-user">
                    <img src="<?= htmlspecialchars(!empty($post['avatar']) ? '/creativityfreaks/uploads/avatars/' . $post['avatar'] : '/creativityfreaks/uploads/avatars/default.png'); ?>"
                        alt="Avatar" class="avatar">
                    <div>
                        <strong><?= htmlspecialchars($post['user_name']); ?></strong>
                        <p class="post-time"><?= date('M j, Y \a\t g:i A', strtotime($post['created_at'])); ?></p>
                    </div>
                </div>
                <h3><?= htmlspecialchars($post['title']); ?></h3>
                <p class="post-content"><?= nl2br(htmlspecialchars($post['content'])); ?></p>
                <?php if ($post['image']): ?>
                <img src="/creativityfreaks/uploads/community/<?= htmlspecialchars($post['image']); ?>" alt="Post Image"
                    class="post-image">
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
                <br>

            </div>
            <!-- Comments Section -->
            <div class="comments-section" style="display: none;" data-post-id="<?= $post['id']; ?>">
                <div class="comments-container">

                    <!-- AJAX-loaded comments will go here -->
                </div>
                <form class="comment-form" enctype="multipart/form-data">
                    <input type="hidden" name="post_id" value="<?= $post['id']; ?>">
                    <textarea name="comment" placeholder="Write a comment..." required></textarea>
                    <input type="file" name="image" accept="image/*">
                    <button type="submit">Post Comment</button>
                </form>
            </div>
        </div>

        <?php endwhile; ?>
        <?php else: ?>
        <p>No posts yet. Be the first to post!</p>
        <?php endif; ?>
    </div>

    <!-- My Posts Tab -->
    <div id="my-posts" class="tab-content">
        <div class="posts-list" id="my-posts-container">
            <!-- My posts will be loaded via AJAX -->
        </div>
    </div>

</div>


<link rel="stylesheet" href="/creativityfreaks/assets/css/community.css">

<script src="/creativityfreaks/assets/js/community.js"></script>