<?php
require_once "db.php";

header("Content-Type: application/json");

$customization_id = $_GET['id'] ?? null;

if (!$customization_id) {
    echo json_encode(["error" => "No ID"]);
    exit;
}

$sql = "SELECT * FROM customization WHERE customization_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customization_id);
$stmt->execute();

$result = $stmt->get_result();

$data = $result->fetch_assoc();

echo json_encode($data);
?>