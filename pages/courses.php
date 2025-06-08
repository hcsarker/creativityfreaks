<?php 
session_start();
require_once '../includes/db.php';

$categoryStructure = [
  'Academic' => ['Class 6-10', 'Class 11-12', 'Undergraduate'],
  'Admission' => ['Medical', 'Engineering', 'GST Varsity', 'Agri Cluster', 'Nursing'],
  'Skill' => ['Web Development', 'Graphic Design', 'Digital Marketing', 'UI/UX', 'Freelancing'],
  'Language' => ['English', 'Bangla', 'Arabic', 'Japanese', 'Korean'],
  'ToT' => [ 'Instructor']
];


$content = 'courses_content.php';
include '../includes/layout.php';
?>
