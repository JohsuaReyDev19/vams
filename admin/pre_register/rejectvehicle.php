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
            $mail->Subject = "PRMSU Vehicle Registration - Approved ✅";
            $mail->Body = "
                Dear $name,<br><br>
                We are pleased to inform you that your vehicle registration under the category '<strong>$ownerType</strong>' has been <strong>Approved</strong>.<br><br>
                Please proceed to our office to claim your RFID Card.<br><br>
                Thank you for supporting the <strong>Web-based RFID Card Vehicle Access Monitoring System</strong>.<br><br>
                <em>- PRMSU Candelaria Vehicle Registration Office</em>
            ";
        } else if ($status === 'Rejected') {
            $mail->Subject = "PRMSU Vehicle Registration - Rejected ❌";
            $mail->Body = "
                Dear $name,<br><br>
                We regret to inform you that your vehicle registration under the category '<strong>$ownerType</strong>' has been <strong>Rejected</strong>.<br><br>
                Possible reasons might include incomplete or incorrect information.<br><br>
                <strong>What you can do next:</strong><br>
                - Review your registration details.<br>
                - Contact the Vehicle Registration Office for further assistance.<br><br>
                We encourage you to reapply if necessary.<br><br>
                <em>- PRMSU Candelaria Vehicle Registration Office</em>
            ";
        }

        $mail->send();
        
    } catch (Exception $e) {
        error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
        // Optional: Log error somewhere, but don't break API response
    }
}

?>
