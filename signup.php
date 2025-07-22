<?php
include 'includes/db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];
    $role = 'pending';
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Handle image upload
    $profilePicName = 'default.jpg';
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === 0) {
        $fileTmp = $_FILES['profile_pic']['tmp_name'];
        $fileName = basename($_FILES['profile_pic']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExt, $allowed)) {
            $newName = uniqid('img_', true) . '.' . $fileExt;
            $uploadDir = __DIR__ . '/uploads/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $uploadPath = $uploadDir . $newName;
            move_uploaded_file($fileTmp, $uploadPath);
            $profilePicName = $newName;
        }
    }

    // Insert user
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, profile_pic) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $hashedPassword, $role, $profilePicName);

    if ($stmt->execute()) {
        $studentId = $conn->insert_id;

        // Safe insert into notes table only if user insert succeeded
        $noteStmt = $conn->prepare("INSERT INTO notes (student_id, note) VALUES (?, '')");
        $noteStmt->bind_param("i", $studentId);
        if (!$noteStmt->execute()) {
            $message = "Note creation error: " . $noteStmt->error;
        } else {
            $message = "User registered successfully!";
            header("Location: pending.html");
        }
        $noteStmt->close();
    } else {
        $message = "User insert error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="assets/index.js" defer></script>
    <link rel="stylesheet" href="assets/index.css">
    <link rel="shortcut icon" href="assets/logo2.jpeg" type="image/x-icon">
</head>

<body class="">

    <!--Loader Start-->
    <div class="loader" id="loader">
        <div class="load" id="load"></div>
    </div>
    <!--Loader End-->

    <div class="flex w-full h-screen items-center justify-center">
        <form action="signup.php" method="post" enctype="multipart/form-data"
            class="flex flex-col p-[25px]! rounded-md bg-[var(--SecBg)] w-[350px] lg:w-[500px] items-center gap-[25px] ">
            <h2 class="text-[4rem] font-bold text-center">Register</h2>

            <input type="file" name="profile_pic" accept="image/*"
                class="p-[5px]! border-solid border-[2px] border-[var(--SecText)] rounded-md w-full placeholder:text-[var(--SecText)] outline-none">
            <input type="text" name="name" placeholder="Full Name" required
                class="p-[5px]! border-solid border-[2px] border-[var(--SecText)] rounded-md w-full placeholder:text-[var(--SecText)] outline-none">
            <input type="email" name="email" placeholder="Email" required
                class="p-[5px]! border-solid border-[2px] border-[var(--SecText)] rounded-md w-full placeholder:text-[var(--SecText)] outline-none">
            <input type="password" name="password" placeholder="Password" required
                class="p-[5px]! border-solid border-[2px] border-[var(--SecText)] rounded-md w-full placeholder:text-[var(--SecText)] outline-none">

            <button type="submit" name="submit"
                class="p-[10px]! px-[20px]! font-bold uppercase text-[1rem] rounded-md bg-transparent border-solid border-[2px] border-[var(--MainText)] hover:bg-[var(--MainText)] hover:text-[var(--MainBg)] duration-300 transition-all ">Sign
                Up</button>
            <div class="flex flex-col gap-[5px]">
                <p>Already have an account? <a href="/auth/login.php"
                        class="underline hover:text-[var(--Highlight)] ">Sign in</a></p>
                <p>Not an admin or doctor? <a href="/auth/signup.php"
                        class="underline hover:text-[var(--Highlight)] ">Sign up here</a></p>
            </div>

            <?php if (!empty($message)): ?>
                <p class="text-center mt-3 <?= str_contains($message, 'Error') ? 'text-red-600' : 'text-green-600' ?>">
                    <?= $message ?>
                </p>
            <?php endif; ?>
        </form>

    </div>
</body>

</html>