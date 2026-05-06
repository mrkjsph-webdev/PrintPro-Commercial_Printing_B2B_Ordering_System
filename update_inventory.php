<?php
include 'db.php';
header('Content-Type: application/json');


$product_id = $_REQUEST['product_id'] ?? '';
$added_stock = $_REQUEST['added_stock'] ?? 0; 

if(empty($product_id)) {
    echo json_encode(["status" => "error", "message" => "Product ID is required"]);
    exit;
}

// ito matik na sya mag uupdate kung available or hindi
$sql = "UPDATE products 
        SET stock_quantity = stock_quantity + $added_stock, 
            product_status = 'available' 
        WHERE product_id = '$product_id'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["status" => "success", "message" => "Inventory updated successfully!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Database error: " . $conn->error]);
}

$conn->close();
?>