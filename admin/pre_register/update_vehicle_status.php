<?php
// update_vehicle_status.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../phpmailer/PHPMailer.php';
require '../../phpmailer/SMTP.php';
require '../../phpmailer/Exception.php';

header('Content-Type: application/json');

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my-rfid";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Validate vehicle_id
if (!isset($_POST['vehicle_id']) || empty($_POST['vehicle_id'])) {
    echo json_encode(['success' => false, 'message' => 'Missing vehicle_id']);
    exit;
}

$vehicleId = intval($_POST['vehicle_id']);
$status = isset($_POST['status']) ? $_POST['status'] : null;
$rfidTag = isset($_POST['rfid_tag']) ? $conn->real_escape_string($_POST['rfid_tag']) : null;
$ownerEmail = $_POST['email'];
$ownerName = $_POST['name'];
$ownerType = $_POST['owner_type'];

if (!$ownerEmail) {
    echo json_encode(['success' => false, 'message' => 'Owner email not found']);
    exit;
}

// Start transaction
$conn->begin_transaction();

try {
    // CASE 1: Approving with RFID
    if ($rfidTag !== null) {
        // Approve and assign RFID
        $update_sql = "UPDATE pre_registered_vehicles SET status = 'Approved', rfid_tag = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("si", $rfidTag, $vehicleId);

        if (!$stmt->execute()) {
            throw new Exception("Error approving vehicle: " . $stmt->error);
        }
        $stmt->close();

        // Insert into registered_vehicles if not already inserted
        $insert_sql = "INSERT INTO registered_vehicles (vehicle_id, plate_number, vehicle_type, rfid_tag, registered_at)
                       SELECT id, plate_number, vehicle_type, rfid_tag, NOW()
                       FROM pre_registered_vehicles
                       WHERE id = ? 
                       AND NOT EXISTS (SELECT 1 FROM registered_vehicles WHERE vehicle_id = ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("ii", $vehicleId, $vehicleId);

        if (!$insert_stmt->execute()) {
            throw new Exception("Error inserting registered vehicle: " . $insert_stmt->error);
        }
        $insert_stmt->close();

        // Send email notification
        sendEmailNotification($ownerEmail, $ownerName, $ownerType, 'Approved');
    }
    // CASE 2: Updating status (Rejected only)
    else if ($status !== null) {
        // Validate status
        $allowed_statuses = ['Approved', 'Rejected'];
        if (!in_array($status, $allowed_statuses)) {
            throw new Exception('Invalid status value');
        }

        $update_sql = "UPDATE pre_registered_vehicles SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("si", $status, $vehicleId);

        if (!$stmt->execute()) {
            throw new Exception("Error updating vehicle status: " . $stmt->error);
        }
        $stmt->close();

        // Send email notification
        sendEmailNotification($ownerEmail, $ownerName, $ownerType, $status);
    }
    // Neither RFID nor status sent properly
    else {
        throw new Exception('Missing status or RFID tag');
    }

    // Commit if everything is okay
    $conn->commit();
    echo json_encode(['success' => true, 'message' => 'Vehicle updated successfully.']);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

// Close connection
$conn->close();

function sendEmailNotification($email, $name, $ownerType, $status) {
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP();  
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'prmsucandelaria04@gmail.com'; // Your email
        $mail->Password = 'mvqd srpp dtxv bwti'; // App password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
        // Recipients
        $mail->setFrom('prmsucandelaria04@gmail.com', 'PRMSU Candelaria Vehicle Registration');
        $mail->addAddress($email, $name);
        
        // Content
        $mail->isHTML(true);
        
        if ($status === 'Approved') {
            $mail->Subject = "PRMSU Vehicle Registration - Approved";
            $mail->Body = "Dear $name,<br><br>Congratulations! Your vehicle registration under the category '$ownerType' has been <strong>Approved</strong>.<br><br>"
                        . "Please proceed to our office to claim your RFID Card for the <strong>Web-based RFID Card Vehicle Access Monitoring System</strong>.<br><br>"
                        . "Thank you for your cooperation.<br><br>- Vehicle Registration Office";
        } else if ($status === 'Rejected') {
            $mail->Subject = "PRMSU Vehicle Registration - Rejected";
            $mail->Body = "Dear $name,<br><br>Unfortunately, your vehicle registration under the category '$ownerType' has been <strong>Rejected</strong>.<br><br>"
                        . "Please contact the Vehicle Registration Office for more information.<br><br>- Vehicle Registration Office";
        }

        $mail->send();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => "Email could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
    }
}

?>
