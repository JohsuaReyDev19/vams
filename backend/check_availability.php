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

/* ================================================
   CHECK PLATE NUMBER
=================================================*/
if (isset($_POST['plate_number'])) {
    $plate_number = $_POST['plate_number'];

    // 1. Check in vehicles table
    $stmtVeh = $conn->prepare("SELECT license_plate FROM vehicles WHERE license_plate = ?");
    $stmtVeh->bind_param("s", $plate_number);
    $stmtVeh->execute();
    $stmtVeh->store_result();

    if ($stmtVeh->num_rows > 0) {
        $response['exists'] = true;
        $response['message'] = "Plate number is already registered this system.";
    } else {

        // 2. Check in registered_vehicles table
        $stmt = $conn->prepare("SELECT status FROM registered_vehicles WHERE plate_number = ?");
        $stmt->bind_param("s", $plate_number);
        $stmt->execute();
        $stmt->bind_result($status);

        if ($stmt->fetch()) {
            $status = strtolower($status);

            if ($status === 'pending') {
                $response['exists'] = true;
                $response['message'] = "Plate number is pending approval.";
            } elseif ($status === 'approved') {
                $response['exists'] = true;
                $response['message'] = "Plate number is already registered.";
            } elseif ($status === 'rejected') {
                $response['exists'] = false;
                $response['message'] = "Plate number was rejected. You may re-apply.";
            } else {
                $response['exists'] = true;
                $response['message'] = "Plate number is already registered.";
            }
        } else {
            $response['exists'] = false;
            $response['message'] = "Plate number is not registered in this system.";
        }
        $stmt->close();
    }

    $stmtVeh->close();
}

/* ================================================
   CHECK STUDENT ID (Simplified)
=================================================*/
if (isset($_POST['student_id_number'])) {
    $student_id_number = $_POST['student_id_number'];

    // Combined query using UNION
    $stmt = $conn->prepare("
        SELECT student_id_number AS id FROM student_vehicles WHERE student_id_number = ?
        UNION
        SELECT student_id AS id FROM registered_vehicles WHERE student_id = ?
    ");
    
    $stmt->bind_param("ss", $student_id_number, $student_id_number);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $response['exists'] = true;
        $response['message'] = "Student not found.";
    } else {
        $response['exists'] = false;
        $response['message'] = "Student found.";
    }

    $stmt->close();
}


$conn->close();
echo json_encode($response);
?>
