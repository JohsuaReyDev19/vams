<?php
// fetch_admin_accounts.php
// DB credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my-rfid";

// DB connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

// Fetch only Security Personel accounts
$query = "SELECT * FROM admin_accounts 
          WHERE Rule = 'Security Personel'
          ORDER BY created_at DESC";

$result = $conn->query($query);

$data = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

echo json_encode($data);
$conn->close();
?>
