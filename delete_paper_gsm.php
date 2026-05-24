<?php

require "db.php";

$id = $_GET['id'];

mysqli_query($conn,"
DELETE FROM paper_gsm_inventory
WHERE paper_gsm_id = '$id'
");

header("Location: admin_inventory.php");

?>