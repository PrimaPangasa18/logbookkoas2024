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

		$daftar_presensi = mysqli_query($con,"SELECT * FROM `kdk_nilai_presensi` WHERE `nim`='$nim_mhsw_kdk' AND `status_approval`='1' ORDER BY `tgl_approval` ASC");
		$jumlah_halaman = mysqli_num_rows($daftar_presensi) + 1;
		$halaman = 1;
		$nilai_masuk = "Bila setiap hari masuk dan kegiatan memenuhi (skor maksimal 100)\r\n(<i>Rumus skor = 100 x Jml hari masuk / Jml hari kerja</i>)";
		$nilai_absen = "Bila tidak masuk tanpa ijin, nilai dipotong 5 / hari\r\n(<i>Rumus skor pengurangan = 5 x Jml hari tidak masuk tanpa ijin</i>)";
		$nilai_ijin = "Bila tidak masuk dengan ijin, nilai dipotong 2 / hari\r\n(<i>Rumus skor pengurangan = 2 x Jml hari tidak masuk dengan ijin</i>)";
		$nilai_total = "Total Nilai (Skor 1 + Skor 2 + Skor 3)";

		while ($data_presensi = mysqli_fetch_array($daftar_presensi))
		{
		  //Judul
		  $tanggal_mulai = tanggal_indo($data_presensi[tgl_mulai]);
		  $tanggal_selesai = tanggal_indo($data_presensi[tgl_selesai]);
		  $periode = $tanggal_mulai." s.d. ".$tanggal_selesai;

		  $kolom1 = array ('item'=>"");
		  $tabel1{1} = array ('item'=>"NILAI PRESENSI / KEHADIRAN");
		  $tabel1{2} = array ('item'=>"KEPANITERAAN KEDOKTERAN KELUARGA");
		  $tabel1{3} = array ('item'=>"LOGBOOK KOAS PENDIDIKAN PROFESI DOKTER");
		  $tabel1{4} = array ('item'=>"FAKULTAS KEDOKTERAN - UNIVERSITAS DIPONEGORO");
		  $pdf->ezTable($tabel1,$kolom1,"",
		  array('maxWidth'=>540,'width'=>520,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 0 ,
		  'cols'=>array('item'=>array('justification'=>'left'))));

		  //Data Mahasiswa
		  $pdf->ezSetDy(-20,'');
		  $kolom2 = array ('item'=>"",'isi'=>"");
		  $tabel2{1} = array ('item'=>"Nama Dokter Muda", 'isi'=>": "."$data_mhsw[nama]");
		  $tabel2{2} = array ('item'=>"NIM", 'isi'=>": "."$data_mhsw[nim]");
		  $tabel2{3} = array ('item'=>"Kepaniteraan (Stase)", 'isi'=>": "."KEPANITERAAN KEDOKTERAN KELUARGA");
		  $tabel2{4} = array ('item'=>"Instansi", 'isi'=>": "."$data_presensi[instansi]");
		  $tabel2{5} = array ('item'=>"Lokasi Puskesmas/Klinik Pratama", 'isi'=>": "."$data_presensi[lokasi]");
		  $tabel2{6} = array ('item'=>"Periode Kegiatan", 'isi'=>": "."$periode");
		  $pdf->ezTable($tabel2,$kolom2,"",
		  array('maxWidth'=>540,'width'=>520,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 0 ,
		  'cols'=>array('item'=>array('justification'=>'left','width'=>170))));
		  $pdf->ezSetDy(-10,'');

			//Jumlah Hari Kegiatan
			$kolom2a = array ('item'=>"",'isi'=>"");
		  $tabel2a{1} = array ('item'=>"Jumlah hari kerja", 'isi'=>": "."$data_presensi[hari_kerja] hari");
		  $tabel2a{2} = array ('item'=>"Jumlah hari tidak masuk dengan ijin", 'isi'=>": "."$data_presensi[hari_ijin] hari");
		  $tabel2a{3} = array ('item'=>"Jumlah hari tidak masuk tanpa ijin", 'isi'=>": "."$data_presensi[hari_alpa] hari");
		  $tabel2a{4} = array ('item'=>"Jumlah hari masuk kegiatan", 'isi'=>": "."$data_presensi[hari_masuk] hari");
		  $pdf->ezTable($tabel2a,$kolom2a,"",
		  array('maxWidth'=>540,'width'=>520,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 0 ,
		  'cols'=>array('item'=>array('justification'=>'left','width'=>170))));
		  $pdf->ezSetDy(-20,'');

		  //Header tabel
		  $kolom3 = array ('NO'=>'','UNSUR'=>'','SKOR'=>'');
		  $tabel3{1} = array ('NO'=>'<b>No</b>','UNSUR'=>'<b>Unsur Penilaian</b>','SKOR'=>'<b>Skor</b>');
		  $pdf->ezTable($tabel3,$kolom3,"",
		  array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
		  'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'UNSUR'=>array('justification'=>'center'),'SKOR'=>array('width'=>100,'justification'=>'center'))));

		  //Nilai Skor 1 - 3, Skor Total

		  $kolom4 = array ('NO'=>'','UNSUR'=>'','SKOR'=>'');
		  $tabel4{1} = array ('NO'=>'1','UNSUR'=>$nilai_masuk,'SKOR'=>$data_presensi[nilai_masuk]);
		  $tabel4{2} = array ('NO'=>'2','UNSUR'=>$nilai_absen,'SKOR'=>$data_presensi[nilai_absen]);
		  $tabel4{3} = array ('NO'=>'3','UNSUR'=>$nilai_ijin,'SKOR'=>$data_presensi[nilai_ijin]);
		  $pdf->ezTable($tabel4,$kolom4,"",
		  array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
		  'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'SKOR'=>array('width'=>100,'justification'=>'center'))));

		  //Nilai Total
		  $kolom5 = array ('TOTAL'=>'','NILAI'=>'');
		  $tabel5{1} = array ('TOTAL'=>$nilai_total,'NILAI'=>"<b>$data_presensi[nilai_total]</b>");
		  $pdf->ezTable($tabel5,$kolom5,"",
		  array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
		  'cols'=>array('TOTAL'=>array('justification'=>'right'),'NILAI'=>array('width'=>100,'justification'=>'center'))));
		  $pdf->ezSetDy(-20,'');

		  //Persetujuan
		  $tanggal_approval = tanggal_indo($data_presensi[tgl_approval]);
		  $data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_presensi[dosen]'"));
		  $dosen = $data_dosen[nama].", ".$data_dosen[gelar];
		  $kolom6 = array ('ITEM'=>'');
		  $tabel6{1} = array ('ITEM'=>'Status: <b>DISETUJUI</b>');
		  $tabel6{2} = array ('ITEM'=>'pada tanggal '.$tanggal_approval);
		  $tabel6{3} = array ('ITEM'=>'Dokter Pembimbing');
		  $tabel6{4} = array ('ITEM'=>'');
		  $tabel6{5} = array ('ITEM'=>$dosen);
		  $tabel6{6} = array ('ITEM'=>'NIP: '.$data_dosen[nip]);
		  $pdf->ezTable($tabel6,$kolom6,"",
		  array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 1 ,'showBgCol'=> 0,
		  'cols'=>array('ITEM'=>array('justification'=>'right'))));
		  $pdf->ezSetDy(-10,'');
		  $pdf->addText(50,25,10,"$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Nilai Presensi / Kehadiran         <i>[hal $halaman dari $jumlah_halaman hal]</i>");
		  $pdf->ezNewPage();

		  $halaman++;
		}

		//---------------------
		//Rekap Nilai Puskesmas
		//---------------------
		//Nilai Rata Presensi
		$presensi = mysqli_query($con,"SELECT * FROM `kdk_nilai_presensi` WHERE `nim`='$nim_mhsw_kdk' AND `instansi`='Puskesmas' AND `status_approval`='1'");
		$jumlah_presensi = mysqli_num_rows($presensi);
		$jum_nilai = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`nilai_masuk`), SUM(`nilai_absen`), SUM(`nilai_ijin`), SUM(`nilai_total`) FROM `kdk_nilai_presensi` WHERE `nim`='$nim_mhsw_kdk' AND `instansi`='Puskesmas' AND `status_approval`='1'"));
		if ($jumlah_presensi>0) $rata_nilai_masuk_puskesmas =  number_format($jum_nilai[0]/$jumlah_presensi,2);
		else $rata_nilai_masuk_puskesmas = 0.00;
		if ($jumlah_presensi>0) $rata_nilai_absen_puskesmas =  number_format($jum_nilai[1]/$jumlah_presensi,2);
		else $rata_nilai_absen_puskesmas = 0.00;
		if ($jumlah_presensi>0) $rata_nilai_ijin_puskesmas =  number_format($jum_nilai[2]/$jumlah_presensi,2);
		else $rata_nilai_ijin_puskesmas = 0.00;
		if ($jumlah_presensi>0) $rata_nilai_total_puskesmas =  number_format($jum_nilai[3]/$jumlah_presensi,2);
		else $rata_nilai_total_puskesmas = 0.00;
		$total_presensi_puskesmas=number_format($rata_nilai_total_puskesmas,2);

		//---------------------
		//Rekap Nilai Klinik Pratama
		//---------------------
		//Nilai Rata Presensi
		$presensi = mysqli_query($con,"SELECT * FROM `kdk_nilai_presensi` WHERE `nim`='$nim_mhsw_kdk' AND `instansi`='Klinik Pratama' AND `status_approval`='1'");
		$jumlah_presensi = mysqli_num_rows($presensi);
		$jum_nilai = mysqli_fetch_array(mysqli_query($con,"SELECT SUM(`nilai_masuk`), SUM(`nilai_absen`), SUM(`nilai_ijin`), SUM(`nilai_total`) FROM `kdk_nilai_presensi` WHERE `nim`='$nim_mhsw_kdk' AND `instansi`='Klinik Pratama' AND `status_approval`='1'"));
		if ($jumlah_presensi>0) $rata_nilai_masuk_klinik =  number_format($jum_nilai[0]/$jumlah_presensi,2);
		else $rata_nilai_masuk_klinik = 0.00;
		if ($jumlah_presensi>0) $rata_nilai_absen_klinik =  number_format($jum_nilai[1]/$jumlah_presensi,2);
		else $rata_nilai_absen_klinik = 0.00;
		if ($jumlah_presensi>0) $rata_nilai_ijin_klinik =  number_format($jum_nilai[2]/$jumlah_presensi,2);
		else $rata_nilai_ijin_klinik = 0.00;
		if ($jumlah_presensi>0) $rata_nilai_total_klinik =  number_format($jum_nilai[3]/$jumlah_presensi,2);
		else $rata_nilai_total_klinik = 0.00;
		$total_presensi_klinik=number_format($rata_nilai_total_klinik,2);

		//Judul Rekap Total
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `stase_M121` WHERE `nim`='$nim_mhsw_kdk'"));
		$tanggal_mulai = tanggal_indo($data_stase[tgl_mulai]);
		$tanggal_selesai = tanggal_indo($data_stase[tgl_selesai]);
		$periode = $tanggal_mulai." s.d. ".$tanggal_selesai;

		$kolom7 = array ('item'=>"");
		$tabel7{1} = array ('item'=>"RATA-RATA NILAI PRESENSI / KEHADIRAN");
		$tabel7{2} = array ('item'=>"KEPANITERAAN KEDOKTERAN KELUARGA");
		$tabel7{3} = array ('item'=>"LOGBOOK KOAS PENDIDIKAN PROFESI DOKTER");
		$tabel7{4} = array ('item'=>"FAKULTAS KEDOKTERAN - UNIVERSITAS DIPONEGORO");
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

		//Rekap Puskesmas
		$kolom8a = array ('ITEM'=>'');
		$tabel8a{1} = array ('ITEM'=>'<b>Rekap Rata-Rata Nilai Presensi / Kehadiran di Puskesmas</b>');
		$pdf->ezTable($tabel8a,$kolom8a,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 2 ,'showBgCol'=> 0,
		'cols'=>array('ITEM'=>array('width'=>510))));

		//Header tabel
		$kolom9 = array ('NO'=>'','UNSUR'=>'','SKOR'=>'');
		$tabel9{1} = array ('NO'=>'<b>No</b>','UNSUR'=>'<b>Unsur Penilaian</b>','SKOR'=>'<b>Skor</b>');
		$pdf->ezTable($tabel9,$kolom9,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 2 ,'showBgCol'=> 0,
		'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'UNSUR'=>array('justification'=>'center'),'SKOR'=>array('width'=>100,'justification'=>'center'))));

		//Nilai Skor 1-3
		$kolom10 = array ('NO'=>'','UNSUR'=>'','SKOR'=>'');
		$tabel10{1} = array ('NO'=>'1','UNSUR'=>$nilai_masuk,'SKOR'=>$rata_nilai_masuk_puskesmas);
		$tabel10{2} = array ('NO'=>'2','UNSUR'=>$nilai_absen,'SKOR'=>$rata_nilai_absen_puskesmas);
		$tabel10{3} = array ('NO'=>'3','UNSUR'=>$nilai_ijin,'SKOR'=>$rata_nilai_ijin_puskesmas);
		$pdf->ezTable($tabel10,$kolom10,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 2 ,'showBgCol'=> 0,
		'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'SKOR'=>array('width'=>100,'justification'=>'center'))));

		//Nilai Total
		$kolom11 = array ('TOTAL'=>'','NILAI'=>'');
		$tabel11{1} = array ('TOTAL'=>$nilai_total,'NILAI'=>"<b>$total_presensi_puskesmas</b>");
		$pdf->ezTable($tabel11,$kolom11,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 2 ,'showBgCol'=> 0,
		'cols'=>array('TOTAL'=>array('justification'=>'right'),'NILAI'=>array('width'=>100,'justification'=>'center'))));
		$pdf->ezSetDy(-20,'');

		//Rekap Rumah Sakit
		$kolom8b = array ('ITEM'=>'');
		$tabel8b{1} = array ('ITEM'=>'<b>Rekap Rata-Rata Nilai Presensi / Kehadiran di Klinik Pratama</b>');
		$pdf->ezTable($tabel8b,$kolom8b,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 2 ,'showBgCol'=> 0,
		'cols'=>array('ITEM'=>array('width'=>510))));

		//Header tabel
		$kolom9a = array ('NO'=>'','UNSUR'=>'','SKOR'=>'');
		$tabel9a{1} = array ('NO'=>'<b>No</b>','UNSUR'=>'<b>Unsur Penilaian</b>','SKOR'=>'<b>Skor</b>');
		$pdf->ezTable($tabel9a,$kolom9a,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 2 ,'showBgCol'=> 0,
		'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'UNSUR'=>array('justification'=>'center'),'SKOR'=>array('width'=>100,'justification'=>'center'))));

		//Nilai Skor 1-3
		$kolom10a = array ('NO'=>'','UNSUR'=>'','SKOR'=>'');
		$tabel10a{1} = array ('NO'=>'1','UNSUR'=>$nilai_masuk,'SKOR'=>$rata_nilai_masuk_klinik);
		$tabel10a{2} = array ('NO'=>'2','UNSUR'=>$nilai_absen,'SKOR'=>$rata_nilai_absen_klinik);
		$tabel10a{3} = array ('NO'=>'3','UNSUR'=>$nilai_ijin,'SKOR'=>$rata_nilai_ijin_klinik);
		$pdf->ezTable($tabel10a,$kolom10a,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 2 ,'showBgCol'=> 0,
		'cols'=>array('NO'=>array('width'=>30,'justification'=>'center'),'SKOR'=>array('width'=>100,'justification'=>'center'))));

		//Nilai Total
		$kolom11a = array ('TOTAL'=>'','NILAI'=>'');
		$tabel11a{1} = array ('TOTAL'=>$nilai_total,'NILAI'=>"<b>$total_presensi_klinik</b>");
		$pdf->ezTable($tabel11a,$kolom11a,"",
		array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>2,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 2 ,'showBgCol'=> 0,
		'cols'=>array('TOTAL'=>array('justification'=>'right'),'NILAI'=>array('width'=>100,'justification'=>'center'))));
		$pdf->ezSetDy(-20,'');

		//Nilai Total Rata-rata
		$rata_total_presensi=($total_presensi_puskesmas+$total_presensi_klinik)/2;
		$rata_total_presensi = number_format($rata_total_presensi,2);
		$kolom12 = array ('TOTAL'=>'','NILAI'=>'');
		$tabel12{1} = array ('TOTAL'=>'Rata-Rata Nilai Presensi','NILAI'=>": <b>$rata_total_presensi</b>");
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
		$pdf->addText(50,25,10,"$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Nilai Presensi / Kehadiran         <i>[hal $halaman dari $jumlah_halaman hal]</i>");
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
