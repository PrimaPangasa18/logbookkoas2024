<?php

include "../config.php";
include "../fungsi.php";
include "../class.ezpdf.php";

error_reporting("E_ALL ^ E_NOTICE");

$nim_mhsw_ika = $_GET['nim'];

$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw_ika'"));
$pdf = new Cezpdf('A4');
$pdf->ezSetMargins(30, 40, 50, 50);
$pdf->selectFont('../fonts/Helvetica.afm');

//-------------------------------
//Rekap Nilai Mini-CEX

//Rata Nilai Mini-CEX Infeksi
$minicex_infeksi1 = mysqli_fetch_array(mysqli_query($con, "SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$nim_mhsw_ika' AND `evaluasi`='1' AND `status_approval`='1'"));
$minicex_infeksi2 = mysqli_fetch_array(mysqli_query($con, "SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$nim_mhsw_ika' AND `evaluasi`='2' AND `status_approval`='1'"));
$minicex_infeksi3 = mysqli_fetch_array(mysqli_query($con, "SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$nim_mhsw_ika' AND `evaluasi`='3' AND `status_approval`='1'"));
$minicex_infeksi4 = mysqli_fetch_array(mysqli_query($con, "SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$nim_mhsw_ika' AND `evaluasi`='4' AND `status_approval`='1'"));
if (!empty($minicex_infeksi1)) $nilai_minicex_infeksi1 = $minicex_infeksi1['nilai_rata'];
else $nilai_minicex_infeksi1 = 0;
$nilai_minicex_infeksi1 = number_format($nilai_minicex_infeksi1, 2);
if (!empty($minicex_infeksi2)) $nilai_minicex_infeksi2 = $minicex_infeksi2['nilai_rata'];
else $nilai_minicex_infeksi2 = 0;
$nilai_minicex_infeksi2 = number_format($nilai_minicex_infeksi2, 2);
if (!empty($minicex_infeksi3)) $nilai_minicex_infeksi3 = $minicex_infeksi3['nilai_rata'];
else $nilai_minicex_infeksi3 = 0;
$nilai_minicex_infeksi3 = number_format($nilai_minicex_infeksi3, 2);
if (!empty($minicex_infeksi4)) $nilai_minicex_infeksi4 = $minicex_infeksi4['nilai_rata'];
else $nilai_minicex_infeksi4 = 0;
$nilai_minicex_infeksi4 = number_format($nilai_minicex_infeksi4, 2);

$nilai_rata_minicex_infeksi = ($nilai_minicex_infeksi1 + $nilai_minicex_infeksi2 + $nilai_minicex_infeksi3 + $nilai_minicex_infeksi4) / 4;
$nilai_rata_minicex_infeksi = number_format($nilai_rata_minicex_infeksi, 2);

