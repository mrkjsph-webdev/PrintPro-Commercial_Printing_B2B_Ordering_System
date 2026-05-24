<?php
include 'db.php';
error_reporting(E_ERROR | E_PARSE);

$fname      = $_POST['firstName'] ?? '';
$mi         = $_POST['middleInitial'] ?? '';
$lname      = $_POST['lastName'] ?? '';
$email      = $_POST['email'] ?? '';
$contact    = $_POST['contact'] ?? '';
$password   = $_POST['password'] ?? '';
$occupation = $_POST['occupation'] ?? '';

$username = strtolower($fname . $lname);

/* EMPTY CHECK */
if (empty($fname) || empty($lname) || empty($email) ||
    empty($contact) || empty($password) || empty($occupation)) {

    header("Location: signup.html?emptyFields=All+fields+are+required");
    exit;
}

/* PASSWORD CHECK */
if (strlen($password) < 8 ||
    !preg_match("/[a-zA-Z]/", $password) ||
    !preg_match("/[0-9]/", $password)) {

    header("Location: signup.html?error=Weak+password.+Password+must+be+at+least+8+characters+and+include+letters+and+numbers");
    exit;
}

/* EMAIL VALIDATION */
$allowed_domains = ['@gmail.com', '@outlook.com', '@yahoo.com'];
$valid_domain = false;

foreach ($allowed_domains as $domain) {
    if (substr($email, -strlen($domain)) === $domain) {
        $valid_domain = true;
        break;
    }
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !$valid_domain) {
    header("Location: signup.html?error=Invalid+email+address");
    exit;
}

/* CHECK DUPLICATE EMAIL */
$check = "SELECT * FROM users WHERE email='$email'";
$res = $conn->query($check);

if ($res->num_rows > 0) {
    header("Location: signup.html?error=Email+already+exists");
    exit;
}

/* HASH PASSWORD */
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

/* INSERT USER */
$sql = "INSERT INTO users 
(first_name, middle_initial, last_name, username, email, contact_number, user_password, occupation)
VALUES
('$fname', '$mi', '$lname', '$username', '$email', '$contact', '$hashed_password', '$occupation')";

if ($conn->query($sql) === TRUE) {
    header("Location: login.html?signup=success");
    exit();

} else {
    header("Location: signup.html?error=Error+creating+account");
    exit;
}

$conn->close();
?>