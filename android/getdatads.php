
<?php
require 'connect.php';
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON,TRUE);
$username = $input["username"];
$hasil1         = mysqli_query($conn,"SELECT nama,gelar FROM dosen  where nip='$username'")or mysqli_error();
$hasil2         = mysqli_query($conn,"SELECT password FROM admin  where username='$username'")or mysqli_error();
 $json_response = array();

if(mysqli_num_rows($hasil1)> 0){
while ($row = mysqli_fetch_assoc($hasil1)) {
    $json=array();
$json[] = $row;
}}
if(mysqli_num_rows($hasil2)> 0){
while ($row1 = mysqli_fetch_assoc($hasil2)) {
    $json1=array();
$json1[] = $row1;
}}
    $json_response["datads"]=$json;
    $json_response["pass"]=$json1;
echo json_encode($json_response);
mysqli_close($conn);



?>
