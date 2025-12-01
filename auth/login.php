<?php
require_once '../includes/init.php';
require_once '../includes/db.php';
require_once '../includes/logger.php';

// Simple rate limiting: 5 attempts per 10 minutes per IP
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$key = 'login_attempts_' . sha1($ip);
if (!isset($_SESSION[$key])) {
  $_SESSION[$key] = ['count' => 0, 'reset' => time() + 600];
}
if (time() > $_SESSION[$key]['reset']) {
  $_SESSION[$key] = ['count' => 0, 'reset' => time() + 600];
}
if ($_SESSION[$key]['count'] >= 5) {
  $_SESSION['error'] = 'Too many attempts. Please try again later.';
  $_SESSION['error_type'] = 'login';
  http_response_code(429);
  exit(header('Location: /creativityfreaks/index.php'));
}

if (!csrf_verify()) {
  $_SESSION['error'] = 'Invalid session. Please try again.';
  $_SESSION['error_type'] = 'login';
  header('Location: /creativityfreaks/index.php');
  exit;
}

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
    $_SESSION['error_type'] = 'login';
    cf_log('Login failed: invalid password', ['email' => $email, 'ip' => $_SERVER['REMOTE_ADDR'] ?? 'n/a']);
    $_SESSION[$key]['count']++;
  }
} else {
  $_SESSION['error'] = "Email not found.";
  $_SESSION['error_type'] = 'login';
  cf_log('Login failed: email not found', ['email' => $email, 'ip' => $_SERVER['REMOTE_ADDR'] ?? 'n/a']);
  $_SESSION[$key]['count']++;
}

header("Location: /creativityfreaks/index.php");
exit;