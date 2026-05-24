<?php
require_once "db.php";
session_start();

header("Content-Type: application/json");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "User not logged in"
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Get the cart item ID from the request body
$data = json_decode(file_get_contents("php://input"), true);
$cart_item_id = $data['cart_item_id'] ?? null;

if (!$cart_item_id) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid cart item ID"
    ]);
    exit;
}

// Find the active cart for the user
$stmt = $conn->prepare("
    SELECT cart_id 
    FROM shopping_cart 
    WHERE user_id = ? 
    AND cart_status = 'active'
");

if (!$stmt) {
    echo json_encode([
        "success" => false,
        "message" => "Prepare failed: " . $conn->error
    ]);
    exit;
}

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$cart = $result->fetch_assoc();
$stmt->close();

if (!$cart) {
    echo json_encode([
        "success" => false,
        "message" => "No active cart found"
    ]);
    exit;
}

$cart_id = $cart['cart_id'];

// Delete the cart item, ensuring it belongs to the user's active cart
$stmt = $conn->prepare("
    DELETE FROM shopping_cart_items 
    WHERE cart_item_id = ? 
    AND cart_id = ?
");

if (!$stmt) {
    echo json_encode([
        "success" => false,
        "message" => "Prepare failed: " . $conn->error
    ]);
    exit;
}

$stmt->bind_param("ii", $cart_item_id, $cart_id);

// Execute the delete statement and check if it was successful
if ($stmt->execute()) {

    if ($stmt->affected_rows > 0) {
        echo json_encode([
            "success" => true,
            "message" => "Item deleted successfully"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Item not found in your cart"
        ]);
    }

} else {
    echo json_encode([
        "success" => false,
        "message" => "Execute failed: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();