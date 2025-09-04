<?php
include 'conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rfid = $_POST['rfid_number'];

    // Check if vehicle exists
    $stmt = $conn->prepare("SELECT owner_name, entry_type, plate_number, vehicle_type FROM vehicle_registration WHERE rfid_number = ?");
    $stmt->bind_param("s", $rfid);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        echo "<p class='text-red-500'>$rfid This RFID is not Registered</p>";
        exit();
    }
    
    $row = $result->fetch_assoc();
    $owner_name = $row['owner_name'];
    $entry_type = $row['entry_type'];
    $plate_number = $row['plate_number'];
    $vehicle_type = $row['vehicle_type'];

    // Check last log status for this specific RFID
    $stmt = $conn->prepare("SELECT status FROM vehicle_logs WHERE rfid_number = ? ORDER BY scan_time DESC LIMIT 1");
    $stmt->bind_param("s", $rfid);
    $stmt->execute();
    $log_result = $stmt->get_result();
    $last_status = ($log_result->num_rows > 0) ? $log_result->fetch_assoc()['status'] : 'Exited';

    // Ensure only one active entry per RFID
    $new_status = ($last_status == 'Entered') ? 'Exited' : 'Entered';

    // Insert new log record including the plate number and vehicle type
    $stmt = $conn->prepare("INSERT INTO vehicle_logs (rfid_number, owner_name, entry_type, plate_number, vehicle_type, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $rfid, $owner_name, $entry_type, $plate_number, $vehicle_type, $new_status);
    $stmt->execute();

    echo "<p class='text-green-500'>Status: $new_status for $owner_name ($entry_type) - Plate: $plate_number</p>";
}
?>
