<?php
require_once __DIR__ . '/env.php';

$host = getenv('CF_DB_HOST') ?: 'localhost';
$db   = getenv('CF_DB_NAME') ?: 'creativity_freaks';
$user = getenv('CF_DB_USER') ?: 'root';
$pass = getenv('CF_DB_PASS') ?: '';

$conn = @new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    error_log('Database connection failed: ' . $conn->connect_error);
    http_response_code(500);
    die('Service temporarily unavailable.');
}

$conn->set_charset('utf8mb4');
?>