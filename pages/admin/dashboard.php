<?php


$adminName = $_SESSION['user_name'];
$adminAvatar = $_SESSION['user_avatar'];
?>

<div class="dashboard admin-dashboard">
  <header class="dashboard-header">
    <img src="../../uploads/avatars/<?= htmlspecialchars($adminAvatar) ?>" alt="Admin Avatar" class="avatar" />
    <h2>Welcome back, <?= htmlspecialchars($adminName) ?> 👑</h2>
  </header>

  <section class="dashboard-actions">
    <a href="manage_users.php" class="btn btn-primary"><i class="fas fa-users"></i> Manage Users</a>
    <a href="reported_content.php" class="btn btn-warning"><i class="fas fa-flag"></i> Review Reports</a>
    <a href="site_analytics.php" class="btn btn-info"><i class="fas fa-chart-line"></i> Site Analytics</a>
    <a href="manage_courses.php" class="btn btn-success"><i class="fas fa-book"></i> Manage Courses</a>
  </section>

  <section class="dashboard-overview">
    <div class="card">
      <h3>Users Registered</h3>
      <p><?= getTotalUsers(); ?></p>
    </div>
    <div class="card">
      <h3>Courses Available</h3>
      <p><?= getTotalCourses(); ?></p>
    </div>
    <div class="card">
      <h3>Instructors</h3>
      <p><?= getInstructorCount(); ?></p>
    </div>
    <div class="card">
      <h3>Reviews</h3>
      <p><?= getReviewCount(); ?></p>
    </div>
  </section>
</div>

<link rel="stylesheet" href="/creativityfreaks/assets/css/dashboard.css">