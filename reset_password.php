<?php
session_start();

if(
    !isset($_SESSION['otp_verified'])
    ||
    $_SESSION['otp_verified'] !== true
){
    header("Location: forgot_password.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
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

    a:hover {
      color: #FF7800;
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

    @media (max-width: 768px) {
      body {
        padding-top: 20px;
      }
    }
  </style>
</head>

<body class="justify-content-center align-items-center bg-light" style="font-family: 'Poppins', sans-serif;">
  <div class="container mt-5 py-4">
    <a class="index text-decoration-none m-0" href="login.html">← Back to Login</a><br>
    <!-- Alert box for error messages -->
    <div id="alertBox" class="alert alert-danger position-fixed top-0 start-50 translate-middle-x mt-3 d-none shadow"
      style="z-index: 9999; width: 500px;">
    </div>
    <div id="emptyBox" class="alert alert-warning position-fixed top-0 start-50 translate-middle-x mt-3 d-none shadow"
      style="z-index: 9999; width: 500px;">
    </div>
    <div id="successBox" class="alert alert-success position-fixed top-0 start-50 translate-middle-x mt-3 d-none shadow"
    style="z-index: 9999; width: 500px;">
  </div>
    <br>
    <!-- Reset password form -->
    <div class="card py-5 mx-auto shadow border-0" style="max-width: 600px;">
      <div class="card-body">
        <h2 class="text-center py-1">RESET PASSWORD</h2>
        <p class="text-center">Enter your new password and confirm it.</p>

        <form action="update_password.php" method="POST">

          <div class="form-floating mt-4 mb-3">
            <input type="password" name="new_password" class="form-control" id="new_password" placeholder="New Password"
              required>

            <label for="new_password">New Password</label>
          </div>

          <div class="form-floating mt-4 mb-3">
            <input type="password" name="confirm_password" class="form-control" id="confirm_password"
              placeholder="Confirm Password" required>

            <label for="confirm_password">Confirm Password</label>
          </div>

          <div class="mb-3">
            <div class="form-check">
              <input type="checkbox" class="form-check-input" id="showPassword" onclick="togglePassword()">

              <label class="form-check-label" for="showPassword">
                Show Password
              </label>
            </div>
          </div>

          <div class="d-grid gap-3 col-8 mx-auto mt-4">
            <button type="submit" class="btn btn-primary">
              RESET PASSWORD
            </button>
          </div>

        </form>
      </div>
    </div>
  </div>
  <script>
    function togglePassword() {
      const pass1 = document.getElementById("new_password");
      const pass2 = document.getElementById("confirm_password");

      const type = pass1.type === "password" ? "text" : "password";
      pass1.type = type;
      pass2.type = type;
    }
    const params = new URLSearchParams(window.location.search);
    const error = params.get("error");
    const success = params.get("success");
    const emptyFields = params.get("emptyFields");

    if (emptyFields) {
      const emptyBox = document.getElementById("emptyBox");
      emptyBox.classList.remove("d-none");
      emptyBox.innerText = "Please fill in all fields.";
      setTimeout(() => { emptyBox.classList.add("d-none"); }, 4000);
    }
    if (params.get("reset") === "success") {
      alert("Password successfully reset!");
    } else if (error) {
      const alertBox = document.getElementById("alertBox");
      alertBox.classList.remove("d-none");
      alertBox.innerText = error;
      setTimeout(() => { alertBox.classList.add("d-none"); }, 4000);

    }

    if (success) {
      const successBox = document.getElementById("successBox");
      successBox.classList.remove("d-none");
      successBox.innerText = success;
      
      setTimeout(() => { 
        successBox.classList.add("d-none");}, 4000);
    }
  </script>
</body>

</html>