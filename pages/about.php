<?php 
session_start();
require_once '../includes/db.php';


// Get course count (remove is_active condition if column doesn't exist)
$course_count = 0;
$course_query = "SELECT COUNT(*) as count FROM courses"; // Removed WHERE is_active = TRUE
$course_result = $conn->query($course_query);
if ($course_result) {
    $course_data = $course_result->fetch_assoc();
    $course_count = $course_data['count'];
}

// Get active learner count (use status column if it exists instead of is_active)
$learner_count = 0;
$learner_query = "SELECT COUNT(*) as count FROM users WHERE role = 'student'"; // Removed AND is_active = TRUE
$learner_result = $conn->query($learner_query);
if ($learner_result) {
    $learner_data = $learner_result->fetch_assoc();
    $learner_count = $learner_data['count'];
}

// Get instructor count
$instructor_count = 0;
$instructor_query = "SELECT COUNT(*) as count FROM users WHERE role = 'instructor'"; // Removed AND is_active = TRUE
$instructor_result = $conn->query($instructor_query);
if ($instructor_result) {
    $instructor_data = $instructor_result->fetch_assoc();
    $instructor_count = $instructor_data['count'];
}

$satisfaction_rate = "95%"; // Default or implement dynamic rating later



$content = 'about_content.php';
include '../includes/layout.php';
?>

