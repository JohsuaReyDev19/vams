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

// SQL query to fetch only rejected vehicles
$sql = "SELECT pre_registered_vehicles.id AS vehicle_id, 
               pre_registered_vehicles.plate_number, 
               pre_registered_vehicles.vehicle_type, 
               pre_registered_vehicles.rfid_tag, 
               pre_registered_vehicles.vehicle_image, 
               pre_registered_vehicles.status, 
               'student' AS owner_type, 
               CONCAT(IFNULL(student_vehicles.student_name, ''), ', ', 
                      IFNULL(student_vehicles.student_id_number, ''), ', ', 
                      IFNULL(student_vehicles.course, ''), ', ', 
                      IFNULL(student_vehicles.year_level, '')) AS owner_details, 
               pre_registered_vehicles.registered_at
        FROM pre_registered_vehicles
        JOIN student_vehicles ON pre_registered_vehicles.id = student_vehicles.vehicle_id 
        WHERE pre_registered_vehicles.status = 'Rejected' 

        UNION

        SELECT pre_registered_vehicles.id AS vehicle_id, 
               pre_registered_vehicles.plate_number, 
               pre_registered_vehicles.vehicle_type, 
               pre_registered_vehicles.rfid_tag, 
               pre_registered_vehicles.vehicle_image, 
               pre_registered_vehicles.status, 
               'faculty' AS owner_type, 
               CONCAT(IFNULL(faculty_vehicles.faculty_name, ''), ', ', 
                      IFNULL(faculty_vehicles.department, ''), ', ', 
                      IFNULL(faculty_vehicles.position, '')) AS owner_details, 
               pre_registered_vehicles.registered_at
        FROM pre_registered_vehicles
        JOIN faculty_vehicles ON pre_registered_vehicles.id = faculty_vehicles.vehicle_id 
        WHERE pre_registered_vehicles.status = 'Rejected' 

        UNION 

        SELECT pre_registered_vehicles.id AS vehicle_id, 
               pre_registered_vehicles.plate_number, 
               pre_registered_vehicles.vehicle_type, 
               pre_registered_vehicles.rfid_tag, 
               pre_registered_vehicles.vehicle_image, 
               pre_registered_vehicles.status, 
               'ojt' AS owner_type, 
               CONCAT(IFNULL(ojt_vehicles.ojt_name, ''), ', ', 
                      IFNULL(ojt_vehicles.company_name, ''), ', ', 
                      IFNULL(ojt_vehicles.supervisor_name, '')) AS owner_details, 
               pre_registered_vehicles.registered_at
        FROM pre_registered_vehicles
        JOIN ojt_vehicles ON pre_registered_vehicles.id = ojt_vehicles.vehicle_id 
        WHERE pre_registered_vehicles.status = 'Rejected'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $vehicles = [];
    while ($row = $result->fetch_assoc()) {
        // Ensure correct image path
        $row['vehicle_image'] = !empty($row['vehicle_image']) ? "uploads/" . $row['vehicle_image'] : null;
        $vehicles[] = $row;
    }
    echo json_encode(['success' => true, 'vehicles' => $vehicles]);
} else {
    echo json_encode(['success' => false, 'message' => "No rejected vehicles found."]);
}

$conn->close();
?>
