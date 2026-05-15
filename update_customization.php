    <?php
    require_once "db.php";
    session_start();

    header("Content-Type: application/json");

    $data = json_decode(file_get_contents("php://input"), true);

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        echo json_encode([
            "success" => false,
            "message" => "User not logged in"
        ]);
        exit;
    }

    // Extract and validate input data
    $customization_id = $data['customization_id'] ?? null;
    $paper_size       = $data['paper_size'] ?? null;
    $gsm              = isset($data['gsm']) ? (int)$data['gsm'] : 0;
    $paper_texture    = $data['paper_texture'] ?? null;
    $copies           = isset($data['copies']) ? (int)$data['copies'] : 1;
    $price            = isset($data['total_price']) ? (float)$data['total_price'] : 0;

    if (!$customization_id || $price <= 0) {
        echo json_encode([
            "success" => false,
            "message" => "Missing or invalid data"
        ]);
        exit;
    }

    // 1. UPDATE CUSTOMIZATION
    $stmt1 = $conn->prepare("
        UPDATE customization
        SET paper_size = ?,
            gsm = ?,
            paper_texture = ?,
            copies = ?,
            total_price = ?
        WHERE customization_id = ?
    ");

    if (!$stmt1) {
        echo json_encode([
            "success" => false,
            "message" => "Prepare failed (customization): " . $conn->error
        ]);
        exit;
    }

    $stmt1->bind_param(
        "sisidi",
        $paper_size,
        $gsm,
        $paper_texture,
        $copies,
        $price,
        $customization_id
    );

    if (!$stmt1->execute()) {
        echo json_encode([
            "success" => false,
            "message" => "Customization update failed: " . $stmt1->error
        ]);
        exit;
    }

    // 2. SYNC CART ITEMS
    $stmt2 = $conn->prepare("
        UPDATE shopping_cart_items
        SET unit_price = ?
        WHERE customization_id = ?
    ");

    if (!$stmt2) {
        echo json_encode([
            "success" => false,
            "message" => "Prepare failed (cart): " . $conn->error
        ]);
        exit;
    }

    $stmt2->bind_param(
        "di",
        $price,
        $customization_id
    );

    if (!$stmt2->execute()) {
        echo json_encode([
            "success" => false,
            "message" => "Cart sync failed: " . $stmt2->error
        ]);
        exit;
    }

    // 3. SUCCESS RESPONSE
    echo json_encode([
        "success" => true,
        "message" => "Customization and cart updated successfully",
        "customization_id" => $customization_id,
        "price" => $price
    ]);
    ?>