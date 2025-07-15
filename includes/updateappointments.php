<?php
session_start();
include 'db.php';

// Only doctors allowed
if (!isset($_SESSION['user']['id']) || $_SESSION['user']['role'] !== 'doctor') {
    die("Unauthorized access.");
}

$doctorId = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointmentId = intval($_POST['appointment_id']);
    $newStatus = $_POST['status'];

    var_dump($appointmentId, $doctorId);

    // Check if doctor owns the appointment
    $checkStmt = $conn->prepare("SELECT * FROM appointments WHERE id = ? AND doctor_id = ?");
    $checkStmt->bind_param("ii", $appointmentId, $doctorId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows === 0) {
        die("Unauthorized or invalid appointment.");
    }

    $appointment = $result->fetch_assoc();

    // Handle cancellation and reassignment
    if ($newStatus === 'cancelled') {
        $studentId = $appointment['student_id'];

        // Find a new doctor (not the current one) with the least appointments
        $sql = "
            SELECT u.id, COUNT(a.id) as total 
            FROM users u 
            LEFT JOIN appointments a ON a.doctor_id = u.id AND a.status = 'pending'
            WHERE u.role = 'doctor' AND u.id != ?
            GROUP BY u.id
            ORDER BY total ASC
            LIMIT 1
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $doctorId);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($row = $res->fetch_assoc()) {
            $newDoctorId = $row['id'];

            // Reassign and reset to 'pending'
            $update = $conn->prepare("UPDATE appointments SET doctor_id = ?, status = 'pending' WHERE id = ?");
            $update->bind_param("ii", $newDoctorId, $appointmentId);
            $update->execute();

            echo "Appointment reassigned to another doctor.";
        } else {
            echo "No other doctor available to reassign.";
        }

    } else {
        // Confirm or mark as done
        $update = $conn->prepare("UPDATE appointments SET status = ? WHERE id = ?");
        $update->bind_param("si", $newStatus, $appointmentId);
        if ($update->execute()) {
            echo "Status updated to $newStatus";
            header("Location: ../views/doctor/appointments.php");
            exit;
        } else {
            echo "Failed to update status.";
        }
    }
}
?>
