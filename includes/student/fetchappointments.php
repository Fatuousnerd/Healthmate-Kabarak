<?php
session_start();
include '../db.php';

header('Content-Type: application/json');

// Authorization check
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$student_id = $_SESSION['user']['id'];
$date = $_GET['date'] ?? null;

if (!$date) {
    echo json_encode(['error' => 'Missing date']);
    exit;
}


// Prepare and execute the cleaned query
$stmt = $conn->prepare("
    SELECT 
        d.name AS doctor_name,
        a.status,
        a.appointment_date,
        a.appointment_time,
        a.reason
    FROM appointments a
    JOIN users d ON a.doctor_id = d.id
    WHERE a.student_id = ?
      AND a.appointment_date = ?
");

$stmt->bind_param("is", $student_id, $date);

$stmt->execute();
$result = $stmt->get_result();

$appointments = [];
while ($row = $result->fetch_assoc()) {
    $appointments[] = [
        'doctor_name'      => $row['doctor_name'],
        'status'           => $row['status'],
        'appointment_date' => $row['appointment_date'],
        'appointment_time' => $row['appointment_time'],
        'reason'           => $row['reason']
    ];
}

echo json_encode($appointments);
?>
