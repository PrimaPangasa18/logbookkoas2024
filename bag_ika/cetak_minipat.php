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

		$daftar_minipat = mysqli_query($con, "SELECT * FROM `ika_nilai_minipat` WHERE `nim`='$nim_mhsw_ika' AND `status_approval`='1' ORDER BY `tgl_approval` ASC");
		$jumlah_halaman = mysqli_num_rows($daftar_minipat);
		$halaman = 1;
		while ($data_minipat = mysqli_fetch_array($daftar_minipat)) {
			//Judul
			$kolom1 = array('item' => "");
			$tabel1[1] = array('item' => "NILAI MINI PEER ASSESMENT TOOL (MINI-PAT)");
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
			$tanggal_awal = tanggal_indo($data_minipat['tgl_awal']);
			$tanggal_akhir = tanggal_indo($data_minipat['tgl_akhir']);
			$periode_stase = $tanggal_awal . " s.d. " . $tanggal_akhir;
			$tanggal_penilaian = tanggal_indo($data_minipat['tgl_penilaian']);

			$kolom2 = array('item' => "", 'isi' => "");
			$tabel2[1] = array('item' => "Nama Mahasiswa", 'isi' => ": " . "$data_mhsw[nama]");
			$tabel2[2] = array('item' => "NIM", 'isi' => ": " . "$data_mhsw[nim]");
			$tabel2[3] = array('item' => "Kepaniteraan (Stase)", 'isi' => ": " . "Ilmu Kesehatan Anak");
			$tabel2[4] = array('item' => "Periode Stase", 'isi' => ": " . "$periode_stase");
			$tabel2[5] = array('item' => "Tanggal Penilaian", 'isi' => ": " . "$tanggal_penilaian");
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

			//Nilai Penyajian: Aspek 1-5
			$kolom4a = array('ASPEK' => '');
			$tabel4a[1] = array('ASPEK' => "<b>Kemampuan Diagnosis:</b>");
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
			$tabel4b[1] = array('NO' => '1', 'ASPEK' => 'Kemampuan menegakkan diagnosis', 'NILAI' => $data_minipat['aspek_1']);
			$tabel4b[2] = array('NO' => '2', 'ASPEK' => 'Kemampuan memformulasikan rencana tatalaksana', 'NILAI' => $data_minipat['aspek_2']);
			$tabel4b[3] = array('NO' => '3', 'ASPEK' => 'Kesadaran akan keterbatasan diri sendiri', 'NILAI' => $data_minipat['aspek_3']);
			$tabel4b[4] = array('NO' => '4', 'ASPEK' => 'Kemampuan terhadap aspek psikososial dan penyakit', 'NILAI' => $data_minipat['aspek_4']);
			$tabel4b[5] = array('NO' => '5', 'ASPEK' => 'Pemilihan/penggunaan alat penunjang medik', 'NILAI' => $data_minipat['aspek_5']);
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

			//Nilai Aspek Naskah: Aspek 6-7
			$kolom4c = array('ASPEK' => '');
			$tabel4c[1] = array('ASPEK' => "<b>Menjaga Praktik Kedokteran:</b>");
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
					'cols' => array('ASPEK' => array('width' => 510))
				)
			);
			$kolom4d = array('NO' => '', 'ASPEK' => '', 'NILAI' => '');
			$tabel4d[1] = array('NO' => '6', 'ASPEK' => 'Kemampuan memanfaatkan waktu secara efektif dan prioritas', 'NILAI' => $data_minipat['aspek_6']);
			$tabel4d[2] = array('NO' => '7', 'ASPEK' => 'Kemampuan melaksanakan kewajiban dokter dan kecakapan secara teknis', 'NILAI' => $data_minipat['aspek_7']);
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
					'cols' => array('NO' => array('width' => 30, 'justification' => 'center'), 'NILAI' => array('width' => 100, 'justification' => 'center'))
				)
			);

			//Nilai Aspek Diskusi: Aspek 8
			$kolom4e = array('ASPEK' => '');
			$tabel4e[1] = array('ASPEK' => "<b>Partisipasi dalam Pendidikan:</b>");
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
					'cols' => array('ASPEK' => array('width' => 510))
				)
			);
			$kolom4f = array('NO' => '', 'ASPEK' => '', 'NILAI' => '');
			$tabel4f[1] = array('NO' => '8', 'ASPEK' => "Keinginan dan kemampuan ikut mendidik sesama peserta didik dan peserta didik profesi lain", 'NILAI' => $data_minipat['aspek_8']);
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
					'cols' => array('NO' => array('width' => 30, 'justification' => 'center'), 'NILAI' => array('width' => 100, 'justification' => 'center'))
				)
			);

			//Nilai Aspek Diskusi: Aspek 9-16
			$kolom4g = array('ASPEK' => '');
			$tabel4g[1] = array('ASPEK' => "<b>Hubungan dengan Pasien:</b>");
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
			$tabel4h[1] = array('NO' => '9', 'ASPEK' => "Komunikasi dengan pasien", 'NILAI' => $data_minipat['aspek_9']);
			$tabel4h[2] = array('NO' => '10', 'ASPEK' => "Komunikasi dengan keluarga pasien", 'NILAI' => $data_minipat['aspek_10']);
			$tabel4h[3] = array('NO' => '11', 'ASPEK' => "Menghargai hak pasien", 'NILAI' => $data_minipat['aspek_11']);
			$tabel4h[4] = array('NO' => '12', 'ASPEK' => "Komunikasi verbal dengan teman sejawat", 'NILAI' => $data_minipat['aspek_12']);
			$tabel4h[5] = array('NO' => '13', 'ASPEK' => "Komunikasi tertulis dengan teman sejawat", 'NILAI' => $data_minipat['aspek_13']);
			$tabel4h[6] = array('NO' => '14', 'ASPEK' => "Kemampuan memahami dan menilai kontribusi orang lain", 'NILAI' => $data_minipat['aspek_14']);
			$tabel4h[7] = array('NO' => '15', 'ASPEK' => "Asesibilitas dan reliabilitas", 'NILAI' => $data_minipat['aspek_15']);
			$tabel4h[8] = array('NO' => '16', 'ASPEK' => "Penilaian secara keseluruhan terhadap peserta didik", 'NILAI' => $data_minipat['aspek_16']);
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

			//Nilai Total
			$kolom5 = array('TOTAL' => '', 'NILAI' => '');
			$tabel5[1] = array('TOTAL' => 'Rata-Rata Nilai', 'NILAI' => "<b>$data_minipat[nilai_rata]</b>");
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
			$tabel5a[1] = array('ITEM' => "<b>Umpan Balik:</b>\r\n\r\n<i>$data_minipat[umpan_balik]</i>\r\n");
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
			$tabel5d[1] = array('ITEM' => "<b>Saran:</b>\r\n\r\n<i>$data_minipat[saran]</i>\r\n");
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
			$tanggal_approval = tanggal_indo($data_minipat['tgl_approval']);
			$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_minipat[dosen]'"));
			$dosen = $data_dosen['nama'] . ", " . $data_dosen['gelar'];
			$kolom6 = array('ITEM' => '');
			$tabel6[1] = array('ITEM' => 'Status: <b>DISETUJUI</b>');
			$tabel6[2] = array('ITEM' => 'pada tanggal ' . $tanggal_approval);
			$tabel6[3] = array('ITEM' => 'Dosen Penilai');
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
			$pdf->addText(50, 25, 10, "$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Nilai Mini-PAT                    <i>[hal $halaman dari $jumlah_halaman hal]</i>");
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
