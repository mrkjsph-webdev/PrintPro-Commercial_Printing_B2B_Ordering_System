<?php require "order_details.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="image_resources/logo.png">
    <title>Order Details - PrintPro</title>

    <link href="bootstrap.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Mina:wght@400;700&display=swap"
        rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Mina:wght@400;700&display=swap');

        @media screen and (max-width: 600px) {

            /* Responsive Style for Navigation Bar */
            nav.navigation ul,
            li,
            li.list {
                float: none;
            }

        }

        body {
            margin: 0;
            padding: 0;
        }

        /* Navigation Bar */

        nav.navigation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 55px;
            /* fixed height */
            background-color: #2B307E;
            z-index: 1050;
            display: flex;
            /* flexbox keeps items aligned */
            align-items: center;
            /* vertically center items */
            padding: 0 10px;
            /* horizontal padding only */
        }

        nav.navigation ul {
            margin: 0;
            padding: 0;
            /* remove vertical padding */
        }

        body {
            padding-top: 70px;
            /* match actual nav height */
        }

        nav ul {
            /* Styles for the navigation bar and its items */
            font-weight: 700;
            font-style: normal;
            list-style-type: none;
            margin-top: 0;
            padding: 15px 10px;
        }

        nav ul li a {
            /* Styles for the navigation links */
            color: white;
            text-decoration: none;
            text-align: center;
            padding: 14px 16px;
        }

        nav ul li a:hover {
            /* Hover effect for the navigation links */
            background-color: #3A3B7B;
        }

        nav ul li.list {
            /* Text Alignment and Layout of the Navigation Links. */
            float: right;
        }

        ul li {
            /* Text Styles and Layout of Logo */
            font-family: 'Mina', sans-serif;
            color: white;
            float: left;
        }

        /* Dropdown Menu Styles */
        .dropdown-item {
            color: #EB0808;
            font-weight: 700;
        }

        .dropdown-item:hover {
            color: #EB0808;
            font-weight: 700;
        }

        .dropdown-item:focus {
            background-color: transparent;
            color: inherit;
            outline: none;
        }

        img {
            border-radius: 8px;
        }

        .cancel-btn {
            background: #e50914;
            color: white;
            font-weight: bold;
        }


        .receipt-container {
            border: 2px solid #2b307e;
            padding: 20px;
        }

        .receipt-header {
            text-align: center;
        }

        .receipt-header h4 {
            font-family: 'Mina', sans-serif;
            color: #2b307e;
            font-weight: bold;
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

        .total {
            font-weight: bold;
        }


        @media print {
            body * {
                visibility: hidden;
            }

            #printArea,
            #printArea * {
                visibility: visible;
            }

            #printArea {
                position: absolute;
                top: 0;
                left: 0;
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

        .status-completed {
            color: #16a34a;
            font-weight: 600;
        }

        .status-ready {
            color: #4457c7;
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <header>
        <nav class="navigation">
            <ul class="d-flex align-items-center w-100">
                <!-- Logo -->
                <li class="me-2">
                    <img src="image_resources/logo.png" alt="Logo" style="max-width: 24px; max-height: 24px;">
                </li>

                <!-- Company Logo -->
                <li class="me-auto">PrintPro</li>

                <!-- Home icon -->
                <li class="list">
                    <a href="client_dashboard.php"><img src="image_resources/home-btn.png" alt="Home"></a>
                </li>

                <!-- User icon -->
                <li class="list">
                    <a href="my_profile.php"><img src="image_resources/user-btn.png" alt="User"></a>
                </li>

                <!-- Menu dropdown -->
                <li class="dropdown list">
                    <a href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="image_resources/menu-btn.png" alt="Menu">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item d-flex align-items-center gap-2" href="login.html">
                                <img src="image_resources/logout-btn.png" alt="Logout" height="16">
                                Logout
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>

        </nav>
    </header>


    <div class="container mt-4">

        <a href="my_orders.php" class="text-dark fw-bold">
            <img src="image_resources/arrow_back.png" alt="Back" width="20" height="20"> Back
        </a>

        <div class="row mt-3">

            <!-- ALL ORDER IMAGES -->
            <div class="col-md-5">

                <?php foreach ($items as $item): ?>

                <?php if (!empty($item['image1'])): ?>

                <img src="<?= e($item['image1']) ?>" class="img-fluid shadow rounded mb-3 w-100">

                <?php endif; ?>

                <?php if (!empty($item['image2'])): ?>

                <img src="<?= e($item['image2']) ?>" class="img-fluid shadow rounded mb-3 w-100">

                <?php endif; ?>

                <?php endforeach; ?>

            </div>

            <!-- ORDER INFO -->
            <div class="col-md-7">

                <div class="card p-3 mb-3">
                    <h6 class="text-center">Order ID</h6>
                    <h5 class="text-center fw-bold">#
                        <?= e($order['order_id']) ?>
                    </h5>
                    <hr>

                    <small>
                        <p><b>Full Name:</b>
                            <?= e($order['fullname']) ?>
                        </p>
                        <p><b>Email:</b>
                            <?= e($order['email']) ?>
                        </p>
                        <p><b>Contact:</b>
                            <?= e($order['contact']) ?>
                        </p>
                        <?php
                        $paymentClass = "";
                        switch(strtolower(trim($order['payment_status']))) {
                            
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

                        <p>
                            <b>Payment:</b>
                            <span class="<?= $paymentClass ?>">
                                <?= e(ucfirst($order['payment_status'])) ?>
                            </span>
                        </p>
                        <p><b>Delivery:</b> Pickup Only</p>
                    </small>

                    <button class="btn btn-outline-secondary mt-2" onclick="generateReceipt()">
                        PRINT ORDER TICKET
                    </button>
                </div>

                <div class="card p-3">

                    <h4 class="fw-bold">Order Details</h4>

                    <?php
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
                    ?>

                    <p>
                        Status:
                        <span id="statusText" class="<?= $statusClass ?>">
                            <?= ucfirst($order['order_status']); ?>
                        </span>
                    </p>

                    <hr>

                    <?php
                    $grandQuantity = 0;
                    $grandSubtotal = 0;

                    foreach ($items as $item):

                    // UNIT PRICE
                    $productTotal = $item['total_price'];
                    $unitPrice = 0;
                    
                    if (!empty($item['copies']) && $item['copies'] > 0) {
                        $unitPrice = $productTotal / $item['copies'];
                    }

                    // PRODUCT TOTAL
                    $productTotal = $unitPrice * $item['copies'];

                    // GRAND TOTALS
                    $grandQuantity += 1; // number of products
                    $grandSubtotal += $item['total_price'];
                    ?>

                    <div class="mb-4">

                        <!-- PRODUCT NAME -->
                        <p class="fw-bold text-primary mb-3">
                            <?= e($item['product_name']) ?>
                        </p>

                        <small>

                            <p>
                                Paper Size
                                <span class="float-end">
                                    <?= e($item['paper_size']) ?>
                                </span>
                            </p>

                            <p>
                                Texture
                                <span class="float-end">
                                    <?= e($item['paper_texture']) ?>
                                </span>
                            </p>

                            <p>
                                GSM
                                <span class="float-end">
                                    <?= e($item['gsm']) ?>
                                </span>
                            </p>

                            <!-- UNIT PRICE -->
                            <p>
                                Unit Price
                                <span class="float-end fw-bold">
                                    ₱
                                    <?= number_format($unitPrice, 2) ?>
                                </span>
                            </p>

                            <!-- COPIES -->
                            <p>
                                Copies
                                <span class="float-end fw-bold">
                                    x
                                    <?= e($item['copies']) ?>
                                </span>
                            </p>

                            <!-- PRODUCT TOTAL -->
                            <p>
                                Total
                                <span class="float-end fw-bold">
                                    ₱
                                    <?= number_format($productTotal, 2) ?>
                                </span>
                            </p>

                        </small>

                    </div>

                    <hr>

                    <?php endforeach; ?>

                    <!-- FINAL TOTALS -->
                    <?php
                    $discount = $grandSubtotal - $order['total_amount'];

                    if ($discount < 0) { $discount=0; } ?>

                    <div class="mt-4">

                        <p>
                            Product Quantity
                            <span class="float-end">
                                <?= $grandQuantity ?>
                            </span>
                        </p>

                        <p>
                            Subtotal
                            <span class="float-end">
                                ₱
                                <?= number_format($grandSubtotal, 2) ?>
                            </span>
                        </p>

                        <p class="text-success">
                            Discount
                            <span class="float-end">
                                ₱
                                <?= number_format($discount, 2) ?>
                            </span>
                        </p>

                        <h5 class="fw-bold border-top pt-2">

                            Total

                            <span class="float-end text-primary">
                                ₱
                                <?= number_format($order['total_amount'], 2) ?>
                            </span>

                        </h5>

                    </div>

                    <button class="btn cancel-btn mt-4 w-100" data-bs-toggle="modal" data-bs-target="#cancelOrderModal">
                        CANCEL ORDER
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Cancel Order Modal -->
    <div class="modal fade" id="cancelOrderModal" tabindex="-1">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title text-danger fw-bold">
                        Cancel Order
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    Are you sure you want to cancel this order?

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                        No

                    </button>

                    <button type="button" class="btn btn-danger" onclick="cancelOrder()">

                        Yes, Cancel Order

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

                    <h5 class="modal-title fw-bold">
                        Order Receipt
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body" id="printArea">

                    <div class="receipt-container bg-white">

                        <!-- HEADER -->
                        <div class="receipt-header mb-4">

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
                                <?= e($order['order_id']) ?>
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
                                <?= e($order['fullname']) ?>
                            </span>
                        </div>

                        <div class="row-line mb-2">
                            <span>Email</span>

                            <span>
                                <?= e($order['email']) ?>
                            </span>
                        </div>

                        <div class="row-line mb-2">
                            <span>Contact</span>

                            <span>
                                <?= e($order['contact']) ?>
                            </span>
                        </div>

                        <div class="row-line mb-2">
                            <span>Payment Status</span>

                            <span>
                                <?= e($order['payment_status']) ?>
                            </span>
                        </div>

                        <div class="row-line mb-2">
                            <span>Delivery Method</span>

                            <span>
                                Pickup Only
                            </span>
                        </div>

                        <div class="row-line mb-2">
                            <span>Order Status</span>

                            <span id="receiptStatus" class="<?= $statusClass ?>">

                                <?= ucfirst($order['order_status']) ?>

                            </span>
                        </div>

                        <div class="divi my-4"></div>

                        <!-- ORDER ITEMS -->
                        <h6 class="fw-bold mb-3">
                            Order Items
                        </h6>

                        <?php foreach ($items as $item): ?>

                        <?php
                        $productTotal = $item['total_price'];

                        $unitPrice = 0;

                        if (!empty($item['copies']) && $item['copies'] > 0) {

                            $unitPrice =
                                $productTotal / $item['copies'];

                        }
                    ?>

                        <div class="product-box">

                            <p class="product-name">
                                <?= e($item['product_name']) ?>
                            </p>

                            <div class="row-line mb-2">
                                <span>Paper Size</span>

                                <span>
                                    <?= e($item['paper_size']) ?>
                                </span>
                            </div>

                            <div class="row-line mb-2">
                                <span>Texture</span>

                                <span>
                                    <?= e($item['paper_texture']) ?>
                                </span>
                            </div>

                            <div class="row-line mb-2">
                                <span>GSM</span>

                                <span>
                                    <?= e($item['gsm']) ?>
                                </span>
                            </div>

                            <div class="row-line mb-2">
                                <span>Unit Price</span>

                                <span>
                                    ₱
                                    <?= number_format($unitPrice, 2) ?>
                                </span>
                            </div>

                            <div class="row-line mb-2">
                                <span>Copies</span>

                                <span>
                                    x
                                    <?= e($item['copies']) ?>
                                </span>
                            </div>

                            <div class="row-line fw-bold">
                                <span>Total</span>

                                <span>
                                    ₱
                                    <?= number_format($productTotal, 2) ?>
                                </span>
                            </div>

                        </div>

                        <?php endforeach; ?>

                        <!-- SUMMARY -->
                        <h6 class="fw-bold mb-3">
                            Payment Summary
                        </h6>

                        <div class="row-line mb-2">
                            <span>Product Quantity</span>

                            <span>
                                <?= $grandQuantity ?>
                            </span>
                        </div>

                        <div class="row-line mb-2">
                            <span>Subtotal</span>

                            <span>
                                ₱
                                <?= number_format($grandSubtotal, 2) ?>
                            </span>
                        </div>

                        <div class="row-line mb-2 text-success">
                            <span>Discount</span>

                            <span>
                                ₱
                                <?= number_format($discount, 2) ?>
                            </span>
                        </div>

                        <div class="row-line grand-total">

                            <span>TOTAL</span>

                            <span class="text-primary">
                                ₱
                                <?= number_format($order['total_amount'], 2) ?>
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
    <script>

        function generateReceipt() {

            const modalEl =
                document.getElementById('receiptModal');

            const modal =
                new bootstrap.Modal(modalEl);

            modal.show();

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

        function cancelOrder() {

            fetch("admin_update_order_status.php", {

                method: "POST",

                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },

                body:
                    "order_id=<?= $order_id; ?>&status=cancelled"

            })

                .then(response => response.json())

                .then(data => {

                    if (data.success) {

                        // CLOSE MODAL
                        const modalEl = document.getElementById('cancelOrderModal');

                        const modal = bootstrap.Modal.getInstance(modalEl);

                        modal.hide();

                        // UPDATE STATUS
                        const statusText = document.getElementById("statusText");

                        statusText.innerText = "Cancelled";

                        statusText.classList.remove(
                            "status-pending",
                            "status-processing",
                            "status-completed"
                        );

                        statusText.classList.add("status-cancelled");

                        // DISABLE BUTTON
                        const cancelBtn = document.querySelector(".cancel-btn");

                        cancelBtn.disabled = true;

                        cancelBtn.innerText = "ORDER CANCELLED";

                    } else {

                        alert("Failed to cancel order.");

                    }

                });

        }

    </script>
    <script>
        setInterval(() => {

            fetch("get_order_status.php?order_id=<?= $order_id ?>")
                .then(res => res.text())
                .then(status => {

                    document.getElementById("statusText").innerText = status;

                });
        }, 3000);

    </script>

</body>

</html>
