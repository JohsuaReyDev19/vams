<?php
$host = "localhost";
$user = "root";   
$pass = "";        // leave blank if no password
$db   = "my-rfid";

// Backup filename
$backupFile = $db . "_backup_" . date("Y-m-d_H-i-s") . ".sql";

// ✅ Full path to mysqldump (adjust if needed)
$mysqldump = "C:/xampp/mysql/bin/mysqldump.exe";  // Windows (XAMPP)
// $mysqldump = "/usr/bin/mysqldump";             // Linux/Mac

// Build command safely
if ($pass === "") {
    $command = "\"$mysqldump\" -h $host -u $user $db";
} else {
    $command = "\"$mysqldump\" -h $host -u $user -p$pass $db";
}

// Send headers to force download
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $backupFile . '"');
header('Pragma: no-cache');
header('Expires: 0');

// Run and stream output
passthru($command, $result);

if ($result !== 0) {
    echo "-- ❌ Backup failed. Check path or credentials.\n";
}
exit;
