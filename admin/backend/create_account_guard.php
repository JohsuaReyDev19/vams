<?php
// create_account.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my-rfid";

// DB connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password_hash = password_hash("default123", PASSWORD_DEFAULT); // default password
    $full_name = $_POST['fullname'];
    $contact_number = $_POST['contact'];
    $address = $_POST['address'];
    $rule = $_POST['purpose'];

    // ✅ 1. Check if username already exists
    $checkUserStmt = $conn->prepare("SELECT id FROM admin_accounts WHERE username = ?");
    $checkUserStmt->bind_param("s", $username);
    $checkUserStmt->execute();
    $checkUserStmt->store_result();

    if ($checkUserStmt->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Username already used. Please choose another.']);
        $checkUserStmt->close();
        $conn->close();
        exit;
    }
    $checkUserStmt->close();

    // ✅ 2. Check if email already exists
    $checkEmailStmt = $conn->prepare("SELECT id FROM admin_accounts WHERE email = ?");
    $checkEmailStmt->bind_param("s", $email);
    $checkEmailStmt->execute();
    $checkEmailStmt->store_result();

    if ($checkEmailStmt->num_rows > 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email already exists. Please use another email.']);
        $checkEmailStmt->close();
        $conn->close();
        exit;
    }
    $checkEmailStmt->close();

    // ✅ 3. Insert if username and email are both unique
    $stmt = $conn->prepare("INSERT INTO admin_accounts (username, password_hash, full_name, contact_number, address, email, Rule) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $username, $password_hash, $full_name, $contact_number, $address, $email, $rule);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Account created successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to create account.']);
    }

    $stmt->close();
    $conn->close();
}
?>
