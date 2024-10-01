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
		if ($_COOKIE['level'] == '5') $nim_mhsw_neuro = $_COOKIE['user'];
		if ($_COOKIE['level'] == '1' or $_COOKIE['level'] == '2' or $_COOKIE['level'] == '3') $nim_mhsw_neuro = $_GET['nim'];

		$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw_neuro'"));
		$pdf = new Cezpdf('A4');
		$pdf->ezSetMargins(30, 40, 50, 50);
		$pdf->selectFont('../fonts/Helvetica.afm');

		//-------------------------------
		//Rekap Nilai Penilaian Kasus CBD
		//-------------------------------
		$kasus_cbd = mysqli_query($con, "SELECT DISTINCT `kasus_ke` FROM `neuro_nilai_cbd` WHERE `nim`='$nim_mhsw_neuro' AND `status_approval`='1'");
		$jumlah_nilai = 0;
		while ($data_cbd = mysqli_fetch_array($kasus_cbd)) {
			$nilai = mysqli_fetch_array(mysqli_query($con, "SELECT MAX(`nilai_rata`) FROM `neuro_nilai_cbd` WHERE `nim`='$nim_mhsw_neuro' AND `status_approval`='1' AND `kasus_ke`='$data_cbd[kasus_ke]'"));
			$jumlah_nilai = $jumlah_nilai + $nilai[0];
		}
		$nilai_cbd = $jumlah_nilai / 5;
		$nilai_cbd = number_format($nilai_cbd, 2);

		//-------------------------------
		//Rekap Nilai Penilaian Journal Reading
		//-------------------------------
		$data_nilai_jurnal = mysqli_fetch_array(mysqli_query($con, "SELECT MAX(`nilai_total`) FROM `neuro_nilai_jurnal` WHERE `nim`='$nim_mhsw_neuro' AND `status_approval`='1'"));
		$nilai_jurnal = $data_nilai_jurnal[0];
		$nilai_jurnal = number_format($nilai_jurnal, 2);

		//-------------------------------
		//Rekap Nilai Penilaian Ujian SPV
		//-------------------------------
		$data_nilai_spv = mysqli_fetch_array(mysqli_query($con, "SELECT MAX(`nilai`) FROM `neuro_nilai_spv` WHERE `nim`='$nim_mhsw_neuro' AND `status_approval`='1'"));
		$nilai_spv = $data_nilai_spv[0];
		$nilai_spv = number_format($nilai_spv, 2);

		//-------------------------------
		//Rekap Nilai Ujian Mini-CEX
		//-------------------------------
		$data_nilai_minicex = mysqli_fetch_array(mysqli_query($con, "SELECT MAX(`nilai_rata`) FROM `neuro_nilai_minicex` WHERE `nim`='$nim_mhsw_neuro' AND `status_approval`='1'"));
		$nilai_minicex = $data_nilai_minicex[0];
		$nilai_minicex = number_format($nilai_minicex, 2);

		//-------------------------------
		//Rekap Nilai Test
		//-------------------------------
		$nilai_osce = mysqli_fetch_array(mysqli_query($con, "SELECT MAX(`nilai`) FROM `neuro_nilai_test` WHERE `nim`='$nim_mhsw_neuro' AND `status_approval`='1' AND `jenis_test`='5'"));
		$nilai_mcq = mysqli_fetch_array(mysqli_query($con, "SELECT MAX(`nilai`) FROM `neuro_nilai_test` WHERE `nim`='$nim_mhsw_neuro' AND `status_approval`='1' AND `jenis_test`='6'"));
		$nilai_test = ($nilai_osce[0] + $nilai_mcq[0]) / 2;
		$nilai_test = number_format($nilai_test, 2);

		//Judul Rekap Total
		$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `stase_M092` WHERE `nim`='$nim_mhsw_neuro'"));
		$tanggal_mulai = tanggal_indo($data_stase['tgl_mulai']);
		$tanggal_selesai = tanggal_indo($data_stase['tgl_selesai']);
		$periode = $tanggal_mulai . " s.d. " . $tanggal_selesai;

		$kolom1 = array('item' => "");
		$tabel1[1] = array('item' => "REKAP NILAI KEPANITERAAN (STASE) NEUROLOGI");
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
		$tabel2[3] = array('item' => "Kepaniteraan (Stase)", 'isi' => ": " . "Neurologi");
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
		$tabel4[1] = array('NO' => '1', 'ITEM' => 'Rata Nilai Kasus CBD (5 Kasus)', 'BOBOT' => '10%', 'NILAI' => $nilai_cbd);
		$tabel4[2] = array('NO' => '2', 'ITEM' => 'Rata Nilai Journal Reading', 'BOBOT' => '10%', 'NILAI' => $nilai_jurnal);
		$tabel4[3] = array('NO' => '3', 'ITEM' => 'Rata Nilai Ujian SPV', 'BOBOT' => '15%', 'NILAI' => $nilai_spv);
		$tabel4[4] = array('NO' => '4', 'ITEM' => 'Rata Nilai Ujian MINI-CEX', 'BOBOT' => '15%', 'NILAI' => $nilai_minicex);
		$tabel4[5] = array('NO' => '5', 'ITEM' => "Rata Nilai Ujian / Test:\r\n  Nilai Ujian OSCE: $nilai_osce[0]\r\n  Nilai Ujian MCQ: $nilai_mcq[0]", 'BOBOT' => '50%', 'NILAI' => $nilai_test);
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
		$nilai_total = number_format(0.1 * $nilai_cbd + 0.1 * $nilai_jurnal + 0.15 * $nilai_spv + 0.15 * $nilai_minicex + 0.5 * $nilai_test, 2);
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
		$tabel6[1] = array('TOTAL' => 'Nilai Total Kepaniteraan (Stase) Neurologi', 'NILAI' => ": " . $nilai_total);
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
		$nip_kordik = mysqli_fetch_array(mysqli_query($con, "SELECT `username` FROM `admin` WHERE `stase`='K092'"));
		$data_kordik = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$nip_kordik[username]'"));
		$kordik = $data_kordik['nama'] . ", " . $data_kordik['gelar'];
		$kolom6a = array('ITEM' => '');
		$tabel6a[1] = array('ITEM' => 'Status: <b>DISETUJUI</b>');
		$tabel6a[2] = array('ITEM' => 'pada tanggal _____________________');
		$tabel6a[3] = array('ITEM' => 'Kordik Kepaniteraan (Stase) Neurologi');
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
		$pdf->addText(50, 25, 10, "$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Rekap Nilai Kepaniteraan (Stase) Neurologi    <i>[hal 1]</i>");
		$pdf->ezStream();
	} else
		echo "
		<script>
			window.location.href=\"../accessdenied.php\";
		</script>
		";
}
