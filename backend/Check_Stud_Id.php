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

    $stmt = $conn->prepare("SELECT plate_number FROM registered_vehicles WHERE plate_number = ?");
    $stmt->bind_param("s", $plate_number);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $response['exists'] = true;
        $response['message'] = "Plate number already registered!";
    } else {
        $stmt2 = $conn->prepare("SELECT status FROM pre_registered_vehicles WHERE plate_number = ?");
        $stmt2->bind_param("s", $plate_number);
        $stmt2->execute();
        $stmt2->bind_result($status);
        if ($stmt2->fetch()) {
            if (strtolower($status) === 'pending') {
                $response['exists'] = true;
                $response['message'] = "Plate number is pending approval.";
            } elseif (strtolower($status) === 'approved') {
                $response['exists'] = true;
                $response['message'] = "Plate number has already been registered.";
            } elseif (strtolower($status) === 'rejected') {
                $response['exists'] = false;
                $response['message'] = "Plate Number is Available.";
            } else {
                $response['exists'] = true;
                $response['message'] = "Plate number exists with unknown status.";
            }
        } else {
            $response['exists'] = false;
            $response['message'] = "Plate Number is Available.";
        }
        $stmt2->close();
    }
    $stmt->close();
}

$conn->close();
echo json_encode($response);
?>
