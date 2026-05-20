<?php
session_start();
require "db.php";

$user_id = $_SESSION['user_id'] ?? null;
$order_id = $_GET['order_id'] ?? null;

if (!$user_id || !$order_id) {
    die("Invalid access.");
}

/* =========================
   ORDER HEADER (FOR UI)
========================= */
$sql = "
SELECT 
    o.order_id,
    o.order_date,
    o.order_status,
    o.total_amount,
    o.payment_status,

    CONCAT(u.first_name, ' ', u.middle_initial, ' ', u.last_name) AS fullname,
    u.email,
    u.contact_number AS contact

FROM orders o
JOIN users u ON o.user_id = u.user_id
WHERE o.order_id = ? AND o.user_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();

$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    die("Order not found.");
}

/* =========================
   ORDER ITEMS
========================= */
$sqlItems = "
SELECT 
    od.order_detail_id,
    od.quantity,
    od.subtotal,
    od.total_price,

    p.product_name,

    c.paper_size,
    c.gsm,
    c.paper_texture,
    c.copies,

    f.image1,
    f.image2

FROM order_details od

JOIN shopping_cart_items sci 
    ON sci.cart_item_id = od.cart_item_id

JOIN products p 
    ON p.product_id = sci.product_id

LEFT JOIN customization c 
    ON c.customization_id = sci.customization_id

LEFT JOIN file_upload f 
    ON f.file_id = c.file_id

WHERE od.order_id = ?
";

$stmt2 = $conn->prepare($sqlItems);
$stmt2->bind_param("i", $order_id);
$stmt2->execute();

$items = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);

$firstItem = $items[0] ?? [];

/* helper */
function e($str) {
    return htmlspecialchars($str ?? '');
}
?>