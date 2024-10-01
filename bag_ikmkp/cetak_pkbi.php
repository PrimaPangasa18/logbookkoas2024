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
		if ($_COOKIE['level'] == '5') $nim_mhsw_ikmkp = $_COOKIE['user'];
		if ($_COOKIE['level'] == '1' or $_COOKIE['level'] == '2' or $_COOKIE['level'] == '3') $nim_mhsw_ikmkp = $_GET['nim'];

		$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw_ikmkp'"));
		$pdf = new Cezpdf('A4');
		$pdf->ezSetMargins(30, 40, 50, 50);
		$pdf->selectFont('../fonts/Helvetica.afm');

		$daftar_pkbi = mysqli_query($con, "SELECT * FROM `ikmkp_nilai_pkbi` WHERE `nim`='$nim_mhsw_ikmkp' AND `status_approval`='1' ORDER BY `tgl_approval` ASC");
		$jumlah_halaman = mysqli_num_rows($daftar_pkbi);
		$halaman = 1;
		while ($data_pkbi = mysqli_fetch_array($daftar_pkbi)) {
			//Judul
			$kolom1 = array('item' => "");
			$tabel1[1] = array('item' => "NILAI KEGIATAN DI PKBI KEPANITERAAN (STASE) IKM-KP");
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
			$tabel2[3] = array('item' => "Kepaniteraan (Stase)", 'isi' => ": " . "IKM-KP");
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

			//Header tabel
			$kolom3 = array('NO' => '', 'ASPEK' => '', 'BOBOT' => '', 'NILAI' => '');
			$tabel3[1] = array('NO' => '<b>No</b>', 'ASPEK' => '<b>Komponen Yang Dinilai</b>', 'BOBOT' => '<b>Bobot</b>', 'NILAI' => '<b>Nilai (0-100)</b>');
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
					'cols' => array('NO' => array('width' => 30, 'justification' => 'center'), 'ASPEK' => array('justification' => 'center'), 'BOBOT' => array('width' => 100, 'justification' => 'center'), 'NILAI' => array('width' => 100, 'justification' => 'center'))
				)
			);

			//Nilai Aspek 1 - 5
			$kolom4 = array('NO' => '', 'ASPEK' => '', 'BOBOT' => '', 'NILAI' => '');
			$tabel4[1] = array('NO' => '1', 'ASPEK' => 'Kemampuan penyusunan laporan dan presentasi', 'BOBOT' => '20%', 'NILAI' => $data_pkbi['aspek_1']);
			$tabel4[2] = array('NO' => '2', 'ASPEK' => 'Kemampuan melakukan identifikasi dan analisis masalah', 'BOBOT' => '20%', 'NILAI' => $data_pkbi['aspek_2']);
			$tabel4[3] = array('NO' => '3', 'ASPEK' => 'Kemampuan melakukan intervensi masalah', 'BOBOT' => '20%', 'NILAI' => $data_pkbi['aspek_3']);
			$tabel4[4] = array('NO' => '4', 'ASPEK' => 'Kelancaran diskusi dan tanya jawab', 'BOBOT' => '20%', 'NILAI' => $data_pkbi['aspek_4']);
			$tabel4[5] = array('NO' => '5', 'ASPEK' => 'Sikap', 'BOBOT' => '20%', 'NILAI' => $data_pkbi['aspek_5']);
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

			//Nilai Total
			$kolom5 = array('TOTAL' => '', 'NILAI' => '');
			$tabel5[1] = array('TOTAL' => 'Total Nilai (Bobot x Nilai)', 'NILAI' => $data_pkbi['nilai_total']);
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

			//Umpan Balik
			$kolom5a = array('ITEM' => '');
			$tabel5a[1] = array('ITEM' => "<b>Umpan Balik:</b>\r\n\r\n<i>$data_pkbi[umpan_balik]</i>\r\n");
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
					'showLines' => 2,
					'titleFontSize' => 12,
					'xPos' => 'center',
					'xOrientation' => 'center',
					'rowGap' => 3,
					'showBgCol' => 0,
					'cols' => array('ITEM' => array('width' => 510))
				)
			);
			//Saran
			$kolom5d = array('ITEM' => '');
			$tabel5d[1] = array('ITEM' => "<b>Saran:</b>\r\n\r\n<i>$data_pkbi[saran]</i>\r\n");
			$pdf->ezTable(
				$tabel5d,
				$kolom5d,
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
					'cols' => array('ITEM' => array('width' => 510))
				)
			);
			$pdf->ezSetDy(-20, '');

			//Persetujuan
			$tanggal_approval = tanggal_indo($data_pkbi['tgl_approval']);
			$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_pkbi[dosen]'"));
			$dosen = $data_dosen['nama'] . ", " . $data_dosen['gelar'];
			$kolom6 = array('ITEM' => '');
			$tabel6[1] = array('ITEM' => 'Status: <b>DISETUJUI</b>');
			$tabel6[2] = array('ITEM' => 'pada tanggal ' . $tanggal_approval);
			$tabel6[3] = array('ITEM' => 'Dosen Pembimbing');
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
			$pdf->addText(50, 25, 10, "$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Nilai Kegiatan di PKBI               <i>[hal $halaman dari $jumlah_halaman hal]</i>");
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
