<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my-rfid";

// DB connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

$rfid_tag = $_POST['rfid_tag'];

$response = [];

$query = "SELECT * FROM registered_vehicles WHERE rfid_tag = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $rfid_tag);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $response = ['exists' => true, 'message' => 'RFID Sticker already used.'];
} else {
    $response = ['exists' => false];
}

echo json_encode($response);
?>
