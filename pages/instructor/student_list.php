<?php
session_start();
require_once '../../includes/db.php'; // Your DB connection


$instructorId = $_SESSION['user_id'];

$sql = "SELECT e.enrolled_at, u.name AS student_name, u.email, c.title AS course_title
        FROM enrollments e
        JOIN users u ON e.student_id = u.id
        JOIN courses c ON e.course_id = c.id
        WHERE c.instructor_id = ?
        ORDER BY c.title, e.enrolled_at DESC";

$stmt = $conn->prepare($sql);
$stmt->execute([$instructorId]);
$enrollments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="dashboard-content">
  <h2>👩‍🎓 Enrolled Students</h2>
  <table class="styled-table">
    <thead>
      <tr>
        <th>Course</th>
        <th>Student Name</th>
        <th>Email</th>
        <th>Enrolled Date</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($enrollments as $row): ?>
        <tr>
          <td><?= htmlspecialchars($row['course_title']) ?></td>
          <td><?= htmlspecialchars($row['student_name']) ?></td>
          <td><?= htmlspecialchars($row['email']) ?></td>
          <td><?= date('d M Y', strtotime($row['enrolled_at'])) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<a href="index.php" class="btn btn-primary">Back</a>

<link rel="stylesheet" href="/creativityfreaks/assets/css/dashboard.css">
