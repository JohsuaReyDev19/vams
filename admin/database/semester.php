<?php
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "my-rfid";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "DB connection failed"]);
    exit;
}

function table_exists(mysqli $conn, string $table): bool {
    $table = $conn->real_escape_string($table);
    $sql = "SHOW TABLES LIKE '$table'";
    $result = $conn->query($sql);
    return $result && $result->num_rows > 0;
}

$action = $_POST['action'] ?? '';

try {
    if ($action === "start") {
        $start_date = $_POST['start_date'] ?? '';
        $end_date   = $_POST['end_date'] ?? '';

        if (!$start_date || !$end_date) {
            echo json_encode(["success" => false, "message" => "Missing dates"]);
            exit;
        }

        $conn->begin_transaction();

        // Upsert semester dates (id=1)
        $stmt = $conn->prepare("
            INSERT INTO semester_control (id, start_date, end_date)
            VALUES (1, ?, ?)
            ON DUPLICATE KEY UPDATE start_date=VALUES(start_date), end_date=VALUES(end_date)
        ");
        $stmt->bind_param("ss", $start_date, $end_date);
        if (!$stmt->execute()) { throw new Exception("Failed to save semester dates"); }
        $stmt->close();

        $changes = [];

        // Visitor -> Student in vehicles
        if (table_exists($conn, "vehicles")) {
            if (!$conn->query("UPDATE vehicles SET purpose = 'Student' WHERE purpose = 'Visitor'")) {
                throw new Exception("Failed to update vehicles (start)");
            }
            $changes['vehicles_updated'] = $conn->affected_rows;
        }

        // Visitor -> Student in student_vehicles
        if (table_exists($conn, "student_vehicles")) {
            if (!$conn->query("UPDATE student_vehicles SET purpose = 'Student' WHERE purpose = 'Visitor'")) {
                throw new Exception("Failed to update student_vehicles (start)");
            }
            $changes['student_vehicles_updated'] = $conn->affected_rows;
        }

        $conn->commit();

        echo json_encode([
            "success" => true,
            "message" => "Semester started. Dates saved. Visitor → Student applied.",
            "changes" => $changes
        ]);
    }
    elseif ($action === "end") {
        $conn->begin_transaction();

        $changes = [];

        // Student -> Visitor in vehicles (do NOT touch OJT/Faculty/etc.)
        if (table_exists($conn, "vehicles")) {
            if (!$conn->query("UPDATE vehicles SET purpose = 'Visitor' WHERE purpose = 'Student'")) {
                throw new Exception("Failed to update vehicles (end)");
            }
            $changes['vehicles_updated'] = $conn->affected_rows;
        }

        // Student -> Visitor in student_vehicles
        if (table_exists($conn, "student_vehicles")) {
            if (!$conn->query("UPDATE student_vehicles SET purpose = 'Visitor' WHERE purpose = 'Student'")) {
                throw new Exception("Failed to update student_vehicles (end)");
            }
            $changes['student_vehicles_updated'] = $conn->affected_rows;
        }

        // Clear dates (NULL) so UI won’t show stale dates
        if (!$conn->query("UPDATE semester_control SET start_date = NULL, end_date = NULL WHERE id = 1")) {
            throw new Exception("Failed to clear semester dates");
        }

        $conn->commit();

        echo json_encode([
            "success" => true,
            "message" => "Semester ended. Student → Visitor applied. Dates cleared.",
            "changes" => $changes
        ]);
    }
    elseif ($action === "get_dates") {
        $result = $conn->query("SELECT start_date, end_date FROM semester_control WHERE id=1 LIMIT 1");
        if ($row = $result->fetch_assoc()) {
            echo json_encode(["success" => true, "data" => $row]);
        } else {
            echo json_encode(["success" => true, "data" => ["start_date" => null, "end_date" => null]]);
        }
    }
    else {
        echo json_encode(["success" => false, "message" => "Invalid request"]);
    }
} catch (Exception $e) {
    @$conn->rollback();
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}

$conn->close();
