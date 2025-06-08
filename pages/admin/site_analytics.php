<?php
session_start();
require_once '../../includes/db.php';

// Check admin login
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: /creativityfreaks/index.php');
    exit;
}

$pageTitle = 'Site Analytics';

$startDate = $_GET['start_date'] ?? date('Y-m-01', strtotime('-11 months'));
$endDate = $_GET['end_date'] ?? date('Y-m-t');
$category = $_GET['category'] ?? null;
$subcategory = $_GET['subcategory'] ?? null;

$startDateSql = date('Y-m-d', strtotime($startDate));
$endDateSql = date('Y-m-d', strtotime($endDate));

function getMonthlyRevenue($conn, $startDate, $endDate, $category = null, $subcategory = null)
{
    $sql = "SELECT DATE_FORMAT(enrolled_at, '%Y-%m') AS month, SUM(amount_paid) AS revenue
            FROM enrollments
            INNER JOIN courses ON enrollments.course_id = courses.id
            WHERE payment_status = 'completed'
              AND enrolled_at BETWEEN ? AND ?";
    
    $params = [$startDate, $endDate];
    
    if ($category) {
        $sql .= " AND courses.category = ?";
        $params[] = $category;
    }
    if ($subcategory) {
        $sql .= " AND courses.subcategory = ?";
        $params[] = $subcategory;
    }

    $sql .= " GROUP BY month ORDER BY month";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[$row['month']] = $row['revenue'] ?: 0;
    }
    return $data;
}


function getTopCourses($conn) {
    $sql = "SELECT c.title, COUNT(e.id) AS enroll_count
            FROM courses c
            LEFT JOIN enrollments e ON e.course_id = c.id AND e.payment_status = 'completed'
            GROUP BY c.id ORDER BY enroll_count DESC LIMIT 5";
    $result = $conn->query($sql);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

function getPopularInstructors($conn) {
    $sql = "SELECT u.name AS instructor_name, COUNT(e.id) AS total_enrollments
            FROM users u
            JOIN courses c ON c.instructor_id = u.id
            LEFT JOIN enrollments e ON e.course_id = c.id AND e.payment_status = 'completed'
            WHERE u.role = 'instructor'
            GROUP BY u.id
            ORDER BY total_enrollments DESC LIMIT 5";
    $result = $conn->query($sql);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

function getCategories($conn) {
    $categories = [];
    $catRes = $conn->query("SELECT DISTINCT category FROM courses ORDER BY category");
    while ($row = $catRes->fetch_assoc()) {
        $categories[] = ['id' => $row['category'], 'name' => $row['category']];
    }
    return $categories;
}


$revenueData = getMonthlyRevenue($conn, $startDateSql, $endDateSql, $category, $subcategory);

$topCourses = getTopCourses($conn);
$popularInstructors = getPopularInstructors($conn);
$categories = getCategories($conn);

// Fetch monthly user registrations (last 12 months)
function getMonthlyUserRegistrations($conn) {
    $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, COUNT(*) AS count 
            FROM users 
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            GROUP BY month ORDER BY month";
    $result = $conn->query($sql);
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[$row['month']] = $row['count'];
    }
    return $data;
}

// Fetch monthly courses added (last 12 months)
function getMonthlyCoursesAdded($conn) {
    $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, COUNT(*) AS count 
            FROM courses 
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            GROUP BY month ORDER BY month";
    $result = $conn->query($sql);
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[$row['month']] = $row['count'];
    }
    return $data;
}

// Prepare data for charts
$userRegs = getMonthlyUserRegistrations($conn);
$courseAdds = getMonthlyCoursesAdded($conn);

// Fill missing months with zero counts for charts continuity
function fillMissingMonths($data) {
    $filled = [];
    $start = new DateTime('-11 months');
    $end = new DateTime();

    while ($start <= $end) {
        $key = $start->format('Y-m');
        $filled[$key] = $data[$key] ?? 0;
        $start->modify('+1 month');
    }
    return $filled;
}

$userRegs = fillMissingMonths($userRegs);
$courseAdds = fillMissingMonths($courseAdds);


$content = 'site_visual.php';
include '../../includes/layout.php';
?>
