<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'student') {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_id'])) {
    $course_id = intval($_POST['course_id']);
    $user_id = $_SESSION['user_id'];

    // Check if already enrolled
    $check_sql = "SELECT * FROM enrollments WHERE course_id = ? AND user_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $course_id, $user_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        header("Location: course_details.php?id=$course_id&enrolled=exists");
        exit;
    }

    // Insert new enrollment
    $sql = "INSERT INTO enrollments (user_id, course_id, enrolled_at) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $course_id);

    if ($stmt->execute()) {
        header("Location: course_details.php?id=$course_id&enrolled=1");
    } else {
        echo "Enrollment failed. Please try again.";
    }
} else {
    header("Location: courses.php");
}
?>
