<?php
include 'db.php';
error_reporting(E_ERROR | E_PARSE);
$fname      = $_POST['first_name'] ?? '';
$mi         = $_POST['middle_initial'] ?? '';
$lname      = $_POST['last_name'] ?? '';
$email      = $_POST['email'] ?? '';
$contact    = $_POST['contact_number'] ?? '';
$password   = $_POST['user_password'] ?? '';
$occupation = $_POST['occupation'] ?? '';

$username = strtolower($fname . $lname);

if(empty($fname) || empty($lname) || empty($email) || 
empty($contact) || empty($password) || empty($occupation)) {
    echo "All fields are required";
    exit;
}

if(!filter_var($email, FILTER_VALIDATE_EMAIL) || !str_ends_with($email, '@gmail.com')) {
    echo "Please use a valid Gmail address.";
    exit;
}

// check if email already exists
$check = "SELECT * FROM users WHERE email='$email'";
$res = $conn->query($check);
if($res->num_rows > 0){
    echo "Email already exists";
    exit;
}

// Insert new user
$sql = "INSERT INTO users (first_name, middle_initial, last_name, username, email, contact_number, user_password, occupation) 
        VALUES ('$fname', '$mi', '$lname', '$username', 
        '$email', '$contact', '$password', '$occupation')";

if ($conn->query($sql) === TRUE) {
    echo "success";
} else {
    echo "Error: " . $conn->error;
}
$conn->close();
?>