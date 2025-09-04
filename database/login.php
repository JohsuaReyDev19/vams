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

header("Content-Type: application/json");

session_start(); // Start session to store login data

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (empty($username) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Username and password are required."]);
        exit;
    }

    // Include `Rule` in the SELECT query
    $stmt = $conn->prepare("SELECT id, full_name, username, password_hash, email, Rule FROM admin_accounts WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password_hash'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['Rule'];
            $_SESSION["loggedin"] = true;

            // Determine redirect URL based on role
            $redirect = './guardAccess/'; // Default
            if (strtolower($user['Rule']) === 'admin') {
                $redirect = './admin/admin.php';
            } elseif (strtolower($user['Rule']) === 'Security Personel') {
                $redirect = './guardAccess/';
            }
            echo json_encode([
                "success" => true,
                "message" => "Login successful.",
                "redirect" => $redirect
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Invalid password."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "User not found."]);
    }

    $stmt->close();
    $conn->close();
}
?>
