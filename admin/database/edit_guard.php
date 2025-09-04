<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "my-rfid"; // Change to your DB name

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed."]);
    exit;
}

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $stmt = $conn->prepare("SELECT id, full_name, contact_number, address, username FROM admin_accounts WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows) {
            echo json_encode(["status" => "success", "data" => $result->fetch_assoc()]);
        } else {
            echo json_encode(["status" => "error", "message" => "Admin not found."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Query failed."]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "No ID provided."]);
}

$conn->close();
?>
