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
		if ($_COOKIE[level]=='5') $nim_mhsw_ikfr = $_COOKIE[user];
		if ($_COOKIE[level]=='1' OR $_COOKIE[level]=='2' OR $_COOKIE[level]=='3') $nim_mhsw_ikfr = $_GET[nim];

		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw_ikfr'"));

		$pdf = new Cezpdf('A4');
		$pdf->ezSetMargins(30,40,50,50);
		$pdf->selectFont('../fonts/Helvetica.afm');

		$daftar_minicex = mysqli_query($con,"SELECT * FROM `ikfr_nilai_minicex` WHERE `nim`='$nim_mhsw_ikfr' AND `status_approval`='1' ORDER BY `tgl_approval` ASC");
		$jumlah_halaman = mysqli_num_rows($daftar_minicex);
		$halaman = 1;
		while ($data_minicex = mysqli_fetch_array($daftar_minicex))
		{
			//Judul
			$kolom1 = array ('item'=>"");
			$tabel1{1} = array ('item'=>"NILAI UJIAN MINI-CEX KEPANITERAAN (STASE) IKFR");
			$tabel1{2} = array ('item'=>"LOGBOOK KOAS PENDIDIKAN PROFESI DOKTER");
			$tabel1{3} = array ('item'=>"FAKULTAS KEDOKTERAN - UNIVERSITAS DIPONEGORO");
			$pdf->ezTable($tabel1,$kolom1,"",
			array('maxWidth'=>540,'width'=>520,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 0 ,
			'cols'=>array('item'=>array('justification'=>'left'))));

			//Data Mahasiswa
			$pdf->ezSetDy(-20,'');
			$tanggal_ujian = tanggal_indo($data_minicex[tgl_ujian]);
			$kolom2 = array ('item'=>"",'isi'=>"");
			$tabel2{1} = array ('item'=>"Nama Mahasiswa", 'isi'=>": "."$data_mhsw[nama]");
			$tabel2{2} = array ('item'=>"NIM", 'isi'=>": "."$data_mhsw[nim]");
			$tabel2{3} = array ('item'=>"Kepaniteraan (Stase)", 'isi'=>": "."IKFR");
			$tabel2{4} = array ('item'=>"Tanggal Ujian", 'isi'=>": "."$tanggal_ujian");
			$tabel2{5} = array ('item'=>"Situasi Ruangan", 'isi'=>": "."$data_minicex[situasi_ruangan]");
			$tabel2{6} = array ('item'=>"Umur Pasien", 'isi'=>": "."$data_minicex[umur_pasien]"." tahun");
			$tabel2{7} = array ('item'=>"Jenis Kelamin Pasien", 'isi'=>": "."$data_minicex[jk_pasien]");
			$tabel2{8} = array ('item'=>"Problem/Diagnosis Pasien", 'isi'=>": "."$data_minicex[diagnosis]");
			$tabel2{9} = array ('item'=>"Tingkat Kerumitan", 'isi'=>": "."$data_minicex[tingkat_kerumitan]");

			$pdf->ezTable($tabel2,$kolom2,"",
			array('maxWidth'=>540,'width'=>520,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 0 ,
			'cols'=>array('item'=>array('justification'=>'left','width'=>150))));
			$pdf->ezSetDy(-20,'');

			//Header tabel
			$kolom3 = array ('NO'=>'','ASPEK'=>'','BOBOT'=>'','NILAI'=>'');
			$tabel3{1} = array ('NO'=>'<b>No</b>','ASPEK'=>'<b>Komponen Penilaian</b>','BOBOT'=>'<b>Bobot</b>','NILAI'=>'<b>Nilai (0-100)</b>');
			$pdf->ezTable($tabel3,$kolom3,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'ASPEK'=>array('justification'=>'center'),'BOBOT'=>array('width'=>100,'justification'=>'center'),'NILAI'=>array('width'=>100,'justification'=>'center'))));

			//Nilai Aspek 1 - 5
			$kolom4 = array ('NO'=>'','ASPEK'=>'','BOBOT'=>'','NILAI'=>'');
			$tabel4{1} = array ('NO'=>'1','ASPEK'=>'Pemeriksaan Sensibilitas','BOBOT'=>'20%','NILAI'=>$data_minicex[aspek_1]);
			$tabel4{2} = array ('NO'=>'2','ASPEK'=>'Assesmen Kekuatan Otot (MMT)','BOBOT'=>'20%','NILAI'=>$data_minicex[aspek_2]);
			$tabel4{3} = array ('NO'=>'3','ASPEK'=>'Pemeriksaan Fleksibiltas','BOBOT'=>'20%','NILAI'=>$data_minicex[aspek_3]);
			$tabel4{4} = array ('NO'=>'4','ASPEK'=>'Assesmen Lingkup Gerak Sendi','BOBOT'=>'20%','NILAI'=>$data_minicex[aspek_4]);
			$tabel4{5} = array ('NO'=>'5','ASPEK'=>'Assesmen Postur dan Ambulasi','BOBOT'=>'20%','NILAI'=>$data_minicex[aspek_5]);
			$pdf->ezTable($tabel4,$kolom4,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'BOBOT'=>array('width'=>100,'justification'=>'center'),'NILAI'=>array('width'=>100,'justification'=>'center'))));

			//Nilai Total
			$kolom5 = array ('TOTAL'=>'','NILAI'=>'');
			$tabel5{1} = array ('TOTAL'=>'Rata-Rata Nilai (Jumlah Bobot x Nilai):','NILAI'=>"<b>$data_minicex[nilai_rata]</b>");
			$pdf->ezTable($tabel5,$kolom5,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('TOTAL'=>array('justification'=>'right'),'NILAI'=>array('width'=>100,'justification'=>'center'))));
			$pdf->ezSetDy(-20,'');

			//Umpan Balik
			$kolom5a = array ('ITEM'=>'');
			$tabel5a{1} = array ('ITEM'=>"<b>UMPAN BALIK TERHADAP MINI-CEX</b>");
			$pdf->ezTable($tabel5a,$kolom5a,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('ITEM'=>array('justification'=>'center'))));
			$kolom5b = array ('ITEM1'=>'','ITEM2'=>'');
			$tabel5b{1} = array ('ITEM1'=>"<b>Sudah Bagus</b>",'ITEM2'=>"<b>Perlu Perbaikan</b>");
			$pdf->ezTable($tabel5b,$kolom5b,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('ITEM1'=>array('width'=>255,'justification'=>'center'),'ITEM2'=>array('width'=>255,'justification'=>'center'))));
			$kolom5c = array ('ITEM1'=>'','ITEM2'=>'');
			$tabel5c{1} = array ('ITEM1'=>"<i>$data_minicex[ub_bagus]</i>",'ITEM2'=>"<i>$data_minicex[ub_perbaikan]</i>");
			$pdf->ezTable($tabel5c,$kolom5c,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('ITEM1'=>array('width'=>255),'ITEM2'=>array('width'=>255))));
			//Saran
			$kolom5d = array ('ITEM'=>'');
			$tabel5d{1} = array ('ITEM'=>"<b>Saran:</b>\r\n\r\n<i>$data_minicex[saran]</i>");
			$pdf->ezTable($tabel5d,$kolom5d,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('ITEM'=>array('width'=>510))));
			//Catatan
			$kolom5e = array ('ITEM'=>'');
			$tabel5e{1} = array ('ITEM'=>"<b>Catatan:</b>\r\n\r\nWaktu Penilaian Kasus MINI-CEX:\r\n    Observasi:<i>$data_minicex[waktu_observasi] menit</i>\r\n    Memberikan umpan balik:<i>$data_minicex[waktu_ub] menit</i>");
			$pdf->ezTable($tabel5e,$kolom5e,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('ITEM'=>array('width'=>510))));
			$pdf->ezSetDy(-20,'');

			//Persetujuan
			$tanggal_approval = tanggal_indo($data_minicex[tgl_approval]);
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_minicex[dosen]'"));
			$dosen = $data_dosen[nama].", ".$data_dosen[gelar];
			$kolom6 = array ('ITEM'=>'');
			$tabel6{1} = array ('ITEM'=>'Status: <b>DISETUJUI</b>');
			$tabel6{2} = array ('ITEM'=>'pada tanggal '.$tanggal_approval);
			$tabel6{3} = array ('ITEM'=>'Dosen Penilai');
			$tabel6{4} = array ('ITEM'=>'');
			$tabel6{5} = array ('ITEM'=>$dosen);
			$tabel6{6} = array ('ITEM'=>'NIP: '.$data_dosen[nip]);
			$pdf->ezTable($tabel6,$kolom6,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 1 ,'showBgCol'=> 0,
			'cols'=>array('ITEM'=>array('justification'=>'right'))));
			$pdf->ezSetDy(-10,'');
			$pdf->addText(50,25,10,"$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Nilai Ujian MINI-CEX                <i>[hal $halaman dari $jumlah_halaman hal]</i>");
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
