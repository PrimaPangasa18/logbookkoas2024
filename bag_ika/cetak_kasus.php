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
		if ($_COOKIE[level]=='5') $nim_mhsw_ika = $_COOKIE[user];
		if ($_COOKIE[level]=='1' OR $_COOKIE[level]=='2' OR $_COOKIE[level]=='3') $nim_mhsw_ika = $_GET[nim];

		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw_ika'"));
		$pdf = new Cezpdf('A4');
		$pdf->ezSetMargins(30,40,50,50);
		$pdf->selectFont('../fonts/Helvetica.afm');

		$daftar_kasus = mysqli_query($con,"SELECT * FROM `ika_nilai_kasus` WHERE `nim`='$nim_mhsw_ika' AND `status_approval`='1' ORDER BY `tgl_approval` ASC");
		$jumlah_halaman = mysqli_num_rows($daftar_kasus);
		$halaman = 1;
		while ($data_kasus = mysqli_fetch_array($daftar_kasus))
		{
			//Judul
			$kolom1 = array ('item'=>"");
			$tabel1{1} = array ('item'=>"NILAI PENYAJIAN KASUS BESAR");
			$tabel1{2} = array ('item'=>"KEPANITERAAN (STASE) ILMU KESEHATAN ANAK");
			$tabel1{3} = array ('item'=>"LOGBOOK KOAS PENDIDIKAN PROFESI DOKTER");
			$tabel1{4} = array ('item'=>"FAKULTAS KEDOKTERAN - UNIVERSITAS DIPONEGORO");
			$pdf->ezTable($tabel1,$kolom1,"",
			array('maxWidth'=>540,'width'=>520,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 0 ,
			'cols'=>array('item'=>array('justification'=>'left'))));

			//Data Mahasiswa
			$pdf->ezSetDy(-20,'');
			$tanggal_awal = tanggal_indo($data_kasus[tgl_awal]);
			$tanggal_akhir = tanggal_indo($data_kasus[tgl_akhir]);
			$periode_stase = $tanggal_awal." s.d. ".$tanggal_akhir;
			$tanggal_penyajian = tanggal_indo($data_kasus[tgl_penyajian]);

			$kolom2 = array ('item'=>"",'isi'=>"");
			$tabel2{1} = array ('item'=>"Nama Mahasiswa", 'isi'=>": "."$data_mhsw[nama]");
			$tabel2{2} = array ('item'=>"NIM", 'isi'=>": "."$data_mhsw[nim]");
			$tabel2{3} = array ('item'=>"Kepaniteraan (Stase)", 'isi'=>": "."Ilmu Kesehatan Anak");
			$tabel2{4} = array ('item'=>"Periode Stase", 'isi'=>": "."$periode_stase");
			$tabel2{5} = array ('item'=>"Judul Kasus", 'isi'=>": "."$data_kasus[kasus]");
			$tabel2{6} = array ('item'=>"Tanggal Penyajian", 'isi'=>": "."$tanggal_penyajian");
			$pdf->ezTable($tabel2,$kolom2,"",
			array('maxWidth'=>540,'width'=>520,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 0 ,
			'cols'=>array('item'=>array('justification'=>'left','width'=>110))));
			$pdf->ezSetDy(-20,'');

			//Header tabel
			$kolom3 = array ('NO'=>'','ASPEK'=>'','BOBOT'=>'','NILAI'=>'');
			$tabel3{1} = array ('NO'=>'<b>No</b>','ASPEK'=>'<b>Komponen Penilaian</b>','BOBOT'=>'<b>Bobot</b>','NILAI'=>'<b>Nilai (0-100)</b>');
			$pdf->ezTable($tabel3,$kolom3,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'ASPEK'=>array('justification'=>'center'),'BOBOT'=>array('width'=>100,'justification'=>'center'),'NILAI'=>array('width'=>100,'justification'=>'center'))));

			//Nilai Penyajian: Aspek 1-2
			$kolom4a = array ('ASPEK'=>'');
			$tabel4a{1} = array ('ASPEK'=>"<b>Aspek Penyajian:</b>");
			$pdf->ezTable($tabel4a,$kolom4a,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('ASPEK'=>array('width'=>510))));
			$kolom4b = array ('NO'=>'','ASPEK'=>'','BOBOT'=>'','NILAI'=>'');
			$tabel4b{1} = array ('NO'=>'1','ASPEK'=>'Slide','BOBOT'=>'5%','NILAI'=>$data_kasus[aspek_1]);
			$tabel4b{2} = array ('NO'=>'2','ASPEK'=>'Sikap dan suara','BOBOT'=>'10%','NILAI'=>$data_kasus[aspek_2]);
			$pdf->ezTable($tabel4b,$kolom4b,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'BOBOT'=>array('width'=>100,'justification'=>'center'),'NILAI'=>array('width'=>100,'justification'=>'center'))));

			//Nilai Aspek Naskah: Aspek 3-6
			$kolom4c = array ('ASPEK'=>'');
			$tabel4c{1} = array ('ASPEK'=>"<b>Aspek Naskah:</b>");
			$pdf->ezTable($tabel4c,$kolom4c,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('ASPEK'=>array('width'=>510))));
			$kolom4d = array ('NO'=>'','ASPEK'=>'','BOBOT'=>'','NILAI'=>'');
			$tabel4d{1} = array ('NO'=>'3','ASPEK'=>'Kesesuaian format penulisan','BOBOT'=>'2.5%','NILAI'=>$data_kasus[aspek_3]);
			$tabel4d{2} = array ('NO'=>'4','ASPEK'=>'Ringkasan kasus','BOBOT'=>'5%','NILAI'=>$data_kasus[aspek_4]);
			$tabel4d{3} = array ('NO'=>'5','ASPEK'=>'Pembahasan dan aplikasi EBM (PICO-VIA)','BOBOT'=>'5%','NILAI'=>$data_kasus[aspek_5]);
			$tabel4d{4} = array ('NO'=>'6','ASPEK'=>'Kepustakaan','BOBOT'=>'2.5%','NILAI'=>$data_kasus[aspek_6]);
			$pdf->ezTable($tabel4d,$kolom4d,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'BOBOT'=>array('width'=>100,'justification'=>'center'),'NILAI'=>array('width'=>100,'justification'=>'center'))));

			//Nilai Aspek Diskusi: Aspek 7-14
			$kolom4e = array ('ASPEK'=>'');
			$tabel4e{1} = array ('ASPEK'=>"<b>Aspek Diskusi:</b>");
			$pdf->ezTable($tabel4e,$kolom4e,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('ASPEK'=>array('width'=>510))));
			$kolom4f = array ('NO'=>'','ASPEK'=>'','BOBOT'=>'','NILAI'=>'');
			$tabel4f{1} = array ('NO'=>'7','ASPEK'=>"Anamnesis (<i>sacred seven, fundamental four</i>)",'BOBOT'=>'10%','NILAI'=>$data_kasus[aspek_7]);
			$tabel4f{2} = array ('NO'=>'8','ASPEK'=>"Pemeriksaan Fisik (<i>status lokalis dan generalis</i>)",'BOBOT'=>'10%','NILAI'=>$data_kasus[aspek_8]);
			$tabel4f{3} = array ('NO'=>'9','ASPEK'=>"Pemeriksaan Penunjang (<i>jenis dan interpretasi hasil</i>)",'BOBOT'=>'10%','NILAI'=>$data_kasus[aspek_9]);
			$tabel4f{4} = array ('NO'=>'10','ASPEK'=>"Diagnosis Banding/Diagnosis Kerja",'BOBOT'=>'10%','NILAI'=>$data_kasus[aspek_10]);
			$tabel4f{5} = array ('NO'=>'11','ASPEK'=>"Tatalaksana",'BOBOT'=>'10%','NILAI'=>$data_kasus[aspek_11]);
			$tabel4f{6} = array ('NO'=>'12','ASPEK'=>"Komplikasi, Pencegahan, Prognosis",'BOBOT'=>'5%','NILAI'=>$data_kasus[aspek_12]);
			$tabel4f{7} = array ('NO'=>'13','ASPEK'=>"Tumbuh kembang (<i>nutrisi, imunisasi, perkembangan</i>)",'BOBOT'=>'10%','NILAI'=>$data_kasus[aspek_13]);
			$tabel4f{8} = array ('NO'=>'14','ASPEK'=>"Diskusi lain",'BOBOT'=>'5%','NILAI'=>$data_kasus[aspek_14]);
			$pdf->ezTable($tabel4f,$kolom4f,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'BOBOT'=>array('width'=>100,'justification'=>'center'),'NILAI'=>array('width'=>100,'justification'=>'center'))));

			//Nilai Total
			$kolom5 = array ('TOTAL'=>'','NILAI'=>'');
			$tabel5{1} = array ('TOTAL'=>'Rata-Rata Nilai (Jumlah Bobot x Nilai)','NILAI'=>"<b>$data_kasus[nilai_rata]</b>");
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
			$tabel6{3} = array ('ITEM'=>'Dosen Penilai');
			$tabel6{4} = array ('ITEM'=>'');
			$tabel6{5} = array ('ITEM'=>$dosen);
			$tabel6{6} = array ('ITEM'=>'NIP: '.$data_dosen[nip]);
			$pdf->ezTable($tabel6,$kolom6,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 1 ,'showBgCol'=> 0,
			'cols'=>array('ITEM'=>array('justification'=>'right'))));
			$pdf->ezSetDy(-10,'');
			$pdf->addText(50,25,10,"$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Nilai Penyajian Kasus Besar        <i>[hal $halaman dari $jumlah_halaman hal]</i>");
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
