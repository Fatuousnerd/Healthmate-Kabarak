<?php
session_start();
include 'db.php';

// Load PHPMailer classes manually (no Composer)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';
require '../PHPMailer/Exception.php';

// Only doctors allowed
if (!isset($_SESSION['user']['id']) || $_SESSION['user']['role'] !== 'doctor') {
    die("Unauthorized access.");
}

$doctorId = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointmentId = intval($_POST['appointment_id']);
    $newStatus = $_POST['status'];

    // Check if doctor owns the appointment
    $checkStmt = $conn->prepare("SELECT * FROM appointments WHERE id = ? AND doctor_id = ?");
    $checkStmt->bind_param("ii", $appointmentId, $doctorId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows === 0) {
        die("Unauthorized or invalid appointment.");
    }

    $appointment = $result->fetch_assoc();
    $studentId = $appointment['student_id'];

    // Get student email
    $studentQ = $conn->prepare("SELECT email, name FROM users WHERE id = ?");
    $studentQ->bind_param("i", $studentId);
    $studentQ->execute();
    $studentRes = $studentQ->get_result();
    $student = $studentRes->fetch_assoc();
    $studentEmail = $student['email'];
    $studentName = $student['name'];

    $mail = new PHPMailer(true);
    try {
        // SMTP Settings (change to your actual config)
        $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'calvinkmwaura@gmail.com'; // replace
            $mail->Password   = 'mmfb qyud btuw iltx';    // replace
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

        $mail->setFrom('calvinkmwaura@gmail.com', 'Appointment System');
        $mail->addAddress($studentEmail, $studentName);

        if ($newStatus === 'cancelled') {
            // Reassign appointment
            $sql = "
                SELECT u.id, u.name, COUNT(a.id) as total 
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
                $newDoctorName = $row['name'];

                $update = $conn->prepare("UPDATE appointments SET doctor_id = ?, status = 'pending' WHERE id = ?");
                $update->bind_param("ii", $newDoctorId, $appointmentId);
                $update->execute();

                // Email: Appointment Cancelled + Reassigned
                $mail->Subject = "Your appointment has been reassigned";
                $mail->Body = "Hello $studentName,\n\nYour appointment with Dr. {$_SESSION['user']['name']} was cancelled and has been reassigned to Dr. $newDoctorName. It is now pending confirmation.\n\nRegards,\nAppointment System";

                $mail->send();
                echo "Appointment reassigned. Email sent.";
            } else {
                echo "No other doctor available to reassign.";
            }

        } else {
            // Update status
            $update = $conn->prepare("UPDATE appointments SET status = ? WHERE id = ?");
            $update->bind_param("si", $newStatus, $appointmentId);
            if ($update->execute()) {
                // Email: Status changed
                $mail->Subject = "Appointment Status Update";
                $mail->Body = "Hello $studentName,\n\nYour appointment status has been updated to: $newStatus.\n\nRegards,\nAppointment System";

                $mail->send();
                header("Location: ../views/doctor/appointments.php");
                exit;
            } else {
                echo "Failed to update status.";
            }
        }

    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
}
?>
