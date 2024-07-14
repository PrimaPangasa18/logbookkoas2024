<?php

    include "connect.php";
	$inputJSON = file_get_contents('php://input');
	$input = json_decode($inputJSON,TRUE);
    $username=$input['username'];
	$query_stase = "SELECT * FROM kepaniteraan WHERE id LIKE 'M12%'";
	$data_stase = mysqli_query($conn,$query_stase);
	$jum=mysqli_num_rows($data_stase);
	$array_tmp = array();
    $array_mulai = array();
    $array_selesai = array();
    $array_rotasi = array();
    
		while ($data1=mysqli_fetch_assoc($data_stase))
		{
            $array_tmp[] = $data1;
            $data2 = $data1['id'];
            $data3 = "stase_".$data2;
	        $mulai = mysqli_fetch_assoc(mysqli_query($conn,"SELECT tgl_mulai FROM `$data3` WHERE `nim`='$username'"));
			if($mulai==null){
				$array_mulai[]=(object)["tgl_mulai"=>"null"];
			}
			else{
			$array_mulai[] =$mulai;
			}
            $selesai = mysqli_fetch_assoc(mysqli_query($conn,"SELECT tgl_selesai FROM `$data3` WHERE `nim`='$username'"));
			if($selesai==null){
				$array_selesai[]=(object)["tgl_selesai"=>"null"];
			}
			else{
			$array_selesai[] =$selesai;
            }
            $rotasi = mysqli_fetch_assoc(mysqli_query($conn,"SELECT rotasi FROM `$data3` WHERE `nim`='$username'"));
			if($rotasi==null){
				$array_rotasi[]=(object)["rotasi"=>"null"];
			}
			else{
			$array_rotasi[] =$rotasi;
            }
        }
		$array["tmp"] = $array_tmp;
		$array["mulai"] = $array_mulai;
        $array["selesai"] = $array_selesai;
        $array["rotasi"] = $array_rotasi;

		header('Content-type:application/json');
		echo json_encode($array);
		mysqli_close($conn);
?>
