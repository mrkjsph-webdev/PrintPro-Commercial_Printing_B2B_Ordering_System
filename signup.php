<?php
include 'db.php';
error_reporting(E_ERROR | E_PARSE);

$username = $_POST['username'] ?? '';
$email    = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$studID   = $_POST['studID'] ?? '';

// Validate fields
if(empty($username) || empty($email) || empty($password) || empty($studID)){
    echo "All fields are required";
    exit;
}

// Check email format
if(!filter_var($email, FILTER_VALIDATE_EMAIL) || !str_ends_with($email, '@students.nu-dasma.edu.ph')){
    echo "Use your allocated University Email.";
    exit;
}

// Check if email already exists
$check = "SELECT * FROM users WHERE email='$email'";
$res = $conn->query($check);
if($res->num_rows > 0){
    echo "Email already exists";
    exit;
}

// Insert new user
$sql = "INSERT INTO users (username, email, password, studID) 
        VALUES ('$username', '$email', '$password', '$studID')";

if ($conn->query($sql) === TRUE) {
    echo "success";
} else {
    echo "Error: " . $conn->error;
}
?>

