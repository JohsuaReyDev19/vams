<?php 
header("Content-Type: application/json");
require './db.php';

$response = ["success" => false, "message" => ""];

try {
    // ✅ Get JSON input
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['id'])) {
        echo json_encode(["success" => false, "message" => "Missing vehicle ID."]);
        exit;
    }

    $id            = intval($data['id']);
    $owner_name    = $data['owner_name'] ?? null;
    $license_plate = $data['license_plate'] ?? null;
    $vehicle_type  = $data['vehicle_type'] ?? null;
    $purpose       = $data['purpose'] ?? null;
    $rfid_tag      = $data['rfid_tag'] ?? null;

    // ✅ Check RFID duplication if provided
    if (!empty($rfid_tag)) {
        // 🔹 Check in registered_vehicles
        $check1 = $conn->prepare("
            SELECT id FROM registered_vehicles 
            WHERE rfid_tag = ? LIMIT 1
        ");
        $check1->bind_param("s", $rfid_tag);
        $check1->execute();
        $check1->store_result();

        if ($check1->num_rows > 0) {
            echo json_encode([
                "success" => false,
                "message" => "This RFID tag is already assigned in registered_vehicles."
            ]);
            $check1->close();
            $conn->close();
            exit;
        }
        $check1->close();

        // 🔹 Check in vehicles (excluding the current record being updated)
        $check2 = $conn->prepare("
            SELECT id FROM vehicles 
            WHERE rfid_tag = ? AND id != ? LIMIT 1
        ");
        $check2->bind_param("si", $rfid_tag, $id);
        $check2->execute();
        $check2->store_result();

        if ($check2->num_rows > 0) {
            echo json_encode([
                "success" => false,
                "message" => "This RFID tag is already assigned to another vehicle in vehicles."
            ]);
            $check2->close();
            $conn->close();
            exit;
        }
        $check2->close();
    }

    // ✅ Check license_plate duplication if provided
    if (!empty($license_plate)) {
        // 🔹 Check in registered_vehicles
        $check3 = $conn->prepare("
            SELECT id FROM registered_vehicles 
            WHERE plate_number = ? LIMIT 1
        ");
        $check3->bind_param("s", $license_plate);
        $check3->execute();
        $check3->store_result();

        if ($check3->num_rows > 0) {
            echo json_encode([
                "success" => false,
                "message" => "This license plate is already assigned to another vehicle"
            ]);
            $check3->close();
            $conn->close();
            exit;
        }
        $check3->close();

        // 🔹 Check in vehicles (excluding the current record being updated)
        $check4 = $conn->prepare("
            SELECT id FROM vehicles 
            WHERE license_plate = ? AND id != ? LIMIT 1
        ");
        $check4->bind_param("si", $license_plate, $id);
        $check4->execute();
        $check4->store_result();

        if ($check4->num_rows > 0) {
            echo json_encode([
                "success" => false,
                "message" => "This license plate is already assigned to another vehicle"
            ]);
            $check4->close();
            $conn->close();
            exit;
        }
        $check4->close();
    }

    // ✅ Update record in vehicles table
    $stmt = $conn->prepare("
        UPDATE vehicles 
        SET owner_name = ?, license_plate = ?, vehicle_type = ?, purpose = ?, rfid_tag = ?
        WHERE id = ?
    ");
    $stmt->bind_param("sssssi", $owner_name, $license_plate, $vehicle_type, $purpose, $rfid_tag, $id);

    if ($stmt->execute()) {
        $response["success"] = true;
        $response["message"] = "Vehicle updated successfully.";
    } else {
        $response["message"] = "Failed to update vehicle.";
    }

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    $response["message"] = $e->getMessage();
}

echo json_encode($response);
