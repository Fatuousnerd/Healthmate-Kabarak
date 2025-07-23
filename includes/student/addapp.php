<?php
session_start();
include '../db.php';

// Load PHPMailer classes
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';
require '../PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if (!isset($_SESSION['user']['id']) || $_SESSION['user']['role'] !== 'student') {
    die("Unauthorized access.");
}

$studentId = $_SESSION['user']['id'];
$studentName = $_SESSION['user']['name'];
$studentEmail = $_SESSION['user']['email'];
$message = "";

// print_r($_SESSION['user']);
// exit();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $reason = htmlspecialchars(trim($_POST['reason']));
    $date = $_POST['date'];
    $time = $_POST['time'];

    if (empty($reason) || empty($date) || empty($time)) {
        $message = "All fields are required.";
    } else {
        // 1. Check for previous doctor
        $stmt = $conn->prepare("SELECT doctor_id FROM appointments WHERE student_id = ? ORDER BY appointment_date DESC LIMIT 1");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $doctorId = $row['doctor_id'];
        } else {
            $sql = "
                SELECT u.id, COUNT(a.id) as total 
                FROM users u 
                LEFT JOIN appointments a ON a.doctor_id = u.id AND a.status = 'pending'
                WHERE u.role = 'doctor'
                GROUP BY u.id
                ORDER BY total ASC
                LIMIT 1
            ";
            $res = $conn->query($sql);
            if ($doc = $res->fetch_assoc()) {
                $doctorId = $doc['id'];
            } else {
                die("No available doctor at the moment.");
            }
        }

        // Get doctor's name
        $docStmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
        $docStmt->bind_param("i", $doctorId);
        $docStmt->execute();
        $docResult = $docStmt->get_result();
        $doctorName = ($d = $docResult->fetch_assoc()) ? $d['name'] : "Your Doctor";

        // 2. Insert appointment
        $insert = $conn->prepare("INSERT INTO appointments (student_id, doctor_id, reason, appointment_date, appointment_time) VALUES (?, ?, ?, ?, ?)");
        $insert->bind_param("iisss", $studentId, $doctorId, $reason, $date, $time);

        if ($insert->execute()) {
            // 3. Send email
            $mail = new PHPMailer(true);
            try {
                $mail->SMTPDebug = 2; // Or 3 for more detail
                $mail->Debugoutput = 'html'; // Optional
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'calvinkmwaura@gmail.com';
                $mail->Password = 'mmfb qyud btuw iltx'; // DOUBLE-CHECK THIS PASSWORD
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->setFrom('calvinkmwaura@gmail.com', 'Healthmate Notifications');
                $mail->addAddress($studentEmail, $studentName); // safer way
                // $mail->addAddress("anonymousguruplay@gmail.com", "Sudo Student"); // safer way

                $mail->isHTML(true);
                $mail->Subject = 'Appointment Booked';
                $mail->Body = "
        <h3>Appointment Booked</h3>
        <p>Your appointment has been scheduled on <strong>$date</strong> at <strong>$time</strong>.</p>
        <p>Reason: $reason</p>
    ";

                if ($mail->send()) {
                    echo "Email sent successfully!";
                } else {
                    echo "Email failed: " . $mail->ErrorInfo;
                }

            } catch (Exception $e) {
                echo "Exception caught: " . $mail->ErrorInfo;
            }


            header("Location: ../../views/student/dashboard.php");
            exit();
        } else {
            $message = "Error booking appointment: " . $insert->error;
        }
    }
}
?>

<!-- <pre><?php print_r($_SESSION['user']); ?></pre> -->