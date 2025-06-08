<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tran_id = $_POST['tran_id'];

    $delete = $conn->prepare("DELETE FROM enrollments WHERE transaction_id = ?");
    $delete->bind_param("s", $tran_id);
    $delete->execute();
    $delete->close();

    echo "<h2>Payment Canceled!</h2><p>Your transaction was canceled.</p>";
}
