<?php
session_start();
require_once '../includes/db.php';

// Collect form data
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$password = $_POST['password'];
$confirm = $_POST['confirm_password'];

// Validation
if ($password !== $confirm) {
  $_SESSION['error'] = "Passwords do not match.";
  header("Location: /creativityfreaks/index.php");
  exit;
}

// Check if user exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
  $_SESSION['error'] = "Email already registered.";
  header("Location: /creativityfreaks/index.php");
  exit;
}

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert user
$stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $hashedPassword);
if ($stmt->execute()) {
  $_SESSION['user_id'] = $stmt->insert_id;
  $_SESSION['user_name'] = $name;
  $_SESSION['user_email'] = $email;
  $_SESSION['user_avatar'] = 'default.png';
  $_SESSION['user_role'] = 'student';
  header("Location: /creativityfreaks/index.php");
} else {
  $_SESSION['error'] = "Registration failed.";
  header("Location: /creativityfreaks/index.php");
}
exit;
