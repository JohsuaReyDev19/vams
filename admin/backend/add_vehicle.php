<?php
// add_vehicle.php
header('Content-Type: application/json');
require_once '../database/db.php'; // Your DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $owner_id      = $_POST['owner_id'] ?? '';
    $purpose       = $_POST['purpose'] ?? '';
    $owner_name    = $_POST['owner_name'] ?? '';
    $license_plate = $_POST['license_plate'] ?? '';
    $vehicle_type  = $_POST['vehicle_type'] ?? '';
    $rfid_tag      = $_POST['rfid_tag'] ?? '';

    // ✅ Basic validation
    if (empty($owner_id) || empty($purpose) || empty($owner_name) || empty($license_plate) || empty($vehicle_type) || empty($rfid_tag)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    // ✅ Check if RFID already exists in vehicles OR registered_vehicles
    $check = $conn->prepare("
        SELECT 'vehicles' AS source FROM vehicles WHERE rfid_tag = ?
        UNION
        SELECT 'registered_vehicles' AS source FROM registered_vehicles WHERE rfid_tag = ?
    ");
    $check->bind_param("ss", $rfid_tag, $rfid_tag);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'RFID already used!']);
        exit;
    }

    // ✅ Insert into DB
    $stmt = $conn->prepare("
        INSERT INTO vehicles (owner_id, purpose, owner_name, rfid_tag, license_plate, vehicle_type)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("isssss", $owner_id, $purpose, $owner_name, $rfid_tag, $license_plate, $vehicle_type);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Vehicle successfully added.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add vehicle.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
?>
