<?php
require 'connect.php';
$inputJSON = file_get_contents('php://input');
	$input = json_decode($inputJSON, TRUE); //convert JSON into array
$username=$input["username"];
 $strSQL = "SELECT * FROM bagian_ilmu";
$hasil1         = mysqli_query($conn,"SELECT dosen.kode_bagian,bagian_ilmu.bagian FROM dosen INNER JOIN bagian_ilmu on dosen.kode_bagian=bagian_ilmu.id where nip='$username'");
 $objQuery = mysqli_query($conn,$strSQL);
 $resultArray = array();
 $response = array();
$jumlah=mysqli_num_rows($objQuery);
$jumlah1=mysqli_num_rows($hasil1);
    $isi=array();
if($jumlah>0){
 while($obResult = mysqli_fetch_assoc($objQuery))
 {
$isi[]=$obResult;
 }
}
$isi1=array();
if($jumlah1>0){
 while($obResult1 = mysqli_fetch_assoc($hasil1))
 {
    $isi1[]=$obResult1; 
 }
}
else{
    $isi1[]=(object)["kode_bagian"=>"FK000",
                      "bagian"=>"Institusi Fakultas Kedokteran"];
}
 $resultArray["idbagian"]=$isi;
 $resultArray["idhsl"]=$isi1;    



     

echo json_encode($resultArray);
mysqli_close($conn);
?>