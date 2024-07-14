<?php
require 'connect.php';
$inputJSON = file_get_contents('php://input');
	$input = json_decode($inputJSON, TRUE); //convert JSON into array
$username=$input["username"];
 $strSQL = "SELECT * FROM prop";
$hasil1         = mysqli_query($conn,"SELECT biodata_mhsw.prop_lahir,prop.prop,biodata_mhsw.kota_lahir,kota.kota FROM biodata_mhsw INNER JOIN prop ON prop.id_prop=biodata_mhsw.prop_lahir INNER JOIN kota ON kota.id_kota=biodata_mhsw.kota_lahir where biodata_mhsw.nim='$username'");
$hasil2         = mysqli_query($conn,"SELECT biodata_mhsw.prop_alamat,prop.prop,biodata_mhsw.kota_alamat,kota.kota FROM biodata_mhsw INNER JOIN prop ON prop.id_prop=biodata_mhsw.prop_alamat INNER JOIN kota ON kota.id_kota=biodata_mhsw.kota_alamat where biodata_mhsw.nim='$username'");
$hasil3         = mysqli_query($conn,"SELECT biodata_mhsw.prop_ortu,prop.prop,biodata_mhsw.kota_ortu,kota.kota FROM biodata_mhsw INNER JOIN prop ON prop.id_prop=biodata_mhsw.prop_ortu INNER JOIN kota ON kota.id_kota=biodata_mhsw.kota_ortu where biodata_mhsw.nim='$username'");
$array_kota = array();
 $objQuery = mysqli_query($conn,$strSQL);
 $resultArray = array();
$jumlah=mysqli_num_rows($objQuery);
$jumlah1=mysqli_num_rows($hasil1);
$isi=array();
$isi1=array();
$isi2=array();
$isi3=array();
if(mysqli_num_rows($objQuery)>0){
 while($obResult = mysqli_fetch_assoc($objQuery))
 {
$isi[]=$obResult;
 }
}
if(mysqli_num_rows($hasil1)>0){
 while($obResult1 = mysqli_fetch_assoc($hasil1))
 {
$isi1[]=$obResult1;
 }
}
else{
    $isi1[]=(object)["prop_lahir"=>"11",
                     "prop"=>"ACEH",
                    "kota_lahir"=>"1105",
                    "kota"=>"ACEH BARAT"];
}
if(mysqli_num_rows($hasil2)>0){
 while($obResult2 = mysqli_fetch_assoc($hasil2))
 {
$isi2[]=$obResult2;
 }
}
else{
    $isi2[]=(object)["prop_alamat"=>"11",
                     "prop"=>"ACEH",
                    "kota_alamat"=>"1105",
                    "kota"=>"ACEH BARAT"];
}
if(mysqli_num_rows($hasil3)>0){
 while($obResult3 = mysqli_fetch_assoc($hasil3))
 {
$isi3[]=$obResult3;
 }
}
else{
    $isi3[]=(object)["prop_ortu"=>"11",
                     "prop"=>"ACEH",
                    "kota_ortu"=>"1105",
                    "kota"=>"ACEH BARAT"];
}


 $resultArray["dfprop"]=$isi;
 $resultArray["hsllhr"]=$isi1;
 $resultArray["hslalmt"]=$isi2;
 $resultArray["hslortu"]=$isi3;
echo json_encode($resultArray);
mysqli_close($conn);
?>
