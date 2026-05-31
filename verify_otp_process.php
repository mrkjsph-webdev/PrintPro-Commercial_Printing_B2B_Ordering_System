<?php
session_start();

if(!isset($_SESSION['reset_otp'])){
    header("Location: forgot_password.php");
    exit();
}

$userOTP = trim($_POST['otp']);

if(time() > $_SESSION['otp_expiry']){

    session_destroy();

    header(
    "Location: verify_otp.php?error=OTP expired. Please request a new OTP."
    );

    exit();
}

if($userOTP == $_SESSION['reset_otp']){

    $_SESSION['otp_verified'] = true;

  header(
    "Location: reset_password.php?success=OTP verified successfully."
    );
exit();
}

header(
    "Location: verify_otp.php?error=Invalid OTP. Please try again."
);
exit();