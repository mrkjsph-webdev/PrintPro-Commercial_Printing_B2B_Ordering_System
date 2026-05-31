<?php
session_start();
include 'db.php';

$email = trim($_POST['email']);
$contact = trim($_POST['contact_number']);

$stmt = $conn->prepare(
    "SELECT user_id
     FROM users
     WHERE email = ?
     AND contact_number = ?"
);

$stmt->bind_param("ss", $email, $contact);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows === 0){

    header(
      "Location: forgot_password.php?error=The information you provided is incorrect. Account not found."
    );

    exit();
}

$otp = rand(100000, 999999);

$_SESSION['reset_email'] = $email;
$_SESSION['reset_otp'] = $otp;
$_SESSION['otp_verified'] = false;
$_SESSION['otp_expiry'] = time() + 300; // 5 minutes

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="image_resources/logo.png">
    <title>OTP Generated</title>
    <link href="bootstrap.css" rel="stylesheet">

    <style>
        body{
            background: linear-gradient(to right, #161490, #4D57E4);
            font-family: 'Poppins', sans-serif;
        }

        .card{
            border-radius:15px;
        }

        .otp-code{
            font-size:2rem;
            font-weight:bold;
            letter-spacing:8px;
            color:#161490;
        }

        .btn-primary{
            background:#FF7800;
            border-color:#FF7800;
        }

        .btn-primary:hover{
            background:#e66f00;
            border-color:#e66f00;
        }
    </style>
</head>

<body>

<div class="container vh-100 d-flex align-items-center justify-content-center">

    <div class="card shadow border-0 p-5 text-center"
         style="max-width:550px; width:100%;">

        <h2 class="mb-3">OTP Generated</h2>

        <p class="text-muted">
            OTP Verification
        </p>

        <div class="otp-code my-4">
            <?php echo $otp; ?>
        </div>

        <div class="alert alert-warning">
            This OTP will expire in 5 minutes.
        </div>

        <a href="verify_otp.php"
           class="btn btn-primary w-100">
            Continue to Verification
        </a>

    </div>

</div>

</body>
</html>
<?php
exit();