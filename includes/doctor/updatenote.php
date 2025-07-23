<?php
session_start();
include '../db.php';

// Make sure only doctors can update notes
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'doctor') {
    http_response_code(403);
    echo "Unauthorized access.";
    exit;
}

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Check for missing fields
if (!isset($data['student_id']) || !isset($data['note'])) {
    http_response_code(400);
    echo "Missing required fields.";
    exit;
}

$student_id = intval($data['student_id']);
$note = trim($data['note']);
$last_updated = date('Y-m-d H:i:s');

// Either update if exists or insert new
$stmt = $conn->prepare("SELECT id FROM notes WHERE student_id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update existing note
    $update = $conn->prepare("UPDATE notes SET note = ?, last_updated = ? WHERE student_id = ?");
    $update->bind_param("ssi", $note, $last_updated, $student_id);
    $update->execute();
    echo "Note updated.";
} else {
    // Insert new note
    $insert = $conn->prepare("INSERT INTO notes (student_id, note, last_updated) VALUES (?, ?, ?)");
    $insert->bind_param("iss", $student_id, $note, $last_updated);
    $insert->execute();
    echo "Note added.";
}
