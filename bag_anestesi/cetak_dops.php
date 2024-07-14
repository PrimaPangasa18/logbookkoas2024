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
		if ($_COOKIE[level]=='5') $nim_mhsw_anestesi = $_COOKIE[user];
		if ($_COOKIE[level]=='1' OR $_COOKIE[level]=='2' OR $_COOKIE[level]=='3') $nim_mhsw_anestesi = $_GET[nim];

		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw_anestesi'"));
		$pdf = new Cezpdf('A4');
		$pdf->ezSetMargins(30,40,50,50);
		$pdf->selectFont('../fonts/Helvetica.afm');

		$daftar_dops = mysqli_query($con,"SELECT * FROM `anestesi_nilai_dops` WHERE `nim`='$nim_mhsw_anestesi' AND `status_approval`='1' ORDER BY `tgl_approval` ASC");
		$jumlah_halaman = mysqli_num_rows($daftar_dops);
		$halaman = 1;
		while ($data_dops = mysqli_fetch_array($daftar_dops))
		{
			//Judul
			$kolom1 = array ('item'=>"");
			$tabel1{1} = array ('item'=>"NILAI DOPS KEPANITERAAN (STASE) ANESTESI DAN INTENSIVE CARE");
			$tabel1{2} = array ('item'=>"LOGBOOK KOAS PENDIDIKAN PROFESI DOKTER");
			$tabel1{3} = array ('item'=>"FAKULTAS KEDOKTERAN - UNIVERSITAS DIPONEGORO");
			$pdf->ezTable($tabel1,$kolom1,"",
			array('maxWidth'=>540,'width'=>520,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 0 ,
			'cols'=>array('item'=>array('justification'=>'left'))));

			//Data Mahasiswa
			$pdf->ezSetDy(-20,'');
			$kolom2 = array ('item'=>"",'isi'=>"");
			$tabel2{1} = array ('item'=>"Nama Mahasiswa", 'isi'=>": "."$data_mhsw[nama]");
			$tabel2{2} = array ('item'=>"NIM", 'isi'=>": "."$data_mhsw[nim]");
			$tabel2{3} = array ('item'=>"Kepaniteraan (Stase)", 'isi'=>": "."Anestesi dan Intensive Care");
			$tabel2{4} = array ('item'=>"Situasi Ruangan", 'isi'=>": "."$data_dops[situasi_ruangan]");
			$tabel2{5} = array ('item'=>"Jenis Tindak Medik", 'isi'=>": "."$data_dops[tindak_medik]");
			$pdf->ezTable($tabel2,$kolom2,"",
			array('maxWidth'=>540,'width'=>520,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 0 ,
			'cols'=>array('item'=>array('justification'=>'left','width'=>150))));

			$pdf->ezSetDy(-10,'');
			$kolom2a = array ('item'=>"",'isi'=>"");
			$tabel2a{1} = array ('item'=>"Jumlah tindak medik serupa yang pernah diobservasi penilai", 'isi'=>": "."$data_dops[obs_penilai]");
			$tabel2a{2} = array ('item'=>"Jumlah tindak medik serupa yang pernah dilakukan mahasiswa", 'isi'=>": "."$data_dops[obs_mhsw]");
			$pdf->ezTable($tabel2a,$kolom2a,"",
			array('maxWidth'=>540,'width'=>520,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 0 ,
			'cols'=>array('item'=>array('justification'=>'left','width'=>295))));
			$pdf->ezSetDy(-20,'');

			//Header tabel
			$kolom3 = array ('NO'=>'','ASPEK'=>'','OBS'=>'','NILAI'=>'');
			$tabel3{1} = array ('NO'=>'<b>No</b>','ASPEK'=>'<b>Aspek Yang Dinilai</b>','OBS'=>'<b>Status Observasi</b>','NILAI'=>'<b>Nilai (0-100)</b>');
			$pdf->ezTable($tabel3,$kolom3,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'ASPEK'=>array('justification'=>'center'),'OBS'=>array('width'=>100,'justification'=>'center'),'NILAI'=>array('width'=>100,'justification'=>'center'))));

			//Nilai Aspek 1 - 5
			if ($data_dops[observasi_1]=="0") $obs1 = "TIDAK"; else $obs1 = "YA";
			if ($data_dops[observasi_2]=="0") $obs2 = "TIDAK"; else $obs2 = "YA";
			if ($data_dops[observasi_3]=="0") $obs3 = "TIDAK"; else $obs3 = "YA";
			if ($data_dops[observasi_4]=="0") $obs4 = "TIDAK"; else $obs4 = "YA";
			if ($data_dops[observasi_5]=="0") $obs5 = "TIDAK"; else $obs5 = "YA";
			if ($data_dops[observasi_6]=="0") $obs6 = "TIDAK"; else $obs6 = "YA";
			if ($data_dops[observasi_7]=="0") $obs7 = "TIDAK"; else $obs7 = "YA";
			if ($data_dops[observasi_8]=="0") $obs8 = "TIDAK"; else $obs8 = "YA";
			if ($data_dops[observasi_9]=="0") $obs9 = "TIDAK"; else $obs9 = "YA";
			if ($data_dops[observasi_10]=="0") $obs10 = "TIDAK"; else $obs10 = "YA";
			if ($data_dops[observasi_11]=="0") $obs11 = "TIDAK"; else $obs11 = "YA";

			$kolom4 = array ('NO'=>'','ASPEK'=>'','OBS'=>'','NILAI'=>'');
			$tabel4{1} = array ('NO'=>'1','ASPEK'=>"Mempunyai pengetahuan tentang indikasi relevansi anatomic dan teknik tindak medik",'OBS'=>$obs1,'NILAI'=>$data_dops[aspek_1]);
			$tabel4{2} = array ('NO'=>'2','ASPEK'=>"Mendapat persetujuan tindak medik",'OBS'=>$obs2,'NILAI'=>$data_dops[aspek_2]);
			$tabel4{3} = array ('NO'=>'3','ASPEK'=>"Mampu mengajukan persiapan yang sesuai sebelum tindak medik",'OBS'=>$obs3,'NILAI'=>$data_dops[aspek_3]);
			$tabel4{4} = array ('NO'=>'4','ASPEK'=>"Mampu memberikan analgesic yang sesuai atau sedasi yang aman",'OBS'=>$obs4,'NILAI'=>$data_dops[aspek_4]);
			$tabel4{5} = array ('NO'=>'5','ASPEK'=>"Kemampuan secara teknik",'OBS'=>$obs5,'NILAI'=>$data_dops[aspek_5]);
			$tabel4{6} = array ('NO'=>'6','ASPEK'=>"Melakukan tindakan aseptic",'OBS'=>$obs6,'NILAI'=>$data_dops[aspek_6]);
			$tabel4{7} = array ('NO'=>'7','ASPEK'=>"Mencari bantuan bila diperlukan",'OBS'=>$obs7,'NILAI'=>$data_dops[aspek_7]);
			$tabel4{8} = array ('NO'=>'8','ASPEK'=>"Tatalaksana paska tindakan",'OBS'=>$obs8,'NILAI'=>$data_dops[aspek_8]);
			$tabel4{9} = array ('NO'=>'9','ASPEK'=>"Kecakapan komunikasi",'OBS'=>$obs9,'NILAI'=>$data_dops[aspek_9]);
			$tabel4{10} = array ('NO'=>'10','ASPEK'=>"Mempertimbangkan kondisi pasien / profesionalisme",'OBS'=>$obs10,'NILAI'=>$data_dops[aspek_10]);
			$tabel4{11} = array ('NO'=>'11','ASPEK'=>"Kemampuan secara keseluruhan dalam melakukan tindak medik",'OBS'=>$obs11,'NILAI'=>$data_dops[aspek_11]);
			$pdf->ezTable($tabel4,$kolom4,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'OBS'=>array('width'=>100,'justification'=>'center'),'NILAI'=>array('width'=>100,'justification'=>'center'))));

			//Nilai Total
			$kolom5 = array ('TOTAL'=>'','NILAI'=>'');
			$tabel5{1} = array ('TOTAL'=>'Rata-Rata Nilai (Jumlah Nilai / Jumlah Observasi):','NILAI'=>"<b>$data_dops[nilai_rata]</b>");
			$pdf->ezTable($tabel5,$kolom5,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('TOTAL'=>array('justification'=>'right'),'NILAI'=>array('width'=>100,'justification'=>'center'))));
			$kolom5 = array ('TOTAL'=>'','NILAI'=>'');

			$kolom50 = array ('ITEM'=>'');
			$tabel50{1} = array ('ITEM'=>"<i>Keterangan: Nilai Batas Lulus (NBL) = 70</i>");
			$pdf->ezTable($tabel50,$kolom50,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>8,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>10, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('ITEM'=>array('justification'=>'left'))));
			$pdf->ezSetDy(-20,'');

			//Umpan Balik
			$kolom5a = array ('ITEM'=>'');
			$tabel5a{1} = array ('ITEM'=>"<b>UMPAN BALIK TERHADAP KECAKAPAN TINDAK MEDIK</b>");
			$pdf->ezTable($tabel5a,$kolom5a,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('ITEM'=>array('justification'=>'center'))));
			$kolom5b = array ('ITEM1'=>'','ITEM2'=>'');
			$tabel5b{1} = array ('ITEM1'=>"<b>Sudah Bagus</b>",'ITEM2'=>"<b>Perlu Perbaikan</b>");
			$pdf->ezTable($tabel5b,$kolom5b,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('ITEM1'=>array('width'=>255,'justification'=>'center'),'ITEM2'=>array('width'=>255,'justification'=>'center'))));
			$kolom5c = array ('ITEM1'=>'','ITEM2'=>'');
			$tabel5c{1} = array ('ITEM1'=>"<i>$data_dops[ub_bagus]</i>",'ITEM2'=>"<i>$data_dops[ub_perbaikan]</i>");
			$pdf->ezTable($tabel5c,$kolom5c,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('ITEM1'=>array('width'=>255),'ITEM2'=>array('width'=>255))));
			//Saran
			$kolom5d = array ('ITEM'=>'');
			$tabel5d{1} = array ('ITEM'=>"<b>Saran:</b>\r\n\r\n<i>$data_dops[saran]</i>");
			$pdf->ezTable($tabel5d,$kolom5d,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('ITEM'=>array('width'=>510))));
			//Catatan
			$kolom5e = array ('ITEM'=>'');
			$tabel5e{1} = array ('ITEM'=>"<b>Catatan:</b>\r\n\r\nWaktu Penilaian Diskusi Kasus:\r\n    Observasi:<i>$data_dops[waktu_observasi] menit</i>\r\n    Memberikan umpan balik:<i>$data_dops[waktu_ub] menit</i>");
			$pdf->ezTable($tabel5e,$kolom5e,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('ITEM'=>array('width'=>510))));
			$pdf->ezSetDy(-20,'');

			//Persetujuan
			$tanggal_approval = tanggal_indo($data_dops[tgl_approval]);
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_dops[dosen]'"));
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
			$pdf->addText(50,25,10,"$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Nilai DOPS                                <i>[hal $halaman dari $jumlah_halaman hal]</i>");
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
