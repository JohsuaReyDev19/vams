<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my-rfid";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

$owner_id = isset($_GET['owner_id']) ? intval($_GET['owner_id']) : 0;

if ($owner_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid owner ID.']);
    exit;
}

// Order newest first using created_at or id
$sql = "SELECT * FROM vehicles WHERE owner_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $owner_id);
$stmt->execute();
$result = $stmt->get_result();

$vehicles = [];

while ($row = $result->fetch_assoc()) {
    $vehicles[] = $row;
}

if (!empty($vehicles)) {
    echo json_encode(['success' => true, 'vehicles' => $vehicles]);
} else {
    echo json_encode(['success' => false, 'message' => 'No other vehicles found for this owner.']);
}

$conn->close();
?>
