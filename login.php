<?php
session_start();
include 'db.php';
error_reporting(E_ERROR | E_PARSE);

$email = $_POST['email'] ?? '';
$password = $_POST['user_password'] ?? '';

$admin_email = "admin@gmail.com";
$admin_password = "admin123";

/* CHECK ADMIN LOGIN FIRST */
if ($email === $admin_email && $password === $admin_password) {

    $_SESSION['admin'] = true;
    $_SESSION['admin_email'] = $admin_email;

    header("Location: admin_dashboard.php");
    exit();
}

if (empty($email) || empty($password)) {
    header("Location: login.html?emptyFields=All+fields+are+required");
    exit;
}

$allowed_domains = ['@gmail.com', '@outlook.com', '@yahoo.com'];
$valid_domain = false;

foreach ($allowed_domains as $domain) {
    if (substr($email, -strlen($domain)) === $domain) {
        $valid_domain = true;
        break;
    }
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !$valid_domain) {
    header("Location: login.html?error=Invalid+email+address");
    exit;
}

/* GET USER BY EMAIL ONLY */
$sql = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

    $row = $result->fetch_assoc();

    /* VERIFY HASHED PASSWORD */
    if (password_verify($password, $row['user_password'])) {

        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['first_name'] = $row['first_name'];
        $_SESSION['last_name'] = $row['last_name'];

        header("Location: client_dashboard.php");
        exit;

    } else {
        header("Location: login.html?error=Invalid+email+or+password");
        exit;
    }

} else {
    header("Location: login.html?error=Invalid+email+or+password");
    exit;
}
?>