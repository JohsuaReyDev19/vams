<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rfid = $_POST['rfid_number'];
    $today = date("Y-m-d"); // Get today's date
    $current_time = date("H:i:s");
    $current_period = (date("H") < 12) ? 'AM' : 'PM';

    // Check if vehicle exists in the registration database
    $stmt = $conn->prepare("SELECT owner_name, entry_type, plate_number, vehicle_type FROM vehicle_registration WHERE rfid_number = ?");
    $stmt->bind_param("s", $rfid);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        echo "<p class='text-red-500 text-center'>Error: RFID <strong>$rfid</strong> is <strong>not registered</strong>. Please register the vehicle.</p>";
        echo "RFID is not Registered";
        exit();
    }
    
    // Fetch vehicle details
    $row = $result->fetch_assoc();
    $owner_name = $row['owner_name'];
    $entry_type = $row['entry_type'];
    $plate_number = $row['plate_number'];
    $vehicle_type = $row['vehicle_type'];

    // Check today's logs
    $stmt = $conn->prepare("SELECT status FROM vehicle_logs WHERE rfid_number = ? AND DATE(scan_time) = ? ORDER BY scan_time DESC");
    $stmt->bind_param("ss", $rfid, $today);
    $stmt->execute();
    $log_result = $stmt->get_result();

    $entered_today = false;
    $exited_today = false;

    while ($log_row = $log_result->fetch_assoc()) {
        if ($log_row['status'] == "Entered") {
            $entered_today = true;
        }
        if ($log_row['status'] == "Exited") {
            $exited_today = true;
        }
    }

    // Restrict to only one entry and one exit per day
    if ($entered_today && $exited_today) {
        echo "Notice: Entry and exit already recorded for today. No further action needed.";
        exit();
    } elseif (!$entered_today) {
        $new_status = "Entered"; // Allow entry if no entry record exists today
    } elseif (!$exited_today) {
        $new_status = "Exited"; // Allow exit if entry was recorded but not exit
    } else {
        echo "<p class='text-red-500'>Error: Invalid status.</p>";
        exit();
    }

    // Insert new log entry
    $stmt = $conn->prepare("INSERT INTO vehicle_logs (rfid_number, owner_name, entry_type, plate_number, vehicle_type, status, scan_time) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssssss", $rfid, $owner_name, $entry_type, $plate_number, $vehicle_type, $new_status);
    
    if ($stmt->execute() && $new_status === "Entered") {
        echo "Success: Entered recorded";
    }elseif ($new_status === "Exited"){
        echo "Success: Exited recorded";
    } else {
        echo "<p class='text-red-500'>Error: Could not update log. Please try again.</p>";
    }

    // Close connections
    $stmt->close();
    $conn->close();
}
?>
