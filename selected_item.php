<?php
require_once "db.php";
session_start();

header("Content-Type: application/json");

/* ---------- CHECK LOGIN ---------- */
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "User not logged in"
    ]);
    exit;
}

/* ---------- GET JSON DATA ---------- */
$data = json_decode(file_get_contents("php://input"), true);

$cart_item_id = $data['cart_item_id'] ?? null;
$is_selected  = $data['is_selected'] ?? 0;

if (!$cart_item_id) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid cart item"
    ]);
    exit;
}

/* ---------- UPDATE DATABASE ---------- */
$stmt = $conn->prepare("
    UPDATE shopping_cart_items
    SET is_selected = ?
    WHERE cart_item_id = ?
");

$stmt->bind_param("ii", $is_selected, $cart_item_id);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Update failed"
    ]);
}

$stmt->close();
$conn->close();
?>