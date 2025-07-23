<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../includes/db.php'; // your DB connection
require '../includes/PHPMailer/PHPMailer.php';
require '../includes/PHPMailer/SMTP.php';
require '../includes/PHPMailer/Exception.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);

    // 1. Check if user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $userResult = $stmt->get_result();

    if ($userResult->num_rows === 0) {
        $error = "No account found with that email.";
    } else {
        // 2. Generate token & expiry
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+30 minutes'));

        // 3. Delete any old tokens for this email
        $deleteStmt = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
        if ($deleteStmt) {
            $deleteStmt->bind_param("s", $email);
            $deleteStmt->execute();
            $deleteStmt->close();
        }

        // 4. Store token
        $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $token, $expiresAt);
        $stmt->execute();

        // 5. Send the email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'calvinkmwaura@gmail.com'; // replace
            $mail->Password   = 'mmfb qyud btuw iltx';    // replace
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('calvinkmwaura@gmail.com', 'Healthmate Support');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $resetLink = "http://127.0.0.1/auth/resetpassword.php?token=$token";

            $mail->Body = "Click the link to reset your password:<br><a href='$resetLink'>$resetLink</a>";
            $mail->AltBody = "Copy this link to reset your password: $resetLink";
            $mail->send();

            $success = "Reset link has been sent to your email.";
        } catch (Exception $e) {
            $error = "Mailer Error: " . $mail->ErrorInfo;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">
    <form method="POST" class="bg-white p-6 rounded shadow-md w-full max-w-sm">
        <h2 class="text-2xl font-semibold mb-4">Forgot Password</h2>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 text-red-700 p-2 mb-4 rounded"><?= $error ?></div>
        <?php elseif (isset($success)): ?>
            <div class="bg-green-100 text-green-700 p-2 mb-4 rounded"><?= $success ?></div>
        <?php endif; ?>

        <input type="email" name="email" placeholder="Enter your email" required
               class="w-full p-2 mb-4 border border-gray-300 rounded">
        <button type="submit" name="submit"
                class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">
            Send Reset Link
        </button>
    </form>
</body>
</html>
