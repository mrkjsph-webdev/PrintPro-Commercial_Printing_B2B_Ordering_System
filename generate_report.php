<?php

require "db.php";
require "fpdf/fpdf.php";

/* PHILIPPINE TIMEZONE */
date_default_timezone_set('Asia/Manila');

/* TOTAL ORDERS */
$totalQuery = mysqli_query($conn,
"SELECT COUNT(*) AS total FROM orders");

$totalOrders =
mysqli_fetch_assoc($totalQuery)['total'];

/* COMPLETED */
$completedQuery = mysqli_query($conn,
"SELECT COUNT(*) AS total
FROM orders
WHERE order_status='completed'");

$completedOrders =
mysqli_fetch_assoc($completedQuery)['total'];

/* CANCELLED */
$cancelledQuery = mysqli_query($conn,
"SELECT COUNT(*) AS total
FROM orders
WHERE order_status='cancelled'");

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

/* PDF */

$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 20);

$pdf->Cell(0, 15,
'PrintPro Analytics Report',
0, 1, 'C');

$pdf->Ln(5);

$pdf->SetFont('Arial', '', 12);

$pdf->Cell(0, 10,
'Generated on: ' .
date("F d, Y h:i A"),
0, 1);

$pdf->Ln(5);

/* SUMMARY */

$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10,
'Order Summary',
0, 1);

$pdf->SetFont('Arial', '', 12);

$pdf->Cell(0, 10,
'Total Orders: ' .
$totalOrders,
0, 1);

$pdf->Cell(0, 10,
'Completed Orders: ' .
$completedOrders,
0, 1);

$pdf->Cell(0, 10,
'Cancelled Orders: ' .
$cancelledOrders,
0, 1);

$pdf->Ln(10);

/* PRODUCT ANALYTICS */

$pdf->SetFont('Arial', 'B', 14);

$pdf->Cell(0, 10,
'Product Analytics',
0, 1);

$pdf->SetFont('Arial', 'B', 12);

$pdf->Cell(120, 10,
'Product Name',
1);

$pdf->Cell(50, 10,
'Orders',
1);

$pdf->Ln();

$pdf->SetFont('Arial', '', 12);

while($row = mysqli_fetch_assoc($productQuery)) {

    $pdf->Cell(120, 10,
    $row['product_name'],
    1);

    $pdf->Cell(50, 10,
    $row['total'],
    1);

    $pdf->Ln();
}

$pdf->Output(
'D',
'PrintPro_Analytics_Report.pdf'
);

?>