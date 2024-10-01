<?php

	include "connect.php";
	include "fungsi.php";

	require 'verifiytoken.php';
	require './vendor/autoload.php';
	use \Firebase\JWT\JWT;
	use \Firebase\JWT\Key;

	// Verify the token before proceeding
	$userData = verifyToken(); // This will exit the script if the token is invalid


	$inputJSON = file_get_contents('php://input');
	$input = json_decode($inputJSON,TRUE);
    $username=$input['username'];
	$query_stase = "SELECT * FROM kepaniteraan";
	$data_stase = mysqli_query($conn,$query_stase);
	$jum=mysqli_num_rows($data_stase);
	$array_tmp = array();
    $array_mulai = array();
	$array_selesai = array();
	$sekarang = date_create($tgl);
    
		while ($data1=mysqli_fetch_assoc($data_stase))
		{
            $array_tmp[] = $data1;
            $data2 = $data1['id'];
            $data3 = "stase_".$data2;
	        $mulai = mysqli_fetch_assoc(mysqli_query($conn,"SELECT tgl_mulai,tgl_selesai FROM `$data3` WHERE `nim`='$username'"));
			if($mulai==null){
				$array_mulai[]=(object)["tgl_mulai"=>"null","tgl_selesai"=>"null"];
			}
			else{
			$array_mulai[] =$mulai;
			if (strtotime($mulai["tgl_mulai"])<=strtotime($tgl) and strtotime($tgl)<=strtotime($mulai["tgl_selesai"]))
				{
					$update = mysqli_query($conn,"UPDATE `$data3` SET `status`='1' WHERE `nim`='$username'");
				}
			else if (strtotime($tgl)>strtotime($mulai["tgl_selesai"]))
				{
					$update = mysqli_query($conn,"UPDATE `$data3` SET `status`='2' WHERE `nim`='$username'");
				}
			else if (strtotime($tgl)<strtotime($mulai["tgl_mulai"]))
				{
					$update = mysqli_query($conn,"UPDATE `$data3` SET `status`='0' WHERE `nim`='$username'");
				}
			}
        }
		$array["tmp"] = $array_tmp;
		$array["mulai"] = $array_mulai;

		header('Content-type:application/json');
		echo json_encode($array);
		mysqli_close($conn);
?>
