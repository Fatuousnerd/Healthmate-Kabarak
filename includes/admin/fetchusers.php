<?php
require_once '../db.php'; // or wherever your DB connection is

$query = "SELECT * FROM `users` WHERE 1;";
$result = mysqli_query($conn, $query);

$users = [];

while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

header('Content-Type: application/json');
echo json_encode($users);
?>
