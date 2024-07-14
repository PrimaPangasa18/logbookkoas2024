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
	$gelar      = $input["gelar"];
    $bagian = $input["bagian"];
	$query    = "UPDATE dosen SET nama=?,gelar=?,kode_bagian=? WHERE nip=?";
    $query1    = "UPDATE admin SET nama=?,password=? WHERE username=?";
    $stmt = $conn->prepare($query);
    $stmt1= $conn->prepare($query1);
	if($stmt && $stmt1){
	    $stmt->bind_param("ssss",$nama,$gelar,$bagian,$username);
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