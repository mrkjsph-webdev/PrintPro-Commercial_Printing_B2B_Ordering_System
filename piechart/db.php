<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "pie_db"; 

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {

    die("DATABASE CONNECTION ERROR: " . $conn->connect_error);
}
else
{
    // echo "PIE_DB DATABASE CONNECTION SUCCESSFUL";
}

?>