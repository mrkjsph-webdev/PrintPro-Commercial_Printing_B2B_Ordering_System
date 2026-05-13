<?php
require_once 'db.php';

$query = "
SELECT 
    pc.category_name,
    SUM(od.quantity) AS quantity
FROM order_details od
JOIN products p 
    ON od.product_id = p.product_id
JOIN product_category pc 
    ON p.category_id = pc.category_id
GROUP BY pc.category_name
";

$result = mysqli_query($conn, $query);

$categories = [];
$quantities = [];

while($row = mysqli_fetch_assoc($result)) {
    $categories[] = $row['category_name'];
    $quantities[] = $row['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report and Analytics | PrintPro</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mina:wght@700&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

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

        .main-content {
            margin-left: 240px;
            width: 100%;
            padding: 40px;
        }

        .card-custom {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
    </style>
</head>

<body>

<!-- SIDEBAR (unchanged) -->
<div class="sidebar">
    <div class="logo mb-4">
        <img src="image_resources/logo.png" width="30">
        PrintPro
    </div>

    <a href="admin_analytics.php" class="nav-link active">Report and Analytics</a>
</div>

<!-- MAIN -->
<main class="main-content">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Report and Analytics</h3>
    </div>

    <div class="card-custom">

        <div class="row align-items-center">

            <!-- PIE CHART -->
            <div class="col-md-6 d-flex justify-content-center">
                <div style="width: 320px; height: 320px;">
                    <canvas id="myChart"></canvas>
                </div>
            </div>

            <!-- STATS -->
            <div class="col-md-6">
                <div class="stats-box mb-3">
                    <h5 class="fw-bold">Total Orders</h5>
                    <p>Dynamic chart replaces manual breakdown</p>
                </div>

                <button class="btn btn-orange w-100">
                    GENERATE PDF REPORT
                </button>
            </div>

        </div>
    </div>

</main>

<script>
const labels = <?php echo json_encode($categories); ?>;
const data = <?php echo json_encode($quantities); ?>;

const total = data.reduce((a, b) => a + parseInt(b), 0);

new Chart(document.getElementById('myChart'), {
    type: 'pie',
    data: {
        labels: labels,
        datasets: [{
            data: data,
            backgroundColor: [
                '#1c7ed6',
                '#69db7c',
                '#ffa94d',
                '#845ef7',
                '#ff6b6b',
                '#20c997',
                '#339af0',
                '#f783ac'
            ]
        }]
    },
    options: {
        plugins: {
            legend: {
                position: 'bottom'
            },
            datalabels: {
                formatter: (value, ctx) => {
                    let percent = (value / total * 100).toFixed(1) + "%";
                    return value + " (" + percent + ")";
                },
                color: '#000',
                font: {
                    weight: 'bold'
                }
            }
        }
    },
    plugins: [ChartDataLabels]
});
</script>

</body>
</html>