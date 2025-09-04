<?php
include 'db.php';

$limit = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$totalRecords = $conn->query("SELECT COUNT(*) as count FROM my_table")->fetch_assoc()['count'];
$totalPages = ceil($totalRecords / $limit);

$data = $conn->query("SELECT * FROM my_table LIMIT $offset, $limit");

$rows = '';
while ($row = $data->fetch_assoc()) {
    $rows .= "<tr>
                <td>{$row['id']}</td>
                <td>{$row['name']}</td>
              </tr>";
}

echo json_encode([
    'data' => $rows,
    'currentPage' => $page,
    'totalPages' => $totalPages
]);
