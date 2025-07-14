<?php
include __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password'];
    $role = 'student'; // or your token logic
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
                mkdir($uploadDir, 0755, true); // creates folder if it doesn't exist
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
        echo "User registered successfully!";
    } else {
        echo "Error: " . $stmt->error;
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
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-100 py-10">

    <form action="auth.php" method="post" enctype="multipart/form-data"
        class="flex flex-col gap-4 w-full max-w-sm mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4 text-center">Register</h2>

        <input type="file" name="profile_pic" accept="image/*" class="p-2 border rounded">
        <input type="text" name="name" placeholder="Full Name" required class="p-2 border rounded">
        <input type="email" name="email" placeholder="Email" required class="p-2 border rounded">
        <input type="password" name="password" placeholder="Password" required class="p-2 border rounded">

        <!-- <select name="role" required class="p-2 border rounded">
      <option value="student">Student</option>
      <option value="doctor">Doctor</option>
      <option value="admin">Admin</option>
    </select> -->

        <button type="submit" name="submit" class="bg-blue-600 text-white p-2 rounded hover:bg-blue-700">Sign
            Up</button>

        <?php if (!empty($message)): ?>
            <p class="text-center mt-3 <?= str_contains($message, 'Error') ? 'text-red-600' : 'text-green-600' ?>">
                <?= $message ?>
            </p>
        <?php endif; ?>
    </form>

</body>

</html>