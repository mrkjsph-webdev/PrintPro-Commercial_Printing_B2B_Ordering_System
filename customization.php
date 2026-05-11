<?php
session_start();
require "db.php";

header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
    exit;
}

$file_id = intval($_POST['file_id'] ?? 0);
$paper_size = trim($_POST['paper_size'] ?? '');
$gsm = trim($_POST['gsm'] ?? '');
$paper_texture = trim($_POST['paper_texture'] ?? '');
$copies = intval($_POST['copies'] ?? 1);

$price = floatval($_POST['price'] ?? 0);

if ($file_id <= 0 || !$paper_size || !$gsm || !$paper_texture) {
    echo json_encode(["status" => "error", "message" => "Missing fields"]);
    exit;
}

$stmt = $conn->prepare("
    INSERT INTO customization
    (file_id, paper_size, gsm, paper_texture, copies, price)
    VALUES (?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "isssid",
    $file_id,
    $paper_size,
    $gsm,
    $paper_texture,
    $copies,
    $price
);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "customization_id" => $stmt->insert_id
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>