//Rata Nilai Mini-CEX Non-Infeksi
$minicex_noninfeksi1 = mysqli_fetch_array(mysqli_query($con, "SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$nim_mhsw_ika' AND `evaluasi`='5' AND `status_approval`='1'"));
$minicex_noninfeksi2 = mysqli_fetch_array(mysqli_query($con, "SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$nim_mhsw_ika' AND `evaluasi`='6' AND `status_approval`='1'"));
$minicex_noninfeksi3 = mysqli_fetch_array(mysqli_query($con, "SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$nim_mhsw_ika' AND `evaluasi`='7' AND `status_approval`='1'"));
$minicex_noninfeksi4 = mysqli_fetch_array(mysqli_query($con, "SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$nim_mhsw_ika' AND `evaluasi`='8' AND `status_approval`='1'"));
$minicex_noninfeksi5 = mysqli_fetch_array(mysqli_query($con, "SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$nim_mhsw_ika' AND `evaluasi`='9' AND `status_approval`='1'"));
$minicex_noninfeksi6 = mysqli_fetch_array(mysqli_query($con, "SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$nim_mhsw_ika' AND `evaluasi`='10' AND `status_approval`='1'"));
$minicex_noninfeksi7 = mysqli_fetch_array(mysqli_query($con, "SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$nim_mhsw_ika' AND `evaluasi`='11' AND `status_approval`='1'"));
if (!empty($minicex_noninfeksi1)) $nilai_minicex_noninfeksi1 = $minicex_noninfeksi1['nilai_rata'];
else $nilai_minicex_noninfeksi1 = 0;
$nilai_minicex_noninfeksi1 = number_format($nilai_minicex_noninfeksi1, 2);
if (!empty($minicex_noninfeksi2)) $nilai_minicex_noninfeksi2 = $minicex_noninfeksi2['nilai_rata'];
else $nilai_minicex_noninfeksi2 = 0;
$nilai_minicex_noninfeksi2 = number_format($nilai_minicex_noninfeksi2, 2);
if (!empty($minicex_noninfeksi3)) $nilai_minicex_noninfeksi3 = $minicex_noninfeksi3['nilai_rata'];
else $nilai_minicex_noninfeksi3 = 0;
$nilai_minicex_noninfeksi3 = number_format($nilai_minicex_noninfeksi3, 2);
if (!empty($minicex_noninfeksi4)) $nilai_minicex_noninfeksi4 = $minicex_noninfeksi4['nilai_rata'];
else $nilai_minicex_noninfeksi4 = 0;
$nilai_minicex_noninfeksi4 = number_format($nilai_minicex_noninfeksi4, 2);
if (!empty($minicex_noninfeksi5)) $nilai_minicex_noninfeksi5 = $minicex_noninfeksi5['nilai_rata'];
else $nilai_minicex_noninfeksi5 = 0;
$nilai_minicex_noninfeksi5 = number_format($nilai_minicex_noninfeksi5, 2);
if (!empty($minicex_noninfeksi6)) $nilai_minicex_noninfeksi6 = $minicex_noninfeksi6['nilai_rata'];
else $nilai_minicex_noninfeksi6 = 0;
$nilai_minicex_noninfeksi6 = number_format($nilai_minicex_noninfeksi6, 2);
if (!empty($minicex_noninfeksi7)) $nilai_minicex_noninfeksi7 = $minicex_noninfeksi7['nilai_rata'];
else $nilai_minicex_noninfeksi7 = 0;
$nilai_minicex_noninfeksi7 = number_format($nilai_minicex_noninfeksi7, 2);

$nilai_rata_minicex_noninfeksi = ($nilai_minicex_noninfeksi1 + $nilai_minicex_noninfeksi2 + $nilai_minicex_noninfeksi3 + $nilai_minicex_noninfeksi4 + $nilai_minicex_noninfeksi5 + $nilai_minicex_noninfeksi6 + $nilai_minicex_noninfeksi7) / 7;
$nilai_rata_minicex_noninfeksi = number_format($nilai_rata_minicex_noninfeksi, 2);

//Rata Nilai Mini-CEX ERIA
$minicex_eria = mysqli_fetch_array(mysqli_query($con, "SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$nim_mhsw_ika' AND `evaluasi`='12' AND `status_approval`='1'"));
if (!empty($minicex_eria)) $nilai_minicex_eria = $minicex_eria['nilai_rata'];
else $nilai_minicex_eria = 0;
$nilai_minicex_eria = number_format($nilai_minicex_eria, 2);

//Rata Nilai Mini-CEX Perinatologi
$minicex_perinatologi = mysqli_fetch_array(mysqli_query($con, "SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$nim_mhsw_ika' AND `evaluasi`='13' AND `status_approval`='1'"));
if (!empty($minicex_perinatologi)) $nilai_minicex_perinatologi = $minicex_perinatologi['nilai_rata'];
else $nilai_minicex_perinatologi = 0;
$nilai_minicex_perinatologi = number_format($nilai_minicex_perinatologi, 2);

