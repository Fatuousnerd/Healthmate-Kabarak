<?php
session_start();
include '../includes/db.php'; // adjust path as needed

$message = "";

// Handle login request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate input
    if (empty($email) || empty($password)) {
        $message = "Please enter both email and password.";
    } else {
        // Prepare and execute user lookup
        $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $user = $result->fetch_assoc()) {
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];

                // Redirect based on role
                switch ($user['role']) {
                    case 'admin':
                        header("Location: /admin/dashboard.php");
                        break;
                    case 'doctor':
                        header("Location: /views/doctor/doctor.php");
                        break;
                    default:
                        header("Location: /student/dashboard.php");
                        break;
                }
                exit();
            } else {
                $message = "Invalid password.";
            }
        } else {
            $message = "User not found.";
        }
        $stmt->close();
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 py-10">
  <form action="login.php" method="post" class="flex flex-col gap-4 w-full max-w-sm mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold text-center">Log In</h2>
    
    <input type="email" name="email" placeholder="Email" required class="p-2 border rounded">
    <input type="password" name="password" placeholder="Password" required class="p-2 border rounded">
    
    <button type="submit" name="submit" class="bg-blue-600 text-white p-2 rounded hover:bg-blue-700">
      Sign In
    </button>

    <?php if (!empty($message)) : ?>
      <p class="text-red-600 text-sm text-center"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
  </form>
</body>
</html>
