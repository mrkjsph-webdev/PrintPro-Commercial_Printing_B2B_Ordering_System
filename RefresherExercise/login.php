<?php
session_start();
include "users.php"; // contains your $users array

$email = $_POST['email'];
$password = $_POST['password'];

if (isset($users[$email]) && $users[$email]['password'] === $password) {
    $_SESSION['user'] = $users[$email]; // store user info
    header("Location: dashboard.php");
    exit;
} else {
    echo "Invalid login. <a href='index.html'>Try again</a>";
}
?>