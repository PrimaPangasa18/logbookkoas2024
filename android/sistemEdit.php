<?php
$response = array();
include 'connect.php';
 
//Get the input request parameters
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array
$penyakit = $input['penyakit'];
//$penyakit = "pemeriksaan fisik - inspeksi leher";
//$penyakit2 = "-";
$penyakit2 = $input['penyakit2'];
//$penyakit2 = "-";
$penyakit3= $input['penyakit3'];
//$penyakit3= "-";
$penyakit4 = $input['penyakit4'];
//$penyakit4 = "-";
$jenis = $input['jenis'];
//$jenis = "ketrampilan";



if($jenis=="penyakit"){
$query= "SELECT sistem_penyakit FROM `sistem_penyakit` JOIN daftar_penyakit WHERE sistem_penyakit.id = daftar_penyakit.id_sistem AND upper(daftar_penyakit.penyakit)='".$penyakit."'";
$data_sistem = mysqli_fetch_assoc(mysqli_query($conn,$query));
$array["sistem"][] = $data_sistem;
if($penyakit2!=="-"){
$query2= "SELECT sistem_penyakit FROM `sistem_penyakit` JOIN daftar_penyakit WHERE sistem_penyakit.id = daftar_penyakit.id_sistem AND upper(daftar_penyakit.penyakit)='".$penyakit2."'";
$data_sistem2 = mysqli_fetch_assoc(mysqli_query($conn,$query2));
$array["sistem2"][] = $data_sistem2;
}else {$array["sistem2"][]=(object)["sistem_penyakit"=>"<KOSONG>"];}
if($penyakit3!=="-"){
$query3= "SELECT sistem_penyakit FROM `sistem_penyakit` JOIN daftar_penyakit WHERE sistem_penyakit.id = daftar_penyakit.id_sistem AND upper(daftar_penyakit.penyakit)='".$penyakit3."'";
$data_sistem3 = mysqli_fetch_assoc(mysqli_query($conn,$query3));
$array["sistem3"][] = $data_sistem3;
}else {$array["sistem3"][]=(object)["sistem_penyakit"=>"<KOSONG>"];}
if($penyakit4!=="-"){
$query4= "SELECT sistem_penyakit FROM `sistem_penyakit` JOIN daftar_penyakit WHERE sistem_penyakit.id = daftar_penyakit.id_sistem AND upper(daftar_penyakit.penyakit)='".$penyakit4."'";
$data_sistem4 = mysqli_fetch_assoc(mysqli_query($conn,$query4));
$array["sistem4"][] = $data_sistem4;
}else {$array["sistem4"][]=(object)["sistem_penyakit"=>"<KOSONG>"];}	
}else {
	$query= "SELECT sistem_ketrampilan FROM `sistem_ketrampilan` JOIN daftar_ketrampilan WHERE sistem_ketrampilan.id = daftar_ketrampilan.id_sistem AND upper(daftar_ketrampilan.ketrampilan)='".$penyakit."'";
$data_sistem = mysqli_fetch_assoc(mysqli_query($conn,$query));
$array["sistem"][] = $data_sistem;
if($penyakit2!=="-"){
$query2= "SELECT sistem_ketrampilan FROM `sistem_ketrampilan` JOIN daftar_ketrampilan WHERE sistem_ketrampilan.id = daftar_ketrampilan.id_sistem AND upper(daftar_ketrampilan.ketrampilan)='".$penyakit2."'";
$data_sistem2 = mysqli_fetch_assoc(mysqli_query($conn,$query2));
$array["sistem2"][] = $data_sistem2;
}else {$array["sistem2"][]=(object)["sistem_ketrampilan"=>"<KOSONG>"];}
if($penyakit3!=="-"){
$query3= "SELECT sistem_ketrampilan FROM `sistem_ketrampilan` JOIN daftar_ketrampilan WHERE sistem_ketrampilan.id = daftar_ketrampilan.id_sistem AND upper(daftar_ketrampilan.ketrampilan)='".$penyakit3."'";
$data_sistem3 = mysqli_fetch_assoc(mysqli_query($conn,$query3));
$array["sistem3"][] = $data_sistem3;
}else {$array["sistem3"][]=(object)["sistem_ketrampilan"=>"<KOSONG>"];}
if($penyakit4!=="-"){
$query4= "SELECT sistem_ketrampilan FROM `sistem_ketrampilan` JOIN daftar_ketrampilan WHERE sistem_ketrampilan.id = daftar_ketrampilan.id_sistem AND upper(daftar_ketrampilan.ketrampilan)='".$penyakit4."'";
$data_sistem4 = mysqli_fetch_assoc(mysqli_query($conn,$query4));
$array["sistem4"][] = $data_sistem4;
}else {$array["sistem4"][]=(object)["sistem_ketrampilan"=>"<KOSONG>"];}
	
}




header('Content-type:application/json');
    echo json_encode($array);
    mysqli_close($conn);

?>