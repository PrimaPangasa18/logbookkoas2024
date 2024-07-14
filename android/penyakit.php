<?php

    include "connect.php";
	$inputJSON = file_get_contents('php://input');
	$input = json_decode($inputJSON,TRUE);
	$username=$input['username'];
	$tanggal = $input['tanggal'];
	$id = $input['id'];

	$query_penyakit = "SELECT * FROM jurnal_penyakit WHERE nim='".$username."' AND tanggal='".$tanggal."' AND stase='".$id."'";
	$data_penyakit = mysqli_query($conn,$query_penyakit);
	$jum=mysqli_num_rows($data_penyakit);
	
	$array_kegiatan = array();
    $array_lokasi = array();
    $array_dosen = array();
	$array_tmp = array();
	$array_kelas = array();
	$array_penyakit1 = array();
	$array_penyakit2 = array();
	$array_penyakit3 = array();
	$array_penyakit4 = array();
		while ($data1=mysqli_fetch_assoc($data_penyakit))
		{
			$array_tmp[] = $data1; 
	        $kegiatan = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `kegiatan` WHERE `id`='$data1[kegiatan]'"));
			$array_kegiatan[] =$kegiatan;
			$lokasi = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `lokasi` WHERE `id`='$data1[lokasi]'"));
			$array_lokasi[] = $lokasi;
			$kelas = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `kelas` WHERE `id`='$data1[kelas]'"));
            $array_kelas[] = $kelas;
            $dosen = mysqli_fetch_assoc(mysqli_query($conn,"SELECT nama,gelar FROM `dosen` WHERE `nip`='$data1[dosen]'"));
			$array_dosen[] = $dosen;
				$id = 1;
				while ($id<=4)
				{
					$penyakit_id = "penyakit".$id;
					if ($data1[$penyakit_id]!="")
					{
						$penyakit = mysqli_fetch_assoc(mysqli_query($conn,"SELECT penyakit,skdi_level,k_level FROM `daftar_penyakit` WHERE `id`='$data1[$penyakit_id]'"));
						${"array_".$penyakit_id}[] =$penyakit;
					} else {
					${"array_".$penyakit_id}[]=(object)["penyakit"=>"null","skdi_level"=>"null","k_level"=>"null"];
				
					}
				
					$id++;
				}
				
		}
		
		$array["tmp"] = $array_tmp;
		$array["kegiatan"] = $array_kegiatan;
		$array["lokasi"] = $array_lokasi;
		$array["kelas"] = $array_kelas;
        $array["dosen"] = $array_dosen;
		$array["penyakit1"] = $array_penyakit1;
		$array["penyakit2"] = $array_penyakit2;
		$array["penyakit3"] = $array_penyakit3;
		$array["penyakit4"] = $array_penyakit4;
	
		header('Content-type:application/json');
		echo json_encode($array);
		mysqli_close($conn);
?>
