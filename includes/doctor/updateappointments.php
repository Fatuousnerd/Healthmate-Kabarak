<?php
session_start();
include '../db.php';

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
    if (!isset($_POST['appointment_id'], $_POST['status'])) {
        die("Invalid request.");
    }

    $appointmentId = intval($_POST['appointment_id']);
    $newStatus = trim($_POST['status']); // sanitize

    if ($newStatus === '') {
        die("Status cannot be empty.");
    }

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
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'calvinkmwaura@gmail.com';
        $mail->Password = 'mmfb qyud btuw iltx';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('calvinkmwaura@gmail.com', 'Appointment System');
        $mail->addAddress($studentEmail, $studentName);
        $mail->isHTML(true); // use HTML formatting

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

                // Email: Appointment reassigned
                $mail->Subject = "Your Appointment Has Been Reassigned";
                $mail->Body = "
                    <p>Hi <strong>$studentName</strong>,</p>
                    <p>Your appointment with Dr. {$_SESSION['user']['name']} was cancelled and reassigned to <strong>Dr. $newDoctorName</strong>.</p>
                    <p>Status: <strong>Pending</strong></p>
                    <br><p>Regards,<br>Appointment System</p>
                ";

                $mail->send();
                echo "Appointment reassigned. Email sent.";
            } else {
                echo "No other doctor available to reassign.";
            }
        } else {
            // Regular status update
            $update = $conn->prepare("UPDATE appointments SET status = ? WHERE id = ?");
            $update->bind_param("si", $newStatus, $appointmentId);

            if ($update->execute()) {
                $mail->Subject = "Appointment Status Updated";
                $mail->Body = "
                    <p>Hi <strong>$studentName</strong>,</p>
                    <p>Your appointment status has been updated to: <strong>$newStatus</strong>.</p>
                    <br><p>Regards,<br>Appointment System</p>
                ";

                $mail->send();
                header("Location: ../../views/doctor/appointments.php");
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