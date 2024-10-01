<?php
require 'connect.php';
//security using jwt token for header

require 'verifiytoken.php';
require './vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

// Verify the token before proceeding
$userData = verifyToken(); // This will exit the script if the token is invalid

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON,TRUE);
$username = $input["username"];
$hasil1         = mysqli_query($conn,"SELECT biodata_mhsw.id,biodata_mhsw.nama,biodata_mhsw.tanggal_lahir,biodata_mhsw.alamat,biodata_mhsw.no_hp,biodata_mhsw.email,biodata_mhsw.nama_ortu,biodata_mhsw.alamat_ortu,biodata_mhsw.no_hportu,biodata_mhsw.foto,admin.password FROM biodata_mhsw INNER JOIN admin on biodata_mhsw.nim=admin.username where nim='$username'");
 $json_response = array();
if(mysqli_num_rows($hasil1)>= 0){
while ($row = mysqli_fetch_assoc($hasil1)) {
 $json=array();
$json[] = $row;
}
}
    $json_response["datalhr"]=$json;
echo json_encode($json_response);
mysqli_close($conn);



?>
