<?php 
session_start();
require_once '../includes/db.php';



// Fetch batch categories from database
$categories = [];
$result = $conn->query("SELECT DISTINCT category FROM batches");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row['category'];
    }
}

$content = 'exam_batch_content.php';
include '../includes/layout.php';
?>
