<?php
$host = 'localhost';
$db   = 'creativity_freaks';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

// Set charset to prevent encoding issues
$conn->set_charset("utf8mb4");
?>