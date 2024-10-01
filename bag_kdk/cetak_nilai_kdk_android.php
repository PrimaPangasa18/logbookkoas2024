<?php

include "../config.php";
include "../fungsi.php";
include "../class.ezpdf.php";

error_reporting("E_ALL ^ E_NOTICE");
$nim_mhsw_kdk = $_GET['nim'];

$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw_kdk'"));
$pdf = new Cezpdf('A4');
$pdf->ezSetMargins(30, 40, 50, 50);
$pdf->selectFont('../fonts/Helvetica.afm');

//-------------------------------
//Rekap Nilai DPL / Laporan Kasus
//-------------------------------
//Nilai Rata Kasus Ibu
$kasus = mysqli_query($con, "SELECT * FROM `kdk_nilai_kasus` WHERE `nim`='$nim_mhsw_kdk' AND `kasus`='Ibu' AND `status_approval`='1'");
$jumlah_kasus = mysqli_num_rows($kasus);
$jum_nilai = mysqli_fetch_array(mysqli_query($con, "SELECT SUM(`aspek_1`), SUM(`aspek_2`), SUM(`aspek_3`), SUM(`aspek_4`), SUM(`aspek_5`), SUM(`nilai_rata`) FROM `kdk_nilai_kasus` WHERE `nim`='$nim_mhsw_kdk' AND `kasus`='Ibu' AND `status_approval`='1'"));
if ($jumlah_kasus > 0) $rata_aspek1_ibu =  $jum_nilai[0] / $jumlah_kasus;
else $rata_aspek1_ibu = 0.00;
if ($jumlah_kasus > 0) $rata_aspek2_ibu =  $jum_nilai[1] / $jumlah_kasus;
else $rata_aspek2_ibu = 0.00;
if ($jumlah_kasus > 0) $rata_aspek3_ibu =  $jum_nilai[2] / $jumlah_kasus;
else $rata_aspek3_ibu = 0.00;
if ($jumlah_kasus > 0) $rata_aspek4_ibu =  $jum_nilai[3] / $jumlah_kasus;
else $rata_aspek4_ibu = 0.00;
if ($jumlah_kasus > 0) $rata_aspek5_ibu =  $jum_nilai[4] / $jumlah_kasus;
else $rata_aspek5_ibu = 0.00;
if ($jumlah_kasus > 0) $total_kasus_ibu =  $jum_nilai[5] / $jumlah_kasus;
else $total_kasus_ibu = 0.00;

//Nilai Rata Kasus Bayi/Balita
$kasus = mysqli_query($con, "SELECT * FROM `kdk_nilai_kasus` WHERE `nim`='$nim_mhsw_kdk' AND `kasus`='Bayi/Balita' AND `status_approval`='1'");
$jumlah_kasus = mysqli_num_rows($kasus);
$jum_nilai = mysqli_fetch_array(mysqli_query($con, "SELECT SUM(`aspek_1`), SUM(`aspek_2`), SUM(`aspek_3`), SUM(`aspek_4`), SUM(`aspek_5`), SUM(`nilai_rata`) FROM `kdk_nilai_kasus` WHERE `nim`='$nim_mhsw_kdk' AND `kasus`='Bayi/Balita' AND `status_approval`='1'"));
if ($jumlah_kasus > 0) $rata_aspek1_bayi =  $jum_nilai[0] / $jumlah_kasus;
else $rata_aspek1_bayi = 0.00;
if ($jumlah_kasus > 0) $rata_aspek2_bayi =  $jum_nilai[1] / $jumlah_kasus;
else $rata_aspek2_bayi = 0.00;
if ($jumlah_kasus > 0) $rata_aspek3_bayi =  $jum_nilai[2] / $jumlah_kasus;
else $rata_aspek3_bayi = 0.00;
if ($jumlah_kasus > 0) $rata_aspek4_bayi =  $jum_nilai[3] / $jumlah_kasus;
else $rata_aspek4_bayi = 0.00;
if ($jumlah_kasus > 0) $rata_aspek5_bayi =  $jum_nilai[4] / $jumlah_kasus;
else $rata_aspek5_bayi = 0.00;
if ($jumlah_kasus > 0) $total_kasus_bayi =  $jum_nilai[5] / $jumlah_kasus;
else $total_kasus_bayi = 0.00;

