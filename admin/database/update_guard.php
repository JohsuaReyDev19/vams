<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "my-rfid"; // change to your DB name

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit;
}

if (!empty($_POST['id']) && !empty($_POST['fullname']) && !empty($_POST['contact']) && !empty($_POST['address']) && !empty($_POST['username'])) {
    $id       = intval($_POST['id']);
    $fullname = $_POST['fullname'];
    $contact  = $_POST['contact'];
    $address  = $_POST['address'];
    $username = $_POST['username'];

    $stmt = $conn->prepare("UPDATE admin_accounts SET full_name=?, contact_number=?, address=?, username=? WHERE id=?");
    $stmt->bind_param("ssssi", $fullname, $contact, $address, $username, $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update profile"]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "All fields are required"]);
}

$conn->close();
?>
