<?php

session_start();
require "db.php";

$user_id = $_SESSION['user_id'];

$cartQuery = "
SELECT COUNT(sci.cart_item_id) AS cart_count
FROM shopping_cart_items sci
INNER JOIN shopping_cart sc
ON sci.cart_id = sc.cart_id
WHERE sc.user_id = ?
AND sci.cart_status = 'active'
";

$stmt = $conn->prepare($cartQuery);

if(!$stmt){
    die("SQL Error: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

$cart_count = $row['cart_count'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="image_resources/logo.png">
    <title>PrintPro Dashboard</title>
    <link href="bootstrap.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Mina:wght@400;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f5f7;
            padding-top: 55px;
        }

        nav.navigation {
            position: fixed;
            top: 0;
            left: 0;
            height: 55px;
            width: 100%;
            background-color: #2B307E;
            z-index: 1000;
            /* important */
        }


        .card-btn {
            background-color: #3A3B7B;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            border-radius: 0.75rem;
            width: 100%;
            /* full width inside column */
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

        /* Responsive grid */
        @media (max-width: 992px) {
            .row {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }

            .col-md-4 {
                margin-bottom: 1rem;
                /* spacing between stacked cards */
            }
        }

        @media (max-width: 768px) {
            .card-btn {
                padding: 1rem;
                /* tighter padding for small screens */
                font-size: 0.9rem;
            }
        }

        .card-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        .template-card {
            background: white;
            border-radius: 12px;
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
        }

        .template-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-orange {
            background-color: #FF7800;
            color: white;
            border: none;
            padding: 8px 0;
            border-radius: 8px;
            font-weight: 500;
            width: 100%;
            transition: background 0.2s;
        }

        .btn-orange:hover {
            background-color: #FF7800;
            color: white;
        }

        .search-wrapper {
            position: relative;
            max-width: 600px;
            width: 100%;
        }

        .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }

        .filter-btn {
            height: 40px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 12px;
            padding: 12px 18px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .filter-btn:hover {
            background: #f8f9fa;
        }

        .template-img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
        }

        hr {
            border-top: 1px solid #cbd5e0;
            opacity: 1;
        }

        .action-box {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            transition: all 0.2s;
        }

        .action-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
        }

        @media (max-width: 768px) {
            .topbar .flex {
                padding: 0 1rem;
            }
        }

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

        .search-input {
            border: 2px solid #eee;
            border-radius: 12px;
            transition: 0.3s;
            height: 48px;
            /* fixed height for consistency */
            line-height: 1.5;
            /* keeps text centered */
        }

        input.search-input {
            padding: 0 20px;
            /* horizontal only, vertical handled by height */
        }

        select.search-input {
            padding: 0 20px;
            /* same horizontal padding */
            appearance: none;
            /* optional: unify styling across browsers */
        }

        .cart-link {
            position: relative;
            display: inline-block;
        }

        .cart-badge {
            position: absolute;

            top: -6px;
            right: -1px;

            background: #ff3b3b;
            color: white;

            font-size: 10px;
            font-weight: bold;

            min-width: 18px;
            height: 18px;

            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            padding: 2px 5px;

            line-height: 1;
        }
        @media (max-width: 768px) {
            .template-img {
                height: 120px;
            }
            
            .template-card h4 {
                font-size: 1rem;
            }

            .btn-orange {
                font-size: 0.85rem;
            }
        }
    </style>
</head>

<body>
    <script src="bootstrap.bundle.js"></script>

    <header>
        <nav class="navigation">
            <ul class="d-flex align-items-center w-100">
                <!-- Logo -->
                <li class="me-2">
                    <img src="image_resources/logo.png" alt="Logo" style="max-width: 24px; max-height: 24px;">
                </li>

                <!-- Company Logo -->
                <li class="me-auto">PrintPro</li>

                <!-- Cart icon -->
                <li class="cart-link">
                    <a href="cart.html">

                        <img src="image_resources/shopping_cart.png" alt="Cart">
                        <?php if ($cart_count > 0): ?>
                        <span class="cart-badge">
                            <?= $cart_count ?>
                        </span>
                        <?php endif; ?>
                    </a>
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

    <main>
        <!-- Greeting -->
        <div class="px-4 px-md-5 mt-4">
            <h2 class="text-primary fw-bold" style="color: #0088FF !important; font-size: 1.75rem;">
                <?php
                    if (isset($_SESSION['first_name'], $_SESSION['last_name'])) {
                        echo "Hi, " . htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']) . ".";
                    } else {
                        echo "Hi, Guest.";
                    }
                ?>
            </h2>
        </div>

        <!-- Action Cards (My Orders, Order History, My Profile) -->
        <div class="px-4 px-md-5 mt-4">
            <div class="row g-3">
                <div class="col-6 col-md-4">
                    <div class="card-btn text-white p-4 d-flex align-items-center gap-3">
                        <img src="image_resources/package_order.png" alt="User" height="32">
                        <span onclick="window.location.href='my_orders.html'">My Orders</span>
                    </div>
                </div>
                <div class="col-6 col-md-4">
                    <div class="card-btn text-white p-4 d-flex align-items-center gap-3">
                        <img src="image_resources/history.png" alt="User" height="32">
                        <span onclick="window.location.href='order_history.html'">Order History</span>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card-btn text-white p-4 d-flex align-items-center gap-3">
                        <img src="image_resources/profile.png" alt="User" height="32">
                        <span onclick="window.location.href='my_profile.html'">My Profile</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <div class="px-4 px-md-5 mt-5">
            <hr style="height: 2px; background-color: #0E0E0E; opacity: 1; border: none;">
        </div>

        <!-- Browse Section -->
        <div class="px-4 px-md-5 mt-4 pb-5">

            <h3 class="text-center text-primary fw-bold mb-4" style="color: #0088FF !important;">
                Browse Product Templates For Your Next Customization
            </h3>

            <!-- Search + Filter -->
            <div class="filter-section">
                <div class="row g-3">
                    <div class="col-7 col-md-7">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 border-2"><span
                                    class="material-symbols-outlined text-muted">search</span></span>
                            <input type="text" class="form-control border-start-0 border-2 search-input"
                                placeholder="Search for product template to customize..." id="userSearch">
                        </div>
                    </div>
                    <div class="col-5 col-md-5">
                        <select class="form-select border-2 search-input" id="productFilter">
                               <option value="">All Product Templates</option>
                               <option value="Marketing Materials">Marketing Materials</option>
                               <option value="Business & Corporate">Business & Corporate</option>
                               <option value="Events">Events</option>
                               <option value="Publication Covers">Publication Covers</option>
                        </select>
                    </div>
                </div>
            </div>
            <br>
            <!-- Templates Grid (Bootstrap Cards) -->
            <div class="row g-4">
                <!-- Flyers -->
                <div class="col-6 col-md-4 template-item" data-category="Marketing Materials">
                    <div class="template-card p-3 h-100">
                        <img src="image_resources/flyers.png" class="template-img mb-3">
                        <h4 class="text-primary fw-bold mb-2" style="color: #0088FF !important;">Flyers</h4>
                        <button class="btn-orange" onclick="window.location.href='product_flyers.html'">View
                            Details</button>
                    </div>
                </div>
                <!-- Postcards -->
                <div class="col-6 col-md-4 template-item" data-category="Marketing Materials">
                    <div class="template-card p-3 h-100">
                        <img src="image_resources/postcards.webp" class="template-img mb-3">
                        <h4 class="text-primary fw-bold mb-2" style="color: #0088FF !important;">Postcards</h4>
                        <button class="btn-orange" onclick="window.location.href='product_postcards.html'">View
                            Details</button>
                    </div>
                </div>
                <!-- Posters -->
                <div class="col-6 col-md-4 template-item" data-category="Marketing Materials">
                    <div class="template-card p-3 h-100">
                        <img src="image_resources/posters.png" class="template-img mb-3">
                        <h4 class="text-primary fw-bold mb-2" style="color: #0088FF !important;">Posters</h4>
                        <button class="btn-orange" onclick="window.location.href='product_posters.html'">View
                            Details</button>
                    </div>
                </div>
                <!-- Business Cards -->
                <div class="col-6 col-md-4 template-item" data-category="Business & Corporate">
                    <div class="template-card p-3 h-100">
                        <img src="image_resources/businesscards.webp" class="template-img mb-3">
                        <h4 class="text-primary fw-bold mb-2" style="color: #0088FF !important;">Business Cards</h4>
                        <button class="btn-orange" onclick="window.location.href='product_business_cards.html'">View
                            Details</button>
                    </div>
                </div>
                <!-- Brochures -->
                <div class="col-6 col-md-4 template-item" data-category="Marketing Materials">
                    <div class="template-card p-3 h-100">
                        <img src="image_resources/brochure.avif" class="template-img mb-3">
                        <h4 class="text-primary fw-bold mb-2" style="color: #0088FF !important;">Brochures</h4>
                        <button class="btn-orange" onclick="window.location.href='product_brochures.html'">View
                            Details</button>
                    </div>
                </div>
                <!-- Invitations -->
                <div class="col-6 col-md-4 template-item" data-category="Events">
                    <div class="template-card p-3 h-100">
                        <img src="image_resources/invitation.webp" class="template-img mb-3">
                        <h4 class="text-primary fw-bold mb-2" style="color: #0088FF !important;">Invitations</h4>
                        <button class="btn-orange" onclick="window.location.href='product_invitations.html'">View
                            Details</button>
                    </div>
                </div>
                <div class="col-6 col-md-4 template-item" data-category="Publication Covers">
                    <div class="template-card p-3 h-100">
                        <img src="image_resources/magazine.png" class="template-img mb-3">
                        <h4 class="text-primary fw-bold mb-2" style="color: #0088FF !important;">Magazine Covers</h4>
                        <button class="btn-orange" onclick="window.location.href='product_magazines.html'">View
                            Details</button>
                    </div>
                </div>
                <div class="col-6 col-md-4 template-item" data-category="Business & Corporate">
                    <div class="template-card p-3 h-100">
                        <img src="image_resources/resume.png" class="template-img mb-3">
                        <h4 class="text-primary fw-bold mb-2" style="color: #0088FF !important;">Resume</h4>
                        <button class="btn-orange" onclick="window.location.href='product_resume.html'">View
                            Details</button>
                    </div>
                </div>
            </div>
            <!--Toast Notification-->
            <div class="toast-container position-fixed bottom-0 end-0 p-3">

                <div id="liveToast" class="toast border-0 shadow" role="alert" aria-live="assertive" aria-atomic="true">

                    <div class="toast-header">

                        <img src="image_resources/logo.png" class="rounded me-2" width="20">

                        <strong class="me-auto">

                            Order Status Notification

                        </strong>

                        <small id="toastTime">

                            just now

                        </small>

                        <button type="button" class="btn-close" data-bs-dismiss="toast">
                        </button>

                    </div>

                    <div class="toast-body" id="toastMessage">

                        

                    </div>

                </div>

            </div>
        </div>
    </main>
    <!-- Search & Filter Function -->
    <script>

        let previousStatuses = {};

        function showToast(message) {

            document.getElementById("toastMessage").innerText = message;

            document.getElementById("toastTime").innerText = "just now";

            const toastElement =
                document.getElementById("liveToast");

            const toast =
                bootstrap.Toast.getOrCreateInstance(toastElement);

            toast.show();

            /*PLAY SOUND*/
            const sound = new Audio("notification/notification.mp3");
            sound.play();
        }

        function checkOrderStatus() {

            fetch("check_order_status.php")

                .then(response => response.json())

                .then(data => {

                    if (!data.success) return;

                    data.orders.forEach(order => {

                        const orderId = order.order_id;

                        const currentStatus = order.order_status;

                        /*
                        FIRST TIME LOADING THIS ORDER
                        */
                        if (!(orderId in previousStatuses)) {

                            previousStatuses[orderId] = currentStatus;

                            return;
                        }

                        /*
                        STATUS CHANGED
                        */
                        if (previousStatuses[orderId] != currentStatus) {

                            showToast(
                                `Order #${orderId} is now "${currentStatus}".`
                            );

                            console.log(
                                "Changed:",
                                previousStatuses[orderId],
                                "->",
                                currentStatus
                            );

                            /*
                            UPDATE AFTER TOAST
                            */
                            previousStatuses[orderId] = currentStatus;
                        }

                    });

                })

                .catch(error => console.error(error));
        }

        /*
        LOAD INITIAL STATUSES
        */
        checkOrderStatus();

        /*
        CHECK EVERY 5 SECONDS
        */
        setInterval(checkOrderStatus, 5000);

    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const searchInput = document.getElementById("userSearch");
            const filterSelect = document.getElementById("productFilter");
            const items = document.querySelectorAll(".template-item");
            function filterItems() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedCategory = filterSelect.value.toLowerCase();
                let visibleCount = 0;
                items.forEach(item => {
                    const title = item.querySelector("h4").textContent.toLowerCase();
                    const category = item.getAttribute("data-category").toLowerCase();

                    const matchesSearch = title.includes(searchTerm);
                    const matchesFilter =
                        selectedCategory === "" || category === selectedCategory;

                    if (matchesSearch && matchesFilter) {
                        item.style.display = "";
                        visibleCount++;
                    } else {
                        item.style.display = "none";
                    }
                });
            }
            // Events
            searchInput.addEventListener("input", filterItems);
            filterSelect.addEventListener("change", filterItems);
        });
    </script>
</body>

</html>
