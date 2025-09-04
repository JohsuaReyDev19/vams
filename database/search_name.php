<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "my-rfid");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = isset($_POST['query']) ? $conn->real_escape_string($_POST['query']) : '';

if ($query != '') {
    $sql = "
        SELECT r.id AS owner_id, s.student_name AS name, s.purpose 
        FROM student_vehicles s
        INNER JOIN registered_vehicles r ON s.vehicle_id = r.vehicle_id
        WHERE s.student_name LIKE '%$query%'
        
        UNION
        
        SELECT r.id AS owner_id, o.ojt_name AS name, o.purpose 
        FROM ojt_vehicles o
        INNER JOIN registered_vehicles r ON o.vehicle_id = r.vehicle_id
        WHERE o.ojt_name LIKE '%$query%'
        
        UNION
        
        SELECT r.id AS owner_id, f.faculty_name AS name, f.purpose 
        FROM faculty_vehicles f
        INNER JOIN registered_vehicles r ON f.vehicle_id = r.vehicle_id
        WHERE f.faculty_name LIKE '%$query%'
        
        LIMIT 10
    ";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="search-item px-4 py-2 hover:bg-gray-100 cursor-pointer"
                  data-id="'.$row['owner_id'].'"
                  data-name="'.htmlspecialchars($row['name']).'"
                  data-purpose="'.htmlspecialchars($row['purpose']).'">'
                  .htmlspecialchars($row['name']).'
                  </div>';
        }
    } else {
        echo '<div class="px-4 py-2 text-gray-500">No results found</div>';
    }
}
?>
