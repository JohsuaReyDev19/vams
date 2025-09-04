<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my-rfid";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]));
}

// SQL Query to join all vehicle owners and include email + vehicle image
$sql = "
    SELECT rv.id, rv.plate_number, rv.vehicle_type, rv.rfid_tag, rv.registered_at, 
           pv.vehicle_image, rv.vehicle_id, 
           'Faculty' AS owner_type, fv.faculty_name AS owner_name, 
           fv.department AS extra_info, fv.email AS owner_email
    FROM registered_vehicles rv
    JOIN faculty_vehicles fv ON rv.vehicle_id = fv.vehicle_id
    JOIN pre_registered_vehicles pv ON rv.vehicle_id = pv.id

    UNION 

    SELECT rv.id, rv.plate_number, rv.vehicle_type, rv.rfid_tag, rv.registered_at, 
           pv.vehicle_image, rv.vehicle_id, 
           'OJT' AS owner_type, ov.ojt_name AS owner_name, 
           ov.company_name AS extra_info, ov.email AS owner_email
    FROM registered_vehicles rv
    JOIN ojt_vehicles ov ON rv.vehicle_id = ov.vehicle_id
    JOIN pre_registered_vehicles pv ON rv.vehicle_id = pv.id

    UNION 

    SELECT rv.id, rv.plate_number, rv.vehicle_type, rv.rfid_tag, rv.registered_at, 
           pv.vehicle_image, rv.vehicle_id, 
           'Student' AS owner_type, sv.student_name AS owner_name, 
           sv.course AS extra_info, sv.email AS owner_email
    FROM registered_vehicles rv
    JOIN student_vehicles sv ON rv.vehicle_id = sv.vehicle_id
    JOIN pre_registered_vehicles pv ON rv.vehicle_id = pv.id
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $vehicles = [];
    while ($row = $result->fetch_assoc()) {
        $row['vehicle_image'] = $row['vehicle_image'] ? 'uploads/' . $row['vehicle_image'] : '../images/default_vehicle.jpg';
        $vehicles[] = $row;
    }
    echo json_encode(['success' => true, 'vehicles' => $vehicles]);
} else {
    echo json_encode(['success' => false, 'message' => "No registered vehicles found."]);
}

$conn->close();
