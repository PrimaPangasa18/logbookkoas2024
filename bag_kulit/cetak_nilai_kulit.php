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
		if ($_COOKIE['level'] == '5') $nim_mhsw_kulit = $_COOKIE['user'];
		if ($_COOKIE['level'] == '1' or $_COOKIE['level'] == '2' or $_COOKIE['level'] == '3') $nim_mhsw_kulit = $_GET['nim'];

		$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw_kulit'"));
		$pdf = new Cezpdf('A4');
		$pdf->ezSetMargins(30, 40, 50, 50);
		$pdf->selectFont('../fonts/Helvetica.afm');

		//---------------------
		//Rekap Nilai CBD
		//---------------------
		//Nilai Rata CBD
		$daftar_cbd = mysqli_query($con, "SELECT * FROM `kulit_nilai_cbd` WHERE `nim`='$nim_mhsw_kulit' AND `status_approval`='1'");
		$jumlah_cbd = mysqli_num_rows($daftar_cbd);
		$jum_nilai = mysqli_fetch_array(mysqli_query($con, "SELECT SUM(`aspek_1`), SUM(`aspek_2`), SUM(`aspek_3`), SUM(`aspek_4`), SUM(`nilai_rata`) FROM `kulit_nilai_cbd` WHERE `nim`='$nim_mhsw_kulit' AND `status_approval`='1'"));
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

		//-------------------------------
		//Rekap Nilai Test
		//-------------------------------
		$sikap = mysqli_fetch_array(mysqli_query($con, "SELECT MAX(`nilai`) FROM `kulit_nilai_test` WHERE `nim`='$nim_mhsw_kulit' AND `jenis_test`='3' AND `status_approval`='1'"));
		$osce = mysqli_fetch_array(mysqli_query($con, "SELECT MAX(`nilai`) FROM `kulit_nilai_test` WHERE `nim`='$nim_mhsw_kulit' AND `jenis_test`='5' AND `status_approval`='1'"));
		$ujian = mysqli_fetch_array(mysqli_query($con, "SELECT MAX(`nilai`) FROM `kulit_nilai_test` WHERE `nim`='$nim_mhsw_kulit' AND `jenis_test`='12' AND `status_approval`='1'"));
		$nilai_sikap = number_format($sikap[0], 2);
		$nilai_osce = number_format($osce[0], 2);
		$nilai_ujian = number_format($ujian[0], 2);

		//Judul Rekap Total
		$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `stase_M114` WHERE `nim`='$nim_mhsw_kulit'"));
		$tanggal_mulai = tanggal_indo($data_stase['tgl_mulai']);
		$tanggal_selesai = tanggal_indo($data_stase['tgl_selesai']);
		$periode = $tanggal_mulai . " s.d. " . $tanggal_selesai;

		$kolom1 = array('item' => "");
		$tabel1[1] = array('item' => "REKAP NILAI KEPANITERAAN (STASE) ILMU KESEHATAN KULIT DAN KELAMIN");
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
		$tabel2[3] = array('item' => "Kepaniteraan (Stase)", 'isi' => ": " . "Ilmu Kesehatan Kulit dan Kelamin");
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
		//---------------------------------
		//Isi Tabel Rekap
		$kolom4 = array('NO' => '', 'ITEM' => '', 'BOBOT' => '', 'NILAI' => '');
		$tabel4[1] = array('NO' => '1', 'ITEM' => "Nilai Ujian Kasus", 'BOBOT' => '33.33%', 'NILAI' => $total_cbd);
		$tabel4[2] = array('NO' => '2', 'ITEM' => "Nilai Ujian OSCE", 'BOBOT' => '33.33%', 'NILAI' => $nilai_osce);
		$tabel4[3] = array('NO' => '3', 'ITEM' => "Nilai Ujian Teori", 'BOBOT' => '33.33%', 'NILAI' => $nilai_ujian);
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
		$nilai_rata = number_format(($total_cbd + $nilai_osce + $nilai_ujian) / 3, 2);
		$kolom5 = array('TOTAL' => "", 'NILAI' => "");
		$tabel5[1] = array('TOTAL' => "Nilai Rata:", 'NILAI' => "$nilai_rata");
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

		//Pengali nilai sikap dan perilaku
		if ($nilai_sikap >= 70) $nilai_total = $nilai_rata;
		if ($nilai_sikap < 70 and $nilai_sikap >= 60) $nilai_total = 0.8 * $nilai_rata;
		if ($nilai_sikap < 60) $nilai_total = 0.5 * $nilai_rata;
		$kolom5a = array('TOTAL' => '', 'NILAI' => '');
		$tabel5a[1] = array('TOTAL' => 'Nilai Sikap dan Perilaku', 'NILAI' => ": " . "$nilai_sikap");
		$tabel5a[2] = array('TOTAL' => 'Nilai Total', 'NILAI' => ": " . "$nilai_total");
		$pdf->ezTable(
			$tabel5a,
			$kolom5a,
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
				'cols' => array('TOTAL' => array('width' => 150))
			)
		);

		$kolom5b = array('ITEM' => '');
		$tabel5b[1] = array('ITEM' => "<i>Catatan:\r\nJika Nilai Sikap >= 70, Nilai Total = 100% x Nilai Rata\r\nJika 60 <= Nilai Sikap < 70, Nilai Total = 80% x Nilai Rata\r\nJika Nilai Sikap < 60, Nilai Total = 50% x Nilai Rata</i>");
		$pdf->ezTable(
			$tabel5b,
			$kolom5b,
			"",
			array(
				'maxWidth' => 530,
				'width' => 510,
				'fontSize' => 8,
				'showHeadings' => 0,
				'shaded' => 0,
				'showLines' => 0,
				'titleFontSize' => 8,
				'xPos' => 'center',
				'xOrientation' => 'center',
				'rowGap' => 3,
				'showBgCol' => 0,
				'cols' => array('ITEM' => array('width' => 510))
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
		$tabel6[1] = array('TOTAL' => 'Nilai Total Kepaniteraan (Stase) Ilmu Kesehatan Kulit dan Kelamin', 'NILAI' => ": " . "<b>$nilai_total</b>");
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
				'cols' => array('TOTAL' => array('width' => 325))
			)
		);
		$pdf->ezSetDy(-20, '');

		//Persetujuan Kordik
		$nip_kordik = mysqli_fetch_array(mysqli_query($con, "SELECT `username` FROM `admin` WHERE `stase`='K114'"));
		$data_kordik = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$nip_kordik[username]'"));
		$kordik = $data_kordik['nama'] . ", " . $data_kordik['gelar'];
		$kolom6a = array('ITEM' => '');
		$tabel6a[1] = array('ITEM' => 'Status: <b>DISETUJUI</b>');
		$tabel6a[2] = array('ITEM' => 'pada tanggal _____________________');
		$tabel6a[3] = array('ITEM' => 'Kordik Kepaniteraan (Stase)');
		$tabel6a[4] = array('ITEM' => 'Ilmu Kesehatan Kulit dan Kelamin');
		$tabel6a[5] = array('ITEM' => '');
		$tabel6a[6] = array('ITEM' => '');
		$tabel6a[7] = array('ITEM' => $kordik);
		$tabel6a[8] = array('ITEM' => 'NIP: ' . $data_kordik['nip']);
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
		$pdf->addText(50, 25, 10, "$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Rekap Nilai Ilmu Kesehatan Kulit dan Kelamin   <i>[hal 1]</i>");
		$pdf->ezStream();
	} else
		echo "
		<script>
			window.location.href=\"../accessdenied.php\";
		</script>
		";
}
