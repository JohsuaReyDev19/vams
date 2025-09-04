<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "wbrv";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} // Ensure this is correctly pointing to your database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        echo json_encode(["status" => "error", "message" => "Invalid vehicle ID."]);
        exit;
    }

    $id = intval($_POST['id']); // Sanitize input

    $query = "DELETE FROM vehicle_registration WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Vehicle deleted successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete vehicle."]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
?>
