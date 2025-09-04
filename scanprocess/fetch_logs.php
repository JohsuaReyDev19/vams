<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my-rfid";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]));
}

// Parameters from frontend
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$date = isset($_GET['date']) && !empty($_GET['date']) ? $conn->real_escape_string($_GET['date']) : date('Y-m-d'); // default to today
// Build filter condition
$where = "WHERE 1";
if (!empty($search)) {
    $where .= " AND LOWER(plate_number) LIKE '%" . strtolower($search) . "%'";
}
$where .= " AND DATE(time_in) = '$date'"; // Always filter by date (either from frontend or today)

// Total count (for pagination)
$countQuery = "SELECT COUNT(*) as total FROM access_logs $where";
$countResult = $conn->query($countQuery);
$totalRows = $countResult->fetch_assoc()['total'];

// Fetch paginated data
$sql = "SELECT * FROM access_logs $where ORDER BY id DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

$logs = [];
while ($row = $result->fetch_assoc()) {
    $logs[] = $row;
}

echo json_encode([
    'success' => true,
    'logs' => $logs,
    'total' => $totalRows
]);

$conn->close();
?>
