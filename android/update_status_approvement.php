<?php
$response = array();
include 'connect.php';
 
//Get the input request parameters
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array
 
//Check for Mandatory parameters
$query_update = "UPDATE $jurnal SET status = 1 
                WHERE nim='$username' AND id='$id'";
mysqli_query ($conn,$query_update);
$response["status"] = 1;
echo json_encode($response);
?>