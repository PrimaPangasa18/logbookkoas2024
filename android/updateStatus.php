<?php
require 'connect.php';
$username = $_GET["username"];
$stase = $_GET["stase"];
$status = $_GET["status"];
 $strSQL = "UPDATE $stase SET status='".$status."' WHERE nim='".$username."' ";
 $objQuery = mysqli_query($conn,$strSQL);
?>