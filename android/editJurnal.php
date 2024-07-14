<?php
include 'connect.php';
 
//Get the input request parameters
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); //convert JSON into array
 
//Check for Mandatory parameters
$jenis = $input['jenis'];
$username = $input['username'];
$angkatana = $input['angkatan'];
$hari = $input['hari'];
$stase    = $input['stase'];
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
$id_jurnal = $input['id_jurnal'];

$query_angkatan = "SELECT angkatan FROM biodata_mhsw WHERE nim='".$username."'";
$angkatan_result = mysqli_query($conn,$query_angkatan);
while ($angkatan_a = mysqli_fetch_assoc($angkatan_result)){
$angkatan = $angkatan_a["angkatan"];}
$query_grup    = "SELECT grup FROM biodata_mhsw WHERE nim='".$username."' AND angkatan='".$angkatan."'";
$grup_result = mysqli_query($conn,$query_grup);
while ($grup_a = mysqli_fetch_assoc($grup_result)){
$grup = $grup_a["grup"];}

$jam_kelas=substr($jam_awal,0,2);
$query_kelas="SELECT jam_mulai,jam_selesai,id FROM kelas";
$kelas_result = mysqli_query($conn,$query_kelas);
while($klas = mysqli_fetch_assoc($kelas_result)){
	if(substr($klas["jam_mulai"],0,2)<=$jam_kelas && substr($klas["jam_selesai"],0,2)>=$jam_kelas){
	$kelas = $klas["id"];
	}
}



$status = "0";






if($jenis=="jurnal_penyakit"){
	
	$query    = "UPDATE jurnal_penyakit set nim='".$username."',angkatan='".$angkatan."',grup='".$grup."',hari='".$hari."',tanggal='".$tanggal."',
	stase='".$stase."',jam_awal='".$jam_awal."',jam_akhir='".$jam_akhir."',lokasi='".$lokasi."',penyakit1='".$jenis1."',
	dosen='".$dosen."' WHERE id='".$id_jurnal."'";
	mysqli_query ($conn,$query);
	$query_update = "UPDATE jurnal_penyakit set kegiatan='".$kegiatan."', kelas='".$kelas."', status='0' WHERE id='".$id_jurnal."'";
	mysqli_query($conn,$query_update);
	if($jenis2!="0"){
		$update2="UPDATE jurnal_penyakit set penyakit2='".$jenis2."' WHERE id='".$id_jurnal."'";
	mysqli_query($conn,$update2);
	}else{
	$update2="UPDATE jurnal_penyakit set penyakit2='' WHERE id='".$id_jurnal."'";
	mysqli_query($conn,$update2);
	}
	if($jenis3!="0"){
		$update2="UPDATE jurnal_penyakit set penyakit3='".$jenis3."' WHERE id='".$id_jurnal."'";
	mysqli_query($conn,$update2);
	}else {
		$update2="UPDATE jurnal_penyakit set penyakit3='' WHERE id='".$id_jurnal."'";
	mysqli_query($conn,$update2);
	}
	if($jenis4!="0"){
		$update2="UPDATE jurnal_penyakit set penyakit4='".$jenis4."' WHERE id='".$id_jurnal."'";
	mysqli_query($conn,$update2);
	} else {
	$update2="UPDATE jurnal_penyakit set penyakit4='' WHERE id='".$id_jurnal."'";
	mysqli_query($conn,$update2);
	}
	
	
	}

else{
	$query    = "UPDATE jurnal_ketrampilan set nim='".$username."',angkatan='".$angkatan."',grup='".$grup."',hari='".$hari."',tanggal='".$tanggal."',
	stase='".$stase."',jam_awal='".$jam_awal."',jam_akhir='".$jam_akhir."',lokasi='".$lokasi."', ketrampilan1='".$jenis1."',
	dosen='".$dosen."' WHERE id='".$id_jurnal."'";
	mysqli_query ($conn,$query);
	$query_update = "UPDATE jurnal_ketrampilan set kegiatan='".$kegiatan."', kelas='".$kelas."', status='0' WHERE id='".$id_jurnal."'";
	mysqli_query($conn,$query_update);
	if($jenis2!="0"){
		$update2="UPDATE jurnal_ketrampilan set ketrampilan2='".$jenis2."' WHERE id='".$id_jurnal."'";
	mysqli_query($conn,$update2);
	}else {
	$update2="UPDATE jurnal_ketrampilan set ketrampilan2='' WHERE id='".$id_jurnal."'";
	mysqli_query($conn,$update2);
		
	}
	if($jenis3!="0"){
		$update2="UPDATE jurnal_ketrampilan set ketrampilan3='".$jenis3."' WHERE id='".$id_jurnal."'";
	mysqli_query($conn,$update2);
	}else {
	$update2="UPDATE jurnal_ketrampilan set ketrampilan3='' WHERE id='".$id_jurnal."'";
	mysqli_query($conn,$update2);
	}
	if($jenis4!="0"){
		$update2="UPDATE jurnal_ketrampilan set ketrampilan4='".$jenis4."' WHERE id='".$id_jurnal."'";
	mysqli_query($conn,$update2);
	} else {
	$update2="UPDATE jurnal_ketrampilan set ketrampilan4='' WHERE id='".$id_jurnal."'";
	mysqli_query($conn,$update2);
	}

}

?>