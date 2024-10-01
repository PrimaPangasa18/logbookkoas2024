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
		if ($_COOKIE['level'] == '5') $nim_mhsw_bedah = $_COOKIE['user'];
		if ($_COOKIE['level'] == '1' or $_COOKIE['level'] == '2' or $_COOKIE['level'] == '3') $nim_mhsw_bedah = $_GET['nim'];

		$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw_bedah'"));
		$pdf = new Cezpdf('A4');
		$pdf->ezSetMargins(30, 40, 50, 50);
		$pdf->selectFont('../fonts/Helvetica.afm');

		//---------------------
		//Rekap Nilai Mentoring
		//---------------------
		//Nilai Rata Mentoring
		$data_mentor1 = mysqli_query($con, "SELECT * FROM `bedah_nilai_mentor` WHERE `nim`='$nim_mhsw_bedah' AND `bulan_ke`='1' AND `status_approval`='1'");
		$jum_mentor1 = mysqli_num_rows($data_mentor1);
		$mentor1 = mysqli_fetch_array($data_mentor1);
		if ($jum_mentor1 > 0) $nilai_mentor1 = number_format($mentor1['nilai_rata'], 2);
		else $nilai_mentor1 = "0.00";
		$data_mentor2 = mysqli_query($con, "SELECT * FROM `bedah_nilai_mentor` WHERE `nim`='$nim_mhsw_bedah' AND `bulan_ke`='2' AND `status_approval`='1'");
		$jum_mentor2 = mysqli_num_rows($data_mentor2);
		$mentor2 = mysqli_fetch_array($data_mentor2);
		if ($jum_mentor2 > 0) $nilai_mentor2 = number_format($mentor2['nilai_rata'], 2);
		else $nilai_mentor2 = "0.00";
		//---------------------

		//-------------------------------
		//Rekap Nilai Test
		//-------------------------------
		$pre_test = mysqli_fetch_array(mysqli_query($con, "SELECT MAX(`nilai`) FROM `bedah_nilai_test` WHERE `nim`='$nim_mhsw_bedah' AND `jenis_test`='1' AND `status_approval`='1'"));
		$post_test = mysqli_fetch_array(mysqli_query($con, "SELECT MAX(`nilai`) FROM `bedah_nilai_test` WHERE `nim`='$nim_mhsw_bedah' AND `jenis_test`='2' AND `status_approval`='1'"));
		$osce = mysqli_fetch_array(mysqli_query($con, "SELECT MAX(`nilai`) FROM `bedah_nilai_test` WHERE `nim`='$nim_mhsw_bedah' AND `jenis_test`='5' AND `status_approval`='1'"));
		$skill_lab = mysqli_fetch_array(mysqli_query($con, "SELECT MAX(`nilai`) FROM `bedah_nilai_test` WHERE `nim`='$nim_mhsw_bedah' AND `jenis_test`='15' AND `status_approval`='1'"));

		$nilai_pre_test = number_format($pre_test[0], 2);
		$nilai_post_test = number_format($post_test[0], 2);
		$nilai_osce = number_format($osce[0], 2);
		$nilai_skill_lab = number_format($skill_lab[0], 2);

		//Judul Rekap Total
		$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `stase_M101` WHERE `nim`='$nim_mhsw_bedah'"));
		$tanggal_mulai = tanggal_indo($data_stase['tgl_mulai']);
		$tanggal_selesai = tanggal_indo($data_stase['tgl_selesai']);
		$periode = $tanggal_mulai . " s.d. " . $tanggal_selesai;

		$kolom1 = array('item' => "");
		$tabel1[1] = array('item' => "REKAP NILAI KEPANITERAAN (STASE) ILMU BEDAH");
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
		$tabel2[3] = array('item' => "Kepaniteraan (Stase)", 'isi' => ": " . "Ilmu Bedah");
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
		$tabel4[1] = array('NO' => '1', 'ITEM' => "Nilai Mentoring:\r\nA. Mentoring Bulan ke-1\r\nB. Mentoring Bulan ke-2", 'BOBOT' => "\r\n25%\r\n25%", 'NILAI' => "\r\n$nilai_mentor1\r\n$nilai_mentor2");
		$tabel4[2] = array('NO' => '2', 'ITEM' => "Nilai Pre-Test", 'BOBOT' => '5%', 'NILAI' => $nilai_pre_test);
		$tabel4[3] = array('NO' => '3', 'ITEM' => "Nilai Post-Test", 'BOBOT' => '10%', 'NILAI' => $nilai_post_test);
		$tabel4[4] = array('NO' => '4', 'ITEM' => "Nilai OSCE", 'BOBOT' => '25%', 'NILAI' => $nilai_osce);
		$tabel4[5] = array('NO' => '5', 'ITEM' => "Nilai Skill Lab", 'BOBOT' => '10%', 'NILAI' => $nilai_skill_lab);
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
		$nilai_total = number_format(0.25 * $nilai_mentor1 + 0.25 * $nilai_mentor2 + 0.05 * $nilai_pre_test + 0.1 * $nilai_post_test + 0.25 * $nilai_osce + 0.1 * $nilai_skill_lab, 2);
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
		$pdf->ezSetDy(-20, '');

		//Nilai Total Rata-rata
		if ($nilai_total <= 100 and $nilai_total >= 80) $grade = "A";
		if ($nilai_total < 80 and $nilai_total >= 70) $grade = "B";
		if ($nilai_total < 70 and $nilai_total >= 60) $grade = "C";
		if ($nilai_total < 60 and $nilai_total >= 50) $grade = "D";
		if ($nilai_total < 50) $grade = "E";
		$kolom6 = array('TOTAL' => '', 'NILAI' => '');
		$tabel6[1] = array('TOTAL' => 'Nilai Total Kepaniteraan (Stase) Ilmu Bedah', 'NILAI' => ": " . "<b>$nilai_total</b>");
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
				'cols' => array('TOTAL' => array('width' => 225))
			)
		);
		$pdf->ezSetDy(-20, '');

		//Persetujuan Kordik
		$nip_kordik = mysqli_fetch_array(mysqli_query($con, "SELECT `username` FROM `admin` WHERE `stase`='K101'"));
		$data_kordik = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$nip_kordik[username]'"));
		$kordik = $data_kordik['nama'] . ", " . $data_kordik['gelar'];
		$kolom6a = array('ITEM' => '');
		$tabel6a[1] = array('ITEM' => 'Status: <b>DISETUJUI</b>');
		$tabel6a[2] = array('ITEM' => 'pada tanggal _____________________');
		$tabel6a[3] = array('ITEM' => 'Kordik Kepaniteraan (Stase) Ilmu Bedah');
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
		$pdf->addText(50, 25, 10, "$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Rekap Nilai Ilmu Bedah                                 <i>[hal 1]</i>");
		$pdf->ezStream();
	} else
		echo "
		<script>
			window.location.href=\"../accessdenied.php\";
		</script>
		";
}
