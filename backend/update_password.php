<?php
// backend/update_password.php
session_start();

header('Content-Type: application/json');

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "my-rfid"; // change this to your database name

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed."]);
    exit;
}

// Check if user is logged in and has an ID stored
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized access."]);
    exit;
}

$guard_id = $_SESSION['user_id'];

// Get POST data
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validate inputs
if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
    echo json_encode(["status" => "error", "message" => "All fields are required."]);
    exit;
}

if ($new_password !== $confirm_password) {
    echo json_encode(["status" => "error", "message" => "New password and confirm password do not match."]);
    exit;
}

// Fetch current password hash from DB
$stmt = $conn->prepare("SELECT password_hash FROM admin_accounts WHERE id = ?");
$stmt->bind_param("i", $guard_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Account not found."]);
    exit;
}

$row = $result->fetch_assoc();
$stored_hash = $row['password_hash'];

// Verify current password
if (!password_verify($current_password, $stored_hash)) {
    echo json_encode(["status" => "error", "message" => "Current password is incorrect."]);
    exit;
}

// Hash the new password
$new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

// Update password in database
$update_stmt = $conn->prepare("UPDATE admin_accounts SET password_hash = ? WHERE id = ?");
$update_stmt->bind_param("si", $new_password_hash, $guard_id);

if ($update_stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Password updated successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update password."]);
}

$update_stmt->close();
$stmt->close();
$conn->close();
