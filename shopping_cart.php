<?php
session_start();
require "db.php";

header("Content-Type: application/json");

error_reporting(E_ALL);
ini_set('display_errors', 1);

/* ---------------- LOGIN CHECK ---------------- */
if (!isset($_SESSION['user_id'])) {

    echo json_encode([
        "status" => "error",
        "message" => "User not logged in."
    ]);

    exit;
}

$user_id = $_SESSION['user_id'];

/* ---------------- CHECK EXISTING ACTIVE CART ---------------- */
$check = "SELECT cart_id 
          FROM shopping_cart 
          WHERE user_id = ? 
          AND cart_status = 'active'
          LIMIT 1";

$stmt = $conn->prepare($check);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

/* ---------------- IF EXISTS, RETURN IT ---------------- */
if ($result->num_rows > 0) {

    $row = $result->fetch_assoc();

    echo json_encode([
        "status" => "success",
        "message" => "Existing active cart found.",
        "cart_id" => $row['cart_id']
    ]);

    exit;
}

/* ---------------- OTHERWISE CREATE NEW CART ---------------- */
$sql = "INSERT INTO shopping_cart
(user_id, created_at, updated_at, cart_status)
VALUES (?, NOW(), NOW(), 'active')";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {

    echo json_encode([
        "status" => "success",
        "message" => "New cart created.",
        "cart_id" => $stmt->insert_id
    ]);

} else {

    echo json_encode([
        "status" => "error",
        "message" => $stmt->error
    ]);
}
?>