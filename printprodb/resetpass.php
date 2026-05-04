<?php
include 'db.php';
$email        = $_POST['email'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if(empty($email) || empty($new_password) || empty($confirm_password)) {
    echo "Please fill in all fields";
    exit;
}

if($new_password !== $confirm_password){
    echo "Passwords do not match";
    exit;
}

if(empty($email) || empty($new_password)) {
    echo "Please fill in all fields";
    exit;
}

$check = "SELECT * FROM users WHERE email='$email'";
$res = $conn->query($check);

if($res->num_rows > 0) {
    $update = "UPDATE users SET user_password='$new_password' WHERE email='$email'";
    
    if($conn->query($update) === TRUE) {
        header("Location: login.html?reset=success");
        echo "Password updated successfully";
        exit;
    } else {
        echo "Update failed";
    }
} else {
    echo "Email does not match our records";
}

$conn->close();
?>