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

/* ---------------- POST DATA ---------------- */

$product_id = $_POST['product_id'] ?? null;
$unit_price = $_POST['unit_price'] ?? null;
$file_id    = $_POST['file_id'] ?? null;

$data = json_decode(file_get_contents("php://input"), true);
$customization_id = $data['customization_id'] ?? $_POST['customization_id'] ?? null;

if (!$product_id || !$unit_price || !$file_id) {

    echo json_encode([
        "status" => "error",
        "message" => "Missing required fields."
    ]);
    exit;
}

/* ---------------- GET ACTIVE CART ---------------- */

$sql = "SELECT cart_id 
        FROM shopping_cart 
        WHERE user_id = ? 
        AND cart_status = 'active'
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {

    echo json_encode([
        "status" => "error",
        "message" => "No active cart found."
    ]);
    exit;
}

$row = $result->fetch_assoc();
$cart_id = $row['cart_id'];
/* ---------------- GET CUSTOMIZATION DETAILS ---------------- */

$getCustomization = mysqli_query($conn, "

SELECT
paper_size,
gsm,
paper_texture

FROM customization

WHERE customization_id = '$customization_id'

");

$customData =
mysqli_fetch_assoc($getCustomization);

$paper_size =
$customData['paper_size'];

$paper_gsm =
$customData['gsm'];

$paper_texture =
$customData['paper_texture'];

/* ---------------- INSERT ITEM ---------------- */

$insert = "INSERT INTO shopping_cart_items
(
cart_id,
product_id,
customization_id,
unit_price,
added_at,
paper_size,
paper_gsm,
paper_texture
)

VALUES
(
?,
?,
?,
?,
NOW(),
?,
?,
?
)";

$stmt2 = $conn->prepare($insert);

$stmt2->bind_param(
    "iiidsss",
    $cart_id,
    $product_id,
    $customization_id,
    $unit_price,
    $paper_size,
    $paper_gsm,
    $paper_texture
);

if ($stmt2->execute()) {

    /* update cart timestamp */
    $update = "UPDATE shopping_cart
               SET updated_at = NOW()
               WHERE cart_id = ?";

    $stmt3 = $conn->prepare($update);
    $stmt3->bind_param("i", $cart_id);
    $stmt3->execute();

    echo json_encode([
        "status" => "success",
        "message" => "Item added successfully.",
        "cart_id" => $cart_id,
        "customization_id" => $customization_id
    ]);

} else {

    echo json_encode([
        "status" => "error",
        "message" => "Insert failed: " . $stmt2->error
    ]);
}
?>