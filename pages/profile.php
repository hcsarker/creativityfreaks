<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: /creativityfreaks/index.php");
  exit;
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $newName = trim($_POST['name']);
  $userId = $_SESSION['user_id'];

  if (!empty($_FILES['avatar']['name'])) {
    $targetDir = "../uploads/avatars/";
    $fileName = uniqid() . "_" . basename($_FILES["avatar"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($imageFileType, $allowed)) {
      move_uploaded_file($_FILES["avatar"]["tmp_name"], $targetFilePath);
      $avatar = $fileName;

      $stmt = $conn->prepare("UPDATE users SET name = ?, avatar = ? WHERE id = ?");
      $stmt->bind_param("ssi", $newName, $avatar, $userId);
      $stmt->execute();
      $_SESSION['user_name'] = $newName;
      $_SESSION['user_avatar'] = $avatar;
    }
  } else {
    $stmt = $conn->prepare("UPDATE users SET name = ? WHERE id = ?");
    $stmt->bind_param("si", $newName, $userId);
    $stmt->execute();
    $_SESSION['user_name'] = $newName;
  }

  header("Location: profile.php");
  exit;
}

$content = 'profile_content.php';
include '../includes/layout.php';
?>