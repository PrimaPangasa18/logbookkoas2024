<?php
include "connect.php";
include "fungsi.php";
	$inputJSON = file_get_contents('php://input');
	$input = json_decode($inputJSON,TRUE);
	$username=$input['username'];
	//$username="21060117130082";
	//$id_stase = "M113";
	$id_stase = $input['id_stase'];

	//yudhi menambahkan tgl
	$tgl = $input['tanggal'];
	

	

	$filter_penyakit = "SELECT * FROM jurnal_penyakit WHERE nim='".$username."' AND stase ='".$id_stase."' AND tanggal='".$tgl."'  ORDER BY tanggal,jam_awal";
	$filter_ketrampilan = "SELECT * FROM `jurnal_ketrampilan` WHERE nim='".$username."' AND stase ='".$id_stase."' AND tanggal='".$tgl."' ORDER BY `tanggal`,`jam_awal`";
	$data_penyakit = mysqli_query($conn,$filter_penyakit);
	$data_ketrampilan = mysqli_query($conn,$filter_ketrampilan);
	$jum1=mysqli_num_rows($data_penyakit);
	$jum2=mysqli_num_rows($data_ketrampilan);
	
	
	$kegiatan_penyakit = array();
	$dosen_penyakit = array();
	$lokasi_penyakit = array();
	$lain_penyakit = array();
	$array_penyakit1 = array();
	$array_penyakit2 = array();
	$array_penyakit3 = array();
	$array_penyakit4 = array();
		while ($data1=mysqli_fetch_assoc($data_penyakit))
		{
			$lain_penyakit[] = $data1; 
			$dosen = mysqli_fetch_assoc(mysqli_query($conn,"SELECT nama,gelar FROM dosen WHERE nip='$data1[dosen]'"));
			$dosen_penyakit[] =$dosen;
			$kegiatan = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `kegiatan` WHERE `id`='$data1[kegiatan]'"));
			$kegiatan_penyakit[] =$kegiatan;
			$lokasi = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `lokasi` WHERE `id`='$data1[lokasi]'"));
			$lokasi_penyakit[] = $lokasi;

				$id = 1;
				while ($id<=4)
				{
					$penyakit_id = "penyakit".$id;
					if ($data1[$penyakit_id]!="")
					{
						$penyakit = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `daftar_penyakit` WHERE `id`='$data1[$penyakit_id]'"));
//						$penyakit_kapital = strtoupper($penyakit[penyakit]);
						${"array_".$penyakit_id}[] =$penyakit;
					} else {
					${"array_".$penyakit_id}[]=(object)["penyakit"=>"null","skdi_level"=>"null","k_level"=>"null"];
				
					}
				
					$id++;
				}
				
		}
		
		$array["lain_penyakit"] = $lain_penyakit;
		$array["dosen_penyakit"] = $dosen_penyakit;
		$array["kegiatan_penyakit"] = $kegiatan_penyakit;
		$array["lokasi_penyakit"] = $lokasi_penyakit;
		$array["penyakit1"] = $array_penyakit1;
		$array["penyakit2"] = $array_penyakit2;
		$array["penyakit3"] = $array_penyakit3;
		$array["penyakit4"] = $array_penyakit4;
	$kegiatan_ketrampilan = array();
	$dosen_ketrampilan = array();
	$lokasi_ketrampilan = array();
	$lain_ketrampilan = array();
	$array_keterampilan1 = array();
	$array_keterampilan2 = array();
	$array_keterampilan3 = array();
	$array_keterampilan4 = array();
		while ($data2=mysqli_fetch_assoc($data_ketrampilan))
		{
			$lain_ketrampilan[]=$data2;
			$dosen = mysqli_fetch_assoc(mysqli_query($conn,"SELECT nama,gelar FROM dosen WHERE nip='$data2[dosen]'"));
			$dosen_ketrampilan[] = $dosen;
			//$tanggal_keg = tanggal_indo($data2[tanggal]);
			$kegiatan = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `kegiatan` WHERE `id`='$data2[kegiatan]'"));
			$kegiatan_ketrampilan[] = $kegiatan;
			$lokasi = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `lokasi` WHERE `id`='$data2[lokasi]'"));
			$lokasi_ketrampilan[]=$lokasi;

				$id = 1;
				while ($id<=4)
				{
					$ketrampilan_id = "ketrampilan".$id;
					if ($data2[$ketrampilan_id]!="")
					{
						$ketrampilan = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `daftar_ketrampilan` WHERE `id`='$data2[$ketrampilan_id]'"));
						${"array_keterampilan".$id}[]=$ketrampilan;
					} else {${"array_keterampilan".$id}[]=(object)["ketrampilan"=>"null","skdi_level"=>"null","k_level"=>"null","ipsg_level"=>"null"];}
					$id++;
				}
		}
		
		$array["lain_ketrampilan"] = $lain_ketrampilan;
		$array["dosen_ketrampilan"] = $dosen_ketrampilan;
		$array["kegiatan_ketrampilan"] = $kegiatan_ketrampilan;
		$array["lokasi_ketrampilan"] = $lokasi_ketrampilan;
		$array["keterampilan1"] = $array_keterampilan1;
		$array["keterampilan2"] = $array_keterampilan2;
		$array["keterampilan3"] = $array_keterampilan3;
		$array["keterampilan4"] = $array_keterampilan4;
		
	

//		ob_end_clean();
		header('Content-type:application/json');
		echo json_encode($array);
		mysqli_close($conn);
		

?>