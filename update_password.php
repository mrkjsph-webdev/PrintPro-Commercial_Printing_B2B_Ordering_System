<?php
session_start();
include 'db.php';

if(
    !isset($_SESSION['otp_verified'])
    ||
    $_SESSION['otp_verified'] !== true
){
    exit();
}

$email = $_SESSION['reset_email'];

$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

if($new_password !== $confirm_password){

    header(
      "Location: reset_password.php?error=Mismatch"
    );

    exit();
}

$hashed_password =
password_hash(
    $new_password,
    PASSWORD_DEFAULT
);

$stmt = $conn->prepare(
    "UPDATE users
     SET user_password = ?
     WHERE email = ?"
);

$stmt->bind_param(
    "ss",
    $hashed_password,
    $email
);

$stmt->execute();

unset($_SESSION['reset_email']);
unset($_SESSION['reset_otp']);
unset($_SESSION['otp_verified']);
unset($_SESSION['otp_expiry']);

header("Location: login.html?reset=success");
exit();