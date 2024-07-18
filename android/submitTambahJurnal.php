<?php
include 'connect.php';
 
//Get the input request parameters
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array
 
//Check for Mandatory parameters
$jenis = $input['jenis'];
$username = $input['username'];
$hari = $input['hari'];
$stase    = $input['stase'];
//yudhi menghapus angkatan
$tanggal  = $input['tanggal'];
$jam_awal= $input['jam_awal'];
$jam_akhir = $input['jam_akhir'];
$lokasi = $input['lokasi'];
$kegiatan = $input['kegiatan'];
$dosen = $input['dosen'];
$jenis1 = $input['id_jenis1'];
$jenis2 = $input['id_jenis2'];
$jenis3 = $input['id_jenis3'];
$jenis4 = $input['id_jenis4'];
$query_angkatan = "SELECT angkatan FROM biodata_mhsw WHERE nim='".$username."'";
$angkatan_result = mysqli_query($conn,$query_angkatan);
while ($angkatan_a = mysqli_fetch_assoc($angkatan_result)){
$angkatan = $angkatan_a["angkatan"];}
$query_grup    = "SELECT grup FROM biodata_mhsw WHERE nim='".$username."' AND angkatan='".$angkatan."'";
$grup_result = mysqli_query($conn,$query_grup);
while ($grup_a = mysqli_fetch_assoc($grup_result)){
$grup = $grup_a["grup"];}

/*$jam_kelas=substr($jam_awal,0,2);
$query_kelas="SELECT jam_mulai,jam_selesai,id FROM kelas";
$kelas_result = mysqli_query($conn,$query_kelas);
while($klas = mysqli_fetch_assoc($kelas_result)){
	if(substr($klas["jam_mulai"],0,2)<=$jam_kelas && substr($klas["jam_selesai"],0,2)>=$jam_kelas){
	$kelas = $klas["id"];
	}
}
$jam_kelas=substr($jam_awal,0,2);*/
if(substr($jam_awal,2,1)==':'){
	$query_kelas="SELECT jam_mulai,jam_selesai,id FROM kelas";
	$kelas_result = mysqli_query($conn,$query_kelas);
	while($klas = mysqli_fetch_assoc($kelas_result)){
	if($klas["jam_mulai"]<=$jam_awal && $klas["jam_selesai"]>=$jam_awal){
	$kelas = $klas["id"];
	}
}
} else {
	$query_kelas1="SELECT jam_mulai,jam_selesai,id FROM kelas WHERE jam_mulai<'10:00:00'";
	$kelas_result1 = mysqli_query($conn,$query_kelas1);
	while($klas = mysqli_fetch_assoc($kelas_result1)){
	if(substr($klas["jam_mulai"],1)<=$jam_awal && substr($klas["jam_selesai"],1)>=$jam_awal){
	$kelas = $klas["id"];
	}
}
	
}





$status = "0";






if($jenis=="jurnal_penyakit"){
	/*$query_jenis1    = "SELECT id FROM daftar_penyakit WHERE id_sistem='".$id_sis1."' AND penyakit='".$jenis1."'";
	$jenis1_result = mysqli_query($conn,$query_jenis1);
	while ($temp = mysqli_fetch_assoc($jenis1_result)){
	$id_jenis1 = $temp["id"];}
	*/
	$query    = "INSERT INTO jurnal_penyakit (nim,angkatan,hari,tanggal,stase,jam_awal,jam_akhir,lokasi,penyakit1,dosen) 
    SELECT * FROM (SELECT '".$username."','".$angkatan."','".$hari."','".$tanggal."',
	'".$stase."','".$jam_awal."','".$jam_akhir."','".$lokasi."','".$jenis1."',
	'".$dosen."') AS temp
    WHERE NOT EXISTS(SELECT * FROM jurnal_penyakit WHERE nim='".$username."' AND angkatan='".$angkatan."' 
    AND tanggal='".$tanggal."' AND lokasi='".$lokasi."' AND jam_awal='".$jam_awal."' AND jam_akhir='".$jam_akhir."')";
	mysqli_query ($conn,$query);
	$query_update = "UPDATE jurnal_penyakit set grup='".$grup."', kegiatan='".$kegiatan."', kelas='".$kelas."', status='".$status."' WHERE nim='".$username."' AND angkatan='".$angkatan."' 
    AND tanggal='".$tanggal."' AND lokasi='".$lokasi."' AND jam_awal='".$jam_awal."' AND jam_akhir='".$jam_akhir."'";
	mysqli_query($conn,$query_update);
	if($jenis2!="0"){
	/*	$query_jenis2    = "SELECT id FROM daftar_penyakit WHERE id_sistem='".$id_sis2."' AND penyakit='".$jenis2."'";
	$jenis2_result = mysqli_query($conn,$query_jenis2);
	while ($temp = mysqli_fetch_assoc($jenis2_result)){
	$id_jenis2 = $temp["id"];}*/
		$update2="UPDATE jurnal_penyakit set penyakit2='".$jenis2."' WHERE nim='".$username."' AND angkatan='".$angkatan."' 
    AND tanggal='".$tanggal."' AND lokasi='".$lokasi."' AND jam_awal='".$jam_awal."' AND jam_akhir='".$jam_akhir."'";
	mysqli_query($conn,$update2);
	}
	if($jenis3!="0"){
	$update2="UPDATE jurnal_penyakit set  penyakit3='".$jenis3."' WHERE nim='".$username."' AND angkatan='".$angkatan."' 
    AND tanggal='".$tanggal."' AND lokasi='".$lokasi."' AND jam_awal='".$jam_awal."' AND jam_akhir='".$jam_akhir."'";
	mysqli_query($conn,$update2);
	}
	if($jenis4!="0"){
		$update2="UPDATE jurnal_penyakit set penyakit4='".$jenis4."' WHERE nim='".$username."' AND angkatan='".$angkatan."' 
    AND tanggal='".$tanggal."' AND lokasi='".$lokasi."' AND jam_awal='".$jam_awal."' AND jam_akhir='".$jam_akhir."'";
	mysqli_query($conn,$update2);
	}
	
	
	}

