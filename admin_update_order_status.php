<?php

require "db.php";

$order_id = $_POST['order_id'];
$status = $_POST['status'];

$query = "
UPDATE orders
SET order_status = ?
WHERE order_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("si", $status, $order_id);

if ($stmt->execute()) {

    echo json_encode([
        "success" => true
    ]);

} else {

    echo json_encode([
        "success" => false
    ]);

}