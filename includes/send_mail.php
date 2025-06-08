<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/PHPMailer/src/Exception.php';
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';

function sendContactEmail($to, $name, $email, $subject, $bodyMessage) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'hridoy.pstu.cse19@gmail.com';          // ✅ Your Gmail
        $mail->Password   = 'vumw yirn ysbm xgxn';        // ✅ App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('your-email@gmail.com', 'Creativity Freaks Contact');
        $mail->addAddress($to); // admin email

        // Content
        $mail->isHTML(false);
        $mail->Subject = $subject;
        $mail->Body    = "You have received a new message from $name ($email):\n\n$bodyMessage";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('Mailer Error: ' . $mail->ErrorInfo);
        return false;
    }
}
