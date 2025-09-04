<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/PHPMailer.php';
require '../phpmailer/SMTP.php';
require '../phpmailer/Exception.php';

header('Content-Type: application/json'); // Always return JSON

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my-rfid";

// Connect to database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Validate required general vehicle fields
$requiredFields = ['plate_number', 'vehicle_type', 'owner_type'];
foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
        exit;
    }
}

$plate_number = $_POST['plate_number'];
$vehicle_type = $_POST['vehicle_type'];
$owner_type = $_POST['owner_type'];

// Handle image upload
if (!isset($_FILES["image_name"]) || $_FILES["image_name"]["error"] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => "Image upload error."]);
    exit;
}

$targetDir = "../admin/pages/uploads/";
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

$imageName = time() . "_" . basename($_FILES["image_name"]["name"]);
$targetFilePath = $targetDir . $imageName;
$imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

if (!in_array($imageFileType, $allowedExtensions)) {
    echo json_encode(['success' => false, 'message' => "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed."]);
    exit;
}

if (!move_uploaded_file($_FILES["image_name"]["tmp_name"], $targetFilePath)) {
    echo json_encode(['success' => false, 'message' => "Image upload failed."]);
    exit;
}

// Fallback if student_id_number is not set (for faculty/OJT)
$stud_id = $_POST['student_id_number'] ?? null;

// Insert into pre_registered_vehicles
$sql_vehicle = "INSERT INTO pre_registered_vehicles (plate_number, vehicle_type, vehicle_image, email, status, student_id_number) VALUES (?, ?, ?, ?, 'Pending', ?)";
$stmt_vehicle = $conn->prepare($sql_vehicle);
$stmt_vehicle->bind_param("sssss", $plate_number, $vehicle_type, $imageName, $_POST['email'], $stud_id);

if (!$stmt_vehicle->execute()) {
    echo json_encode(['success' => false, 'message' => "Database error: " . $stmt_vehicle->error]);
    $stmt_vehicle->close();
    $conn->close();
    exit;
}

$vehicle_id = $stmt_vehicle->insert_id;
$stmt_vehicle->close();

$student = "Student";
$ojt = "Ojt";
$faculty = "Faculty";

// Owner-specific logic
switch ($owner_type) {
    case 'student':
        $required = ['student_name', 'student_id_number', 'course', 'year_level', 'email'];
        foreach ($required as $field) {
            if (!isset($_POST[$field])) {
                echo json_encode(['success' => false, 'message' => "Missing student detail: $field"]);
                exit;
            }
        }

        $stmt = $conn->prepare("INSERT INTO student_vehicles (vehicle_id, student_name, student_id_number, course, year_level, email, purpose) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss",
            $vehicle_id,
            $_POST['student_name'],
            $_POST['student_id_number'],
            $_POST['course'],
            $_POST['year_level'],
            $_POST['email'],
            $student
        );
        $notify_name = $_POST['student_name'];
        break;

    case 'faculty':
        $required = ['faculty_name', 'department', 'position', 'email'];
        foreach ($required as $field) {
            if (!isset($_POST[$field])) {
                echo json_encode(['success' => false, 'message' => "Missing faculty detail: $field"]);
                exit;
            }
        }

        $stmt = $conn->prepare("INSERT INTO faculty_vehicles (vehicle_id, faculty_name, department, position, email, purpose) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss",
            $vehicle_id,
            $_POST['faculty_name'],
            $_POST['department'],
            $_POST['position'],
            $_POST['email'],
            $faculty
        );
        $notify_name = $_POST['faculty_name'];
        break;

    case 'ojt':
        $required = ['ojt_name', 'company_name', 'supervisor_name', 'email'];
        foreach ($required as $field) {
            if (!isset($_POST[$field])) {
                echo json_encode(['success' => false, 'message' => "Missing OJT detail: $field"]);
                exit;
            }
        }

        $stmt = $conn->prepare("INSERT INTO ojt_vehicles (vehicle_id, ojt_name, company_name, supervisor_name, email, purpose) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss",
            $vehicle_id,
            $_POST['ojt_name'],
            $_POST['company_name'],
            $_POST['supervisor_name'],
            $_POST['email'],
            $ojt
        );
        $notify_name = $_POST['ojt_name'];
        break;

    default:
        echo json_encode(['success' => false, 'message' => "Invalid owner type."]);
        $conn->close();
        exit;
}

if ($stmt->execute()) {
    $stmt->close();
    $conn->close();

    // Send emails
    sendEmailNotification($_POST['email'], $notify_name, $owner_type);

    echo json_encode(['success' => true, 'message' => ucfirst($owner_type) . " vehicle registered successfully. Pending Approval."]);
} else {
    echo json_encode(['success' => false, 'message' => "Owner registration error: " . $stmt->error]);
    $stmt->close();
    $conn->close();
    exit;
}

// Function to send email notification
function sendEmailNotification($email, $name, $ownerType) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();  
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'prmsucandelaria04@gmail.com';
        $mail->Password = 'mvqd srpp dtxv bwti';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // To User
        $mail->setFrom('prmsucandelaria04@gmail.com', 'Web-based RFID Registration');
        $mail->addAddress($email, $name); 
        $mail->isHTML(true);
        $mail->Subject = "PRMSU Vehicle Registration - Pending Approval";
        $mail->Body = "Dear $name,<br><br>Your vehicle registration under '$ownerType' has been received and is pending approval.<br><br>- Vehicle Registration Office";
        $mail->send();

        // Notify Admin
        $adminMail = new PHPMailer(true);
        $adminMail->isSMTP();  
        $adminMail->Host = 'smtp.gmail.com'; 
        $adminMail->SMTPAuth = true;
        $adminMail->Username = 'prmsucandelaria04@gmail.com';
        $adminMail->Password = 'mvqd srpp dtxv bwti';
        $adminMail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $adminMail->Port = 587;
        $adminMail->setFrom('prmsucandelaria04@gmail.com', 'Web-based RFID Registration');
        $adminMail->addAddress('prmsucandelaria04@gmail.com', 'PRMSU Admin');
        $adminMail->isHTML(true);
        $adminMail->Subject = "New $ownerType Vehicle Registration Submitted";
        $adminMail->Body = "Admin,<br><br>$name has registered a vehicle under the '$ownerType' category.<br><br>Please log in to review and approve.<br><br>- System Notification";
        $adminMail->send();

    } catch (Exception $e) {
        // Optional: log to file instead of echo
        error_log("Email error: " . $mail->ErrorInfo);
    }
}
