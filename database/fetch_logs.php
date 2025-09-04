<?php
include '../database/db.php';

$last_id = isset($_POST['last_id']) ? (int) $_POST['last_id'] : 0;

// Get today's date in word format
$today = date('F j, Y');

$query = "SELECT * FROM vehicle_logs WHERE log_id > $last_id AND DATE(scan_time) = CURDATE() ORDER BY scan_time DESC";
$result = $conn->query($query);

$logs = [];
$max_id = $last_id;

while ($row = $result->fetch_assoc()) {
    $status_class = ($row['status'] == 'Entered') ? 'text-green-500' : 'text-red-500';
    $alert = "";

    // Check if RFID is registered
    $rfid_check = "SELECT COUNT(*) AS count FROM vehicle_registration WHERE rfid_number = '{$row['rfid_number']}'";
    $rfid_result = $conn->query($rfid_check);
    $rfid_data = $rfid_result->fetch_assoc();

    // Convert scan_time to word format
    $formatted_scan_time = date('F j, Y g:i A', strtotime($row['scan_time']));

    // Determine vehicle icon based on type
    $vehicle_icon = ($row['vehicle_type'] == 'Car') ? 
        '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-car"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>' : 
        '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bike"><circle cx="18.5" cy="17.5" r="3.5"/><circle cx="5.5" cy="17.5" r="3.5"/><circle cx="15" cy="5" r="1"/><path d="M12 17.5V14l-3-3 4-3 2 3h2"/></svg>';

    $logs[] = [
        "log_id" => $row['log_id'],
        "plate_number" => htmlspecialchars($row['plate_number']),
        "owner_name" => htmlspecialchars($row['owner_name']),
        "vehicle_type" => htmlspecialchars($row['vehicle_type']),
        "status" => htmlspecialchars($row['status']),
        "scan_time" => $formatted_scan_time,
        "vehicle_icon" => $vehicle_icon,
        "alert" => $alert
    ];

    if ($row['log_id'] > $max_id) {
        $max_id = $row['log_id'];
    }
}

echo json_encode(["logs" => $logs, "last_id" => $max_id]);
?>
