<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

include 'db.php';

$dept = $_GET['dept'] ?? ''; 

$response = array();

if (empty($dept)) {
    echo json_encode(["error" => "Department is missing"]);
    exit;
}

// 1. Query para sa Counts
$countQuery = "SELECT 
    SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN status = 'Working' THEN 1 ELSE 0 END) as working,
    SUM(CASE WHEN status = 'Resolved' THEN 1 ELSE 0 END) as resolved
    FROM reports WHERE category = '$dept'";

$countResult = $conn->query($countQuery);
$counts = $countResult->fetch_assoc();

$response["counts"] = [
    "pending" => (string)($counts['pending'] ?? 0),
    "working" => (string)($counts['working'] ?? 0),
    "resolved" => (string)($counts['resolved'] ?? 0)
];

$reportQuery = "SELECT r.*, u.username AS reviewer_name, u.role AS reviewer_role 
                FROM reports r 
                LEFT JOIN users u ON r.reviewed_by = u.userID 
                WHERE r.category = '$dept' 
                ORDER BY r.date_submitted DESC";

$reportResult = $conn->query($reportQuery);

$reports = array();
if ($reportResult) {
    while($row = $reportResult->fetch_assoc()){
        $reports[] = array(
            "report_id"      => $row['report_id'],
            "category"       => $row['category'],
            "floor"          => $row['floor'],
            "spot"           => $row['spot'],
            "description"    => $row['description'],
            "status"         => $row['status'],
            "date_submitted" => $row['date_submitted'],
            "image_name"     => $row['image_name'],
            "reviewer_name"  => $row['reviewer_name'] ? $row['reviewer_name'] : "Not yet reviewed",
            "reviewer_role"  => $row['reviewer_role'] ? $row['reviewer_role'] : ""
        );
    }
}
$response["reports"] = $reports;

echo json_encode($response);

$conn->close();
?>