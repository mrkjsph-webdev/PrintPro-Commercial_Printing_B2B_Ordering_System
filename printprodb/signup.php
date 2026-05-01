<?php
include 'db.php';
error_reporting(E_ERROR | E_PARSE);
$username = $_GET['username'] ?? '';
$email    = $_GET['email'] ?? '';
$password = $_GET['user_password'] ?? '';

if(empty($username) || empty($email) || empty($password)){
    echo "All fields are required";
    exit;
}

$check = "SELECT * FROM users WHERE email='$email'";
$res = $conn->query($check);
if($res->num_rows > 0){
    echo "Email already exists";
    exit;
}

// Insert new user
$sql = "INSERT INTO users (username, email, password) 
        VALUES ('$username', '$email', '$password')";

if ($conn->query($sql) === TRUE) {
    echo "success";
} else {
    echo "Error: " . $conn->error;
}
?>