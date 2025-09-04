<?php
include 'db.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehicle_id = trim($_POST['vehicle_id']);
    $rfid = trim($_POST['rfid_number']);
    $name = trim($_POST['owner_name']);
    $contact = trim($_POST['contact_number']);
    $entry_type = trim($_POST['entry_type']);
    $plate = trim($_POST['plate_number']);
    $vehicle_type = trim($_POST['vehicle_type']);

    // Check if any field is empty
    if (!$vehicle_id || !$rfid || !$name || !$contact || !$entry_type || !$plate || !$vehicle_type) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "All fields are required!"]);
        exit();
    }

    // Validate phone number format (adjust regex as needed)
    if (!preg_match('/^\d{10,15}$/', $contact)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Invalid contact number format!"]);
        exit();
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare("UPDATE vehicle_registration SET rfid_number = ?, owner_name = ?, contact_number = ?, entry_type = ?, plate_number = ?, vehicle_type = ? WHERE id = ?");
    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Database preparation failed!"]);
        exit();
    }

    $stmt->bind_param("ssssssi", $rfid, $name, $contact, $entry_type, $plate, $vehicle_type, $vehicle_id);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["status" => "success", "message" => "Vehicle details updated successfully!"]);
    } else {
        error_log("Database Error: " . $stmt->error);
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Update failed! Please try again."]);
    }

    $stmt->close();
    $conn->close();
}
?>
