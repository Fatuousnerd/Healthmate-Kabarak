<?php
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $user_id = $_POST['user_id'];

    if ($action === 'update') {
        $role = $_POST['role'];

        $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param("si", $role, $user_id);
        $stmt->execute();

        echo "updated";
        header("Location: ../../views/admin/admin.php");
    }

    if ($action === 'delete') {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        echo "deleted";
    }
}
?>
