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
		if ($_COOKIE[level]=='5') $nim_mhsw_kdk = $_COOKIE[user];
		if ($_COOKIE[level]=='1' OR $_COOKIE[level]=='2' OR $_COOKIE[level]=='3') $nim_mhsw_kdk = $_GET[nim];

		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw_kdk'"));
		$pdf = new Cezpdf('A4');
		$pdf->ezSetMargins(30,40,50,50);
		$pdf->selectFont('../fonts/Helvetica.afm');

		$daftar_sikap = mysqli_query($con,"SELECT * FROM `kdk_nilai_sikap` WHERE `nim`='$nim_mhsw_kdk' AND `status_approval`='1' ORDER BY `tgl_approval` ASC");
		$jumlah_halaman = mysqli_num_rows($daftar_sikap) + 1;
		$halaman = 1;
		$aspek1 = "DISIPLIN\r\n(tepat waktu, mengikuti tata tertib)";
		$aspek2 = "KERJASAMA\r\n(dengan teman, pembimbing dan tenaga kesehatan lain)";
		$aspek3 = "KETELITIAN";
		$aspek4 = "INISIATIF / KREATIVITAS\r\n(mengambil keputusan, menyelesaikan masalan, dll)";
		$aspek5 = "SOPAN SANTUN\r\n(dengan pasien, pengunjung dan tenaga kesehatan lain)";
		$aspek6 = "TANGGUNG JAWAB\r\n(menyelesaikan tugas kelompok, tugas individu dan tugas lain dari pembimbing)";
		$aspek7 = "KERAMAHAN\r\n(dengan pasien, pengunjung dan tenaga kesehatan lain)";
		while ($data_sikap = mysqli_fetch_array($daftar_sikap))
		{
		  //Judul
		  $tanggal_mulai = tanggal_indo($data_sikap[tgl_mulai]);
		  $tanggal_selesai = tanggal_indo($data_sikap[tgl_selesai]);
		  $periode = $tanggal_mulai." s.d. ".$tanggal_selesai;

		  $kolom1 = array ('item'=>"");
		  $tabel1{1} = array ('item'=>"NILAI SIKAP KEPANITERAAN KEDOKTERAN KELUARGA");
		  $tabel1{2} = array ('item'=>"LOGBOOK KOAS PENDIDIKAN PROFESI DOKTER");
		  $tabel1{3} = array ('item'=>"FAKULTAS KEDOKTERAN - UNIVERSITAS DIPONEGORO");
		  $pdf->ezTable($tabel1,$kolom1,"",
		  array('maxWidth'=>540,'width'=>520,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 0 ,
		  'cols'=>array('item'=>array('justification'=>'left'))));

		  //Data Mahasiswa
		  $pdf->ezSetDy(-20,'');
		  $kolom2 = array ('item'=>"",'isi'=>"");
		  $tabel2{1} = array ('item'=>"Nama Dokter Muda", 'isi'=>": "."$data_mhsw[nama]");
		  $tabel2{2} = array ('item'=>"NIM", 'isi'=>": "."$data_mhsw[nim]");
		  $tabel2{3} = array ('item'=>"Kepaniteraan (Stase)", 'isi'=>": "."KEPANITERAAN KEDOKTERAN KELUARGA");
		  $tabel2{4} = array ('item'=>"Instansi", 'isi'=>": "."$data_sikap[instansi]");
		  $tabel2{5} = array ('item'=>"Nama Puskesmas / Klinik Pratama", 'isi'=>": "."$data_sikap[lokasi]");
		  $tabel2{6} = array ('item'=>"Periode Kegiatan", 'isi'=>": "."$periode");
		  $pdf->ezTable($tabel2,$kolom2,"",
		  array('maxWidth'=>540,'width'=>520,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 0 ,
		  'cols'=>array('item'=>array('justification'=>'left','width'=>170))));
		  $pdf->ezSetDy(-20,'');

		  //Header tabel
		  $kolom3 = array ('NO'=>'','ASPEK'=>'','BOBOT'=>'','NILAI'=>'');
		  $tabel3{1} = array ('NO'=>'<b>No</b>','ASPEK'=>'<b>Aspek Yang Dinilai</b>','BOBOT'=>'<b>Bobot</b>','NILAI'=>'<b>Nilai (0-100)</b>');
		  $pdf->ezTable($tabel3,$kolom3,"",
		  array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
		  'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'ASPEK'=>array('justification'=>'center'),'BOBOT'=>array('width'=>100,'justification'=>'center'),'NILAI'=>array('width'=>100,'justification'=>'center'))));

		  //Nilai Aspek 1 - 7
		  $kolom4 = array ('NO'=>'','ASPEK'=>'','BOBOT'=>'','NILAI'=>'');
		  $tabel4{1} = array ('NO'=>'1','ASPEK'=>$aspek1,'BOBOT'=>'15%','NILAI'=>$data_sikap[aspek_1]);
		  $tabel4{2} = array ('NO'=>'2','ASPEK'=>$aspek2,'BOBOT'=>'15%','NILAI'=>$data_sikap[aspek_2]);
		  $tabel4{3} = array ('NO'=>'3','ASPEK'=>$aspek3,'BOBOT'=>'15%','NILAI'=>$data_sikap[aspek_3]);
		  $tabel4{4} = array ('NO'=>'4','ASPEK'=>$aspek4,'BOBOT'=>'15%','NILAI'=>$data_sikap[aspek_4]);
		  $tabel4{5} = array ('NO'=>'5','ASPEK'=>$aspek5,'BOBOT'=>'15%','NILAI'=>$data_sikap[aspek_5]);
		  $tabel4{6} = array ('NO'=>'6','ASPEK'=>$aspek6,'BOBOT'=>'15%','NILAI'=>$data_sikap[aspek_6]);
		  $tabel4{7} = array ('NO'=>'7','ASPEK'=>$aspek7,'BOBOT'=>'10%','NILAI'=>$data_sikap[aspek_7]);
		  $pdf->ezTable($tabel4,$kolom4,"",
		  array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
		  'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'BOBOT'=>array('width'=>100,'justification'=>'center'),'NILAI'=>array('width'=>100,'justification'=>'center'))));

		  //Nilai Total
		  $kolom5 = array ('TOTAL'=>'','NILAI'=>'');
		  $tabel5{1} = array ('TOTAL'=>'Rata-Rata Nilai (Jumlah Bobot x Nilai):','NILAI'=>"<b>$data_sikap[nilai_rata]</b>");
		  $pdf->ezTable($tabel5,$kolom5,"",
		  array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
		  'cols'=>array('TOTAL'=>array('justification'=>'right'),'NILAI'=>array('width'=>100,'justification'=>'center'))));
		  $pdf->ezSetDy(-20,'');

			//Umpan Balik
			$kolom5a = array ('ITEM'=>'');
			$tabel5a{1} = array ('ITEM'=>"<b>Umpan Balik:</b>\r\n\r\n<i>$data_sikap[umpan_balik]</i>\r\n");
			$pdf->ezTable($tabel5a,$kolom5a,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('ITEM'=>array('width'=>510))));
			//Saran
			$kolom5d = array ('ITEM'=>'');
			$tabel5d{1} = array ('ITEM'=>"<b>Saran:</b>\r\n\r\n<i>$data_sikap[saran]</i>\r\n");
			$pdf->ezTable($tabel5d,$kolom5d,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('ITEM'=>array('width'=>510))));
			$pdf->ezSetDy(-20,'');

		  //Persetujuan
		  $tanggal_approval = tanggal_indo($data_sikap[tgl_approval]);
		  $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_sikap[dosen]'"));
		  $dosen = $data_dosen[nama].", ".$data_dosen[gelar];
		  $kolom6 = array ('ITEM'=>'');
		  $tabel6{1} = array ('ITEM'=>'Status: <b>DISETUJUI</b>');
		  $tabel6{2} = array ('ITEM'=>'pada tanggal '.$tanggal_approval);
		  $tabel6{3} = array ('ITEM'=>'Dokter Pembimbing');
		  $tabel6{4} = array ('ITEM'=>'');
		  $tabel6{5} = array ('ITEM'=>$dosen);
		  $tabel6{6} = array ('ITEM'=>'NIP: '.$data_dosen[nip]);
		  $pdf->ezTable($tabel6,$kolom6,"",
		  array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 1 ,'showBgCol'=> 0,
		  'cols'=>array('ITEM'=>array('justification'=>'right'))));
		  $pdf->ezSetDy(-10,'');
		  $pdf->addText(50,25,10,"$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Nilai Sikap                              <i>[hal $halaman dari $jumlah_halaman hal]</i>");
		  $pdf->ezNewPage();

		  $halaman++;
		}

		//---------------------
		//Rekap Nilai Puskesmas
		//---------------------
		//Nilai Rata Sikap
		$sikap = mysqli_query($con,"SELECT * FROM `kdk_nilai_sikap` WHERE `nim`='$nim_mhsw_kdk' AND `instansi`='Puskesmas' AND `status_approval`='1'");
		$jumlah_sikap = mysqli_num_rows($sikap);
		$jum_nilai = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`aspek_1`), SUM(`aspek_2`), SUM(`aspek_3`), SUM(`aspek_4`), SUM(`aspek_5`), SUM(`aspek_6`), SUM(`aspek_7`), SUM(`nilai_rata`) FROM `kdk_nilai_sikap` WHERE `nim`='$nim_mhsw_kdk' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
		if ($jumlah_sikap>0) $rata_aspek1_puskesmas =  number_format($jum_nilai[0]/$jumlah_sikap,2);
		else $rata_aspek1_puskesmas = 0.00;
		if ($jumlah_sikap>0) $rata_aspek2_puskesmas =  number_format($jum_nilai[1]/$jumlah_sikap,2);
		else $rata_aspek2_puskesmas = 0.00;
		if ($jumlah_sikap>0) $rata_aspek3_puskesmas =  number_format($jum_nilai[2]/$jumlah_sikap,2);
		else $rata_aspek3_puskesmas = 0.00;
		if ($jumlah_sikap>0) $rata_aspek4_puskesmas =  number_format($jum_nilai[3]/$jumlah_sikap,2);
		else $rata_aspek4_puskesmas = 0.00;
		if ($jumlah_sikap>0) $rata_aspek5_puskesmas =  number_format($jum_nilai[4]/$jumlah_sikap,2);
		else $rata_aspek5_puskesmas = 0.00;
		if ($jumlah_sikap>0) $rata_aspek6_puskesmas =  number_format($jum_nilai[5]/$jumlah_sikap,2);
		else $rata_aspek6_puskesmas = 0.00;
		if ($jumlah_sikap>0) $rata_aspek7_puskesmas =  number_format($jum_nilai[6]/$jumlah_sikap,2);
		else $rata_aspek7_puskesmas = 0.00;
		if ($jumlah_sikap>0) $total_sikap_puskesmas =  number_format($jum_nilai[7]/$jumlah_sikap,2);
		else $total_sikap_puskesmas = 0.00;

		//---------------------
		//Rekap Nilai Klinik
		//---------------------
		//Nilai Rata Sikap
		$sikap = mysqli_query($con,"SELECT * FROM `kdk_nilai_sikap` WHERE `nim`='$nim_mhsw_kdk' AND `instansi`='Klinik Pratama' AND `status_approval`='1'");
		$jumlah_sikap = mysqli_num_rows($sikap);
		$jum_nilai = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`aspek_1`), SUM(`aspek_2`), SUM(`aspek_3`), SUM(`aspek_4`), SUM(`aspek_5`), SUM(`aspek_6`), SUM(`aspek_7`), SUM(`nilai_rata`) FROM `kdk_nilai_sikap` WHERE `nim`='$nim_mhsw_kdk' AND `instansi`='Klinik Pratama' AND `status_approval`='1'"));
		if ($jumlah_sikap>0) $rata_aspek1_klinik =  number_format($jum_nilai[0]/$jumlah_sikap,2);
		else $rata_aspek1_klinik = 0.00;
		if ($jumlah_sikap>0) $rata_aspek2_klinik =  number_format($jum_nilai[1]/$jumlah_sikap,2);
		else $rata_aspek2_klinik = 0.00;
		if ($jumlah_sikap>0) $rata_aspek3_klinik =  number_format($jum_nilai[2]/$jumlah_sikap,2);
		else $rata_aspek3_klinik = 0.00;
		if ($jumlah_sikap>0) $rata_aspek4_klinik =  number_format($jum_nilai[3]/$jumlah_sikap,2);
		else $rata_aspek4_klinik = 0.00;
		if ($jumlah_sikap>0) $rata_aspek5_klinik =  number_format($jum_nilai[4]/$jumlah_sikap,2);
		else $rata_aspek5_klinik = 0.00;
		if ($jumlah_sikap>0) $rata_aspek6_klinik =  number_format($jum_nilai[5]/$jumlah_sikap,2);
		else $rata_aspek6_klinik = 0.00;
		if ($jumlah_sikap>0) $rata_aspek7_klinik =  number_format($jum_nilai[6]/$jumlah_sikap,2);
		else $rata_aspek7_klinik = 0.00;
		if ($jumlah_sikap>0) $total_sikap_klinik =  number_format($jum_nilai[7]/$jumlah_sikap,2);
		else $total_sikap_klinik = 0.00;

		//Judul Rekap Total
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `stase_M121` WHERE `nim`='$nim_mhsw_kdk'"));
		$tanggal_mulai = tanggal_indo($data_stase[tgl_mulai]);
		$tanggal_selesai = tanggal_indo($data_stase[tgl_selesai]);
		$periode = $tanggal_mulai." s.d. ".$tanggal_selesai;

		$kolom7 = array ('item'=>"");
		$tabel7{1} = array ('item'=>"RATA-RATA NILAI SIKAP KEPANITERAAN KEDOKTERAN KELUARGA");
		$tabel7{2} = array ('item'=>"LOGBOOK KOAS PENDIDIKAN PROFESI DOKTER");
		$tabel7{3} = array ('item'=>"FAKULTAS KEDOKTERAN - UNIVERSITAS DIPONEGORO");
		$pdf->ezTable($tabel7,$kolom7,"",
		array('maxWidth'=>540,'width'=>520,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 0 ,
		'cols'=>array('item'=>array('justification'=>'left'))));

		//Data Mahasiswa
		$pdf->ezSetDy(-20,'');
		$kolom8 = array ('item'=>"",'isi'=>"");
		$tabel8{1} = array ('item'=>"Nama Dokter Muda", 'isi'=>": "."$data_mhsw[nama]");
		$tabel8{2} = array ('item'=>"NIM", 'isi'=>": "."$data_mhsw[nim]");
		$tabel8{3} = array ('item'=>"Kepaniteraan (Stase)", 'isi'=>": "."KEPANITERAAN KEDOKTERAN KELUARGA");
		$tabel8{4} = array ('item'=>"Periode", 'isi'=>": "."$periode");
		$pdf->ezTable($tabel8,$kolom8,"",
		array('maxWidth'=>540,'width'=>520,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 0 ,
		'cols'=>array('item'=>array('justification'=>'left','width'=>110))));
		$pdf->ezSetDy(-20,'');

		//Rekap Puskesmas
		$kolom8a = array ('ITEM'=>'');
		$tabel8a{1} = array ('ITEM'=>'<b>Rekap Rata-Rata Nilai Sikap di Puskesmas</b>');
		$pdf->ezTable($tabel8a,$kolom8a,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>8,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>10, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 2 ,'showBgCol'=> 0,
		'cols'=>array('ITEM'=>array('width'=>510))));

		//Header tabel
		$kolom9 = array ('NO'=>'','ASPEK'=>'','BOBOT'=>'','NILAI'=>'');
		$tabel9{1} = array ('NO'=>'<b>No</b>','ASPEK'=>'<b>Aspek Yang Dinilai</b>','BOBOT'=>'<b>Bobot</b>','NILAI'=>'<b>Nilai (0-100)</b>');
		$pdf->ezTable($tabel9,$kolom9,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>8,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>10, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 2 ,'showBgCol'=> 0,
		'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'ASPEK'=>array('justification'=>'center'),'BOBOT'=>array('width'=>100,'justification'=>'center'),'NILAI'=>array('width'=>100,'justification'=>'center'))));

		//Nilai Aspek 1 - 7
		$kolom10 = array ('NO'=>'','ASPEK'=>'','BOBOT'=>'','NILAI'=>'');
		$tabel10{1} = array ('NO'=>'1','ASPEK'=>$aspek1,'BOBOT'=>'15%','NILAI'=>$rata_aspek1_puskesmas);
		$tabel10{2} = array ('NO'=>'2','ASPEK'=>$aspek2,'BOBOT'=>'15%','NILAI'=>$rata_aspek2_puskesmas);
		$tabel10{3} = array ('NO'=>'3','ASPEK'=>$aspek3,'BOBOT'=>'15%','NILAI'=>$rata_aspek3_puskesmas);
		$tabel10{4} = array ('NO'=>'4','ASPEK'=>$aspek4,'BOBOT'=>'15%','NILAI'=>$rata_aspek4_puskesmas);
		$tabel10{5} = array ('NO'=>'5','ASPEK'=>$aspek5,'BOBOT'=>'15%','NILAI'=>$rata_aspek5_puskesmas);
		$tabel10{6} = array ('NO'=>'6','ASPEK'=>$aspek6,'BOBOT'=>'15%','NILAI'=>$rata_aspek6_puskesmas);
		$tabel10{7} = array ('NO'=>'7','ASPEK'=>$aspek7,'BOBOT'=>'10%','NILAI'=>$rata_aspek7_puskesmas);
		$pdf->ezTable($tabel10,$kolom10,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>8,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>10, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 2 ,'showBgCol'=> 0,
		'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'BOBOT'=>array('width'=>100,'justification'=>'center'),'NILAI'=>array('width'=>100,'justification'=>'center'))));

		//Nilai Total
		$kolom11 = array ('TOTAL'=>'','NILAI'=>'');
		$tabel11{1} = array ('TOTAL'=>'Rata-Rata Nilai Total:','NILAI'=>"<b>$total_sikap_puskesmas</b>");
		$pdf->ezTable($tabel11,$kolom11,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>8,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>10, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 2 ,'showBgCol'=> 0,
		'cols'=>array('TOTAL'=>array('justification'=>'right'),'NILAI'=>array('width'=>100,'justification'=>'center'))));
		$pdf->ezSetDy(-20,'');

		//Rekap Klinik
		$kolom8b = array ('ITEM'=>'');
		$tabel8b{1} = array ('ITEM'=>'<b>Rekap Rata-Rata Nilai Sikap di Klinik Pratama</b>');
		$pdf->ezTable($tabel8b,$kolom8b,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>8,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>10, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 2 ,'showBgCol'=> 0,
		'cols'=>array('ITEM'=>array('width'=>510))));

		//Header tabel
		$kolom9a = array ('NO'=>'','ASPEK'=>'','BOBOT'=>'','NILAI'=>'');
		$tabel9a{1} = array ('NO'=>'<b>No</b>','ASPEK'=>'<b>Aspek Yang Dinilai</b>','BOBOT'=>'<b>Bobot</b>','NILAI'=>'<b>Nilai (0-100)</b>');
		$pdf->ezTable($tabel9a,$kolom9a,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>8,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>10, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 2 ,'showBgCol'=> 0,
		'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'ASPEK'=>array('justification'=>'center'),'BOBOT'=>array('width'=>100,'justification'=>'center'),'NILAI'=>array('width'=>100,'justification'=>'center'))));

		//Nilai Aspek 1 - 7
		$kolom10a = array ('NO'=>'','ASPEK'=>'','BOBOT'=>'','NILAI'=>'');
		$tabel10a{1} = array ('NO'=>'1','ASPEK'=>$aspek1,'BOBOT'=>'15%','NILAI'=>$rata_aspek1_klinik);
		$tabel10a{2} = array ('NO'=>'2','ASPEK'=>$aspek2,'BOBOT'=>'15%','NILAI'=>$rata_aspek2_klinik);
		$tabel10a{3} = array ('NO'=>'3','ASPEK'=>$aspek3,'BOBOT'=>'15%','NILAI'=>$rata_aspek3_klinik);
		$tabel10a{4} = array ('NO'=>'4','ASPEK'=>$aspek4,'BOBOT'=>'15%','NILAI'=>$rata_aspek4_klinik);
		$tabel10a{5} = array ('NO'=>'5','ASPEK'=>$aspek5,'BOBOT'=>'15%','NILAI'=>$rata_aspek5_klinik);
		$tabel10a{6} = array ('NO'=>'6','ASPEK'=>$aspek6,'BOBOT'=>'15%','NILAI'=>$rata_aspek6_klinik);
		$tabel10a{7} = array ('NO'=>'7','ASPEK'=>$aspek7,'BOBOT'=>'10%','NILAI'=>$rata_aspek7_klinik);
		$pdf->ezTable($tabel10a,$kolom10a,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>8,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>10, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 2 ,'showBgCol'=> 0,
		'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'BOBOT'=>array('width'=>100,'justification'=>'center'),'NILAI'=>array('width'=>100,'justification'=>'center'))));

		//Nilai Total
		$kolom11a = array ('TOTAL'=>'','NILAI'=>'');
		$tabel11a{1} = array ('TOTAL'=>'Rata-Rata Nilai Total:','NILAI'=>"<b>$total_sikap_klinik</b>");
		$pdf->ezTable($tabel11a,$kolom11a,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>8,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>10, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 2 ,'showBgCol'=> 0,
		'cols'=>array('TOTAL'=>array('justification'=>'right'),'NILAI'=>array('width'=>100,'justification'=>'center'))));
		$pdf->ezSetDy(-20,'');

		//Nilai Total Rata-rata
		$rata_total_sikap=($total_sikap_puskesmas+$total_sikap_klinik)/2;
		$rata_total_sikap = number_format($rata_total_sikap,2);
		$kolom12 = array ('TOTAL'=>'','NILAI'=>'');
		$tabel12{1} = array ('TOTAL'=>'Rata-Rata Nilai Sikap','NILAI'=>": <b>$rata_total_sikap</b>");
		$pdf->ezTable($tabel12,$kolom12,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
		'cols'=>array('TOTAL'=>array('width'=>155))));
		$pdf->ezSetDy(-20,'');

		//Persetujuan Kordik
		$nip_kordik = mysqli_fetch_array(mysqli_query($con,"SELECT `username` FROM `admin` WHERE `stase`='K121'"));
		$data_kordik = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$nip_kordik[username]'"));
		$kordik = $data_kordik[nama].", ".$data_kordik[gelar];
		$kolom6a = array ('ITEM'=>'');
		$tabel6a{1} = array ('ITEM'=>'Status: <b>DISETUJUI</b>');
		$tabel6a{2} = array ('ITEM'=>'pada tanggal _____________________');
		$tabel6a{3} = array ('ITEM'=>'Kordik Kepaniteraan Kedokteran Keluarga');
		$tabel6a{4} = array ('ITEM'=>'');
		$tabel6a{5} = array ('ITEM'=>'');
		$tabel6a{6} = array ('ITEM'=>$kordik);
		$tabel6a{7} = array ('ITEM'=>'NIP: '.$data_kordik[nip]);
		$pdf->ezTable($tabel6a,$kolom6a,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 1 ,'showBgCol'=> 0,
		'cols'=>array('ITEM'=>array('justification'=>'right'))));

		$pdf->ezSetDy(-10,'');
		$pdf->addText(50,25,10,"$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Nilai Sikap                              <i>[hal $halaman dari $jumlah_halaman hal]</i>");
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
