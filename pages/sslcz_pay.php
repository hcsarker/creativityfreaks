<?php
session_start();
header('Content-Type: application/json');
require_once '../includes/db.php'; // adjust if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_id'])) {
    $course_id = intval($_POST['course_id']);
    $user_id = $_SESSION['user_id'] ?? null;

    if (!$user_id) {
        echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
        exit;
    }

    // Fetch course info
    $stmt = $conn->prepare("SELECT title, price FROM courses WHERE id = ?");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $course = $result->fetch_assoc();

    if (!$course) {
        echo json_encode(['status' => 'error', 'message' => 'Course not found']);
        exit;
    }

    // Prepare payment
    $tran_id = uniqid('CF_'); // Unique transaction ID
    $amount = $course['price'];

    $post_data = [
        'store_id' => 'creat6832130016b23',
        'store_passwd' => 'creat6832130016b23@ssl',
        'total_amount' => $amount,
        'currency' => 'BDT',
        'tran_id' => $tran_id,
        'success_url' => "http://localhost/creativityfreaks/pages/payment_success.php",

        'fail_url' => 'http://localhost/creativityfreaks/pages/payment_fail.php',
        'cancel_url' => 'http://localhost/creativityfreaks/pages/payment_cancel.php',
        'cus_name' => $_SESSION['user_name'],
        'cus_email' => $_SESSION['user_email'],
        'cus_add1' => 'Dhaka',
        'cus_phone' => '0123456789',
        'product_name' => $course['title'],
        'product_category' => 'Online Course',
        'product_profile' => 'general',
    ];

    $url = "https://sandbox.sslcommerz.com/gwprocess/v3/api.php"; // for Easy Checkout

    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_POST, true);
    curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($handle);
    $result = json_decode($response, true);

    if (isset($result['status']) && $result['status'] === 'SUCCESS') {
        // Optionally save pending enrollment
        $stmt = $conn->prepare("INSERT INTO enrollments (user_id, course_id, payment_status, transaction_id, amount_paid)
                                VALUES (?, ?, 'pending', ?, ?)");
        $stmt->bind_param("iisd", $user_id, $course_id, $tran_id, $amount);
        $stmt->execute();

        echo json_encode(['status' => 'success', 'url' => $result['GatewayPageURL']]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Payment gateway error']);
        }
    } else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Request']);
}