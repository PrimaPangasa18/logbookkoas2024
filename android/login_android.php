<?php
$response = array();
include 'connect.php';
// Import script autoload agar bisa menggunakan library
require_once('./vendor/autoload.php');
// Import library
use Firebase\JWT\JWT;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$expired_time = time() + (24 * 60 * 60);
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit();
  }
//Get the input request parameters
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array

if (!isset($input["username"]) || !isset($input["password"])) {
    http_response_code(400);
    exit();
  }
//Check for Mandatory parameters
if(isset($input["username"]) && isset($input["password"])){
	$username = $input["username"];
	$password = $input["password"];
	$query    = "SELECT level, nama,password FROM admin WHERE username=? AND password=?";
    
	if($stmt = $conn->prepare($query)){
		$stmt->bind_param("ss",$username,$password);
		$stmt->execute();
		$stmt->bind_result($level,$nama,$pass);
        
        $payload = [
            'username' => $username,
            'exp' => $expired_time
        ];
        $access_token = JWT::encode($payload, $_ENV['ACCESS_TOKEN_SECRET'],'HS256');

		if($stmt->fetch()){
			//Validate the password
			if($level=='4'){
				$response["status"] = 0;
				$response["level"] = $level;
				$response["full_name"] = $nama;
				$response["pass"] = $pass;
                $response["accessToken"] = $access_token;
                $response["expiry"] = date(DATE_ISO8601, $expired_time);
				$response["message"] = "Login dosen successful";
			}
			else if($level=='5'){
				$response["status"] = 3;
				$response["level"] = $level;
                $response["full_name"] = $nama;
                $response["pass"] = $pass;
                $response["accessToken"] = $access_token;
                $response["expiry"] = date(DATE_ISO8601, $expired_time);
				$response["message"] = "Login mahasiswa succesful";
			}
			else if($level=='2'){
				$response["status"] = 5;
				$response["level"] = $level;
				$response["full_name"] = $nama;
				$response["pass"] = $pass;
                $response["accessToken"] = $access_token;
                $response["expiry"] = date(DATE_ISO8601, $expired_time);
				$response["message"] = "Login Residen successful";
			}
			else if($level=='6'){
				$response["status"] = 4;
				$response["level"] = $level;
				$response["full_name"] = $nama;
				$response["pass"] = $pass;
                $response["accessToken"] = $access_token;
                $response["expiry"] = date(DATE_ISO8601, $expired_time);
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

