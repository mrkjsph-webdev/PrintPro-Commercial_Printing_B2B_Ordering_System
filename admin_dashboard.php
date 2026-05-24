<?php
session_start();
require "db.php";

// COMPLETED ORDERS
$completed_query = "
SELECT COUNT(*) AS total_completed
FROM orders
WHERE order_status = 'completed'
";

$completed_result = mysqli_query($conn, $completed_query);
$completed_data = mysqli_fetch_assoc($completed_result);

$total_completed = $completed_data['total_completed'];


// PROCESSING ORDERS
$processing_query = "
SELECT COUNT(*) AS total_processing
FROM orders
WHERE order_status = 'processing'
";

$processing_result = mysqli_query($conn, $processing_query);
$processing_data = mysqli_fetch_assoc($processing_result);

$total_processing = $processing_data['total_processing'];


// PENDING ORDERS
$pending_query = "
SELECT COUNT(*) AS total_pending
FROM orders
WHERE order_status = 'pending'
";

$pending_result = mysqli_query($conn, $pending_query);
$pending_data = mysqli_fetch_assoc($pending_result);

$total_pending = $pending_data['total_pending'];


// CANCELLED ORDERS
$cancelled_query = "
SELECT COUNT(*) AS total_cancelled
FROM orders
WHERE order_status = 'cancelled'
";

$cancelled_result = mysqli_query($conn, $cancelled_query);
$cancelled_data = mysqli_fetch_assoc($cancelled_result);

$total_cancelled = $cancelled_data['total_cancelled'];
?>

<?php
$recent_orders_query = "
SELECT 
    o.order_id,
    o.order_date,
    o.order_status,

    u.first_name,
    u.last_name,

    MIN(p.product_name) AS product_name,
    MIN(fu.image1) AS product_image

FROM orders o

LEFT JOIN users u
    ON o.user_id = u.user_id

LEFT JOIN order_details od
    ON o.order_id = od.order_id

LEFT JOIN shopping_cart_items sci
    ON od.cart_item_id = sci.cart_item_id

LEFT JOIN products p
    ON sci.product_id = p.product_id

LEFT JOIN customization c
    ON sci.customization_id = c.customization_id

LEFT JOIN file_upload fu
    ON c.file_id = fu.file_id

GROUP BY o.order_id

ORDER BY o.order_date DESC
LIMIT 5
";

$recent_orders_result = mysqli_query($conn, $recent_orders_query);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="image_resources/logo.png">
    <title>Admin Dashboard | PrintPro</title>

    <link href="bootstrap.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mina:wght@700&family=Poppins:wght@400;500;600&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f5f7;
            display: flex;
            overflow-x: hidden;
            scrollbar-gutter: stable;
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

        /* ===== DASHBOARD CARDS ===== */
        .status-card {
            border-radius: 18px;
            padding: 20px;
            color: white;
            display: flex;
            align-items: center;
            gap: 15px;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .bg-green {
            background: #2e7d32;
        }

        .bg-purple {
            background: #7e57c2;
        }

        .bg-orange {
            background: #ef6c00;
        }

        .bg-red {
            background: #c62828;
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
        }

        /* DITO UNG DASHBOARD STATUS CARDS */
        .status-card {
            border-radius: 12px;
            padding: 20px;
            color: white;
            display: flex;
            align-items: center;
            gap: 15px;
            border: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        /* DITO NAMAN UNG RECENT ORDERS BOX */
        .order-container {
            background: white;
            border-radius: 15px;
            padding: 18px;
            border: 1px solid #e0e0e0;
        }

        .order-card {
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            transition: 0.2s;
        }

        .order-card:hover {
            background: #fcfcfc;
        }


        .recent-order-card {
            display: flex;
            justify-content: space-between;
            align-items: center;

            padding: 18px 22px;
            border: 1px solid #ddd;
            border-radius: 15px;

            margin-bottom: 18px;
            background: white;
        }

        .recent-order-left {
            display: flex;
            align-items: center;
            gap: 20px;

            flex: 1;
        }

        .recent-order-left img {
            width: 90px;
            height: 90px;

            object-fit: cover;
            border-radius: 10px;

            flex-shrink: 0;
        }

        .recent-order-info h3 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
        }

        .recent-order-info p {
            margin: 6px 0 0;
            font-size: 15px;
        }

        .recent-order-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: center;

            gap: 14px;

            min-width: 180px;
        }

        .recent-order-right p {
            margin: 0;
            font-weight: 600;
        }

        .status-pending {
            color: #ef6c00;
        }

        .status-processing {
            color: #7e57c2;
        }

        .status-ready {
            color: #4457c7;
            font-weight: 600;
        }

        .status-completed {
            color: #2e7d32;
        }

        .status-cancelled {
            color: #c62828;
        }

        .view-order-btn {
            background: #0085ff;
            color: white;
            text-decoration: none;

            padding: 8px 18px;
            border-radius: 8px;

            font-size: 14px;
            font-weight: 500;

            transition: 0.2s;
        }

        .view-order-btn:hover {
            background: #006fe0;
            color: white;
        }
    </style>
