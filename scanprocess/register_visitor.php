<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "my-rfid";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$plate = $_POST['license_plate'] ?? '';
$name = $_POST['owner_name'] ?? '';
$phone = $_POST['phone'] ?? '';
$type = $_POST['vehicle_type'] ?? '';
$color = $_POST['vehicle_color'] ?? '';
$purpose = $_POST['purpose'] ?? '';

if (!$plate || !$name || !$phone || !$type || !$color) {
    echo json_encode(['error' => 'Missing required fields.']);
    exit;
}


// ✅ Check if plate already exists
$checkStmt = $conn->prepare("SELECT id FROM visitor_entries WHERE license_plate = ?");
$checkStmt->bind_param("s", $plate);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    echo json_encode(['error' => 'License plate already Registered.']);
    exit;
}
$checkStmt->close();

// ✅ Insert new visitor
$stmt = $conn->prepare("INSERT INTO visitor_entries (license_plate, owner_name, phone_number, vehicle_type, vehicle_color, visit_purpose) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $plate, $name, $phone, $type, $color, $purpose);

if ($stmt->execute()) {
    echo json_encode([
        'plate' => $plate,
        'name' => $name,
        'phone' => $phone,
        'type' => $type,
        'color' => $color,
        'purpose' => $purpose
    ]);
} else {
    echo json_encode(['error' => 'Failed to register visitor.']);
}

$stmt->close();
$conn->close();
?>
