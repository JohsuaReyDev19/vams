<?php
header('Content-Type: application/json');
session_start();

// PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/Exception.php';
require '../phpmailer/PHPMailer.php';
require '../phpmailer/SMTP.php';

// Database connection
$conn = new mysqli("localhost", "root", "", "my-rfid");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "DB Connection failed"]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email']);
    if (empty($email)) {
        echo json_encode(["success" => false, "message" => "Please enter your email"]);
        exit;
    }

    // Check if email exists
    $stmt = $conn->prepare("SELECT id, full_name FROM admin_accounts WHERE email = ? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo json_encode(["success" => false, "message" => "Email not found"]);
        exit;
    }

    $user = $result->fetch_assoc();

    // Generate reset code
    $reset_code = rand(100000, 999999);
    $expires_at = date("Y-m-d H:i:s", strtotime("+15 minutes"));

    // Delete old reset codes for this email
    $conn->query("DELETE FROM password_resets WHERE email = '".$conn->real_escape_string($email)."'");

    // Insert new reset record
    $stmt = $conn->prepare("INSERT INTO password_resets (email, reset_code, expires_at) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $reset_code, $expires_at);
    $stmt->execute();

    // Send email via PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'burcejosh19@gmail.com';         // Your Gmail
        $mail->Password   = 'oxcyewwxjrakajuc';       // Gmail App Password (16 chars)
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('burcejosh19@gmail.com', 'PRMSU VAMS'); // Must match Gmail
        $mail->addAddress($email, $user['full_name']);

        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Code';
        $mail->Body    = "
            <p>Hello <b>{$user['full_name']}</b>,</p>
            <p>Your password reset code is: <b>{$reset_code}</b></p>
            <p>This code will expire in 15 minutes.</p>
            <br>
            <p>- PRMSU Access System</p>
        ";

        $mail->send();
        echo json_encode(["success" => true, "message" => "The code sent to your email"]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Mailer Error: " . $mail->ErrorInfo]);
    }
}
?>