//Rata Nilai Mini-CEX RS Jejaring
$minicex_jejaring = mysqli_fetch_array(mysqli_query($con, "SELECT `nilai_rata` FROM `ika_nilai_minicex` WHERE `nim`='$nim_mhsw_ika' AND `evaluasi`='14' AND `status_approval`='1'"));
if (!empty($minicex_jejaring)) $nilai_minicex_jejaring = $minicex_jejaring['nilai_rata'];
else $nilai_minicex_jejaring = 0;
$nilai_minicex_jejaring = number_format($nilai_minicex_jejaring, 2);

//End of Rekap Nilai Mini-CEX
//-------------------------------

//-------------------------------
//Rekap Nilai Penilaian DOPS
//-------------------------------
$data_nilai_dops = mysqli_fetch_array(mysqli_query($con, "SELECT `nilai_rata` FROM `ika_nilai_dops` WHERE `nim`='$nim_mhsw_ika' AND `status_approval`='1'"));
if (!empty($data_nilai_dops)) $nilai_dops = $data_nilai_dops['nilai_rata'];
else $nilai_dops = 0;
$nilai_dops = number_format($nilai_dops, 2);

//-------------------------------
//Rekap Nilai Penilaian CBD
//-------------------------------
$data_nilai_cbd = mysqli_fetch_array(mysqli_query($con, "SELECT `nilai_rata` FROM `ika_nilai_cbd` WHERE `nim`='$nim_mhsw_ika' AND `status_approval`='1'"));
if (!empty($data_nilai_cbd)) $nilai_cbd = $data_nilai_cbd['nilai_rata'];
else $nilai_cbd = 0;
$nilai_cbd = number_format($nilai_cbd, 2);

//-------------------------------
//Rekap Nilai Penilaian Kasus Besar
//-------------------------------
$data_nilai_kasus = mysqli_fetch_array(mysqli_query($con, "SELECT `nilai_rata` FROM `ika_nilai_kasus` WHERE `nim`='$nim_mhsw_ika' AND `status_approval`='1'"));
if (!empty($data_nilai_kasus)) $nilai_kasus = $data_nilai_kasus['nilai_rata'];
else $nilai_kasus = 0;
$nilai_kasus = number_format($nilai_kasus, 2);

//-------------------------------
//Rekap Nilai Penilaian Journal Reading
//-------------------------------
$data_nilai_jurnal = mysqli_fetch_array(mysqli_query($con, "SELECT `nilai_rata` FROM `ika_nilai_jurnal` WHERE `nim`='$nim_mhsw_ika' AND `status_approval`='1'"));
if (!empty($data_nilai_jurnal)) $nilai_jurnal = $data_nilai_jurnal['nilai_rata'];
else $nilai_jurnal = 0;
$nilai_jurnal = number_format($nilai_jurnal, 2);

//-------------------------------
//Rekap Nilai Penilaian Mini-PAT
//-------------------------------
$data_nilai_minipat = mysqli_fetch_array(mysqli_query($con, "SELECT `nilai_rata` FROM `ika_nilai_minipat` WHERE `nim`='$nim_mhsw_ika' AND `status_approval`='1'"));
if (!empty($data_nilai_minipat)) $nilai_minipat = $data_nilai_minipat['nilai_rata'];
else $nilai_minipat = 0;
$nilai_minipat = number_format($nilai_minipat, 2);

//-------------------------------
//Rekap Nilai Penilaian Ujian Akhir
//-------------------------------
$data_nilai_ujian = mysqli_fetch_array(mysqli_query($con, "SELECT MAX(`nilai_rata`) FROM `ika_nilai_ujian` WHERE `nim`='$nim_mhsw_ika' AND `status_approval`='1'"));
if (!empty($data_nilai_ujian)) $nilai_ujian = $data_nilai_ujian[0];
else $nilai_ujian = 0;
$nilai_ujian = number_format($nilai_ujian, 2);


