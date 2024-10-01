
<?php
require 'connect.php';

require 'verifiytoken.php';
require './vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

// Verify the token before proceeding
$userData = verifyToken(); // This will exit the script if the token is invalid

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON,TRUE);
$username = $input["username"];
$hasil1         = mysqli_query($conn,"SELECT nama,foto FROM biodata_mhsw where nim='$username'") or die(mysqli_error());
 $json_response = array();
if(mysqli_num_rows($hasil1)> 0){
while ($row = mysqli_fetch_assoc($hasil1)) {
 $json=array();
$json[] = $row;
}}
    $json_response["data1"]=$json;
echo json_encode($json_response);
mysqli_close($conn);



?>
