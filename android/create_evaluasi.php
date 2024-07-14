<?php
$response = array();
include 'connect.php';
 
//Get the input request parameters
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array
 
//Check for Mandatory parameters
$username = $input['username'];
$angkatana = $input['angkatan'];
$stase    = $input['stase'];
$tanggal  = $input['tanggal'];
$query_angkatan = "SELECT angkatan FROM biodata_mhsw WHERE nim='".$username."'";
$angkatan_result = mysqli_query($conn,$query_angkatan);
while ($angkatan_a = mysqli_fetch_assoc($angkatan_result)){
$angkatan = $angkatan_a["angkatan"];}
$query_grup    = "SELECT grup FROM biodata_mhsw WHERE nim='".$username."' AND angkatan='".$angkatan."'";
$grup_result = mysqli_query($conn,$query_grup);
while ($grup_a = mysqli_fetch_assoc($grup_result)){
$grup = $grup_a["grup"];}
    $query    = "INSERT INTO evaluasi (nim,angkatan,grup,stase,tanggal) 
    SELECT * FROM (SELECT '".$username."','".$angkatan."','".$grup."','".$stase."','".$tanggal."') AS temp
    WHERE NOT EXISTS(SELECT * FROM evaluasi WHERE nim='".$username."' AND angkatan='".$angkatan."' 
    AND grup='".$grup."' AND stase='".$stase."' AND tanggal='".$tanggal."')";
    mysqli_query ($conn,$query);
?>