</head>

<body>
    <div class="overlay" onclick="toggleSidebar()"></div>

    <button class="menu-btn d-md-none" onclick="toggleSidebar()">
        ☰
    </button>

    <div class="sidebar">
        <div class="logo mt-5">
            <img src="image_resources/logo.png" width="30" alt="Logo">
            PrintPro
        </div>

        <nav class="nav flex-column">
            <a href="admin_dashboard.php" class="nav-link active">
                <span class="material-symbols-outlined">home</span> Dashboard
            </a>
            <a href="admin_clients.php" class="nav-link">
                <span class="material-symbols-outlined">person</span> Clients
            </a>
            <a href="admin_orders.php" class="nav-link">
                <span class="material-symbols-outlined">shopping_cart</span> Orders
            </a>
            <a href="admin_inventory.php" class="nav-link">
                <span class="material-symbols-outlined">inventory_2</span> Inventory
            </a>
            <a href="admin_analytics.php" class="nav-link">
                <span class="material-symbols-outlined">analytics</span> Report and Analytics
            </a>
        </nav>

        <div class="mt-auto">
            <a href="login.html" class="logout-btn">
                <span class="material-symbols-outlined">logout</span>
                Logout
            </a>
        </div>
    </div>

    <main class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Admin Dashboard</h2>
            <div id="currentTime" class="text-muted small fw-bold"></div>
        </div>


        <div class="row g-3 mb-5">

            <div class="col-6 col-md-3">
                <div class="status-card bg-green">
                    <span class="material-symbols-outlined fs-1">check_circle</span>
                    <div>
                        <small>Orders Completed:</small>
                        <h3 class="mb-0 fw-bold">
                            <?php echo $total_completed; ?>
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="status-card bg-purple">
                    <span class="material-symbols-outlined fs-1">sync</span>
                    <div>
                        <small>Orders Processing:</small>
                        <h3 class="mb-0 fw-bold">
                            <?php echo $total_processing; ?>
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="status-card bg-orange">
                    <span class="material-symbols-outlined fs-1">history</span>
                    <div>
                        <small>Orders Pending:</small>
                        <h3 class="mb-0 fw-bold">
                            <?php echo $total_pending; ?>
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="status-card bg-red">
                    <span class="material-symbols-outlined fs-1">cancel</span>
                    <div>
                        <small>Orders Cancelled:</small>
                        <h3 class="mb-0 fw-bold">
                            <?php echo $total_cancelled; ?>
                        </h3>
                    </div>
                </div>
            </div>

        </div>

        <div class="order-container shadow-sm">
            <h5 class="fw-bold mb-4 text-primary">Recent Orders Placed</h5>

            <?php while($row = mysqli_fetch_assoc($recent_orders_result)) { ?>

            <div class="recent-order-card">

                <div class="recent-order-left">

                    <img src="<?php echo $row['product_image']; ?>" alt="">

                    <div class="recent-order-info">

                        <h3>
                            <?php echo $row['product_name']; ?>
                        </h3>

                        <p>
                            Order ID: #
                            <?php echo $row['order_id']; ?>
                        </p>

                        <p>
                            <?php echo $row['first_name'] . " " . $row['last_name']; ?>
                            |
                            <?php echo date("F j, Y", strtotime($row['order_date'])); ?>
                        </p>

                    </div>

                </div>

                <div class="recent-order-right">

                    <p class="
            <?php
            if($row['order_status'] == 'pending'){
                echo 'status-pending';
            }
            elseif($row['order_status'] == 'processing'){
                echo 'status-processing';
            }
            elseif($row['order_status'] == 'completed'){
                echo 'status-completed';
            }
            elseif($row['order_status'] == 'ready for pickup'){
                echo 'status-ready';
            }
            elseif($row['order_status'] == 'cancelled'){
                echo 'status-cancelled';
            }
            ?>
            ">
                        <?php echo ucfirst($row['order_status']); ?>
                    </p>

                    <a class="view-order-btn" href="admin_order_details.php?order_id=<?php echo $row['order_id']; ?>">
                        View Order
                    </a>

                </div>

            </div>

            <?php } ?>
        </div>
    </main>
    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('show');
            document.querySelector('.overlay').classList.toggle('show');
        }
    </script>
    <script>
        function updateTime() {
            const now = new Date();

            const time = now.toLocaleTimeString('en-PH', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            });

            const date = now.toLocaleDateString('en-PH', {
                month: 'long',
                day: 'numeric',
                year: 'numeric'
            });

            document.getElementById("currentTime").textContent = `${time} ${date}`;
        }

        setInterval(updateTime, 1000);
        updateTime();
    </script>
</body>

</html>