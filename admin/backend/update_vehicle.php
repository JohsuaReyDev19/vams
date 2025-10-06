<?php
header('Content-Type: application/json');

// DB credentials
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "my-rfid";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

// Read the raw input
$data = json_decode(file_get_contents("php://input"), true);

// Validate required fields
$requiredFields = ['id', 'plate_number', 'vehicle_type', 'rfid_tag', 'owner_type', 'owner_name', 'vehicle_ID'];
foreach ($requiredFields as $field) {
    if (!isset($data[$field]) || $data[$field] === '') {
        echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
        exit;
    }
}

$id           = intval($data['id']);
$plate_number = $data['plate_number'];
$vehicle_type = $data['vehicle_type'];
$rfid_tag     = $data['rfid_tag'];
$owner_type   = $data['owner_type'];
$owner_name   = $data['owner_name'];
$extra_info   = $data['extra_info'] ?? null; // course/department/company/contact
$vehicle_id   = intval($data['vehicle_ID']);

/**
 * ✅ Step 1: RFID Validation (check both registered_vehicles & vehicles)
 */
$checkRfid = $conn->prepare("
    SELECT 'registered' as source FROM registered_vehicles WHERE rfid_tag = ? AND id != ?
    UNION
    SELECT 'vehicles' as source FROM vehicles WHERE rfid_tag = ? AND id != ?
");
$checkRfid->bind_param("sisi", $rfid_tag, $id, $rfid_tag, $vehicle_id);
$checkRfid->execute();
$checkRfid->store_result();

if ($checkRfid->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'RFID Sticker already exists in system']);
    $checkRfid->close();
    $conn->close();
    exit;
}
$checkRfid->close();

/**
 * ✅ Step 2: Plate/License Number Validation (check both tables)
 */
$checkPlate = $conn->prepare("
    SELECT 'registered' as source FROM registered_vehicles WHERE plate_number = ? AND id != ?
    UNION
    SELECT 'vehicles' as source FROM vehicles WHERE license_plate = ? AND id != ?
");
$checkPlate->bind_param("sisi", $plate_number, $id, $plate_number, $vehicle_id);
$checkPlate->execute();
$checkPlate->store_result();

if ($checkPlate->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Plate number already exists in system']);
    $checkPlate->close();
    $conn->close();
    exit;
}
$checkPlate->close();

/**
 * ✅ Step 3: Update registered_vehicles
 */
$update = $conn->prepare("
    UPDATE registered_vehicles
    SET plate_number = ?, vehicle_type = ?, rfid_tag = ?, name = ?, status = ?, contact = ?
    WHERE id = ?
");
$update->bind_param("ssssssi", $plate_number, $vehicle_type, $rfid_tag, $owner_name, $owner_type, $extra_info, $id);

if (!$update->execute()) {
    echo json_encode(['success' => false, 'message' => 'Failed to update vehicle: ' . $conn->error]);
    $update->close();
    $conn->close();
    exit;
}
$update->close();

/**
 * ✅ Step 4: Update owner-specific table
 */
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
    echo json_encode(['success' => false, 'message' => 'Failed to update owner info: ' . $conn->error]);
    $stmt->close();
    $conn->close();
    exit;
}

$stmt->close();
$conn->close();

echo json_encode(['success' => true, 'message' => 'Vehicle and owner info updated successfully']);
?>
