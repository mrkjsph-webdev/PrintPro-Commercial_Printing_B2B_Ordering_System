<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "printprodb";

$conn = new mysqli($host, $user, $pass, $db);

// Stop execution if connection fails
if ($conn->connect_error) {
    die("Database connection failed.");
}
?>