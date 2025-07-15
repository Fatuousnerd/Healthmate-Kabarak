<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'doctor') {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$doctor_id = $_SESSION['user']['id'];
$date = $_GET['date'] ?? null;

if (!$date) {
    echo json_encode(['error' => 'Missing date']);
    exit;
}

$stmt = $conn->prepare("SELECT a.id, a.reason, a.status, a.doctor_id, u.name AS student_name 
                        FROM appointments a
                        JOIN users u ON a.student_id = u.id
                        WHERE a.doctor_id = ? 
                        AND a.appointment_date = ? 
                        AND a.status = 'pending'");


$stmt->bind_param("is", $doctor_id, $date);
$stmt->execute();
$result = $stmt->get_result();

$appointments = [];
while ($row = $result->fetch_assoc()) {
    $appointments[] = [
        'id' => $row['id'],
        'reason' => $row['reason'],
        'student' => $row['student_name'],
        'status' => $row['status'],
        'doctor_id' => $row['doctor_id'] // ✅ Add this!
    ];


}

echo json_encode($appointments);
