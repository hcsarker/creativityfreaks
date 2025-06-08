<?php
session_start();
require_once '../../includes/db.php';


if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'instructor') {
    header('Location: ../../index.php');
    exit;
}

$instructorId = $_SESSION['user_id'];

// Fetch courses created by this instructor
$sql = "SELECT c.id, c.title, c.thumbnail, c.description, c.category, c.subcategory, c.price, c.created_at, 
               (SELECT COUNT(*) FROM enrollments e WHERE e.course_id = c.id AND e.payment_status = 'completed') AS enrolled_students
        FROM courses c
        WHERE c.instructor_id = ?
        ORDER BY c.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $instructorId);
$stmt->execute();
$result = $stmt->get_result();


$content = 'my_courses_content.php';
include '../../includes/layout.php';
?>

