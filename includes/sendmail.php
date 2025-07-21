<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

// Create instance
$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'calvinkmwaura@gmail.com';       // Your Gmail
    $mail->Password   = 'mmfb qyud btuw iltx';    // App Password
    $mail->SMTPSecure = 'tls'; // Or PHPMailer::ENCRYPTION_SMTPS
    $mail->Port       = 587;

    // Sender & recipient
    $mail->setFrom('calvinkmwaura@gmail.com', 'Healthmate Kabarak');
    $mail->addAddress('kanvazweb@gmail.com', 'User Name');

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Password Reset';
    $mail->Body    = 'Click here to reset your password: <a href="https://yourdomain.com/reset-password.php?token=xyz">Reset Password</a>';
    $mail->AltBody = 'Use this link to reset your password: https://yourdomain.com/reset-password.php?token=xyz';

    $mail->send();
    echo 'Email sent successfully!';
} catch (Exception $e) {
    echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
