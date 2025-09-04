<?php
header('Content-Type: application/json');
require './db.php'; // Change to your connection file

$page   = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit  = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$date   = isset($_GET['date']) ? trim($_GET['date']) : '';

$offset = ($page - 1) * $limit;

// Build WHERE conditions
$whereClauses = [];
$params = [];
$types = '';

if (!empty($search)) {
    // Search in multiple columns
    $whereClauses[] = "(
        owner_name LIKE ? OR 
        purpose LIKE ? OR 
        plate_number LIKE ? OR 
        vehicle_type LIKE ? OR 
        access_by LIKE ? OR
        rfid_tag LIKE ?
    )";
    $searchParam = "%{$search}%";
    $params = array_merge($params, array_fill(0, 6, $searchParam));
    $types .= str_repeat('s', 6);
}

if (!empty($date)) {
    $whereClauses[] = "DATE(time_in) = ?";
    $params[] = $date;
    $types .= 's';
}

$whereSQL = '';
if (!empty($whereClauses)) {
    $whereSQL = "WHERE " . implode(" AND ", $whereClauses);
}

// Fetch logs with pagination
$sql = "SELECT * FROM access_logs 
        $whereSQL 
        ORDER BY time_in DESC 
        LIMIT ?, ?";
$params[] = $offset;
$params[] = $limit;
$types .= 'ii';

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$logs = [];
while ($row = $result->fetch_assoc()) {
    $logs[] = $row;
}

// Count total for pagination
$countSql = "SELECT COUNT(*) as total FROM access_logs $whereSQL";
$stmt2 = $conn->prepare($countSql);

if (!empty($params)) {
    // Remove last 2 params (offset, limit) for count query
    $countParams = array_slice($params, 0, count($params) - 2);
    $countTypes  = substr($types, 0, -2);
    if (!empty($countParams)) {
        $stmt2->bind_param($countTypes, ...$countParams);
    }
}

$stmt2->execute();
$countResult = $stmt2->get_result();
$total = $countResult->fetch_assoc()['total'] ?? 0;

echo json_encode([
    'success' => true,
    'logs'    => $logs,
    'total'   => $total
]);
?>
