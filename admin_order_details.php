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

    fu.image1

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
    <title>Orders - PrintPro</title>

    <link href="bootstrap.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Mina:wght@700&family=Poppins:wght@300;400;500;600&display=swap"
        rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f5f7;
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* SIDEBAR */
        .sidebar {
            width: 240px;
            height: 100vh;
            background: #2b2d77;
            color: white;
            position: fixed;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .logo {
            font-family: 'Mina', sans-serif;
            font-size: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 40px;
        }

        .nav-link {
            color: white;
            opacity: 0.8;
            margin: 5px 0;
            padding: 12px 15px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            transition: 0.2s;
        }

        .nav-link.active {
            background: #0085ff;
            opacity: 1;
            font-weight: 500;
        }

        .logout-btn {
            background: #ff4d4f;
            color: white;
            padding: 10px;
            border-radius: 8px;
            text-decoration: none;
            display: flex;
            justify-content: center;
            gap: 8px;
        }

        .logout-btn:hover {
            background: #d9363e;
            color:white;
        }

        /* MAIN CONTENT FIX */
        .main {
            margin-left: 240px;
            width: calc(100% - 240px);
            padding: 30px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: #333;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 15px;
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

        /* RESPONSIVE FIX */
        @media (max-width: 768px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .main {
                margin-left: 0;
                width: 100%;
            }
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
            color: #0ea5e9;
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
    </style>
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="logo">
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
    <div class="main">

        <a href="#" class="back-btn" onclick="history.back()">← Back</a>

        <div class="card-box mb-4">
            <div class="row">

                <!-- LEFT SIDE : ALL IMAGES -->
                <div class="col-md-5">

                    <?php foreach($order_items as $item): ?>

                    <div class="img-box mb-3">
                        <img src="<?= e($item['image1']) ?>" class="img-fluid rounded">
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

                                <h3 class="fw-bold text-primary mb-1">
                                    PrintPro
                                </h3>

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

                            <div class="mb-4 pb-3 border-bottom">

                                <h6 class="fw-bold text-primary">
                                    <?= e($item['product_name']); ?>
                                </h6>

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

                            <div class="row-line fw-bold fs-5 border-top pt-3 mt-3">
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

            function printReceipt() {

                let printContents =
                    document.getElementById("printArea").innerHTML;

                let originalContents =
                    document.body.innerHTML;

                document.body.innerHTML = printContents;

                window.print();

                document.body.innerHTML = originalContents;

                location.reload();

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