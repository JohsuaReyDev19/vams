<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my-rfid";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]);
    exit;
}

$rfid_tag = $_POST['rfid_tag'] ?? '';
$access   = $_POST['access'] ?? '';

if (empty($rfid_tag) || empty($access)) {
    echo json_encode(['success' => false, 'message' => 'RFID Tag is required']);
    exit;
}

/**
 * STEP 1 — Search registered_vehicles
 */
$stmt = $conn->prepare("
    SELECT rv.rfid_tag, rv.plate_number, rv.vehicle_type,
           CASE 
               WHEN fv.faculty_name IS NOT NULL THEN fv.faculty_name
               WHEN ov.ojt_name IS NOT NULL THEN ov.ojt_name
               WHEN sv.student_name IS NOT NULL THEN sv.student_name
               ELSE 'Unknown'
           END AS owner_name,
           CASE 
               WHEN fv.purpose IS NOT NULL THEN fv.purpose
               WHEN ov.purpose IS NOT NULL THEN ov.purpose
               WHEN sv.purpose IS NOT NULL THEN sv.purpose
               ELSE 'Unknown'
           END AS purpose
    FROM registered_vehicles rv
    LEFT JOIN faculty_vehicles fv ON rv.vehicle_id = fv.vehicle_id
    LEFT JOIN ojt_vehicles ov ON rv.vehicle_id = ov.vehicle_id
    LEFT JOIN student_vehicles sv ON rv.vehicle_id = sv.vehicle_id
    WHERE rv.rfid_tag = ?
    LIMIT 1
");
$stmt->bind_param("s", $rfid_tag);
$stmt->execute();
$result = $stmt->get_result();
$vehicle = $result->fetch_assoc();

/**
 * STEP 2 — If not found in registered_vehicles, search vehicles table
 */
if (!$vehicle) {
    $stmt2 = $conn->prepare("
        SELECT rfid_tag, license_plate AS plate_number, vehicle_type, owner_name, purpose
        FROM vehicles
        WHERE rfid_tag = ?
        LIMIT 1
    ");
    $stmt2->bind_param("s", $rfid_tag);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $vehicle = $result2->fetch_assoc();
}

/**
 * STEP 3 — If still not found, return error
 */
if (!$vehicle) {
    echo json_encode(['success' => false, 'message' => 'RFID Tag not registered']);
    exit;
}

/**
 * STEP 4 — Check if TIME IN exists without TIME OUT today
 */
$stmtLog = $conn->prepare("
    SELECT id, time_in FROM access_logs 
    WHERE rfid_tag = ? 
    AND DATE(time_in) = CURDATE()
    AND time_out IS NULL 
    ORDER BY id DESC 
    LIMIT 1
");
$stmtLog->bind_param("s", $rfid_tag);
$stmtLog->execute();
$logResult = $stmtLog->get_result();

if ($logResult->num_rows > 0) {
    $row = $logResult->fetch_assoc();
    $logId = $row['id'];

    // Check time difference
    $checkTime = $conn->prepare("SELECT TIMESTAMPDIFF(SECOND, time_in, NOW()) as seconds_elapsed FROM access_logs WHERE id = ?");
    $checkTime->bind_param("i", $logId);
    $checkTime->execute();
    $timeResult = $checkTime->get_result();

    if ($timeResult->num_rows > 0) {
        $timeData = $timeResult->fetch_assoc();
        if ($timeData['seconds_elapsed'] < 180) {
            echo json_encode([
                'success' => false,
                'action' => 'deny_exit',
                'message' => 'Exit not allowed. Please wait at least 3 minutes after entry.'
            ]);
            exit;
        } else {
            // Allow TIME OUT
            $update = $conn->prepare("UPDATE access_logs SET time_out = NOW() WHERE id = ?");
            $update->bind_param("i", $logId);
            $update->execute();

            echo json_encode([
                'success' => true,
                'action' => 'timeout',
                'message' => 'Time Out recorded'
            ]);
            exit;
        }
    }
} else {
    // Record TIME IN
    $insert = $conn->prepare("
        INSERT INTO access_logs (rfid_tag, owner_name, purpose, plate_number, vehicle_type, time_in, access_by) 
        VALUES (?, ?, ?, ?, ?, NOW(), ?)
    ");
    $insert->bind_param(
        "ssssss", 
        $vehicle['rfid_tag'], 
        $vehicle['owner_name'], 
        $vehicle['purpose'], 
        $vehicle['plate_number'], 
        $vehicle['vehicle_type'], 
        $access
    );
    $insert->execute();

    echo json_encode(['success' => true, 'action' => 'timein', 'message' => 'Time In recorded']);
    exit;
}

$conn->close();
?>
