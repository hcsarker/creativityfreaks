<?php
session_start();
require_once '../includes/db.php';

$email = trim($_POST['email']);
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT id, name, password, avatar, role FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
  $stmt->bind_result($id, $name, $hashedPassword, $avatar, $role);
  $stmt->fetch();

  if (password_verify($password, $hashedPassword)) {
    $_SESSION['user_id'] = $id;
    $_SESSION['user_name'] = $name;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_avatar'] = $avatar;
    $_SESSION['user_role'] = $role;
    
    

    switch ($role) {
      case 'admin':
        header("Location: /creativityfreaks/pages/admin/index.php");
        break;
      case 'instructor':
        header("Location: /creativityfreaks/pages/instructor/index.php");
        break;
      default: // student or any other
        header("Location: /creativityfreaks/pages/dashboard.php");
        break;
    }

    exit;
  } else {
    $_SESSION['error'] = "Invalid password.";
  }
} else {
  $_SESSION['error'] = "Email not found.";
}

header("Location: /creativityfreaks/index.php");
exit;