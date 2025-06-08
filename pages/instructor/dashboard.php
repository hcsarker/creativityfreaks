<?php
$instName = $_SESSION['user_name'];
$instAvatar = $_SESSION['user_avatar'];

?>

<div class="dashboard instructor-dashboard">
  <header class="dashboard-header">
    <img src="../../uploads/avatars/<?= htmlspecialchars($instAvatar) ?>" alt="Instructor Avatar" class="avatar" />
    <h2>Welcome, <?= htmlspecialchars($instName) ?> 🎓</h2>
  </header>

  <section class="dashboard-actions">
    <a href="my_courses.php" class="btn btn-primary"><i class="fas fa-book-open"></i> My Courses</a>
    <a href="create_course.php" class="btn btn-success"><i class="fas fa-plus"></i> Create New Course</a>
    <a href="student_list.php" class="btn btn-info"><i class="fas fa-users"></i> Students</a>
    <a href="notifications.php" class="btn btn-warning"><i class="fas fa-bell"></i> Notifications</a>
  </section>

  <section class="dashboard-overview">
    <div class="card">
      <h3>Total Courses</h3>
      <p><?= getInstructorCourseCount($_SESSION['user_id']); ?></p>
    </div>
    <div class="card">
      <h3>Students Enrolled</h3>
      <p><?= getInstructorStudentCount($_SESSION['user_id']); ?></p>
    </div>
    <div class="card">
      <h3>Total Revenue</h3>
      <p><?= getInstructorTotalRevenue($_SESSION['user_id']); ?></p>
    </div>
  </section>
</div>

<link rel="stylesheet" href="/creativityfreaks/assets/css/dashboard.css">