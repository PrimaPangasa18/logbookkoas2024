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

		$daftar_cbd = mysqli_query($con, "SELECT * FROM `ika_nilai_cbd` WHERE `nim`='$nim_mhsw_ika' AND `status_approval`='1' ORDER BY `tgl_approval` ASC");
		$jumlah_halaman = mysqli_num_rows($daftar_cbd);
		$halaman = 1;
		while ($data_cbd = mysqli_fetch_array($daftar_cbd)) {
			//Judul
			$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_cbd[dosen]'"));

			$kolom1 = array('item' => "");
			$tabel1[1] = array('item' => "NILAI CASE-BASED DISCUSSION (CBD) - KASUS POLIKLINIK");
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

			$tanggal_ujian = tanggal_indo($data_cbd['tgl_ujian']);

			//Data Mahasiswa dan Tabel 1
			$pdf->ezSetDy(-20, '');
			$kolom2 = array('item' => "", 'isi' => "");
			$tabel2[1] = array('item' => "Nama Dosen Penilai / Mentor", 'isi' => ": " . "$data_dosen[nama]" . ", " . "$data_dosen[gelar]");
			$tabel2[2] = array('item' => "Tanggal Ujian", 'isi' => ": " . "$tanggal_ujian");
			$tabel2[3] = array('item' => "Nama Poliklinik", 'isi' => ": " . "$data_cbd[poliklinik]");
			$tabel2[4] = array('item' => "", 'isi' => "");
			$tabel2[5] = array('item' => "Nama Dokter Muda", 'isi' => ": " . "$data_mhsw[nama]");
			$tabel2[6] = array('item' => "NIM", 'isi' => ": " . "$data_mhsw[nim]");
			$tabel2[7] = array('item' => "Situasi Ruangan", 'isi' => ": " . "$data_cbd[situasi_ruangan]");
			$tabel2[8] = array('item' => "Inisial Pasien", 'isi' => ": " . "$data_cbd[inisial_pasien]");
			$tabel2[9] = array('item' => "Umur Pasien", 'isi' => ": " . "$data_cbd[umur_pasien]" . " tahun");
			$tabel2[10] = array('item' => "Jenis Kelamin Pasien", 'isi' => ": " . "$data_cbd[jk_pasien]");
			$tabel2[11] = array('item' => "Problem Pasien/Diagnosis", 'isi' => ": " . "$data_cbd[diagnosis]");
			$tabel2[12] = array('item' => "Fokus Kasus", 'isi' => ": " . "$data_cbd[fokus_kasus]");

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
					'cols' => array('item' => array('justification' => 'left', 'width' => 170))
				)
			);
			$pdf->ezSetDy(-20, '');

			//Header tabel 2
			$kolom3 = array('NO' => '', 'ASPEK' => '', 'OBS' => '', 'NILAI' => '');
			$tabel3[1] = array('NO' => '<b>No</b>', 'ASPEK' => '<b>Aspek Yang Dinilai</b>', 'OBS' => '<b>Status Observasi</b>', 'NILAI' => '<b>Nilai (0-100)</b>');
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
					'cols' => array('NO' => array('width' => 30, 'justification' => 'center'), 'ASPEK' => array('justification' => 'center'), 'OBS' => array('width' => 100, 'justification' => 'center'), 'NILAI' => array('width' => 100, 'justification' => 'center'))
				)
			);

			//Nilai Aspek 1 - 7 dan Status Observasi 1-7
			$aspek1 = "Penulisan Rekam Medik";
			$aspek2 = "Penilaian Kemampuan Klinis";
			$aspek3 = "Tatalaksana Kasus";
			$aspek4 = "Investigasi dan Rujukan";
			$aspek5 = "Pemantauan dan Rencana Tindak Lanjut";
			$aspek6 = "Profesionalisme";
			$aspek7 = "Kompetensi Klinis Keseluruhan";
			if ($data_cbd['observasi_1'] == "1") $obs1 = "Ya";
			else $obs1 = "Tidak";
			if ($data_cbd['observasi_2'] == "1") $obs2 = "Ya";
			else $obs2 = "Tidak";
			if ($data_cbd['observasi_3'] == "1") $obs3 = "Ya";
			else $obs3 = "Tidak";
			if ($data_cbd['observasi_4'] == "1") $obs4 = "Ya";
			else $obs4 = "Tidak";
			if ($data_cbd['observasi_5'] == "1") $obs5 = "Ya";
			else $obs5 = "Tidak";
			if ($data_cbd['observasi_6'] == "1") $obs6 = "Ya";
			else $obs6 = "Tidak";
			if ($data_cbd['observasi_7'] == "1") $obs7 = "Ya";
			else $obs7 = "Tidak";

			$kolom4 = array('NO' => '', 'ASPEK' => '', 'OBS' => '', 'NILAI' => '');
			$tabel4[1] = array('NO' => '1', 'ASPEK' => $aspek1, 'OBS' => $obs1, 'NILAI' => $data_cbd['aspek_1']);
			$tabel4[2] = array('NO' => '2', 'ASPEK' => $aspek2, 'OBS' => $obs2, 'NILAI' => $data_cbd['aspek_2']);
			$tabel4[3] = array('NO' => '3', 'ASPEK' => $aspek3, 'OBS' => $obs3, 'NILAI' => $data_cbd['aspek_3']);
			$tabel4[4] = array('NO' => '4', 'ASPEK' => $aspek4, 'OBS' => $obs4, 'NILAI' => $data_cbd['aspek_4']);
			$tabel4[5] = array('NO' => '5', 'ASPEK' => $aspek5, 'OBS' => $obs5, 'NILAI' => $data_cbd['aspek_5']);
			$tabel4[6] = array('NO' => '6', 'ASPEK' => $aspek6, 'OBS' => $obs6, 'NILAI' => $data_cbd['aspek_6']);
			$tabel4[7] = array('NO' => '7', 'ASPEK' => $aspek7, 'OBS' => $obs7, 'NILAI' => $data_cbd['aspek_7']);
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
					'cols' => array('NO' => array('width' => 30, 'justification' => 'center'), 'OBS' => array('width' => 100, 'justification' => 'center'), 'NILAI' => array('width' => 100, 'justification' => 'center'))
				)
			);

			//Nilai Total
			$kolom5 = array('TOTAL' => '', 'NILAI' => '');
			$tabel5[1] = array('TOTAL' => 'Rata-Rata Nilai (Jumlah Nilai / Jumlah Observasi):', 'NILAI' => "<b>$data_cbd[nilai_rata]</b>");
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

			$kolom50 = array('ITEM' => '');
			$tabel50[1] = array('ITEM' => "<i>Keterangan: Nilai Batas Lulus (NBL) = 70</i>");
			$pdf->ezTable(
				$tabel50,
				$kolom50,
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
					'cols' => array('ITEM' => array('justification' => 'left'))
				)
			);
			$pdf->ezSetDy(-20, '');

			//Umpan Balik
			$kolom5a = array('ITEM' => '');
			$tabel5a[1] = array('ITEM' => "<b>Umpan Balik:</b>\r\n\r\n<i>$data_cbd[umpan_balik]</i>");
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
			$tabel5d[1] = array('ITEM' => "<b>Rencana tindak lanjut yang disetujui bersama:</b>\r\n\r\n<i>$data_cbd[saran]</i>");
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

			//Catatan
			$kolom7a = array('ITEM' => '');
			$tabel7a[1] = array('ITEM' => '<b>Catatan:</b>');
			$pdf->ezTable(
				$tabel7a,
				$kolom7a,
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
					'rowGap' => 1,
					'showBgCol' => 0,
					'cols' => array('ITEM' => array('width' => 510))
				)
			);

			$kolom7b = array('NO' => '', 'ITEM' => '', 'NILAI' => '');
			$tabel7b[1] = array('NO' => '1', 'ITEM' => 'Waktu Penilaian CBD:', 'NILAI' => '');
			$tabel7b[2] = array('NO' => '', 'ITEM' => '  Observasi', 'NILAI' => ': ' . $data_cbd['waktu_observasi'] . ' menit');
			$tabel7b[3] = array('NO' => '', 'ITEM' => '  Memberikan umpan balik', 'NILAI' => ': ' . $data_cbd['waktu_ub'] . ' menit');
			$tabel7b[4] = array('NO' => '2', 'ITEM' => 'Kepuasan penilai terhadap pelaksanaan CBD', 'NILAI' => ': ' . $data_cbd['kepuasan_penilai']);
			$tabel7b[5] = array('NO' => '3', 'ITEM' => 'Kepuasan mahasiswa terhadap pelaksanaan CBD', 'NILAI' => ': ' . $data_cbd['kepuasan_residen']);
			$pdf->ezTable(
				$tabel7b,
				$kolom7b,
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
					'rowGap' => 0,
					'showBgCol' => 0,
					'cols' => array('NO' => array('width' => 15, 'justification' => 'center'), 'ITEM' => array('width' => 200))
				)
			);
			$pdf->ezSetDy(-20, '');

			//Persetujuan
			$tanggal_approval = tanggal_indo($data_cbd['tgl_approval']);
			$dosen = $data_dosen['nama'] . ", " . $data_dosen['gelar'];
			$kolom8 = array('ITEM' => '');
			$tabel8[1] = array('ITEM' => 'Status: <b>DISETUJUI</b>');
			$tabel8[2] = array('ITEM' => 'pada tanggal ' . $tanggal_approval);
			$tabel8[3] = array('ITEM' => 'Dosen Penilai / Mentor');
			$tabel8[4] = array('ITEM' => '');
			$tabel8[5] = array('ITEM' => $dosen);
			$tabel8[6] = array('ITEM' => 'NIP: ' . $data_dosen['nip']);
			$pdf->ezTable(
				$tabel8,
				$kolom8,
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
			$pdf->addText(50, 25, 10, "$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Nilai CBD (Kasus Poliklinik)           <i>[hal $halaman dari $jumlah_halaman hal]</i>");
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
