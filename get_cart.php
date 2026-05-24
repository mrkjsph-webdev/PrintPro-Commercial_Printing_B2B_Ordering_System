<?php
session_start();
require_once "db.php";

header("Content-Type: application/json");

try {

    if (!isset($_SESSION['user_id'])) {
        throw new Exception("User not logged in.");
    }

    $user_id = $_SESSION['user_id'];

    // Get the active cart for the user
    $cartQuery = "
        SELECT cart_id
        FROM shopping_cart
        WHERE user_id = ?
        AND cart_status = 'active'
        LIMIT 1
    ";

    $stmtCart = $conn->prepare($cartQuery);
    $stmtCart->bind_param("i", $user_id);
    $stmtCart->execute();

    $cartResult = $stmtCart->get_result();

    if ($cartResult->num_rows === 0) {
        echo json_encode([]);
        exit;
    }

    $cart = $cartResult->fetch_assoc();
    $cart_id = $cart['cart_id'];

    // Optimized query to get all cart items with product and image info in one go
$sql = "
SELECT 
   sci.cart_item_id,
   sci.product_id,
   sci.customization_id,
   sci.unit_price,

   c.copies,

   p.product_name,
   fu.image1 AS image
   
FROM shopping_cart_items sci

LEFT JOIN products p
    ON sci.product_id = p.product_id

LEFT JOIN customization c
    ON sci.customization_id = c.customization_id

LEFT JOIN file_upload fu
    ON c.file_id = fu.file_id

WHERE sci.cart_id = ?
AND sci.cart_status = 'active'
ORDER BY sci.added_at DESC
";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();

    $result = $stmt->get_result();

    $cart_items = [];

    while ($row = $result->fetch_assoc()) {

        // Handle image path - if no image, use default; otherwise ensure path is correct
        $image = $row['image'];

        if (!$image) {
            $image = "image_resources/no-image.png";
        } else {
            
            $image = str_replace("\\", "/", $image);
            $image = ltrim($image, "/");
        }

        $cart_items[] = [
            "cart_item_id"     => $row["cart_item_id"],
            "product_id"       => $row["product_id"],
            "customization_id" => $row["customization_id"],
            "product_name"     => $row["product_name"],
            "unit_price"       => $row["unit_price"],
            "copies"           => $row["copies"],
            "image"            => $image
        ];
    }

    echo json_encode($cart_items);

} catch (Exception $e) {

    echo json_encode([
        "error" => $e->getMessage()
    ]);
}
?>