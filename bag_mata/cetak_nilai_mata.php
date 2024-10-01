<?php

include "../config.php";
include "../fungsi.php";
include "../class.ezpdf.php";

error_reporting("E_ALL ^ E_NOTICE");

if (empty($_COOKIE['user']) || empty($_COOKIE['pass'])) {
	echo "
		<script>
			window.location.href=\"../accessdenied.php\";
		</script>
		";
} else {
	if (!empty($_COOKIE['user']) and !empty($_COOKIE['pass']) and ($_COOKIE['level'] == 1 or $_COOKIE['level'] == 2 or $_COOKIE['level'] == 3 or $_COOKIE['level'] == 5)) {
		if ($_COOKIE['level'] == '5') $nim_mhsw_mata = $_COOKIE['user'];
		if ($_COOKIE['level'] == '1' or $_COOKIE['level'] == '2' or $_COOKIE['level'] == '3') $nim_mhsw_mata = $_GET['nim'];

		$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw_mata'"));
		$pdf = new Cezpdf('A4');
		$pdf->ezSetMargins(30, 40, 50, 50);
		$pdf->selectFont('../fonts/Helvetica.afm');

		//-------------------------------
		//Rekap Nilai Presentasi Kasus Besar
		//-------------------------------
		$data_nilai_presentasi = mysqli_fetch_array(mysqli_query($con, "SELECT `nilai_total` FROM `mata_nilai_presentasi` WHERE `nim`='$nim_mhsw_mata' AND `status_approval`='1'"));
		$nilai_presentasi = number_format($data_nilai_presentasi['nilai_total'], 2);

		//-------------------------------
		//Rekap Nilai Journal Reading
		//-------------------------------
		$data_nilai_jurnal = mysqli_fetch_array(mysqli_query($con, "SELECT `nilai_total` FROM `mata_nilai_jurnal` WHERE `nim`='$nim_mhsw_mata' AND `status_approval`='1'"));
		$nilai_jurnal = number_format($data_nilai_jurnal['nilai_total'], 2);

		//-------------------------------
		//Rekap Nilai Penyanggah
		//-------------------------------
		$jml_penyanggah = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `mata_nilai_penyanggah` WHERE `nim`='$nim_mhsw_mata' AND `status_approval`='1'"));
		$jml_nilai_penyanggah = mysqli_fetch_array(mysqli_query($con, "SELECT SUM(`nilai`) FROM `mata_nilai_penyanggah` WHERE `nim`='$nim_mhsw_mata' AND `status_approval`='1'"));
		if ($jml_penyanggah > 0) $nilai_penyanggah = $jml_nilai_penyanggah[0] / $jml_penyanggah;
		else $nilai_penyanggah = 0;
		$nilai_penyanggah = number_format($nilai_penyanggah, 2);

		//-------------------------------
		//Rekap Nilai Ujian Mini-Cex
		//-------------------------------
		$data_nilai_minicex = mysqli_fetch_array(mysqli_query($con, "SELECT `nilai_rata` FROM `mata_nilai_minicex` WHERE `nim`='$nim_mhsw_mata' AND `status_approval`='1'"));
		$nilai_minicex = number_format($data_nilai_minicex['nilai_rata'], 2);

		//-------------------------------
		//Rekap Nilai Test MCQ
		//-------------------------------
		$data_nilai_mcq = mysqli_fetch_array(mysqli_query($con, "SELECT MAX(`nilai`) FROM `mata_nilai_test` WHERE `nim`='$nim_mhsw_mata' AND `jenis_test`='6'"));
		$nilai_mcq = number_format($data_nilai_mcq[0], 2);

		//-------------------------------
		//Rekap Nilai Test OSCE
		//-------------------------------
		$data_nilai_osce = mysqli_fetch_array(mysqli_query($con, "SELECT MAX(`nilai`) FROM `mata_nilai_test` WHERE `nim`='$nim_mhsw_mata' AND `jenis_test`='5'"));
		$nilai_osce = number_format($data_nilai_osce[0], 2);

		//Total Nilai
		$nilai_total = number_format(0.15 * $nilai_presentasi + 0.05 * $nilai_penyanggah + 0.10 * $nilai_jurnal + 0.20 * $nilai_minicex + 0.25 * $nilai_mcq + 0.25 * $nilai_osce, 2);

		//Nilai Total Rata-rata
		if ($nilai_total <= 100 and $nilai_total >= 80) $grade = "A";
		if ($nilai_total < 80 and $nilai_total >= 70) $grade = "B";
		if ($nilai_total < 70 and $nilai_total >= 60) $grade = "C";
		if ($nilai_total < 60 and $nilai_total >= 50) $grade = "D";
		if ($nilai_total < 50) $grade = "E";

		//Judul Rekap Total
		$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `stase_M104` WHERE `nim`='$nim_mhsw_mata'"));
		$tanggal_mulai = tanggal_indo($data_stase['tgl_mulai']);
		$tanggal_selesai = tanggal_indo($data_stase['tgl_selesai']);
		$periode = $tanggal_mulai . " s.d. " . $tanggal_selesai;

		$kolom1 = array('item' => "");
		$tabel1[1] = array('item' => "REKAP NILAI KEPANITERAAN (STASE) ILMU KESEHATAN MATA");
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
		$tabel2[3] = array('item' => "Kepaniteraan (Stase)", 'isi' => ": " . "Ilmu Kesehatan Mata");
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
		$kolom4 = array('NO' => '', 'ITEM' => '', 'BOBOT' => '', 'NILAI' => '');
		$tabel4[1] = array('NO' => '1', 'ITEM' => 'Nilai Presentasi Kasus Besar', 'BOBOT' => '15%', 'NILAI' => $nilai_presentasi);
		$tabel4[2] = array('NO' => '2', 'ITEM' => 'Nilai Presentasi Journal Reading', 'BOBOT' => '10%', 'NILAI' => $nilai_jurnal);
		$tabel4[3] = array('NO' => '3', 'ITEM' => 'Rata Nilai Penyanggah', 'BOBOT' => '5%', 'NILAI' => $nilai_penyanggah);
		$tabel4[4] = array('NO' => '4', 'ITEM' => 'Nilai Ujian MINI-CEX', 'BOBOT' => '20%', 'NILAI' => $nilai_minicex);
		$tabel4[5] = array('NO' => '5', 'ITEM' => 'Nilai Ujian / Test MCQ', 'BOBOT' => '25%', 'NILAI' => $nilai_mcq);
		$tabel4[6] = array('NO' => '6', 'ITEM' => 'Nilai Ujian / Test OSCE / OSCA', 'BOBOT' => '25%', 'NILAI' => $nilai_osce);
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
		$kolom5 = array('TOTAL' => "", 'NILAI' => "");
		$tabel5[1] = array('TOTAL' => "Nilai Total (Jumlah Bobot x Nilai):", 'NILAI' => "<b>$nilai_total</b>");
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
		$kolom6 = array('TOTAL' => '', 'NILAI' => '');
		$tabel6[1] = array('TOTAL' => 'Nilai Total Kepaniteraan (Stase) Ilmu Kesehatan Mata', 'NILAI' => ": " . $nilai_total);
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
				'cols' => array('TOTAL' => array('width' => 265))
			)
		);
		$pdf->ezSetDy(-20, '');

		//Persetujuan Kordik
		$nip_kordik = mysqli_fetch_array(mysqli_query($con, "SELECT `username` FROM `admin` WHERE `stase`='K104'"));
		$data_kordik = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$nip_kordik[username]'"));
		$kordik = $data_kordik['nama'] . ", " . $data_kordik['gelar'];
		$kolom6a = array('ITEM' => '');
		$tabel6a[1] = array('ITEM' => 'Status: <b>DISETUJUI</b>');
		$tabel6a[2] = array('ITEM' => 'pada tanggal _____________________');
		$tabel6a[3] = array('ITEM' => 'Kordik Kepaniteraan (Stase) Ilmu Kesehatan Mata');
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
		$pdf->addText(50, 25, 10, "$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Rekap Nilai Kepaniteraan (Stase) Ilmu Kesehatan Mata    <i>[hal 1]</i>");
		$pdf->ezStream();
	} else
		echo "
		<script>
			window.location.href=\"../accessdenied.php\";
		</script>
		";
}
