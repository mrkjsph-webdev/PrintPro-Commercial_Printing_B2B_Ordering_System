<?php

session_start();
require "db.php";

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? 0;

if (!$user_id) {

    echo json_encode([
        "success" => false
    ]);

    exit;
}

/*
GET ALL ACTIVE ORDERS
*/
$query = "
SELECT order_id, order_status
FROM orders
WHERE user_id = ?
ORDER BY order_id DESC
";

$stmt = $conn->prepare($query);

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();

$orders = [];

while($row = $result->fetch_assoc()) {

    $orders[] = $row;
}

echo json_encode([
    "success" => true,
    "orders" => $orders
]);