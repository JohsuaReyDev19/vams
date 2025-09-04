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

$response = ["success" => false, "message" => ""];

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access!"]);
    exit();
}

$admin_id = $_SESSION['user_id'];

// Handle GET request: Fetch admin data
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $stmt = $conn->prepare("SELECT full_name, username, email FROM admin_accounts WHERE id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $adminData = $result->fetch_assoc();
    $stmt->close();

    if ($adminData) {
        echo json_encode(["success" => true, "data" => $adminData]);
    } else {
        echo json_encode(["success" => false, "message" => "Admin not found"]);
    }
    exit();
}

// Handle POST request: Update admin profile
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check required fields
    if (empty($_POST['fullname']) || empty($_POST['email']) || empty($_POST['username'])) {
        $response["message"] = "All fields are required!";
        echo json_encode($response);
        exit();
    }

    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);

    // Prevent SQL Injection using prepared statements
    $stmt = $conn->prepare("UPDATE admin_accounts SET full_name=?, username=?, email=? WHERE id=?");
    $stmt->bind_param("sssi", $fullname, $username, $email, $admin_id);

    if ($stmt->execute()) {
        $response["success"] = true;
        $response["message"] = "Profile updated successfully!";
    } else {
        $response["message"] = "Database error: " . $stmt->error;
    }

    $stmt->close();
    echo json_encode($response);
    exit();
}

$conn->close();
?>
