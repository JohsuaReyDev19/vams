<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $rfid = trim($_POST['rfid_number']);
    $name = trim($_POST['owner_name']);
    $contact = $_POST['contact_number'];
    $entry_type = trim($_POST['entry_type']);
    $plate = trim($_POST['plate_number']);
    $vehicle_type = trim($_POST['vehicle_type']);

    // Check for empty fields
    if (empty($rfid) || empty($name) || empty($entry_type) || empty($plate) || empty($vehicle_type)) {
        echo json_encode(["status" => "error", "message" => "All fields are required!"]);
        exit(); // Stop execution if any field is empty
    }

    // Check if the RFID or plate number already exists
    $check_stmt = $conn->prepare("SELECT rfid_number, plate_number FROM vehicle_registration WHERE rfid_number = ? OR plate_number = ?");
    $check_stmt->bind_param("ss", $rfid, $plate);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $existing_data = $check_result->fetch_assoc();
    $check_stmt->close();

    if ($existing_data) {
        if ($existing_data['rfid_number'] == $rfid) {
            echo json_encode(["status" => "error", "message" => "RFID number already exists!"]);
        } elseif ($existing_data['plate_number'] == $plate) {
            echo json_encode(["status" => "error", "message" => "Plate number already exists!"]);
        }elseif ($existing_data['contact_number'] == $contact) {
            echo json_encode(["status" => "error", "message" => "This contact number already exists!"]);
        }
    } else {
        // Insert the new vehicle registration
        $stmt = $conn->prepare("INSERT INTO vehicle_registration (rfid_number, owner_name, contact_number, entry_type, plate_number, vehicle_type) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $rfid, $name, $contact, $entry_type, $plate, $vehicle_type);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Vehicle registered successfully!"]);
        } else {
            error_log("Database Error: " . $stmt->error); // Log error for debugging
            echo json_encode(["status" => "error", "message" => "Registration failed! Please try again."]);
        }

        $stmt->close();
    }

    $conn->close();
}
?>
