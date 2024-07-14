<?php
$response = array();
include 'connect.php';
 
//Get the input request parameters
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array
 
//Check for Mandatory parameters
if(isset($input['username'])){
	$username = $input["username"];
	$password      = $input["password"];
    $nama     = $input["nama"];
	$proplhr      = $input["proplahir"];
    $kotalhr = $input["kotalahir"];
    $tgllhr = $input["tgllahir"];
    $alamat = $input["alamat"];
    $propalmt = $input["propinsialamat"];
    $kotaalmt = $input["kotalamat"];
    $nohp = $input["nohp"];
    $email = $input["email"];
    $nmwali = $input["namawali"];
    $almtwali = $input["alamatwali"];
    $propwali = $input["propwali"];
    $kotawali = $input["kotawali"];
    $nohpwali = $input["nohpwali"];
	$query    = "UPDATE biodata_mhsw SET nama=?,prop_lahir=?,kota_lahir=?,tanggal_lahir=?,alamat=?,prop_alamat=?,kota_alamat=?,no_hp=?,email=?,nama_ortu=?,alamat_ortu=?,prop_ortu=?,kota_ortu=?,no_hportu=? WHERE nim=?";
    $query1    = "UPDATE admin SET nama=?,password=? WHERE username=?";
    $stmt = $conn->prepare($query);
    $stmt1= $conn->prepare($query1);
	if($stmt && $stmt1){
	    $stmt->bind_param("sssssssssssssss",$nama,$proplhr,$kotalhr,$tgllhr,$alamat,$propalmt,$kotaalmt,$nohp,$email,$nmwali,$almtwali,$propwali,$kotawali,$nohpwali,$username);
        $stmt->execute();
        $stmt1->bind_param("sss",$nama,$password,$username);
        $stmt1->execute();
		if($stmt->execute() && $stmt1->execute()){
		    $response["status"] = 0;
			$response["message"] = "successful";
		}
		else{
		    $response["status"] = 1;
			$response["message"] = "failed1";
		}
		
	}
	else{
		$response["status"] = 2;
		$response["message"] = "failed2";
	}
		
		$stmt->close();
}
else{
	$response["status"] = 3;
	$response["message"] = "failed3";
}
//Display the JSON response
echo json_encode($response);
?>