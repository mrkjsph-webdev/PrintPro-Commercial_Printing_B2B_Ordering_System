<?php

require "db.php";

$id = $_GET['id'];
$stock = $_GET['stock'];
$available = $_GET['available'];

if($stock <= 0){
    $availability = 0;
}

$query = "
UPDATE paper_size_inventory
SET
    stock_quantity = '$stock',
    is_available = '$available'
WHERE paper_size_id = '$id'
";

$result = mysqli_query($conn, $query);

if($result) {

    header("Location: admin_inventory.php");
    exit;

} else {

    echo mysqli_error($conn);

}
?>