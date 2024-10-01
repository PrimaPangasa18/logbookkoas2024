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
		if ($_COOKIE['level'] == '5') $nim_mhsw_thtkl = $_COOKIE['user'];
		if ($_COOKIE['level'] == '1' or $_COOKIE['level'] == '2' or $_COOKIE['level'] == '3') $nim_mhsw_thtkl = $_GET['nim'];

		$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw_thtkl'"));
		$pdf = new Cezpdf('A4');
		$pdf->ezSetMargins(30, 40, 50, 50);
		$pdf->selectFont('../fonts/Helvetica.afm');

		$daftar_osce = mysqli_query($con, "SELECT * FROM `thtkl_nilai_osce` WHERE `nim`='$nim_mhsw_thtkl' AND `status_approval`='1' ORDER BY `tgl_approval` ASC");
		$jumlah_halaman = mysqli_num_rows($daftar_osce);
		$halaman = 1;
		while ($data_osce = mysqli_fetch_array($daftar_osce)) {
			//Judul
			$kolom1 = array('item' => "");
			$tabel1[1] = array('item' => "NILAI UJIAN OSCE KASUS KEPANITERAAN (STASE) ILMU KESEHATAN THT-KL");
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
			$tabel2[3] = array('item' => "Kepaniteraan (Stase)", 'isi' => ": " . "Ilmu Kesehatan THT-KL");
			$tabel2[4] = array('item' => "", 'isi' => "");
			$tabel2[5] = array('item' => "Jenis Ujian OSCE", 'isi' => ": " . "$data_osce[jenis_ujian]");
			$tabel2[6] = array('item' => "Nilai Ujian", 'isi' => ": " . "<b>$data_osce[nilai]</b>");
			$tabel2[7] = array('item' => "Catatan Penguji", 'isi' => ": " . "<i>$data_osce[catatan]</i>");
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

			//Persetujuan
			$tanggal_approval = tanggal_indo($data_osce['tgl_approval']);
			$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_osce[dosen]'"));
			$dosen = $data_dosen['nama'] . ", " . $data_dosen['gelar'];
			$kolom6 = array('ITEM' => '');
			$tabel6[1] = array('ITEM' => 'Status: <b>DISETUJUI</b>');
			$tabel6[2] = array('ITEM' => 'pada tanggal ' . $tanggal_approval);
			$tabel6[3] = array('ITEM' => 'Dosen Penguji');
			$tabel6[4] = array('ITEM' => '');
			$tabel6[5] = array('ITEM' => $dosen);
			$tabel6[6] = array('ITEM' => 'NIP: ' . $data_dosen['nip']);
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
					'rowGap' => 1,
					'showBgCol' => 0,
					'cols' => array('ITEM' => array('justification' => 'right'))
				)
			);
			$pdf->ezSetDy(-10, '');
			$pdf->addText(50, 25, 10, "$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Nilai Ujian OSCE                         <i>[hal $halaman dari $jumlah_halaman hal]</i>");
			if ($halaman < $jumlah_halaman) $pdf->ezNewPage();

			$halaman++;
		}

		$pdf->ezStream();
	} else
		echo "
		<script>
			window.location.href=\"../accessdenied.php\";
		</script>
		";
}
