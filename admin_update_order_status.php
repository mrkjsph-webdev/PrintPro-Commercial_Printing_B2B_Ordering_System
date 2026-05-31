<?php

require "db.php";

$order_id = $_POST['order_id'];
$status = $_POST['status'];

$payment_status = "unpaid";

if ($status === "completed") {
    $payment_status = "paid";
}
elseif ($status === "cancelled") {
    $payment_status = "refunded";
}

$query = "
UPDATE orders
SET order_status = ?, payment_status = ?
WHERE order_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("ssi", $status, $payment_status, $order_id);

if ($stmt->execute()) {

    echo json_encode([
        "success" => true,
        "payment_status" => $payment_status
    ]);

} else {

    echo json_encode([
        "success" => false
    ]);
}
