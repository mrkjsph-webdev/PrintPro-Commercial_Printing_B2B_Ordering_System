<?php
include 'db.php';
error_reporting(E_ERROR | E_PARSE);

header('Content-Type: application/json');

$email        = $_POST['email'] ?? '';
$new_password = $_POST['new_password'] ?? '';

if(empty($email) || empty($new_password)) {
    echo json_encode(["status" => "error", "message" => "Please fill in all fields"]);
    exit;
}

$check = "SELECT * FROM users WHERE email='$email'";
$res = $conn->query($check);

if($res->num_rows > 0) {
    $update = "UPDATE users SET user_password='$new_password' WHERE email='$email'";
    
    if($conn->query($update) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Password updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Update failed: " . $conn->error]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Email does not match our records"]);
}

$conn->close();
?>