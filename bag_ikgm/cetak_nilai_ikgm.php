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
		if ($_COOKIE['level'] == '5') $nim_mhsw_ikgm = $_COOKIE['user'];
		if ($_COOKIE['level'] == '1' or $_COOKIE['level'] == '2' or $_COOKIE['level'] == '3') $nim_mhsw_ikgm = $_GET['nim'];

		$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw_ikgm'"));
		$pdf = new Cezpdf('A4');
		$pdf->ezSetMargins(30, 40, 50, 50);
		$pdf->selectFont('../fonts/Helvetica.afm');

		//-------------------------------
		//Rekap Nilai Penilaian Laporan Kasus
		//-------------------------------
		$data_nilai_kasus = mysqli_fetch_array(mysqli_query($con, "SELECT `nilai_total` FROM `ikgm_nilai_kasus` WHERE `nim`='$nim_mhsw_ikgm' AND `status_approval`='1'"));
		$nilai_kasus = $data_nilai_kasus['nilai_total'];
		$nilai_kasus = number_format($nilai_kasus, 2);

		//-------------------------------
		//Rekap Nilai Penilaian Journal Reading
		//-------------------------------
		$data_nilai_jurnal = mysqli_fetch_array(mysqli_query($con, "SELECT `nilai_total` FROM `ikgm_nilai_jurnal` WHERE `nim`='$nim_mhsw_ikgm' AND `status_approval`='1'"));
		$nilai_jurnal = $data_nilai_jurnal['nilai_total'];
		$nilai_jurnal = number_format($nilai_jurnal, 2);

		//-------------------------------
		//Rekap Nilai Penilaian Responsi Kasus Kecil
		//-------------------------------
		$data_nilai_responsi = mysqli_fetch_array(mysqli_query($con, "SELECT SUM(`nilai_rata`) FROM `ikgm_nilai_responsi` WHERE `nim`='$nim_mhsw_ikgm' AND `status_approval`='1'"));
		$jml_data_responsi = mysqli_num_rows(mysqli_query($con, "SELECT `nilai_rata` FROM `ikgm_nilai_responsi` WHERE `nim`='$nim_mhsw_ikgm' AND `status_approval`='1'"));
		if ($jml_data_responsi == 0) $nilai_responsi = 0;
		else $nilai_responsi = $data_nilai_responsi[0] / $jml_data_responsi;
		$nilai_responsi = number_format($nilai_responsi, 2);

		//-------------------------------
		//Rekap Nilai Test
		//-------------------------------
		$nilaisikap = mysqli_fetch_array(mysqli_query($con, "SELECT MAX(`nilai`) FROM `ikgm_nilai_test` WHERE `nim`='$nim_mhsw_ikgm' AND `jenis_test`='3' AND `status_approval`='1'"));
		$nilaiosca = mysqli_fetch_array(mysqli_query($con, "SELECT MAX(`nilai`) FROM `ikgm_nilai_test` WHERE `nim`='$nim_mhsw_ikgm' AND `jenis_test`='5' AND `status_approval`='1'"));
		$nilai_sikap = number_format($nilaisikap[0], 2);
		$nilai_osca = number_format($nilaiosca[0], 2);

		//Judul Rekap Total
		$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `stase_M106` WHERE `nim`='$nim_mhsw_ikgm'"));
		$tanggal_mulai = tanggal_indo($data_stase['tgl_mulai']);
		$tanggal_selesai = tanggal_indo($data_stase['tgl_selesai']);
		$periode = $tanggal_mulai . " s.d. " . $tanggal_selesai;

		$kolom1 = array('item' => "");
		$tabel1[1] = array('item' => "REKAP NILAI KEPANITERAAN (STASE) IKGM");
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
		$tabel2[3] = array('item' => "Kepaniteraan (Stase)", 'isi' => ": " . "IKGM");
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
		$tabel4[1] = array('NO' => '1', 'ITEM' => 'Nilai Laporan Kasus', 'BOBOT' => '12.5%', 'NILAI' => $nilai_kasus);
		$tabel4[2] = array('NO' => '2', 'ITEM' => 'Nilai Journal Reading', 'BOBOT' => '12.5%', 'NILAI' => $nilai_jurnal);
		$tabel4[3] = array('NO' => '3', 'ITEM' => 'Rata Nilai Responsi Kasus Kecil', 'BOBOT' => '30%', 'NILAI' => $nilai_responsi);
		$tabel4[4] = array('NO' => '4', 'ITEM' => "Nilai Ujian OSCA", 'BOBOT' => '30%', 'NILAI' => $nilai_osca);
		$tabel4[5] = array('NO' => '5', 'ITEM' => "Nilai Sikap dan Perilaku", 'BOBOT' => '15%', 'NILAI' => $nilai_sikap);
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
		$nilai_total = number_format(0.125 * $nilai_kasus + 0.125 * $nilai_jurnal + 0.3 * $nilai_responsi + 0.3 * $nilai_osca + 0.15 * $nilai_sikap, 2);
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
		if ($nilai_total <= 100 and $nilai_total >= 80) $grade = "A";
		if ($nilai_total < 80 and $nilai_total >= 70) $grade = "B";
		if ($nilai_total < 70 and $nilai_total >= 60) $grade = "C";
		if ($nilai_total < 60 and $nilai_total >= 50) $grade = "D";
		if ($nilai_total < 50) $grade = "E";
		$kolom6 = array('TOTAL' => '', 'NILAI' => '');
		$tabel6[1] = array('TOTAL' => 'Nilai Total Kepaniteraan (Stase) IKGM', 'NILAI' => ": " . "<b>$nilai_total</b>");
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
		$nip_kordik = mysqli_fetch_array(mysqli_query($con, "SELECT `username` FROM `admin` WHERE `stase`='K106'"));
		$data_kordik = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$nip_kordik[username]'"));
		$kordik = $data_kordik['nama'] . ", " . $data_kordik['gelar'];
		$kolom6a = array('ITEM' => '');
		$tabel6a[1] = array('ITEM' => 'Status: <b>DISETUJUI</b>');
		$tabel6a[2] = array('ITEM' => 'pada tanggal _____________________');
		$tabel6a[3] = array('ITEM' => 'Kordik Kepaniteraan (Stase) IKGM');
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
		$pdf->addText(50, 25, 10, "$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Rekap Nilai Kepaniteraan (Stase) IKGM       <i>[hal 1]</i>");
		$pdf->ezStream();
	} else
		echo "
		<script>
			window.location.href=\"../accessdenied.php\";
		</script>
		";
}
