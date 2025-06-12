<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../../includes/db.php'; // Your DB connection



if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'instructor') {
  die('Access denied.');
}
$instructorId = $_SESSION['user_id'];

$sql = "SELECT e.enrolled_at, u.name AS student_name, u.email, c.title AS course_title
        FROM enrollments e
        JOIN users u ON e.user_id = u.id
        JOIN courses c ON e.course_id = c.id
        WHERE c.instructor_id = ?
        ORDER BY c.title, e.enrolled_at DESC";

$stmt = $conn->prepare($sql);
$stmt->execute([$instructorId]);
$result = $stmt->get_result();
$enrollments = $result->fetch_all(MYSQLI_ASSOC);

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
<style>
  .dashboard-content {
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
  }
  .styled-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
  }
  .styled-table th, .styled-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
  }
  .styled-table th {
    background-color: #4CAF50;
    color: white;
  }
  .styled-table tr:nth-child(even) {
    background-color: #f2f2f2;
  }
  .styled-table tr:hover {
    background-color: #ddd;
  }
  .btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #007BFF;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    margin-top: 20px;
  }
  .btn:hover {
    background-color: #0056b3;
  }
  .btn-primary {
    background-color: #007BFF;
  }
  .btn-primary:hover {
    background-color: #0056b3;
  }
</style>
