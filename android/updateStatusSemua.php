<?php
$response = array();
include 'connect.php';
 
//Get the input request parameters
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array
 
//Check for Mandatory parameters
if(isset($input['username'])&& isset($input['jenisJurnal'])){
	$username = $input['username'];
	$jenis_jurnal = $input['jenisJurnal'];
//	$username = "12345";
//	$jenis_jurnal = $input['jenisJurnal'];
	
	
	if($jenis_jurnal=="Jurnal Penyakit"){
	$query    = "UPDATE jurnal_penyakit SET status='1' WHERE jurnal_penyakit.dosen=?";
	} else {
		$query    = "UPDATE jurnal_ketrampilan SET status='1' WHERE jurnal_ketrampilan.dosen=?";
	}
	if($stmt = $conn->prepare($query)){
	    $stmt->bind_param("s",$username);
		$stmt->execute();
		if($stmt->execute()){
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
}else{
	$response["status"] = 3;
	$response["message"] = "failed3";
}
//Display the JSON response
echo json_encode($response);
?>