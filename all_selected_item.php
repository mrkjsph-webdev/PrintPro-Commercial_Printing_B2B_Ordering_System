<?php
require_once "db.php";
session_start();

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success"=>false]);
    exit;
}

$user_id = $_SESSION['user_id'];

$data = json_decode(file_get_contents("php://input"), true);

$is_selected = intval($data['is_selected']);

// get user's cart
$stmt = $conn->prepare("
    SELECT cart_id
    FROM shopping_cart
    WHERE user_id = ?
");

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$row = $result->fetch_assoc()) {
    echo json_encode(["success"=>false]);
    exit;
}

$cart_id = $row['cart_id'];

// update ALL items
$update = $conn->prepare("
    UPDATE shopping_cart_items
    SET is_selected = ?
    WHERE cart_id = ?
");

$update->bind_param("ii", $is_selected, $cart_id);
$update->execute();

echo json_encode(["success"=>true]);
?>