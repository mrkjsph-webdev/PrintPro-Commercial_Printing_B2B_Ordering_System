<?php
header('Content-Type: application/json');
include 'db.php';

$userID = $_GET['userID'] ?? '';

if (empty($userID)) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT r.*, u.username AS reviewer_name, u.role AS reviewer_role 
        FROM reports r 
        LEFT JOIN users u ON r.reviewed_by = u.userID 
        WHERE r.userID = '$userID' 
        ORDER BY r.report_id DESC";

$result = $conn->query($sql);
$reports = array();

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $reports[] = array(
            'report_id'      => $row['report_id'],    
            'category'       => $row['category'],
            'floor'          => $row['floor'],
            'spot'           => $row['spot'],
            'description'    => $row['description'],
            'status'         => $row['status'],
            'date_submitted' => $row['date_submitted'], 
            'image_name'     => $row['image_name'],
            'reviewer_name'  => $row['reviewer_name'] ? $row['reviewer_name'] : "Not yet reviewed",
            'reviewer_role'  => $row['reviewer_role'] ? $row['reviewer_role'] : ""      
        );
    }
}

echo json_encode($reports);
$conn->close();
?>