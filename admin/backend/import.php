<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "my-rfid";

// Path to mysql.exe (Windows/XAMPP) or mysql (Linux/Mac)
$mysql = "C:/xampp/mysql/bin/mysql.exe";
// $mysql = "/usr/bin/mysql"; 

if ($_FILES["sqlfile"]["error"] == UPLOAD_ERR_OK) {
    $sqlFile = $_FILES["sqlfile"]["tmp_name"];

    // Command to import
    if ($pass === "") {
        $command = "\"$mysql\" -h $host -u $user $db < \"$sqlFile\"";
    } else {
        $command = "\"$mysql\" -h $host -u $user -p$pass $db < \"$sqlFile\"";
    }

    // Execute
    $output = null;
    $result = null;
    exec($command, $output, $result);

    if ($result === 0) {
        echo "Database imported successfully!";
    } else {
        echo "❌ Import failed. Check SQL file or credentials.";
    }
} else {
    echo "❌ File upload failed.";
}
