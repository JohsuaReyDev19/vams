<?php 
    include 'db.php';
    $query = "SELECT COUNT(DISTINCT plate_number) AS total_vehicles FROM vehicle_registration";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    echo json_encode(["total_vehicles" => $row['total_vehicles']]);
?>