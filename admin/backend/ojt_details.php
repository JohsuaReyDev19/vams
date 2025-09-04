<?php
// ojt_details.php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my-rfid";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

if (isset($_GET['vehicle_id'])) {
    $vehicleId = $_GET['vehicle_id'];

    $sql = "SELECT ojt_name, company_name, supervisor_name FROM ojt_vehicles WHERE vehicle_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $vehicleId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $ojt = $result->fetch_assoc();
            echo json_encode(['success' => true, 'ojt' => $ojt]);
        } else {
            echo json_encode(['success' => false, 'message' => 'OJT details not found']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error executing query']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Required fields are missing']);
}

$conn->close();
?>