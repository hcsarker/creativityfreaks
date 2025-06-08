<?php
require_once '../includes/db.php';

$categoryStructure = [
  'Academic' => ['Class 6-10', 'Class 11-12', 'Undergraduate'],
  'Admission' => ['Medical', 'Engineering', 'GST Varsity', 'Agri Cluster', 'Nursing'],
  'Skill' => ['Web Development', 'Graphic Design', 'Digital Marketing', 'UI/UX', 'Freelancing'],
  'Language' => ['English', 'Bangla', 'Arabic', 'Japanese', 'Korean'],
  'ToT' => [ 'Instructor']
];

$main = $_POST['main'] ?? 'All';
$sub = $_POST['subcategory'] ?? 'All';

// Initialize variables
$courses = [];
$subcategories = [];

if ($main !== 'All' && $sub !== 'All') {
  // Filter by category and subcategory
  $stmt = $conn->prepare("SELECT * FROM courses WHERE category = ? AND subcategory = ?");
  $stmt->bind_param("ss", $main, $sub);
  $stmt->execute();
  $result = $stmt->get_result();

} elseif ($main !== 'All') {
  // Filter by category only
  $stmt = $conn->prepare("SELECT * FROM courses WHERE category = ?");
  $stmt->bind_param("s", $main);
  $stmt->execute();
  $result = $stmt->get_result();

} else {
  // No filter - show all courses
  $result = $conn->query("SELECT * FROM courses");
}

// Fetch courses
while ($row = $result->fetch_assoc()) {
  $courses[] = $row;
}

// Set subcategories if a main category is selected, else empty
$subcategories = ($main !== 'All' && isset($categoryStructure[$main])) ? $categoryStructure[$main] : [];

echo json_encode([
  'courses' => $courses,
  'subcategories' => $subcategories
]);
