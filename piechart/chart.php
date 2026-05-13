<?php
require_once 'db.php'; // Include your database connection file

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
    <title>Chart Example</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
</head>
<body>
    <div style="width: 600px; height: 400px;">
        <canvas id="myChart"></canvas>
    </div>

    <script>
        const labels = <?php echo json_encode($categories); ?>;
        const data = <?php echo json_encode($quantities); ?>;

        const total = data.reduce((sum, val) => sum + parseInt(val), 0);

        new Chart(document.getElementById('myChart'), {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(128, 2, 30, 0.2)',
                        'rgba(17, 99, 155, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgb(151, 54, 54)',
                        'rgb(14, 105, 166)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                plugins: {
                    datalabels: {
                        formatter: (value, ctx) => {
                            let percentage = (value / total * 100).toFixed(1) + "%";
                            let label = ctx.chart.data.labels[ctx.dataIndex];
                            return label + "\n" + value + " (" + percentage + ")";
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
