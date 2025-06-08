<?php
session_start();
include '../../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /creativityfreaks/index.php");
    exit;
}

if ($_SESSION['user_role'] !== 'admin') {
    // Jodi admin na hoy, home e pathano
    header("Location: /creativityfreaks/index.php");
    exit;
}

function getTotalUsers() {
    global $conn;
    $result = $conn->query("SELECT COUNT(*) FROM users");
    return $result->fetch_row()[0];
}

function getTotalCourses() {
    global $conn;
    $result = $conn->query("SELECT COUNT(*) FROM courses");
    return $result->fetch_row()[0];
}

function getInstructorCount() {
    global $conn;
    $result = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'instructor'");
    return $result ? $result->fetch_row()[0] : 0;
}

function getReviewCount() {
    global $conn;
    $result = $conn->query("SELECT COUNT(*) FROM reviews");
    return $result ? $result->fetch_row()[0] : 0;   
}

$content = 'dashboard.php';
include '../../includes/layout.php';
?>
