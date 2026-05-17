<?php

require "db.php";

$type = $_POST['inventory_type'];
$name = $_POST['inventory_name'];
$stock = $_POST['stock_quantity'];

if($stock > 0){
    $available = 1;
}else{
    $available = 0;
}

if($type == "size"){

    mysqli_query($conn,"
    INSERT INTO paper_size_inventory
    (paper_size, stock_quantity, is_available)

    VALUES
    ('$name','$stock','$available')
    ");

}

elseif($type == "gsm"){

    mysqli_query($conn,"
    INSERT INTO paper_gsm_inventory
    (paper_gsm, stock_quantity, is_available)

    VALUES
    ('$name','$stock','$available')
    ");

}

elseif($type == "texture"){

    mysqli_query($conn,"
    INSERT INTO paper_texture_inventory
    (paper_texture, stock_quantity, is_available)

    VALUES
    ('$name','$stock','$available')
    ");

}

header("Location: admin_inventory.php");

?>