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
$delivery_method = $data['delivery_method'];
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
    (order_id, cart_item_id, quantity, unit_price, subtotal, total_price)
    VALUES (?, ?, ?, ?, ?, ?)";

    $detail_stmt = $conn->prepare($detail_insert);

    foreach ($data['cart_items'] as $item) {

        $cart_item_id = intval($item['cart_item_id']);

        // GET CART CUSTOMIZATION DETAILS
        $get_cart = mysqli_query($conn, "

        SELECT
        paper_size,
        paper_gsm,
        paper_texture

        FROM shopping_cart_items

        WHERE cart_item_id = '$cart_item_id'

        ");

        $cart_data = mysqli_fetch_assoc($get_cart);

        $paper_size =
        $cart_data['paper_size'];

        $paper_gsm =
        $cart_data['paper_gsm'];

        $paper_texture =
        $cart_data['paper_texture'];

        // ORDER DETAILS
        $copies =
        intval($item['quantity']);

        $unit_price =
        floatval($item['unit_price']);

        $subtotal =
        floatval($item['subtotal']);

        $total_price =
        floatval($item['total_price']);

        // INSERT ORDER DETAILS
        $detail_stmt->bind_param(
            "iiiddd",
            $order_id,
            $cart_item_id,
            $copies,
            $unit_price,
            $subtotal,
            $total_price
        );

        $detail_stmt->execute();

        // DEDUCT PAPER SIZE STOCK
        mysqli_query($conn, "

        UPDATE paper_size_inventory

        SET stock_quantity =
        GREATEST(stock_quantity - $copies, 0)

        WHERE paper_size = '$paper_size'

        ");

        // DEDUCT GSM STOCK
        mysqli_query($conn, "

        UPDATE paper_gsm_inventory

        SET stock_quantity =
        GREATEST(stock_quantity - $copies, 0)

        WHERE paper_gsm = '$paper_gsm'

        ");

        // DEDUCT TEXTURE STOCK
        mysqli_query($conn, "

        UPDATE paper_texture_inventory

        SET stock_quantity =
        GREATEST(stock_quantity - $copies, 0)

        WHERE paper_texture = '$paper_texture'

        ");

        // AUTO UPDATE AVAILABILITY
        mysqli_query($conn, "

        UPDATE paper_size_inventory

        SET is_available =

        CASE
            WHEN stock_quantity <= 0 THEN 0
            ELSE 1
        END

        ");

        mysqli_query($conn, "

        UPDATE paper_gsm_inventory

        SET is_available =

        CASE
            WHEN stock_quantity <= 0 THEN 0
            ELSE 1
        END

        ");

        mysqli_query($conn, "

        UPDATE paper_texture_inventory

        SET is_available =

        CASE
            WHEN stock_quantity <= 0 THEN 0
            ELSE 1
        END

        ");
    }
}

// INSERT INTO CHECKOUT
$checkout_insert = "INSERT INTO order_check_out 
(order_id, payment_method, delivery_method, checkout_date)
VALUES (?, ?, ?, NOW())";

$checkout_stmt = $conn->prepare($checkout_insert);
$checkout_stmt->bind_param(
    "iss",
    $order_id,
    $payment_method,
    $delivery_method
);

$checkout_stmt->execute();

$update_sql = "
UPDATE shopping_cart_items
SET cart_status = 'checked_out'
WHERE cart_item_id = ?
";

if (isset($data['cart_items']) && is_array($data['cart_items'])) {

    $update_stmt = $conn->prepare("
        UPDATE shopping_cart_items
        SET cart_status = 'checked_out'
        WHERE cart_item_id = ?
    ");

    foreach ($data['cart_items'] as $item) {

        $cart_item_id = intval($item['cart_item_id']);

        $update_stmt->bind_param("i", $cart_item_id);
        $update_stmt->execute();
    }
}

// RETURN SUCCESS RESPONSE
echo json_encode([
    "status" => "success",
    "message" => "Order placed successfully.",
    "order_id" => $order_id,
    "total_amount" => $total_amount
]);
?>