<?php
// db.php

$host = "127.0.0.1";       // or localhost
$user = "root";            // default for local dev
$password = "";            // empty for local user
$database = "healthmate";   // name of database

if (!isset($conn)) {

    $conn = new mysqli($host, $user, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
}
?>