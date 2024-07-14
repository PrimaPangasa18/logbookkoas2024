<HTML>
<head>
	<!--</head>-->
</head>
<BODY>

<?php

	include "config.php";
	include "fungsi.php";

	error_reporting("E_ALL ^ E_NOTICE");

	//Kepaniteraan Komprehensip
  //Tabel kompre_nilai_cbd
	$delete_data1 = mysqli_query($con,"TRUNCATE TABLE `kompre_nilai_cbd`");
	$data_lama1 = mysqli_query($con,"SELECT * FROM `kompre_nilai_cbd_old` ORDER BY `id`");
	while ($data1 = mysqli_fetch_array($data_lama1))
	{
	  $aspek_1 = number_format($data1[aspek_1],2);
	  $aspek_2 = number_format($data1[aspek_2],2);
	  $aspek_3 = number_format($data1[aspek_3],2);
	  $aspek_4 = number_format($data1[aspek_4],2);

	  $nilai_rata = 0.25*$data1[aspek_1] + 0.25*$data1[aspek_2] + 0.25*$data1[aspek_3] + 0.25*$data1[aspek_4];
	  $nilai_rata = number_format($nilai_rata,2);

	  $insert_kasus=mysqli_query($con,"INSERT INTO `kompre_nilai_cbd`
	    ( `nim`, `dosen`, `kasus`,
	      `aspek_1`, `aspek_2`, `aspek_3`, `aspek_4`, `nilai_rata`,
	      `tgl_isi`, `jam_isi`, `tgl_approval`, `status_approval`)
	    VALUES
	    ( '$data1[nim]', '$data1[dosen]', '$data1[kasus]',
	      '$aspek_1', '$aspek_2', '$aspek_3', '$aspek_4', '$nilai_rata',
	      '$data1[tgl_isi]','$data1[jam_isi]','$data1[tgl_approval]', '$data1[status_approval]')");
	}

	//Tabel kompre_nilai_laporan
	$delete_data2 = mysqli_query($con,"TRUNCATE TABLE `kompre_nilai_laporan`");
	$data_lama2 = mysqli_query($con,"SELECT * FROM `kompre_nilai_laporan_old` ORDER BY `id`");
	while ($data2 = mysqli_fetch_array($data_lama2))
	{
	  $aspek1_ind = number_format($data2[aspek1_ind],2);
	  $aspek2_ind = number_format($data2[aspek2_ind],2);
	  $aspek3_ind = number_format($data2[aspek3_ind],2);
	  $aspek4_ind = number_format($data2[aspek4_ind],2);
	  $aspek5_ind = number_format($data2[aspek5_ind],2);
	  $nilai_rata_ind = 0.1*$data2[aspek1_ind] + 0.2*$data2[aspek2_ind] + 0.2*$data2[aspek3_ind] + 0.3*$data2[aspek4_ind] + 0.2*$data2[aspek5_ind];
	  $nilai_rata_ind = number_format($nilai_rata_ind,2);

	  $aspek1_kelp = number_format($data2[aspek1_kelp],2);
	  $aspek2_kelp = number_format($data2[aspek2_kelp],2);
	  $aspek3_kelp = number_format($data2[aspek3_kelp],2);
	  $aspek4_kelp = number_format($data2[aspek4_kelp],2);
	  $aspek5_kelp = number_format($data2[aspek5_kelp],2);
	  $nilai_rata_kelp = 0.1*$data2[aspek1_kelp] + 0.2*$data2[aspek2_kelp] + 0.2*$data2[aspek3_kelp] + 0.3*$data2[aspek4_kelp] + 0.2*$data2[aspek5_kelp];
	  $nilai_rata_kelp = number_format($nilai_rata_kelp,2);

	  $lokasi = addslashes($data2[lokasi]);

	  $insert_laporan=mysqli_query($con,"INSERT INTO `kompre_nilai_laporan`
	    ( `nim`, `dosen`, `lokasi`, `instansi`,
	      `tgl_mulai`, `tgl_selesai`,
	      `aspek1_ind`, `aspek1_kelp`, `aspek2_ind`, `aspek2_kelp`,
	      `aspek3_ind`, `aspek3_kelp`, `aspek4_ind`, `aspek4_kelp`,
	      `aspek5_ind`, `aspek5_kelp`, `nilai_rata_ind`,`nilai_rata_kelp`,
	      `tgl_isi`, `tgl_approval`,`status_approval`)
	    VALUES
	    ( '$data2[nim]', '$data2[dosen]', '$lokasi','$data2[instansi]',
	      '$data2[tgl_mulai]','$data2[tgl_selesai]',
	      '$aspek1_ind','$aspek1_kelp','$aspek2_ind','$aspek2_kelp',
	      '$aspek3_ind','$aspek3_kelp','$aspek4_ind','$aspek4_kelp',
	      '$aspek5_ind','$aspek5_kelp','$nilai_rata_ind','$nilai_rata_kelp',
	      '$data2[tgl_isi]', '$data2[tgl_approval]', '$data2[status_approval]')");
	}

//Tabel kompre_nilai_presensi
$delete_data3 = mysqli_query($con,"TRUNCATE TABLE `kompre_nilai_presensi`");
$data_lama3 = mysqli_query($con,"SELECT * FROM `kompre_nilai_presensi_old` ORDER BY `id`");
while ($data3 = mysqli_fetch_array($data_lama3))
{
	$lokasi = addslashes($data3[lokasi]);
	$nilai_total = number_format($data3[nilai_total],2);
	$nilai_masuk = number_format($data3[nilai_masuk],2);
	$nilai_absen = number_format($data3[nilai_absen],2);
	$nilai_ijin = number_format($data3[nilai_ijin],2);

	$hari_ijin = (int)(-1*$data3[nilai_ijin]/2);
	$hari_alpa = (int)(-1*$data3[nilai_absen]/5);
	$tgl_mulai = date_create($data3[tgl_mulai]);
	$tgl_selesai = date_create($data3[tgl_selesai]);
	$beda_hari = date_diff($tgl_mulai,$tgl_selesai);
	$hari_kerja = $beda_hari->days+1;
	$hari_masuk = (int)($hari_kerja-$hari_ijin-$hari_alpa);

	$lokasi = addslashes($data3[lokasi]);

	$insert_presensi=mysqli_query($con,"INSERT INTO `kompre_nilai_presensi`
		( `nim`, `dosen`, `lokasi`,`instansi`, `tgl_mulai`,`tgl_selesai`,
			`hari_kerja`, `hari_masuk`, `hari_ijin`, `hari_alpa`,
			`nilai_masuk`, `nilai_absen`, `nilai_ijin`, `nilai_total`,
			`tgl_isi`, `tgl_approval`, `status_approval`)
		VALUES
		( '$data3[nim]', '$data3[dosen]', '$lokasi', '$data3[instansi]', '$data3[tgl_mulai]','$data3[tgl_selesai]',
			'$hari_kerja','$hari_masuk','$hari_ijin','$hari_alpa',
			'$nilai_masuk', '$nilai_absen', '$nilai_ijin', '$nilai_total',
			'$data3[tgl_isi]', '$data3[tgl_approval]', '$data3[status_approval]')");
}

//Tabel kompre_nilai_sikap
$delete_data4 = mysqli_query($con,"TRUNCATE TABLE `kompre_nilai_sikap`");
$data_lama4 = mysqli_query($con,"SELECT * FROM `kompre_nilai_sikap_old` ORDER BY `id`");
while ($data4 = mysqli_fetch_array($data_lama4))
{
	$lokasi = addslashes($data4[lokasi]);

	$aspek1 = number_format($data4[aspek_1]*100/15,2);
	$aspek2 = number_format($data4[aspek_2]*100/15,2);
	$aspek3 = number_format($data4[aspek_3]*100/15,2);
	$aspek4 = number_format($data4[aspek_4]*100/15,2);
	$aspek5 = number_format($data4[aspek_5]*100/15,2);
	$aspek6 = number_format($data4[aspek_6]*100/15,2);
	$aspek7 = number_format($data4[aspek_7]*100/10,2);

	$nilai_rata = 0.15*$data4[aspek_1]*100/15 + 0.15*$data4[aspek_2]*100/15 + 0.15*$data4[aspek_3]*100/15 + 0.15*$data4[aspek_4]*100/15 + 0.15*$data4[aspek_5]*100/15 + 0.15*$data4[aspek_6]*100/15 + 0.1*$data4[aspek_7]*100/10;
	$nilai_rata = number_format($nilai_rata,2);

	$insert_sikap=mysqli_query($con,"INSERT INTO `kompre_nilai_sikap`
		( `nim`, `dosen`, `lokasi`, `instansi`,`tgl_mulai`,`tgl_selesai`,
			`aspek_1`, `aspek_2`, `aspek_3`, `aspek_4`, `aspek_5`,
			`aspek_6`, `aspek_7`, `nilai_rata`,
			`tgl_isi`, `tgl_approval`, `status_approval`)
		VALUES
		( '$data4[nim]', '$data4[dosen]', '$lokasi', '$data4[instansi]','$data4[tgl_mulai]','$data4[tgl_selesai]',
			'$aspek1', '$aspek2', '$aspek3', '$aspek4', '$aspek5',
			'$aspek6', '$aspek7', '$nilai_rata',
			'$data4[tgl_isi]', '$data4[tgl_approval]', '$data4[status_approval]')");
}


//Kepaniteraan Kedokteran Keluarga
//Tabel kdk_nilai_dops
$delete_data5 = mysqli_query($con,"TRUNCATE TABLE `kdk_nilai_dops`");
$data_lama5 = mysqli_query($con,"SELECT * FROM `kdk_nilai_dops_old` ORDER BY `id`");
while ($data5 = mysqli_fetch_array($data_lama5))
{
	$lokasi = addslashes($data5[lokasi]);

	$aspek1 = number_format($data5[aspek_1],2);
	$aspek2 = number_format($data5[aspek_2],2);
	$aspek3 = number_format($data5[aspek_3],2);
	$aspek4 = number_format($data5[aspek_4],2);

	$nilai_rata = 0.20*$data5[aspek_1] + 0.20*$data5[aspek_2] + 0.40*$data5[aspek_3] + 0.20*$data5[aspek_4];
	$nilai_rata = number_format($nilai_rata,2);

	$insert_dops=mysqli_query($con,"INSERT INTO `kdk_nilai_dops`
		( `nim`, `dosen`, `lokasi`,`instansi`, `tgl_mulai`,`tgl_selesai`,
			`aspek_1`, `aspek_2`, `aspek_3`, `aspek_4`,`nilai_rata`,
			`tgl_isi`, `tgl_approval`, `status_approval`)
		VALUES
		( '$data5[nim]', '$data5[dosen]', '$lokasi', '$data5[instansi]', '$data5[tgl_mulai]','$data5[tgl_selesai]',
			'$aspek1', '$aspek2', '$aspek3', '$aspek4','$nilai_rata',
			'$data5[tgl_isi]', '$data5[tgl_approval]', '$data5[status_approval]')");
}

//Tabel kdk_nilai_kasus
$delete_data6 = mysqli_query($con,"TRUNCATE TABLE `kdk_nilai_kasus`");
$data_lama6 = mysqli_query($con,"SELECT * FROM `kdk_nilai_kasus_old` ORDER BY `id`");
while ($data6 = mysqli_fetch_array($data_lama6))
{
	$lokasi = addslashes($data6[lokasi]);

	$aspek1 = number_format(5*$data6[aspek_1],2);
	$aspek2 = number_format(5*$data6[aspek_2],2);
	$aspek3 = number_format(5*$data6[aspek_3],2);
	$aspek4 = number_format(5*$data6[aspek_4],2);
	$aspek5 = number_format(5*$data6[aspek_5],2);

	$nilai_rata = $data6[aspek_1] + $data6[aspek_2] + $data6[aspek_3] + $data6[aspek_4] + $data6[aspek_5];
	$nilai_rata = number_format($nilai_rata,2);

	$insert_kasus=mysqli_query($con,"INSERT INTO `kdk_nilai_kasus`
		( `nim`, `dosen`, `lokasi`, `tgl_mulai`,`tgl_selesai`,`kasus`,
			`aspek_1`, `aspek_2`, `aspek_3`, `aspek_4`, `aspek_5`, `nilai_rata`,
			`tgl_isi`, `tgl_approval`, `status_approval`)
		VALUES
		( '$data6[nim]', '$data6[dosen]', '$lokasi', '$data6[tgl_mulai]','$data6[tgl_selesai]','$data6[kasus]',
			'$aspek1', '$aspek2', '$aspek3', '$aspek4', '$aspek5', '$nilai_rata',
			'$data6[tgl_isi]', '$data6[tgl_approval]', '$data6[status_approval]')");
}

//Tabel kdk_nilai_minicex
$delete_data7 = mysqli_query($con,"TRUNCATE TABLE `kdk_nilai_minicex`");
$data_lama7 = mysqli_query($con,"SELECT * FROM `kdk_nilai_minicex_old` ORDER BY `id`");
while ($data7 = mysqli_fetch_array($data_lama7))
{
	$aspek1 = number_format($data7[observasi_1]*$data7[aspek_1],2);
	$aspek2 = number_format($data7[observasi_2]*$data7[aspek_2],2);
	$aspek3 = number_format($data7[observasi_3]*$data7[aspek_3],2);
	$aspek4 = number_format($data7[observasi_4]*$data7[aspek_4],2);
	$aspek5 = number_format($data7[observasi_5]*$data7[aspek_5],2);
	$aspek6 = number_format($data7[observasi_6]*$data7[aspek_6],2);
	$aspek7 = number_format($data7[observasi_7]*$data7[aspek_7],2);
	$aspek8 = number_format($data7[observasi_8]*$data7[aspek_8],2);

	$lokasi = addslashes($data7[lokasi]);
	$diagnosis = addslashes($data7[diagnosis]);
	$ub_bagus = addslashes($data7[ub_bagus]);
	$ub_perbaikan = addslashes($data7[ub_perbaikan]);
	$action_plan = addslashes($data7[action_plan]);

	$jml_observasi = $data7[observasi_1]+$data7[observasi_2]+$data7[observasi_3]+$data7[observasi_4]+$data7[observasi_5]+$data7[observasi_6]+$data7[observasi_7]+$data7[observasi_8];
	$nilai_total = $data7[observasi_1]*$data7[aspek_1]+$data7[observasi_2]*$data7[aspek_2]+$data7[observasi_3]*$data7[aspek_3]+$data7[observasi_4]*$data7[aspek_4]+$data7[observasi_5]*$data7[aspek_5]+$data7[observasi_6]*$data7[aspek_6]+$data7[observasi_7]*$data7[aspek_7]+$data7[observasi_8]*$data7[aspek_8];
	if ($jml_observasi==0) $nilai_rata = 0;
	else $nilai_rata = $nilai_total/$jml_observasi;
	$nilai_rata = number_format($nilai_rata,2);

	$insert_minicex=mysqli_query($con,"INSERT INTO `kdk_nilai_minicex`
		( `nim`, `dosen`, `lokasi`, `instansi`,`no_ujian`,
			`diagnosis`, `situasi_ruangan`, `umur_pasien`, `jk_pasien`,
			`status_pasien`, `tingkat_kesulitan`, `fokus_kasus`,
			`aspek_1`, `observasi_1`, `aspek_2`, `observasi_2`,
			`aspek_3`, `observasi_3`, `aspek_4`, `observasi_4`,
			`aspek_5`, `observasi_5`, `aspek_6`, `observasi_6`,
			`aspek_7`, `observasi_7`, `aspek_8`, `observasi_8`,
			`nilai_rata`, `ub_bagus`, `ub_perbaikan`, `action_plan`,
			`waktu_observasi`, `waktu_ub`,
			`kepuasan_penilai`, `kepuasan_residen`,
			`tgl_isi`, `tgl_approval`, `status_approval`)
		VALUES
		( '$data7[nim]', '$data7[dosen]', '$lokasi', '$data7[instansi]', '$data7[no_ujian]',
			'$diagnosis', '$data7[situasi_ruangan]', '$data7[umur_pasien]', '$data7[jk_pasien]',
			'$data7[status_pasien]', '$data7[tingkat_kesulitan]', '$data7[fokus_kasus]',
			'$aspek1', '$data7[observasi_1]', '$aspek2', '$data7[observasi_2]',
			'$aspek3', '$data7[observasi_3]', '$aspek4', '$data7[observasi_4]',
			'$aspek5', '$data7[observasi_5]', '$aspek6', '$data7[observasi_6]',
			'$aspek7', '$data7[observasi_7]', '$aspek8', '$data7[observasi_8]',
			'$nilai_rata', '$ub_bagus', '$ub_perbaikan', '$action_plan',
			'$data7[waktu_observasi]', '$data7[waktu_ub]',
			'$data7[kepuasan_penilai]', '$data7[kepuasan_residen]',
			'$data7[tgl_isi]', '$data7[tgl_approval]', '$data7[status_approval]')");
}

//Tabel kdk_nilai_presensi
$delete_data8 = mysqli_query($con,"TRUNCATE TABLE `kdk_nilai_presensi`");
$data_lama8 = mysqli_query($con,"SELECT * FROM `kdk_nilai_presensi_old` ORDER BY `id`");
while ($data8 = mysqli_fetch_array($data_lama8))
{
	$lokasi = addslashes($data8[lokasi]);
	$nilai_total = number_format($data8[nilai_total],2);
	$nilai_masuk = number_format($data8[nilai_masuk],2);
	$nilai_absen = number_format($data8[nilai_absen],2);
	$nilai_ijin = number_format($data8[nilai_ijin],2);

	$hari_ijin = (int)(-1*$data8[nilai_ijin]/2);
	$hari_alpa = (int)(-1*$data8[nilai_absen]/5);
	$tgl_mulai = date_create($data8[tgl_mulai]);
	$tgl_selesai = date_create($data8[tgl_selesai]);
	$beda_hari = date_diff($tgl_mulai,$tgl_selesai);
	$hari_kerja = $beda_hari->days+1;
	$hari_masuk = (int)($hari_kerja-$hari_ijin-$hari_alpa);

	$lokasi = addslashes($data8[lokasi]);

	$insert_presensi=mysqli_query($con,"INSERT INTO `kdk_nilai_presensi`
		( `nim`, `dosen`, `lokasi`,`instansi`, `tgl_mulai`,`tgl_selesai`,
			`hari_kerja`, `hari_masuk`, `hari_ijin`, `hari_alpa`,
			`nilai_masuk`, `nilai_absen`, `nilai_ijin`, `nilai_total`,
			`tgl_isi`, `tgl_approval`, `status_approval`)
		VALUES
		( '$data8[nim]', '$data8[dosen]', '$lokasi', '$data8[instansi]', '$data8[tgl_mulai]','$data8[tgl_selesai]',
			'$hari_kerja','$hari_masuk','$hari_ijin','$hari_alpa',
			'$nilai_masuk', '$nilai_absen', '$nilai_ijin', '$nilai_total',
			'$data8[tgl_isi]', '$data8[tgl_approval]', '$data8[status_approval]')");
}

//Tabel kdk_nilai_sikap
$delete_data9 = mysqli_query($con,"TRUNCATE TABLE `kdk_nilai_sikap`");
$data_lama9 = mysqli_query($con,"SELECT * FROM `kdk_nilai_sikap_old` ORDER BY `id`");
while ($data9 = mysqli_fetch_array($data_lama9))
{
	$lokasi = addslashes($data9[lokasi]);

	$aspek1 = number_format($data9[aspek_1]*100/15,2);
	$aspek2 = number_format($data9[aspek_2]*100/15,2);
	$aspek3 = number_format($data9[aspek_3]*100/15,2);
	$aspek4 = number_format($data9[aspek_4]*100/15,2);
	$aspek5 = number_format($data9[aspek_5]*100/15,2);
	$aspek6 = number_format($data9[aspek_6]*100/15,2);
	$aspek7 = number_format($data9[aspek_7]*100/10,2);

	$nilai_rata = 0.15*$data9[aspek_1]*100/15 + 0.15*$data9[aspek_2]*100/15 + 0.15*$data9[aspek_3]*100/15 + 0.15*$data9[aspek_4]*100/15 + 0.15*$data9[aspek_5]*100/15 + 0.15*$data9[aspek_6]*100/15 + 0.1*$data9[aspek_7]*100/10;
	$nilai_rata = number_format($nilai_rata,2);

	$insert_sikap=mysqli_query($con,"INSERT INTO `kdk_nilai_sikap`
		( `nim`, `dosen`, `lokasi`, `instansi`,`tgl_mulai`,`tgl_selesai`,
			`aspek_1`, `aspek_2`, `aspek_3`, `aspek_4`, `aspek_5`,
			`aspek_6`, `aspek_7`, `nilai_rata`,
			`tgl_isi`, `tgl_approval`, `status_approval`)
		VALUES
		( '$data9[nim]', '$data9[dosen]', '$lokasi', '$data9[instansi]','$data9[tgl_mulai]','$data9[tgl_selesai]',
			'$aspek1', '$aspek2', '$aspek3', '$aspek4', '$aspek5',
			'$aspek6', '$aspek7', '$nilai_rata',
			'$data9[tgl_isi]', '$data9[tgl_approval]', '$data9[status_approval]')");
}

?>
