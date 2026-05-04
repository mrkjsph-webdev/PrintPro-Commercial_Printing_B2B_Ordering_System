<?php
session_start();
include 'db.php';
error_reporting(E_ERROR | E_PARSE);

header('Content-Type: application/json');

$email = $_REQUEST['email'] ?? '';
$password = $_REQUEST['user_password'] ?? '';

if(empty($email) || empty($password)) {
    echo json_encode(["status" => "error", "message" => "All fields are required"]);
    exit;
}

$sql = "SELECT * FROM users WHERE email='$email' AND user_password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    $_SESSION['user_id'] = $row['user_id'];
    $_SESSION['first_name'] = $row['first_name'];

    header("Refresh:2; url=client_dashboard.php");
    echo "Login Successfully! Redirecting to homepage...";
    exit;

} else {
    echo json_encode(["status" => "error", "message" => "Invalid email or password"]); 
}
?>