//Nilai Rata Kasus Remaja
$kasus = mysqli_query($con, "SELECT * FROM `kdk_nilai_kasus` WHERE `nim`='$nim_mhsw_kdk' AND `kasus`='Remaja' AND `status_approval`='1'");
$jumlah_kasus = mysqli_num_rows($kasus);
$jum_nilai = mysqli_fetch_array(mysqli_query($con, "SELECT SUM(`aspek_1`), SUM(`aspek_2`), SUM(`aspek_3`), SUM(`aspek_4`), SUM(`aspek_5`), SUM(`nilai_rata`) FROM `kdk_nilai_kasus` WHERE `nim`='$nim_mhsw_kdk' AND `kasus`='Remaja' AND `status_approval`='1'"));
if ($jumlah_kasus > 0) $rata_aspek1_remaja =  $jum_nilai[0] / $jumlah_kasus;
else $rata_aspek1_remaja = 0.00;
if ($jumlah_kasus > 0) $rata_aspek2_remaja =  $jum_nilai[1] / $jumlah_kasus;
else $rata_aspek2_remaja = 0.00;
if ($jumlah_kasus > 0) $rata_aspek3_remaja =  $jum_nilai[2] / $jumlah_kasus;
else $rata_aspek3_remaja = 0.00;
if ($jumlah_kasus > 0) $rata_aspek4_remaja =  $jum_nilai[3] / $jumlah_kasus;
else $rata_aspek4_remaja = 0.00;
if ($jumlah_kasus > 0) $rata_aspek5_remaja =  $jum_nilai[4] / $jumlah_kasus;
else $rata_aspek5_remaja = 0.00;
if ($jumlah_kasus > 0) $total_kasus_remaja =  $jum_nilai[5] / $jumlah_kasus;
else $total_kasus_remaja = 0.00;

//Nilai Rata Kasus Dewasa
$kasus = mysqli_query($con, "SELECT * FROM `kdk_nilai_kasus` WHERE `nim`='$nim_mhsw_kdk' AND `kasus`='Dewasa' AND `status_approval`='1'");
$jumlah_kasus = mysqli_num_rows($kasus);
$jum_nilai = mysqli_fetch_array(mysqli_query($con, "SELECT SUM(`aspek_1`), SUM(`aspek_2`), SUM(`aspek_3`), SUM(`aspek_4`), SUM(`aspek_5`), SUM(`nilai_rata`) FROM `kdk_nilai_kasus` WHERE `nim`='$nim_mhsw_kdk' AND `kasus`='Dewasa' AND `status_approval`='1'"));
if ($jumlah_kasus > 0) $rata_aspek1_dewasa =  $jum_nilai[0] / $jumlah_kasus;
else $rata_aspek1_dewasa = 0.00;
if ($jumlah_kasus > 0) $rata_aspek2_dewasa =  $jum_nilai[1] / $jumlah_kasus;
else $rata_aspek2_dewasa = 0.00;
if ($jumlah_kasus > 0) $rata_aspek3_dewasa =  $jum_nilai[2] / $jumlah_kasus;
else $rata_aspek3_dewasa = 0.00;
if ($jumlah_kasus > 0) $rata_aspek4_dewasa =  $jum_nilai[3] / $jumlah_kasus;
else $rata_aspek4_dewasa = 0.00;
if ($jumlah_kasus > 0) $rata_aspek5_dewasa =  $jum_nilai[4] / $jumlah_kasus;
else $rata_aspek5_dewasa = 0.00;
if ($jumlah_kasus > 0) $total_kasus_dewasa =  $jum_nilai[5] / $jumlah_kasus;
else $total_kasus_dewasa = 0.00;

