<?php
header("Content-Type: application/json");

include './db.php';

// Get latest semester
$sql = "SELECT * FROM semester_control ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode([
        "success" => true,
        "start_date" => $row['start_date'],
        "end_date" => $row['end_date'],
        "today" => date("Y-m-d")
    ]);
} else {
    echo json_encode(["success" => false, "message" => "No semester found"]);
}

$conn->close();
