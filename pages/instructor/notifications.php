<?php
session_start();
require_once '../../includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'instructor') {
  die('Access denied.');
}

$instructorId = $_SESSION['user_id'];

$sql = "SELECT * FROM notifications WHERE user_id = ? AND type = 'course' ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $instructorId);
$stmt->execute();
$result = $stmt->get_result();
$notifications = $result->fetch_all(MYSQLI_ASSOC);
?>

<div class="dashboard-content">
  <h2>🔔 Course Notifications</h2>

  <?php if (empty($notifications)): ?>
    <p>No notifications yet.</p>
  <?php else: ?>
    <ul class="notification-list">
      <?php foreach ($notifications as $note): ?>
        <li class="<?= $note['is_read'] ? 'read' : 'unread' ?>">
          <span class="message"><?= htmlspecialchars($note['message']) ?></span>
          <span class="date"><?= date('d M Y, h:i A', strtotime($note['created_at'])) ?></span>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>

<a href="index.php" class="btn btn-primary">Back</a>

<style>

.notification-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.notification-list li {
  padding: 15px;
  border-bottom: 1px solid #eee;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.notification-list li.unread {
  background-color: #fffbe6;
  font-weight: bold;
}

.notification-list li.read {
  background-color: #f9f9f9;
}

.notification-list .message {
  flex-grow: 1;
}

.notification-list .date {
  font-size: 0.85em;
  color: #666;
}
.notification-list li:hover {
  background-color: #f1f1f1;
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