else{
	/*$query_jenis1    = "SELECT id FROM daftar_ketrampilan WHERE id_sistem='".$id_sis1."' AND ketrampilan='".$jenis1."'";
	$jenis1_result = mysqli_query($conn,$query_jenis1);
	while ($temp = mysqli_fetch_assoc($jenis1_result)){
	$id_jenis1 = $temp["id"];}*/
	$query    = "INSERT INTO jurnal_ketrampilan (nim,angkatan,hari,tanggal,stase,jam_awal,jam_akhir,lokasi,ketrampilan1,dosen) 
    SELECT * FROM (SELECT '".$username."','".$angkatan."','".$hari."','".$tanggal."',
	'".$stase."','".$jam_awal."','".$jam_akhir."','".$lokasi."','".$jenis1."',
	'".$dosen."') AS temp
    WHERE NOT EXISTS(SELECT * FROM jurnal_ketrampilan WHERE nim='".$username."' AND angkatan='".$angkatan."' 
    AND tanggal='".$tanggal."' AND lokasi='".$lokasi."' AND jam_awal='".$jam_awal."' AND jam_akhir='".$jam_akhir."')";
	mysqli_query ($conn,$query);
	$query_update = "UPDATE jurnal_ketrampilan set kegiatan='".$kegiatan."', kelas='".$kelas."', grup='".$grup."', status='".$status."' WHERE nim='".$username."' AND angkatan='".$angkatan."' 
    AND tanggal='".$tanggal."' AND lokasi='".$lokasi."' AND jam_awal='".$jam_awal."' AND jam_akhir='".$jam_akhir."'";
	mysqli_query($conn,$query_update);
	if($jenis2!="0"){
		/*$query_jenis2    = "SELECT id FROM daftar_ketrampilan WHERE id_sistem='".$id_sis2."' AND ketrampilan='".$jenis2."'";
	$jenis2_result = mysqli_query($conn,$query_jenis2);
	while ($temp = mysqli_fetch_assoc($jenis2_result)){
	$id_jenis2 = $temp["id"];}*/
		$update2="UPDATE jurnal_ketrampilan set  ketrampilan2='".$jenis2."' WHERE nim='".$username."' AND angkatan='".$angkatan."' 
    AND tanggal='".$tanggal."' AND lokasi='".$lokasi."' AND jam_awal='".$jam_awal."' AND jam_akhir='".$jam_akhir."'";
	mysqli_query($conn,$update2);
	}
	if($jenis3!="0"){
		$update2="UPDATE jurnal_ketrampilan set ketrampilan3='".$jenis3."' WHERE nim='".$username."' AND angkatan='".$angkatan."' 
    AND tanggal='".$tanggal."' AND lokasi='".$lokasi."' AND jam_awal='".$jam_awal."' AND jam_akhir='".$jam_akhir."'";
	mysqli_query($conn,$update2);
	}
	if($jenis4!="0"){
		$update2="UPDATE jurnal_ketrampilan set ketrampilan4='".$jenis4."' WHERE nim='".$username."' AND angkatan='".$angkatan."' 
    AND tanggal='".$tanggal."' AND lokasi='".$lokasi."' AND jam_awal='".$jam_awal."' AND jam_akhir='".$jam_akhir."'";
	mysqli_query($conn,$update2);
	}

}

?>