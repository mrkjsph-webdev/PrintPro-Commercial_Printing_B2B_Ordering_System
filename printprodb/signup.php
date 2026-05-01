<?php
include 'db.php';

$firstName = $_POST['firstName'] ?? '';
$middleInitial = $_POST['middleInitial'] ?? '';
$lastName = $_POST['lastName'] ?? '';
$email = $_POST['email'] ?? '';
$contact = $_POST['contact'] ?? '';
$password = $_POST['password'] ?? '';
$occupation = $_POST['occupation'] ?? '';

$username = $firstName . " " . $middleInitial . " " . $lastName;

if (
    empty($firstName) ||
    empty($lastName) ||
    empty($email) ||
    empty($contact) ||
    empty($password) ||
    empty($occupation)
) {
    echo "All fields are required";
    exit;
}

// check email
$check = "SELECT * FROM users WHERE email='$email'";
$res = $conn->query($check);

if ($res->num_rows > 0) {
    echo "Email already exists";
    exit;
}

// insert
$sql = "INSERT INTO users (username, email, contact, password, occupation)
        VALUES ('$username', '$email', '$contact', '$password', '$occupation')";

if ($conn->query($sql) === TRUE) {
    echo "success";
} else {
    echo "Error: " . $conn->error;
}
?>