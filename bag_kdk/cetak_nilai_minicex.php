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
		if ($_COOKIE['level'] == '5') $nim_mhsw_kdk = $_COOKIE['user'];
		if ($_COOKIE['level'] == '1' or $_COOKIE['level'] == '2' or $_COOKIE['level'] == '3') $nim_mhsw_kdk = $_GET['nim'];

		$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw_kdk'"));
		$pdf = new Cezpdf('A4');
		$pdf->ezSetMargins(30, 40, 50, 50);
		$pdf->selectFont('../fonts/Helvetica.afm');

		$daftar_minicex = mysqli_query($con, "SELECT * FROM `kdk_nilai_minicex` WHERE `nim`='$nim_mhsw_kdk' AND `status_approval`='1' ORDER BY `no_ujian` ASC");
		$jumlah_halaman = mysqli_num_rows($daftar_minicex) + 1;
		$halaman = 1;
		while ($data_minicex = mysqli_fetch_array($daftar_minicex)) {
			//Judul
			$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_minicex[dosen]'"));

			$kolom1 = array('item' => "");
			$tabel1[1] = array('item' => "NILAI MINI-CEX KEPANITERAAN KEDOKTERAN KELUARGA");
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

			//Data Mahasiswa dan Tabel 1
			$pdf->ezSetDy(-20, '');
			$kolom2 = array('item' => "", 'isi' => "");
			$tabel2[1] = array('item' => "Instansi", 'isi' => ": " . "$data_minicex[instansi]");
			$tabel2[2] = array('item' => "Nama Puskesmas / Klinik Pratama", 'isi' => ": " . "$data_minicex[lokasi]");
			$tabel2[3] = array('item' => "Nama Penilai/DPJP", 'isi' => ": " . "$data_dosen[nama]" . ", " . "$data_dosen[gelar]");
			$tabel2[4] = array('item' => "", 'isi' => "");
			$tabel2[5] = array('item' => "Nama Dokter Muda", 'isi' => ": " . "$data_mhsw[nama]");
			$tabel2[6] = array('item' => "NIM", 'isi' => ": " . "$data_mhsw[nim]");
			$tabel2[7] = array('item' => "Tahap", 'isi' => ": " . "KEPANITERAAN KEDOKTERAN KELUARGA");
			$tabel2[8] = array('item' => "Ujian ke-", 'isi' => ": " . "$data_minicex[no_ujian]");
			$tabel2[9] = array('item' => "", 'isi' => "");
			$tabel2[10] = array('item' => "Problem Pasien/Diagnosis", 'isi' => ": " . "$data_minicex[diagnosis]");
			$tabel2[11] = array('item' => "Situasi Ruangan", 'isi' => ": " . "$data_minicex[situasi_ruangan]");
			$tabel2[12] = array('item' => "Umur Pasien", 'isi' => ": " . "$data_minicex[umur_pasien]" . " tahun");
			$tabel2[13] = array('item' => "Jenis Kelamin Pasien", 'isi' => ": " . "$data_minicex[jk_pasien]");
			$tabel2[14] = array('item' => "Status Pasien", 'isi' => ": " . "$data_minicex[status_pasien]");
			$tabel2[15] = array('item' => "Tingkat Kesulitan", 'isi' => ": " . "$data_minicex[tingkat_kesulitan]");
			$tabel2[16] = array('item' => "Fokus Kasus", 'isi' => ": " . "$data_minicex[fokus_kasus]");

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

			//Nilai Aspek 1 - 8 dan Status Observasi 1-8
			$aspek1 = "Kemampuan wawancara medis";
			$aspek2 = "Kemampuan pemeriksaan fisik";
			$aspek3 = "Kualitas humanistik/profesionalisme";
			$aspek4 = "Keputusan klinis/diagnostis";
			$aspek5 = "Kemampuan mengelola pasien";
			$aspek6 = "Kemampuan konseling";
			$aspek7 = "Organisasi/efisiensi";
			$aspek8 = "Kompetensi klinis keseluruhan";
			if ($data_minicex['observasi_1'] == "1") $obs1 = "Ya";
			else $obs1 = "Tidak";
			if ($data_minicex['observasi_2'] == "1") $obs2 = "Ya";
			else $obs2 = "Tidak";
			if ($data_minicex['observasi_3'] == "1") $obs3 = "Ya";
			else $obs3 = "Tidak";
			if ($data_minicex['observasi_4'] == "1") $obs4 = "Ya";
			else $obs4 = "Tidak";
			if ($data_minicex['observasi_5'] == "1") $obs5 = "Ya";
			else $obs5 = "Tidak";
			if ($data_minicex['observasi_6'] == "1") $obs6 = "Ya";
			else $obs6 = "Tidak";
			if ($data_minicex['observasi_7'] == "1") $obs7 = "Ya";
			else $obs7 = "Tidak";
			if ($data_minicex['observasi_8'] == "1") $obs8 = "Ya";
			else $obs8 = "Tidak";

			$kolom4 = array('NO' => '', 'ASPEK' => '', 'OBS' => '', 'NILAI' => '');
			$tabel4[1] = array('NO' => '1', 'ASPEK' => $aspek1, 'OBS' => $obs1, 'NILAI' => $data_minicex['aspek_1']);
			$tabel4[2] = array('NO' => '2', 'ASPEK' => $aspek2, 'OBS' => $obs2, 'NILAI' => $data_minicex['aspek_2']);
			$tabel4[3] = array('NO' => '3', 'ASPEK' => $aspek3, 'OBS' => $obs3, 'NILAI' => $data_minicex['aspek_3']);
			$tabel4[4] = array('NO' => '4', 'ASPEK' => $aspek4, 'OBS' => $obs4, 'NILAI' => $data_minicex['aspek_4']);
			$tabel4[5] = array('NO' => '5', 'ASPEK' => $aspek5, 'OBS' => $obs5, 'NILAI' => $data_minicex['aspek_5']);
			$tabel4[6] = array('NO' => '6', 'ASPEK' => $aspek6, 'OBS' => $obs6, 'NILAI' => $data_minicex['aspek_6']);
			$tabel4[7] = array('NO' => '7', 'ASPEK' => $aspek7, 'OBS' => $obs7, 'NILAI' => $data_minicex['aspek_7']);
			$tabel4[8] = array('NO' => '8', 'ASPEK' => $aspek8, 'OBS' => $obs8, 'NILAI' => $data_minicex['aspek_8']);
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
			$tabel5[1] = array('TOTAL' => 'Rata-Rata Nilai (Jumlah Nilai / Jumlah Observasi):', 'NILAI' => "<b>$data_minicex[nilai_rata]</b>");
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
			$kolom6a = array('ITEM' => '');
			$tabel6a[1] = array('ITEM' => '<b>Umpan Balik Kompetensi Klinis</b>');
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
					'showLines' => 2,
					'titleFontSize' => 12,
					'xPos' => 'center',
					'xOrientation' => 'center',
					'rowGap' => 3,
					'showBgCol' => 0,
					'cols' => array('ITEM' => array('width' => 510, 'justification' => 'center'))
				)
			);

			$kolom6b = array('ITEM1' => '', 'ITEM2' => '');
			$item1 = "Sudah bagus.\r\nUmpan balik: " . "<i>$data_minicex[ub_bagus]</i>";
			$item2 = "Perlu perbaikan.\r\nUmpan balik: " . "<i>$data_minicex[ub_perbaikan]</i>";
			$tabel6b[1] = array('ITEM1' => $item1, 'ITEM2' => $item2);
			$pdf->ezTable(
				$tabel6b,
				$kolom6b,
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
					'cols' => array('ITEM1' => array('width' => 255), 'ITEM2' => array('width' => 255))
				)
			);

			$kolom6c = array('ITEM' => '');
			$item1 = "Action plan yang disetujui bersama:\r\n<i>$data_minicex[action_plan]</i>";
			$tabel6c[1] = array('ITEM' => $item1);
			$pdf->ezTable(
				$tabel6c,
				$kolom6c,
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
			$tabel7b[1] = array('NO' => '1', 'ITEM' => 'Waktu Mini-CEX:', 'NILAI' => '');
			$tabel7b[2] = array('NO' => '', 'ITEM' => 'Observasi', 'NILAI' => ': ' . $data_minicex['waktu_observasi'] . ' menit');
			$tabel7b[3] = array('NO' => '', 'ITEM' => 'Memberikan Umpan Balik', 'NILAI' => ': ' . $data_minicex['waktu_ub'] . ' menit');
			$tabel7b[4] = array('NO' => '2', 'ITEM' => 'Kepuasan penilai terhadap Mini-CEX', 'NILAI' => ': ' . $data_minicex['kepuasan_penilai']);
			$tabel7b[5] = array('NO' => '3', 'ITEM' => 'Kepuasan residen terhadap Mini-CEX', 'NILAI' => ': ' . $data_minicex['kepuasan_residen']);
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
					'cols' => array('NO' => array('width' => 15, 'justification' => 'center'), 'ITEM' => array('width' => 150), 'NILAI' => array('width' => 345))
				)
			);
			$pdf->ezSetDy(-20, '');

			//Persetujuan
			$tanggal_approval = tanggal_indo($data_minicex['tgl_approval']);
			$dosen = $data_dosen['nama'] . ", " . $data_dosen['gelar'];
			$kolom8 = array('ITEM' => '');
			$tabel8[1] = array('ITEM' => 'Status: <b>DISETUJUI</b>');
			$tabel8[2] = array('ITEM' => 'pada tanggal ' . $tanggal_approval);
			$tabel8[3] = array('ITEM' => 'Penilai/DPJP');
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
			$pdf->addText(50, 25, 10, "$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Nilai Mini-CEX                             <i>[hal $halaman dari $jumlah_halaman hal]</i>");
			$pdf->ezNewPage();

			$halaman++;
		}

		//---------------------
		//Rekap Nilai Puskesmas
		//---------------------
		//Nilai Rata minicex
		$minicex_puskesmas = mysqli_query($con, "SELECT `lokasi`,`no_ujian`,`nilai_rata` FROM `kdk_nilai_minicex` WHERE `nim`='$nim_mhsw_kdk' AND `instansi`='Puskesmas' AND `status_approval`='1' ORDER BY `no_ujian`");
		$jumlah_minicex_puskesmas = mysqli_num_rows($minicex_puskesmas);
		$jum_nilai_puskesmas = mysqli_fetch_array(mysqli_query($con, "SELECT SUM(`nilai_rata`) FROM `kdk_nilai_minicex` WHERE `nim`='$nim_mhsw_kdk' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
		if ($jumlah_minicex_puskesmas > 0) $total_minicex_puskesmas =  number_format($jum_nilai_puskesmas[0] / $jumlah_minicex_puskesmas, 2);
		else $total_minicex_puskesmas = 0.00;

		//---------------------
		//Rekap Nilai Klinik
		//---------------------
		//Nilai Rata minicex
		$minicex_klinik = mysqli_query($con, "SELECT `lokasi`,`no_ujian`,`nilai_rata` FROM `kdk_nilai_minicex` WHERE `nim`='$nim_mhsw_kdk' AND `instansi`='Klinik Pratama' AND `status_approval`='1' ORDER BY `no_ujian`");
		$jumlah_minicex_klinik = mysqli_num_rows($minicex_klinik);
		$jum_nilai_klinik = mysqli_fetch_array(mysqli_query($con, "SELECT SUM(`nilai_rata`) FROM `kdk_nilai_minicex` WHERE `nim`='$nim_mhsw_kdk' AND `instansi`='Klinik Pratama' AND `status_approval`='1'"));
		if ($jumlah_minicex_klinik > 0) $total_minicex_klinik =  number_format($jum_nilai_klinik[0] / $jumlah_minicex_klinik, 2);
		else $total_minicex_klinik = 0.00;

		//Judul Rekap Total
		$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `stase_M121` WHERE `nim`='$nim_mhsw_kdk'"));
		$tanggal_mulai = tanggal_indo($data_stase['tgl_mulai']);
		$tanggal_selesai = tanggal_indo($data_stase['tgl_selesai']);
		$periode = $tanggal_mulai . " s.d. " . $tanggal_selesai;

		$kolom9 = array('item' => "");
		$tabel9[1] = array('item' => "RATA-RATA NILAI MINI-CEX KEPANITERAAN KEDOKTERAN KELUARGA");
		$tabel9[2] = array('item' => "LOGBOOK KOAS PENDIDIKAN PROFESI DOKTER");
		$tabel9[3] = array('item' => "FAKULTAS KEDOKTERAN - UNIVERSITAS DIPONEGORO");
		$pdf->ezTable(
			$tabel9,
			$kolom9,
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
		$kolom10 = array('item' => "", 'isi' => "");
		$tabel10[1] = array('item' => "Nama Dokter Muda", 'isi' => ": " . "$data_mhsw[nama]");
		$tabel10[2] = array('item' => "NIM", 'isi' => ": " . "$data_mhsw[nim]");
		$tabel10[3] = array('item' => "Kepaniteraan (Stase)", 'isi' => ": " . "KEPANITERAAN KEDOKTERAN KELUARGA");
		$tabel10[4] = array('item' => "Periode", 'isi' => ": " . "$periode");
		$pdf->ezTable(
			$tabel10,
			$kolom10,
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

		//Rekap Puskesmas
		$kolom11 = array('ITEM' => '');
		$tabel11[1] = array('ITEM' => '<b>Rekap Rata-Rata Nilai Mini-CEX di Puskesmas</b>');
		$pdf->ezTable(
			$tabel11,
			$kolom11,
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
				'cols' => array('ITEM' => array('width' => 510))
			)
		);

		//Header Tabel Rata-Rata Puskesmas
		$kolom11a = array('NO' => '', 'ITEM' => '', 'NILAI' => '');
		$tabel11a[1] = array('NO' => '<b>No</b>', 'ITEM' => '<b>Nomor dan Lokasi Ujian Mini-Cex</b>', 'NILAI' => '<b>Nilai (0-100)</b>');
		$pdf->ezTable(
			$tabel11a,
			$kolom11a,
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
				'cols' => array('NO' => array('width' => 30, 'justification' => 'center'), 'ITEM' => array('justification' => 'center'), 'NILAI' => array('width' => 100, 'justification' => 'center'))
			)
		);
		//Isi Tabel Rata-Rata Puskesmas
		$kolom11c = array('NO' => '', 'ITEM' => '', 'NILAI' => '');
		$i = 1;
		while ($data_puskesmas = mysqli_fetch_array($minicex_puskesmas)) {
			$tabel11c[$i] = array('NO' => $i, 'ITEM' => "Ujian Mini-Cex Ke-$data_puskesmas[no_ujian] di $data_puskesmas[lokasi]", 'NILAI' => $data_puskesmas['nilai_rata']);
			$i++;
		}
		$pdf->ezTable(
			$tabel11c,
			$kolom11c,
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
				'cols' => array('NO' => array('width' => 30, 'justification' => 'center'), 'ITEM' => array('justification' => 'left'), 'NILAI' => array('width' => 100, 'justification' => 'center'))
			)
		);

		//Nilai Total
		$kolom11d = array('TOTAL' => '', 'NILAI' => '');
		$tabel11d[1] = array('TOTAL' => 'Rata-Rata Nilai Total:', 'NILAI' => "<b>$total_minicex_puskesmas</b>");
		$pdf->ezTable(
			$tabel11d,
			$kolom11d,
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

		//Rekap Klinik
		$kolom12 = array('ITEM' => '');
		$tabel12[1] = array('ITEM' => '<b>Rekap Rata-Rata Nilai Mini-CEX di Klinik Pratama</b>');
		$pdf->ezTable(
			$tabel12,
			$kolom12,
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
				'cols' => array('ITEM' => array('width' => 510))
			)
		);

		//Header Tabel Rata-Rata Klinik
		$kolom12a = array('NO' => '', 'ITEM' => '', 'NILAI' => '');
		$tabel12a[1] = array('NO' => '<b>No</b>', 'ITEM' => '<b>Nomor dan Lokasi Ujian Mini-Cex</b>', 'NILAI' => '<b>Nilai (0-100)</b>');
		$pdf->ezTable(
			$tabel12a,
			$kolom12a,
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
				'cols' => array('NO' => array('width' => 30, 'justification' => 'center'), 'ITEM' => array('justification' => 'center'), 'NILAI' => array('width' => 100, 'justification' => 'center'))
			)
		);
		//Isi Tabel Rata-Rata Puskesmas
		$kolom12b = array('NO' => '', 'ITEM' => '', 'NILAI' => '');
		$i = 1;
		while ($data_klinik = mysqli_fetch_array($minicex_klinik)) {
			$tabel12b[$i] = array('NO' => $i, 'ITEM' => "Ujian Mini-Cex Ke-$data_klinik[no_ujian] di $data_klinik[lokasi]", 'NILAI' => $data_klinik['nilai_rata']);
			$i++;
		}
		$pdf->ezTable(
			$tabel12b,
			$kolom12b,
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
				'cols' => array('NO' => array('width' => 30, 'justification' => 'center'), 'ITEM' => array('justification' => 'left'), 'NILAI' => array('width' => 100, 'justification' => 'center'))
			)
		);

		//Nilai Total
		$kolom12c = array('TOTAL' => '', 'NILAI' => '');
		$tabel12c[1] = array('TOTAL' => 'Rata-Rata Nilai Total:', 'NILAI' => "<b>$total_minicex_klinik</b>");
		$pdf->ezTable(
			$tabel12c,
			$kolom12c,
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
		$rata_total_minicex = ($total_minicex_puskesmas + $total_minicex_klinik) / 2;
		$rata_total_minicex = number_format($rata_total_minicex, 2);
		$kolom14 = array('TOTAL' => '', 'NILAI' => '');
		$tabel14[1] = array('TOTAL' => 'Rata-Rata Nilai MINI-CEX', 'NILAI' => ": <b>$rata_total_minicex</b>");
		$pdf->ezTable(
			$tabel14,
			$kolom14,
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
				'cols' => array('TOTAL' => array('width' => 155))
			)
		);
		$pdf->ezSetDy(-20, '');

		//Persetujuan Kordik
		$nip_kordik = mysqli_fetch_array(mysqli_query($con, "SELECT `username` FROM `admin` WHERE `stase`='K121'"));
		$data_kordik = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$nip_kordik[username]'"));
		$kordik = $data_kordik['nama'] . ", " . $data_kordik['gelar'];
		$kolom6a = array('ITEM' => '');
		$tabel6a[1] = array('ITEM' => 'Status: <b>DISETUJUI</b>');
		$tabel6a[2] = array('ITEM' => 'pada tanggal _____________________');
		$tabel6a[3] = array('ITEM' => 'Kordik Kepaniteraan Kedokteran Keluarga');
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
		$pdf->addText(50, 25, 10, "$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Nilai Mini-CEX                             <i>[hal $halaman dari $jumlah_halaman hal]</i>");
		$pdf->ezStream();
	} else
		echo "
		<script>
			window.location.href=\"../accessdenied.php\";
		</script>
		";
}
