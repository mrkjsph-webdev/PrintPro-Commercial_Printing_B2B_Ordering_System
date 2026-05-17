<?php
session_start();
require "db.php";

$orders_query = "
SELECT 
    o.order_id,
    o.order_date,
    o.order_status,

    MIN(p.product_name) AS product_name,
    MIN(fu.image1) AS product_image

FROM orders o

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
";

$orders_result = mysqli_query($conn, $orders_query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders | PrintPro</title>

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
        }


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
            transition: all 0.2s ease;
        }

        .nav-link.active {
            background: #0085ff;
            opacity: 1;
            font-weight: 500;
        }


        .main-content {
            margin-left: 240px;
            width: 100%;
            padding: 40px;
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

        /* DITO NAMAN UNG RECENT ORDERS BOX */
        .order-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
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

        .logout-btn {
            background: #ff4d4f;
            color: white;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .logout-btn:hover {
            background: #d9363e;
            color:white;
        }

        .orders-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
        }

        .order-item {
            border: 1px solid #4a6cf7;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 10px;
        }

        .order-img {
            width: 80px;
            height: 80px;

            object-fit: cover;
            border-radius: 10px;

            flex-shrink: 0;
        }

        .status {
            font-size: 12px;
            font-weight: bold;
            margin-right: 10px;
        }

        .processing {
            color: blue;
        }

        .pending {
            color: orange;
        }

        .completed {
            color: #2e7d32;
        }

        .cancelled {
            color: #c62828;
        }

        .orders-box {
            background: white;

            padding: 10px;
            border-radius: 10px;

            max-height: 650px;
            overflow-y: auto;

            scrollbar-width: thin;
        }

        .orders-box::-webkit-scrollbar {
            width: 8px;
        }

        .orders-box::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .orders-box::-webkit-scrollbar-thumb {
            background: #c5c5c5;
            border-radius: 10px;
        }

        .orders-box::-webkit-scrollbar-thumb:hover {
            background: #999;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <div class="logo">
            <img src="image_resources/logo.png" width="30" alt="Logo">
            PrintPro
        </div>

        <nav class="nav flex-column">
            <a href="admin_dashboard.php" class="nav-link">
                <span class="material-symbols-outlined">home</span> Dashboard
            </a>
            <a href="admin_clients.php" class="nav-link">
                <span class="material-symbols-outlined">person</span> Clients
            </a>
            <a href="admin_orders.php" class="nav-link active">
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
            <h2 class="fw-bold">Orders</h2>
            <div id="currentTime" class="text-muted small fw-bold"></div>
        </div>

        <div class="order-container shadow-sm">
            <div class="filter-section mb-3">
                <div class="row g-3">
                    <div class="col-md-7">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 border-2"><span
                                    class="material-symbols-outlined text-muted">search</span></span>
                            <input type="text" class="form-control border-start-0 border-2 search-input"
                                placeholder="Search by order ID..." id="userSearch">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <select class="form-select border-2 search-input" id="occupationFilter">
                            <option value="" disabled selected>Category</option>
                            <option value="ByRecentOrders">By Recent Orders</option>
                            <option value="Completed">Completed</option>
                            <option value="Processing">Processing</option>
                            <option value="Pending">Pending</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="orders-box">
                
                <?php while($row = mysqli_fetch_assoc($orders_result)) { ?>

                <div class="order-item d-flex align-items-center">

                    <img src="<?php echo $row['product_image']; ?>" class="order-img">

                    <div class="ms-3 flex-grow-1">

                        <h6 class="mb-1">
                            <?php echo $row['product_name']; ?>
                        </h6>

                        <small class="text-muted">
                            Order ID: #
                            <?php echo $row['order_id']; ?>
                        </small>
                        <br>

                        <small class="text-muted">
                            <?php echo date("F j, Y", strtotime($row['order_date'])); ?>
                        </small>

                    </div>

                    <span class="status
                        <?php
                            if($row['order_status'] == 'processing'){
                                echo 'processing';
                            } elseif($row['order_status'] == 'pending'){
                                echo 'pending';
                            } elseif($row['order_status'] == 'completed'){
                                echo 'completed';
                            } elseif($row['order_status'] == 'cancelled'){
                                echo 'cancelled';
                            }
                        ?>
                    ">
                    <?php echo ucfirst($row['order_status']); ?>
                    </span>

                    <button class="btn btn-primary btn-sm ms-3"
                        onclick="window.location.href='admin_order_details.php?order_id=<?php echo $row['order_id']; ?>'">
                        View Order
                    </button>

                </div>

                <?php } ?>

            </div>
    </main>
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