<?php
header("Content-Type: application/json");
require '../admin/database/db.php'; // <-- adjust if needed

$response = ["success" => false, "message" => ""];

// ✅ Allow only POST
if ($_SERVER['REQUEST_METHOD'] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}

try {
    // Collect form data
    $plate_number = trim($_POST['plate_number'] ?? '');
    $vehicle_type = trim($_POST['vehicle_type'] ?? '');
    $owner_type   = trim($_POST['owner_type'] ?? '');
    $rfid_tag     = trim($_POST['rfidtag'] ?? '');
    $email        = trim($_POST['email'] ?? null);

    $student_id_number = trim($_POST['student_id_number'] ?? null);

    if (empty($plate_number) || empty($vehicle_type) || empty($owner_type) || empty($rfid_tag)) {
        throw new Exception("Required fields are missing.");
    }

    // ✅ Duplicate Check (plate number OR RFID) across registered_vehicles + vehicles
    $check = $conn->prepare("
        SELECT id FROM registered_vehicles WHERE plate_number = ? OR rfid_tag = ?
        UNION
        SELECT id FROM vehicles WHERE license_plate = ? OR rfid_tag = ?
    ");
    $check->bind_param("ssss", $plate_number, $rfid_tag, $plate_number, $rfid_tag);
    $check->execute();
    $check->store_result();
    if ($check->num_rows > 0) {
        throw new Exception("This Plate Number or RFID Tag is already registered.");
    }
    $check->close();

    // ✅ File Upload (Vehicle Image OR/CR)
    $vehicle_image = null;
    if (isset($_FILES['image_name']) && $_FILES['image_name']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "../admin/pages/uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $filename = time() . "_" . preg_replace("/[^A-Za-z0-9_\.-]/", "_", $_FILES['image_name']['name']);
        $targetPath = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['image_name']['tmp_name'], $targetPath)) {
            $vehicle_image = $filename;
        }
    }

    // ✅ Insert into pre_registered_vehicles (Auto Approved)
    $stmt = $conn->prepare("INSERT INTO pre_registered_vehicles 
        (plate_number, vehicle_type, vehicle_image, rfid_tag, email, status, student_id_number) 
        VALUES (?, ?, ?, ?, ?, 'Approved', ?)");
    $stmt->bind_param("ssssss", $plate_number, $vehicle_type, $vehicle_image, $rfid_tag, $email, $student_id_number);

    if (!$stmt->execute()) {
        throw new Exception("Failed to insert pre-registered vehicle: " . $stmt->error);
    }

    $preVehicleId = $stmt->insert_id;
    $stmt->close();

    // ✅ Determine Owner Name
    $name = null;
    if ($owner_type === "student") {
        $name = $_POST['student_name'] ?? null;
    } elseif ($owner_type === "faculty") {
        $name = $_POST['faculty_name'] ?? null;
    } elseif ($owner_type === "ojt") {
        $name = $_POST['ojt_name'] ?? null;
    }

    // ✅ Insert into registered_vehicles (Auto Approved)
    $stmt = $conn->prepare("INSERT INTO registered_vehicles 
        (vehicle_id, plate_number, vehicle_type, rfid_tag, student_id, name, contact, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, 'Approved')");
    $stmt->bind_param("issssss", $preVehicleId, $plate_number, $vehicle_type, $rfid_tag, $student_id_number, $name, $email);

    if (!$stmt->execute()) {
        throw new Exception("Failed to insert registered vehicle: " . $stmt->error);
    }
    $stmt->close();

    // ✅ Insert into owner-specific table
    if ($owner_type === "student") {
        $student_name = $_POST['student_name'] ?? '';
        $course       = $_POST['course'] ?? '';
        $year_level   = $_POST['year_level'] ?? '';
        $purpose      = "Student";

        $stmt = $conn->prepare("INSERT INTO student_vehicles 
            (vehicle_id, student_name, student_id_number, course, year_level, email, purpose) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss", $preVehicleId, $student_name, $student_id_number, $course, $year_level, $email, $purpose);
        $stmt->execute();
        $stmt->close();

    } elseif ($owner_type === "faculty") {
        $faculty_name = $_POST['faculty_name'] ?? '';
        $department   = $_POST['department'] ?? '';
        $position     = $_POST['position'] ?? '';
        $purpose      = "Faculty";

        $stmt = $conn->prepare("INSERT INTO faculty_vehicles 
            (vehicle_id, faculty_name, department, position, email, purpose) 
            VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $preVehicleId, $faculty_name, $department, $position, $email, $purpose);
        $stmt->execute();
        $stmt->close();

    } elseif ($owner_type === "ojt") {
        $ojt_name       = $_POST['ojt_name'] ?? '';
        $company_name   = $_POST['company_name'] ?? '';
        $supervisor_name= $_POST['supervisor_name'] ?? '';
        $purpose        = "OJT";

        $stmt = $conn->prepare("INSERT INTO ojt_vehicles 
            (vehicle_id, ojt_name, company_name, supervisor_name, email, purpose) 
            VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $preVehicleId, $ojt_name, $company_name, $supervisor_name, $email, $purpose);
        $stmt->execute();
        $stmt->close();
    }

    $response["success"] = true;
    $response["message"] = "Vehicle registered successfully.";

} catch (Exception $e) {
    $response["success"] = false;
    $response["message"] = $e->getMessage();
}

echo json_encode($response);
?>
