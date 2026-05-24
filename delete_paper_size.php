<?php

require "db.php";

$id = $_GET['id'];

mysqli_query($conn,"
DELETE FROM paper_size_inventory
WHERE paper_size_id = '$id'
");

header("Location: admin_inventory.php");

?>