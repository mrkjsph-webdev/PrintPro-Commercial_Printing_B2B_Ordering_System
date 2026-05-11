<?php
session_start();
require_once "db.php";

header("Content-Type: application/json");

try {

    /* ---------- CHECK LOGIN ---------- */

    if (!isset($_SESSION['user_id'])) {
        throw new Exception("User not logged in.");
    }

    $user_id = $_SESSION['user_id'];

    /* ---------- GET ACTIVE CART ---------- */

    $cartQuery = "
        SELECT cart_id
        FROM shopping_cart
        WHERE user_id = ?
        AND cart_status = 'active'
        LIMIT 1
    ";

    $stmtCart = $conn->prepare($cartQuery);

    if (!$stmtCart) {
        throw new Exception($conn->error);
    }

    $stmtCart->bind_param("i", $user_id);
    $stmtCart->execute();

    $cartResult = $stmtCart->get_result();

    if ($cartResult->num_rows === 0) {
        echo json_encode([]);
        exit;
    }

    $cart = $cartResult->fetch_assoc();
    $cart_id = $cart['cart_id'];

    /* ---------- GET CART ITEMS (UPDATED) ---------- */

    $sql = "
        SELECT 
            sci.cart_item_id,
            sci.product_id,
            sci.customization_id,
            sci.unit_price,
            p.product_name
        FROM shopping_cart_items sci
        INNER JOIN products p
            ON sci.product_id = p.product_id
        WHERE sci.cart_id = ?
        ORDER BY sci.added_at DESC
    ";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception($conn->error);
    }

    $stmt->bind_param("i", $cart_id);
    $stmt->execute();

    $result = $stmt->get_result();

    $cart_items = [];

    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
    }

    echo json_encode($cart_items);

} catch (Exception $e) {

    echo json_encode([
        "error" => $e->getMessage()
    ]);
}
?>