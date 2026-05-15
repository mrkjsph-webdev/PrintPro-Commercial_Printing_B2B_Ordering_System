<?php
session_start();
require "db.php";

header("Content-Type: application/json");

error_reporting(E_ALL);
ini_set('display_errors', 1);

// CHECK LOGIN
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "User not logged in."
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];

// GET JSON INPUT
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['total_amount'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Total amount is required."
    ]);
    exit;
}

$total_amount = floatval($data['total_amount']);
$payment_method = isset($data['payment_method']) ? $data['payment_method'] : "cash";

// DEFAULT VALUES
$order_status = "pending";
$payment_status = "unpaid";

// INSERT INTO ORDERS
$insert = "INSERT INTO orders 
(user_id, order_date, order_status, total_amount, payment_status)
VALUES (?, NOW(), ?, ?, ?)";

$stmt = $conn->prepare($insert);
$stmt->bind_param("isds", $user_id, $order_status, $total_amount, $payment_status);

if (!$stmt->execute()) {
    echo json_encode([
        "status" => "error",
        "message" => "Order creation failed.",
        "error" => $stmt->error
    ]);
    exit;
}

$order_id = $stmt->insert_id;

// INSERT INTO ORDER SUMMARY
$quantity = isset($data['quantity']) ? intval($data['quantity']) : 0;
$subtotal = isset($data['subtotal']) ? floatval($data['subtotal']) : 0;
$discount = isset($data['discount']) ? floatval($data['discount']) : 0;
$grand_total = $total_amount;

$summary_insert = "INSERT INTO order_summary 
(order_id, quantity, subtotal, discount, grand_total)
VALUES (?, ?, ?, ?, ?)";

$summary_stmt = $conn->prepare($summary_insert);
$summary_stmt->bind_param("iiddi", $order_id, $quantity, $subtotal, $discount, $grand_total);

$summary_stmt->execute();


// INSERT INTO ORDER DETAILS
$detail_quantity = $quantity;
$detail_subtotal = $subtotal;
$detail_total = $grand_total;

if (isset($data['cart_items']) && is_array($data['cart_items'])) {

    $detail_insert = "INSERT INTO order_details
    (order_id, cart_item_id, quantity, subtotal, total_price)
    VALUES (?, ?, ?, ?, ?)";

    $detail_stmt = $conn->prepare($detail_insert);

    foreach ($data['cart_items'] as $item) {

        $cart_item_id = intval($item['cart_item_id']);

        $detail_stmt->bind_param(
            "iiidd",
            $order_id,
            $cart_item_id,
            $detail_quantity,
            $detail_subtotal,
            $detail_total
        );

        $detail_stmt->execute();
    }
}


// INSERT INTO CHECKOUT
$checkout_insert = "INSERT INTO order_check_out 
(order_id, payment_method, checkout_date)
VALUES (?, ?, NOW())";

$checkout_stmt = $conn->prepare($checkout_insert);
$checkout_stmt->bind_param("is", $order_id, $payment_method);

$checkout_stmt->execute();

// RETURN SUCCESS RESPONSE
echo json_encode([
    "status" => "success",
    "message" => "Order placed successfully.",
    "order_id" => $order_id,
    "total_amount" => $total_amount
]);
?>