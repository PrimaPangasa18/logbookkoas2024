<?php
//	ob_start();
    include "connect.php";
	$inputJSON = file_get_contents('php://input');
	$input = json_decode($inputJSON,TRUE);
	$username=$input['username'];
	$mhsw_filter = $input['nim'];
	$stase_filter = $input['stase'];
	$status_filter = $input['status'];
	$jenis_filter=$input['jenisJurnal'];
	$tanggal_filter = $input['tanggal'];
	/*$username=$input['username'];
	$mhsw_filter = $input['nim'];
	$stase_filter = $input['stase'];
	$status_filter = $input['status'];
	$jenis_filter=$input['jenisJurnal'];
	$tanggal_filter = $input['tanggal'];*/
	error_reporting(E_ERROR | E_PARSE);


	if ($mhsw_filter=="all") $filtermhsw = "";
	else $filtermhsw = "AND `nim`='".$mhsw_filter."'";
	if ($stase_filter=="all") $filterstase = "";
	else $filterstase = "AND `stase`='".$stase_filter."'";
	if ($status_filter=="Semua status") $filterstatus = "";
	else if($status_filter=="Approved"){ $filterstatus = "AND `status`='1'";}
	else if($status_filter=="Unapproved"){ $filterstatus = "AND `status`='0'";}
	if ($tanggal_filter=="semua tanggal") $filtertanggal = "";
	else $filtertanggal = "AND `tanggal`='".$tanggal_filter."'";
//	$dosen_filter = "`dosen`="."'$_SESSION[user]'";

	$filter_penyakit = "SELECT * FROM jurnal_penyakit WHERE dosen='".$username."'$filtermhsw $filterstase $filterstatus $filtertanggal ORDER BY tanggal,jam_awal";
	$filter_ketrampilan = "SELECT * FROM `jurnal_ketrampilan` WHERE `dosen`='$username' $filtermhsw $filterstase $filterstatus $filtertanggal ORDER BY `tanggal`,`jam_awal`";
//	$biodata_dosen = mysql_fetch_array(mysql_query("SELECT * FROM `dosen` WHERE `nip`='$username'"));
	$data_penyakit = mysqli_query($conn,$filter_penyakit);
	$data_ketrampilan = mysqli_query($conn,$filter_ketrampilan);
	$jum1=mysqli_num_rows($data_penyakit);
	$jum2=mysqli_num_rows($data_ketrampilan);
	
	
	if($jenis_filter=="Jurnal Penyakit"){
	$array_kegiatan = array();
	$array_nama = array();
	$array_lokasi = array();
	$array_lain = array();
	$array_penyakit1 = array();
	$array_penyakit2 = array();
	$array_penyakit3 = array();
	$array_penyakit4 = array();
		while ($data1=mysqli_fetch_assoc($data_penyakit))
		{
			$array_lain[] = $data1; 
			$nama_mhsw = mysqli_fetch_assoc(mysqli_query($conn,"SELECT `nama` FROM `biodata_mhsw` WHERE `nim`='$data1[nim]'"));
			$array_nama[] =$nama_mhsw;
			//$tanggal_keg = tanggal_indo($data1[tanggal]);
			$kegiatan = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `kegiatan` WHERE `id`='$data1[kegiatan]'"));
			$array_kegiatan[] =$kegiatan;
			$lokasi = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `lokasi` WHERE `id`='$data1[lokasi]'"));
			$array_lokasi[] = $lokasi;

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
				
		//	if ($kelas=="ganjil") $kelas="genap";
			//else $kelas="ganjil";
		}
		
		$array["lain"] = $array_lain;
		$array["nama"] = $array_nama;
		$array["kegiatan"] = $array_kegiatan;
		$array["lokasi"] = $array_lokasi;
		$array["penyakit1"] = $array_penyakit1;
		$array["penyakit2"] = $array_penyakit2;
		$array["penyakit3"] = $array_penyakit3;
		$array["penyakit4"] = $array_penyakit4;
	} else{
	$array_kegiatan = array();
	$array_nama = array();
	$array_lokasi = array();
	$array_lain = array();
	$array_keterampilan1 = array();
	$array_keterampilan2 = array();
	$array_keterampilan3 = array();
	$array_keterampilan4 = array();
		while ($data2=mysqli_fetch_assoc($data_ketrampilan))
		{
			$array_lain[]=$data2;
			$nama_mhsw = mysqli_fetch_assoc(mysqli_query($conn,"SELECT `nama` FROM `biodata_mhsw` WHERE `nim`='$data2[nim]'"));
			$array_nama[] = $nama_mhsw;
			//$tanggal_keg = tanggal_indo($data2[tanggal]);
			$kegiatan = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `kegiatan` WHERE `id`='$data2[kegiatan]'"));
			$array_kegiatan[] = $kegiatan;
			$lokasi = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM `lokasi` WHERE `id`='$data2[lokasi]'"));
			$array_lokasi[]=$lokasi;

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
		
		$array["lain"] = $array_lain;
		$array["nama"] = $array_nama;
		$array["kegiatan"] = $array_kegiatan;
		$array["lokasi"] = $array_lokasi;
		$array["keterampilan1"] = $array_keterampilan1;
		$array["keterampilan2"] = $array_keterampilan2;
		$array["keterampilan3"] = $array_keterampilan3;
		$array["keterampilan4"] = $array_keterampilan4;
		
	}

//		ob_end_clean();
		header('Content-type:application/json');
		echo json_encode($array);
		mysqli_close($conn);
		
	
	
	
	
	
    
	
	
	

?>
