<?php
$response = array();
include 'connect.php';
 
//Get the input request parameters
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array
 
//Check for Mandatory parameters
if(isset($input["username"]) && isset($input["password"])){
	$username = $input["username"];
	$password = $input["password"];
	$query    = "SELECT level, nama,password FROM admin WHERE username=? AND password=?";
 
	if($stmt = $conn->prepare($query)){
		$stmt->bind_param("ss",$username,$password);
		$stmt->execute();
		$stmt->bind_result($level,$nama,$pass);
		if($stmt->fetch()){
			//Validate the password
			if($level=='4'){
				$response["status"] = 0;
				$response["level"] = $level;
				$response["full_name"] = $nama;
				$response["pass"] = $pass;
				$response["message"] = "Login dosen successful";
			}
			else if($level=='5'){
				$response["status"] = 3;
				$response["level"] = $level;
                $response["full_name"] = $nama;
                $response["pass"] = $pass;
				$response["message"] = "Login mahasiswa succesful";
			}
			else if($level=='6'){
				$response["status"] = 4;
				$response["level"] = $level;
				$response["full_name"] = $nama;
				$response["pass"] = $pass;
				$response["message"] = "Login Residen successful";
			}
			else{
				$response["status"] = 1;
				$response["message"] = "Invalid username and password combination";
			}
		}
		else{
			$response["status"] = 1;
			$response["message"] = "Invalid username and password combination";
		}
		
		$stmt->close();
	}
}
else{
	$response["status"] = 2;
	$response["message"] = "Missing mandatory parameters";
}
//Display the JSON response
echo json_encode($response);
?>

