<?php 
    // include 'db.php';

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "my-rfid";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die(json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]));
    }
    // Get the count of 'Entered' vehicles
    $entry_count_query = "SELECT COUNT(time_in) AS total_entries FROM access_logs WHERE DATE(timestamp) = CURDATE()";
    $entry_count_result = $conn->query($entry_count_query);
    $entry_count_row = $entry_count_result->fetch_assoc();
    $total_entries = $entry_count_row['total_entries'];

    echo json_encode(["total_entered" => $total_entries]);
?>