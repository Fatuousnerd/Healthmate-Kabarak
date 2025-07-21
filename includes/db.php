<?php
// db.php

$host = "127.0.0.1";       // or 127.0.0.1
$user = "sudo";            // default for local dev (XAMPP, etc.)
$password = "sudo";            // empty for local usually
$database = "healthmate";   // name of your database

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
