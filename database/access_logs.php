<?php
include './db.php';

$date = isset($_POST['date']) ? $_POST['date'] : '';
$owner = isset($_POST['owner']) ? $_POST['owner'] : '';
$vehicle = isset($_POST['vehicle']) ? $_POST['vehicle'] : '';

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

$query .= " ORDER BY scan_time DESC";
$result = $conn->query($query);

$output = "";
while ($row = $result->fetch_assoc()) {
    $status_class = ($row['status'] == 'Entered') ? 'text-green-500' : 'text-red-500';
    $alert = "";

    // Check if RFID is registered
    $rfid_check = "SELECT COUNT(*) AS count FROM vehicle_registration WHERE rfid_number = '{$row['rfid_number']}'";
    $rfid_result = $conn->query($rfid_check);
    $rfid_data = $rfid_result->fetch_assoc();

    if ($rfid_data['count'] == 0) {
        $alert = "<span class='text-red-500 font-bold'>Unregistered RFID!</span>";
    }

    $output .= "<tr>
                    <td class='border p-2'>{$row['rfid_number']}</td>
                    <td class='border p-2'>{$row['owner_name']}</td>
                    <td class='border p-2'>{$row['entry_type']}</td>
                    <td class='border p-2'>{$row['plate_number']}</td> 
                    <td class='border p-2'>{$row['vehicle_type']}</td>
                    <td class='border p-2'>{$row['scan_time']}</td>
                    <td class='border p-2 $status_class'>{$row['status']}</td>
                    <td class='border p-2'>$alert</td>
                </tr>";
}

echo $output;
?>
