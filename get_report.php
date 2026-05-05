<?php
header('Content-Type: application/json');
include 'db.php';

$sql = "SELECT * FROM reports ORDER BY id DESC";

$result = $conn->query($sql);
$reports = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $reports[] = array(
            'report_id'          => $row['report_id'],
            'category'    => $row['category'],
            'floor'       => $row['floor'],
            'spot'        => $row['spot'],
            'description' => $row['description'],
            'date_submitted' => $row['date_submitted'] ?? date("Y-m-d"), 
            'status'      => $row['status'],
            'image'       => $row['image_name']
        );
    }
    echo json_encode($reports);
} else {
    echo json_encode(array());
}

$conn->close();
?>