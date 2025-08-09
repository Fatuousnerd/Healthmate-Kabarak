<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $userId = $_SESSION['user']['id'];
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));

    // Check if email is taken by another user
    $check = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $check->bind_param("si", $email, $userId);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "Email already in use by another user.";
        exit;
    }

    // Handle profile pic upload (optional)
    $profilePicName = null;
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === 0) {
        $fileTmp = $_FILES['profile_pic']['tmp_name'];
        $fileName = basename($_FILES['profile_pic']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExt, $allowed)) {
            $profilePicName = uniqid('img_', true) . '.' . $fileExt;
            $uploadDir = __DIR__ . '/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $uploadPath = $uploadDir . $profilePicName;
            move_uploaded_file($fileTmp, $uploadPath);
        }
    }

    // Update statement
    if ($profilePicName) {
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, profile_pic = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $email, $profilePicName, $userId);
    } else {
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $email, $userId);
    }

    if ($stmt->execute()) {
        echo "Profile updated successfully.";
        header("Location: /views/doctor/profile.php"); 
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
