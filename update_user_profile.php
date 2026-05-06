<?php
session_start();
require 'db.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Get POST data
$first_name      = trim($_POST['first_name'] ?? '');
$middle_initial  = trim($_POST['middle_initial'] ?? '');
$last_name       = trim($_POST['last_name'] ?? '');
$contact_number  = trim($_POST['contact_number'] ?? '');
$email           = trim($_POST['email'] ?? '');
$occupation      = trim($_POST['occupation'] ?? '');

// Validate required fields
if (empty($first_name) || empty($last_name) || empty($email)) {
    echo json_encode(["status" => "error", "message" => "Required fields missing"]);
    exit;
}

// If email is changed, check if it's already taken by another user
$check = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
$check->bind_param("si", $email, $user_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Email is already taken by another account"
    ]);
    exit;
}

// Update user profile
$sql = "UPDATE users 
        SET first_name = ?, middle_initial = ?, last_name = ?, 
            contact_number = ?, email = ?, occupation = ?
        WHERE user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssssssi",
    $first_name,
    $middle_initial,
    $last_name,
    $contact_number,
    $email,
    $occupation,
    $user_id
);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Profile updated successfully"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Update failed"
    ]);
}

$stmt->close();
$conn->close();
?>