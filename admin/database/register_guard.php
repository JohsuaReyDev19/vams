<?php
header('Content-Type: application/json');
$mysqli = new mysqli("localhost", "root", "", "my-rfid");

if ($mysqli->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit();
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$full_name = $_POST['full_name'] ?? '';
$contact_number = $_POST['contact_number'] ?? '';
$address = $_POST['address'] ?? '';
$email = $_POST['email'] ?? '';
$role = $_POST['role'] ?? '';

if (!$username || !$password || !$role) {
    echo json_encode(['success' => false, 'message' => 'Required fields missing.']);
    exit();
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $mysqli->prepare("INSERT INTO admin_accounts (username, password_hash, full_name, contact_number, address, email, `Rule`) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $username, $password_hash, $full_name, $contact_number, $address, $email, $role);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Username already exists or database error.']);
}

$stmt->close();
$mysqli->close();
