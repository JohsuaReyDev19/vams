<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my-rfid";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]));
}

$sql = "SELECT pre_registered_vehicles.id AS vehicle_id, 
               pre_registered_vehicles.plate_number, 
               pre_registered_vehicles.vehicle_type, 
               pre_registered_vehicles.rfid_tag, 
               pre_registered_vehicles.vehicle_image, 
               pre_registered_vehicles.status, 
               pre_registered_vehicles.student_id_number, -- this line is added
               'student' AS owner_type, 
               CONCAT(IFNULL(student_vehicles.student_name, ''), ', ', 
                      IFNULL(student_vehicles.student_id_number, ''), ', ', 
                      IFNULL(student_vehicles.course, ''), ', ', 
                      IFNULL(student_vehicles.year_level, '')) AS owner_details, 
               student_vehicles.email AS owner_email, 
               pre_registered_vehicles.registered_at
        FROM pre_registered_vehicles
        JOIN student_vehicles ON pre_registered_vehicles.id = student_vehicles.vehicle_id 
        WHERE pre_registered_vehicles.status = 'Pending' 

        UNION

        SELECT pre_registered_vehicles.id AS vehicle_id, 
               pre_registered_vehicles.plate_number, 
               pre_registered_vehicles.vehicle_type, 
               pre_registered_vehicles.rfid_tag, 
               pre_registered_vehicles.vehicle_image, 
               pre_registered_vehicles.status, 
               NULL AS student_id_number, -- keep column alignment
               'faculty' AS owner_type, 
               CONCAT(IFNULL(faculty_vehicles.faculty_name, ''), ', ', 
                      IFNULL(faculty_vehicles.department, ''), ', ', 
                      IFNULL(faculty_vehicles.position, '')) AS owner_details, 
               faculty_vehicles.email AS owner_email, 
               pre_registered_vehicles.registered_at
        FROM pre_registered_vehicles
        JOIN faculty_vehicles ON pre_registered_vehicles.id = faculty_vehicles.vehicle_id 
        WHERE pre_registered_vehicles.status = 'Pending' 

        UNION 

        SELECT pre_registered_vehicles.id AS vehicle_id, 
               pre_registered_vehicles.plate_number, 
               pre_registered_vehicles.vehicle_type, 
               pre_registered_vehicles.rfid_tag, 
               pre_registered_vehicles.vehicle_image, 
               pre_registered_vehicles.status, 
               NULL AS student_id_number, -- keep column alignment
               'ojt' AS owner_type, 
               CONCAT(IFNULL(ojt_vehicles.ojt_name, ''), ', ', 
                      IFNULL(ojt_vehicles.company_name, ''), ', ', 
                      IFNULL(ojt_vehicles.supervisor_name, '')) AS owner_details, 
               ojt_vehicles.email AS owner_email, 
               pre_registered_vehicles.registered_at
        FROM pre_registered_vehicles
        JOIN ojt_vehicles ON pre_registered_vehicles.id = ojt_vehicles.vehicle_id 
        WHERE pre_registered_vehicles.status = 'Pending'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $vehicles = [];
    while ($row = $result->fetch_assoc()) {
        $row['vehicle_image'] = !empty($row['vehicle_image']) ? "uploads/" . $row['vehicle_image'] : null;
        $vehicles[] = $row;
    }
    echo json_encode(['success' => true, 'vehicles' => $vehicles]);
} else {
    echo json_encode(['success' => false, 'message' => "No pending vehicles."]);
}

$conn->close();
?>
