<?php
$host = "localhost";
$user = "root";
$pass = ""; // or your MySQL password
$db = "my-rfid";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch logs where the timestamp is from today
$query = "SELECT * FROM access_logs WHERE DATE(timestamp) = CURDATE() ORDER BY timestamp DESC LIMIT 20";
$result = mysqli_query($conn, $query);

$logs = [];
while ($row = mysqli_fetch_assoc($result)) {
    $logs[] = $row;
}

echo json_encode($logs);
?>
