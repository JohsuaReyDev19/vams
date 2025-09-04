<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "wbrv";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$date = isset($_POST['date']) ? $_POST['date'] : '';
$owner = isset($_POST['owner']) ? $_POST['owner'] : '';
$vehicle = isset($_POST['vehicle']) ? $_POST['vehicle'] : '';
$entry = isset($_POST['entry']) ? $_POST['entry'] : '';

$query = "SELECT * FROM vehicle_logs WHERE 1";

if (!empty($date)) {
    $query .= " AND DATE(scan_time) = '$date'";
}
if (!empty($owner)) {
    $query .= " AND owner_name LIKE '%$owner%'";
}
if (!empty($vehicle)) {
    $query .= " AND vehicle_type = '$vehicle'";
}
if (!empty($entry)) {
    $query .= " AND entry_type = '$entry'";
}

$query .= " ORDER BY scan_time DESC";
$result = $conn->query($query);

$output = "";
while ($row = $result->fetch_assoc()) {
    $status_class = ($row['status'] == 'Entered') ? 'text-green-500' : 'text-red-500';
    $alert = "";
    
    // Convert date format to "Month Day, Year" (e.g., March 20, 2025)
    $formatted_date = date("F j, Y", strtotime($row['scan_time']));
    $formatted_time = date("h:i A", strtotime($row['scan_time']));
    // Check if RFID is registered
    $rfid_check = "SELECT COUNT(*) AS count FROM vehicle_registration WHERE rfid_number = '{$row['rfid_number']}'";
    $rfid_result = $conn->query($rfid_check);
    $rfid_data = $rfid_result->fetch_assoc();

    $output .= "<tr>                     
                    <td class='border p-2 text-center'>$formatted_date</td>                     
                    <td class='border p-2'>{$row['owner_name']}</td>                     
                    <td class='border p-2'>{$row['entry_type']}</td>                     
                    <td class='border p-2 text-center'>{$row['plate_number']}</td>                      
                    <td class='border p-2'>{$row['vehicle_type']}</td>                     
                    <td class='border p-2 text-center'>$formatted_time</td>                     
                    <td class='border p-2 $status_class'>{$row['status']}</td>                 
                </tr>";
}

echo $output;
?>
