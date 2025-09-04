<?php
$host = "localhost";
$user = "root";
$pass = ""; // or your MySQL password
$db = "my-rfid";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = intval($_POST['id']);
$update = "UPDATE access_logs SET time_out = NOW() WHERE id = $id";

if (mysqli_query($conn, $update)) {
  echo 'success';
} else {
  echo 'error';
}
