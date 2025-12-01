<?php
session_start();
require_once '../../includes/db.php';
require_once '../../includes/init.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'instructor') {
  die('Access denied.');
}

$instructorId = $_SESSION['user_id'];

$perPage = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $perPage;

// Count total
$countStmt = $conn->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND type = 'course'");
$countStmt->bind_param("i", $instructorId);
$countStmt->execute();
$total = (int)$countStmt->get_result()->fetch_row()[0];
$totalPages = max(1, (int)ceil($total / $perPage));

// Fetch page
$sql = "SELECT * FROM notifications WHERE user_id = ? AND type = 'course' ORDER BY created_at DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $instructorId, $perPage, $offset);
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

  <a href="index.php" class="btn btn-primary">Back</a>

    <?php if ($totalPages > 1): ?>
    <nav class="pagination">
      <?php $q = function($p){
        $params = $_GET; $params['page']=$p; return '?' . http_build_query($params);
      }; ?>
      <a class="page-link <?= $page<=1?'disabled':'' ?>" href="<?= $page>1?$q($page-1):'#' ?>">Prev</a>
      <span class="page-info">Page <?= $page ?> of <?= $totalPages ?></span>
      <a class="page-link <?= $page>=$totalPages?'disabled':'' ?>" href="<?= $page<$totalPages?$q($page+1):'#' ?>">Next</a>
    </nav>
    <?php endif; ?>
</div>



<style>
.dashboard-content {
  padding: 20px;
  background-color: #f9f9f9;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  max-width: 800px;
  margin: 20px auto;
  font-family: Arial, sans-serif;
}
.dashboard-content h2 {
  margin-bottom: 20px;
  font-size: 1.5em;
  color: #333;
}

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
.pagination {
  display: flex;
  gap: 12px;
  align-items: center;
  margin-top: 16px;
}
.pagination .page-link {
  padding: 6px 12px;
  border: 1px solid #ddd;
  border-radius: 6px;
  text-decoration: none;
  color: #333;
}
.pagination .page-link.disabled {
  pointer-events: none;
  opacity: 0.5;
}
.pagination .page-info { color: #555; }

</style>