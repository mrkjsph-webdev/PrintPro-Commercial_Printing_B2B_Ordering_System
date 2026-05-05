<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "barkitdb"; 

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {

    die("DATABASE CONNECTION ERROR: " . $conn->connect_error);
}
?>