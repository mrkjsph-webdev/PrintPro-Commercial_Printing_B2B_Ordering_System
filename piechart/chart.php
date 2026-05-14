<?php
require_once 'db.php';

// Fetch chart data
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

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Data Labels -->
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

    <!-- html2canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <!-- jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <style>
        body{
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .chart-container{
            width: 600px;
            height: 400px;
            margin-bottom: 20px;
        }

        button{
            padding: 10px 20px;
            border: none;
            background: #0d6efd;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover{
            background: #0b5ed7;
        }
    </style>
</head>
<body>

    <div id="reportContent">
        <h2>Product Category Report</h2>

        <div class="chart-container">
            <canvas id="myChart"></canvas>
        </div>

        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Total Quantity</th>
                </tr>
            </thead>

            <tbody>
                <?php
                for($i = 0; $i < count($categories); $i++){
                    echo "
                    <tr>
                        <td>{$categories[$i]}</td>
                        <td>{$quantities[$i]}</td>
                    </tr>
                    ";
                }
                ?>
            </tbody>
        </table>
    </div>

    <br>

    <button onclick="generatePDF()">Generate PDF Report</button>

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
                responsive: true,

                plugins: {
                    datalabels: {
                        formatter: (value, ctx) => {

                            let percentage = (
                                value / total * 100
                            ).toFixed(1) + "%";

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

        async function generatePDF(){

            const { jsPDF } = window.jspdf;

            const report = document.getElementById('reportContent');

            const canvas = await html2canvas(report, {
                scale: 2
            });

            const imgData = canvas.toDataURL('image/png');

            const pdf = new jsPDF('p', 'mm', 'a4');

            const pdfWidth = pdf.internal.pageSize.getWidth();

            const imgWidth = pdfWidth - 20;

            const imgHeight = (canvas.height * imgWidth) / canvas.width;

            pdf.text("Product Category Report", 10, 10);

            pdf.addImage(
                imgData,
                'PNG',
                10,
                20,
                imgWidth,
                imgHeight
            );

            pdf.save("category_report.pdf");
        }
    </script>

</body>
</html>

