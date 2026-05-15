<?php require "order_details.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order Details - PrintPro</title>

    <link href="bootstrap.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Mina:wght@400;700&display=swap" rel="stylesheet">

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
    </style>
</head>

<body>
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
                    <a href="client_dashboard.html"><img src="image_resources/home-btn.png" alt="Home"></a>
                </li>

                <!-- User icon -->
                <li class="list">
                    <a href="my_profile.html"><img src="image_resources/user-btn.png" alt="User"></a>
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

    <a href="client_dashboard.php" class="text-dark fw-bold"> 
        <img src="image_resources/arrow_back.png" alt="Back" width="20" height="20"> Back
    </a>

    <div class="row mt-3">

        <!-- IMAGE -->
        <div class="col-md-5">

            <?php if (!empty($firstItem['image1'])): ?>
            <img src="<?= e($firstItem['image1']) ?>" class="img-fluid shadow mb-2">
            <?php endif; ?>

            <?php if (!empty($firstItem['image2'])): ?>
                <img src="<?= e($firstItem['image2']) ?>" class="img-fluid shadow">
            <?php endif; ?>

        </div>

        <!-- ORDER INFO -->
        <div class="col-md-7">

            <div class="card p-3 mb-3">
                <h6 class="text-center">Order ID</h6>
                <h5 class="text-center fw-bold">#<?= e($order['order_id']) ?></h5>
                <hr>

                <small>
                    <p><b>Full Name:</b> <?= e($order['fullname']) ?></p>
                    <p><b>Email:</b> <?= e($order['email']) ?></p>
                    <p><b>Contact:</b> <?= e($order['contact']) ?></p>
                    <p><b>Payment:</b> <?= e($order['payment_status']) ?></p>
                    <p><b>Delivery:</b> Pickup Only</p>
                </small>

                <button class="btn btn-outline-secondary mt-2" onclick="generateReceipt()">
                    PRINT ORDER TICKET
                </button>
            </div>

            <div class="card p-3">
                <h4 class="fw-bold">Order Details</h4>

                <p class="fw-bold text-primary">
                    <?= e($firstItem['product_name'] ?? 'Product') ?>
                </p>

                <p>Status 
                    <span class="float-end fw-bold text-primary">
                        <?= e($order['order_status']) ?>
                    </span>
                </p>

                <small>
                    <p>Quantity <span class="float-end">x<?= e($firstItem['quantity'] ?? 0) ?></span></p>
                    <p>Paper Size <span class="float-end"><?= e($firstItem['paper_size'] ?? '') ?></span></p>
                    <p>Texture <span class="float-end"><?= e($firstItem['paper_texture'] ?? '') ?></span></p>
                    <p>GSM <span class="float-end"><?= e($firstItem['gsm'] ?? '') ?></span></p>
                </small>

                <hr>

                <small>
                    <p>Subtotal <span class="float-end">₱<?= e($firstItem['subtotal'] ?? 0) ?></span></p>
                    <p>Total <span class="float-end">₱<?= e($firstItem['total_price'] ?? $order['total_amount']) ?></span></p>
                </small>

                <button class="btn cancel-btn mt-3">CANCEL ORDER</button>
            </div>

        </div>
    </div>
</div>
<!-- Receipt Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="receiptModalLabel">Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Receipt Number: <span id="receiptNumber"></span></p>
                <p>Order ID: <?= $order['order_id'] ?></p>
                <p>Amount Paid: ₱<?= $order['total_amount'] ?></p>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function generateReceipt() {

    const orderId = "<?= $order['order_id'] ?>";
    const amountPaid = "<?= $order['total_amount'] ?>";

    fetch("save_receipt.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `order_id=${orderId}&amount_paid=${amountPaid}&payment_status=paid`
    })
    .then(res => res.json())
    .then(data => {

        if (data.status === "success") {

            document.getElementById("receiptNumber").textContent = data.receipt_number;

            // OPEN MODAL PROPERLY
            const modalEl = document.getElementById('receiptModal');
            const modal = new bootstrap.Modal(modalEl);
            modal.show();

        } else {
            alert(data.message);
        }

    })
    .catch(err => {
        console.error(err);
        alert("Failed to generate receipt");
    });
}
</script>

</body>
</html>