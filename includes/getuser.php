<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user']['id'])) {
    header("Location: /tests/login.php");
    exit();
}

include_once 'db.php';

$user = null;

if (isset($_SESSION['user']['id'])) {
    $id = $_SESSION['user']['id'];

    $stmt = $conn->prepare("SELECT id, profile_pic, name, email, role, reg_date FROM users WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
    }

    $stmt->close();
    // $conn->close();
}
