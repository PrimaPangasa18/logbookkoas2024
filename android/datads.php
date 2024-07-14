
<?php
require 'connect.php';
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON,TRUE);
$username = $input["username"];
$hasil1         = mysqli_query($conn,"SELECT nama FROM dosen where nip='$username'") or die(mysqli_error());
 $json_response = array();

if(mysqli_num_rows($hasil1)> 0){
while ($row = mysqli_fetch_assoc($hasil1)) {
    $json=array();
$json[] = $row;
}}
    $json_response["datads"]=$json;
echo json_encode($json_response);
mysqli_close($conn);

?>
