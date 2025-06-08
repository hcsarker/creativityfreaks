<?php
 session_start();
require_once '../includes/db.php'; 
require_once '../includes/send_mail.php';

$message_sent = false;
$success = '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Validate input
    if ($name && $email && $subject && $message && filter_var($email, FILTER_VALIDATE_EMAIL)) {

        // Save to database
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $subject, $message);
        $stmt->execute();
        $stmt->close();

        // Admin email (you can replace it with your own email)
        $admin_email = "hridoy.pstu.cse19@gmail.com";

        // Send using PHPMailer
        $sent = sendContactEmail($admin_email, $name, $email, $subject, $message);

        if ($sent) {
            $success = "Message sent successfully!";
            $message_sent = true;
        } else {
            $error = "Saved to database, but email failed to send.";
        }
    } else {
        $error = "Please fill out all fields correctly.";
    }
}

$content = 'contact_content.php';
include '../includes/layout.php';
?>