//Nilai Rata Kasus Lansia
$kasus = mysqli_query($con, "SELECT * FROM `kdk_nilai_kasus` WHERE `nim`='$nim_mhsw_kdk' AND `kasus`='Lansia' AND `status_approval`='1'");
$jumlah_kasus = mysqli_num_rows($kasus);
$jum_nilai = mysqli_fetch_array(mysqli_query($con, "SELECT SUM(`aspek_1`), SUM(`aspek_2`), SUM(`aspek_3`), SUM(`aspek_4`), SUM(`aspek_5`), SUM(`nilai_rata`) FROM `kdk_nilai_kasus` WHERE `nim`='$nim_mhsw_kdk' AND `kasus`='Lansia' AND `status_approval`='1'"));
if ($jumlah_kasus > 0) $rata_aspek1_lansia =  $jum_nilai[0] / $jumlah_kasus;
else $rata_aspek1_lansia = 0.00;
if ($jumlah_kasus > 0) $rata_aspek2_lansia =  $jum_nilai[1] / $jumlah_kasus;
else $rata_aspek2_lansia = 0.00;
if ($jumlah_kasus > 0) $rata_aspek3_lansia =  $jum_nilai[2] / $jumlah_kasus;
else $rata_aspek3_lansia = 0.00;
if ($jumlah_kasus > 0) $rata_aspek4_lansia =  $jum_nilai[3] / $jumlah_kasus;
else $rata_aspek4_lansia = 0.00;
if ($jumlah_kasus > 0) $rata_aspek5_lansia =  $jum_nilai[4] / $jumlah_kasus;
else $rata_aspek5_lansia = 0.00;
if ($jumlah_kasus > 0) $total_kasus_lansia =  $jum_nilai[5] / $jumlah_kasus;
else $total_kasus_lansia = 0.00;

$rata_nilai_kasus = ($total_kasus_ibu + $total_kasus_bayi + $total_kasus_remaja + $total_kasus_dewasa + $total_kasus_lansia) / 5;
$rata_nilai_dpl = number_format($rata_nilai_kasus, 2);

//-----------------
//Rekap Nilai Klinik
//-----------------
//Rekap Nilai Sikap
$jumlah_sikap_klinik = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `kdk_nilai_sikap` WHERE `nim`='$nim_mhsw_kdk' AND `instansi`='Klinik Pratama' AND `status_approval`='1'"));
$nilai_sikap_klinik = mysqli_fetch_array(mysqli_query($con, "SELECT SUM(`nilai_rata`) FROM `kdk_nilai_sikap` WHERE `nim`='$nim_mhsw_kdk' AND `instansi`='Klinik Pratama' AND `status_approval`='1'"));
if ($jumlah_sikap_klinik > 0) $rata_nilai_sikap_klinik = number_format($nilai_sikap_klinik[0] / $jumlah_sikap_klinik, 2);
else $rata_nilai_sikap_klinik = 0.00;
//Rekap Nilai DOPS
$jumlah_dops_klinik = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `kdk_nilai_dops` WHERE `nim`='$nim_mhsw_kdk' AND `instansi`='Klinik Pratama' AND `status_approval`='1'"));
$nilai_dops_klinik = mysqli_fetch_array(mysqli_query($con, "SELECT SUM(`nilai_rata`) FROM `kdk_nilai_dops` WHERE `nim`='$nim_mhsw_kdk' AND `instansi`='Klinik Pratama' AND `status_approval`='1'"));
if ($jumlah_dops_klinik > 0) $rata_nilai_dops_klinik = number_format($nilai_dops_klinik[0] / $jumlah_dops_klinik, 2);
else $rata_nilai_dops_klinik = 0.00;
//Rekap Nilai Mini-CEX
$jumlah_minicex_klinik = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `kdk_nilai_minicex` WHERE `nim`='$nim_mhsw_kdk' AND `instansi`='Klinik Pratama' AND `status_approval`='1'"));
$nilai_minicex_klinik = mysqli_fetch_array(mysqli_query($con, "SELECT SUM(`nilai_rata`) FROM `kdk_nilai_minicex` WHERE `nim`='$nim_mhsw_kdk' AND `instansi`='Klinik Pratama' AND `status_approval`='1'"));
if ($jumlah_minicex_klinik > 0) $rata_nilai_minicex_klinik = number_format($nilai_minicex_klinik[0] / $jumlah_minicex_klinik, 2);
else $rata_nilai_minicex_klinik = 0.00;
//Rekap Nilai Presensi Klinik
$jumlah_presensi_klinik = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `kdk_nilai_presensi` WHERE `nim`='$nim_mhsw_kdk' AND `instansi`='Klinik Pratama' AND `status_approval`='1'"));
$nilai_presensi_klinik = mysqli_fetch_array(mysqli_query($con, "SELECT SUM(`nilai_total`) FROM `kdk_nilai_presensi` WHERE `nim`='$nim_mhsw_kdk' AND `instansi`='Klinik Pratama' AND `status_approval`='1'"));
if ($jumlah_presensi_klinik > 0) $rata_nilai_presensi_klinik = number_format($nilai_presensi_klinik[0] / $jumlah_presensi_klinik, 2);
else $rata_nilai_presensi_klinik = 0.00;

