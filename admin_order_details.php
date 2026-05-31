<?php
session_start();
require "db.php";

$order_id = $_GET['order_id'] ?? 0;

if (!$order_id) {
    die("Invalid Order ID");
}


/* =========================
   ORDER + CUSTOMER INFO
========================= */
$query = "
SELECT
    o.order_id,
    o.order_date,
    o.order_status,
    o.payment_status,

    oc.payment_method,
    oc.delivery_method,

    u.first_name,
    u.last_name,
    u.email,
    u.contact_number

FROM orders o

LEFT JOIN users u
    ON o.user_id = u.user_id

LEFT JOIN order_check_out oc
    ON o.order_id = oc.order_id

WHERE o.order_id = ?
";

$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $order_id);
$stmt->execute();

$result = $stmt->get_result();

$order = $result->fetch_assoc();

if (!$order) {
    die("Order not found.");
}

/* =========================
   ORDER ITEMS
========================= */
$itemsQuery = "
SELECT
    p.product_name,

    od.quantity,
    od.unit_price,
    od.subtotal,
    od.total_price,

    c.paper_size,
    c.paper_texture,
    c.gsm,

    fu.image1,
    fu.image2

FROM order_details od

LEFT JOIN shopping_cart_items sci
    ON od.cart_item_id = sci.cart_item_id

LEFT JOIN products p
    ON sci.product_id = p.product_id

LEFT JOIN customization c
    ON sci.customization_id = c.customization_id

LEFT JOIN file_upload fu
    ON c.file_id = fu.file_id

WHERE od.order_id = ?
";

$stmt2 = $conn->prepare($itemsQuery);

if (!$stmt2) {
    die("Prepare failed: " . $conn->error);
}

$stmt2->bind_param("i", $order_id);
$stmt2->execute();

$itemsResult = $stmt2->get_result();

$order_items = [];

while ($row = mysqli_fetch_assoc($itemsResult)) {
    $order_items[] = $row;
}

/* =========================
   ORDER TOTALS
========================= */

$total_quantity = 0;

foreach ($order_items as $item) {
    $total_quantity += $item['quantity'];
}

/* GET ORDER SUMMARY */
$summaryQuery = "
SELECT
    subtotal,
    discount,
    grand_total
FROM order_summary
WHERE order_id = ?
";

$stmt3 = $conn->prepare($summaryQuery);
$stmt3->bind_param("i", $order_id);
$stmt3->execute();

$summaryResult = $stmt3->get_result();
$summary = $summaryResult->fetch_assoc();

/* VALUES FROM DATABASE */
$order_subtotal = $summary['subtotal'] ?? 0;
$discount = $summary['discount'] ?? 0;
$order_total = $summary['grand_total'] ?? 0;

$statusClass = "";

switch(strtolower($order['order_status'])) {

    case "completed":
        $statusClass = "status-completed";
        break;

    case "cancelled":
        $statusClass = "status-cancelled";
        break;

    case "processing":
        $statusClass = "status-processing";
        break;

    case "ready for pickup":
        $statusClass = "status-ready";
        break;

    default:
        $statusClass = "status-pending";
}

