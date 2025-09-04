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

// Return data as JSON
echo json_encode([
    'registered' => $approvedCount
]);

$conn->close();
?>
