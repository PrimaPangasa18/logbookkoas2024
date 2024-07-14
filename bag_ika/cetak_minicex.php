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

		$daftar_minicex = mysqli_query($con,"SELECT * FROM `ika_nilai_minicex` WHERE `nim`='$nim_mhsw_ika' AND `status_approval`='1' ORDER BY `tgl_approval` ASC");
		$jumlah_halaman = mysqli_num_rows($daftar_minicex);
		$halaman = 1;
		while ($data_minicex = mysqli_fetch_array($daftar_minicex))
		{
		  //Judul
		  $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_minicex[dosen]'"));

		  $kolom1 = array ('item'=>"");
		  $tabel1{1} = array ('item'=>"NILAI MINI-CEX KEPANITERAAN (STASE) ILMU KESEHATAN ANAK");
		  $tabel1{2} = array ('item'=>"LOGBOOK KOAS PENDIDIKAN PROFESI DOKTER");
		  $tabel1{3} = array ('item'=>"FAKULTAS KEDOKTERAN - UNIVERSITAS DIPONEGORO");
		  $pdf->ezTable($tabel1,$kolom1,"",
		  array('maxWidth'=>540,'width'=>520,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 0 ,
		  'cols'=>array('item'=>array('justification'=>'left'))));

			$tanggal_ujian = tanggal_indo($data_minicex[tgl_ujian]);
			$evaluasi = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `ika_evaluasi_ref` WHERE `id`='$data_minicex[evaluasi]'"));

		  //Data Mahasiswa dan Tabel 1
		  $pdf->ezSetDy(-20,'');
		  $kolom2 = array ('item'=>"",'isi'=>"");
		  $tabel2{1} = array ('item'=>"Nama Dosen Penilai / DPJP", 'isi'=>": "."$data_dosen[nama]".", "."$data_dosen[gelar]");
			$tabel2{2} = array ('item'=>"Tanggal Ujian", 'isi'=>": "."$tanggal_ujian");
		  $tabel2{3} = array ('item'=>"", 'isi'=>"");
		  $tabel2{4} = array ('item'=>"Nama Dokter Muda", 'isi'=>": "."$data_mhsw[nama]");
		  $tabel2{5} = array ('item'=>"NIM", 'isi'=>": "."$data_mhsw[nim]");
			$tabel2{6} = array ('item'=>"Jenis Evaluasi", 'isi'=>": "."$evaluasi[evaluasi] (#$evaluasi[id])");
			$tabel2{7} = array ('item'=>"Situasi Ruangan", 'isi'=>": "."$data_minicex[situasi_ruangan]");
			$tabel2{8} = array ('item'=>"Inisial Pasien", 'isi'=>": "."$data_minicex[inisial_pasien]");
			$tabel2{9} = array ('item'=>"Umur Pasien", 'isi'=>": "."$data_minicex[umur_pasien]"." tahun");
		  $tabel2{10} = array ('item'=>"Jenis Kelamin Pasien", 'isi'=>": "."$data_minicex[jk_pasien]");
		  $tabel2{11} = array ('item'=>"Problem Pasien/Diagnosis", 'isi'=>": "."$data_minicex[diagnosis]");
		  $tabel2{12} = array ('item'=>"Tingkat Kesulitan", 'isi'=>": "."$data_minicex[tingkat_kesulitan]");
		  $tabel2{13} = array ('item'=>"Fokus Kasus", 'isi'=>": "."$data_minicex[fokus_kasus]");

		  $pdf->ezTable($tabel2,$kolom2,"",
		  array('maxWidth'=>540,'width'=>520,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 0 ,
		  'cols'=>array('item'=>array('justification'=>'left','width'=>170))));
		  $pdf->ezSetDy(-20,'');

		  //Header tabel 2
		  $kolom3 = array ('NO'=>'','ASPEK'=>'','OBS'=>'','NILAI'=>'');
		  $tabel3{1} = array ('NO'=>'<b>No</b>','ASPEK'=>'<b>Aspek Yang Dinilai</b>','OBS'=>'<b>Status Observasi</b>','NILAI'=>'<b>Nilai (0-100)</b>');
		  $pdf->ezTable($tabel3,$kolom3,"",
		  array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
		  'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'ASPEK'=>array('justification'=>'center'),'OBS'=>array('width'=>100,'justification'=>'center'),'NILAI'=>array('width'=>100,'justification'=>'center'))));

		  //Nilai Aspek 1 - 8 dan Status Observasi 1-8
		  $aspek1 = "Kemampuan Wawancara Medis";
		  $aspek2 = "Kemampuan Pemeriksaan Fisik";
		  $aspek3 = "Kualitas Humanisti / Profesionalisme";
		  $aspek4 = "Keputusan Klinis";
		  $aspek5 = "Kemampuan Tatalaksana Kasus";
		  $aspek6 = "Kemampuan Konseling";
		  $aspek7 = "Organisasi / Efisiensi";
		  $aspek8 = "Kompetensi Klinis Keseluruhan";
		  if ($data_minicex[observasi_1]=="1") $obs1 = "Ya"; else $obs1 = "Tidak";
		  if ($data_minicex[observasi_2]=="1") $obs2 = "Ya"; else $obs2 = "Tidak";
		  if ($data_minicex[observasi_3]=="1") $obs3 = "Ya"; else $obs3 = "Tidak";
		  if ($data_minicex[observasi_4]=="1") $obs4 = "Ya"; else $obs4 = "Tidak";
		  if ($data_minicex[observasi_5]=="1") $obs5 = "Ya"; else $obs5 = "Tidak";
		  if ($data_minicex[observasi_6]=="1") $obs6 = "Ya"; else $obs6 = "Tidak";
		  if ($data_minicex[observasi_7]=="1") $obs7 = "Ya"; else $obs7 = "Tidak";
		  if ($data_minicex[observasi_8]=="1") $obs8 = "Ya"; else $obs8 = "Tidak";

		  $kolom4 = array ('NO'=>'','ASPEK'=>'','OBS'=>'','NILAI'=>'');
		  $tabel4{1} = array ('NO'=>'1','ASPEK'=>$aspek1,'OBS'=>$obs1,'NILAI'=>$data_minicex[aspek_1]);
		  $tabel4{2} = array ('NO'=>'2','ASPEK'=>$aspek2,'OBS'=>$obs2,'NILAI'=>$data_minicex[aspek_2]);
		  $tabel4{3} = array ('NO'=>'3','ASPEK'=>$aspek3,'OBS'=>$obs3,'NILAI'=>$data_minicex[aspek_3]);
		  $tabel4{4} = array ('NO'=>'4','ASPEK'=>$aspek4,'OBS'=>$obs4,'NILAI'=>$data_minicex[aspek_4]);
		  $tabel4{5} = array ('NO'=>'5','ASPEK'=>$aspek5,'OBS'=>$obs5,'NILAI'=>$data_minicex[aspek_5]);
		  $tabel4{6} = array ('NO'=>'6','ASPEK'=>$aspek6,'OBS'=>$obs6,'NILAI'=>$data_minicex[aspek_6]);
		  $tabel4{7} = array ('NO'=>'7','ASPEK'=>$aspek7,'OBS'=>$obs7,'NILAI'=>$data_minicex[aspek_7]);
		  $tabel4{8} = array ('NO'=>'8','ASPEK'=>$aspek8,'OBS'=>$obs8,'NILAI'=>$data_minicex[aspek_8]);
		  $pdf->ezTable($tabel4,$kolom4,"",
		  array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
		  'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'OBS'=>array('width'=>100,'justification'=>'center'),'NILAI'=>array('width'=>100,'justification'=>'center'))));

		  //Nilai Total
		  $kolom5 = array ('TOTAL'=>'','NILAI'=>'');
		  $tabel5{1} = array ('TOTAL'=>'Rata-Rata Nilai (Jumlah Nilai / Jumlah Observasi):','NILAI'=>"<b>$data_minicex[nilai_rata]</b>");
		  $pdf->ezTable($tabel5,$kolom5,"",
		  array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
		  'cols'=>array('TOTAL'=>array('justification'=>'right'),'NILAI'=>array('width'=>100,'justification'=>'center'))));

			$kolom50 = array ('ITEM'=>'');
			$tabel50{1} = array ('ITEM'=>"<i>Keterangan: Nilai Batas Lulus (NBL) = 70</i>");
			$pdf->ezTable($tabel50,$kolom50,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>8,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>10, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('ITEM'=>array('justification'=>'left'))));
			$pdf->ezSetDy(-20,'');

		  //Umpan Balik
		  $kolom6a = array ('ITEM'=>'');
		  $tabel6a{1} = array ('ITEM'=>'<b>UMPAN BALIK KOMPETENSI KLINIS</b>');
		  $pdf->ezTable($tabel6a,$kolom6a,"",
		  array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
		  'cols'=>array('ITEM'=>array('width'=>510,'justification'=>'center'))));

		  $kolom6b = array ('ITEM1'=>'','ITEM2'=>'');
		  $item1 = "Sudah bagus.\r\nUmpan balik: "."<i>$data_minicex[ub_bagus]</i>";
		  $item2 = "Perlu perbaikan.\r\nUmpan balik: "."<i>$data_minicex[ub_perbaikan]</i>";
		  $tabel6b{1} = array ('ITEM1'=>$item1,'ITEM2'=>$item2);
		  $pdf->ezTable($tabel6b,$kolom6b,"",
		  array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
		  'cols'=>array('ITEM1'=>array('width'=>255),'ITEM2'=>array('width'=>255))));

		  $kolom6c = array ('ITEM'=>'');
		  $item1 = "Rencana tindak lanjut yang disetujui bersama:\r\n<i>$data_minicex[action_plan]</i>";
		  $tabel6c{1} = array ('ITEM'=>$item1);
		  $pdf->ezTable($tabel6c,$kolom6c,"",
		  array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
		  'cols'=>array('ITEM'=>array('width'=>510))));
		  $pdf->ezSetDy(-20,'');

		  //Catatan
		  $kolom7a = array ('ITEM'=>'');
		  $tabel7a{1} = array ('ITEM'=>'<b>Catatan:</b>');
		  $pdf->ezTable($tabel7a,$kolom7a,"",
		  array('maxWidth'=>530,'width'=>510,'fontSize'=>8,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>10, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 1 ,'showBgCol'=> 0,
		  'cols'=>array('ITEM'=>array('width'=>510))));

		  $kolom7b = array ('NO'=>'','ITEM'=>'','NILAI'=>'');
		  $tabel7b{1} = array ('NO'=>'1','ITEM'=>'Waktu Mini-CEX:','NILAI'=>'');
		  $tabel7b{2} = array ('NO'=>'','ITEM'=>'  Observasi','NILAI'=>': '.$data_minicex[waktu_observasi].' menit');
		  $tabel7b{3} = array ('NO'=>'','ITEM'=>'  Memberikan Umpan Balik','NILAI'=>': '.$data_minicex[waktu_ub].' menit');
		  $tabel7b{4} = array ('NO'=>'2','ITEM'=>'Kepuasan penilai terhadap Mini-CEX','NILAI'=>': '.$data_minicex[kepuasan_penilai]);
		  $tabel7b{5} = array ('NO'=>'3','ITEM'=>'Kepuasan residen terhadap Mini-CEX','NILAI'=>': '.$data_minicex[kepuasan_residen]);
		  $pdf->ezTable($tabel7b,$kolom7b,"",
		  array('maxWidth'=>530,'width'=>510,'fontSize'=>8,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>10, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 0 ,'showBgCol'=> 0,
		  'cols'=>array('NO'=>array('width'=>15,'justification'=>'center'),'ITEM'=>array('width'=>150),'NILAI'=>array('width'=>345))));
		  $pdf->ezSetDy(-20,'');

		  //Persetujuan
		  $tanggal_approval = tanggal_indo($data_minicex[tgl_approval]);
		  $dosen = $data_dosen[nama].", ".$data_dosen[gelar];
		  $kolom8 = array ('ITEM'=>'');
		  $tabel8{1} = array ('ITEM'=>'Status: <b>DISETUJUI</b>');
		  $tabel8{2} = array ('ITEM'=>'pada tanggal '.$tanggal_approval);
		  $tabel8{3} = array ('ITEM'=>'Dosen Penilai / DPJP');
		  $tabel8{4} = array ('ITEM'=>'');
		  $tabel8{5} = array ('ITEM'=>$dosen);
		  $tabel8{6} = array ('ITEM'=>'NIP: '.$data_dosen[nip]);
		  $pdf->ezTable($tabel8,$kolom8,"",
		  array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 1 ,'showBgCol'=> 0,
		  'cols'=>array('ITEM'=>array('justification'=>'right'))));

		  $pdf->ezSetDy(-10,'');
		  $pdf->addText(50,25,10,"$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Nilai Mini-CEX                             <i>[hal $halaman dari $jumlah_halaman hal]</i>");
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
