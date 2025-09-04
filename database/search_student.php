<?php
header('Content-Type: application/json');
require './db.php'; // adjust path to your DB config

$code = isset($_POST['code']) ? trim($_POST['code']) : '';

if ($code !== '') {
    $stmt = $conn->prepare("SELECT * FROM student_list WHERE code = ?");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode([
            'status' => 'success',
            'student' => $row
        ]);
    } else {
        echo json_encode(['status' => 'not_found']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'No code provided']);
}
$conn->close();
?>
