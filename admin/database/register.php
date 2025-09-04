<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my-rfid";

// Create DB connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize input
    $fullname = trim($_POST["fullname"]);
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validate required fields
    if (empty($fullname) || empty($username) || empty($email) || empty($password)) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "Invalid email format."]);
        exit;
    }

    // Check if username or email already exists
    $checkStmt = $conn->prepare("SELECT username, email FROM admin_accounts WHERE username = ? OR email = ?");
    $checkStmt->bind_param("ss", $username, $email);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        $existing = $result->fetch_assoc();
        if ($existing['username'] === $username) {
            echo json_encode(["success" => false, "message" => "Username already exists."]);
        } else {
            echo json_encode(["success" => false, "message" => "Email already exists."]);
        }
        exit;
    }
    
    $checkStmt->close();

    // Hash password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Insert into DB
    $insertStmt = $conn->prepare("INSERT INTO admin_accounts (full_name, username, email, password_hash) VALUES (?, ?, ?, ?)");
    $insertStmt->bind_param("ssss", $fullname, $username, $email, $hashedPassword);

    if ($insertStmt->execute()) {
        echo json_encode(["success" => true, "message" => "Account created successfully."]);
    } else {
        error_log("Insert Error: " . $insertStmt->error);
        echo json_encode(["success" => false, "message" => "Registration failed. Please try again."]);
    }

    $insertStmt->close();
    $conn->close();
}
?>
