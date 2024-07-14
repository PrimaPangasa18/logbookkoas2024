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

		$daftar_kasus = mysqli_query($con,"SELECT * FROM `kdk_nilai_kasus` WHERE `nim`='$nim_mhsw_kdk' AND `status_approval`='1' ORDER BY `tgl_approval` ASC");
		$jumlah_halaman = mysqli_num_rows($daftar_kasus) + 1;
		$halaman = 1;
		while ($data_kasus = mysqli_fetch_array($daftar_kasus))
		{
			//Judul
			$tanggal_mulai = tanggal_indo($data_kasus[tgl_mulai]);
			$tanggal_selesai = tanggal_indo($data_kasus[tgl_selesai]);
			$periode = $tanggal_mulai." s.d. ".$tanggal_selesai;

			$kolom1 = array ('item'=>"");
			$tabel1{1} = array ('item'=>"NILAI LAPORAN KASUS KEPANITERAAN KEDOKTERAN KELUARGA");
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
			$tabel2{4} = array ('item'=>"Lokasi Kegiatan", 'isi'=>": "."$data_kasus[lokasi]");
			$tabel2{5} = array ('item'=>"Jenis Kasus", 'isi'=>": Kasus "."$data_kasus[kasus]");
			$tabel2{6} = array ('item'=>"Periode Kegiatan", 'isi'=>": "."$periode");
			$pdf->ezTable($tabel2,$kolom2,"",
			array('maxWidth'=>540,'width'=>520,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 0 ,
			'cols'=>array('item'=>array('justification'=>'left','width'=>110))));
			$pdf->ezSetDy(-20,'');

			//Header tabel
			$kolom3 = array ('NO'=>'','ASPEK'=>'','BOBOT'=>'','NILAI'=>'');
			$tabel3{1} = array ('NO'=>'<b>No</b>','ASPEK'=>'<b>Aspek Yang Dinilai</b>','BOBOT'=>'<b>Bobot</b>','NILAI'=>'<b>Nilai (0-100)</b>');
			$pdf->ezTable($tabel3,$kolom3,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'ASPEK'=>array('justification'=>'center'),'BOBOT'=>array('width'=>100,'justification'=>'center'),'NILAI'=>array('width'=>100,'justification'=>'center'))));

			//Nilai Aspek 1 - 5
			$kolom4 = array ('NO'=>'','ASPEK'=>'','BOBOT'=>'','NILAI'=>'');
			$tabel4{1} = array ('NO'=>'1','ASPEK'=>'Kejelasan penyajian/presentasi','BOBOT'=>'20%','NILAI'=>$data_kasus[aspek_1]);
			$tabel4{2} = array ('NO'=>'2','ASPEK'=>'Kualitas kemampuan identifikasi masalah pasien dan keluarga (dilihat dari kejelasan isi dan penulisan)','BOBOT'=>'20%','NILAI'=>$data_kasus[aspek_2]);
			$tabel4{3} = array ('NO'=>'3','ASPEK'=>'Kualitas kemampuan diagnosis holistic (dilihat dari kejelasan isi dan penulisan)','BOBOT'=>'20&','NILAI'=>$data_kasus[aspek_3]);
			$tabel4{4} = array ('NO'=>'4','ASPEK'=>'Kualitas kemampuan penatalaksanaan kasus (dilihat dari kejelasan isi dan penulisan)','BOBOT'=>'20%','NILAI'=>$data_kasus[aspek_4]);
			$tabel4{5} = array ('NO'=>'5','ASPEK'=>'Kelancaran diskusi','BOBOT'=>'20%','NILAI'=>$data_kasus[aspek_5]);
			$pdf->ezTable($tabel4,$kolom4,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'BOBOT'=>array('width'=>100,'justification'=>'center'),'NILAI'=>array('width'=>100,'justification'=>'center'))));

			//Nilai Total
			$kolom5 = array ('TOTAL'=>'','NILAI'=>'');
			$tabel5{1} = array ('TOTAL'=>'Rata-Rata Nilai (Jumlah Bobot x Nilai):','NILAI'=>"<b>$data_kasus[nilai_rata]</b>");
			$pdf->ezTable($tabel5,$kolom5,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('TOTAL'=>array('justification'=>'right'),'NILAI'=>array('width'=>100,'justification'=>'center'))));
			$pdf->ezSetDy(-20,'');

			//Umpan Balik
			$kolom5a = array ('ITEM'=>'');
			$tabel5a{1} = array ('ITEM'=>"<b>Umpan Balik:</b>\r\n\r\n<i>$data_kasus[umpan_balik]</i>\r\n");
			$pdf->ezTable($tabel5a,$kolom5a,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('ITEM'=>array('width'=>510))));
			//Saran
			$kolom5d = array ('ITEM'=>'');
			$tabel5d{1} = array ('ITEM'=>"<b>Saran:</b>\r\n\r\n<i>$data_kasus[saran]</i>\r\n");
			$pdf->ezTable($tabel5d,$kolom5d,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('ITEM'=>array('width'=>510))));
			$pdf->ezSetDy(-20,'');

			//Persetujuan
			$tanggal_approval = tanggal_indo($data_kasus[tgl_approval]);
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_kasus[dosen]'"));
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
			$pdf->addText(50,25,10,"$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Nilai Laporan Kasus                     <i>[hal $halaman dari $jumlah_halaman hal]</i>");
			$pdf->ezNewPage();

			$halaman++;
		}

		//Nilai Rata Kasus Ibu
		$kasus = mysqli_query($con,"SELECT * FROM `kdk_nilai_kasus` WHERE `nim`='$nim_mhsw_kdk' AND `kasus`='Ibu' AND `status_approval`='1'");
		$jumlah_kasus = mysqli_num_rows($kasus);
		$jum_nilai = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`aspek_1`), SUM(`aspek_2`), SUM(`aspek_3`), SUM(`aspek_4`), SUM(`aspek_5`), SUM(`nilai_rata`) FROM `kdk_nilai_kasus` WHERE `nim`='$nim_mhsw_kdk' AND `kasus`='Ibu' AND `status_approval`='1'"));
		if ($jumlah_kasus>0) $rata_aspek1_ibu =  number_format($jum_nilai[0]/$jumlah_kasus,2);
		else $rata_aspek1_ibu = 0.00;
		if ($jumlah_kasus>0) $rata_aspek2_ibu =  number_format($jum_nilai[1]/$jumlah_kasus,2);
		else $rata_aspek2_ibu = 0.00;
		if ($jumlah_kasus>0) $rata_aspek3_ibu =  number_format($jum_nilai[2]/$jumlah_kasus,2);
		else $rata_aspek3_ibu = 0.00;
		if ($jumlah_kasus>0) $rata_aspek4_ibu =  number_format($jum_nilai[3]/$jumlah_kasus,2);
		else $rata_aspek4_ibu = 0.00;
		if ($jumlah_kasus>0) $rata_aspek5_ibu =  number_format($jum_nilai[4]/$jumlah_kasus,2);
		else $rata_aspek5_ibu = 0.00;
		if ($jumlah_kasus>0) $total_kasus_ibu =  number_format($jum_nilai[5]/$jumlah_kasus,2);
		else $total_kasus_ibu = 0.00;

		//Nilai Rata Kasus Bayi/Balita
		$kasus = mysqli_query($con,"SELECT * FROM `kdk_nilai_kasus` WHERE `nim`='$nim_mhsw_kdk' AND `kasus`='Bayi/Balita' AND `status_approval`='1'");
		$jumlah_kasus = mysqli_num_rows($kasus);
		$jum_nilai = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`aspek_1`), SUM(`aspek_2`), SUM(`aspek_3`), SUM(`aspek_4`), SUM(`aspek_5`), SUM(`nilai_rata`) FROM `kdk_nilai_kasus` WHERE `nim`='$nim_mhsw_kdk' AND `kasus`='Bayi/Balita' AND `status_approval`='1'"));
		if ($jumlah_kasus>0) $rata_aspek1_bayi =  number_format($jum_nilai[0]/$jumlah_kasus,2);
		else $rata_aspek1_bayi = 0.00;
		if ($jumlah_kasus>0) $rata_aspek2_bayi =  number_format($jum_nilai[1]/$jumlah_kasus,2);
		else $rata_aspek2_bayi = 0.00;
		if ($jumlah_kasus>0) $rata_aspek3_bayi =  number_format($jum_nilai[2]/$jumlah_kasus,2);
		else $rata_aspek3_bayi = 0.00;
		if ($jumlah_kasus>0) $rata_aspek4_bayi =  number_format($jum_nilai[3]/$jumlah_kasus,2);
		else $rata_aspek4_bayi = 0.00;
		if ($jumlah_kasus>0) $rata_aspek5_bayi =  number_format($jum_nilai[4]/$jumlah_kasus,2);
		else $rata_aspek5_bayi = 0.00;
		if ($jumlah_kasus>0) $total_kasus_bayi =  number_format($jum_nilai[5]/$jumlah_kasus,2);
		else $total_kasus_bayi = 0.00;

		//Nilai Rata Kasus Remaja
		$kasus = mysqli_query($con,"SELECT * FROM `kdk_nilai_kasus` WHERE `nim`='$nim_mhsw_kdk' AND `kasus`='Remaja' AND `status_approval`='1'");
		$jumlah_kasus = mysqli_num_rows($kasus);
		$jum_nilai = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`aspek_1`), SUM(`aspek_2`), SUM(`aspek_3`), SUM(`aspek_4`), SUM(`aspek_5`), SUM(`nilai_rata`) FROM `kdk_nilai_kasus` WHERE `nim`='$nim_mhsw_kdk' AND `kasus`='Remaja' AND `status_approval`='1'"));
		if ($jumlah_kasus>0) $rata_aspek1_remaja =  number_format($jum_nilai[0]/$jumlah_kasus,2);
		else $rata_aspek1_remaja = 0.00;
		if ($jumlah_kasus>0) $rata_aspek2_remaja =  number_format($jum_nilai[1]/$jumlah_kasus,2);
		else $rata_aspek2_remaja = 0.00;
		if ($jumlah_kasus>0) $rata_aspek3_remaja =  number_format($jum_nilai[2]/$jumlah_kasus,2);
		else $rata_aspek3_remaja = 0.00;
		if ($jumlah_kasus>0) $rata_aspek4_remaja =  number_format($jum_nilai[3]/$jumlah_kasus,2);
		else $rata_aspek4_remaja = 0.00;
		if ($jumlah_kasus>0) $rata_aspek5_remaja =  number_format($jum_nilai[4]/$jumlah_kasus,2);
		else $rata_aspek5_remaja = 0.00;
		if ($jumlah_kasus>0) $total_kasus_remaja =  number_format($jum_nilai[5]/$jumlah_kasus,2);
		else $total_kasus_remaja = 0.00;

		//Nilai Rata Kasus Dewasa
		$kasus = mysqli_query($con,"SELECT * FROM `kdk_nilai_kasus` WHERE `nim`='$nim_mhsw_kdk' AND `kasus`='Dewasa' AND `status_approval`='1'");
		$jumlah_kasus = mysqli_num_rows($kasus);
		$jum_nilai = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`aspek_1`), SUM(`aspek_2`), SUM(`aspek_3`), SUM(`aspek_4`), SUM(`aspek_5`), SUM(`nilai_rata`) FROM `kdk_nilai_kasus` WHERE `nim`='$nim_mhsw_kdk' AND `kasus`='Dewasa' AND `status_approval`='1'"));
		if ($jumlah_kasus>0) $rata_aspek1_dewasa =  number_format($jum_nilai[0]/$jumlah_kasus,2);
		else $rata_aspek1_dewasa = 0.00;
		if ($jumlah_kasus>0) $rata_aspek2_dewasa =  number_format($jum_nilai[1]/$jumlah_kasus,2);
		else $rata_aspek2_dewasa = 0.00;
		if ($jumlah_kasus>0) $rata_aspek3_dewasa =  number_format($jum_nilai[2]/$jumlah_kasus,2);
		else $rata_aspek3_dewasa = 0.00;
		if ($jumlah_kasus>0) $rata_aspek4_dewasa =  number_format($jum_nilai[3]/$jumlah_kasus,2);
		else $rata_aspek4_dewasa = 0.00;
		if ($jumlah_kasus>0) $rata_aspek5_dewasa =  number_format($jum_nilai[4]/$jumlah_kasus,2);
		else $rata_aspek5_dewasa = 0.00;
		if ($jumlah_kasus>0) $total_kasus_dewasa =  number_format($jum_nilai[5]/$jumlah_kasus,2);
		else $total_kasus_dewasa = 0.00;

		//Nilai Rata Kasus Lansia
		$kasus = mysqli_query($con,"SELECT * FROM `kdk_nilai_kasus` WHERE `nim`='$nim_mhsw_kdk' AND `kasus`='Lansia' AND `status_approval`='1'");
		$jumlah_kasus = mysqli_num_rows($kasus);
		$jum_nilai = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`aspek_1`), SUM(`aspek_2`), SUM(`aspek_3`), SUM(`aspek_4`), SUM(`aspek_5`), SUM(`nilai_rata`) FROM `kdk_nilai_kasus` WHERE `nim`='$nim_mhsw_kdk' AND `kasus`='Lansia' AND `status_approval`='1'"));
		if ($jumlah_kasus>0) $rata_aspek1_lansia =  number_format($jum_nilai[0]/$jumlah_kasus,2);
		else $rata_aspek1_lansia = 0.00;
		if ($jumlah_kasus>0) $rata_aspek2_lansia =  number_format($jum_nilai[1]/$jumlah_kasus,2);
		else $rata_aspek2_lansia = 0.00;
		if ($jumlah_kasus>0) $rata_aspek3_lansia =  number_format($jum_nilai[2]/$jumlah_kasus,2);
		else $rata_aspek3_lansia = 0.00;
		if ($jumlah_kasus>0) $rata_aspek4_lansia =  number_format($jum_nilai[3]/$jumlah_kasus,2);
		else $rata_aspek4_lansia = 0.00;
		if ($jumlah_kasus>0) $rata_aspek5_lansia =  number_format($jum_nilai[4]/$jumlah_kasus,2);
		else $rata_aspek5_lansia = 0.00;
		if ($jumlah_kasus>0) $total_kasus_lansia =  number_format($jum_nilai[5]/$jumlah_kasus,2);
		else $total_kasus_lansia = 0.00;

		//Judul Rekap Total
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `stase_M121` WHERE `nim`='$nim_mhsw_kdk'"));
		$tanggal_mulai = tanggal_indo($data_stase[tgl_mulai]);
		$tanggal_selesai = tanggal_indo($data_stase[tgl_selesai]);
		$periode = $tanggal_mulai." s.d. ".$tanggal_selesai;

		$kolom7 = array ('item'=>"");
		$tabel7{1} = array ('item'=>"RATA-RATA NILAI LAPORAN KASUS KEPANITERAAN KEDOKTERAN KELUARGA");
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

		//Header tabel
		$kolom9 = array ('NO'=>'','ASPEK'=>'','BOBOT'=>'','NILAI1'=>'','NILAI2'=>'','NILAI3'=>'','NILAI4'=>'','NILAI5'=>'');
		$tabel9{1} = array ('NO'=>'<b>No</b>','ASPEK'=>'<b>Aspek Yang Dinilai</b>','BOBOT'=>'<b>Bobot</b>','NILAI1'=>'<b> Kasus  Ibu</b>','NILAI2'=>'<b>Kasus Bayi/Balita</b>','NILAI3'=>'<b>Kasus Remaja</b>','NILAI4'=>'<b>Kasus Dewasa</b>','NILAI5'=>'<b>Kasus Lansia</b>');
		$pdf->ezTable($tabel9,$kolom9,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>8,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>10, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
		'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'ASPEK'=>array('justification'=>'center'),'BOBOT'=>array('width'=>55,'justification'=>'center'),
		'NILAI1'=>array('width'=>50,'justification'=>'center'),'NILAI2'=>array('width'=>52,'justification'=>'center'),
		'NILAI3'=>array('width'=>50,'justification'=>'center'),'NILAI4'=>array('width'=>50,'justification'=>'center'),
		'NILAI5'=>array('width'=>50,'justification'=>'center'))));

		//Nilai Aspek 1 - 5
		$kolom10 = array ('NO'=>'','ASPEK'=>'','BOBOT'=>'','NILAI1'=>'','NILAI2'=>'','NILAI3'=>'','NILAI4'=>'','NILAI5'=>'');
		$tabel10{1} = array ('NO'=>'1','ASPEK'=>'Kejelasan penyajian/presentasi','BOBOT'=>'20%','NILAI1'=>$rata_aspek1_ibu,'NILAI2'=>$rata_aspek1_bayi,'NILAI3'=>$rata_aspek1_remaja,'NILAI4'=>$rata_aspek1_dewasa,'NILAI5'=>$rata_aspek1_lansia);
		$tabel10{2} = array ('NO'=>'2','ASPEK'=>'Kualitas kemampuan identifikasi masalah pasien dan keluarga (dilihat dari kejelasan isi dan penulisan)','BOBOT'=>'20%','NILAI1'=>$rata_aspek2_ibu,'NILAI2'=>$rata_aspek2_bayi,'NILAI3'=>$rata_aspek2_remaja,'NILAI4'=>$rata_aspek2_dewasa,'NILAI5'=>$rata_aspek2_lansia);
		$tabel10{3} = array ('NO'=>'3','ASPEK'=>'Kualitas kemampuan diagnosis holistic (dilihat dari kejelasan isi dan penulisan)','BOBOT'=>'20%','NILAI1'=>$rata_aspek3_ibu,'NILAI2'=>$rata_aspek3_bayi,'NILAI3'=>$rata_aspek3_remaja,'NILAI4'=>$rata_aspek3_dewasa,'NILAI5'=>$rata_aspek3_lansia);
		$tabel10{4} = array ('NO'=>'4','ASPEK'=>'Kualitas kemampuan penatalaksanaan kasus (dilihat dari kejelasan isi dan penulisan)','BOBOT'=>'20%','NILAI1'=>$rata_aspek4_ibu,'NILAI2'=>$rata_aspek4_bayi,'NILAI3'=>$rata_aspek4_remaja,'NILAI4'=>$rata_aspek4_dewasa,'NILAI5'=>$rata_aspek4_lansia);
		$tabel10{5} = array ('NO'=>'5','ASPEK'=>'Kelancaran diskusi','BOBOT'=>'20%','NILAI1'=>$rata_aspek5_ibu,'NILAI2'=>$rata_aspek5_bayi,'NILAI3'=>$rata_aspek5_remaja,'NILAI4'=>$rata_aspek5_dewasa,'NILAI5'=>$rata_aspek5_lansia);
		$pdf->ezTable($tabel10,$kolom10,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>8,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>10, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
		'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'BOBOT'=>array('width'=>55,'justification'=>'center'),
		'NILAI1'=>array('width'=>50,'justification'=>'center'),'NILAI2'=>array('width'=>52,'justification'=>'center'),
		'NILAI3'=>array('width'=>50,'justification'=>'center'),'NILAI4'=>array('width'=>50,'justification'=>'center'),
		'NILAI5'=>array('width'=>50,'justification'=>'center'))));

		//Nilai Total
		$kolom11 = array ('TOTAL'=>'','NILAI1'=>'','NILAI2'=>'','NILAI3'=>'','NILAI4'=>'','NILAI5'=>'');
		$tabel11{1} = array ('TOTAL'=>'Rata-Rata Nilai:','NILAI1'=>"<b>$total_kasus_ibu</b>",'NILAI2'=>"<b>$total_kasus_bayi</b>",'NILAI3'=>"<b>$total_kasus_remaja</b>",'NILAI4'=>"<b>$total_kasus_dewasa</b>",'NILAI5'=>"<b>$total_kasus_lansia</b>");
		$pdf->ezTable($tabel11,$kolom11,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>8,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>10, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
		'cols'=>array('TOTAL'=>array('justification'=>'right'),
		'NILAI1'=>array('width'=>50,'justification'=>'center'),'NILAI2'=>array('width'=>52,'justification'=>'center'),
		'NILAI3'=>array('width'=>50,'justification'=>'center'),'NILAI4'=>array('width'=>50,'justification'=>'center'),
		'NILAI5'=>array('width'=>50,'justification'=>'center'))));
		$pdf->ezSetDy(-20,'');

		//Nilai Total Rata-rata
		$rata_total_kasus=($total_kasus_ibu+$total_kasus_bayi+$total_kasus_remaja+$total_kasus_dewasa+$total_kasus_lansia)/5;
		$rata_total_kasus = number_format($rata_total_kasus,2);
		$kolom12 = array ('TOTAL'=>'','NILAI'=>'');
		$tabel12{1} = array ('TOTAL'=>'Rata-Rata Nilai Laporan Kasus','NILAI'=>": <b>$rata_total_kasus</b>");
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
		$pdf->addText(50,25,10,"$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Nilai Laporan Kasus                     <i>[hal $halaman dari $jumlah_halaman hal]</i>");
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
