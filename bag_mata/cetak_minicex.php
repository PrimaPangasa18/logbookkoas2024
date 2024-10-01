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

		$daftar_minicex = mysqli_query($con, "SELECT * FROM `mata_nilai_minicex` WHERE `nim`='$nim_mhsw_mata' AND `status_approval`='1' ORDER BY `tgl_approval` ASC");
		$jumlah_halaman = mysqli_num_rows($daftar_minicex);
		$halaman = 1;
		while ($data_minicex = mysqli_fetch_array($daftar_minicex)) {
			//Judul
			$kolom1 = array('item' => "");
			$tabel1[1] = array('item' => "NILAI UJIAN MINI-CEX KEPANITERAAN (STASE) ILMU KESEHATAN MATA");
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
			$tanggal_ujian = tanggal_indo($data_minicex['tgl_ujian']);
			$pdf->ezSetDy(-20, '');
			$kolom2 = array('item' => "", 'isi' => "");
			$tabel2[1] = array('item' => "Nama Mahasiswa", 'isi' => ": " . "$data_mhsw[nama]");
			$tabel2[2] = array('item' => "NIM", 'isi' => ": " . "$data_mhsw[nim]");
			$tabel2[3] = array('item' => "Kepaniteraan (Stase)", 'isi' => ": " . "Ilmu Kesehatan Mata");
			$tabel2[4] = array('item' => "Tanggal Ujian", 'isi' => ": " . "$tanggal_ujian");
			$tabel2[5] = array('item' => "Waktu Observasi", 'isi' => ": " . "$data_minicex[waktu_obs] menit");
			$tabel2[6] = array('item' => "Waktu Umpan Balik", 'isi' => ": " . "$data_minicex[waktu_ub] menit");
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
			$kolom3 = array('NO' => '', 'ASPEK' => '', 'OBS' => '', 'NILAI' => '');
			$tabel3[1] = array('NO' => '<b>No</b>', 'ASPEK' => '<b>Komponen Penilaian Ketrampilan</b>', 'OBS' => '<b>Observasi</b>', 'NILAI' => '<b>Nilai (0-100)</b>');
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
					'rowGap' => 3,
					'showBgCol' => 0,
					'cols' => array('NO' => array('width' => 30, 'justification' => 'center'), 'ASPEK' => array('justification' => 'center'), 'OBS' => array('width' => 100, 'justification' => 'center'), 'NILAI' => array('width' => 100, 'justification' => 'center'))
				)
			);

			if ($data_minicex['observasi_11'] == "1") $obs11 = "Ya";
			else $obs11 = "Tidak";
			if ($data_minicex['observasi_12'] == "1") $obs12 = "Ya";
			else $obs12 = "Tidak";
			if ($data_minicex['observasi_13'] == "1") $obs13 = "Ya";
			else $obs13 = "Tidak";
			if ($data_minicex['observasi_14'] == "1") $obs14 = "Ya";
			else $obs14 = "Tidak";
			if ($data_minicex['observasi_15'] == "1") $obs15 = "Ya";
			else $obs15 = "Tidak";
			if ($data_minicex['observasi_16'] == "1") $obs16 = "Ya";
			else $obs16 = "Tidak";
			if ($data_minicex['observasi_17'] == "1") $obs17 = "Ya";
			else $obs17 = "Tidak";

			if ($data_minicex['observasi_21'] == "1") $obs21 = "Ya";
			else $obs21 = "Tidak";
			if ($data_minicex['observasi_22'] == "1") $obs22 = "Ya";
			else $obs22 = "Tidak";
			if ($data_minicex['observasi_23'] == "1") $obs23 = "Ya";
			else $obs23 = "Tidak";
			if ($data_minicex['observasi_24'] == "1") $obs24 = "Ya";
			else $obs24 = "Tidak";

			if ($data_minicex['observasi_31'] == "1") $obs31 = "Ya";
			else $obs31 = "Tidak";
			if ($data_minicex['observasi_32'] == "1") $obs32 = "Ya";
			else $obs32 = "Tidak";
			if ($data_minicex['observasi_33'] == "1") $obs33 = "Ya";
			else $obs33 = "Tidak";
			if ($data_minicex['observasi_34'] == "1") $obs34 = "Ya";
			else $obs34 = "Tidak";
			if ($data_minicex['observasi_35'] == "1") $obs35 = "Ya";
			else $obs35 = "Tidak";
			if ($data_minicex['observasi_36'] == "1") $obs36 = "Ya";
			else $obs36 = "Tidak";

			if ($data_minicex['observasi_41'] == "1") $obs41 = "Ya";
			else $obs41 = "Tidak";
			if ($data_minicex['observasi_42'] == "1") $obs42 = "Ya";
			else $obs42 = "Tidak";
			if ($data_minicex['observasi_43'] == "1") $obs43 = "Ya";
			else $obs43 = "Tidak";
			if ($data_minicex['observasi_44'] == "1") $obs44 = "Ya";
			else $obs44 = "Tidak";

			//KOMUNIKASI
			$kolom4 = array('ITEM' => '');
			$tabel4[1] = array('ITEM' => 'KOMUNIKASI');
			$pdf->ezTable(
				$tabel4,
				$kolom4,
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
					'rowGap' => 3,
					'showBgCol' => 0,
					'cols' => array('ITEM' => array('width' => 510))
				)
			);

			//Nilai KOMUNIKASI
			$kolom4a = array('NO' => '', 'ASPEK' => '', 'OBS' => '', 'NILAI' => '');
			$tabel4a[1] = array('NO' => '1', 'ASPEK' => 'Memperkenalkan diri dan menjelaskan perannya kepada pasien', 'OBS' => $obs11, 'NILAI' => $data_minicex['aspek_11']);
			$tabel4a[2] = array('NO' => '2', 'ASPEK' => 'Memperlihatkan kontak mata yang baik', 'OBS' => $obs12, 'NILAI' => $data_minicex['aspek_12']);
			$tabel4a[3] = array('NO' => '3', 'ASPEK' => 'Mendengarkan pasien tanpa menginterupsi', 'OBS' => $obs13, 'NILAI' => $data_minicex['aspek_13']);
			$tabel4a[4] = array('NO' => '4', 'ASPEK' => 'Mengekspresikan perhatiannya kepada pasien', 'OBS' => $obs14, 'NILAI' => $data_minicex['aspek_14']);
			$tabel4a[5] = array('NO' => '5', 'ASPEK' => 'Bertanya dengan pertanyaan terbuka', 'OBS' => $obs15, 'NILAI' => $data_minicex['aspek_15']);
			$tabel4a[6] = array('NO' => '6', 'ASPEK' => 'Memberi kesempatan kepada pasien untuk bertanya', 'OBS' => $obs16, 'NILAI' => $data_minicex['aspek_16']);
			$tabel4a[7] = array('NO' => '7', 'ASPEK' => 'Menjelaskan perencanaan selanjutnya dengan baik', 'OBS' => $obs17, 'NILAI' => $data_minicex['aspek_17']);
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
					'rowGap' => 3,
					'showBgCol' => 0,
					'cols' => array('NO' => array('width' => 30, 'justification' => 'center'), 'OBS' => array('width' => 100, 'justification' => 'center'), 'NILAI' => array('width' => 100, 'justification' => 'center'))
				)
			);

			//PROFESIONALISME
			$kolom4b = array('ITEM' => '');
			$tabel4b[1] = array('ITEM' => 'PROFESIONALISME');
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
					'rowGap' => 3,
					'showBgCol' => 0,
					'cols' => array('ITEM' => array('width' => 510))
				)
			);

			//Nilai PROFESIONALISME
			$kolom4c = array('NO' => '', 'ASPEK' => '', 'OBS' => '', 'NILAI' => '');
			$tabel4c[1] = array('NO' => '1', 'ASPEK' => 'Mengenakan pakaian yang pantas', 'OBS' => $obs21, 'NILAI' => $data_minicex['aspek_21']);
			$tabel4c[2] = array('NO' => '2', 'ASPEK' => 'Sopan / hormat pada pasien', 'OBS' => $obs22, 'NILAI' => $data_minicex['aspek_22']);
			$tabel4c[3] = array('NO' => '3', 'ASPEK' => 'Memperlihatkan sikap profesional (memanggil nama pasien, memperlihatkan keseriusan dan kompeten)', 'OBS' => $obs23, 'NILAI' => $data_minicex['aspek_23']);
			$tabel4c[4] = array('NO' => '4', 'ASPEK' => 'Menghargai kebebasan / kerahasiaan pribadi', 'OBS' => $obs24, 'NILAI' => $data_minicex['aspek_24']);
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
					'rowGap' => 3,
					'showBgCol' => 0,
					'cols' => array('NO' => array('width' => 30, 'justification' => 'center'), 'OBS' => array('width' => 100, 'justification' => 'center'), 'NILAI' => array('width' => 100, 'justification' => 'center'))
				)
			);

			//KETRAMPILAN KLINIK
			$kolom4d = array('ITEM' => '');
			$tabel4d[1] = array('ITEM' => 'KETRAMPILAN KLINIK');
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
					'rowGap' => 3,
					'showBgCol' => 0,
					'cols' => array('ITEM' => array('width' => 510))
				)
			);

			//Nilai KETRAMPILAN KLINIK
			$kolom4e = array('NO' => '', 'ASPEK' => '', 'OBS' => '', 'NILAI' => '');
			$tabel4e[1] = array('NO' => '1', 'ASPEK' => 'Mencuci tangan sebelum dan sesudah  memeriksa pasien', 'OBS' => $obs31, 'NILAI' => $data_minicex['aspek_31']);
			$tabel4e[2] = array('NO' => '2', 'ASPEK' => 'Pemeriksaan visus', 'OBS' => $obs32, 'NILAI' => $data_minicex['aspek_32']);
			$tabel4e[3] = array('NO' => '3', 'ASPEK' => 'Pemeriksaan segmen anterior', 'OBS' => $obs33, 'NILAI' => $data_minicex['aspek_33']);
			$tabel4e[4] = array('NO' => '4', 'ASPEK' => 'Pemeriksaan funduskopi', 'OBS' => $obs34, 'NILAI' => $data_minicex['aspek_34']);
			$tabel4e[5] = array('NO' => '5', 'ASPEK' => 'Pemeriksaan tonometri', 'OBS' => $obs35, 'NILAI' => $data_minicex['aspek_35']);
			$tabel4e[6] = array('NO' => '6', 'ASPEK' => 'Pemeriksaan lapang pandang', 'OBS' => $obs36, 'NILAI' => $data_minicex['aspek_36']);
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
					'rowGap' => 3,
					'showBgCol' => 0,
					'cols' => array('NO' => array('width' => 30, 'justification' => 'center'), 'OBS' => array('width' => 100, 'justification' => 'center'), 'NILAI' => array('width' => 100, 'justification' => 'center'))
				)
			);

			//CLINICAL REASONING
			$kolom4f = array('ITEM' => '');
			$tabel4f[1] = array('ITEM' => 'CLINICAL REASONING');
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
					'rowGap' => 3,
					'showBgCol' => 0,
					'cols' => array('ITEM' => array('width' => 510))
				)
			);

			//Nilai CLINICAL REASONING
			$kolom4g = array('NO' => '', 'ASPEK' => '', 'OBS' => '', 'NILAI' => '');
			$tabel4g[1] = array('NO' => '1', 'ASPEK' => 'Diagnosis', 'OBS' => $obs41, 'NILAI' => $data_minicex['aspek_41']);
			$tabel4g[2] = array('NO' => '2', 'ASPEK' => 'Tatalaksana', 'OBS' => $obs42, 'NILAI' => $data_minicex['aspek_42']);
			$tabel4g[3] = array('NO' => '3', 'ASPEK' => 'Edukasi', 'OBS' => $obs43, 'NILAI' => $data_minicex['aspek_43']);
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
					'rowGap' => 3,
					'showBgCol' => 0,
					'cols' => array('NO' => array('width' => 30, 'justification' => 'center'), 'OBS' => array('width' => 100, 'justification' => 'center'), 'NILAI' => array('width' => 100, 'justification' => 'center'))
				)
			);

			//Nilai Total
			$kolom5 = array('TOTAL' => '', 'NILAI' => '');
			$tabel5[1] = array('TOTAL' => 'Nilai Rata-Rata (Jumlah Nilai / Jumlah Observasi):', 'NILAI' => "<b>$data_minicex[nilai_rata]</b>");
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
					'rowGap' => 3,
					'showBgCol' => 0,
					'cols' => array('TOTAL' => array('justification' => 'right'), 'NILAI' => array('width' => 100, 'justification' => 'center'))
				)
			);

			//Komentar / Saran
			$kolom5a = array('ITEM' => '');
			$tabel5a[1] = array('ITEM' => "Komentar / Saran:\r\n\r\n<i>$data_minicex[saran]</i>");
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
					'showLines' => 2,
					'titleFontSize' => 10,
					'xPos' => 'center',
					'xOrientation' => 'center',
					'rowGap' => 3,
					'showBgCol' => 0,
					'cols' => array('NO' => array('width' => 30, 'justification' => 'center'), 'OBS' => array('width' => 100, 'justification' => 'center'), 'NILAI' => array('width' => 100, 'justification' => 'center'))
				)
			);

			$pdf->ezSetDy(-20, '');

			//Persetujuan
			$tanggal_approval = tanggal_indo($data_minicex['tgl_approval']);
			$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_minicex[dosen]'"));
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
			$pdf->addText(50, 25, 10, "$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Nilai Ujian Mini-Cex                            <i>[hal $halaman dari $jumlah_halaman hal]</i>");
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
