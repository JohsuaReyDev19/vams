<?php
$host = "localhost";
$user = "root";
$pass = ""; // or your MySQL password
$db = "my-rfid";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$plate  = $_POST['plate_number'] ?? '';
$name   = $_POST['owner_name'] ?? '';
$type   = $_POST['vehicle_type'] ?? '';
$access = $_POST['access_by'] ?? '';

if (!$plate || !$name || !$type || !$access) {
    echo 'error';
    exit;
}

$purpose = "Visitors";

// ✅ Force timezone to Philippines
date_default_timezone_set('Asia/Manila');
$time_in = date('Y-m-d H:i:s');

$stmt = $conn->prepare("INSERT INTO access_logs (owner_name, purpose, plate_number, vehicle_type, time_in, access_by) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $name, $purpose, $plate, $type, $time_in, $access);

echo $stmt->execute() ? 'success' : 'error';
?>
