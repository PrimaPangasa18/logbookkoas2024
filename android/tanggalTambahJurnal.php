<?php
ob_start();
	include "connect.php";
	include "fungsi.php";
	$inputJSON = file_get_contents('php://input');
	$input = json_decode($inputJSON, TRUE); //convert JSON into array
	$username = $input['username'];
	$stase_id =$input['id_stase'];
//	$username = "21060117130082";
//	$stase_id ="stase_M112";
	$id_stase = substr($stase_id,6,4);
	$data_stase = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
	$data_stase_mhsw = mysqli_query($conn,"SELECT * FROM `$stase_id` WHERE `nim`='$username'");
	$datastase_mhsw = mysqli_fetch_assoc($data_stase_mhsw);
	$tgl_mulai = tanggal_indo($datastase_mhsw["tgl_mulai"]);
	
	$tgl_selesai = tanggal_indo($datastase_mhsw["tgl_selesai"]);
	$tgl_isi = tanggal_indo($tgl);
	$mulai = date_create($datastase_mhsw["tgl_mulai"]);
	$selesai = date_create($datastase_mhsw["tgl_selesai"]);
	$sekarang = date_create($tgl);
	$jmlhari_stase = $data_stase["hari_stase"];
	$hari_skrg = date_diff($mulai,$sekarang);
	$jmlhari_skrg = $hari_skrg->days+1;
	$array["tgl_isi"]=$tgl_isi;
	$array["tgl_isi_date"]=$tgl;
	$array["jml_hari_skrg"] = $jmlhari_skrg;
	$array["jml_hari_stase"] = $jmlhari_stase;
	
	$array1["tgl"][]=$array;
	ob_end_clean();
	
	
	
	header('Content-type:application/json');
    echo json_encode($array1);
    mysqli_close($conn);
		
?>