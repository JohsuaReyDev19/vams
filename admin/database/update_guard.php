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
    $fullname = trim($_POST['fullname']);
    $contact  = trim($_POST['contact']);
    $address  = trim($_POST['address']);
    $username = trim($_POST['username']);

    // ✅ Check if username already exists in other accounts
    $check = $conn->prepare("SELECT id FROM admin_accounts WHERE username = ? AND id != ?");
    $check->bind_param("si", $username, $id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Username is already taken by another account"]);
        $check->close();
        $conn->close();
        exit;
    }
    $check->close();

    // ✅ Proceed with update
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
