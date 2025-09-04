<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rfid = $_POST['rfid'];

    // Example: Simulating a response
    echo json_encode(["status" => "success", "message" => "RFID Received: $rfid"]);

    // You can add database insertion or validation here
}
?>
