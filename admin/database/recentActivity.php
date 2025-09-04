<?php
require './db.php';

// Set response type to JSON
header('Content-Type: application/json');

// Initialize response
$response = [];

try {
    // Get today's date in Y-m-d format
    $today = date('Y-m-d');

    // Prepare SQL query to fetch today's access logs
    $sql = "SELECT plate_number, time_in, time_out, timestamp 
            FROM access_logs 
            WHERE DATE(timestamp) = ?
            ORDER BY timestamp DESC 
            LIMIT 10";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('s', $today);
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch results
        while ($row = $result->fetch_assoc()) {
            $response[] = $row;
        }

        $stmt->close();
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Database query preparation failed.']);
        exit;
    }

    echo json_encode($response);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error occurred.', 'details' => $e->getMessage()]);
}
?>
