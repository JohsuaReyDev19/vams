<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM vehicle_registration WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $vehicle = mysqli_fetch_assoc($result);

    if ($vehicle) {
        echo json_encode($vehicle);
    } else {
        echo json_encode(null);
    }
}
?>
