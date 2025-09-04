<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "my-rfid"; // ← Replace with your actual DB name

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get only the last 7 days
$sql = "
SELECT 
    DATE(time_in) AS log_date,
    COUNT(DISTINCT id) AS entries,
    (SELECT COUNT(DISTINCT id) FROM access_logs AS a2 WHERE DATE(a2.time_out) = DATE(a1.time_in)) AS exits
FROM access_logs AS a1
WHERE time_in IS NOT NULL AND DATE(time_in) >= CURDATE() - INTERVAL 6 DAY
GROUP BY log_date
ORDER BY log_date ASC
";

$result = $conn->query($sql);
$data = [];

while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}

echo json_encode($data);
$conn->close();
?>
