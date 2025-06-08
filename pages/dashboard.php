<?php
include '../includes/auth.php';

// Optional: Redirect based on role
if ($_SESSION['user_role'] === 'admin') {
  header("Location: /creativityfreaks/pages/admin/index.php");
  exit;
} elseif ($_SESSION['user_role'] === 'instructor') {
  header("Location: /creativityfreaks/pages/instructor/index.php");
  exit;
}

$content = '../pages/student_dashboard_content.php';
include '../includes/layout.php';
