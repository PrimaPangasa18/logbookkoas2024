<?php

	include "../config.php";
	include "../fungsi.php";
	include "../class.ezpdf.php";

	error_reporting("E_ALL ^ E_NOTICE");

	    $nim_mhsw_obsgyn = $_GET[nim];

		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw_obsgyn'"));
		$pdf = new Cezpdf('A4');
		$pdf->ezSetMargins(30,40,50,50);
		$pdf->selectFont('../fonts/Helvetica.afm');

		//---------------------
		//Rekap Nilai Mini-Cex
		//---------------------
		//Nilai Rata Mini-Cex
		$data_minicex = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `obsgyn_nilai_minicex` WHERE `nim`='$nim_mhsw_obsgyn' AND `status_approval`='1'"));
		if (!empty($data_minicex)) $nilai_minicex = $data_minicex[nilai_rata];
		else $nilai_minicex = 0;
		$nilai_minicex = number_format($nilai_minicex,2);
		//---------------------

		//-------------------------------
		//Rekap Nilai CBD
		//-------------------------------
		$daftar_cbd = mysqli_query($con,"SELECT `id` FROM `obsgyn_nilai_cbd` WHERE `nim`='$nim_mhsw_obsgyn' AND `status_approval`='1'");
		$jumlah_cbd = mysqli_num_rows($daftar_cbd);
		$jum_nilai = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`nilai_rata`) FROM `obsgyn_nilai_cbd` WHERE `nim`='$nim_mhsw_obsgyn' AND `status_approval`='1'"));
		if ($jumlah_cbd>0) $rata_cbd =  $jum_nilai[0]/4;
		else $rata_cbd = 0;
		$rata_cbd = number_format($rata_cbd,2);

		//---------------------
		//Rekap Nilai Journal Reading
		//---------------------
		//Nilai Journal Reading
		$data_jurnal = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `obsgyn_nilai_jurnal` WHERE `nim`='$nim_mhsw_obsgyn' AND `status_approval`='1'"));
		if (!empty($data_jurnal)) $nilai_jurnal = $data_jurnal[nilai_rata];
		else $nilai_jurnal = 0;
		$nilai_jurnal = number_format($nilai_jurnal,2);

		//-------------------------------
		//Rekap Nilai Test
		//-------------------------------
		$mcq = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `obsgyn_nilai_test` WHERE `nim`='$nim_mhsw_obsgyn' AND `jenis_test`='6' AND `status_approval`='1'"));
		$nilai_mcq = number_format($mcq[0],2);
		$dops_osce = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `obsgyn_nilai_test` WHERE `nim`='$nim_mhsw_obsgyn' AND `jenis_test`='13' AND `status_approval`='1'"));
		$nilai_dops_osce = number_format($dops_osce[0],2);
		$minipat = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `obsgyn_nilai_test` WHERE `nim`='$nim_mhsw_obsgyn' AND `jenis_test`='14' AND `status_approval`='1'"));
		$nilai_minipat = number_format($minipat[0],2);

		//Judul Rekap Total
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `stase_M111` WHERE `nim`='$nim_mhsw_obsgyn'"));
		$tanggal_mulai = tanggal_indo($data_stase[tgl_mulai]);
		$tanggal_selesai = tanggal_indo($data_stase[tgl_selesai]);
		$periode = $tanggal_mulai." s.d. ".$tanggal_selesai;

		$kolom1 = array ('item'=>"");
		$tabel1[1] = array ('item'=>"REKAP NILAI KEPANITERAAN (STASE) ILMU KEBIDANAN DAN PENYAKIT KANDUNGAN");
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
		$tabel2[3] = array ('item'=>"Kepaniteraan (Stase)", 'isi'=>": "."Ilmu Kebidanan dan Penyakit Kandungan");
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
//---------------------------------
		//Isi Tabel Rekap
		$kolom4 = array ('NO'=>'','ITEM'=>'','BOBOT'=>'','NILAI'=>'');
		$tabel4[1] = array ('NO'=>'1','ITEM'=>"Nilai MINI-CEX",'BOBOT'=>'20%','NILAI'=>$nilai_minicex);
		$tabel4[2] = array ('NO'=>'2','ITEM'=>"Nilai Rata-Rata CBD\r\n<i>(Jumlah semua nilai CBD dibagi 4)</i>",'BOBOT'=>'15%','NILAI'=>$rata_cbd);
		$tabel4[3] = array ('NO'=>'3','ITEM'=>"Nilai Journal Reading",'BOBOT'=>'5%','NILAI'=>$nilai_jurnal);
		$tabel4[4] = array ('NO'=>'4','ITEM'=>"Nilai DOPS / OSCE",'BOBOT'=>'30%','NILAI'=>$nilai_dops_osce);
		$tabel4[5] = array ('NO'=>'5','ITEM'=>"Nilai Ujian MCQ",'BOBOT'=>'20%','NILAI'=>$nilai_mcq);
		$tabel4[6] = array ('NO'=>'6','ITEM'=>"Nilai MINI-PAT",'BOBOT'=>'10%','NILAI'=>$nilai_minipat);
		$pdf->ezTable($tabel4,$kolom4,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
		'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'BOBOT'=>array('width'=>100,'justification'=>'center'),'NILAI'=>array('width'=>100,'justification'=>'center'))));

		//Total Nilai
		$nilai_total = number_format(0.2*$nilai_minicex+0.15*$rata_cbd+0.05*$nilai_jurnal+0.3*$nilai_dops_osce+0.2*$nilai_mcq+0.1*$nilai_minipat,2);
		$kolom5 = array ('TOTAL'=>"",'NILAI'=>"");
		$tabel5[1] = array ('TOTAL'=>"Nilai Total:", 'NILAI'=>"$nilai_total");
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
		$tabel6[1] = array ('TOTAL'=>'Nilai Total Kepaniteraan (Stase) Ilmu Kebidanan dan Penyakit Kandungan','NILAI'=>": "."<b>$nilai_total</b>");
		$tabel6[2] = array ('TOTAL'=>'Ekivalensi Nilai Huruf','NILAI'=>": "."<b>$grade</b>");
		$pdf->ezTable($tabel6,$kolom6,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
		'cols'=>array('TOTAL'=>array('width'=>350))));
		$pdf->ezSetDy(-20,'');

		//Persetujuan Kordik
		$nip_kordik = mysqli_fetch_array(mysqli_query($con,"SELECT `username` FROM `admin` WHERE `stase`='K111'"));
		$data_kordik = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$nip_kordik[username]'"));
		$kordik = $data_kordik[nama].", ".$data_kordik[gelar];
		$kolom6a = array ('ITEM'=>'');
		$tabel6a[1] = array ('ITEM'=>'Status: <b>DISETUJUI</b>');
		$tabel6a[2] = array ('ITEM'=>'pada tanggal _____________________');
		$tabel6a[3] = array ('ITEM'=>'Kordik Kepaniteraan (Stase)');
		$tabel6a[4] = array ('ITEM'=>'Ilmu Kebidanan dan Penyakit Kandungan');
		$tabel6a[5] = array ('ITEM'=>'');
		$tabel6a[6] = array ('ITEM'=>'');
		$tabel6a[7] = array ('ITEM'=>$kordik);
		$tabel6a[8] = array ('ITEM'=>'NIP: '.$data_kordik[nip]);
		$pdf->ezTable($tabel6a,$kolom6a,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 1 ,'showBgCol'=> 0,
		'cols'=>array('ITEM'=>array('justification'=>'right'))));

		$pdf->ezSetDy(-10,'');
		$pdf->addText(50,25,10,"$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Rekap Nilai Ilmu Kebidanan dan Penyakit Kandungan <i>[hal 1]</i>");
		$pdf->ezStream(array("Content-Disposition"=>"Nilai Bagian Ilmu Kebidanan dan Penyakit Kandungan $data_mhsw[nim].pdf"));
?>