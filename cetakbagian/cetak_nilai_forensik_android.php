<?php

	include "../config.php";
	include "../fungsi.php";
	include "../class.ezpdf.php";

	error_reporting("E_ALL ^ E_NOTICE");

		$nim_mhsw_forensik = $_GET[nim];

		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw_forensik'"));
		$pdf = new Cezpdf('A4');
		$pdf->ezSetMargins(30,40,50,50);
		$pdf->selectFont('../fonts/Helvetica.afm');

		//-------------------------------
		//Rekap Nilai Penilaian Visum Bayangan
		//-------------------------------
		$data_nilai_visum1 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `forensik_nilai_visum` WHERE `nim`='$nim_mhsw_forensik' AND `dosen_ke`='1' AND `status_approval`='1'"));
		$data_nilai_visum2 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `forensik_nilai_visum` WHERE `nim`='$nim_mhsw_forensik' AND `dosen_ke`='2' AND `status_approval`='1'"));
		$data_nilai_visum3 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `forensik_nilai_visum` WHERE `nim`='$nim_mhsw_forensik' AND `dosen_ke`='3' AND `status_approval`='1'"));
		$data_nilai_visum4 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `forensik_nilai_visum` WHERE `nim`='$nim_mhsw_forensik' AND `dosen_ke`='4' AND `status_approval`='1'"));
		$nilai_visum1 = number_format($data_nilai_visum1[nilai_rata],2);
		$nilai_visum2 = number_format($data_nilai_visum2[nilai_rata],2);
		$nilai_visum3 = number_format($data_nilai_visum3[nilai_rata],2);
		$nilai_visum4 = number_format($data_nilai_visum4[nilai_rata],2);
		$nilai_visum = number_format(($nilai_visum1+$nilai_visum2+$nilai_visum3+$nilai_visum4)/4,2);

		//-------------------------------
		//Rekap Nilai Penilaian Kegiatan Jaga
		//-------------------------------
		$data_nilai_jaga1 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `forensik_nilai_jaga` WHERE `nim`='$nim_mhsw_forensik' AND `dosen_ke`='1' AND `status_approval`='1'"));
		$data_nilai_jaga2 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `forensik_nilai_jaga` WHERE `nim`='$nim_mhsw_forensik' AND `dosen_ke`='2' AND `status_approval`='1'"));
		$data_nilai_jaga3 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `forensik_nilai_jaga` WHERE `nim`='$nim_mhsw_forensik' AND `dosen_ke`='3' AND `status_approval`='1'"));
		$data_nilai_jaga4 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `forensik_nilai_jaga` WHERE `nim`='$nim_mhsw_forensik' AND `dosen_ke`='4' AND `status_approval`='1'"));
		$nilai_jaga1 = number_format($data_nilai_jaga1[nilai_rata],2);
		$nilai_jaga2 = number_format($data_nilai_jaga2[nilai_rata],2);
		$nilai_jaga3 = number_format($data_nilai_jaga3[nilai_rata],2);
		$nilai_jaga4 = number_format($data_nilai_jaga4[nilai_rata],2);
		$nilai_jaga = number_format(($nilai_jaga1+$nilai_jaga2+$nilai_jaga3+$nilai_jaga4)/4,2);

		//-------------------------------
		//Rekap Nilai Penilaian Substase
		//-------------------------------
		$data_nilai_substase1 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `forensik_nilai_substase` WHERE `nim`='$nim_mhsw_forensik' AND `dosen_ke`='1' AND `status_approval`='1'"));
		$data_nilai_substase2 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `forensik_nilai_substase` WHERE `nim`='$nim_mhsw_forensik' AND `dosen_ke`='2' AND `status_approval`='1'"));
		$data_nilai_substase3 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `forensik_nilai_substase` WHERE `nim`='$nim_mhsw_forensik' AND `dosen_ke`='3' AND `status_approval`='1'"));
		$data_nilai_substase4 = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `forensik_nilai_substase` WHERE `nim`='$nim_mhsw_forensik' AND `dosen_ke`='4' AND `status_approval`='1'"));
		$nilai_substase1 = number_format($data_nilai_substase1[nilai_rata],2);
		$nilai_substase2 = number_format($data_nilai_substase2[nilai_rata],2);
		$nilai_substase3 = number_format($data_nilai_substase3[nilai_rata],2);
		$nilai_substase4 = number_format($data_nilai_substase4[nilai_rata],2);
		$nilai_substase = number_format(($nilai_substase1+$nilai_substase2+$nilai_substase3+$nilai_substase4)/4,2);

		//-------------------------------
		//Rekap Nilai Penilaian Referat
		//-------------------------------
		$data_nilai_referat = mysqli_fetch_array(mysqli_query($con,"SELECT `nilai_rata` FROM `forensik_nilai_referat` WHERE `nim`='$nim_mhsw_forensik' AND `status_approval`='1'"));
		$nilai_referat = $data_nilai_referat[nilai_rata];
		$nilai_referat = number_format($nilai_referat,2);

		//-------------------------------
		//Rekap Nilai Test
		//-------------------------------
		$pretest = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `forensik_nilai_test` WHERE `nim`='$nim_mhsw_forensik' AND `jenis_test`='1' AND `status_approval`='1'"));
		$sikap = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `forensik_nilai_test` WHERE `nim`='$nim_mhsw_forensik' AND `jenis_test`='3' AND `status_approval`='1'"));
		$kompre1 = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `forensik_nilai_test` WHERE `nim`='$nim_mhsw_forensik' AND `jenis_test`='7' AND `status_approval`='1'"));
		$kompre2 = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `forensik_nilai_test` WHERE `nim`='$nim_mhsw_forensik' AND `jenis_test`='8' AND `status_approval`='1'"));
		$kompre3 = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `forensik_nilai_test` WHERE `nim`='$nim_mhsw_forensik' AND `jenis_test`='9' AND `status_approval`='1'"));
		$kompre4 = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `forensik_nilai_test` WHERE `nim`='$nim_mhsw_forensik' AND `jenis_test`='10' AND `status_approval`='1'"));
		$kompre5 = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(`nilai`) FROM `forensik_nilai_test` WHERE `nim`='$nim_mhsw_forensik' AND `jenis_test`='11' AND `status_approval`='1'"));
		$nilai_pretest = number_format($pretest[0],2);
		$nilai_sikap = number_format($sikap[0],2);
		$nilai_kompre1 = number_format($kompre1[0],2);
		$nilai_kompre2 = number_format($kompre2[0],2);
		$nilai_kompre3 = number_format($kompre3[0],2);
		$nilai_kompre4 = number_format($kompre4[0],2);
		$nilai_kompre5 = number_format($kompre5[0],2);
		$nilai_kompre = number_format(($nilai_kompre1+$nilai_kompre2+$nilai_kompre3+$nilai_kompre4+$nilai_kompre5)/5,2);

		//Judul Rekap Total
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `stase_M112` WHERE `nim`='$nim_mhsw_forensik'"));
		$tanggal_mulai = tanggal_indo($data_stase[tgl_mulai]);
		$tanggal_selesai = tanggal_indo($data_stase[tgl_selesai]);
		$periode = $tanggal_mulai." s.d. ".$tanggal_selesai;

		$kolom1 = array ('item'=>"");
		$tabel1[1] = array ('item'=>"REKAP NILAI KEPANITERAAN (STASE) KEDOKTERAN FORENSIK DAN MEDIKOLEGAL");
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
		$tabel2[3] = array ('item'=>"Kepaniteraan (Stase)", 'isi'=>": "."Kedokteran Forensik dan Medikolegal");
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
		$rincian_visum = "<i>Nilai Dosen 1: $nilai_visum1\r\nNilai Dosen 2: $nilai_visum2\r\nNilai Dosen 3: $nilai_visum3\r\nNilai Dosen 4: $nilai_visum4</i>";
		$rincian_jaga = "<i>Nilai Dosen 1: $nilai_jaga1\r\nNilai Dosen 2: $nilai_jaga2\r\nNilai Dosen 3: $nilai_jaga3\r\nNilai Dosen 4: $nilai_jaga4</i>";
		$rincian_substase = "<i>Nilai Dosen 1: $nilai_substase1\r\nNilai Dosen 2: $nilai_substase2\r\nNilai Dosen 3: $nilai_substase3\r\nNilai Dosen 4: $nilai_substase4</i>";
		$rincian_kompre = "<i>Nilai Kompre Stasi 1: $nilai_kompre1\r\nNilai Kompre Stasi 2: $nilai_kompre2\r\nNilai Kompre Stasi 3: $nilai_kompre3\r\nNilai Kompre Stasi 4: $nilai_kompre4\r\nNilai Kompre Stasi 5: $nilai_kompre5</i>";
		$kolom4 = array ('NO'=>'','ITEM'=>'','BOBOT'=>'','NILAI'=>'');
		$tabel4[1] = array ('NO'=>'1','ITEM'=>"Rata Nilai Visum Bayangan\r\n$rincian_visum",'BOBOT'=>'9%','NILAI'=>$nilai_visum);
		$tabel4[2] = array ('NO'=>'2','ITEM'=>"Rata Nilai Kegiatan Jaga\r\n$rincian_jaga",'BOBOT'=>'6%','NILAI'=>$nilai_jaga);
		$tabel4[3] = array ('NO'=>'3','ITEM'=>"Rata Nilai Substase\r\n$rincian_substase",'BOBOT'=>'12%','NILAI'=>$nilai_substase);
		$tabel4[4] = array ('NO'=>'4','ITEM'=>"Rata Nilai Kompre\r\n$rincian_kompre",'BOBOT'=>'36%','NILAI'=>$nilai_kompre);
		$tabel4[5] = array ('NO'=>'5','ITEM'=>"Nilai Referat",'BOBOT'=>'24%','NILAI'=>$nilai_referat);
		$tabel4[6] = array ('NO'=>'6','ITEM'=>"Nilai Pre-Test",'BOBOT'=>'10%','NILAI'=>$nilai_pretest);
		$tabel4[7] = array ('NO'=>'7','ITEM'=>"Nilai Sikap dan Perilaku",'BOBOT'=>'3%','NILAI'=>$nilai_sikap);
		$pdf->ezTable($tabel4,$kolom4,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
		'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'BOBOT'=>array('width'=>100,'justification'=>'center'),'NILAI'=>array('width'=>100,'justification'=>'center'))));

		//Total Nilai
		$nilai_total = number_format(0.09*$nilai_visum+0.06*$nilai_jaga+0.12*$nilai_substase+0.24*$nilai_referat+0.1*$nilai_pretest+0.03*$nilai_sikap+0.36*$nilai_kompre,2);
		$kolom5 = array ('TOTAL'=>"",'NILAI'=>"");
		$tabel5[1] = array ('TOTAL'=>"Nilai Total:", 'NILAI'=>"<b>$nilai_total</b>");
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
		$tabel6[1] = array ('TOTAL'=>'Nilai Total Kepaniteraan (Stase) Kedokteran Forensik dan Medikolegal','NILAI'=>": "."<b>$nilai_total</b>");
		$tabel6[2] = array ('TOTAL'=>'Ekivalensi Nilai Huruf','NILAI'=>": "."<b>$grade</b>");
		$pdf->ezTable($tabel6,$kolom6,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
		'cols'=>array('TOTAL'=>array('width'=>325))));
		$pdf->ezSetDy(-20,'');

		//Persetujuan Kordik
		$nip_kordik = mysqli_fetch_array(mysqli_query($con,"SELECT `username` FROM `admin` WHERE `stase`='K112'"));
		$data_kordik = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$nip_kordik[username]'"));
		$kordik = $data_kordik[nama].", ".$data_kordik[gelar];
		$kolom6a = array ('ITEM'=>'');
		$tabel6a[1] = array ('ITEM'=>'Status: <b>DISETUJUI</b>');
		$tabel6a[2] = array ('ITEM'=>'pada tanggal _____________________');
		$tabel6a[3] = array ('ITEM'=>'Kordik Kepaniteraan (Stase) Kedokteran Forensik dan Medikolegal');
		$tabel6a[4] = array ('ITEM'=>'');
		$tabel6a[5] = array ('ITEM'=>'');
		$tabel6a[6] = array ('ITEM'=>$kordik);
		$tabel6a[7] = array ('ITEM'=>'NIP: '.$data_kordik[nip]);
		$pdf->ezTable($tabel6a,$kolom6a,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 1 ,'showBgCol'=> 0,
		'cols'=>array('ITEM'=>array('justification'=>'right'))));

		$pdf->ezSetDy(-10,'');
		$pdf->addText(50,25,10,"$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Rekap Nilai Kedokteran Forensik dan Medikolegal           <i>[hal 1]</i>");
		$pdf->ezStream(array("Content-Disposition"=>"Nilai Bagian Kedokteran Forensik dan Medikolegal $data_mhsw[nim].pdf"));
?>