//-------------------------------
//Rekap Nilai Test
//-------------------------------
$pretest = mysqli_fetch_array(mysqli_query($con, "SELECT MAX(`nilai`) FROM `ika_nilai_test` WHERE `nim`='$nim_mhsw_ika' AND `jenis_test`='1' AND `status_approval`='1'"));
$posttest = mysqli_fetch_array(mysqli_query($con, "SELECT MAX(`nilai`) FROM `ika_nilai_test` WHERE `nim`='$nim_mhsw_ika' AND `jenis_test`='2' AND `status_approval`='1'"));
$osce = mysqli_fetch_array(mysqli_query($con, "SELECT MAX(`nilai`) FROM `ika_nilai_test` WHERE `nim`='$nim_mhsw_ika' AND `jenis_test`='5' AND `status_approval`='1'"));
$nilai_pretest = number_format($pretest[0], 2);
$nilai_posttest = number_format($posttest[0], 2);
$nilai_osce = number_format($osce[0], 2);

//Judul Rekap Total
$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `stase_M113` WHERE `nim`='$nim_mhsw_ika'"));
$tanggal_mulai = tanggal_indo($data_stase['tgl_mulai']);
$tanggal_selesai = tanggal_indo($data_stase['tgl_selesai']);
$periode = $tanggal_mulai . " s.d. " . $tanggal_selesai;

$kolom1 = array('item' => "");
$tabel1[1] = array('item' => "REKAP NILAI KEPANITERAAN (STASE) ILMU KESEHATAN ANAK");
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
$tabel2[1] = array('item' => "Nama Mahasiswa", 'isi' => ": " . "$data_mhsw[nama]");
$tabel2[2] = array('item' => "NIM", 'isi' => ": " . "$data_mhsw[nim]");
$tabel2[3] = array('item' => "Kepaniteraan (Stase)", 'isi' => ": " . "Ilmu Kesehatan Anak");
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

$item1 = "Nilai Pre-Test";
$item2 = "Nilai Post-Test";
$item3 = "Nilai Mini-CEX Infeksi:\r\n- Respirologi = $nilai_minicex_infeksi1\r\n- Gastroenterologi = $nilai_minicex_infeksi2\r\n- Infeksi Tropik = $nilai_minicex_infeksi3\r\n- Neurologi = $nilai_minicex_infeksi4";
$item4 = "Nilai Mini-CEX Non Infeksi:\r\n- Hemato-onkologi = $nilai_minicex_noninfeksi1\r\n- Alergi imunologi = $nilai_minicex_noninfeksi2\r\n- Nefrologi = $nilai_minicex_noninfeksi3\r\n- Kardiologi = $nilai_minicex_noninfeksi4\r\n- Pediatri Sosial = $nilai_minicex_noninfeksi5\r\n- Nutrisi Pediatri dan Metabolik = $nilai_minicex_noninfeksi6\r\n- Endokrinologi = $nilai_minicex_noninfeksi7";
$item5 = "Nilai Mini-CEX ERIA";
$item6 = "Nilai Mini-CEX Perinatologi";
$item7 = "Nilai Mini-CEX Kasus RS Jejaring";
$item8 = "Nilai CBD Kasus Poliklinik";
$item9 = "Nilai DOPS Ketrampilan Klinis";
$item10 = "Nilai Laporan Kasus Besar";
$item11 = "Nilai Journal Reading";
$item12 = "Nilai Mini-PAT (Attitude, Keaktifan Diskusi DMOM)";
$item13 = "Nilai Ujian OSCE";
$item14 = "Nilai Ujian Akhir Kepaniteraan (Ujian Supervisor)";

