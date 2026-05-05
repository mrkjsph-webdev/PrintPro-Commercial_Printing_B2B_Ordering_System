<?php
include 'db.php';

$report_id = $_POST['report_id'] ?? '';
$new_status = $_POST['new_status'] ?? '';
$adminID = $_POST['adminID'] ?? '';

if (empty($report_id) || empty($adminID)) {
    echo "Error: Missing Data (ID: $report_id, Admin: $adminID)";
    exit;
}

$sql = "UPDATE reports SET status = '$new_status', reviewed_by = '$adminID' WHERE report_id = '$report_id'";

if ($conn->query($sql) === TRUE) {
    echo "success";
} else {
    echo "MySQL Error: " . $conn->error;
}

$conn->close();
?>