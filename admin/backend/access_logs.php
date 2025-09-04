<?php
header("Content-Type: application/json");

$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "my-rfid";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    echo json_encode([
        "success" => false,
        "message" => "Database connection failed: " . $conn->connect_error
    ]);
    exit;
}

$response = ["success" => false, "data" => []];

try {
    $sql = "SELECT * FROM access_logs ORDER BY time_in DESC";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $logs = [];
        while ($row = $result->fetch_assoc()) {
            $logs[] = $row;
        }
        $response["success"] = true;
        $response["data"] = $logs;
    } else {
        $response["success"] = true; // ✅ still true but no data
        $response["data"] = [];
    }
} catch (Exception $e) {
    $response["message"] = $e->getMessage();
}

echo json_encode($response);
$conn->close();
