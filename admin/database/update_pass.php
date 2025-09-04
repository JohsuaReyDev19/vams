<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my-rfid";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

session_start();
header('Content-Type: application/json');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access!"]);
    exit();
}

$admin_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentPassword = $_POST['currentPassword'] ?? '';
    $newPassword = $_POST['newPassword'] ?? '';

    // Check if the required fields are provided
    if (empty($currentPassword) || empty($newPassword)) {
        echo json_encode(["success" => false, "message" => "All fields are required!"]);
        exit();
    }

    // Fetch current hashed password from the database
    $stmt = $conn->prepare("SELECT password_hash FROM admin_accounts WHERE id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $adminData = $result->fetch_assoc();
    $stmt->close();

    // If admin not found, return error
    if (!$adminData) {
        echo json_encode(["success" => false, "message" => "Admin not found!"]);
        exit();
    }

    $hashedPassword = $adminData['password_hash'];  // Correct the variable to use password_hash

    // Verify the current password
    if (!password_verify($currentPassword, $hashedPassword)) {
        echo json_encode(["success" => false, "message" => "Current password is incorrect!"]);
        exit();
    }

    // Hash the new password securely
    $newHashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    // Update password in the database
    $stmt = $conn->prepare("UPDATE admin_accounts SET password_hash = ? WHERE id = ?");
    $stmt->bind_param("si", $newHashedPassword, $admin_id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Password updated successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Database error: " . $stmt->error]);
    }

    $stmt->close();
}

// Close the database connection after operations
$conn->close();
?>
