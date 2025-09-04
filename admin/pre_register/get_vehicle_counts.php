<?php
// Database connection
$host = 'localhost';
$user = 'root'; // Change if needed
$password = ''; // Change if needed
$database = 'my-rfid'; // Replace with your database name

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to count approved vehicles
$sqlApproved = "SELECT COUNT(*) AS approved_count FROM registered_vehicles";
$resultApproved = $conn->query($sqlApproved);
$approvedCount = $resultApproved->fetch_assoc()['approved_count'];

// Query to count pending vehicles
$sqlPending = "SELECT COUNT(*) AS pending_count FROM pre_registered_vehicles WHERE status = 'Pending'";
$resultPending = $conn->query($sqlPending);
$pendingCount = $resultPending->fetch_assoc()['pending_count'];

$sqlPending = "SELECT COUNT(*) AS rejected_count FROM pre_registered_vehicles WHERE status = 'Rejected'";
$resultPending = $conn->query($sqlPending);
$rejectedCount = $resultPending->fetch_assoc()['rejected_count'];

// Return data as JSON
echo json_encode([
    'approved' => $approvedCount,
    'pending' => $pendingCount,
    'rejected' => $rejectedCount
]);

$conn->close();
?>
