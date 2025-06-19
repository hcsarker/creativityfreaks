<?php
session_start();
require_once '../../includes/db.php';

$course_id = $_GET['id'] ?? null;
if (!$course_id) die("Invalid Course ID");

$sql = "SELECT * FROM course_contents WHERE course_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<section class="dashboard-content">
<h2>📚 View Course Content</h2>
<h2>📂 Course Content</h2>

<?php if ($result->num_rows === 0): ?>
  <p>No content uploaded yet.</p>
<?php else: ?>
  <ul>
    <?php while ($row = $result->fetch_assoc()): ?>
      <li>
        <strong><?= htmlspecialchars($row['title']) ?></strong> (<?= $row['type'] ?>) -
        <?php if ($row['type'] === 'video'): ?>
          <a href="<?= htmlspecialchars($row['video_url']) ?>" target="_blank">Watch Video</a>
        <?php else: ?>
          <a href="../../uploads/course_content/<?= $row['file_path'] ?>" download>Download File</a>
        <?php endif; ?>
      </li>
    <?php endwhile; ?>
  </ul>
<?php endif; ?>

<a href="upload_content.php?id=<?= $course_id ?>" class="btn btn-primary">Upload New Content</a>
<a href="index.php" class="btn btn-secondary">Back to Dashboard</a>
</section>
<link rel="stylesheet" href="/creativityfreaks/assets/css/dashboard.css">
<style>
.dashboard-content {
  padding: 20px;
  background-color: #f9f9f9;
  border-radius: 8px;
}
.dashboard-content h2 {
  margin-bottom: 20px;
}
.dashboard-content ul {
  list-style-type: none;
  padding: 0;
}
.dashboard-content li {
  margin-bottom: 10px;
  padding: 10px;
  background-color: #fff;
  border-radius: 4px;
}
.dashboard-content a {
  text-decoration: none;
  color: #007bff;
}
.dashboard-content a:hover {
  text-decoration: underline;
}
.dashboard-content .btn {
  display: inline-block;
  margin-top: 20px;
  padding: 10px 20px;
  font-size: 1rem;
  font-weight: 500;
  border: none;
  border-radius: 8px;
  background-color: #007bff;
  color: #fff;
  text-decoration: none;
  transition: background-color 0.3s ease, transform 0.2s ease;
}
.dashboard-content .btn:hover {
  background-color: #0056b3;
  transform: translateY(-2px);
}
.dashboard-content .btn-secondary {
  background-color: #6c757d;
}
.dashboard-content .btn-secondary:hover {
  background-color: #5a6268;
}
</style>

