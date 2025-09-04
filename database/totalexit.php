<?php 
    include 'db.php';
    // Get the count of 'Entered' vehicles
    $entry_count_query = "SELECT COUNT(time_out) AS total_entries FROM access_logs WHERE DATE(timestamp) = CURDATE()";
    $entry_count_result = $conn->query($entry_count_query);
    $entry_count_row = $entry_count_result->fetch_assoc();
    $total_exits = $entry_count_row['total_entries'];

    echo json_encode(["total_exit" => $total_exits]);
?>