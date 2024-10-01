<?php

include "../config.php";
include "../fungsi.php";
include "../class.ezpdf.php";

error_reporting("E_ALL ^ E_NOTICE");

$nim_mhsw_kompre = $_GET['nim'];

$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw_kompre'"));
$pdf = new Cezpdf('A4');
$pdf->ezSetMargins(30, 40, 50, 50);
$pdf->selectFont('../fonts/Helvetica.afm');

//-------------------------------
//Rekap Nilai Laporan
//-------------------------------
//---------------------
//Rekap Nilai Puskesmas
//---------------------
//Nilai Rata Laporan
$laporan = mysqli_query($con, "SELECT * FROM `kompre_nilai_laporan` WHERE `nim`='$nim_mhsw_kompre' AND `instansi`='Puskesmas' AND `status_approval`='1'");
$jumlah_laporan = mysqli_num_rows($laporan);
$jum_nilai = mysqli_fetch_array(mysqli_query($con, "SELECT
			SUM(`aspek1_ind`), SUM(`aspek2_ind`), SUM(`aspek3_ind`),SUM(`aspek4_ind`), SUM(`aspek5_ind`),
			SUM(`aspek1_kelp`), SUM(`aspek2_kelp`), SUM(`aspek3_kelp`), SUM(`aspek4_kelp`), SUM(`aspek5_kelp`),
			SUM(`nilai_rata_ind`), SUM(`nilai_rata_kelp`)
			FROM `kompre_nilai_laporan` WHERE `nim`='$nim_mhsw_kompre' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
if ($jumlah_laporan > 0) $rata_aspek1_ind_puskesmas =  number_format($jum_nilai[0] / $jumlah_laporan, 2);
else $rata_aspek1_ind_puskesmas = 0.00;
if ($jumlah_laporan > 0) $rata_aspek2_ind_puskesmas =  number_format($jum_nilai[1] / $jumlah_laporan, 2);
else $rata_aspek2_ind_puskesmas = 0.00;
if ($jumlah_laporan > 0) $rata_aspek3_ind_puskesmas =  number_format($jum_nilai[2] / $jumlah_laporan, 2);
else $rata_aspek3_ind_puskesmas = 0.00;
if ($jumlah_laporan > 0) $rata_aspek4_ind_puskesmas =  number_format($jum_nilai[3] / $jumlah_laporan, 2);
else $rata_aspek4_ind_puskesmas = 0.00;
if ($jumlah_laporan > 0) $rata_aspek5_ind_puskesmas =  number_format($jum_nilai[4] / $jumlah_laporan, 2);
else $rata_aspek5_ind_puskesmas = 0.00;
if ($jumlah_laporan > 0) $total_laporan_ind_puskesmas =  number_format($jum_nilai[10] / $jumlah_laporan, 2);
else $total_laporan_ind_puskesmas = 0.00;

if ($jumlah_laporan > 0) $rata_aspek1_kelp_puskesmas =  number_format($jum_nilai[5] / $jumlah_laporan, 2);
else $rata_aspek1_kelp_puskesmas = 0.00;
if ($jumlah_laporan > 0) $rata_aspek2_kelp_puskesmas =  number_format($jum_nilai[6] / $jumlah_laporan, 2);
else $rata_aspek2_kelp_puskesmas = 0.00;
if ($jumlah_laporan > 0) $rata_aspek3_kelp_puskesmas =  number_format($jum_nilai[7] / $jumlah_laporan, 2);
else $rata_aspek3_kelp_puskesmas = 0.00;
if ($jumlah_laporan > 0) $rata_aspek4_kelp_puskesmas =  number_format($jum_nilai[8] / $jumlah_laporan, 2);
else $rata_aspek4_kelp_puskesmas = 0.00;
if ($jumlah_laporan > 0) $rata_aspek5_kelp_puskesmas =  number_format($jum_nilai[9] / $jumlah_laporan, 2);
else $rata_aspek5_kelp_puskesmas = 0.00;
if ($jumlah_laporan > 0) $total_laporan_kelp_puskesmas =  number_format($jum_nilai[11] / $jumlah_laporan, 2);
else $total_laporan_kelp_puskesmas = 0.00;

//---------------------
//Rekap Nilai Rumah Sakit
//---------------------
//Nilai Rata laporan
$laporan = mysqli_query($con, "SELECT * FROM `kompre_nilai_laporan` WHERE `nim`='$nim_mhsw_kompre' AND `instansi`='Rumah Sakit' AND `status_approval`='1'");
$jumlah_laporan = mysqli_num_rows($laporan);
$jum_nilai = mysqli_fetch_array(mysqli_query($con, "SELECT
		  SUM(`aspek1_ind`), SUM(`aspek2_ind`), SUM(`aspek3_ind`),SUM(`aspek4_ind`), SUM(`aspek5_ind`),
		  SUM(`aspek1_kelp`), SUM(`aspek2_kelp`), SUM(`aspek3_kelp`), SUM(`aspek4_kelp`), SUM(`aspek5_kelp`),
			SUM(`nilai_rata_ind`), SUM(`nilai_rata_kelp`)
		  FROM `kompre_nilai_laporan` WHERE `nim`='$nim_mhsw_kompre' AND `instansi`='Rumah Sakit' AND `status_approval`='1'"));
if ($jumlah_laporan > 0) $rata_aspek1_ind_rumkit =  number_format($jum_nilai[0] / $jumlah_laporan, 2);
else $rata_aspek1_ind_rumkit = 0;
if ($jumlah_laporan > 0) $rata_aspek2_ind_rumkit =  number_format($jum_nilai[1] / $jumlah_laporan, 2);
else $rata_aspek2_ind_rumkit = 0;
if ($jumlah_laporan > 0) $rata_aspek3_ind_rumkit =  number_format($jum_nilai[2] / $jumlah_laporan, 2);
else $rata_aspek3_ind_rumkit = 0;
if ($jumlah_laporan > 0) $rata_aspek4_ind_rumkit =  number_format($jum_nilai[3] / $jumlah_laporan, 2);
else $rata_aspek4_ind_rumkit = 0;
if ($jumlah_laporan > 0) $rata_aspek5_ind_rumkit =  number_format($jum_nilai[4] / $jumlah_laporan, 2);
else $rata_aspek5_ind_rumkit = 0;
if ($jumlah_laporan > 0) $total_laporan_ind_rumkit =  number_format($jum_nilai[10] / $jumlah_laporan, 2);
else $total_laporan_ind_rumkit = 0;

if ($jumlah_laporan > 0) $rata_aspek1_kelp_rumkit =  number_format($jum_nilai[5] / $jumlah_laporan, 2);
else $rata_aspek1_kelp_rumkit = 0;
if ($jumlah_laporan > 0) $rata_aspek2_kelp_rumkit =  number_format($jum_nilai[6] / $jumlah_laporan, 2);
else $rata_aspek2_kelp_rumkit = 0;
if ($jumlah_laporan > 0) $rata_aspek3_kelp_rumkit =  number_format($jum_nilai[7] / $jumlah_laporan, 2);
else $rata_aspek3_kelp_rumkit = 0;
if ($jumlah_laporan > 0) $rata_aspek4_kelp_rumkit =  number_format($jum_nilai[8] / $jumlah_laporan, 2);
else $rata_aspek4_kelp_rumkit = 0;
if ($jumlah_laporan > 0) $rata_aspek5_kelp_rumkit =  number_format($jum_nilai[9] / $jumlah_laporan, 2);
else $rata_aspek5_kelp_rumkit = 0;
if ($jumlah_laporan > 0) $total_laporan_kelp_rumkit =  number_format($jum_nilai[11] / $jumlah_laporan, 2);
else $total_laporan_kelp_rumkit = 0;

$rata_laporan_puskesmas = number_format(0.5 * $total_laporan_ind_puskesmas + 0.5 * $total_laporan_kelp_puskesmas, 2);
$rata_laporan_rumkit = number_format(0.5 * $total_laporan_ind_rumkit + 0.5 * $total_laporan_kelp_rumkit, 2);
$rata_nilai_laporan = number_format(0.5 * $rata_laporan_puskesmas + 0.5 * $rata_laporan_rumkit, 2);
//--------------------

//-------------------------------
//Rekap Nilai Sikap
//---------------------
//Rekap Nilai Puskesmas
//---------------------
//Nilai Rata Sikap
$sikap = mysqli_query($con, "SELECT * FROM `kompre_nilai_sikap` WHERE `nim`='$nim_mhsw_kompre' AND `instansi`='Puskesmas' AND `status_approval`='1'");
$jumlah_sikap = mysqli_num_rows($sikap);
$jum_nilai = mysqli_fetch_array(mysqli_query($con, "SELECT SUM(`aspek_1`), SUM(`aspek_2`), SUM(`aspek_3`), SUM(`aspek_4`), SUM(`aspek_5`), SUM(`aspek_6`), SUM(`aspek_7`), SUM(`nilai_rata`) FROM `kompre_nilai_sikap` WHERE `nim`='$nim_mhsw_kompre' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
if ($jumlah_sikap > 0) $rata_aspek1_puskesmas =  number_format($jum_nilai[0] / $jumlah_sikap, 2);
else $rata_aspek1_puskesmas = 0.00;
if ($jumlah_sikap > 0) $rata_aspek2_puskesmas =  number_format($jum_nilai[1] / $jumlah_sikap, 2);
else $rata_aspek2_puskesmas = 0.00;
if ($jumlah_sikap > 0) $rata_aspek3_puskesmas =  number_format($jum_nilai[2] / $jumlah_sikap, 2);
else $rata_aspek3_puskesmas = 0.00;
if ($jumlah_sikap > 0) $rata_aspek4_puskesmas =  number_format($jum_nilai[3] / $jumlah_sikap, 2);
else $rata_aspek4_puskesmas = 0.00;
if ($jumlah_sikap > 0) $rata_aspek5_puskesmas =  number_format($jum_nilai[4] / $jumlah_sikap, 2);
else $rata_aspek5_puskesmas = 0.00;
if ($jumlah_sikap > 0) $rata_aspek6_puskesmas =  number_format($jum_nilai[5] / $jumlah_sikap, 2);
else $rata_aspek6_puskesmas = 0.00;
if ($jumlah_sikap > 0) $rata_aspek7_puskesmas =  number_format($jum_nilai[6] / $jumlah_sikap, 2);
else $rata_aspek7_puskesmas = 0.00;
if ($jumlah_sikap > 0) $total_sikap_puskesmas =  number_format($jum_nilai[7] / $jumlah_sikap, 2);
else $total_sikap_puskesmas = 0.00;

//---------------------
//Rekap Nilai Rumah Sakit
//---------------------
//Nilai Rata Sikap
$sikap = mysqli_query($con, "SELECT * FROM `kompre_nilai_sikap` WHERE `nim`='$nim_mhsw_kompre' AND `instansi`='Rumah Sakit' AND `status_approval`='1'");
$jumlah_sikap = mysqli_num_rows($sikap);
$jum_nilai = mysqli_fetch_array(mysqli_query($con, "SELECT SUM(`aspek_1`), SUM(`aspek_2`), SUM(`aspek_3`), SUM(`aspek_4`), SUM(`aspek_5`), SUM(`aspek_6`), SUM(`aspek_7`), SUM(`nilai_rata`) FROM `kompre_nilai_sikap` WHERE `nim`='$nim_mhsw_kompre' AND `instansi`='Rumah Sakit' AND `status_approval`='1'"));
if ($jumlah_sikap > 0) $rata_aspek1_rumkit =  number_format($jum_nilai[0] / $jumlah_sikap, 2);
else $rata_aspek1_rumkit = 0.00;
if ($jumlah_sikap > 0) $rata_aspek2_rumkit =  number_format($jum_nilai[1] / $jumlah_sikap, 2);
else $rata_aspek2_rumkit = 0.00;
if ($jumlah_sikap > 0) $rata_aspek3_rumkit =  number_format($jum_nilai[2] / $jumlah_sikap, 2);
else $rata_aspek3_rumkit = 0.00;
if ($jumlah_sikap > 0) $rata_aspek4_rumkit =  number_format($jum_nilai[3] / $jumlah_sikap, 2);
else $rata_aspek4_rumkit = 0.00;
if ($jumlah_sikap > 0) $rata_aspek5_rumkit =  number_format($jum_nilai[4] / $jumlah_sikap, 2);
else $rata_aspek5_rumkit = 0.00;
if ($jumlah_sikap > 0) $rata_aspek6_rumkit =  number_format($jum_nilai[5] / $jumlah_sikap, 2);
else $rata_aspek6_rumkit = 0.00;
if ($jumlah_sikap > 0) $rata_aspek7_rumkit =  number_format($jum_nilai[6] / $jumlah_sikap, 2);
else $rata_aspek7_rumkit = 0.00;
if ($jumlah_sikap > 0) $total_sikap_rumkit =  number_format($jum_nilai[7] / $jumlah_sikap, 2);
else $total_sikap_rumkit = 0.00;

$rata_nilai_sikap = number_format(0.5 * $total_sikap_puskesmas + 0.5 * $total_sikap_rumkit, 2);
//---------------------

//---------------------
//Rekap Nilai CBD
//---------------------
//Nilai Rata CBD
$daftar_cbd = mysqli_query($con, "SELECT * FROM `kompre_nilai_cbd` WHERE `nim`='$nim_mhsw_kompre' AND `status_approval`='1'");
$jumlah_cbd = mysqli_num_rows($daftar_cbd);
$jum_nilai = mysqli_fetch_array(mysqli_query($con, "SELECT SUM(`aspek_1`), SUM(`aspek_2`), SUM(`aspek_3`), SUM(`aspek_4`), SUM(`nilai_rata`) FROM `kompre_nilai_cbd` WHERE `nim`='$nim_mhsw_kompre' AND `status_approval`='1'"));
if ($jumlah_cbd > 0) $rata_aspek1 =  number_format($jum_nilai[0] / $jumlah_cbd, 2);
else $rata_aspek1 = 0.00;
if ($jumlah_cbd > 0) $rata_aspek2 =  number_format($jum_nilai[1] / $jumlah_cbd, 2);
else $rata_aspek2 = 0.00;
if ($jumlah_cbd > 0) $rata_aspek3 =  number_format($jum_nilai[2] / $jumlah_cbd, 2);
else $rata_aspek3 = 0.00;
if ($jumlah_cbd > 0) $rata_aspek4 =  number_format($jum_nilai[3] / $jumlah_cbd, 2);
else $rata_aspek4 = 0.00;
if ($jumlah_cbd > 0) $total_cbd =  number_format($jum_nilai[4] / $jumlah_cbd, 2);
else $total_cbd = 0.00;
//---------------------

//---------------------
//Rekap Presensi / Kehadiran
//---------------------
//---------------------
//Rekap Nilai Puskesmas
//---------------------
//Nilai Rata Presensi
$presensi = mysqli_query($con, "SELECT * FROM `kompre_nilai_presensi` WHERE `nim`='$nim_mhsw_kompre' AND `instansi`='Puskesmas' AND `status_approval`='1'");
$jumlah_presensi = mysqli_num_rows($presensi);
$jum_nilai = mysqli_fetch_array(mysqli_query($con, "SELECT SUM(`nilai_masuk`), SUM(`nilai_absen`), SUM(`nilai_ijin`), SUM(`nilai_total`) FROM `kompre_nilai_presensi` WHERE `nim`='$nim_mhsw_kompre' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
if ($jumlah_presensi > 0) $rata_nilai_masuk_puskesmas =  number_format($jum_nilai[0] / $jumlah_presensi, 2);
else $rata_nilai_masuk_puskesmas = 0.00;
if ($jumlah_presensi > 0) $rata_nilai_absen_puskesmas =  number_format($jum_nilai[1] / $jumlah_presensi, 2);
else $rata_nilai_absen_puskesmas = 0.00;
if ($jumlah_presensi > 0) $rata_nilai_ijin_puskesmas =  number_format($jum_nilai[2] / $jumlah_presensi, 2);
else $rata_nilai_ijin_puskesmas = 0.00;
if ($jumlah_presensi > 0) $rata_nilai_total_puskesmas =  number_format($jum_nilai[3] / $jumlah_presensi, 2);
else $rata_nilai_total_puskesmas = 0.00;
$total_presensi_puskesmas = number_format($rata_nilai_total_puskesmas, 2);

//---------------------
//Rekap Nilai Rumah Sakit
//---------------------
//Nilai Rata Presensi
$presensi = mysqli_query($con, "SELECT * FROM `kompre_nilai_presensi` WHERE `nim`='$nim_mhsw_kompre' AND `instansi`='Rumah Sakit' AND `status_approval`='1'");
$jumlah_presensi = mysqli_num_rows($presensi);
$jum_nilai = mysqli_fetch_array(mysqli_query($con, "SELECT SUM(`nilai_masuk`), SUM(`nilai_absen`), SUM(`nilai_ijin`), SUM(`nilai_total`) FROM `kompre_nilai_presensi` WHERE `nim`='$nim_mhsw_kompre' AND `instansi`='Rumah Sakit' AND `status_approval`='1'"));
if ($jumlah_presensi > 0) $rata_nilai_masuk_rumkit =  number_format($jum_nilai[0] / $jumlah_presensi, 2);
else $rata_nilai_masuk_rumkit = 0.00;
if ($jumlah_presensi > 0) $rata_nilai_absen_rumkit =  number_format($jum_nilai[1] / $jumlah_presensi, 2);
else $rata_nilai_absen_rumkit = 0.00;
if ($jumlah_presensi > 0) $rata_nilai_ijin_rumkit =  number_format($jum_nilai[2] / $jumlah_presensi, 2);
else $rata_nilai_ijin_rumkit = 0.00;
if ($jumlah_presensi > 0) $rata_nilai_total_rumkit =  number_format($jum_nilai[3] / $jumlah_presensi, 2);
else $rata_nilai_total_rumkit = 0.00;
$total_presensi_rumkit = number_format($rata_nilai_total_rumkit, 2);

$rata_nilai_presensi = number_format(0.5 * $total_presensi_puskesmas + 0.5 * $total_presensi_rumkit, 2);
//---------------------

//Judul Rekap Total
$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `stase_M121` WHERE `nim`='$nim_mhsw_kompre'"));
$tanggal_mulai = tanggal_indo($data_stase['tgl_mulai']);
$tanggal_selesai = tanggal_indo($data_stase['tgl_selesai']);
$periode = $tanggal_mulai . " s.d. " . $tanggal_selesai;

$kolom1 = array('item' => "");
$tabel1[1] = array('item' => "REKAP NILAI KEPANITERAAN KOMPREHENSIP");
$tabel1[2] = array('item' => "LOGBOOK KOAS PENDIDIKAN PROFESI DOKTER");
$tabel1[3] = array('item' => "FAKULTAS KEDOKTERAN - UNIVERSITAS DIPONEGORO");
$pdf->ezTable(
	$tabel1,
	$kolom1,
	"",
	array(
		'maxWidth' => 540,
		'width' => 520,
		'fontSize' => 10,
		'showHeadings' => 0,
		'shaded' => 0,
		'showLines' => 0,
		'titleFontSize' => 12,
		'xPos' => 'center',
		'xOrientation' => 'center',
		'rowGap' => 0,
		'cols' => array('item' => array('justification' => 'left'))
	)
);

//Data Mahasiswa
$pdf->ezSetDy(-20, '');
$kolom2 = array('item' => "", 'isi' => "");
$tabel2[1] = array('item' => "Nama Dokter Muda", 'isi' => ": " . "$data_mhsw[nama]");
$tabel2[2] = array('item' => "NIM", 'isi' => ": " . "$data_mhsw[nim]");
$tabel2[3] = array('item' => "Kepaniteraan (Stase)", 'isi' => ": " . "KEPANITERAAN KOMPREHENSIP");
$tabel2[4] = array('item' => "Periode", 'isi' => ": " . "$periode");
$pdf->ezTable(
	$tabel2,
	$kolom2,
	"",
	array(
		'maxWidth' => 540,
		'width' => 520,
		'fontSize' => 10,
		'showHeadings' => 0,
		'shaded' => 0,
		'showLines' => 0,
		'titleFontSize' => 12,
		'xPos' => 'center',
		'xOrientation' => 'center',
		'rowGap' => 0,
		'cols' => array('item' => array('justification' => 'left', 'width' => 110))
	)
);
$pdf->ezSetDy(-20, '');

//----------------
//Header Tabel Rekap
$kolom3 = array('NO' => '', 'ITEM' => '', 'BOBOT' => '', 'NILAI' => '');
$tabel3[1] = array('NO' => '<b>No</b>', 'ITEM' => '<b>Item Penilaian</b>', 'BOBOT' => '<b>Bobot</b>', 'NILAI' => '<b>Nilai (0-100)</b>');
$pdf->ezTable(
	$tabel3,
	$kolom3,
	"",
	array(
		'maxWidth' => 530,
		'width' => 510,
		'fontSize' => 10,
		'showHeadings' => 0,
		'shaded' => 0,
		'showLines' => 2,
		'titleFontSize' => 12,
		'xPos' => 'center',
		'xOrientation' => 'center',
		'rowGap' => 3,
		'showBgCol' => 0,
		'cols' => array('NO' => array('width' => 30, 'justification' => 'center'), 'ITEM' => array('justification' => 'center'), 'BOBOT' => array('width' => 100, 'justification' => 'center'), 'NILAI' => array('width' => 100, 'justification' => 'center'))
	)
);
//Isi Tabel Rekap
$rincian_laporan = "Rata-Rata Nilai Laporan\r\n<i>Rincian:\r\na. Nilai Laporan di Puskesmas: $rata_laporan_puskesmas\r\nb. Nilai Laporan di Rumah Sakit: $rata_laporan_rumkit</i>";
$rincian_sikap = "Rata-Rata Nilai Sikap\r\n<i>Rincian:\r\na. Nilai Sikap di Puskesmas: $total_sikap_puskesmas\r\nb. Nilai Sikap di Rumah Sakit: $total_sikap_rumkit</i>";
$rincian_cbd = "Rata-Rata Nilai Case Based Discussion (CBD)";
$rincian_presensi = "Rata-Rata Nilai Presensi / Kehadiran\r\n<i>Rincian:\r\na. Nilai Presensi / Kehadiran di Puskesmas: $total_presensi_puskesmas\r\nb. Nilai Presensi / Kehadiran di Rumah Sakit: $total_presensi_rumkit</i>";
$kolom4 = array('NO' => '', 'ITEM' => '', 'BOBOT' => '', 'NILAI' => '');
$tabel4[1] = array('NO' => '1', 'ITEM' => $rincian_laporan, 'BOBOT' => '20%', 'NILAI' => $rata_nilai_laporan);
$tabel4[2] = array('NO' => '2', 'ITEM' => $rincian_sikap, 'BOBOT' => '30%', 'NILAI' => $rata_nilai_sikap);
$tabel4[3] = array('NO' => '3', 'ITEM' => $rincian_cbd, 'BOBOT' => '20%', 'NILAI' => $total_cbd);
$tabel4[4] = array('NO' => '4', 'ITEM' => $rincian_presensi, 'BOBOT' => '30%', 'NILAI' => $rata_nilai_presensi);
$pdf->ezTable(
	$tabel4,
	$kolom4,
	"",
	array(
		'maxWidth' => 530,
		'width' => 510,
		'fontSize' => 10,
		'showHeadings' => 0,
		'shaded' => 0,
		'showLines' => 2,
		'titleFontSize' => 12,
		'xPos' => 'center',
		'xOrientation' => 'center',
		'rowGap' => 3,
		'showBgCol' => 0,
		'cols' => array('NO' => array('width' => 30, 'justification' => 'center'), 'BOBOT' => array('width' => 100, 'justification' => 'center'), 'NILAI' => array('width' => 100, 'justification' => 'center'))
	)
);
//Total Nilai
$nilai_total = number_format((0.2 * $rata_nilai_laporan + 0.3 * $rata_nilai_sikap + 0.2 * $total_cbd + 0.3 * $rata_nilai_presensi), 2);
$kolom5 = array('TOTAL' => "", 'NILAI' => "");
$tabel5[1] = array('TOTAL' => "Nilai Total:", 'NILAI' => $nilai_total);
$pdf->ezTable(
	$tabel5,
	$kolom5,
	"",
	array(
		'maxWidth' => 530,
		'width' => 510,
		'fontSize' => 10,
		'showHeadings' => 0,
		'shaded' => 0,
		'showLines' => 2,
		'titleFontSize' => 12,
		'xPos' => 'center',
		'xOrientation' => 'center',
		'rowGap' => 3,
		'showBgCol' => 0,
		'cols' => array('TOTAL' => array('justification' => 'right'), 'NILAI' => array('width' => 100, 'justification' => 'center'))
	)
);
$pdf->ezSetDy(-20, '');

//Nilai Total Rata-rata
if ($nilai_total <= 100 and $nilai_total >= 80) $grade = "A";
if ($nilai_total < 80 and $nilai_total >= 70) $grade = "B";
if ($nilai_total < 70 and $nilai_total >= 60) $grade = "C";
if ($nilai_total < 60 and $nilai_total >= 50) $grade = "D";
if ($nilai_total < 50) $grade = "E";
$kolom6 = array('TOTAL' => '', 'NILAI' => '');
$tabel6[1] = array('TOTAL' => 'Nilai Total Kepaniteraan Komprehensip', 'NILAI' => ": " . $nilai_total);
$tabel6[2] = array('TOTAL' => 'Ekivalensi Nilai Huruf', 'NILAI' => ": " . $grade);
$pdf->ezTable(
	$tabel6,
	$kolom6,
	"",
	array(
		'maxWidth' => 530,
		'width' => 510,
		'fontSize' => 10,
		'showHeadings' => 0,
		'shaded' => 0,
		'showLines' => 0,
		'titleFontSize' => 12,
		'xPos' => 'center',
		'xOrientation' => 'center',
		'rowGap' => 3,
		'showBgCol' => 0,
		'cols' => array('TOTAL' => array('width' => 230))
	)
);
$pdf->ezSetDy(-20, '');

//Persetujuan Kordik
$admin_kordik = "K122";
$nip_kordik = mysqli_fetch_array(mysqli_query($con, "SELECT `username` FROM `admin` WHERE `stase`='$admin_kordik'"));
$data_kordik = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$nip_kordik[username]'"));
$kordik = $data_kordik['nama'] . ", " . $data_kordik['gelar'];
$kolom6a = array('ITEM' => '');
$tabel6a[1] = array('ITEM' => 'Status: <b>DISETUJUI</b>');
$tabel6a[2] = array('ITEM' => 'pada tanggal _____________________');
$tabel6a[3] = array('ITEM' => 'Kordik Kepaniteraan Komprehensip');
$tabel6a[4] = array('ITEM' => '');
$tabel6a[5] = array('ITEM' => '');
$tabel6a[6] = array('ITEM' => $kordik);
$tabel6a[7] = array('ITEM' => 'NIP: ' . $data_kordik['nip']);
$pdf->ezTable(
	$tabel6a,
	$kolom6a,
	"",
	array(
		'maxWidth' => 530,
		'width' => 510,
		'fontSize' => 10,
		'showHeadings' => 0,
		'shaded' => 0,
		'showLines' => 0,
		'titleFontSize' => 12,
		'xPos' => 'center',
		'xOrientation' => 'center',
		'rowGap' => 1,
		'showBgCol' => 0,
		'cols' => array('ITEM' => array('justification' => 'right'))
	)
);

$pdf->ezSetDy(-10, '');
$pdf->addText(50, 25, 10, "$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Rekap Nilai Kepaniteraan Komprehensip             <i>[hal 1]</i>");
$pdf->ezStream(array("Content-Disposition" => "Nilai Bagian Komprehensip $data_mhsw[nim].pdf"));
