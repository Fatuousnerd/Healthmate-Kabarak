<?php
session_start();
include '../db.php';

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

$stmt = $conn->prepare("SELECT a.id, a.reason, a.status, a.doctor_id, a.appointment_time, u.name AS student_name, u.id AS student_id, u.profile_pic, u.email, n.note, n.last_updated 
                        FROM appointments a
                        JOIN users u ON a.student_id = u.id
                        LEFT JOIN notes n ON n.student_id = u.id
                        WHERE a.doctor_id = ? 
                        AND a.appointment_date = ? 
                        AND a.appointment_time >= CURTIME()
                        AND a.status = 'confirmed'");


$stmt->bind_param("is", $doctor_id, $date);
$stmt->execute();
$result = $stmt->get_result();

$appointments = [];
while ($row = $result->fetch_assoc()) {
    $appointments[] = [
        'id' => $row['id'],
        'email' => $row['email'],
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