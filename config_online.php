<?php
$dbhost='localhost';
$dbuser='fkundipl_koas';
$dbpass='aris080975';
$dbname='fkundipl_logbook_koas';
$con = new mysqli($dbhost, $dbuser, $dbpass,$dbname) or die("Opps some thing went wrong");

date_default_timezone_set("Asia/Jakarta");
$tgl = date('Y-m-d');
$waktu = date("h-i-sa");
$tahun = substr($tgl, 0, 4);
$jalur="E-LOGBOOK KOAS PENDIDIKAN PROFESI DOKTER";


?>
