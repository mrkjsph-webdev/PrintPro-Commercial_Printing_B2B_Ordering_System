<?php
session_start();
require 'db.php';

// Only allow POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Invalid request');
}

// Get input (MATCHES signup field name: password)
$email = trim($_POST['email'] ?? '');
$password = $_POST['user_password'] ?? '';

// Validate inputs
if (empty($email) || empty($password)) {
    exit('All fields are required');
}

// Prepare query
$stmt = $conn->prepare("
    SELECT user_id, first_name, last_name, user_password 
    FROM users 
    WHERE email = ?
");
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

// Check user exists
if ($row = $result->fetch_assoc()) {

    // Verify hashed password
    if (password_verify($password, $row['user_password'])) {

        // Set session
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['first_name'] = $row['first_name'];
        $_SESSION['last_name'] = $row['last_name'];

        // Redirect to dashboard
        header("Location: client_dashboard.php");
        exit;

    } else {
        exit("Invalid email or password");
    }

} else {
    exit("Invalid email or password");
}

// Close connections
$stmt->close();
$conn->close();
?>