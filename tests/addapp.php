<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user']['id']) || $_SESSION['user']['role'] !== 'student') {
    die("Unauthorized access.");
}

$studentId = $_SESSION['user']['id'];
$message = "";

// If form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $reason = htmlspecialchars(trim($_POST['reason']));
    $date = $_POST['date'];
    $time = $_POST['time'];

    // Basic validation
    if (empty($reason) || empty($date) || empty($time)) {
        $message = "All fields are required.";
    } else {
        // 1. Try to get latest doctor from previous appointments
        $stmt = $conn->prepare("SELECT doctor_id FROM appointments WHERE student_id = ? ORDER BY appointment_date DESC LIMIT 1");
        $stmt->bind_param("i", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $doctorId = $row['doctor_id']; // Use previous doctor
        } else {
            // 2. No previous doctor → Find doctor with fewest pending appointments
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

        // 3. Insert appointment
        $insert = $conn->prepare("INSERT INTO appointments (student_id, doctor_id, reason, appointment_date, appointment_time) VALUES (?, ?, ?, ?, ?)");
        $insert->bind_param("iisss", $studentId, $doctorId, $reason, $date, $time);

        if ($insert->execute()) {
            $message = "Appointment booked successfully!";
        } else {
            $message = "Error booking appointment: " . $insert->error;
        }
    }
}
?>

<!-- <pre><?php print_r($_SESSION['user']['role']); ?></pre> -->
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Book Appointment</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex justify-center items-center h-screen">
    <form method="POST" class="bg-white p-6 rounded shadow-md w-full max-w-lg space-y-4">
        <h2 class="text-2xl font-semibold mb-2">Book an Appointment</h2>

        <?php if (!empty($message)) echo "<p class='text-center mt-4 text-red-600 font-bold'>$message</p>"; ?>

        <textarea name="reason" placeholder="Reason for appointment" required
            class="w-full p-2 border rounded"></textarea>
        <input type="date" name="date" required class="w-full p-2 border rounded">
        <input type="time" name="time" required class="w-full p-2 border rounded">
        <button name="submit" class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">Book
            Appointment</button>
    </form>
</body>

</html>