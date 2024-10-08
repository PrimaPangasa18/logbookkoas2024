<?php

	include "../config.php";
	include "../fungsi.php";
	include "../class.ezpdf.php";

	error_reporting("E_ALL ^ E_NOTICE");

	    $nim_mhsw_psikiatri = $_GET[nim];

		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw_psikiatri'"));
		$pdf = new Cezpdf('A4');
		$pdf->ezSetMargins(30,40,50,50);
		$pdf->selectFont('../fonts/Helvetica.afm');

		//-------------------------------
		//Rekap Nilai Penilaian Journal Reading
		//-------------------------------
		$data_nilai_jurnal = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `psikiatri_nilai_jurnal` WHERE `nim`='$nim_mhsw_psikiatri' AND `status_approval`='1'"));
		$nilai_jurnal = number_format($data_nilai_jurnal[nilai_rata],2);

		//-------------------------------
		//Rekap Nilai Penilaian CBD
		//-------------------------------
		$data_nilai_cbd = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `psikiatri_nilai_cbd` WHERE `nim`='$nim_mhsw_psikiatri' AND `status_approval`='1'"));
		$nilai_cbd = number_format($data_nilai_cbd[nilai_rata],2);

		//-------------------------------
		//Rekap Nilai Penilaian Ujian MINI-CEX
		//-------------------------------
		$data_nilai_minicex = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `psikiatri_nilai_minicex` WHERE `nim`='$nim_mhsw_psikiatri' AND `status_approval`='1'"));
		$nilai_minicex = number_format($data_nilai_minicex[nilai_rata],2);

		//-------------------------------
		//Rekap Nilai Ujian OSCE
		//-------------------------------
		$data_nilai_osce = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `psikiatri_nilai_osce` WHERE `nim`='$nim_mhsw_psikiatri' AND `status_approval`='1'"));
		$nilai_osce = number_format($data_nilai_osce[nilai_rata],2);

		//-------------------------------
		//Rekap Nilai Test
		//-------------------------------
		$nilai_pretest = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `psikiatri_nilai_test` WHERE `nim`='$nim_mhsw_psikiatri' AND `status_approval`='1' AND `jenis_test`='1'"));
		$nilai_posttest = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `psikiatri_nilai_test` WHERE `nim`='$nim_mhsw_psikiatri' AND `status_approval`='1' AND `jenis_test`='2'"));
		$nilai_test = ($nilai_pretest[0]+$nilai_posttest[0])/2;
		$nilai_test = number_format($nilai_test,2);


		//Judul Rekap Total
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `stase_M093` WHERE `nim`='$nim_mhsw_psikiatri'"));
		$tanggal_mulai = tanggal_indo($data_stase[tgl_mulai]);
		$tanggal_selesai = tanggal_indo($data_stase[tgl_selesai]);
		$periode = $tanggal_mulai." s.d. ".$tanggal_selesai;

		$kolom1 = array ('item'=>"");
		$tabel1[1] = array ('item'=>"REKAP NILAI KEPANITERAAN (STASE) ILMU KESEHATAN JIWA");
		$tabel1[2] = array ('item'=>"LOGBOOK KOAS PENDIDIKAN PROFESI DOKTER");
		$tabel1[3] = array ('item'=>"FAKULTAS KEDOKTERAN - UNIVERSITAS DIPONEGORO");
		$pdf->ezTable($tabel1,$kolom1,"",
		array('maxWidth'=>540,'width'=>520,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 0 ,
		'cols'=>array('item'=>array('justification'=>'left'))));

		//Data Mahasiswa
		$pdf->ezSetDy(-20,'');
		$kolom2 = array ('item'=>"",'isi'=>"");
		$tabel2[1] = array ('item'=>"Nama Mahasiswa", 'isi'=>": "."$data_mhsw[nama]");
		$tabel2[2] = array ('item'=>"NIM", 'isi'=>": "."$data_mhsw[nim]");
		$tabel2[3] = array ('item'=>"Kepaniteraan (Stase)", 'isi'=>": "."Ilmu Kesehatan Jiwa");
		$tabel2[4] = array ('item'=>"Periode", 'isi'=>": "."$periode");
		$pdf->ezTable($tabel2,$kolom2,"",
		array('maxWidth'=>540,'width'=>520,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 0 ,
		'cols'=>array('item'=>array('justification'=>'left','width'=>110))));
		$pdf->ezSetDy(-20,'');

		//----------------
		//Header Tabel Rekap
		$kolom3 = array ('NO'=>'','ITEM'=>'','BOBOT'=>'','NILAI'=>'');
		$tabel3[1] = array ('NO'=>'<b>No</b>','ITEM'=>'<b>Item Penilaian</b>','BOBOT'=>'<b>Bobot</b>','NILAI'=>'<b>Nilai (0-100)</b>');
		$pdf->ezTable($tabel3,$kolom3,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
		'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'ITEM'=>array('justification'=>'center'),'BOBOT'=>array('width'=>100,'justification'=>'center'),'NILAI'=>array('width'=>100,'justification'=>'center'))));

		//Isi Tabel Rekap
		$kolom4 = array ('NO'=>'','ITEM'=>'','BOBOT'=>'','NILAI'=>'');
		$tabel4[1] = array ('NO'=>'1','ITEM'=>'Rata Nilai Journal Reading','BOBOT'=>'25%','NILAI'=>$nilai_jurnal);
		$tabel4[2] = array ('NO'=>'2','ITEM'=>'Rata Nilai CBD','BOBOT'=>'12.5%','NILAI'=>$nilai_cbd);
		$tabel4[3] = array ('NO'=>'3','ITEM'=>'Rata Nilai Ujian MINI-CEX','BOBOT'=>'12.5%','NILAI'=>$nilai_minicex);
		$tabel4[4] = array ('NO'=>'4','ITEM'=>'Rata Nilai Ujian OSCE','BOBOT'=>'25%','NILAI'=>$nilai_osce);
		$tabel4[5] = array ('NO'=>'4','ITEM'=>"Rata Nilai Ujian / Test:\r\n  Nilai Pre-Test: $nilai_pretest[0]\r\n  Nilai Post-Test: $nilai_posttest[0]",'BOBOT'=>'25%','NILAI'=>$nilai_test);
		$pdf->ezTable($tabel4,$kolom4,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
		'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'BOBOT'=>array('width'=>100,'justification'=>'center'),'NILAI'=>array('width'=>100,'justification'=>'center'))));

		//Total Nilai
		$nilai_total = number_format(0.25*$nilai_jurnal+0.125*$nilai_cbd+0.125*$nilai_minicex+0.25*$nilai_osce+0.25*$nilai_test,2);
		$kolom5 = array ('TOTAL'=>"",'NILAI'=>"");
		$tabel5[1] = array ('TOTAL'=>"Nilai Total:", 'NILAI'=>$nilai_total);
		$pdf->ezTable($tabel5,$kolom5,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
		'cols'=>array('TOTAL'=>array('justification'=>'right'),'NILAI'=>array('width'=>100,'justification'=>'center'))));
		$pdf->ezSetDy(-20,'');

		//Nilai Total Rata-rata
		if ($nilai_total<=100 AND $nilai_total>=80) $grade = "A";
		if ($nilai_total<80 AND $nilai_total>=70) $grade = "B";
		if ($nilai_total<70 AND $nilai_total>=60) $grade = "C";
		if ($nilai_total<60 AND $nilai_total>=50) $grade = "D";
		if ($nilai_total<50) $grade = "E";
		$kolom6 = array ('TOTAL'=>'','NILAI'=>'');
		$tabel6[1] = array ('TOTAL'=>'Nilai Total Kepaniteraan (Stase) Ilmu Kesehatan Jiwa','NILAI'=>": ".$nilai_total);
		$tabel6[2] = array ('TOTAL'=>'Ekivalensi Nilai Huruf','NILAI'=>": ".$grade);
		$pdf->ezTable($tabel6,$kolom6,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
		'cols'=>array('TOTAL'=>array('width'=>265))));
		$pdf->ezSetDy(-20,'');

		//Persetujuan Kordik
		$nip_kordik = mysqli_fetch_array(mysqli_query($con,"SELECT `username` FROM `admin` WHERE `stase`='K093'"));
		$data_kordik = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$nip_kordik[username]'"));
		$kordik = $data_kordik[nama].", ".$data_kordik[gelar];
		$kolom6a = array ('ITEM'=>'');
		$tabel6a[1] = array ('ITEM'=>'Status: <b>DISETUJUI</b>');
		$tabel6a[2] = array ('ITEM'=>'pada tanggal _____________________');
		$tabel6a[3] = array ('ITEM'=>'Kordik Kepaniteraan (Stase) Ilmu Kesehatan Jiwa');
		$tabel6a[4] = array ('ITEM'=>'');
		$tabel6a[5] = array ('ITEM'=>'');
		$tabel6a[6] = array ('ITEM'=>$kordik);
		$tabel6a[7] = array ('ITEM'=>'NIP: '.$data_kordik[nip]);
		$pdf->ezTable($tabel6a,$kolom6a,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 1 ,'showBgCol'=> 0,
		'cols'=>array('ITEM'=>array('justification'=>'right'))));

		$pdf->ezSetDy(-10,'');
		$pdf->addText(50,25,10,"$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Rekap Nilai Kepaniteraan (Stase) Ilmu Kesehatan Jiwa   <i>[hal 1]</i>");
		$pdf->ezStream(array("Content-Disposition"=>"Nilai Bagian Ilmu Kesehatan Jiwa $data_mhsw[nim].pdf"));
?>
