<?php
include "connect.php";
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON,TRUE);
$provinsi = $input["provinsi"];
$query="SELECT kota.id_kota,kota.kota FROM prop INNER JOIN kota ON prop.id_prop=kota.id_prop where prop.id_prop='$provinsi'";
$result = mysqli_query($conn,$query);
$rows = mysqli_num_rows($result);
$array_kota = array();
if($rows>0){
    while($data = mysqli_fetch_assoc($result)){
        $array_kota[] = $data;
        
    }
}
else{
    $array_kota[]=(object)["id_kota"=>"1105",
                    "kota"=>"ACEH BARAT"];
}
$array["kota"]=$array_kota;
header ('Content-type:application/json');
echo json_encode($array);

mysqli_close($conn);


?>