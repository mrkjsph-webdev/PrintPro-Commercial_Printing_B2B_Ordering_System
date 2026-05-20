<?php

require "db.php";

$order_id = $_GET['order_id'];

$query = "
SELECT order_status
FROM orders
WHERE order_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();

$result = $stmt->get_result();

$order = $result->fetch_assoc();

echo ucfirst($order['order_status']);