//Isi Tabel Rekap
$kolom4 = array('NO' => '', 'ITEM' => '', 'BOBOT' => '', 'NILAI' => '');
$tabel4[1] = array('NO' => '1', 'ITEM' => "$item1", 'BOBOT' => '2.5%', 'NILAI' => $nilai_pretest);
$tabel4[2] = array('NO' => '2', 'ITEM' => "$item2", 'BOBOT' => '7.5%', 'NILAI' => $nilai_posttest);
$tabel4[3] = array('NO' => '3', 'ITEM' => "$item3", 'BOBOT' => '2%', 'NILAI' => $nilai_rata_minicex_infeksi);
$tabel4[4] = array('NO' => '4', 'ITEM' => "$item4", 'BOBOT' => '2%', 'NILAI' => $nilai_rata_minicex_noninfeksi);
$tabel4[5] = array('NO' => '5', 'ITEM' => "$item5", 'BOBOT' => '1%', 'NILAI' => $nilai_minicex_eria);
$tabel4[6] = array('NO' => '6', 'ITEM' => "$item6", 'BOBOT' => '1%', 'NILAI' => $nilai_minicex_perinatologi);
$tabel4[7] = array('NO' => '7', 'ITEM' => "$item7", 'BOBOT' => '2%', 'NILAI' => $nilai_minicex_jejaring);
$tabel4[8] = array('NO' => '8', 'ITEM' => "$item8", 'BOBOT' => '2%', 'NILAI' => $nilai_cbd);
$tabel4[9] = array('NO' => '9', 'ITEM' => "$item9", 'BOBOT' => '10%', 'NILAI' => $nilai_dops);
$tabel4[10] = array('NO' => '10', 'ITEM' => "$item10", 'BOBOT' => '10%', 'NILAI' => $nilai_kasus);
$tabel4[11] = array('NO' => '11', 'ITEM' => "$item11", 'BOBOT' => '10%', 'NILAI' => $nilai_jurnal);
$tabel4[12] = array('NO' => '12', 'ITEM' => "$item12", 'BOBOT' => '10%', 'NILAI' => $nilai_minipat);
$tabel4[13] = array('NO' => '13', 'ITEM' => "$item13", 'BOBOT' => '10%', 'NILAI' => $nilai_osce);
$tabel4[14] = array('NO' => '14', 'ITEM' => "$item14", 'BOBOT' => '30%', 'NILAI' => $nilai_ujian);
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
$nilai_total = number_format(0.025 * $nilai_pretest + 0.075 * $nilai_posttest + 0.02 * $nilai_rata_minicex_infeksi + 0.02 * $nilai_rata_minicex_noninfeksi + 0.01 * $nilai_minicex_eria + 0.01 * $nilai_minicex_perinatologi + 0.02 * $nilai_minicex_jejaring + 0.02 * $nilai_cbd + 0.1 * $nilai_dops + 0.1 * $nilai_kasus + 0.1 * $nilai_jurnal + 0.1 * $nilai_minipat + 0.1 * $nilai_osce + 0.3 * $nilai_ujian, 2);
$kolom5 = array('TOTAL' => "", 'NILAI' => "");
$tabel5[1] = array('TOTAL' => "Nilai Total (Bobot x Nilai):", 'NILAI' => "<b>$nilai_total</b>");
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
$tabel6[1] = array('TOTAL' => 'Nilai Total Kepaniteraan (Stase) Ilmu Kesehatan Anak', 'NILAI' => ": " . "<b>$nilai_total</b>");
$tabel6[2] = array('TOTAL' => 'Ekivalensi Nilai Huruf', 'NILAI' => ": " . "<b>$grade</b>");
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
		'cols' => array('TOTAL' => array('width' => 265))
	)
);
$pdf->ezSetDy(-20, '');

//Persetujuan Kordik
$nip_kordik = mysqli_fetch_array(mysqli_query($con, "SELECT `username` FROM `admin` WHERE `stase`='K113'"));
$data_kordik = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$nip_kordik[username]'"));
$kordik = $data_kordik['nama'] . ", " . $data_kordik['gelar'];
$kolom6a = array('ITEM' => '');
$tabel6a[1] = array('ITEM' => 'Status: <b>DISETUJUI</b>');
$tabel6a[2] = array('ITEM' => 'pada tanggal _____________________');
$tabel6a[3] = array('ITEM' => 'Kordik Kepaniteraan (Stase) Ilmu Kesehatan Anak');
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
$pdf->addText(50, 25, 10, "$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Rekap Nilai Kepaniteraan (Stase) IKA           <i>[hal 1]</i>");
$pdf->ezStream(array("Content-Disposition" => "Nilai Bagian IKA $data_mhsw[nim].pdf"));
