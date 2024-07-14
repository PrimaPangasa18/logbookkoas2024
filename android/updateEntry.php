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
$SEvaluasi= $input['SEvaluasi'];
$SRencana = $input['SRencana'];
$query_angkatan = "SELECT angkatan FROM biodata_mhsw WHERE nim='".$username."'";
$angkatan_result = mysqli_query($conn,$query_angkatan);
while ($angkatan_a = mysqli_fetch_assoc($angkatan_result)){
$angkatan = $angkatan_a["angkatan"];}
$query_grup    = "SELECT grup FROM biodata_mhsw WHERE nim='".$username."' AND angkatan='".$angkatan."'";
$grup_result = mysqli_query($conn,$query_grup);
while ($grup_a = mysqli_fetch_assoc($grup_result)){
$grup = $grup_a["grup"];}
    $query    = "UPDATE evaluasi SET evaluasi='".$SEvaluasi."', rencana='".$SRencana."' WHERE nim='".$username."' AND grup='".$grup."' AND stase='".$stase."' AND tanggal='".$tanggal."'";
    mysqli_query ($conn,$query);
$query_jp = "UPDATE jurnal_penyakit SET evaluasi=1 WHERE stase='".$stase."'";
$jp_result = mysqli_query($conn,$query_jp);
$query_jk = "UPDATE jurnal_ketrampilan SET evaluasi=1 WHERE stase='".$stase."'";
$jk_result = mysqli_query($conn,$query_jk);
?>