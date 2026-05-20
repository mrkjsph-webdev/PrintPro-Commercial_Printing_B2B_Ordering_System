<?php

require "db.php";

/* TOTAL ORDERS */
$totalQuery =
    mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM orders");

$totalOrders =
    mysqli_fetch_assoc($totalQuery)['total'];

/* COMPLETED ORDERS */
$completedQuery =
    mysqli_query($conn,
    "SELECT COUNT(*) AS total
     FROM orders
     WHERE order_status = 'completed'");

$completedOrders =
    mysqli_fetch_assoc($completedQuery)['total'];

/* CANCELLED ORDERS */
$cancelledQuery =
    mysqli_query($conn,
    "SELECT COUNT(*) AS total
     FROM orders
     WHERE order_status = 'cancelled'");

$cancelledOrders =
    mysqli_fetch_assoc($cancelledQuery)['total'];

/* PRODUCT ANALYTICS */

$productQuery = mysqli_query($conn, "
SELECT 
    p.product_name,
    COUNT(*) AS total

FROM orders o

INNER JOIN order_details od
    ON o.order_id = od.order_id

INNER JOIN shopping_cart_items sci
    ON od.cart_item_id = sci.cart_item_id

INNER JOIN products p
    ON sci.product_id = p.product_id

GROUP BY p.product_name
ORDER BY total DESC
");


$productLabels = [];
$productData = [];

if(!$productQuery){
    die(mysqli_error($conn));
}

while($row = mysqli_fetch_assoc($productQuery)) {

    $productLabels[] = $row['product_name'];
    $productData[] = $row['total'];

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="image_resources/logo.png">
    <title>Report and Analytics | PrintPro</title>

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

        /* SEARCH & FILTER AREA */
        .filter-section {
            background: white;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .search-input {
            border: 2px solid #eee;
            border-radius: 12px;
            padding: 12px 20px;
            transition: 0.3s;
        }

        .search-input:focus {
            border-color: #0084ff;
            box-shadow: none;
        }

        /* PARA SA USER CARDS */
        .user-card {
            background: white;
            border: 2px dashed #0084ff;
            border-radius: 18px;
            padding: 25px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: 0.3s;
        }

        .user-card:hover {
            transform: scale(1.01);
            background: #f8fbff;
            border-style: solid;
        }

        .avatar-box {
            width: 70px;
            height: 70px;
            background: #eef2ff;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2c2e7a;
        }

        .btn-view {
            background: #0084ff;
            color: white;
            border-radius: 10px;
            padding: 8px 25px;
            font-weight: 600;
            border: none;
            transition: 0.3s;
        }

        .btn-view:hover {
            background: #0069cc;
            color: white;
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

        .card-custom {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .pie {
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: conic-gradient(#1c7ed6 0% 60%,
                    #69db7c 60% 87%,
                    #ffa94d 87% 100%);
            position: relative;
        }

        .pie span {
            position: absolute;
            color: white;
            font-weight: 600;
            text-align: center;
        }

        .label1 {
            top: 40%;
            left: 65%;
        }

        .label2 {
            top: 15%;
            left: 30%;
        }

        .label3 {
            top: 47%;
            left: 10%;
        }

        .stats-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
        }

        .btn-orange {
            background: #ff7a00;
            color: white;
            border-radius: 10px;
            padding: 12px;
            font-weight: 500;
        }

        .btn-orange:hover {
            background: #e66a00;
            color:white;
        }

        canvas {
            max-width: 400px;
            max-height: 400px;
        }
    </style>
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            <a href="admin_orders.php" class="nav-link">
                <span class="material-symbols-outlined">shopping_cart</span> Orders
            </a>
            <a href="admin_inventory.php" class="nav-link">
                <span class="material-symbols-outlined">inventory_2</span> Inventory
            </a>
            <a href="admin_analytics.php" class="nav-link active">
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
            <h3 class="fw-bold mb-4">Report and Analytics</h3>
            <div id="currentTime" class="text-muted small fw-bold"></div>
        </div>
        <div class="main">

            <div class="card-custom">

               <p class="text-muted fw-semibold mb-3">
                <?= date("F Y") ?> Analytics Report</p>

                <div class="row align-items-center">

                    <div class="col-md-6 d-flex justify-content-center">
                            <canvas id="analyticsChart" width="300" height="300"></canvas>
                    </div>

                    <div class="col-md-6">
                        <div class="stats-box mb-3">
                            <h5 class="fw-bold">
                                Total Orders:
                                <?= $totalOrders ?>
                            </h5>

                            <p>
                                Orders Completed:
                                <?= $completedOrders ?>
                            </p>

                            <p>
                                Orders Cancelled:
                                <?= $cancelledOrders ?>
                            </p>
                        </div>

                        <a href="generate_report.php" class="btn btn-orange w-100">
                            GENERATE PDF REPORT
                        </a>
                    </div>

                </div>
            </div>
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
   <script>

const ctx =
    document.getElementById('analyticsChart');

new Chart(ctx, {

    type: 'pie',

    data: {

        labels:
            <?= json_encode($productLabels) ?>,

        datasets: [{

            data:
                <?= json_encode($productData) ?>,

            backgroundColor: [
                '#1c7ed6',
                '#69db7c',
                '#ffa94d',
                '#ff6b6b',
                '#845ef7',
                '#fcc419'
            ],

            borderWidth: 2

        }]

    },

    options: {

        responsive: true,

        plugins: {

            legend: {

                position: 'bottom'

            }

        }

    }

});

</script>
</body>

</html>