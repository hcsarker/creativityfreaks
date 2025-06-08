<?php
session_start();
require_once 'db.php'; // DB connection

// Load batch info
if (!isset($_POST['batch_id']) || !isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized request']);
    exit;
}

$batch_id = intval($_POST['batch_id']);
$user_id = $_SESSION['user_id'];

// Fetch user info
$user_stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();
$user_stmt->close();

if (!$user) {
    echo json_encode(['status' => 'error', 'message' => 'User not found']);
    exit;
}

// Fetch batch info
$batch_stmt = $conn->prepare("SELECT * FROM batches WHERE id = ?");
$batch_stmt->bind_param("i", $batch_id);
$batch_stmt->execute();
$batch_result = $batch_stmt->get_result();
$batch = $batch_result->fetch_assoc();
$batch_stmt->close();

if (!$batch) {
    echo json_encode(['status' => 'error', 'message' => 'Batch not found']);
    exit;
}

$tran_id = uniqid('CF_'); // Unique transaction ID
$amount = floatval($batch['discounted_price']);

// Check if already enrolled (pending or completed)
$check = $conn->prepare("SELECT id FROM enrollments WHERE user_id = ? AND batch_id = ? AND payment_status IN ('pending', 'completed')");
$check->bind_param("ii", $user_id, $batch_id);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Already enrolled in this batch']);
    $check->close();
    exit;
}
$check->close();


// Save pending enrollment
$insert = $conn->prepare("INSERT INTO enrollments (user_id, batch_id, transaction_id, payment_status) VALUES (?, ?, ?, 'pending')");
$insert->bind_param("iis", $user_id, $batch_id, $tran_id);
$insert->execute();
$insert->close();

// SSLCommerz API Parameters
$post_data = array(
    "store_id" => "creat6832130016b23",
    "store_passwd" => "creat6832130016b23@ssl",
    "total_amount" => $amount,
    "currency" => "BDT",
    "tran_id" => $tran_id,
    "success_url" => "http://localhost/creativityfreaks/payment/success.php",
    "fail_url" => "http://localhost/creativityfreaks/payment/fail.php",
    "cancel_url" => "http://localhost/creativityfreaks/payment/cancel.php",
    "cus_name" => $user['name'],
    "cus_email" => $user['email'],
    "cus_add1" => "Dhaka",
    "cus_phone" => "01711111111",
    "shipping_method" => "NO",
    "product_name" => $batch['title'],
    "product_category" => "Batch",
    "product_profile" => "general"
);

// Initiate SSLCommerz Payment Session
$direct_api_url = "https://sandbox.sslcommerz.com/gwprocess/v3/api.php";

$handle = curl_init();
curl_setopt($handle, CURLOPT_URL, $direct_api_url);
curl_setopt($handle, CURLOPT_TIMEOUT, 30);
curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($handle, CURLOPT_POST, 1);
curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($post_data));
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
$content = curl_exec($handle);
$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

if ($code == 200 && !curl_errno($handle)) {
    $response = json_decode($content, true);
    if (isset($response['GatewayPageURL']) && $response['GatewayPageURL'] != "") {
        echo json_encode(['status' => 'success', 'url' => $response['GatewayPageURL']]);
        exit;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to connect to payment gateway']);
        exit;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Connection error']);
    exit;
}
?>
