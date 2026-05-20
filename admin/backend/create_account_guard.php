<?php
header('Content-Type: application/json');
error_reporting(0);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// PHPMailer FILES
require '../../phpmailer/Exception.php';
require '../../phpmailer/PHPMailer.php';
require '../../phpmailer/SMTP.php';

// DATABASE CONNECTION
$conn = new mysqli("localhost", "root", "", "my-rfid");

if ($conn->connect_error) {
    echo json_encode([
        "status" => "error",
        "message" => "DB connection failed"
    ]);
    exit;
}

// CHECK REQUEST METHOD
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request"
    ]);
    exit;
}

// INPUTS
$username   = trim($_POST['username'] ?? '');
$email      = trim($_POST['email'] ?? '');
$full_name  = trim($_POST['fullname'] ?? '');
$contact    = trim($_POST['contact'] ?? '');
$employeeId = trim($_POST['employeeId'] ?? '');
$address    = trim($_POST['address'] ?? '');
$rule       = trim($_POST['purpose'] ?? '');

// DEFAULT PASSWORD
$plainPassword = "default123";

// HASH PASSWORD
$password_hash = password_hash($plainPassword, PASSWORD_DEFAULT);

// VALIDATION
if (!$username || !$email || !$full_name) {
    echo json_encode([
        "status" => "error",
        "message" => "Missing required fields"
    ]);
    exit;
}

// CHECK USERNAME
$stmt = $conn->prepare("SELECT id FROM admin_accounts WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {

    echo json_encode([
        "status" => "error",
        "message" => "Username already exists"
    ]);

    exit;
}

$stmt->close();

// CHECK EMAIL
$stmt = $conn->prepare("SELECT id FROM admin_accounts WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {

    echo json_encode([
        "status" => "error",
        "message" => "Email already exists"
    ]);

    exit;
}

$stmt->close();

// INSERT ACCOUNT
$stmt = $conn->prepare("
    INSERT INTO admin_accounts
    (
        username,
        password_hash,
        full_name,
        contact_number,
        employeeId,
        address,
        email,
        Rule
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "ssssssss",
    $username,
    $password_hash,
    $full_name,
    $contact,
    $employeeId,
    $address,
    $email,
    $rule
);

// EXECUTE INSERT
if ($stmt->execute()) {

    // ================= EMAIL SEND =================

    $mail = new PHPMailer(true);

    try {

        // SMTP SETTINGS
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;

        // YOUR GMAIL
        $mail->Username   = 'burcejosh19@gmail.com';

        // GMAIL APP PASSWORD
        $mail->Password   = 'mwbt bwct grds fvsr';

        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // SENDER
        $mail->setFrom('burcejosh19@gmail.com', 'VAMS System');

        // RECEIVER
        $mail->addAddress($email, $full_name);

        // EMAIL FORMAT
        $mail->isHTML(true);

        // SUBJECT
        $mail->Subject = 'VAMS Account Credentials';

        // BODY
        $mail->Body = "
            <div style='font-family: Arial, sans-serif;'>

                <h2>Vehicle Access Monitoring System</h2>

                <p>Hello <b>$full_name</b>,</p>

                <p>Your Security Personnel account has been created successfully.</p>

                <table border='1' cellpadding='10' cellspacing='0' style='border-collapse: collapse;'>

                    <tr>
                        <td><b>Username</b></td>
                        <td>$username</td>
                    </tr>

                    <tr>
                        <td><b>Password</b></td>
                        <td>$plainPassword</td>
                    </tr>

                </table>

                <br>

                <p>Please change your password after your first login.</p>

                <br>

                <p><b>VAMS System</b></p>

            </div>
        ";

        // SEND EMAIL
        $mail->send();

        echo json_encode([
            "status" => "success",
            "message" => "Account created and email sent successfully",
            "password" => $plainPassword
        ]);

    } catch (Exception $e) {

        echo json_encode([
            "status" => "warning",
            "message" => "Account created but email failed: " . $mail->ErrorInfo,
            "password" => $plainPassword
        ]);
    }

} else {

    echo json_encode([
        "status" => "error",
        "message" => "Insert failed"
    ]);
}

$stmt->close();
$conn->close();
?>