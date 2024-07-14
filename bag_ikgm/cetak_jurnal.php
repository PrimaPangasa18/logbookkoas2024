<?php

	include "../config.php";
	include "../fungsi.php";
	include "../class.ezpdf.php";

	error_reporting("E_ALL ^ E_NOTICE");

	if (empty($_COOKIE['user']) || empty($_COOKIE['pass'])){
		echo "
		<script>
			window.location.href=\"../accessdenied.php\";
		</script>
		";
	}
	else{
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND ($_COOKIE['level']==1 OR $_COOKIE['level']==2 OR $_COOKIE['level']==3 OR $_COOKIE['level']==5))
	{
		if ($_COOKIE[level]=='5') $nim_mhsw_ikgm = $_COOKIE[user];
		if ($_COOKIE[level]=='1' OR $_COOKIE[level]=='2' OR $_COOKIE[level]=='3') $nim_mhsw_ikgm = $_GET[nim];

		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw_ikgm'"));
		$datastase_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `stase_M106` WHERE `nim`='$nim_mhsw_ikgm'"));
		$mulai_stase = tanggal_indo($datastase_mhsw[tgl_mulai]);
		$selesai_stase = tanggal_indo($datastase_mhsw[tgl_selesai]);
		$periode_stase = $mulai_stase." s.d. ".$selesai_stase;

		$pdf = new Cezpdf('A4');
		$pdf->ezSetMargins(30,40,50,50);
		$pdf->selectFont('../fonts/Helvetica.afm');

		$daftar_jurnal = mysqli_query($con,"SELECT * FROM `ikgm_nilai_jurnal` WHERE `nim`='$nim_mhsw_ikgm' AND `status_approval`='1' ORDER BY `tgl_approval` ASC");
		$jumlah_halaman = mysqli_num_rows($daftar_jurnal);
		$halaman = 1;
		while ($data_jurnal = mysqli_fetch_array($daftar_jurnal))
		{
			//Judul
			$kolom1 = array ('item'=>"");
			$tabel1{1} = array ('item'=>"NILAI JOURNAL READING KEPANITERAAN (STASE) IKGM");
			$tabel1{2} = array ('item'=>"LOGBOOK KOAS PENDIDIKAN PROFESI DOKTER");
			$tabel1{3} = array ('item'=>"FAKULTAS KEDOKTERAN - UNIVERSITAS DIPONEGORO");
			$pdf->ezTable($tabel1,$kolom1,"",
			array('maxWidth'=>540,'width'=>520,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 0 ,
			'cols'=>array('item'=>array('justification'=>'left'))));

			//Data Mahasiswa
			$pdf->ezSetDy(-20,'');
			$tanggal_ujian = tanggal_indo($data_jurnal[tgl_ujian]);
			$kolom2 = array ('item'=>"",'isi'=>"");
			$tabel2{1} = array ('item'=>"Nama Mahasiswa", 'isi'=>": "."$data_mhsw[nama]");
			$tabel2{2} = array ('item'=>"NIM", 'isi'=>": "."$data_mhsw[nim]");
			$tabel2{3} = array ('item'=>"Kepaniteraan (Stase)", 'isi'=>": "."IKGM");
			$tabel2{4} = array ('item'=>"Periode", 'isi'=>": "."$periode_stase");
			$tabel2{5} = array ('item'=>"Tanggal Presentasi/Ujian", 'isi'=>": "."$tanggal_ujian");
			$tabel2{6} = array ('item'=>"Nama Jurnal", 'isi'=>": "."<i>$data_jurnal[nama_jurnal]</i>");
			$tabel2{7} = array ('item'=>"Judul Artikel", 'isi'=>": "."<i>$data_jurnal[judul_paper]</i>");
			$pdf->ezTable($tabel2,$kolom2,"",
			array('maxWidth'=>540,'width'=>520,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 0 ,
			'cols'=>array('item'=>array('justification'=>'left','width'=>150))));
			$pdf->ezSetDy(-20,'');

			//Header tabel
			$kolom3 = array ('NO'=>'','ASPEK'=>'','KRITERIA'=>'','NILAI'=>'');
			$tabel3{1} = array ('NO'=>'<b>No</b>','ASPEK'=>'<b>Aspek Yang Dinilai</b>','KRITERIA'=>'<b>Kriteria Penilaian</b>','NILAI'=>'<b>Nilai</b>');
			$pdf->ezTable($tabel3,$kolom3,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'ASPEK'=>array('justification'=>'center'),'KRITERIA'=>array('width'=>160,'justification'=>'center'),'NILAI'=>array('width'=>100,'justification'=>'center'))));

			//Nilai Aspek 1 - 5
			$aspek1="Kehadiran";
			$aspek2="Tugas terjemahan, slide presentasi dan telaah jurnal";
			$aspek3="Aktifitas saat diskusi\r\n(<i>Dinilai dari frekuensi mengajukan masukan / komentar / pendapat / jawaban</i>)";
			$aspek4="Relevansi pembicaraan\r\n(<i>Dinilai dari relevansi dan penguasaaan terhadap materi diskusi</i>)";
			$aspek5="Keterampilan presentasi/berkomunikasi\r\n(<i>Dinilai dari kemampuan berinteraksi dengan peserta lain. Tunjuk jari bila mau menyampaikan pendapat / bertanya; memperhatikan saat peserta lain berbicara; tidak emosional / tidak memotong pembicaraan orang lain / tidak mendominasi diskusi</i>)";
			$kriteria1="Tidak Hadir-(0)\r\nHadir, terlambat 11-15 menit-(2)\r\nHadir, terlambat kurang dari 10 menit-(3)\r\nHadir tepat waktu-(4)";
			$kriteria2="Tidak membuat-(1)\r\nKurang lengkap-(2)\r\nCukup lengkap-(3)\r\nLengkap-(4)";
			$kriteria3="Pasif-(1)\r\nKurang aktif-(2)\r\nCukup aktif-(3)\r\nSangat aktif-(4)";
			$kriteria4="Pembicaraan selalu tidak relevan-(1)\r\nPembicaraan sering tidak relevan-(2)\r\nPembicaraan cukup relevan-(3)\r\nPembicaraan selalu relevan-(4)";
			$kriteria5="Kurang-(1)\r\nCukup-(2)\r\nBaik-(3)\r\nSangat baik-(4)";

			$kolom4 = array ('NO'=>'','ASPEK'=>'','KRITERIA'=>'','NILAI'=>'');
			$tabel4{1} = array ('NO'=>'1','ASPEK'=>$aspek1,'KRITERIA'=>$kriteria1,'NILAI'=>$data_jurnal[aspek_1]);
			$tabel4{2} = array ('NO'=>'2','ASPEK'=>$aspek2,'KRITERIA'=>$kriteria2,'NILAI'=>$data_jurnal[aspek_2]);
			$tabel4{3} = array ('NO'=>'3','ASPEK'=>$aspek3,'KRITERIA'=>$kriteria3,'NILAI'=>$data_jurnal[aspek_3]);
			$tabel4{4} = array ('NO'=>'4','ASPEK'=>$aspek4,'KRITERIA'=>$kriteria4,'NILAI'=>$data_jurnal[aspek_4]);
			$tabel4{5} = array ('NO'=>'5','ASPEK'=>$aspek5,'KRITERIA'=>$kriteria5,'NILAI'=>$data_jurnal[aspek_5]);
			$pdf->ezTable($tabel4,$kolom4,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>8,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'KRITERIA'=>array('width'=>160),'NILAI'=>array('width'=>100,'justification'=>'center'))));

			//Nilai Total
			$kolom5 = array ('TOTAL'=>'','NILAI'=>'');
			$tabel5{1} = array ('TOTAL'=>'Total Nilai (10 x Jumlah Poin / 2):','NILAI'=>$data_jurnal[nilai_total]);
			$pdf->ezTable($tabel5,$kolom5,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('TOTAL'=>array('justification'=>'right'),'NILAI'=>array('width'=>100,'justification'=>'center'))));
			$pdf->ezSetDy(-20,'');

			//Catatan
			$kolom5a = array ('ITEM'=>'');
			$tabel5a{1} = array ('ITEM'=>"<b>Catatan Dosen Penguji (Tutor) Terhadap Kegiatan:</b>\r\n\r\n<i>$data_jurnal[catatan]</i>");
			$pdf->ezTable($tabel5a,$kolom5a,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>8,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('ITEM'=>array('width'=>510))));
			$pdf->ezSetDy(-20,'');

			//Persetujuan
			$tanggal_approval = tanggal_indo($data_jurnal[tgl_approval]);
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_jurnal[dosen]'"));
			$dosen = $data_dosen[nama].", ".$data_dosen[gelar];
			$kolom6 = array ('ITEM'=>'');
			$tabel6{1} = array ('ITEM'=>'Status: <b>DISETUJUI</b>');
			$tabel6{2} = array ('ITEM'=>'pada tanggal '.$tanggal_approval);
			$tabel6{3} = array ('ITEM'=>'Dosen Penguji (Tutor)');
			$tabel6{4} = array ('ITEM'=>'');
			$tabel6{5} = array ('ITEM'=>$dosen);
			$tabel6{6} = array ('ITEM'=>'NIP: '.$data_dosen[nip]);
			$pdf->ezTable($tabel6,$kolom6,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 1 ,'showBgCol'=> 0,
			'cols'=>array('ITEM'=>array('justification'=>'right'))));
			$pdf->ezSetDy(-10,'');
			$pdf->addText(50,25,10,"$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Nilai Journal Reading                  <i>[hal $halaman dari $jumlah_halaman hal]</i>");
			if ($halaman<$jumlah_halaman) $pdf->ezNewPage();

			$halaman++;
		}

		$pdf->ezStream();

	}
		else
		echo "
		<script>
			window.location.href=\"../accessdenied.php\";
		</script>
		";
	}
?>
