<?php
header('Content-Type: application/json');
include '../database/db.php'; // your DB connection

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(['success' => false, 'message' => 'No data received']);
    exit;
}

$rfid_tag     = $data['rfid_tag'] ?? null;
$license_plate= $data['license_plate'] ?? '';
$owner_name   = $data['owner_name'] ?? '';
$phone_number = $data['phone_number'] ?? '';
$vehicle_type = $data['vehicle_type'] ?? '';
$visit_purpose= $data['visit_purpose'] ?? null;
$owner_type   = "Visitor"; // ✅ force Visitor always

$sql = "INSERT INTO visitor_entries 
        (rfid_tag, license_plate, owner_name, phone_number, vehicle_type, visit_purpose) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $rfid_tag, $license_plate, $owner_name, $phone_number, $vehicle_type, $visit_purpose);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Inserted into visitor_entries']);
} else {
    echo json_encode(['success' => false, 'message' => 'DB Error: ' . $conn->error]);
}

$stmt->close();
$conn->close();
?>
