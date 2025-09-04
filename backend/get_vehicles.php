<?php
// get_vehicles.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "vehicle-prmsu";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]));
}

$sql = "SELECT vehicles.id as vehicle_id, vehicles.plate_number, vehicles.vehicle_type, vehicles.rfid_tag, vehicles.vehicle_image, 'student' as owner_type, 
        CONCAT(student_vehicles.student_name, ', ', student_vehicles.student_id_number, ', ', student_vehicles.course, ', ', student_vehicles.year_level) as owner_details 
        FROM vehicles 
        JOIN student_vehicles ON vehicles.id = student_vehicles.vehicle_id 
        WHERE vehicles.status = 'Pending' 

        UNION
        
        SELECT vehicles.id as vehicle_id, vehicles.plate_number, vehicles.vehicle_type, vehicles.rfid_tag, vehicles.vehicle_image, 'faculty' as owner_type, 
        CONCAT(faculty_vehicles.faculty_name, ', ', faculty_vehicles.department, ', ', faculty_vehicles.position) as owner_details 
        FROM vehicles 
        JOIN faculty_vehicles ON vehicles.id = faculty_vehicles.vehicle_id 
        WHERE vehicles.status = 'Pending' 

        UNION 

        SELECT vehicles.id as vehicle_id, vehicles.plate_number, vehicles.vehicle_type, vehicles.rfid_tag, vehicles.vehicle_image, 'ojt' as owner_type, 
        CONCAT(ojt_vehicles.ojt_name, ', ', ojt_vehicles.company_name, ', ', ojt_vehicles.supervisor_name) as owner_details 
        FROM vehicles 
        JOIN ojt_vehicles ON vehicles.id = ojt_vehicles.vehicle_id 
        WHERE vehicles.status = 'Pending'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $vehicles = [];
    while ($row = $result->fetch_assoc()) {
        // Append full image URL (assuming images are stored in 'uploads/' folder)
        $row['vehicle_image'] = !empty($row['vehicle_image']) ? "uploads/" . $row['vehicle_image'] : null;
        $vehicles[] = $row;
    }
    echo json_encode(['success' => true, 'vehicles' => $vehicles]);
} else {
    echo json_encode(['success' => false, 'message' => "No pending vehicles."]);
}

$conn->close();
?>