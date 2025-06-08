<?php
session_start();

require_once __DIR__ . '/../../includes/db.php';


if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'instructor') {
    header('Location: /creativityfreaks/index.php');
    exit;
}


/**
 * Get total number of registered users
 */
function getInstructorCourseCount($instructor_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT COUNT(*) FROM courses WHERE instructor_id = ?");
    $stmt->bind_param("i", $instructor_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    return $count;
}

function getInstructorStudentCount($instructor_id) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT COUNT(DISTINCT e.user_id)
        FROM enrollments e
        JOIN courses c ON e.course_id = c.id
        WHERE c.instructor_id = ?
    ");
    $stmt->bind_param("i", $instructor_id);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    return $count;
}

function getInstructorTotalRevenue($instructor_id) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT SUM(e.amount_paid)
        FROM enrollments e
        JOIN courses c ON e.course_id = c.id
        WHERE c.instructor_id = ? AND e.payment_status = 'completed'
    ");
    $stmt->bind_param("i", $instructor_id);
    $stmt->execute();
    $stmt->bind_result($total);
    $stmt->fetch();
    return $total ?? 0;
}


$content = 'dashboard.php';
include '../../includes/layout.php';
?>
