<?php 
    include 'db.php';
    $query = "SELECT COUNT(DISTINCT owner_name) AS total_owners FROM vehicle_registration";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    echo json_encode(["total_owners" => $row['total_owners']]);
?>