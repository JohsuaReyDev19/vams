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

if (!empty($_POST['id']) && !empty($_POST['fullname']) && !empty($_POST['contact']) && !empty($_POST['address']) && !empty($_POST['username']) && !empty($_POST['email'])) {
    $id       = intval($_POST['id']);
    $fullname = trim($_POST['fullname']);
    $contact  = trim($_POST['contact']);
    $address  = trim($_POST['address']);
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);

    // ✅ Check if username already exists in other accounts
    $checkUsername = $conn->prepare("SELECT id FROM admin_accounts WHERE username = ? AND id != ?");
    $checkUsername->bind_param("si", $username, $id);
    $checkUsername->execute();
    $checkUsername->store_result();
    if ($checkUsername->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Username is already taken by another account"]);
        $checkUsername->close();
        $conn->close();
        exit;
    }
    $checkUsername->close();

    // ✅ Check if email already exists in other accounts
    $checkEmail = $conn->prepare("SELECT id FROM admin_accounts WHERE email = ? AND id != ?");
    $checkEmail->bind_param("si", $email, $id);
    $checkEmail->execute();
    $checkEmail->store_result();
    if ($checkEmail->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "Email is already taken by another account"]);
        $checkEmail->close();
        $conn->close();
        exit;
    }
    $checkEmail->close();

    // ✅ Proceed with update
    $stmt = $conn->prepare("UPDATE admin_accounts SET full_name=?, contact_number=?, address=?, username=?, email=? WHERE id=?");
    $stmt->bind_param("sssssi", $fullname, $contact, $address, $username, $email, $id);

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
