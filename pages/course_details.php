<?php
session_start();
require_once '../includes/db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: courses.php");
    exit;
}

$course_id = intval($_GET['id']);

// Get course info
$sql = "SELECT c.*, u.name AS instructor_name 
        FROM courses c 
        JOIN users u ON c.instructor_id = u.id 
        WHERE c.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();

if (!$course) {
    echo "<h2>Course not found</h2>";
    exit;
}

$content = 'course_details_content.php';
include '../includes/layout.php';
?>
