<?php
session_start();
include '../db.php';

header('Content-Type: application/json');

// Check if doctor is logged in
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'doctor') {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$doctor_id = $_SESSION['user']['id'];
$status1 = 'completed';
$status2 = 'confirmed';

// Prepare statement
$stmt = $conn->prepare("
    SELECT a.id, a.reason, a.status, a.doctor_id, a.appointment_time,
           u.name AS student_name, u.id AS student_id, u.profile_pic,
           n.note, n.last_updated 
    FROM appointments a
    JOIN users u ON a.student_id = u.id
    LEFT JOIN notes n ON n.student_id = u.id
    WHERE a.doctor_id = ? 
    AND a.appointment_time >= CURTIME()
    AND (a.status = ? OR a.status = ?)
");

// Bind all 3 params in one call
$stmt->bind_param("iss", $doctor_id, $status1, $status2);
$stmt->execute();
$result = $stmt->get_result();

$appointments = [];
while ($row = $result->fetch_assoc()) {
    $appointments[] = [
        'id' => $row['id'],
        'student_id' => $row['student_id'],
        'reason' => $row['reason'],
        'student' => $row['student_name'],
        'status' => $row['status'],
        'doctor_id' => $row['doctor_id'],
        'time' => $row['appointment_time'],
        'profile' => $row['profile_pic'],
        'note' => $row['note'],
        'last_updated' => $row['last_updated']
    ];
}

echo json_encode($appointments);
?>
