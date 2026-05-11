<?php
include 'db.php';
$email        = $_POST['email'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if(empty($email) || empty($new_password) || empty($confirm_password)) {
    header("Location: reset_password.html?error=Invalid:+All+fields+are+required");
    exit;
}

if($new_password !== $confirm_password){
    header("Location: reset_password.html?error=Passwords+do+not+match");
    exit;
}

if(empty($email) || empty($new_password)) {
    header("Location: reset_password.html?error=Invalid:+All+fields+are+required");
    exit;
}

$check = "SELECT * FROM users WHERE email='$email'";
$res = $conn->query($check);

if($res->num_rows > 0) {
    $update = "UPDATE users SET user_password='$new_password' WHERE email='$email'";
    
    if($conn->query($update) === TRUE) {
        header("Location: login.html?reset=success&message=Password updated successfully");
        exit;
    } else {
        header("Location: reset_password.html?error=Update failed");
        exit;
    }
} else {
    header("Location: reset_password.html?error=Email does not match our records");
    exit;
}

$conn->close();
?>