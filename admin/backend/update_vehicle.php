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

// Read the raw input
$data = json_decode(file_get_contents("php://input"), true);

// Validate required fields
$requiredFields = ['id', 'plate_number', 'vehicle_type', 'rfid_tag', 'owner_type', 'owner_name'];
foreach ($requiredFields as $field) {
    if (!isset($data[$field]) || empty($data[$field])) {
        echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
        exit;
    }
}

$id            = intval($data['id']);
$plate_number  = $data['plate_number'];
$vehicle_type  = $data['vehicle_type'];
$rfid_tag      = $data['rfid_tag'];
$owner_type    = $data['owner_type'];
$owner_name    = $data['owner_name'];
$extra_info    = $data['extra_info'] ?? null;
$vehicle_id    = $data['vehicle_ID'];

// ✅ Step 0: Check for duplicate RFID tag in vehicles table
$checkRfid = $conn->prepare("SELECT id FROM vehicles WHERE rfid_tag = ? AND id != ?");
$checkRfid->bind_param("si", $rfid_tag, $id);
$checkRfid->execute();
$checkRfid->store_result();

if ($checkRfid->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'RFID Sticker already exists for another vehicle']);
    $checkRfid->close();
    $conn->close();
    exit;
}
$checkRfid->close();

// ✅ Step 0.5: Check for duplicate plate number in vehicles table
$checkPlate = $conn->prepare("SELECT id FROM vehicles WHERE license_plate = ? AND id != ?");
$checkPlate->bind_param("si", $plate_number, $id);
$checkPlate->execute();
$checkPlate->store_result();

if ($checkPlate->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Plate number already exists for another vehicle']);
    $checkPlate->close();
    $conn->close();
    exit;
}
$checkPlate->close();

// ✅ Step 1: Update vehicles table
$updateVehicle = $conn->prepare("
    UPDATE vehicles 
    SET owner_name = ?, license_plate = ?, vehicle_type = ?, rfid_tag = ? 
    WHERE id = ?
");
$updateVehicle->bind_param("ssssi", $owner_name, $plate_number, $vehicle_type, $rfid_tag, $id);

if (!$updateVehicle->execute()) {
    echo json_encode(['success' => false, 'message' => 'Failed to update vehicle']);
    $updateVehicle->close();
    $conn->close();
    exit;
}
$updateVehicle->close();

// ✅ Step 2: Update corresponding owner table
switch (strtolower($owner_type)) {
    case 'student':
        $stmt = $conn->prepare("UPDATE student_vehicles SET student_name = ?, course = ? WHERE vehicle_id = ?");
        $stmt->bind_param("ssi", $owner_name, $extra_info, $vehicle_id);
        break;

    case 'faculty':
        $stmt = $conn->prepare("UPDATE faculty_vehicles SET faculty_name = ?, department = ? WHERE vehicle_id = ?");
        $stmt->bind_param("ssi", $owner_name, $extra_info, $vehicle_id);
        break;

    case 'ojt':
        $stmt = $conn->prepare("UPDATE ojt_vehicles SET ojt_name = ?, company = ? WHERE vehicle_id = ?");
        $stmt->bind_param("ssi", $owner_name, $extra_info, $vehicle_id);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid owner type']);
        $conn->close();
        exit;
}

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Failed to update owner info']);
    $stmt->close();
    $conn->close();
    exit;
}

$stmt->close();
$conn->close();

echo json_encode(['success' => true, 'message' => 'Vehicle and owner info updated successfully']);
?>
