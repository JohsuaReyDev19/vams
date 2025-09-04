<?php
$host = "localhost";
$user = "root";
$pass = ""; // or your MySQL password
$db = "my-rfid";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['plate'])) {
    $plate = $_GET['plate'];
    $stmt = $conn->prepare("SELECT * FROM visitor_entries WHERE license_plate = ?");
    $stmt->bind_param("s", $plate);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Check latest access log (we'll also get the log date)
        $logStmt = $conn->prepare("
            SELECT id, time_in, time_out, DATE(time_in) AS log_date
            FROM access_logs
            WHERE plate_number = ?
            ORDER BY time_in DESC
            LIMIT 1
        ");
        $logStmt->bind_param("s", $plate);
        $logStmt->execute();
        $logResult = $logStmt->get_result();
        $log = $logResult->fetch_assoc();

        $today = date('Y-m-d');
        $action = 'entry'; // default action
        $logId = null;

        if ($log) {
            // Only consider it an "exit" if the latest log is from today AND no time_out yet
            if ($log['log_date'] === $today && empty($log['time_out'])) {
                $action = 'exit';
                $logId = $log['id'];
            }
        }

        echo json_encode([
            'success' => true,
            'plate' => $row['license_plate'],
            'name' => $row['owner_name'],
            'type' => $row['vehicle_type'],
            'color' => $row['vehicle_color'],
            'phone' => $row['phone_number'],
            'purpose' => $row['visit_purpose'],
            'action' => $action,
            'log_id' => $logId
        ]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
