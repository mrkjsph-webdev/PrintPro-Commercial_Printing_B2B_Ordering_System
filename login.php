<?php
include 'db.php';
error_reporting(E_ERROR | E_PARSE);

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$studID = $_POST['studID'] ?? '';

$sql = "SELECT * FROM users WHERE email='$email' AND password='$password' AND studID='$studID'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode([
        "userID" => $row['userID'],
        "username" => $row['username'], 
        "email" => $row['email'],
        "studID" => $row['studID'],
        "role" => $row['role'],
        "dept_tag" => $row['dept_tag']
    ]);
} else {
    echo "error"; 
}
?>