<?php
require 'db.php';

$token = $_GET['token'] ?? '';
$valid = false;
$error = '';
$success = '';

// Step 1: Validate token
if ($token) {
    $stmt = $conn->prepare("SELECT email, expires_at FROM password_resets WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $data = $result->fetch_assoc();
        $email = $data['email'];
        $expires = strtotime($data['expires_at']);
        $now = time();

        if ($now <= $expires) {
            $valid = true;
        } else {
            $error = "Token has expired.";
        }
    } else {
        $error = "Invalid token.";
    }
} else {
    $error = "No token provided.";
}

// Step 2: Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && $valid) {
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm'];

    if ($newPassword !== $confirmPassword) {
        $error = "Passwords do not match.";
    } elseif (strlen($newPassword) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update user password
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashed, $email);
        $stmt->execute();

        // Delete token
        $stmt = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $success = "Password updated successfully. You can now log in.";
        $valid = false;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Reset Password</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">
  <form method="POST" class="bg-white p-6 rounded shadow-md w-full max-w-md">
    <h2 class="text-2xl font-semibold mb-4">Reset Password</h2>

    <?php if ($error): ?>
      <div class="bg-red-100 text-red-700 p-2 mb-4 rounded"><?= $error ?></div>
    <?php elseif ($success): ?>
      <div class="bg-green-100 text-green-700 p-2 mb-4 rounded"><?= $success ?></div>
    <?php endif; ?>

    <?php if ($valid): ?>
      <input type="password" name="password" placeholder="New Password" required class="w-full p-2 mb-3 border rounded">
      <input type="password" name="confirm" placeholder="Confirm Password" required class="w-full p-2 mb-4 border rounded">
      <button type="submit" name="submit" class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">Reset</button>
    <?php else: ?>
      <a href="tests/login.php" class="text-blue-600 underline">Go to Login</a>
    <?php endif; ?>
  </form>
</body>
</html>
