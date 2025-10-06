<?php
header('Content-Type: application/json');
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "my-rfid");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "DB connection failed"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $reset_code = trim($_POST['reset_code']);
    $new_password = trim($_POST['new_password']);

    if (empty($email) || empty($reset_code) || empty($new_password)) {
        echo json_encode(["success" => false, "message" => "All fields are required"]);
        exit;
    }

    // Fetch reset code record
    $stmt = $conn->prepare("SELECT * FROM password_resets WHERE email=? AND reset_code=? LIMIT 1");
    $stmt->bind_param("ss", $email, $reset_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo json_encode(["success" => false, "message" => "Invalid reset code"]);
        exit;
    }

    $reset = $result->fetch_assoc();

    // Check expiration in PHP
    if (strtotime($reset['expires_at']) < time()) {
        echo json_encode(["success" => false, "message" => "Reset code expired"]);
        exit;
    }

    // Hash the new password
    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

    // Update admin_accounts password
    $stmt = $conn->prepare("UPDATE admin_accounts SET password_hash=? WHERE email=?");
    $stmt->bind_param("ss", $password_hash, $email);
    $stmt->execute();

    // Delete used reset code
    $conn->query("DELETE FROM password_resets WHERE email='".$conn->real_escape_string($email)."'");

    echo json_encode(["success" => true, "message" => "Password successfully reset!"]);
}
?>
