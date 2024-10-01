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
		if ($_COOKIE['level'] == '5') $nim_mhsw_ika = $_COOKIE['user'];
		if ($_COOKIE['level'] == '1' or $_COOKIE['level'] == '2' or $_COOKIE['level'] == '3') $nim_mhsw_ika = $_GET['nim'];

		$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw_ika'"));
		$pdf = new Cezpdf('A4');
		$pdf->ezSetMargins(30, 40, 50, 50);
		$pdf->selectFont('../fonts/Helvetica.afm');

		$daftar_ujian = mysqli_query($con, "SELECT * FROM `ika_nilai_ujian` WHERE `nim`='$nim_mhsw_ika' AND `status_approval`='1' ORDER BY `tgl_approval` ASC");
		$jumlah_halaman = mysqli_num_rows($daftar_ujian);
		$halaman = 1;
		while ($data_ujian = mysqli_fetch_array($daftar_ujian)) {
			//Judul
			$kolom1 = array('item' => "");
			$tabel1[1] = array('item' => "NILAI UJIAN AKHIR KEPANITERAAN");
			$tabel1[2] = array('item' => "KEPANITERAAN (STASE) ILMU KESEHATAN ANAK");
			$tabel1[3] = array('item' => "LOGBOOK KOAS PENDIDIKAN PROFESI DOKTER");
			$tabel1[4] = array('item' => "FAKULTAS KEDOKTERAN - UNIVERSITAS DIPONEGORO");
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
			$tanggal_ujian = tanggal_indo($data_ujian['tgl_ujian']);

			$kolom2 = array('item' => "", 'isi' => "");
			$tabel2[1] = array('item' => "Nama Mahasiswa", 'isi' => ": " . "$data_mhsw[nama]");
			$tabel2[2] = array('item' => "NIM", 'isi' => ": " . "$data_mhsw[nim]");
			$tabel2[3] = array('item' => "Kepaniteraan (Stase)", 'isi' => ": " . "Ilmu Kesehatan Anak");
			$tabel2[4] = array('item' => "Ujian ke-", 'isi' => ": " . "$data_ujian[ujian_ke]");
			$tabel2[5] = array('item' => "Kasus", 'isi' => ": " . "$data_ujian[kasus]");
			$tabel2[6] = array('item' => "Tanggal Ujian", 'isi' => ": " . "$tanggal_ujian");
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
			$kolom3 = array('NO' => '', 'ASPEK' => '', 'NILAI' => '');
			$tabel3[1] = array('NO' => '<b>No</b>', 'ASPEK' => '<b>Komponen Penilaian</b>', 'NILAI' => '<b>Nilai (0-100)</b>');
			$pdf->ezTable(
				$tabel3,
				$kolom3,
				"",
				array(
					'maxWidth' => 530,
					'width' => 510,
					'fontSize' => 8,
					'showHeadings' => 0,
					'shaded' => 0,
					'showLines' => 2,
					'titleFontSize' => 10,
					'xPos' => 'center',
					'xOrientation' => 'center',
					'rowGap' => 2,
					'showBgCol' => 0,
					'cols' => array('NO' => array('width' => 30, 'justification' => 'center'), 'ASPEK' => array('justification' => 'center'), 'NILAI' => array('width' => 100, 'justification' => 'center'))
				)
			);

			//Penilaian Ketrampilan
			$kolom4a = array('ASPEK' => '');
			$tabel4a[1] = array('ASPEK' => "<b>Penilaian Ketrampilan:</b>");
			$pdf->ezTable(
				$tabel4a,
				$kolom4a,
				"",
				array(
					'maxWidth' => 530,
					'width' => 510,
					'fontSize' => 8,
					'showHeadings' => 0,
					'shaded' => 0,
					'showLines' => 2,
					'titleFontSize' => 10,
					'xPos' => 'center',
					'xOrientation' => 'center',
					'rowGap' => 2,
					'showBgCol' => 0,
					'cols' => array('ASPEK' => array('width' => 510))
				)
			);
			$kolom4b = array('NO' => '', 'ASPEK' => '', 'NILAI' => '');
			$tabel4b[1] = array('NO' => '1', 'ASPEK' => "Anamnesis	(<i>sacred seven</i>, <i>fundamental four</i>, tumbuh kembang, nutrisi)", 'NILAI' => $data_ujian['aspek_1']);
			$tabel4b[2] = array('NO' => '2', 'ASPEK' => "Pemeriksaan fisik (status lokalis, status generalis)", 'NILAI' => $data_ujian['aspek_2']);
			$tabel4b[3] = array('NO' => '3', 'ASPEK' => "Pemeriksaan laboratorium (usulan pemeriksaan, interpretasi)", 'NILAI' => $data_ujian['aspek_3']);
			$tabel4b[4] = array('NO' => '4', 'ASPEK' => "Kelengkapan pengumpulan data (sistematik, kejelasan, ketepatan waktu)", 'NILAI' => $data_ujian['aspek_4']);
			$pdf->ezTable(
				$tabel4b,
				$kolom4b,
				"",
				array(
					'maxWidth' => 530,
					'width' => 510,
					'fontSize' => 8,
					'showHeadings' => 0,
					'shaded' => 0,
					'showLines' => 2,
					'titleFontSize' => 10,
					'xPos' => 'center',
					'xOrientation' => 'center',
					'rowGap' => 2,
					'showBgCol' => 0,
					'cols' => array('NO' => array('width' => 30, 'justification' => 'center'), 'NILAI' => array('width' => 100, 'justification' => 'center'))
				)
			);
			//Nilai Rata-Rata Ketrampilan
			$kolom4c = array('TOTAL' => '', 'NILAI' => '');
			$tabel4c[1] = array('TOTAL' => 'Rata-Rata Nilai Ketrampilan', 'NILAI' => "<b>$data_ujian[nilai_rata_ketrampilan]</b>");
			$pdf->ezTable(
				$tabel4c,
				$kolom4c,
				"",
				array(
					'maxWidth' => 530,
					'width' => 510,
					'fontSize' => 8,
					'showHeadings' => 0,
					'shaded' => 0,
					'showLines' => 2,
					'titleFontSize' => 10,
					'xPos' => 'center',
					'xOrientation' => 'center',
					'rowGap' => 2,
					'showBgCol' => 0,
					'cols' => array('TOTAL' => array('justification' => 'right'), 'NILAI' => array('width' => 100, 'justification' => 'center'))
				)
			);

			//Penilaian Kemampuan Berpikir
			$kolom4d = array('ASPEK' => '');
			$tabel4d[1] = array('ASPEK' => "<b>Penilaian Kemampuan Berpikir:</b>");
			$pdf->ezTable(
				$tabel4d,
				$kolom4d,
				"",
				array(
					'maxWidth' => 530,
					'width' => 510,
					'fontSize' => 8,
					'showHeadings' => 0,
					'shaded' => 0,
					'showLines' => 2,
					'titleFontSize' => 10,
					'xPos' => 'center',
					'xOrientation' => 'center',
					'rowGap' => 2,
					'showBgCol' => 0,
					'cols' => array('ASPEK' => array('width' => 510))
				)
			);
			$kolom4e = array('NO' => '', 'ASPEK' => '', 'NILAI' => '');
			$tabel4e[1] = array('NO' => '5', 'ASPEK' => "Assesment", 'NILAI' => $data_ujian['aspek_5']);
			$tabel4e[2] = array('NO' => '6', 'ASPEK' => "<i>Initial plan</i> (diagnosis, terapi, monitoring, edukasi)", 'NILAI' => $data_ujian['aspek_6']);
			$tabel4e[3] = array('NO' => '7', 'ASPEK' => "Diskusi komplikasi dan pencegahan", 'NILAI' => $data_ujian['aspek_7']);
			$tabel4e[4] = array('NO' => '8', 'ASPEK' => "Prognosis", 'NILAI' => $data_ujian['aspek_8']);
			$pdf->ezTable(
				$tabel4e,
				$kolom4e,
				"",
				array(
					'maxWidth' => 530,
					'width' => 510,
					'fontSize' => 8,
					'showHeadings' => 0,
					'shaded' => 0,
					'showLines' => 2,
					'titleFontSize' => 10,
					'xPos' => 'center',
					'xOrientation' => 'center',
					'rowGap' => 2,
					'showBgCol' => 0,
					'cols' => array('NO' => array('width' => 30, 'justification' => 'center'), 'NILAI' => array('width' => 100, 'justification' => 'center'))
				)
			);
			//Nilai Rata-Rata Kemampuan Berpikir
			$kolom4f = array('TOTAL' => '', 'NILAI' => '');
			$tabel4f[1] = array('TOTAL' => 'Rata-Rata Nilai Kemampuan Berpikir', 'NILAI' => "<b>$data_ujian[nilai_rata_berpikir]</b>");
			$pdf->ezTable(
				$tabel4f,
				$kolom4f,
				"",
				array(
					'maxWidth' => 530,
					'width' => 510,
					'fontSize' => 8,
					'showHeadings' => 0,
					'shaded' => 0,
					'showLines' => 2,
					'titleFontSize' => 10,
					'xPos' => 'center',
					'xOrientation' => 'center',
					'rowGap' => 2,
					'showBgCol' => 0,
					'cols' => array('TOTAL' => array('justification' => 'right'), 'NILAI' => array('width' => 100, 'justification' => 'center'))
				)
			);

			//Penilaian Pengetahuan Teoritik
			$kolom4g = array('ASPEK' => '');
			$tabel4g[1] = array('ASPEK' => "<b>Penilaian Pengetahuan Teoritik:</b>");
			$pdf->ezTable(
				$tabel4g,
				$kolom4g,
				"",
				array(
					'maxWidth' => 530,
					'width' => 510,
					'fontSize' => 8,
					'showHeadings' => 0,
					'shaded' => 0,
					'showLines' => 2,
					'titleFontSize' => 10,
					'xPos' => 'center',
					'xOrientation' => 'center',
					'rowGap' => 2,
					'showBgCol' => 0,
					'cols' => array('ASPEK' => array('width' => 510))
				)
			);
			$kolom4h = array('NO' => '', 'ASPEK' => '', 'NILAI' => '');
			$tabel4h[1] = array('NO' => '9', 'ASPEK' => "Diskusi tentang patofisiologi", 'NILAI' => $data_ujian['aspek_9']);
			$tabel4h[2] = array('NO' => '10', 'ASPEK' => "Diskusi tentang tumbuh kembang (imunisasi, nutrisi, perkembangan)", 'NILAI' => $data_ujian['aspek_10']);
			$tabel4h[3] = array('NO' => '11', 'ASPEK' => "Diskusi lain-lain	(hal-hal yang tercantum dalam SKDI, minimal 3)", 'NILAI' => $data_ujian['aspek_11']);
			$pdf->ezTable(
				$tabel4h,
				$kolom4h,
				"",
				array(
					'maxWidth' => 530,
					'width' => 510,
					'fontSize' => 8,
					'showHeadings' => 0,
					'shaded' => 0,
					'showLines' => 2,
					'titleFontSize' => 10,
					'xPos' => 'center',
					'xOrientation' => 'center',
					'rowGap' => 2,
					'showBgCol' => 0,
					'cols' => array('NO' => array('width' => 30, 'justification' => 'center'), 'NILAI' => array('width' => 100, 'justification' => 'center'))
				)
			);
			//Nilai Rata-Rata Kemampuan Berpikir
			$kolom4i = array('TOTAL' => '', 'NILAI' => '');
			$tabel4i[1] = array('TOTAL' => 'Rata-Rata Nilai Pengetahuan Teoritik', 'NILAI' => "<b>$data_ujian[nilai_rata_teoritik]</b>");
			$pdf->ezTable(
				$tabel4i,
				$kolom4i,
				"",
				array(
					'maxWidth' => 530,
					'width' => 510,
					'fontSize' => 8,
					'showHeadings' => 0,
					'shaded' => 0,
					'showLines' => 2,
					'titleFontSize' => 10,
					'xPos' => 'center',
					'xOrientation' => 'center',
					'rowGap' => 2,
					'showBgCol' => 0,
					'cols' => array('TOTAL' => array('justification' => 'right'), 'NILAI' => array('width' => 100, 'justification' => 'center'))
				)
			);

			//Nilai Total
			$kolom5 = array('TOTAL' => '', 'NILAI' => '');
			$tabel5[1] = array('TOTAL' => 'Rata-Rata Nilai', 'NILAI' => "<b>$data_ujian[nilai_rata]</b>");
			$pdf->ezTable(
				$tabel5,
				$kolom5,
				"",
				array(
					'maxWidth' => 530,
					'width' => 510,
					'fontSize' => 8,
					'showHeadings' => 0,
					'shaded' => 0,
					'showLines' => 2,
					'titleFontSize' => 10,
					'xPos' => 'center',
					'xOrientation' => 'center',
					'rowGap' => 2,
					'showBgCol' => 0,
					'cols' => array('TOTAL' => array('justification' => 'right'), 'NILAI' => array('width' => 100, 'justification' => 'center'))
				)
			);
			$pdf->ezSetDy(-20, '');

			//Umpan Balik
			$kolom5a = array('ITEM' => '');
			$tabel5a[1] = array('ITEM' => "<b>Umpan Balik:</b>\r\n\r\n<i>$data_ujian[umpan_balik]</i>\r\n");
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
			$tabel5d[1] = array('ITEM' => "<b>Saran:</b>\r\n\r\n<i>$data_ujian[saran]</i>\r\n");
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
			$tanggal_approval = tanggal_indo($data_ujian['tgl_approval']);
			$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_ujian[dosen]'"));
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
			$pdf->addText(50, 25, 10, "$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Nilai Ujian Akhir Kepaniteraan        <i>[hal $halaman dari $jumlah_halaman hal]</i>");
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
