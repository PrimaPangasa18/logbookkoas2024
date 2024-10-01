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
		if ($_COOKIE['level'] == '5') $nim_mhsw_kompre = $_COOKIE['user'];
		if ($_COOKIE['level'] == '1' or $_COOKIE['level'] == '2' or $_COOKIE['level'] == '3') $nim_mhsw_kompre = $_GET['nim'];

		$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw_kompre'"));
		$pdf = new Cezpdf('A4');
		$pdf->ezSetMargins(30, 40, 50, 50);
		$pdf->selectFont('../fonts/Helvetica.afm');

		$daftar_laporan = mysqli_query($con, "SELECT * FROM `kompre_nilai_laporan` WHERE `nim`='$nim_mhsw_kompre' AND `status_approval`='1' ORDER BY `tgl_approval` ASC");
		$jumlah_halaman = mysqli_num_rows($daftar_laporan) + 1;
		$halaman = 1;
		while ($data_laporan = mysqli_fetch_array($daftar_laporan)) {
			//Judul
			$tanggal_mulai = tanggal_indo($data_laporan['tgl_mulai']);
			$tanggal_selesai = tanggal_indo($data_laporan['tgl_selesai']);
			$periode = $tanggal_mulai . " s.d. " . $tanggal_selesai;

			$kolom1 = array('item' => "");
			$tabel1[1] = array('item' => "NILAI LAPORAN KEPANITERAAN KOMPREHENSIP");
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
			$tabel2[1] = array('item' => "Nama Dokter Muda", 'isi' => ": " . "$data_mhsw[nama]");
			$tabel2[2] = array('item' => "NIM", 'isi' => ": " . "$data_mhsw[nim]");
			$tabel2[3] = array('item' => "Kepaniteraan (Stase)", 'isi' => ": " . "KEPANITERAAN KOMPREHENSIP");
			$tabel2[4] = array('item' => "Instansi", 'isi' => ": $data_laporan[instansi]");
			$tabel2[5] = array('item' => "Lokasi Kegiatan", 'isi' => ": $data_laporan[lokasi]");
			$tabel2[6] = array('item' => "Periode Kegiatan", 'isi' => ": " . "$periode");
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
			$kolom3 = array('NO' => '', 'ASPEK' => '', 'BOBOT' => '', 'IND' => '', 'KELP' => '');
			$tabel3[1] = array('NO' => '<b>No</b>', 'ASPEK' => '<b>Aspek Yang Dinilai</b>', 'BOBOT' => '<b>Bobot</b>', 'IND' => '<b>' . "Nilai Individu\r\n(0-100)" . '</b>', 'KELP' => '<b>' . "Nilai Kelompok\r\n(0-100)" . '</b>');
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
					'cols' => array(
						'NO' => array('width' => 30, 'justification' => 'center'),
						'ASPEK' => array('justification' => 'center'),
						'BOBOT' => array('width' => 75, 'justification' => 'center'),
						'IND' => array('width' => 100, 'justification' => 'center'),
						'KELP' => array('width' => 100, 'justification' => 'center')
					)
				)
			);

			//Nilai Aspek 1 - 5
			$kolom4 = array('NO' => '', 'ASPEK' => '', 'BOBOT' => '', 'IND' => '', 'KELP' => '');
			$tabel4[1] = array('NO' => '1', 'ASPEK' => 'Kelengkapan isi laporan', 'BOBOT' => '10%', 'IND' => $data_laporan['aspek1_ind'], 'KELP' => $data_laporan['aspek1_kelp']);
			$tabel4[2] = array('NO' => '2', 'ASPEK' => 'Kejelasan penulisan (sistimatika) hasil dan pembahasan', 'BOBOT' => '20%', 'IND' => $data_laporan['aspek2_ind'], 'KELP' => $data_laporan['aspek2_kelp']);
			$tabel4[3] = array('NO' => '3', 'ASPEK' => 'Penyerahan laporan tepat waktu', 'BOBOT' => '20%', 'IND' => $data_laporan['aspek3_ind'], 'KELP' => $data_laporan['aspek3_kelp']);
			$tabel4[4] = array('NO' => '4', 'ASPEK' => 'Kelancaran diskusi (menjawab dengan benar)', 'BOBOT' => '30%', 'IND' => $data_laporan['aspek4_ind'], 'KELP' => $data_laporan['aspek4_kelp']);
			$tabel4[5] = array('NO' => '5', 'ASPEK' => 'Kejelasan penyajian', 'BOBOT' => '20%', 'IND' => $data_laporan['aspek5_ind'], 'KELP' => $data_laporan['aspek5_kelp']);
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
					'cols' => array(
						'NO' => array('width' => 30, 'justification' => 'center'),
						'BOBOT' => array('width' => 75, 'justification' => 'center'),
						'IND' => array('width' => 100, 'justification' => 'center'),
						'KELP' => array('width' => 100, 'justification' => 'center')
					)
				)
			);

			//Nilai Total
			$kolom5 = array('TOTAL' => '', 'IND' => '', 'KELP' => '');
			$tabel5[1] = array('TOTAL' => 'Rata-Rata Nilai (Jumlah Bobot x Nilai):', 'IND' => $data_laporan['nilai_rata_ind'], 'KELP' => $data_laporan['nilai_rata_kelp']);
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
					'cols' => array(
						'TOTAL' => array('justification' => 'right'),
						'IND' => array('width' => 100, 'justification' => 'center'),
						'KELP' => array('width' => 100, 'justification' => 'center')
					)
				)
			);
			$pdf->ezSetDy(-20, '');

			//Umpan Balik
			$kolom5a = array('ITEM' => '');
			$tabel5a[1] = array('ITEM' => "<b>Umpan Balik:</b>\r\n\r\n<i>$data_laporan[umpan_balik]</i>\r\n");
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
			$tabel5d[1] = array('ITEM' => "<b>Saran:</b>\r\n\r\n<i>$data_laporan[saran]</i>\r\n");
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
			$tanggal_approval = tanggal_indo($data_laporan['tgl_approval']);
			$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_laporan[dosen]'"));
			$dosen = $data_dosen['nama'] . ", " . $data_dosen['gelar'];
			$kolom6 = array('ITEM' => '');
			$tabel6[1] = array('ITEM' => 'Status: <b>DISETUJUI</b>');
			$tabel6[2] = array('ITEM' => 'pada tanggal ' . $tanggal_approval);
			$tabel6[3] = array('ITEM' => 'Dosen Pembimbing Lapangan');
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
			$pdf->addText(50, 25, 10, "$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Nilai Laporan                       <i>[hal $halaman dari $jumlah_halaman hal]</i>");
			$pdf->ezNewPage();

			$halaman++;
		}

		//---------------------
		//Rekap Nilai Puskesmas
		//---------------------
		//Nilai Rata Laporan
		$laporan = mysqli_query($con, "SELECT * FROM `kompre_nilai_laporan` WHERE `nim`='$nim_mhsw_kompre' AND `instansi`='Puskesmas' AND `status_approval`='1'");
		$jumlah_laporan = mysqli_num_rows($laporan);
		$jum_nilai = mysqli_fetch_array(mysqli_query($con, "SELECT
			SUM(`aspek1_ind`), SUM(`aspek2_ind`), SUM(`aspek3_ind`),SUM(`aspek4_ind`), SUM(`aspek5_ind`),
			SUM(`aspek1_kelp`), SUM(`aspek2_kelp`), SUM(`aspek3_kelp`), SUM(`aspek4_kelp`), SUM(`aspek5_kelp`),
			SUM(`nilai_rata_ind`), SUM(`nilai_rata_kelp`)
			FROM `kompre_nilai_laporan` WHERE `nim`='$nim_mhsw_kompre' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
		if ($jumlah_laporan > 0) $rata_aspek1_ind_puskesmas =  number_format($jum_nilai[0] / $jumlah_laporan, 2);
		else $rata_aspek1_ind_puskesmas = 0.00;
		if ($jumlah_laporan > 0) $rata_aspek2_ind_puskesmas =  number_format($jum_nilai[1] / $jumlah_laporan, 2);
		else $rata_aspek2_ind_puskesmas = 0.00;
		if ($jumlah_laporan > 0) $rata_aspek3_ind_puskesmas =  number_format($jum_nilai[2] / $jumlah_laporan, 2);
		else $rata_aspek3_ind_puskesmas = 0.00;
		if ($jumlah_laporan > 0) $rata_aspek4_ind_puskesmas =  number_format($jum_nilai[3] / $jumlah_laporan, 2);
		else $rata_aspek4_ind_puskesmas = 0.00;
		if ($jumlah_laporan > 0) $rata_aspek5_ind_puskesmas =  number_format($jum_nilai[4] / $jumlah_laporan, 2);
		else $rata_aspek5_ind_puskesmas = 0.00;
		if ($jumlah_laporan > 0) $total_laporan_ind_puskesmas =  number_format($jum_nilai[10] / $jumlah_laporan, 2);
		else $total_laporan_ind_puskesmas = 0.00;

		if ($jumlah_laporan > 0) $rata_aspek1_kelp_puskesmas =  number_format($jum_nilai[5] / $jumlah_laporan, 2);
		else $rata_aspek1_kelp_puskesmas = 0.00;
		if ($jumlah_laporan > 0) $rata_aspek2_kelp_puskesmas =  number_format($jum_nilai[6] / $jumlah_laporan, 2);
		else $rata_aspek2_kelp_puskesmas = 0.00;
		if ($jumlah_laporan > 0) $rata_aspek3_kelp_puskesmas =  number_format($jum_nilai[7] / $jumlah_laporan, 2);
		else $rata_aspek3_kelp_puskesmas = 0.00;
		if ($jumlah_laporan > 0) $rata_aspek4_kelp_puskesmas =  number_format($jum_nilai[8] / $jumlah_laporan, 2);
		else $rata_aspek4_kelp_puskesmas = 0.00;
		if ($jumlah_laporan > 0) $rata_aspek5_kelp_puskesmas =  number_format($jum_nilai[9] / $jumlah_laporan, 2);
		else $rata_aspek5_kelp_puskesmas = 0.00;
		if ($jumlah_laporan > 0) $total_laporan_kelp_puskesmas =  number_format($jum_nilai[11] / $jumlah_laporan, 2);
		else $total_laporan_kelp_puskesmas = 0.00;

		//---------------------
		//Rekap Nilai Rumah Sakit
		//---------------------
		//Nilai Rata laporan
		$laporan = mysqli_query($con, "SELECT * FROM `kompre_nilai_laporan` WHERE `nim`='$nim_mhsw_kompre' AND `instansi`='Rumah Sakit' AND `status_approval`='1'");
		$jumlah_laporan = mysqli_num_rows($laporan);
		$jum_nilai = mysqli_fetch_array(mysqli_query($con, "SELECT
		  SUM(`aspek1_ind`), SUM(`aspek2_ind`), SUM(`aspek3_ind`),SUM(`aspek4_ind`), SUM(`aspek5_ind`),
		  SUM(`aspek1_kelp`), SUM(`aspek2_kelp`), SUM(`aspek3_kelp`), SUM(`aspek4_kelp`), SUM(`aspek5_kelp`),
			SUM(`nilai_rata_ind`), SUM(`nilai_rata_kelp`)
		  FROM `kompre_nilai_laporan` WHERE `nim`='$nim_mhsw_kompre' AND `instansi`='Rumah Sakit' AND `status_approval`='1'"));
		if ($jumlah_laporan > 0) $rata_aspek1_ind_rumkit =  number_format($jum_nilai[0] / $jumlah_laporan, 2);
		else $rata_aspek1_ind_rumkit = 0;
		if ($jumlah_laporan > 0) $rata_aspek2_ind_rumkit =  number_format($jum_nilai[1] / $jumlah_laporan, 2);
		else $rata_aspek2_ind_rumkit = 0;
		if ($jumlah_laporan > 0) $rata_aspek3_ind_rumkit =  number_format($jum_nilai[2] / $jumlah_laporan, 2);
		else $rata_aspek3_ind_rumkit = 0;
		if ($jumlah_laporan > 0) $rata_aspek4_ind_rumkit =  number_format($jum_nilai[3] / $jumlah_laporan, 2);
		else $rata_aspek4_ind_rumkit = 0;
		if ($jumlah_laporan > 0) $rata_aspek5_ind_rumkit =  number_format($jum_nilai[4] / $jumlah_laporan, 2);
		else $rata_aspek5_ind_rumkit = 0;
		if ($jumlah_laporan > 0) $total_laporan_ind_rumkit =  number_format($jum_nilai[10] / $jumlah_laporan, 2);
		else $total_laporan_ind_rumkit = 0;

		if ($jumlah_laporan > 0) $rata_aspek1_kelp_rumkit =  number_format($jum_nilai[5] / $jumlah_laporan, 2);
		else $rata_aspek1_kelp_rumkit = 0;
		if ($jumlah_laporan > 0) $rata_aspek2_kelp_rumkit =  number_format($jum_nilai[6] / $jumlah_laporan, 2);
		else $rata_aspek2_kelp_rumkit = 0;
		if ($jumlah_laporan > 0) $rata_aspek3_kelp_rumkit =  number_format($jum_nilai[7] / $jumlah_laporan, 2);
		else $rata_aspek3_kelp_rumkit = 0;
		if ($jumlah_laporan > 0) $rata_aspek4_kelp_rumkit =  number_format($jum_nilai[8] / $jumlah_laporan, 2);
		else $rata_aspek4_kelp_rumkit = 0;
		if ($jumlah_laporan > 0) $rata_aspek5_kelp_rumkit =  number_format($jum_nilai[9] / $jumlah_laporan, 2);
		else $rata_aspek5_kelp_rumkit = 0;
		if ($jumlah_laporan > 0) $total_laporan_kelp_rumkit =  number_format($jum_nilai[11] / $jumlah_laporan, 2);
		else $total_laporan_kelp_rumkit = 0;

		//Judul Rekap Total
		$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `stase_M121` WHERE `nim`='$nim_mhsw_kompre'"));
		$tanggal_mulai = tanggal_indo($data_stase['tgl_mulai']);
		$tanggal_selesai = tanggal_indo($data_stase['tgl_selesai']);
		$periode = $tanggal_mulai . " s.d. " . $tanggal_selesai;

		$kolom7 = array('item' => "");
		$tabel7[1] = array('item' => "RATA-RATA NILAI LAPORAN KEPANITERAAN KOMPREHENSIP");
		$tabel7[2] = array('item' => "LOGBOOK KOAS PENDIDIKAN PROFESI DOKTER");
		$tabel7[3] = array('item' => "FAKULTAS KEDOKTERAN - UNIVERSITAS DIPONEGORO");
		$pdf->ezTable(
			$tabel7,
			$kolom7,
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
		$kolom8 = array('item' => "", 'isi' => "");
		$tabel8[1] = array('item' => "Nama Dokter Muda", 'isi' => ": " . "$data_mhsw[nama]");
		$tabel8[2] = array('item' => "NIM", 'isi' => ": " . "$data_mhsw[nim]");
		$tabel8[3] = array('item' => "Kepaniteraan (Stase)", 'isi' => ": " . "KEPANITERAAN KOMPREHENSIP");
		$tabel8[4] = array('item' => "Periode", 'isi' => ": " . "$periode");
		$pdf->ezTable(
			$tabel8,
			$kolom8,
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
		$kolom8a = array('ITEM' => '');
		$tabel8a[1] = array('ITEM' => '<b>Rekap Rata-Rata Nilai Laporan di Puskesmas</b>');
		$pdf->ezTable(
			$tabel8a,
			$kolom8a,
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
				'rowGap' => 2,
				'showBgCol' => 0,
				'cols' => array('ITEM' => array('width' => 510))
			)
		);

		//Header tabel
		$kolom8b = array('NO' => '', 'ASPEK' => '', 'BOBOT' => '', 'IND' => '', 'KELP' => '');
		$tabel8b[1] = array('NO' => '<b>No</b>', 'ASPEK' => '<b>Aspek Yang Dinilai</b>', 'BOBOT' => '<b>Bobot</b>', 'IND' => '<b>' . "Nilai Individu\r\n(0-100)" . '</b>', 'KELP' => '<b>' . "Nilai Kelompok\r\n(0-100)" . '</b>');
		$pdf->ezTable(
			$tabel8b,
			$kolom8b,
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
				'cols' => array(
					'NO' => array('width' => 30, 'justification' => 'center'),
					'ASPEK' => array('justification' => 'center'),
					'BOBOT' => array('width' => 75, 'justification' => 'center'),
					'IND' => array('width' => 100, 'justification' => 'center'),
					'KELP' => array('width' => 100, 'justification' => 'center')
				)
			)
		);

		//Nilai Aspek 1 - 5
		$kolom8c = array('NO' => '', 'ASPEK' => '', 'BOBOT' => '', 'IND' => '', 'KELP' => '');
		$tabel8c[1] = array('NO' => '1', 'ASPEK' => 'Kelengkapan isi laporan', 'BOBOT' => '10%', 'IND' => $rata_aspek1_ind_puskesmas, 'KELP' => $rata_aspek1_kelp_puskesmas);
		$tabel8c[2] = array('NO' => '2', 'ASPEK' => 'Kejelasan penulisan (sistimatika) hasil dan pembahasan', 'BOBOT' => '20%', 'IND' => $rata_aspek2_ind_puskesmas, 'KELP' => $rata_aspek2_kelp_puskesmas);
		$tabel8c[3] = array('NO' => '3', 'ASPEK' => 'Penyerahan laporan tepat waktu', 'BOBOT' => '20%', 'IND' => $rata_aspek3_ind_puskesmas, 'KELP' => $rata_aspek3_kelp_puskesmas);
		$tabel8c[4] = array('NO' => '4', 'ASPEK' => 'Kelancaran diskusi (menjawab dengan benar)', 'BOBOT' => '30%', 'IND' => $rata_aspek4_ind_puskesmas, 'KELP' => $rata_aspek4_kelp_puskesmas);
		$tabel8c[5] = array('NO' => '5', 'ASPEK' => 'Kejelasan penyajian', 'BOBOT' => '20%', 'IND' => $rata_aspek5_ind_puskesmas, 'KELP' => $rata_aspek5_kelp_puskesmas);
		$pdf->ezTable(
			$tabel8c,
			$kolom8c,
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
				'cols' => array(
					'NO' => array('width' => 30, 'justification' => 'center'),
					'BOBOT' => array('width' => 75, 'justification' => 'center'),
					'IND' => array('width' => 100, 'justification' => 'center'),
					'KELP' => array('width' => 100, 'justification' => 'center')
				)
			)
		);

		//Nilai Total
		$kolom8d = array('TOTAL' => '', 'IND' => '', 'KELP' => '');
		$tabel8d[1] = array('TOTAL' => 'Rata-Rata Nilai (Jumlah Bobot x Nilai):', 'IND' => $total_laporan_ind_puskesmas, 'KELP' => $total_laporan_kelp_puskesmas);
		$pdf->ezTable(
			$tabel8d,
			$kolom8d,
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
				'cols' => array(
					'TOTAL' => array('justification' => 'right'),
					'IND' => array('width' => 100, 'justification' => 'center'),
					'KELP' => array('width' => 100, 'justification' => 'center')
				)
			)
		);
		$pdf->ezSetDy(-20, '');

		//Rekap Rumah Sakit
		$kolom9 = array('ITEM' => '');
		$tabel9[1] = array('ITEM' => '<b>Rekap Rata-Rata Nilai Laporan di Rumah Sakit</b>');
		$pdf->ezTable(
			$tabel9,
			$kolom9,
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
				'rowGap' => 2,
				'showBgCol' => 0,
				'cols' => array('ITEM' => array('width' => 510))
			)
		);

		//Header tabel
		$kolom9a = array('NO' => '', 'ASPEK' => '', 'BOBOT' => '', 'IND' => '', 'KELP' => '');
		$tabel9a[1] = array('NO' => '<b>No</b>', 'ASPEK' => '<b>Aspek Yang Dinilai</b>', 'BOBOT' => '<b>Bobot</b>', 'IND' => '<b>' . "Nilai Individu\r\n(0-100)" . '</b>', 'KELP' => '<b>' . "Nilai Kelompok\r\n(0-100)" . '</b>');
		$pdf->ezTable(
			$tabel9a,
			$kolom9a,
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
				'cols' => array(
					'NO' => array('width' => 30, 'justification' => 'center'),
					'ASPEK' => array('justification' => 'center'),
					'BOBOT' => array('width' => 75, 'justification' => 'center'),
					'IND' => array('width' => 100, 'justification' => 'center'),
					'KELP' => array('width' => 100, 'justification' => 'center')
				)
			)
		);

		//Nilai Aspek 1 - 5
		$kolom9b = array('NO' => '', 'ASPEK' => '', 'BOBOT' => '', 'IND' => '', 'KELP' => '');
		$tabel9b[1] = array('NO' => '1', 'ASPEK' => 'Kelengkapan isi laporan', 'BOBOT' => '10%', 'IND' => $rata_aspek1_ind_rumkit, 'KELP' => $rata_aspek1_kelp_rumkit);
		$tabel9b[2] = array('NO' => '2', 'ASPEK' => 'Kejelasan penulisan (sistimatika) hasil dan pembahasan', 'BOBOT' => '20%', 'IND' => $rata_aspek2_ind_rumkit, 'KELP' => $rata_aspek2_kelp_rumkit);
		$tabel9b[3] = array('NO' => '3', 'ASPEK' => 'Penyerahan laporan tepat waktu', 'BOBOT' => '20%', 'IND' => $rata_aspek3_ind_rumkit, 'KELP' => $rata_aspek3_kelp_rumkit);
		$tabel9b[4] = array('NO' => '4', 'ASPEK' => 'Kelancaran diskusi (menjawab dengan benar)', 'BOBOT' => '30%', 'IND' => $rata_aspek4_ind_rumkit, 'KELP' => $rata_aspek4_kelp_rumkit);
		$tabel9b[5] = array('NO' => '5', 'ASPEK' => 'Kejelasan penyajian', 'BOBOT' => '20%', 'IND' => $rata_aspek5_ind_rumkit, 'KELP' => $rata_aspek5_kelp_rumkit);
		$pdf->ezTable(
			$tabel9b,
			$kolom9b,
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
				'cols' => array(
					'NO' => array('width' => 30, 'justification' => 'center'),
					'BOBOT' => array('width' => 75, 'justification' => 'center'),
					'IND' => array('width' => 100, 'justification' => 'center'),
					'KELP' => array('width' => 100, 'justification' => 'center')
				)
			)
		);

		//Nilai Total
		$kolom9c = array('TOTAL' => '', 'IND' => '', 'KELP' => '');
		$tabel9c[1] = array('TOTAL' => 'Rata-Rata Nilai (Jumlah Bobot x Nilai):', 'IND' => $total_laporan_ind_rumkit, 'KELP' => $total_laporan_kelp_rumkit);
		$pdf->ezTable(
			$tabel9c,
			$kolom9c,
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
				'cols' => array(
					'TOTAL' => array('justification' => 'right'),
					'IND' => array('width' => 100, 'justification' => 'center'),
					'KELP' => array('width' => 100, 'justification' => 'center')
				)
			)
		);
		$pdf->ezSetDy(-20, '');

		//Persetujuan Kordik
		$admin_kordik = "K122";
		$nip_kordik = mysqli_fetch_array(mysqli_query($con, "SELECT `username` FROM `admin` WHERE `stase`='$admin_kordik'"));
		$data_kordik = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$nip_kordik[username]'"));
		$kordik = $data_kordik['nama'] . ", " . $data_kordik['gelar'];
		$kolom6a = array('ITEM' => '');
		$tabel6a[1] = array('ITEM' => 'Status: <b>DISETUJUI</b>');
		$tabel6a[2] = array('ITEM' => 'pada tanggal _____________________');
		$tabel6a[3] = array('ITEM' => 'Kordik Kepaniteraan Komprehensip');
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
		$pdf->addText(50, 25, 10, "$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Nilai Laporan                       <i>[hal $halaman dari $jumlah_halaman hal]</i>");
		$pdf->ezStream();
	} else
		echo "
		<script>
			window.location.href=\"../accessdenied.php\";
		</script>
		";
}
