<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tran_id = $_POST['tran_id'];

    // Verify payment with SSLCommerz (optional)
    $update = $conn->prepare("UPDATE enrollments SET payment_status = 'completed' WHERE transaction_id = ?");
    $update->bind_param("s", $tran_id);
    $update->execute();
    $update->close();

 ?>

 <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment Success</title>
  <style>
    body {
      margin: 0;
      background: #f4f6f9;
      font-family: "Segoe UI", sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .modal-box {
      background: #e6fff3;
      padding: 30px 40px;
      border-left: 6px solid #28a745;
      border-radius: 12px;
      text-align: center;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      animation: fadeIn 0.4s ease;
    }

    .modal-box .icon {
      font-size: 40px;
      color: #28a745;
      margin-bottom: 20px;
    }

    .modal-box p {
      font-size: 18px;
      color: #28a745;
      margin-bottom: 20px;
      font-weight: 600;
    }

    .modal-box a {
      display: inline-block;
      background: #6c63ff;
      color: #fff;
      padding: 10px 20px;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 600;
      transition: background 0.3s ease;
    }

    .modal-box a:hover {
      background: #574bff;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: scale(0.95); }
      to { opacity: 1; transform: scale(1); }
    }
  </style>
</head>
<body>
  <div class="modal-box">
    <div class="icon">✅</div>
    <p>Payment Successful!<br>Your enrollment is confirmed.</p>
    <a href="/creativityfreaks/pages/dashboard.php">Go to Dashboard</a>
  </div>
</body>
</html>
<?php
} else {
    echo "Invalid Access.";
}
?>