/* helper */
function e($str) {
    return htmlspecialchars($str ?? '');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="image_resources/logo.png">
    <title>Orders - PrintPro</title>

    <link href="bootstrap.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Mina:wght@700&family=Poppins:wght@300;400;500;600&display=swap"
        rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

    <style>
        * {
            box-sizing: border-box;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        .row {
            margin-left: 0;
            margin-right: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f5f7;
            display: flex;
            overflow-x: hidden;
            width: 100%;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 240px;
            height: 100vh;
            background: #25286b;
            color: white;
            position: fixed;
            padding: 22px;
            display: flex;
            flex-direction: column;
            top: 0;
            left: 0;
            transition: .3s ease;
            z-index: 1000;
            box-shadow: 4px 0 20px rgba(0, 0, 0, .12);
        }

        /* OVERLAY */
        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .35);
            opacity: 0;
            visibility: hidden;
            transition: .3s;
            z-index: 900;
        }

        .overlay.show {
            opacity: 1;
            visibility: visible;
        }

        /* HAMBURGER BUTTON */
        .menu-btn {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1100;
            background: #2b2d77;
            color: white;
            border: none;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 20px;
        }

        /* LOGO */
        .logo {
            font-family: 'Mina', sans-serif;
            font-size: 28px;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 50px;
            padding: 10px 5px;
        }

        /* NAV LINKS */
        .nav-link {
            color: rgba(255, 255, 255, .82);
            margin: 6px 0;
            padding: 14px 16px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
            transition: .25s ease;
            font-weight: 500;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, .08);
            color: white;
            transform: translateX(3px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, #0084ff, #1f9fff);
            color: white;
            font-weight: 600;
            box-shadow: 0 6px 16px rgba(0, 132, 255, .25);
        }

        /* LOGOUT */
        .logout-btn {
            background: #d9363e;
            color: white;
            padding: 12px;
            border-radius: 14px;
            font-weight: 600;
            transition: .25s;
            text-align: center;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .logout-btn:hover {
            background: #eb1616;
            color: white;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: 240px;
            width: calc(100% - 240px);
            padding: 40px;
            transition: .3s;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {

            .sidebar {
                left: -240px;
                width: 240px;
                box-shadow: 3px 0 15px rgba(0, 0, 0, .25);
            }

            .sidebar.show {
                left: 0;
            }

            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 20px;
                padding-top: 90px;
            }

            .card-box {
                padding: 15px;
            }

            .img-box {
                padding: 5px;
            }

            .value {
                float: none;
                display: block;
                margin-top: 3px;
            }

            .row-line {
                flex-direction: column;
                gap: 4px;
            }

            .receipt-container {
                padding: 15px !important;
            }

        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: #333;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 15px;
            position: relative;
            z-index: 10;
        }

        .card-box {
            background: white;
            border-radius: 10px;
            padding: 20px;
            border: 1px solid #ddd;
        }

        .img-box {
            background: #f5f5f5;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
        }

        .img-box img {
            width: 100%;
            height: auto;
            object-fit: contain;
            border-radius: 5px;
        }

        .small-text {
            font-size: 14px;
        }

        .label {
            color: #888;
        }

        .value {
            float: right;
            font-weight: 500;
        }

        .total {
            font-weight: bold;
        }

        .btn-orange {
            background: #ff7a00;
            color: white;
            width: 100%;
        }

        .divi {
            border: .5px solid lightgray;
            margin: 10px 0;
        }

        .row-line {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
        }

        .status-pending {
            color: #d97706;
            font-weight: 600;
        }

        .status-processing {
            color: #8b5cf6;
            font-weight: 600;
        }

        .status-ready {
            color: #4457c7;
            font-weight: 600;
        }

        .status-completed {
            color: #16a34a;
            font-weight: 600;
        }

        .status-cancelled {
            color: #dc2626;
            font-weight: 600;
        }

        .payment-paid {
            color: #16a34a;
            font-weight: 600;
        }

        .payment-unpaid {
            color: #d97706;
            font-weight: 600;
        }

        .payment-refunded {
            color: #dc2626;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="overlay" onclick="toggleSidebar()"></div>

    <button class="menu-btn d-md-none" onclick="toggleSidebar()">
        ☰
    </button>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="logo mt-5">
            <img src="image_resources/logo.png" width="30">
            PrintPro
        </div>

        <nav class="nav flex-column">
            <a href="admin_dashboard.php" class="nav-link"><span
                    class="material-symbols-outlined">home</span>Dashboard</a>
            <a href="admin_clients.php" class="nav-link"><span
                    class="material-symbols-outlined">person</span>Clients</a>
            <a href="admin_orders.php" class="nav-link active"><span
                    class="material-symbols-outlined">shopping_cart</span>Orders</a>
            <a href="admin_inventory.php" class="nav-link"><span
                    class="material-symbols-outlined">inventory_2</span>Inventory</a>
            <a href="admin_analytics.php" class="nav-link"><span
                    class="material-symbols-outlined">analytics</span>Report and Analytics</a>
        </nav>

        <div class="mt-auto">
            <a href="login.html" class="logout-btn">
                <span class="material-symbols-outlined">logout</span>
                Logout
            </a>
        </div>
    </div>

    <!-- MAIN -->
    <div class="main-content">

        <a href="#" class="back-btn" onclick="history.back()">← Back</a>

        <div class="card-box mb-4">
            <div class="row">

                <!-- LEFT SIDE : ALL IMAGES -->
                <div class="col-md-5">
                    <?php foreach($order_items as $index => $item): ?>

                    <button class="btn btn-dark w-100 mb-2" onclick="printSingleItem(<?= $index ?>)">

                        PRINT
                        <?= strtoupper(e($item['product_name'])) ?>

                    </button>

                    <?php endforeach; ?>

                    <?php foreach($order_items as $index => $item): ?>

                    <div class="img-box mb-3 order-item-images" data-index="<?= $index ?>"
                        data-paper="<?= e($item['paper_size']) ?>" data-texture="<?= e($item['paper_texture']) ?>"
                        data-gsm="<?= e($item['gsm']) ?>">

                        <?php if(!empty($item['image1'])): ?>
                        <img src="<?= e($item['image1']) ?>" class="img-fluid rounded order-img-print mb-2">
                        <?php endif; ?>

                        <?php if(!empty($item['image2'])): ?>
                        <img src="<?= e($item['image2']) ?>" class="img-fluid rounded order-img-print">
                        <?php endif; ?>

                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- RIGHT SIDE -->
                <div class="col-md-7">

                    <!-- ORDER INFO -->
                    <p class="text-center small-text">Order ID</p>

                    <h5 class="text-center fw-bold">
                        #
                        <?= e($order['order_id']); ?>
                    </h5>

                    <div class="small-text mt-3">

                        <p>
                            <b>Full Name:</b>
                            <?= e($order['first_name'] . " " . $order['last_name']); ?>
                        </p>

                        <p>
                            <b>Email Address:</b>
                            <?= e($order['email']); ?>
                        </p>

                        <p>
                            <b>Contact Number:</b>
                            <?= e($order['contact_number']); ?>
                        </p>

                        <p>
                            <b>Payment Method:</b>
                            <?= e($order['payment_method']); ?>
                        </p>

                        <p>
                            <b>Delivery Method:</b>
                            <?= e($order['delivery_method']); ?>
                        </p>

                    </div>

                    <button class="btn btn-outline-secondary w-100 mb-3" data-bs-toggle="modal"
                        data-bs-target="#receiptModal">
                        VIEW ORDER RECEIPT
                    </button>

                    <!-- ORDER DETAILS -->
                    <div class="card-box">

                        <h5 class="fw-bold mb-3">
                            Order Details
                        </h5>

                        <?php foreach($order_items as $item): ?>

                        <div class="mb-4 pb-3 border-bottom">

                            <h6 class="fw-bold text-primary">
                                <?= e($item['product_name']); ?>
                            </h6>

                            <div class="small-text">

                                <p class="label">
                                    Status

                                    <span class="value <?= $statusClass ?> statusText">

                                        <?= e(ucfirst($order['order_status'])); ?>

                                    </span>
                                </p>
                                <?php
                                
                                $paymentClass = "";
                                switch(strtolower($order['payment_status'])) {
                                case "paid":
                                    $paymentClass = "payment-paid";
                                break;
                                
                                case "refunded":
                                    $paymentClass = "payment-refunded";
                                break;
                                
                                default:
                                $paymentClass = "payment-unpaid";
                                }
                                ?>

                                <p class="label">
                                    Payment Status

                                    <span class="value <?= $paymentClass ?> paymentStatusText">
                                        <?= e(ucfirst($order['payment_status'])); ?>
                                    </span>
                                </p>

                                <p class="label">
                                    Paper Size
                                    <span class="value">
                                        <?= e($item['paper_size']); ?>
                                    </span>
                                </p>

                                <p class="label">
                                    Paper Texture
                                    <span class="value">
                                        <?= e($item['paper_texture']); ?>
                                    </span>
                                </p>

                                <p class="label">
                                    GSM
                                    <span class="value">
                                        <?= e($item['gsm']); ?>
                                    </span>
                                </p>

                                <hr>

                                <p class="label">
                                    Unit Price
                                    <span class="value">
                                        ₱
                                        <?= e(number_format($item['unit_price'], 2)); ?>
                                    </span>
                                </p>

                                <p class="label">
                                    Copies
                                    <span class="value">
                                        x
                                        <?= e($item['quantity']); ?>
                                    </span>
                                </p>

                                <p class="label">
                                    Subtotal
                                    <span class="value">
                                        ₱
                                        <?= e(number_format($item['subtotal'], 2)); ?>
                                    </span>
                                </p>

                            </div>

                        </div>

                        <?php endforeach; ?>

                        <div class="small-text">

                            <p class="label">
                                Total Quantity
                                <span class="value">
                                    <?= $total_quantity; ?>
                                </span>
                            </p>

                            <p class="label">
                                Subtotal
                                <span class="value">
                                    ₱
                                    <?= number_format($order_subtotal, 2); ?>
                                </span>
                            </p>

                            <p class="label text-success">
                                Discount
                                <span class="value">
                                    ₱
                                    <?= number_format($discount, 2); ?>
                                </span>
                            </p>

                            <p class="label fw-bold fs-5">
                                Total
                                <span class="value text-primary">
                                    ₱
                                    <?= number_format($order_total, 2); ?>
                                </span>
                            </p>

                        </div>

                        <div class="mt-3">
                            <hr>

                            <p class="label fw-bold fs-5 text-primary">Update Order Status:</p>
                            <select id="orderStatus" class="form-select mb-2">

                                <option value="pending" <?=$order['order_status']=='pending' ? 'selected' : '' ?>>
                                    Pending
                                </option>

                                <option value="processing" <?=$order['order_status']=='processing' ? 'selected' : '' ?>>
                                    Processing
                                </option>

                                <option value="ready for pickup" <?=$order['order_status']=='ready for pickup'
                                    ? 'selected' : '' ?>>
                                    Ready for Pickup
                                </option>

                                <option value="completed" <?=$order['order_status']=='completed' ? 'selected' : '' ?>>
                                    Completed
                                </option>

                                <option value="cancelled" <?=$order['order_status']=='cancelled' ? 'selected' : '' ?>>
                                    Cancelled
                                </option>

                            </select>
                            <br>

                            <button class="btn btn-orange w-100" onclick="updateOrderStatus()">

                                <b>UPDATE ORDER STATUS</b>

                            </button>

                        </div>

                    </div>

                </div>

            </div>
        </div>
        <!-- STATUS MODAL -->
        <div class="modal fade" id="statusModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Order Status</h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>

                    <div class="modal-body" id="statusMessage">

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                            OK
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- RECEIPT MODAL -->
        <div class="modal fade" id="receiptModal" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Print Order Receipt</h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal">
                        </button>
                    </div>

                    <div class="modal-body" id="printArea">

                        <div class="receipt-container bg-white p-4">

                            <!-- HEADER -->
                            <div class="text-center mb-4">

                                <h6 class="section-title">
                                    PrintPro
                                </h6>
                                <p>
                                    Professional Printing Services
                                </p>

                                <p class="text-muted mb-1">
                                    Order Ticket Receipt
                                </p>

                                <h5 class="fw-bold">
                                    #
                                    <?= e($order['order_id']); ?>
                                </h5>

                            </div>

                            <hr>

                            <!-- CUSTOMER DETAILS -->
                            <h6 class="fw-bold mb-3">
                                Customer Information
                            </h6>

                            <div class="row-line mb-2">
                                <span>Full Name</span>
                                <span>
                                    <?= e($order['first_name'] . " " . $order['last_name']); ?>
                                </span>
                            </div>

                            <div class="row-line mb-2">
                                <span>Email Address</span>
                                <span>
                                    <?= e($order['email']); ?>
                                </span>
                            </div>

                            <div class="row-line mb-2">
                                <span>Contact Number</span>
                                <span>
                                    <?= e($order['contact_number']); ?>
                                </span>
                            </div>

                            <div class="row-line mb-2">
                                <span>Payment Method</span>
                                <span>
                                    <?= e($order['payment_method']); ?>
                                </span>
                            </div>

                            <div class="row-line mb-2">
                                <span>Delivery Method</span>
                                <span>
                                    <?= e($order['delivery_method']); ?>
                                </span>
                            </div>

                            <div class="row-line mb-2">
                                <span>Order Status</span>

                                <span class="<?= $statusClass ?>">
                                    <?= ucfirst(e($order['order_status'])); ?>
                                </span>
                            </div>

                            <hr class="my-4">

                            <!-- ORDER ITEMS -->
                            <h6 class="fw-bold mb-3">
                                Order Items
                            </h6>

                            <?php foreach($order_items as $item): ?>

                            <div class="product-box">

                                <p class="product-name">
                                    <?= e($item['product_name']); ?>
                                </p>

                                <div class="row-line mb-2">
                                    <span>Quantity</span>
                                    <span>
                                        x
                                        <?= e($item['quantity']); ?>
                                    </span>
                                </div>

                                <div class="row-line mb-2">
                                    <span>Paper Size</span>
                                    <span>
                                        <?= e($item['paper_size']); ?>
                                    </span>
                                </div>

                                <div class="row-line mb-2">
                                    <span>Texture</span>
                                    <span>
                                        <?= e($item['paper_texture']); ?>
                                    </span>
                                </div>

                                <div class="row-line mb-2">
                                    <span>GSM</span>
                                    <span>
                                        <?= e($item['gsm']); ?>
                                    </span>
                                </div>

                                <div class="row-line mb-2">
                                    <span>Unit Price</span>
                                    <span>
                                        ₱
                                        <?= number_format($item['unit_price'], 2); ?>
                                    </span>
                                </div>

                                <div class="row-line mb-2 fw-bold">
                                    <span>Subtotal</span>
                                    <span>
                                        ₱
                                        <?= number_format($item['subtotal'], 2); ?>
                                    </span>
                                </div>

                            </div>

                            <?php endforeach; ?>

                            <!-- PAYMENT SUMMARY -->
                            <h6 class="fw-bold mb-3">
                                Payment Summary
                            </h6>

                            <div class="row-line mb-2">
                                <span>Total Quantity</span>
                                <span>
                                    <?= $total_quantity; ?>
                                </span>
                            </div>

                            <div class="row-line mb-2">
                                <span>Subtotal</span>
                                <span>
                                    ₱
                                    <?= number_format($order_subtotal, 2); ?>
                                </span>
                            </div>

                            <div class="row-line mb-2 text-success">
                                <span>Discount</span>
                                <span>
                                    ₱
                                    <?= number_format($discount, 2); ?>
                                </span>
                            </div>

                            <div class="row-line grand-total">
                                <span>TOTAL</span>

                                <span class="text-primary">
                                    ₱
                                    <?= number_format($order_total, 2); ?>
                                </span>
                            </div>

                            <hr class="my-4">

                            <div class="text-center text-muted small">
                                Thank you for choosing PrintPro
                            </div>

                            <div class="text-center fw-bold mt-2">
                                PaldoTech Corporation
                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>

                        <button type="button" class="btn btn-primary" onclick="printReceipt()">

                            Print Receipt

                        </button>

                    </div>

                </div>
            </div>
        </div>
    </div>
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('show');
            document.querySelector('.overlay').classList.toggle('show');
        }
    </script>
    <script>

        function printSingleItem(index) {

            const container =
                document.querySelector(
                    `.order-item-images[data-index="${index}"]`
                );

            if (!container) {
                alert("Images not found.");
                return;
            }

            const images =
                container.querySelectorAll("img");

            let imagesHTML = "";

            images.forEach(img => {

                if (!img.src || img.src.trim() === "") {
                    return;
                }

                imagesHTML += `
            <div class="image-page">
                <img src="${img.src}">
            </div>
        `;
            });

            const paper =
                container.dataset.paper;

            const texture =
                container.dataset.texture;

            const gsm =
                container.dataset.gsm;

            const printWindow =
                window.open('', '', 'width=1000,height=700');

            printWindow.document.write(`
            <html>
                <head>
                    <title>Print Item</title>
                <style>
                    body{
                        margin:0;
                        padding:20px;
                        background:white;
                        font-family:Arial;
                    }
                    .image-page{
                        position:fixed;
                        inset:0;
                        width:100vw;
                        height:100vh;
                        overflow:hidden;
                        display:flex;
                        justify-content:center;
                        align-items:center;
                        page-break-after:always;
                    }
                    .image-page:last-child{
                        page-break-after:auto;
                    }
                    
                    img{
                        max-width:100%;
                        max-height:95vh;
                        object-fit:contain;
                        display:block;
                    }

                    @page{
                        margin:10mm;
                    }
                
                </style>
            </head>
            <body>
                ${imagesHTML}
            </body>
            </html>
            `);

            printWindow.document.close();

            printWindow.focus();

            setTimeout(() => {

                printWindow.print();

                printWindow.close();

            }, 500);

        }

    </script>
    <script>

        function printReceipt() {

            const printContents =
                document.getElementById("printArea").innerHTML;

            const printWindow =
                window.open('', '', 'width=900,height=650');

            printWindow.document.write(`
        <html>
        <head>
            <title>Print Receipt</title>

            <link href="bootstrap.css" rel="stylesheet">
            <link rel="icon" type="image/x-icon" href="image_resources/logo.png">

            <style>

                body{
                    font-family: 'Poppins', sans-serif;
                    background: white;
                    padding: 30px;
                    color: #111;
                }

                .receipt-container{
                    border: 2px solid #2b307e;
                    border-radius: 12px;
                    padding: 30px;
                    max-width: 800px;
                    margin: auto;
                }

                .receipt-header{
                    text-align:center;
                    margin-bottom:30px;
                }

                .receipt-header h2{
                    color:#2b307e;
                    font-weight:700;
                    margin-bottom:5px;
                }

                .receipt-header p{
                    color:#777;
                    margin:0;
                }

                .section-title{
                    font-size:16px;
                    font-weight:700;
                    color:#2b307e;
                    margin-top:25px;
                    margin-bottom:15px;
                    border-bottom:1px solid #ddd;
                    padding-bottom:5px;
                }

                .row-line{
                    display:flex;
                    justify-content:space-between;
                    margin-bottom:10px;
                    font-size:14px;
                }

                .product-box{
                    border:1px solid #ddd;
                    border-radius:8px;
                    padding:15px;
                    margin-bottom:15px;
                }

                .product-name{
                    font-weight:700;
                    color:#2b307e;
                    margin-bottom:10px;
                }

                .total-section{
                    margin-top:30px;
                    border-top:2px dashed #aaa;
                    padding-top:20px;
                }

                .grand-total{
                    font-size:20px;
                    font-weight:700;
                    color:#2b307e;
                }

                .footer{
                    margin-top:40px;
                    text-align:center;
                    color:#777;
                    font-size:13px;
                }

            </style>
        </head>

        <body>

            ${printContents}

        </body>
        </html>
    `);

            printWindow.document.close();

            printWindow.focus();

            setTimeout(() => {

                printWindow.print();
                printWindow.close();

            }, 500);

        }

    </script>

    <script>

        function updateOrderStatus() {

            let status = document.getElementById("orderStatus").value;

            fetch("admin_update_order_status.php", {

                method: "POST",

                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },

                body:
                    "order_id=<?= $order_id; ?>&status=" + encodeURIComponent(status)

            })

                .then(response => response.text())

                .then(data => {

                    console.log(data);

                    try {

                        let json = JSON.parse(data);

                        if (json.success) {
                            if (json.payment_status) {

                                document.querySelectorAll(".paymentStatusText")
                                    .forEach(el => {

                                        el.innerText =
                                            json.payment_status.charAt(0).toUpperCase() +
                                            json.payment_status.slice(1);

                                        el.classList.remove(
                                            "payment-paid",
                                            "payment-unpaid",
                                            "payment-refunded"
                                        );

                                        if (json.payment_status === "paid") {

                                            el.classList.add("payment-paid");

                                        } else if (json.payment_status === "refunded") {

                                            el.classList.add("payment-refunded");

                                        } else {

                                            el.classList.add("payment-unpaid");

                                        }

                                    });

                            }

                            document.getElementById("statusMessage").innerText =
                                "Order status updated successfully!";

                            let statusTexts = document.querySelectorAll(".statusText");

                            statusTexts.forEach(statusText => {

                                statusText.innerText =
                                    status.charAt(0).toUpperCase() + status.slice(1);

                                statusText.classList.remove(
                                    "status-pending",
                                    "status-processing",
                                    "status-ready",
                                    "status-completed",
                                    "status-cancelled"
                                );

                                if (status === "pending") {

                                    statusText.classList.add("status-pending");
                                } else if (status === "processing") {

                                    statusText.classList.add("status-processing");

                                } else if (status === "ready for pickup") {

                                    statusText.classList.add("status-ready");

                                } else if (status === "completed") {

                                    statusText.classList.add("status-completed");

                                } else if (status === "cancelled") {

                                    statusText.classList.add("status-cancelled");


                                }

                            });
                            let modal = new bootstrap.Modal(
                                document.getElementById('statusModal')
                            );

                            modal.show();

                        } else {

                            document.getElementById("statusMessage").innerText =
                                "Failed to update order status.";

                            let modal = new bootstrap.Modal(
                                document.getElementById('statusModal')
                            );

                            modal.show();

                        }

                    } catch (err) {

                        console.error(err);

                        alert("PHP Error. Check console.");

                    }

                })

                .catch(error => {

                    console.error(error);

                    alert("Fetch failed.");

                });

        }

    </script>

</body>

</html>
