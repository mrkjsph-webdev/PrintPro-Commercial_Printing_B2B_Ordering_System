<?php
session_start();
require "db.php";

header("Content-Type: application/json");

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {

    echo json_encode([
        "status" => "error",
        "message" => "User not logged in."
    ]);

    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "INSERT INTO shopping_cart
(user_id, created_at, updated_at, cart_status)
VALUES (?, NOW(), NOW(), 'active')";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {

    echo json_encode([
        "status" => "success",
        "cart_id" => $stmt->insert_id
    ]);

} else {

    echo json_encode([
        "status" => "error",
        "message" => $stmt->error
    ]);
}
?>