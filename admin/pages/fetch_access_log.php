<?php 
header("Content-Type: application/json");
require '../database/db.php'; // <-- adjust to your db connection file

$response = ["success" => false, "data" => []];

try {
    $sql = "SELECT * FROM access_logs ORDER BY id DESC"; // latest first
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $logs = [];
        while ($row = $result->fetch_assoc()) {
            // ✅ Format time fields if not null
            if (!empty($row['time_in'])) {
                $row['time_in'] = date("g:i a", strtotime($row['time_in'])); // 4:50 pm
            }
            if (!empty($row['time_out'])) {
                $row['time_out'] = date("g:i a", strtotime($row['time_out']));
            }
            if (!empty($row['timestamp'])) {
                $row['timestamp'] = date("M d, Y", strtotime($row['timestamp'])); // Aug 23, 2025 4:50 pm
            }

            $logs[] = $row;
        }
        $response = ["success" => true, "data" => $logs];
    } else {
        $response = ["success" => true, "data" => []];
    }
} catch (Exception $e) {
    $response = ["success" => false, "message" => $e->getMessage()];
}

echo json_encode($response);
$conn->close();
