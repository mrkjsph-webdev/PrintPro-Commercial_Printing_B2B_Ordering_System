<?php
session_start();
require "db.php";

header('Content-Type: application/json');

// CHECK LOGIN
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Unauthorized access"
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];

// FILTERS (from AJAX / JS)
$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$status = isset($_GET['status']) ? trim($_GET['status']) : "";

// BASE QUERY
$sql = "SELECT order_id, order_date, order_status, total_amount
        FROM orders
        WHERE user_id = ?";

        $isHistory = isset($_GET['history']);
        if ($isHistory) {
            $sql .= " AND (order_status = 'completed' OR order_status = 'cancelled')";
        }

$params = [$user_id];
$types = "i";

// STATUS FILTER
if (!empty($status)) {
    $sql .= " AND order_status = ?";
    $params[] = $status;
    $types .= "s";
}

// SEARCH FILTER (order ID or status)
if (!empty($search)) {
    $sql .= " AND (order_id LIKE ? OR order_status LIKE ?)";
    $searchParam = "%$search%";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $types .= "ss";
}

$sql .= " ORDER BY order_date DESC";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode([
        "status" => "error",
        "message" => "Query preparation failed"
    ]);
    exit;
}

// BIND PARAMETERS
$stmt->bind_param($types, ...$params);
$stmt->execute();

$result = $stmt->get_result();

$orders = [];

while ($row = $result->fetch_assoc()) {


    $statusClass = "status-pending";
        if (strtolower($row['order_status']) === "processing") {
            $statusClass = "status-processing";
        } 
        elseif (strtolower($row['order_status']) === "completed") {
            $statusClass = "status-completed";
        }
        elseif (strtolower($row['order_status']) === "ready for pickup") {
            $statusClass = "status-ready";
        }
        elseif (strtolower($row['order_status']) === "cancelled") {
            $statusClass = "status-cancelled";
        }

    $orders[] = [
        "order_id" => $row["order_id"],
        "order_date" => $row["order_date"],
        "order_status" => $row["order_status"],
        "status_class" => $statusClass,
        "total_amount" => $row["total_amount"]
    ];
}

echo json_encode([
    "status" => "success",
    "data" => $orders
]);

$stmt->close();
$conn->close();
?>