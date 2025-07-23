<?php
include_once 'C:\Users\calvi\Documents\GitHub\Healthmate-Kabarak\includes\getuser.php';
include_once 'C:\Users\calvi\Documents\GitHub\Healthmate-Kabarak\includes\db.php';

$doctorId = $_SESSION['user']['id'];

$stmt = $conn->prepare("SELECT COUNT(*) AS total_appointments FROM appointments WHERE doctor_id = ?");
$stmt->bind_param("i", $doctorId);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$totalAppointments = $row['total_appointments']; // ✅ This is the correct count
?>