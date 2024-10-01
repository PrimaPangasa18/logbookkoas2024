<?php
$response = array();
include 'connect.php';
 
//Get the input request parameters
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array
 
//Check for Mandatory parameters
$username = $input['username'];
$dosen1    = $input['dosen'];
$otp  = $input['otp'];
$id= $input['id'];
$id_internal = $input["id_internal"];
$rotasi = $input["rotasi"];
$lastcharrotasi = substr($rotasi, -1);
$stat = "status".$lastcharrotasi;
$data1 = $id;
$data2 = "internal_".$data1;
$query_dos    = "SELECT nip FROM dosen WHERE nip like '$dosen1%'";
$dos_result = mysqli_query($conn,$query_dos);
while ($dos_a = mysqli_fetch_assoc($dos_result)){
$dosen = $dos_a["nip"];}

    $query    = "SELECT pin FROM dosen WHERE nip=? ";
    if($stmt = $conn->prepare($query)){
    $stmt->bind_param("s",$dosen);
		$stmt->execute();
		$stmt->bind_result($pin);
		if($stmt->fetch()){
            
            if($pin==$otp){
                //$response["status"] = 1;
                include 'update_status_ri.php';
            }
            else{
                $response["status"] = 0;
            }
        }
        else{
           
        } 
            $stmt->close();
    }
    else{

    }
    echo json_encode($response);
?>