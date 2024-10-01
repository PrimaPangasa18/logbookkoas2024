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

		$daftar_presentasi = mysqli_query($con, "SELECT * FROM `mata_nilai_presentasi` WHERE `nim`='$nim_mhsw_mata' AND `status_approval`='1' ORDER BY `tgl_approval` ASC");
		$jumlah_halaman = mysqli_num_rows($daftar_presentasi);
		$halaman = 1;
		while ($data_presentasi = mysqli_fetch_array($daftar_presentasi)) {
			//Judul
			$kolom1 = array('item' => "");
			$tabel1[1] = array('item' => "NILAI PRESENTASI KASUS KEPANITERAAN (STASE) ILMU KESEHATAN MATA");
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
			$tanggal_penyajian = tanggal_indo($data_presentasi['tgl_penyajian']);
			$pdf->ezSetDy(-20, '');
			$kolom2 = array('item' => "", 'isi' => "");
			$tabel2[1] = array('item' => "Nama Mahasiswa", 'isi' => ": " . "$data_mhsw[nama]");
			$tabel2[2] = array('item' => "NIM", 'isi' => ": " . "$data_mhsw[nim]");
			$tabel2[3] = array('item' => "Kepaniteraan (Stase)", 'isi' => ": " . "Ilmu Kesehatan Mata");
			$tabel2[4] = array('item' => "Tanggal Penyajian", 'isi' => ": " . "$tanggal_penyajian");
			$tabel2[5] = array('item' => "Judul Kasus", 'isi' => ": " . "<i>$data_presentasi[judul_presentasi]</i>");
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
			$kolom3 = array('NO' => '', 'ASPEK' => '', 'NMAKS' => '', 'NILAI' => '');
			$tabel3[1] = array('NO' => '<b>No</b>', 'ASPEK' => '<b>Komponen Penilaian</b>', 'NMAKS' => '<b>Bobot</b>', 'NILAI' => '<b>Nilai (0-100)</b>');
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
					'cols' => array('NO' => array('width' => 30, 'justification' => 'center'), 'ASPEK' => array('justification' => 'center'), 'NMAKS' => array('width' => 100, 'justification' => 'center'), 'NILAI' => array('width' => 100, 'justification' => 'center'))
				)
			);

			//Nilai Aspek 1 - 5
			$kolom4 = array('NO' => '', 'ASPEK' => '', 'NMAKS' => '', 'NILAI' => '');
			$tabel4[1] = array('NO' => '1', 'ASPEK' => 'Cara Penyajian:', 'NMAKS' => '', 'NILAI' => '');
			$tabel4[2] = array('NO' => '', 'ASPEK' => '1.1. Penampilan', 'NMAKS' => '10%', 'NILAI' => $data_presentasi['aspek_1']);
			$tabel4[3] = array('NO' => '', 'ASPEK' => '1.2. Penyampaian', 'NMAKS' => '20%', 'NILAI' => $data_presentasi['aspek_2']);
			$tabel4[4] = array('NO' => '', 'ASPEK' => '1.3. Makalah', 'NMAKS' => '20%', 'NILAI' => $data_presentasi['aspek_3']);
			$tabel4[5] = array('NO' => '2', 'ASPEK' => 'Penguasaan Materi', 'NMAKS' => '30%', 'NILAI' => $data_presentasi['aspek_4']);
			$tabel4[6] = array('NO' => '3', 'ASPEK' => 'Pengetahuan Teori / Penunjang', 'NMAKS' => '20%', 'NILAI' => $data_presentasi['aspek_5']);
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
					'cols' => array('NO' => array('width' => 30, 'justification' => 'center'), 'NMAKS' => array('width' => 100, 'justification' => 'center'), 'NILAI' => array('width' => 100, 'justification' => 'center'))
				)
			);

			//Nilai Total
			$kolom5 = array('TOTAL' => '', 'NILAI' => '');
			$tabel5[1] = array('TOTAL' => 'Nilai Total (Jumlah Bobot x Nilai):', 'NILAI' => $data_presentasi['nilai_total']);
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
			$kolom50 = array('ITEM' => '');
			$tabel50[1] = array('ITEM' => "<b>Umpan Balik:</b>\r\n\r\n<i>$data_presentasi[umpan_balik]</i>\r\n");
			$pdf->ezTable(
				$tabel50,
				$kolom50,
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
			$kolom51 = array('ITEM' => '');
			$tabel51[1] = array('ITEM' => "<b>Saran:</b>\r\n\r\n<i>$data_presentasi[saran]</i>\r\n");
			$pdf->ezTable(
				$tabel51,
				$kolom51,
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

			//Penyanggah
			$kolom5a = array('ITEM' => '');
			$tabel5a[1] = array('ITEM' => '<i>Penyanggah:</i>');
			//Penyanggah 1-5
			$i = 1;
			while ($i < 6) {
				$penyanggah_i = "penyanggah_" . "$i";
				$nilai_penyanggah_i = "nilai_penyanggah_" . "$i";
				$row_i = $i + 1;
				if ($data_presentasi[$penyanggah_i] != "-") {
					$mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT `nim`,`nama` FROM `biodata_mhsw` WHERE `nim`='$data_presentasi[$penyanggah_i]'"));
					$tabel5a[$row_i] = array('ITEM' => "<i>$i. $mhsw[nama] (NIM: $mhsw[nim]) [Nilai: $data_presentasi[$nilai_penyanggah_i]]</i>");
				} else $tabel5a[$row_i] = array('ITEM' => "<i>$i. -</i>");
				$i++;
			}
			$pdf->ezTable(
				$tabel5a,
				$kolom5a,
				"",
				array(
					'maxWidth' => 530,
					'width' => 510,
					'fontSize' => 8,
					'showHeadings' => 0,
					'shaded' => 0,
					'showLines' => 0,
					'titleFontSize' => 10,
					'xPos' => 'center',
					'xOrientation' => 'center',
					'rowGap' => 2,
					'showBgCol' => 0,
					'cols' => array('ITEM' => array('justification' => 'left'))
				)
			);
			$pdf->ezSetDy(-20, '');

			//Persetujuan
			$tanggal_approval = tanggal_indo($data_presentasi['tgl_approval']);
			$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_presentasi[dosen]'"));
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
			$pdf->addText(50, 25, 10, "$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Nilai Presentasi Kasus Besar             <i>[hal $halaman dari $jumlah_halaman hal]</i>");
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
