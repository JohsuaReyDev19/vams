<?php
header('Content-Type: application/json');

// DB credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my-rfid";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

// Validate
if (!isset($_POST['vehicle_id']) || !isset($_POST['rfid_tag'])) {
    echo json_encode(['success' => false, 'message' => 'Missing vehicle_id or rfid_tag']);
    exit;
}

$vehicleId = intval($_POST['vehicle_id']);
$rfidTag = $conn->real_escape_string($_POST['rfid_tag']);

// Update the pre-registered vehicle's status and assign RFID
$sql = "UPDATE pre_registered_vehicles SET status='Approved', rfid_tag='$rfidTag' WHERE vehicle_id=$vehicleId";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true, 'message' => 'Vehicle approved and RFID assigned successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to approve vehicle: ' . $conn->error]);
}

$conn->close();
?>
