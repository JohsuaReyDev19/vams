<?php
include 'db.php';
$sql = "SELECT * FROM vehicle_registration ORDER BY registered_at DESC";
$result = $conn->query($sql);

$vehicles = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $vehicles[] = $row;
    }
    echo json_encode($vehicles);
} else {
    echo json_encode(["error" => "Query failed: " . $conn->error]);
}

$conn->close();
?>
