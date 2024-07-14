<?php
$response = array();
include 'connect.php';
 
//Get the input request parameters
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array
 
//Check for Mandatory parameters
if(isset($input['username']) && isset($input['new_status'])&& isset($input['jenisJurnal'])&& isset($input['id_jurnal'])){
	$username = $input["username"];
	$new_status      = $input["new_status"];
	$id_jurnal      = $input["id_jurnal"];
	$jenis_jurnal = $input['jenisJurnal'];

	
	
	if($jenis_jurnal=="Jurnal Penyakit"){
	$query    = "UPDATE jurnal_penyakit SET status=? WHERE jurnal_penyakit.dosen=? AND jurnal_penyakit.id=?";
	} else {
		$query    = "UPDATE jurnal_ketrampilan SET status=? WHERE jurnal_ketrampilan.dosen=? AND jurnal_ketrampilan.id=?";
	}
	if($stmt = $conn->prepare($query)){
	    $stmt->bind_param("sss",$new_status,$username,$id_jurnal);
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
}
else{
	$response["status"] = 3;
	$response["message"] = "failed3";
}
//Display the JSON response
echo json_encode($response);
?>