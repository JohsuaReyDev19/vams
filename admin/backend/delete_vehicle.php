<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// DB credentials
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "my-rfid";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

// Get POST
$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['id']) || !is_numeric($input['id']) || !isset($input['vehicle_id']) || !is_numeric($input['vehicle_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid or missing vehicle ID']);
    exit;
}

$Id         = intval($input['id']);
$vehicle_Id = intval($input['vehicle_id']);

// Get vehicle data
$getVehicleStmt = $conn->prepare("SELECT * FROM registered_vehicles WHERE id = ?");
$getVehicleStmt->bind_param("i", $Id);
$getVehicleStmt->execute();
$vehicleResult = $getVehicleStmt->get_result();

if ($vehicleResult->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Vehicle not found in registered_vehicles']);
    $conn->close();
    exit;
}

$vehicleData = $vehicleResult->fetch_assoc();
$getVehicleStmt->close();

// Correctly map fields
$rfid_tag     = $vehicleData['rfid_tag'] ?? null;
$plate_number = $vehicleData['plate_number'] ?? '';  
$owner_name   = $vehicleData['name'] ?? '';
$contact      = $vehicleData['contact'] ?? '';       
$vehicle_type = $vehicleData['vehicle_type'] ?? '';

// Insert into visitor_entries
$insertStmt = $conn->prepare("
    INSERT INTO visitor_entries (rfid_tag, license_plate, owner_name, phone_number, vehicle_type)
    VALUES (?, ?, ?, ?, ?)
");

$insertStmt->bind_param("sssss", $rfid_tag, $plate_number, $owner_name, $contact, $vehicle_type);

if (!$insertStmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Failed to insert into visitor_entries: ' . $insertStmt->error]);
    $insertStmt->close();
    $conn->close();
    exit;
}
$insertStmt->close();

// Delete from owner-specific tables
$tables = ['faculty_vehicles', 'ojt_vehicles', 'student_vehicles'];
foreach ($tables as $table) {
    $stmt = $conn->prepare("DELETE FROM $table WHERE vehicle_id = ?");
    $stmt->bind_param("i", $vehicle_Id);
    $stmt->execute();
    $stmt->close();
}

// Delete from registered_vehicles
$deleteStmt = $conn->prepare("DELETE FROM registered_vehicles WHERE id = ?");
$deleteStmt->bind_param("i", $Id);
$success = $deleteStmt->execute();
$deleteStmt->close();

// ✅ NEW: Delete all vehicles linked to this owner_id
$deleteVehiclesStmt = $conn->prepare("DELETE FROM vehicles WHERE owner_id = ?");
$deleteVehiclesStmt->bind_param("i", $Id);
$deleteVehiclesStmt->execute();
$deleteVehiclesStmt->close();

if ($success) {
    echo json_encode(['success' => true, 'message' => 'Vehicle moved to visitor_entries and related data deleted']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete from registered_vehicles: ' . $conn->error]);
}

$conn->close();
?>
