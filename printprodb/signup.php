<?php
include 'db.php';
error_reporting(E_ERROR | E_PARSE);
$fname      = $_POST['firstName'] ?? '';
$mi         = $_POST['middleInitial'] ?? '';
$lname      = $_POST['lastName'] ?? '';
$email      = $_POST['email'] ?? '';
$contact    = $_POST['contact'] ?? '';
$password   = $_POST['password'] ?? '';
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
    header("Refresh:2; url=login.html");
    echo "Account created! Redirecting to login...";
    exit;
}else {
    echo "Error: " . $conn->error;
}
$conn->close();
?>