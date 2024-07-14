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
		if ($_COOKIE[level]=='5') $nim_mhsw_kompre = $_COOKIE[user];
		if ($_COOKIE[level]=='1' OR $_COOKIE[level]=='2' OR $_COOKIE[level]=='3') $nim_mhsw_kompre = $_GET[nim];

		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw_kompre'"));
		$pdf = new Cezpdf('A4');
		$pdf->ezSetMargins(30,40,50,50);
		$pdf->selectFont('../fonts/Helvetica.afm');

		$daftar_cbd = mysqli_query($con,"SELECT * FROM `kompre_nilai_cbd` WHERE `nim`='$nim_mhsw_kompre' AND `status_approval`='1' ORDER BY `tgl_approval` ASC");
		$jumlah_halaman = mysqli_num_rows($daftar_cbd) + 1;
		$halaman = 1;
		$aspek1 = "Kemampuan Anamnesis dan Pemeriksaan Fisik";
		$aspek2 = "Kemampuan Keputusan klinis (usulan pemeriksaan penunjang level FKTP dan penegakan diagnosis)";
		$aspek3 = "Kemampuan Penatalaksanaan Pasien (resep)";
		$aspek4 = "Kemampuan edukasi, komunikasi dan profesionalisme";
		while ($data_cbd = mysqli_fetch_array($daftar_cbd))
		{
		  //Judul
		  $tanggal_mulai = tanggal_indo($data_cbd[tgl_mulai]);
		  $tanggal_selesai = tanggal_indo($data_cbd[tgl_selesai]);
		  $periode = $tanggal_mulai." s.d. ".$tanggal_selesai;

		  $kolom1 = array ('item'=>"");
		  $tabel1{1} = array ('item'=>"NILAI CASE BASED DISCUSSION (CBD) KEPANITERAAN KOMPREHENSIP");
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
		  $tabel2{3} = array ('item'=>"Judul Kasus", 'isi'=>": "."$data_cbd[kasus]");
		  $tabel2{4} = array ('item'=>"Diajukan pada:", 'isi'=>"");
			$tgl_isi = tanggal_indo($data_cbd[tgl_isi]);
		  $tabel2{5} = array ('item'=>"   Tanggal isi kegiatan", 'isi'=>": "."$tgl_isi");
		  $tabel2{6} = array ('item'=>"   Jam isi kegiatan", 'isi'=>": "."$data_cbd[jam_isi]");
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
		  $tabel4{1} = array ('NO'=>'1','ASPEK'=>$aspek1,'BOBOT'=>'25%','NILAI'=>$data_cbd[aspek_1]);
		  $tabel4{2} = array ('NO'=>'2','ASPEK'=>$aspek2,'BOBOT'=>'25%','NILAI'=>$data_cbd[aspek_2]);
		  $tabel4{3} = array ('NO'=>'3','ASPEK'=>$aspek3,'BOBOT'=>'25%','NILAI'=>$data_cbd[aspek_3]);
		  $tabel4{4} = array ('NO'=>'4','ASPEK'=>$aspek4,'BOBOT'=>'25%','NILAI'=>$data_cbd[aspek_4]);
		  $pdf->ezTable($tabel4,$kolom4,"",
		  array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
		  'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'BOBOT'=>array('width'=>100,'justification'=>'center'),'NILAI'=>array('width'=>100,'justification'=>'center'))));

		  //Nilai Total
		  $kolom5 = array ('TOTAL'=>'','NILAI'=>'');
		  $tabel5{1} = array ('TOTAL'=>'Rata-Rata Nilai (Jumlah Bobot x Nilai):','NILAI'=>"<b>$data_cbd[nilai_rata]</b>");
		  $pdf->ezTable($tabel5,$kolom5,"",
		  array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
		  'cols'=>array('TOTAL'=>array('justification'=>'right'),'NILAI'=>array('width'=>100,'justification'=>'center'))));
		  $pdf->ezSetDy(-20,'');

			//Umpan Balik
			$kolom5a = array ('ITEM'=>'');
			$tabel5a{1} = array ('ITEM'=>"<b>Umpan Balik:</b>\r\n\r\n<i>$data_cbd[umpan_balik]</i>\r\n");
			$pdf->ezTable($tabel5a,$kolom5a,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('ITEM'=>array('width'=>510))));
			//Saran
			$kolom5d = array ('ITEM'=>'');
			$tabel5d{1} = array ('ITEM'=>"<b>Saran:</b>\r\n\r\n<i>$data_cbd[saran]</i>\r\n");
			$pdf->ezTable($tabel5d,$kolom5d,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('ITEM'=>array('width'=>510))));
			$pdf->ezSetDy(-20,'');

		  //Persetujuan
		  $tanggal_approval = tanggal_indo($data_cbd[tgl_approval]);
		  $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_cbd[dosen]'"));
		  $dosen = $data_dosen[nama].", ".$data_dosen[gelar];
		  $kolom6 = array ('ITEM'=>'');
		  $tabel6{1} = array ('ITEM'=>'Status: <b>DISETUJUI</b>');
		  $tabel6{2} = array ('ITEM'=>'pada tanggal '.$tanggal_approval);
		  $tabel6{3} = array ('ITEM'=>'Dosen Pembimbing Lapangan');
		  $tabel6{4} = array ('ITEM'=>'');
		  $tabel6{5} = array ('ITEM'=>$dosen);
		  $tabel6{6} = array ('ITEM'=>'NIP: '.$data_dosen[nip]);
		  $pdf->ezTable($tabel6,$kolom6,"",
		  array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 1 ,'showBgCol'=> 0,
		  'cols'=>array('ITEM'=>array('justification'=>'right'))));
		  $pdf->ezSetDy(-10,'');
		  $pdf->addText(50,25,10,"$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Nilai CBD                       <i>[hal $halaman dari $jumlah_halaman hal]</i>");
		  $pdf->ezNewPage();

		  $halaman++;
		}

		//---------------------
		//Rekap Nilai CBD
		//---------------------
		//Nilai Rata CBD
		$jumlah_cbd = mysqli_num_rows($daftar_cbd);
		$jum_nilai = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`aspek_1`), SUM(`aspek_2`), SUM(`aspek_3`), SUM(`aspek_4`), SUM(`nilai_rata`) FROM `kompre_nilai_cbd` WHERE `nim`='$nim_mhsw_kompre' AND `status_approval`='1'"));
		if ($jumlah_cbd>0) $rata_aspek1 =  number_format($jum_nilai[0]/$jumlah_cbd,2);
		else $rata_aspek1 = 0.00;
		if ($jumlah_cbd>0) $rata_aspek2 =  number_format($jum_nilai[1]/$jumlah_cbd,2);
		else $rata_aspek2 = 0.00;
		if ($jumlah_cbd>0) $rata_aspek3 =  number_format($jum_nilai[2]/$jumlah_cbd,2);
		else $rata_aspek3 = 0.00;
		if ($jumlah_cbd>0) $rata_aspek4 =  number_format($jum_nilai[3]/$jumlah_cbd,2);
		else $rata_aspek4 = 0.00;
		if ($jumlah_cbd>0) $total_cbd =  number_format($jum_nilai[4]/$jumlah_cbd,2);
		else $total_cbd = 0.00;

		//Judul Rekap Total
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `stase_M121` WHERE `nim`='$nim_mhsw_kompre'"));
		$tanggal_mulai = tanggal_indo($data_stase[tgl_mulai]);
		$tanggal_selesai = tanggal_indo($data_stase[tgl_selesai]);
		$periode = $tanggal_mulai." s.d. ".$tanggal_selesai;

		$kolom7 = array ('item'=>"");
		$tabel7{1} = array ('item'=>"RATA-RATA NILAI CASE BASED DISCUSSION (CBD) KEPANITERAAN KOMPREHENSIP");
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
		$tabel8{3} = array ('item'=>"Kepaniteraan (Stase)", 'isi'=>": "."KEPANITERAAN KOMPREHENSIP");
		$tabel8{4} = array ('item'=>"Periode", 'isi'=>": "."$periode");
		$pdf->ezTable($tabel8,$kolom8,"",
		array('maxWidth'=>540,'width'=>520,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 0 ,
		'cols'=>array('item'=>array('justification'=>'left','width'=>110))));
		$pdf->ezSetDy(-20,'');

		//Header tabel
		$kolom9 = array ('NO'=>'','ASPEK'=>'','BOBOT'=>'','NILAI'=>'');
		$tabel9{1} = array ('NO'=>'<b>No</b>','ASPEK'=>'<b>Aspek Yang Dinilai</b>','BOBOT'=>'<b>Bobot</b>','NILAI'=>'<b>Nilai (0-100)</b>');
		$pdf->ezTable($tabel9,$kolom9,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 2 ,'showBgCol'=> 0,
		'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'ASPEK'=>array('justification'=>'center'),'BOBOT'=>array('width'=>100,'justification'=>'center'),'NILAI'=>array('width'=>100,'justification'=>'center'))));

		//Nilai Aspek 1 - 7
		$kolom10 = array ('NO'=>'','ASPEK'=>'','BOBOT'=>'','NILAI'=>'');
		$tabel10{1} = array ('NO'=>'1','ASPEK'=>$aspek1,'BOBOT'=>'25%','NILAI'=>$rata_aspek1);
		$tabel10{2} = array ('NO'=>'2','ASPEK'=>$aspek2,'BOBOT'=>'25%','NILAI'=>$rata_aspek2);
		$tabel10{3} = array ('NO'=>'3','ASPEK'=>$aspek3,'BOBOT'=>'25%','NILAI'=>$rata_aspek3);
		$tabel10{4} = array ('NO'=>'4','ASPEK'=>$aspek4,'BOBOT'=>'25%','NILAI'=>$rata_aspek4);
		$pdf->ezTable($tabel10,$kolom10,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 2 ,'showBgCol'=> 0,
		'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'BOBOT'=>array('width'=>100,'justification'=>'center'),'NILAI'=>array('width'=>100,'justification'=>'center'))));

		//Nilai Total
		$kolom11 = array ('TOTAL'=>'','NILAI'=>'');
		$tabel11{1} = array ('TOTAL'=>'Rata-Rata Nilai (Jumlah Bobot x Nilai):','NILAI'=>"<b>$total_cbd</b>");
		$pdf->ezTable($tabel11,$kolom11,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 2 ,'showBgCol'=> 0,
		'cols'=>array('TOTAL'=>array('justification'=>'right'),'NILAI'=>array('width'=>100,'justification'=>'center'))));
		$pdf->ezSetDy(-20,'');

		//Persetujuan Kordik
		$admin_kordik ="K122";
		$nip_kordik = mysqli_fetch_array(mysqli_query($con,"SELECT `username` FROM `admin` WHERE `stase`='$admin_kordik'"));
		$data_kordik = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$nip_kordik[username]'"));
		$kordik = $data_kordik[nama].", ".$data_kordik[gelar];
		$kolom6a = array ('ITEM'=>'');
		$tabel6a{1} = array ('ITEM'=>'Status: <b>DISETUJUI</b>');
		$tabel6a{2} = array ('ITEM'=>'pada tanggal _____________________');
		$tabel6a{3} = array ('ITEM'=>'Kordik Kepaniteraan Komprehensip');
		$tabel6a{4} = array ('ITEM'=>'');
		$tabel6a{5} = array ('ITEM'=>'');
		$tabel6a{6} = array ('ITEM'=>$kordik);
		$tabel6a{7} = array ('ITEM'=>'NIP: '.$data_kordik[nip]);
		$pdf->ezTable($tabel6a,$kolom6a,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 1 ,'showBgCol'=> 0,
		'cols'=>array('ITEM'=>array('justification'=>'right'))));

		$pdf->ezSetDy(-10,'');
		$pdf->addText(50,25,10,"$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Nilai CBD                       <i>[hal $halaman dari $jumlah_halaman hal]</i>");
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
