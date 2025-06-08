<?php
require_once '../includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') {
    $transaction_id = $_POST['tran_id'] ?? ($_GET['tran_id'] ?? '');
    $amount = $_POST['amount'] ?? ($_GET['amount'] ?? 0);
    $payment_status = $_POST['status'] ?? ($_GET['status'] ?? '');

    if (empty($transaction_id)) {
        echo json_encode(['status' => 'error', 'message' => 'Transaction ID missing']);
        exit;
    }

    // Get the pending enrollment
    $stmt = $conn->prepare("SELECT id, user_id, course_id FROM enrollments WHERE transaction_id = ? AND payment_status = 'pending'");
    $stmt->bind_param("s", $transaction_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'No matching pending enrollment']);
        exit;
    }

    $row = $result->fetch_assoc();
    $user_id = $row['user_id'];
    $course_id = $row['course_id'];
    $enrollment_id = $row['id'];

    $method = "SSLCommerz";
    $verified = ($payment_status === 'VALID') ? 1 : 0;

    if ($verified) {
        // Update the record
        $update = $conn->prepare("UPDATE enrollments 
            SET payment_status = 'completed', payment_method = ?, payment_verified = 1 
            WHERE id = ?");
        $update->bind_param("si", $method, $enrollment_id);

        if ($update->execute()) {
            header("Location: course_details.php?id=$course_id&enrolled=1");
            exit;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database update failed']);
            exit;
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Payment not verified']);
        exit;
    }
}
?>
