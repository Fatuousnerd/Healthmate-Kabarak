<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /tests/login.php");
    exit();
}

include 'db.php';

$user = null;

if (isset($_SESSION['user_id'])) {
    $id = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT id, profile_pic, name, email, role, reg_date FROM users WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
    }

    $stmt->close();
    $conn->close();
}
