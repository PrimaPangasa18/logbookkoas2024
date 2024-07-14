<?php
ob_start();
	include "connect.php";
	include "fungsi.php";
	$inputJSON = file_get_contents('php://input');
	$input = json_decode($inputJSON, TRUE); //convert JSON into array
    //query untuk mengambil lokasi
	$query_lokasi="SELECT * FROM lokasi ORDER BY id";
    $result_lokasi = mysqli_query($conn,$query_lokasi);
	$rows_lokasi = mysqli_num_rows($result_lokasi);
	

    $array_lokasi = array();

    if($rows_lokasi > 0 ){
        while($row = mysqli_fetch_assoc($result_lokasi)){
            $array_lokasi[] = $row;

        }
    }
	//query untuk mengambil dosen
	$array_dosen = array();
	$id_stase = $input["id_stase"];
	//$id_stase = "stase_M113";
	$stase = substr($id_stase,6,4);
	
	$dosen = mysqli_query($conn, "SELECT dosen.nip, admin.nama, dosen.gelar FROM admin JOIN dosen 
	WHERE (admin.level='4' OR (admin.level='6' AND admin.stase='".$stase."')) AND admin.username=dosen.nip ORDER BY admin.nama");
	$rows_dosen=mysqli_num_rows($dosen);
	$array_dosen = array();
	if($rows_dosen>0){
		while($row = mysqli_fetch_assoc($dosen)){
			$array_dosen[] = $row;
			
		}
	}
	
	$array["dosen"]=$array_dosen;
	
	/*$dosen = mysqli_query($conn,"SELECT username FROM admin WHERE level='4' ORDER BY nama");
	//$dosen = mysqli_query($conn,"SELECT `username` FROM `admin` WHERE `level`='4' ORDER BY username");
	$row_dosen = mysqli_num_rows($dosen);
	if($row_dosen>0){
	while ($dat9=mysqli_fetch_assoc($dosen))
	{
		$data_dosen = mysqli_fetch_assoc(mysqli_query($conn,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='".$dat9["username"]."'"));
		//'".$dat9["username"]."'
		$array_dosen[]=$data_dosen;
		
	}
	}
		$array["dosen"]=$array_dosen;
	*/
	
	//query untuk mengambil kegiatan
	$query_kegiatan="SELECT id,kegiatan,level FROM kegiatan ORDER BY id";
    $result_kegiatan = mysqli_query($conn,$query_kegiatan);
	$rows_kegiatan = mysqli_num_rows($result_kegiatan);
	

    $array_kegiatan = array();

    if($rows_kegiatan > 0 ){
        while($row = mysqli_fetch_assoc($result_kegiatan)){
            $array_kegiatan[] = $row;

        }
    }
	//mengambil spinner sistem penyakit
/*		$query_sistemP="SELECT * FROM sistem_penyakit ORDER BY id";
		$result_sistemP = mysqli_query($conn,$query_sistemP);
		$rows_sistemP = mysqli_num_rows($result_sistemP);
	

		$array_sistemP = array();

		if($rows_sistemP > 0 ){
			while($row = mysqli_fetch_assoc($result_sistemP)){
				$array_sistemP[] = $row;

			}
		}
		$array["sistem"]=$array_sistemP;
		
		$query_sistemK="SELECT * FROM sistem_ketrampilan ORDER BY id";
		$result_sistemK = mysqli_query($conn,$query_sistemK);
		$rows_sistemK = mysqli_num_rows($result_sistemK);
	

		$array_sistemK = array();

		if($rows_sistemK > 0 ){
			while($row = mysqli_fetch_assoc($result_sistemK)){
				$array_sistemK[] = $row;

			}
		}
		$array["sistem_ket"]=$array_sistemK;
*/	
	
	
	//mengambil spinner penyakit
	//$jenisinput = $input['jenis_jurnal'];
	$jenisinput = $input['jenis_jurnal'];
	//$id_include = "include_".$stase;
	
	
	if($jenisinput=="jurnal_penyakit"){
	$id_include = "include_".$stase;	
		//$id_sistemP = "S01";
		$query_penyakit="SELECT * FROM `daftar_penyakit` WHERE $id_include='1' ORDER BY `penyakit` ASC";
		$result_penyakit = mysqli_query($conn,$query_penyakit);
		$rows_penyakit = mysqli_num_rows($result_penyakit);
	

		$array_penyakit = array();

		if($rows_penyakit > 0 ){
			while($row = mysqli_fetch_assoc($result_penyakit)){
				$array_penyakit[] = $row;

			}
		}
		$array["jenis"]=$array_penyakit;
		
		
		
	} else {
		$id_include = "include_".$stase;
		
	//$id_include = "include_M113";

		//$id_sistemP = "S01";
		$query_ketrampilan="SELECT * FROM daftar_ketrampilan WHERE $id_include='1' ORDER BY ketrampilan ASC";
		$result_ketrampilan = mysqli_query($conn,$query_ketrampilan);
		$rows_ketrampilan = mysqli_num_rows($result_ketrampilan);
	

		$array_ketrampilan = array();

		if($rows_ketrampilan > 0 ){
			while($row = mysqli_fetch_assoc($result_ketrampilan)){
				$array_ketrampilan[] = $row;

			}
		}
		$array["jenis"]=$array_ketrampilan;
		
	}
	
	
	
	
	
		
	
	
	$array["lokasi"]=$array_lokasi;
	$array["kegiatan"]=$array_kegiatan;
	
	
	
	
	
	ob_end_clean();

    header('Content-type:application/json');
    echo json_encode($array);
    mysqli_close($conn);

?>