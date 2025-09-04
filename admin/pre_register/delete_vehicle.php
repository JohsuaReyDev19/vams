<?php
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
if (!isset($_POST['vehicle_id'])) {
    echo json_encode(['success' => false, 'message' => "Missing vehicle ID"]);
    exit;
}

$vehicle_id = intval($_POST['vehicle_id']); // Ensure it's an integer

// Delete query
$sql = "DELETE FROM vehicles WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vehicle_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => "Vehicle Rejected successfully."]);
    } else {
        echo json_encode(['success' => false, 'message' => "Vehicle not found or already rejected."]);
    }
} else {
    echo json_encode(['success' => false, 'message' => "Error rejecting vehicle: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
