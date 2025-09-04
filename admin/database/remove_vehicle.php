<?php
header("Content-Type: application/json");
require './db.php';

$response = ["success" => false, "message" => ""];

try {
    // ✅ Get JSON input
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['id'])) {
        echo json_encode(["success" => false, "message" => "Missing vehicle ID."]);
        exit;
    }

    $id = intval($data['id']);

    // ✅ Delete vehicle
    $stmt = $conn->prepare("DELETE FROM vehicles WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $response["success"] = true;
        $response["message"] = "Vehicle deleted successfully.";
    } else {
        $response["message"] = "Failed to delete vehicle.";
    }

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    $response["message"] = $e->getMessage();
}

echo json_encode($response);
