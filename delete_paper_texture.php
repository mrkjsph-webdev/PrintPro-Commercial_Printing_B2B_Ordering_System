<?php

require "db.php";

$id = $_GET['id'];

mysqli_query($conn,"
DELETE FROM paper_texture_inventory
WHERE paper_texture_id = '$id'
");

header("Location: admin_inventory.php");

?>