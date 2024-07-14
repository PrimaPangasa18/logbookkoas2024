<?php
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array
include_once "connect.php";
if($conn){	
$username= $input['username'];
// sesuiakan ip address laptop/pc atau URL server
$query="SELECT foto FROM biodata_mhsw WHERE nim='$username'";
$hps=mysqli_fetch_assoc(mysqli_query($conn,$query));
$path = "../foto/$hps[foto]";  
unlink($path);

echo json_encode(array('response'=>'Deleting Sucessfull'));  
}
mysqli_close($conn);
?>	