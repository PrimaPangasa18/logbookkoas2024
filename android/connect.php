<?php
$db_name="fkundipl_logbook_koas";
$mysql_username="root";
$mysql_password="";
$server_name="localhost";

$conn=mysqli_connect($server_name,$mysql_username,$mysql_password,$db_name);
date_default_timezone_set("Asia/Jakarta");
$tgl = date('Y-m-d');


?>