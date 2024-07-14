
<?php
require 'connect.php';
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON,TRUE);
$username = $input["username"];
$hasil1         = mysqli_query($conn,"SELECT biodata_mhsw.kota_lahir,biodata_mhsw.prop_lahir,prop.prop,kota.kota  FROM biodata_mhsw INNER JOIN prop ON prop.id_prop=biodata_mhsw.prop_lahir INNER JOIN kota on kota.id_kota=biodata_mhsw.kota_lahir where nim='$username'");
$hasil2         = mysqli_query($conn,"SELECT prop.id_prop,prop.prop,kota.id_kota, kota.kota FROM biodata_mhsw INNER JOIN prop ON prop.id_prop=biodata_mhsw.prop_alamat INNER JOIN kota on kota.id_kota=biodata_mhsw.kota_alamat where nim='$username'"); 
$hasil3        = mysqli_query($conn,"SELECT prop.id_prop,prop.prop,kota.id_kota, kota.kota FROM biodata_mhsw INNER JOIN prop ON prop.id_prop=biodata_mhsw.prop_ortu INNER JOIN kota on kota.id_kota=biodata_mhsw.kota_ortu where nim='$username'"); 
 $json_response = array();
if(mysqli_num_rows($hasil1)>= 0 && mysqli_num_rows($hasil2)>= 0 && mysqli_num_rows($hasil3)>= 0){
while ($row = mysqli_fetch_assoc($hasil1)) {
 $json=array();
$json[] = $row;
}
while ($row1 = mysqli_fetch_assoc($hasil2)) {
 $json1=array();
    $json1[] = $row1;

}
while ($row2 = mysqli_fetch_assoc($hasil3)) {
 $json2=array();
$json2[] = $row2;
}
}
if($json_response["datalhr"]="null"){
     $json[]=(object)["kota_lahir"=>"",
                     "prop_lahir"=>"",
                     "prop"=>"",
                     "kota"=>""];	
   $json_response["datalhr"]=$json;
}
else{
   $json_response["datalhr"]=$json;
}
    
if($json_response["dataalmt"]="null"){
     $json1[]=(object)["id_prop"=>"",
                     "prop"=>"",
                     "id_kota"=>"",
                     "kota"=>""];	
    $json_response["dataalmt"]=$json1;
}
else{
    $json_response["dataalmt"]=$json1;
}
    if($json_response["datawali"]="null"){
     $json2[]=(object)["id_prop"=>"",
                     "prop"=>"",
                     "id_kota"=>"",
                     "kota"=>""];	
    $json_response["datawali"]=$json2;
}
else{
    $json_response["datawali"]=$json2;
}
    
   
echo json_encode($json_response);
mysqli_close($conn);



?>
