<?php
require 'connect.php';
$inputJSON = file_get_contents('php://input');
	$input = json_decode($inputJSON,TRUE);
$username = $input["username"];
$tanggal = $input["tanggal"];
$id = $input["id"];
$array_re = array();

$re = mysqli_fetch_assoc(mysqli_query($conn,"SELECT evaluasi,rencana FROM evaluasi WHERE nim='".$username."' AND tanggal='".$tanggal."' AND stase='".$id."'"));
			if($re==null){
				$array_re[]=(object)["evaluasi"=>"","rencana"=>""];
			}
			else{
			$array_re[] =$re;
            }

            $array["re"] = $array_re;
 
            header('Content-type:application/json');
            echo json_encode($array);
            mysqli_close($conn);
?>