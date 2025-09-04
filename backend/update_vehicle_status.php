<?php
// update_vehicle_status.php

header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my-rfid";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => "Database connection failed"]);
    exit;
}

// Validate input
if (!isset($_POST['vehicle_id'], $_POST['status'])) {
    echo json_encode(['success' => false, 'message' => "Missing parameters"]);
    exit;
}

$vehicle_id = intval($_POST['vehicle_id']); // Ensure it's an integer
$status = $_POST['status'];

// Allow only valid status values
$allowed_statuses = ['Approved', 'Rejected'];
if (!in_array($status, $allowed_statuses)) {
    echo json_encode(['success' => false, 'message' => "Invalid status value"]);
    exit;
}

// Update status in the database
$sql = "UPDATE vehicles SET status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $vehicle_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => "Vehicle status updated successfully."]);
    } else {
        echo json_encode(['success' => false, 'message' => "No changes made (vehicle may not exist or already updated)."]);
    }
} else {
    echo json_encode(['success' => false, 'message' => "Error updating vehicle status: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
