<?php
session_start();

if(!isset($_SESSION['reset_email'])){
    header("Location: forgot_password.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Verify OTP</title>

    <link href="bootstrap.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="image_resources/logo.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Mina:wght@400;700&family=Poppins:wght@300;400;600;800&display=swap"
        rel="stylesheet">

    <style>
        body {
            background: linear-gradient(to right, #161490, #4D57E4);
        }

        h2 {
            font-family: 'Poppins', sans-serif;
            color: #161490;
            font-weight: 600;
        }

        p {
            color: #666;
        }

        .card {
            border-radius: 15px;
            padding-left: 25px;
            padding-right: 25px;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #007bff;
        }

        .btn-primary {
            background-color: #FF7800;
            border-color: #FF7800;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #e66f00;
            border-color: #e66f00;
        }

        .index {
            color: #ffffff;
            font-weight: 600;
        }

        .otp-input {
            text-align: center;
            font-size: 1.5rem;
            letter-spacing: 8px;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            body {
                padding-top: 20px;
            }
        }
    </style>
</head>

<body style="font-family: 'Poppins', sans-serif;">
    <script src="bootstrap.bundle.js"></script>

    <div class="container mt-5 py-4">

        <a class="index text-decoration-none m-0" href="forgot_password.php">
            ← Back
        </a>

        <div id="alertBox"
            class="alert alert-danger position-fixed top-0 start-50 translate-middle-x mt-3 d-none shadow"
            style="z-index: 9999; width: 500px;">
        </div>

        <div id="successBox"
            class="alert alert-success position-fixed top-0 start-50 translate-middle-x mt-3 d-none shadow"
            style="z-index: 9999; width: 500px;">
        </div>

        <br><br>

        <div class="card py-5 mx-auto shadow border-0" style="max-width: 600px;">

            <div class="card-body">

                <h2 class="text-center py-1">
                    VERIFY OTP
                </h2>

                <p class="text-center">
                    Enter the 6-digit OTP generated for your account.
                </p>

                <form action="verify_otp_process.php" method="POST">

                    <div class="form-floating mt-4 mb-4">

                        <input type="text" name="otp" class="form-control otp-input" id="otp" placeholder="Enter OTP"
                            maxlength="6" required>

                        <label for="otp">OTP Code</label>

                    </div>

                    <div class="d-grid gap-3 col-8 mx-auto mt-4">

                        <button type="submit" class="btn btn-primary"> VERIFY OTP </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <script>
        const params = new URLSearchParams(window.location.search);

        const error = params.get("error");
        const success = params.get("success");

        if (error) {

            const alertBox = document.getElementById("alertBox");

            alertBox.classList.remove("d-none");
            alertBox.innerText = error;

            setTimeout(() => {
                alertBox.classList.add("d-none");
            }, 4000);
        }

        if (success) {

            const successBox = document.getElementById("successBox");

            successBox.classList.remove("d-none");
            successBox.innerText = success;

            setTimeout(() => {
                successBox.classList.add("d-none");
            }, 4000);
        }
    </script>

</body>

</html>