$rata_nilai_klinik = number_format((2 * $rata_nilai_sikap_klinik + 1 * $rata_nilai_dops_klinik + 2 * $rata_nilai_minicex_klinik + 2 * $rata_nilai_presensi_klinik) / 7, 2);

//-----------------
//Rekap Nilai Puskesmas
//-----------------
//Rekap Nilai Sikap
$jumlah_sikap_puskesmas = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `kdk_nilai_sikap` WHERE `nim`='$nim_mhsw_kdk' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
$nilai_sikap_puskesmas = mysqli_fetch_array(mysqli_query($con, "SELECT SUM(`nilai_rata`) FROM `kdk_nilai_sikap` WHERE `nim`='$nim_mhsw_kdk' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
if ($jumlah_sikap_puskesmas > 0) $rata_nilai_sikap_puskesmas = number_format($nilai_sikap_puskesmas[0] / $jumlah_sikap_puskesmas, 2);
else $rata_nilai_sikap_puskesmas = 0.00;
//Rekap Nilai DOPS
$jumlah_dops_puskesmas = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `kdk_nilai_dops` WHERE `nim`='$nim_mhsw_kdk' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
$nilai_dops_puskesmas = mysqli_fetch_array(mysqli_query($con, "SELECT SUM(`nilai_rata`) FROM `kdk_nilai_dops` WHERE `nim`='$nim_mhsw_kdk' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
if ($jumlah_dops_puskesmas > 0) $rata_nilai_dops_puskesmas = number_format($nilai_dops_puskesmas[0] / $jumlah_dops_puskesmas, 2);
else $rata_nilai_dops_puskesmas = 0.00;
//Rekap Nilai Mini-CEX
$jumlah_minicex_puskesmas = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `kdk_nilai_minicex` WHERE `nim`='$nim_mhsw_kdk' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
$nilai_minicex_puskesmas = mysqli_fetch_array(mysqli_query($con, "SELECT SUM(`nilai_rata`) FROM `kdk_nilai_minicex` WHERE `nim`='$nim_mhsw_kdk' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
if ($jumlah_minicex_puskesmas > 0) $rata_nilai_minicex_puskesmas = number_format($nilai_minicex_puskesmas[0] / $jumlah_minicex_puskesmas, 2);
else $rata_nilai_minicex_puskesmas = 0.00;
//Rekap Nilai Presensi Puskesmas
$jumlah_presensi_puskesmas = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `kdk_nilai_presensi` WHERE `nim`='$nim_mhsw_kdk' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
$nilai_presensi_puskesmas = mysqli_fetch_array(mysqli_query($con, "SELECT SUM(`nilai_total`) FROM `kdk_nilai_presensi` WHERE `nim`='$nim_mhsw_kdk' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
if ($jumlah_presensi_puskesmas > 0) $rata_nilai_presensi_puskesmas = number_format($nilai_presensi_puskesmas[0] / $jumlah_presensi_puskesmas, 2);
else $rata_nilai_presensi_puskesmas = 0.00;

$rata_nilai_puskesmas = number_format((2 * $rata_nilai_sikap_puskesmas + 1 * $rata_nilai_dops_puskesmas + 2 * $rata_nilai_minicex_puskesmas + 2 * $rata_nilai_presensi_puskesmas) / 7, 2);

//Judul Rekap Total
$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `stase_M121` WHERE `nim`='$nim_mhsw_kdk'"));
$tanggal_mulai = tanggal_indo($data_stase['tgl_mulai']);
$tanggal_selesai = tanggal_indo($data_stase['tgl_selesai']);
$periode = $tanggal_mulai . " s.d. " . $tanggal_selesai;

$kolom1 = array('item' => "");
$tabel1[1] = array('item' => "REKAP NILAI KEPANITERAAN KEDOKTERAN KELUARGA");
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
$tabel2[3] = array('item' => "Kepaniteraan (Stase)", 'isi' => ": " . "KEPANITERAAN KEDOKTERAN KELUARGA");
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
$rincian_puskesmas = "Rata-Rata Nilai Puskesmas\r\n<i>Rincian:\r\na. Nilai Sikap: $rata_nilai_sikap_puskesmas\r\nb. Nilai DOPS: $rata_nilai_dops_puskesmas\r\nc. Nilai Mini-CEX: $rata_nilai_minicex_puskesmas\r\nd. Nilai Presensi / Kehadiran: $rata_nilai_presensi_puskesmas</i>";
$rincian_klinik = "Rata-Rata Nilai Klinik Pratama\r\n<i>Rincian:\r\na. Nilai Sikap: $rata_nilai_sikap_klinik\r\nb. Nilai DOPS: $rata_nilai_dops_klinik\r\nc. Nilai Mini-CEX: $rata_nilai_minicex_klinik\r\nd. Nilai Presensi / Kehadiran: $rata_nilai_presensi_klinik</i>";
$kolom4 = array('NO' => '', 'ITEM' => '', 'BOBOT' => '', 'NILAI' => '');
$tabel4[1] = array('NO' => '1', 'ITEM' => 'Rata-Rata Nilai Dosen Pembimbing Lapangan', 'BOBOT' => '30%', 'NILAI' => $rata_nilai_dpl);
$tabel4[2] = array('NO' => '2', 'ITEM' => $rincian_puskesmas, 'BOBOT' => '35%', 'NILAI' => $rata_nilai_puskesmas);
$tabel4[3] = array('NO' => '3', 'ITEM' => $rincian_klinik, 'BOBOT' => '35%', 'NILAI' => $rata_nilai_klinik);
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
$nilai_total = number_format((0.3 * $rata_nilai_dpl + 0.35 * $rata_nilai_puskesmas + 0.35 * $rata_nilai_klinik), 2);
$kolom5 = array('TOTAL' => "", 'NILAI' => "");
$tabel5[1] = array('TOTAL' => "Nilai Total:", 'NILAI' => "<b>$nilai_total</b>");
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

$ctt_nilai = "<i>Nilai Puskesmas / Klinik Pratama = (2xNilai Sikap + 1xNilai DOPS + 2xNilai Mini-CEX + 2xNilai Presensi)/7<i>";
$kolom5a = array('ITEM' => "");
$tabel5a[1] = array('ITEM' => $ctt_nilai);
$pdf->ezTable(
	$tabel5a,
	$kolom5a,
	"",
	array(
		'maxWidth' => 530,
		'width' => 510,
		'fontSize' => 8,
		'showHeadings' => 0,
		'shaded' => 0,
		'showLines' => 0,
		'titleFontSize' => 10,
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
$tabel6[1] = array('TOTAL' => 'Nilai Total Kepaniteraan Kedokteran Keluarga', 'NILAI' => ": " . $nilai_total);
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
$nip_kordik = mysqli_fetch_array(mysqli_query($con, "SELECT `username` FROM `admin` WHERE `stase`='K121'"));
$data_kordik = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$nip_kordik[username]'"));
$kordik = $data_kordik['nama'] . ", " . $data_kordik['gelar'];
$kolom6a = array('ITEM' => '');
$tabel6a[1] = array('ITEM' => 'Status: <b>DISETUJUI</b>');
$tabel6a[2] = array('ITEM' => 'pada tanggal _____________________');
$tabel6a[3] = array('ITEM' => 'Kordik Kepaniteraan Kedokteran Keluarga');
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
$pdf->addText(50, 25, 10, "$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Rekap Nilai Kepaniteraan Kedokteran Keluarga  <i>[hal 1]</i>");
$pdf->ezStream(array("Content-Disposition" => "Nilai Bagian Kedokteran Keluarga $data_mhsw[nim].pdf"));
