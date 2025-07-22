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
        $_SESSION['user'] = [
          'id' => $user['id'],
          'name' => $user['name'],
          'role' => $user['role']
        ];


        // Redirect based on role
        switch ($user['role']) {
          case 'admin':
            header("Location: /views/admin/admin.php");
            break;
          case 'doctor':
            header("Location: /views/doctor/doctor.php");
            break;
          case 'student':
            header("Location: /views/student/dashboard.php");
            break;
          default:
            header("Location: /pending.html");
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
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <script src="../../assets/index.js" defer></script>
  <link rel="stylesheet" href="../../assets/index.css">
  <link rel="shortcut icon" href="../../assets/logo2.jpeg" type="image/x-icon">
</head>

<body class="">

  <!--Loader Start-->
  <div class="loader" id="loader">
    <div class="load" id="load"></div>
  </div>
  <!--Loader End-->
  <div class="flex w-full h-screen items-center justify-center">

    <form action="login.php" method="post"
      class="flex flex-col p-[25px]! rounded-md bg-[var(--SecBg)] w-[500px] items-center gap-[25px] ">
      <h2 class="text-[4rem] font-bold text-center">Log In</h2>

      <input type="email" name="email" placeholder="Email" required class="p-[5px]! border-solid border-[2px] border-[var(--SecText)] rounded-md w-full placeholder:text-[var(--SecText)] outline-none">
      <input type="password" name="password" placeholder="Password" required class="p-[5px]! border-solid border-[2px] border-[var(--SecText)] rounded-md w-full placeholder:text-[var(--SecText)] outline-none">

      <button type="submit" name="submit" class="p-[10px]! px-[20px]! font-bold uppercase text-[1rem] rounded-md bg-transparent border-solid border-[2px] border-[var(--MainText)] hover:bg-[var(--MainText)] hover:text-[var(--MainBg)] duration-300 transition-all ">
        Sign In
      </button>
      <p>Don't have an account? <a href="./signup.php" class="underline hover:text-[var(--Highlight)] ">Create one</a></p>

      <?php if (!empty($message)): ?>
        <p class="text-red-600 text-sm text-center"><?= htmlspecialchars($message) ?></p>
      <?php endif; ?>
    </form>

  </div>

</body>

</html>