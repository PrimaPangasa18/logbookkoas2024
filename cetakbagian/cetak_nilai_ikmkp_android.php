<?php

	include "../config.php";
	include "../fungsi.php";
	include "../class.ezpdf.php";

	error_reporting("E_ALL ^ E_NOTICE");

	    $nim_mhsw_ikmkp = $_GET[nim];

		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw_ikmkp'"));
		$pdf = new Cezpdf('A4');
		$pdf->ezSetMargins(30,40,50,50);
		$pdf->selectFont('../fonts/Helvetica.afm');

		//-------------------------------
		//Rekap Nilai Penilaian Kegiatan di PKBI
		//-------------------------------
		$data_nilai_pkbi = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_total` FROM `ikmkp_nilai_pkbi` WHERE `nim`='$nim_mhsw_ikmkp' AND `status_approval`='1'"));
		$nilai_pkbi = $data_nilai_pkbi[nilai_total];
		$nilai_pkbi = number_format($nilai_pkbi,2);

		//-------------------------------
		//Rekap Nilai Penilaian Kegiatan di P2UKM Mlonggo
		//-------------------------------
		//Kegiatan Evaluasi Manajemen Puskesmas
		$data_nilai_p2ukm_emp = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai_total`) FROM `ikmkp_nilai_p2ukm` WHERE `nim`='$nim_mhsw_ikmkp' AND `jenis_penilaian`='Evaluasi Manajemen Puskesmas' AND `status_approval`='1'"));
		$nilai_p2ukm_emp=$data_nilai_p2ukm_emp[0];
		//Kegiatan Evaluasi Program Kesehatan
		$data_nilai_p2ukm_epk = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai_total`) FROM `ikmkp_nilai_p2ukm` WHERE `nim`='$nim_mhsw_ikmkp' AND `jenis_penilaian`='Evaluasi Program Kesehatan' AND `status_approval`='1'"));
		$nilai_p2ukm_epk = $data_nilai_p2ukm_epk[0];
		//Kegiatan Diagnosis Komunitas
		$data_nilai_p2ukm_dk = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai_total`) FROM `ikmkp_nilai_p2ukm` WHERE `nim`='$nim_mhsw_ikmkp' AND `jenis_penilaian`='Diagnosis Komunitas' AND `status_approval`='1'"));
		$nilai_p2ukm_dk = $data_nilai_p2ukm_dk[0];
		//Nilai Rata P2UKM
		$nilai_p2ukm = ($nilai_p2ukm_emp+$nilai_p2ukm_epk+$nilai_p2ukm_dk)/3;
		$nilai_p2ukm = number_format($nilai_p2ukm,2);

		//-------------------------------
		//Rekap Nilai Penugasan dan Test
		//-------------------------------
		//Nilai Post-test (jenis_test=2)
		$nilai_terbaik = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `ikmkp_nilai_test` WHERE `nim`='$nim_mhsw_ikmkp' AND `jenis_test`='2'"));
		$nilai_test = number_format($nilai_terbaik[0],2);

		//-------------------------------
		//Rekap Nilai Ujian Komprehensip
		//-------------------------------
		$data_nilai_komprehensip = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_total` FROM `ikmkp_nilai_komprehensip` WHERE `nim`='$nim_mhsw_ikmkp' AND `status_approval`='1'"));
		$nilai_komprehensip = number_format($data_nilai_komprehensip[nilai_total],2);

		//Judul Rekap Total
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `stase_M095` WHERE `nim`='$nim_mhsw_ikmkp'"));
		$tanggal_mulai = tanggal_indo($data_stase[tgl_mulai]);
		$tanggal_selesai = tanggal_indo($data_stase[tgl_selesai]);
		$periode = $tanggal_mulai." s.d. ".$tanggal_selesai;

		$kolom1 = array ('item'=>"");
		$tabel1[1] = array ('item'=>"REKAP NILAI KEPANITERAAN (STASE) IKM-KP");
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
		$tabel2[3] = array ('item'=>"Kepaniteraan (Stase)", 'isi'=>": "."IKM-KP");
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
		$tabel4[1] = array ('NO'=>'1','ITEM'=>'Nilai Kegiatan di PKBI','BOBOT'=>'12.5%','NILAI'=>$nilai_pkbi);
		$tabel4[2] = array ('NO'=>'2','ITEM'=>'Nilai Kegiatan di P2UKM Mlonggo','BOBOT'=>'25%','NILAI'=>$nilai_p2ukm);
		$tabel4[3] = array ('NO'=>'3','ITEM'=>'Nilai Post-Test','BOBOT'=>'12.5%','NILAI'=>$nilai_test);
		$tabel4[4] = array ('NO'=>'4','ITEM'=>'Nilai Ujian Komprehensip','BOBOT'=>'50%','NILAI'=>$nilai_komprehensip);
		$pdf->ezTable($tabel4,$kolom4,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
		'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'BOBOT'=>array('width'=>100,'justification'=>'center'),'NILAI'=>array('width'=>100,'justification'=>'center'))));

		//Total Nilai
		$nilai_total = number_format(($nilai_pkbi+2*$nilai_p2ukm+$nilai_test+4*$nilai_komprehensip)/8,2);
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
		$tabel6[1] = array ('TOTAL'=>'Nilai Total Kepaniteraan (Stase) IKM-KP','NILAI'=>": ".$nilai_total);
		$tabel6[2] = array ('TOTAL'=>'Ekivalensi Nilai Huruf','NILAI'=>": ".$grade);
		$pdf->ezTable($tabel6,$kolom6,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
		'cols'=>array('TOTAL'=>array('width'=>230))));
		$pdf->ezSetDy(-20,'');

		//Persetujuan Kordik
		$nip_kordik = mysqli_fetch_array(mysqli_query($con,"SELECT `username` FROM `admin` WHERE `stase`='K095'"));
		$data_kordik = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$nip_kordik[username]'"));
		$kordik = $data_kordik[nama].", ".$data_kordik[gelar];
		$kolom6a = array ('ITEM'=>'');
		$tabel6a[1] = array ('ITEM'=>'Status: <b>DISETUJUI</b>');
		$tabel6a[2] = array ('ITEM'=>'pada tanggal _____________________');
		$tabel6a[3] = array ('ITEM'=>'Kordik Kepaniteraan (Stase) IKM-KP');
		$tabel6a[4] = array ('ITEM'=>'');
		$tabel6a[5] = array ('ITEM'=>'');
		$tabel6a[6] = array ('ITEM'=>$kordik);
		$tabel6a[7] = array ('ITEM'=>'NIP: '.$data_kordik[nip]);
		$pdf->ezTable($tabel6a,$kolom6a,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 1 ,'showBgCol'=> 0,
		'cols'=>array('ITEM'=>array('justification'=>'right'))));

		$pdf->ezSetDy(-10,'');
		$pdf->addText(50,25,10,"$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Rekap Nilai Kepaniteraan (Stase) IKM-KP                     <i>[hal 1]</i>");
		$pdf->ezStream(array("Content-Disposition"=>"Nilai Bagian IKMP-KP $data_mhsw[nim].pdf"));
?>
