<?php
session_start();
require "db.php";

header("Content-Type: application/json");

$order_id = $_POST['order_id'] ?? null;
$amount_paid = $_POST['amount_paid'] ?? null;
$payment_status = $_POST['payment_status'] ?? 'pending';

if (!$order_id || !$amount_paid) {
    echo json_encode(["status" => "error", "message" => "Missing data"]);
    exit;
}

/* -------------------------
   GENERATE RECEIPT NUMBER
--------------------------*/
$receipt_number = "RCP-" . date("Ymd") . "-" . rand(10000, 99999);

$payment_date = date("Y-m-d");

/* -------------------------
   INSERT RECEIPT
--------------------------*/
$sql = "
INSERT INTO receipt 
(receipt_number, order_id, payment_date, amount_paid, payment_status)
VALUES (?, ?, ?, ?, ?)
";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "sisss",
    $receipt_number,
    $order_id,
    $payment_date,
    $amount_paid,
    $payment_status
);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "receipt_number" => $receipt_number
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => $stmt->error
    ]);
}
?>