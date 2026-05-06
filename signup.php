<?php
session_start();
require 'db.php';

// Only accept POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Invalid request');
}

// Sanitize input
$fname      = trim($_POST['firstName'] ?? '');
$mi         = trim($_POST['middleInitial'] ?? '');
$lname      = trim($_POST['lastName'] ?? '');
$email      = trim($_POST['email'] ?? '');
$contact    = trim($_POST['contact'] ?? '');
$password   = $_POST['password'] ?? '';
$occupation = trim($_POST['occupation'] ?? '');

// Generate username
$username = strtolower($fname . $lname);

// Validate required fields
if (!$fname || !$lname || !$email || !$contact || !$password || !$occupation) {
    exit('All fields are required');
}

// Validate Gmail only
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !str_ends_with($email, '@gmail.com')) {
    exit('Please use a valid Gmail address');
}

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check if email already exists
$stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->close();
    exit('Email already exists');
}
$stmt->close();

// Insert new user
$stmt = $conn->prepare("
    INSERT INTO users 
    (first_name, middle_initial, last_name, username, email, contact_number, user_password, occupation) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "ssssssss",
    $fname,
    $mi,
    $lname,
    $username,
    $email,
    $contact,
    $hashed_password,
    $occupation
);

if ($stmt->execute()) {
    header("Location: login.html");
    exit;
} else {
    echo "Something went wrong. Please try again.";
}

$stmt->close();
$conn->close();
?>