<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "printprodb"; 

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {

    die("DATABASE CONNECTION ERROR: " . $conn->connect_error);
}
else
{
    echo "Connected successfully to printprodb!";
}

?>