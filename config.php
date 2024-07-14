<?php
$dbhost='localhost';
$dbuser='root';
$dbpass='';
//$dbname='logbook_koas';
$dbname='fkundipl_logbook_koas';
//$dbname='logbookkoas2024';
$con = new mysqli($dbhost, $dbuser, $dbpass,$dbname) or die("Opps some thing went wrong");

date_default_timezone_set("Asia/Jakarta");
$tgl = date('Y-m-d');
$waktu = date("h-i-sa");
$tahun = substr($tgl, 0, 4);
$jalur="E-LOGBOOK KOAS PENDIDIKAN PROFESI DOKTER";

?>
