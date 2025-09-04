<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my-rfid";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]));
}

$response = ['exists' => false, 'message' => ''];

// Check Plate Number
if (isset($_POST['plate_number'])) {
    $plate_number = $_POST['plate_number'];

    // Check in vehicles table first
    $stmtVeh = $conn->prepare("SELECT license_plate FROM vehicles WHERE license_plate = ?");
    $stmtVeh->bind_param("s", $plate_number);
    $stmtVeh->execute();
    $stmtVeh->store_result();

    if ($stmtVeh->num_rows > 0) {
        $response['exists'] = true;
        $response['message'] = "Plate number already Registered!";
    } else {
        // If not found in vehicles, check in registered_vehicles
        $stmt = $conn->prepare("SELECT status FROM registered_vehicles WHERE plate_number = ?");
        $stmt->bind_param("s", $plate_number);
        $stmt->execute();
        $stmt->bind_result($status);
        if ($stmt->fetch()) {
            if (strtolower($status) === 'pending') {
                $response['exists'] = true;
                $response['message'] = "Plate number is pending approval.";
            } elseif (strtolower($status) === 'approved') {
                $response['exists'] = true;
                $response['message'] = "Plate number has already been registered.";
            } elseif (strtolower($status) === 'rejected') {
                $response['exists'] = false;
                $response['message'] = "Plate number is not Registered.";
            } else {
                $response['exists'] = true;
                $response['message'] = "Plate number exists with unknown status.";
            }
        } else {
            $response['exists'] = false;
            $response['message'] = "Plate number is not Registered.";
        }
        $stmt->close();
    }
    $stmtVeh->close();
}

// Check Student ID Number
if (isset($_POST['student_id_number'])) {
    $student_id_number = $_POST['student_id_number'];

    $stmt3 = $conn->prepare("SELECT student_id_number FROM student_vehicles WHERE student_id_number = ?");
    $stmt3->bind_param("s", $student_id_number);
    $stmt3->execute();
    $stmt3->store_result();

    if ($stmt3->num_rows > 0) {
        $response['exists'] = true;
        $response['message'] = "Student ID number already registered!";
    } else {
        $stmt4 = $conn->prepare("SELECT student_id FROM registered_vehicles WHERE student_id = ?");
        $stmt4->bind_param("s", $student_id_number);
        $stmt4->execute();
        $stmt4->bind_result($status);
        if ($stmt4->fetch()) {
            if (strtolower($status) === 'pending') {
                $response['exists'] = true;
                $response['message'] = "Student ID is pending approval.";
            } elseif (strtolower($status) === 'approved') {
                $response['exists'] = true;
                $response['message'] = "Student ID has already been registered.";
            } elseif (strtolower($status) === 'rejected') {
                $response['exists'] = false;
                $response['message'] = "Student ID Number is not Registered.";
            } else {
                $response['exists'] = true;
                $response['message'] = "Student ID exists with unknown status.";
            }
        } else {
            $response['exists'] = false;
            $response['message'] = "Student ID Number is not Registered.";
        }
        $stmt4->close();
    }
    $stmt3->close();
}

$conn->close();
echo json_encode($response);
?>
