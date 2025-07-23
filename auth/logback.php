<?php
include '../includes/getuser.php';

// Redirect based on role
switch ($user['role']) {
    case 'admin':
        header("Location: /views/admin/admin.php");
        break;
    case 'doctor':
        header("Location: /views/doctor/dashboard.php");
        break;
    case 'student':
        header("Location: /views/student/dashboard.php");
        break;
    default:
        header("Location: /pending.html");
        break;
}
exit();

?>