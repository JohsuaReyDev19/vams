<?php
header('Content-Type: application/json');

// DB credentials
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

// Validate vehicle ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid or missing vehicle ID']);
    exit;
}

$vehicleId = intval($_GET['id']);

// Step 1: Get vehicle details from registered_vehicles
$vehicleStmt = $conn->prepare("SELECT * FROM registered_vehicles WHERE id = ?");
$vehicleStmt->bind_param("i", $vehicleId);
$vehicleStmt->execute();
$vehicleResult = $vehicleStmt->get_result();

if ($vehicleResult->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Vehicle not found']);
    $vehicleStmt->close();
    $conn->close();
    exit;
}

$vehicle = $vehicleResult->fetch_assoc();
$vehicleStmt->close();

$vehicle_id = $vehicle['vehicle_id'] ?? null;
if (!$vehicle_id) {
    echo json_encode(['success' => false, 'message' => 'vehicle_id field is empty']);
    $conn->close();
    exit;
}

// Step 2: Determine owner type
$ownerType = $_GET['ownertype'] ?? null;
$ownerName = 'Unknown';
$extraInfo = 'N/A';

if ($ownerType) {
    // Manual detection
    switch ($ownerType) {
        case 'Faculty':
            $query = "SELECT faculty_name AS owner_name, department AS extra_info FROM faculty_vehicles WHERE vehicle_id = ?";
            break;
        case 'OJT':
            $query = "SELECT ojt_name AS owner_name, company_name AS extra_info FROM ojt_vehicles WHERE vehicle_id = ?";
            break;
        case 'Student':
            $query = "SELECT student_name AS owner_name, course AS extra_info FROM student_vehicles WHERE vehicle_id = ?";
            break;
        default:
            $query = null;
    }

    if ($query) {
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $vehicle_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($ownerRow = $result->fetch_assoc()) {
            $ownerName = $ownerRow['owner_name'] ?? 'Unknown';
            $extraInfo = $ownerRow['extra_info'] ?? 'N/A';
        }
        $stmt->close();
    }
} else {
    // Auto-detect using JOINs
    $detectOwnerQuery = "
        SELECT 
            CASE
                WHEN fv.vehicle_id IS NOT NULL THEN 'Faculty'
                WHEN ov.vehicle_id IS NOT NULL THEN 'OJT'
                WHEN sv.vehicle_id IS NOT NULL THEN 'Student'
                ELSE 'Unknown'
            END AS owner_type,
            COALESCE(fv.faculty_name, ov.ojt_name, sv.student_name) AS owner_name,
            COALESCE(fv.department, ov.company_name, sv.course) AS extra_info
        FROM registered_vehicles rv
        LEFT JOIN faculty_vehicles fv ON fv.vehicle_id = rv.id
        LEFT JOIN ojt_vehicles ov ON ov.vehicle_id = rv.id
        LEFT JOIN student_vehicles sv ON sv.vehicle_id = rv.id
        WHERE rv.id = ?
    ";

    $ownerStmt = $conn->prepare($detectOwnerQuery);
    $ownerStmt->bind_param("i", $vehicleId);
    $ownerStmt->execute();
    $ownerResult = $ownerStmt->get_result();

    if ($ownerRow = $ownerResult->fetch_assoc()) {
        $ownerType = $ownerRow['owner_type'];
        $ownerName = $ownerRow['owner_name'] ?? 'Unknown';
        $extraInfo = $ownerRow['extra_info'] ?? 'N/A';
    }

    $ownerStmt->close();
}

// Final response
$vehicle['owner_type'] = $ownerType;
$vehicle['owner_name'] = $ownerName;
$vehicle['extra_info'] = $extraInfo;

echo json_encode([
    'success' => true,
    'vehicle' => $vehicle
]);

$conn->close();
?>
