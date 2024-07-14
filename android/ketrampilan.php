<?php

    include "connect.php";
	$inputJSON = file_get_contents('php://input');
	$input = json_decode($inputJSON,TRUE);
    $username=$input['username'];
	$tanggal = $input['tanggal'];
	$id = $input['id'];

	$query_ketrampilan = "SELECT * FROM jurnal_ketrampilan WHERE nim='".$username."' AND tanggal='".$tanggal."' AND stase='".$id."'";
	$data_ketrampilan = mysqli_query($conn,$query_ketrampilan);
	$jum=mysqli_num_rows($data_ketrampilan);
	
	$array_kegiatan = array();
    $array_lokasi = array();
    $array_dosen = array();
	$array_tmp = array();
	$array_kelas = array();
	$array_ketrampilan1 = array();
	$array_ketrampilan2 = array();
	$array_ketrampilan3 = array();
	$array_ketrampilan4 = array();
		while ($data1=mysqli_fetch_assoc($data_ketrampilan))
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
					$ketrampilan_id = "ketrampilan".$id;
					if ($data1[$ketrampilan_id]!="")
					{
						$ketrampilan = mysqli_fetch_assoc(mysqli_query($conn,"SELECT ketrampilan,skdi_level,k_level,ipsg_level FROM `daftar_ketrampilan` WHERE `id`='$data1[$ketrampilan_id]'"));
						${"array_".$ketrampilan_id}[] =$ketrampilan;
					} else {
					${"array_".$ketrampilan_id}[]=(object)["ketrampilan"=>"null","skdi_level"=>"null","k_level"=>"null","ipsg_level"=>"null"];
				
					}
				
					$id++;
				}
				
		}
		
		$array["tmp"] = $array_tmp;
		$array["kegiatan"] = $array_kegiatan;
		$array["lokasi"] = $array_lokasi;
		$array["kelas"] = $array_kelas;
        $array["dosen"] = $array_dosen;
		$array["ketrampilan1"] = $array_ketrampilan1;
		$array["ketrampilan2"] = $array_ketrampilan2;
		$array["ketrampilan3"] = $array_ketrampilan3;
		$array["ketrampilan4"] = $array_ketrampilan4;
	
		header('Content-type:application/json');
		echo json_encode($array);
		mysqli_close($conn);
?>
