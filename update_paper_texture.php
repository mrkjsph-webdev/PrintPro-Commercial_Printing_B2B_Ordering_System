<?php

require "db.php";

$id = $_GET['id'];
$stock = $_GET['stock'];
$available = $_GET['available'];

if($stock <= 0){
    $availability = 0;
}

$query = "
UPDATE paper_texture_inventory
SET
stock_quantity = '$stock',
is_available = '$available'
WHERE paper_texture_id = '$id'
";

mysqli_query($conn, $query);

header("Location: admin_inventory.php");

?>