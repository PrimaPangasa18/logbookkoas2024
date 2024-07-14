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
		if ($_COOKIE[level]=='5') $nim_mhsw_neuro = $_COOKIE[user];
		if ($_COOKIE[level]=='1' OR $_COOKIE[level]=='2' OR $_COOKIE[level]=='3') $nim_mhsw_neuro = $_GET[nim];

		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw_neuro'"));
		$datastase_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `stase_M092` WHERE `nim`='$nim_mhsw_neuro'"));
		$mulai_stase = tanggal_indo($datastase_mhsw[tgl_mulai]);
		$selesai_stase = tanggal_indo($datastase_mhsw[tgl_selesai]);
		$periode_stase = $mulai_stase." s.d. ".$selesai_stase;

		$pdf = new Cezpdf('A4');
		$pdf->ezSetMargins(30,40,50,50);
		$pdf->selectFont('../fonts/Helvetica.afm');

		$daftar_spv = mysqli_query($con,"SELECT * FROM `neuro_nilai_spv` WHERE `nim`='$nim_mhsw_neuro' AND `status_approval`='1' ORDER BY `tgl_approval` ASC");
		$jumlah_halaman = mysqli_num_rows($daftar_spv);
		$halaman = 1;
		while ($data_spv = mysqli_fetch_array($daftar_spv))
		{
			//Judul
			$kolom1 = array ('item'=>"");
			$tabel1{1} = array ('item'=>"NILAI UJIAN SPV KEPANITERAAN (STASE) NEUROLOGI");
			$tabel1{2} = array ('item'=>"LOGBOOK KOAS PENDIDIKAN PROFESI DOKTER");
			$tabel1{3} = array ('item'=>"FAKULTAS KEDOKTERAN - UNIVERSITAS DIPONEGORO");
			$pdf->ezTable($tabel1,$kolom1,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 0 ,
			'cols'=>array('item'=>array('justification'=>'left'))));

			//Data Mahasiswa
			$pdf->ezSetDy(-20,'');
			$tanggal_ujian = tanggal_indo($data_spv[tgl_ujian]);
			$kolom2 = array ('item'=>"",'isi'=>"");
			$tabel2{1} = array ('item'=>"Nama Mahasiswa", 'isi'=>": "."$data_mhsw[nama]");
			$tabel2{2} = array ('item'=>"NIM", 'isi'=>": "."$data_mhsw[nim]");
			$tabel2{3} = array ('item'=>"Kepaniteraan (Stase)", 'isi'=>": "."Neurologi");
			$tabel2{4} = array ('item'=>"Periode", 'isi'=>": "."$periode_stase");
			$tabel2{5} = array ('item'=>"Tanggal Ujian", 'isi'=>": "."$tanggal_ujian");
			$tabel2{6} = array ('item'=>"", 'isi'=>"");
			$tabel2{7} = array ('item'=>"Nilai Ujian SPV", 'isi'=>": "."<b>$data_spv[nilai]</b>");
			$pdf->ezTable($tabel2,$kolom2,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 0 ,
			'cols'=>array('item'=>array('justification'=>'left','width'=>110))));
			$pdf->ezSetDy(-20,'');

			//Catatan
			if ($data_spv[catatan]=="") $catatan = "< Tidak ada catatan khusus >";
			else $catatan = $data_spv[catatan];
			$kolom3 = array ('ITEM'=>'');
			$tabel3{1} = array ('ITEM'=>"Catatan Dosen Penguji:\r\n\r\n<i>$catatan</i>");
			$pdf->ezTable($tabel3,$kolom3,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 3 ,'showBgCol'=> 0,
			'cols'=>array('ITEM'=>array('width'=>510))));
			$pdf->ezSetDy(-20,'');

			//Persetujuan
			$tanggal_approval = tanggal_indo($data_spv[tgl_approval]);
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_spv[dosen]'"));
			$dosen = $data_dosen[nama].", ".$data_dosen[gelar];
			$kolom6 = array ('ITEM'=>'');
			$tabel6{1} = array ('ITEM'=>'Status: <b>DISETUJUI</b>');
			$tabel6{2} = array ('ITEM'=>'pada tanggal '.$tanggal_approval);
			$tabel6{3} = array ('ITEM'=>'Dosen Penguji');
			$tabel6{4} = array ('ITEM'=>'');
			$tabel6{5} = array ('ITEM'=>$dosen);
			$tabel6{6} = array ('ITEM'=>'NIP: '.$data_dosen[nip]);
			$pdf->ezTable($tabel6,$kolom6,"",
			array('maxWidth'=>530,'width'=>510,'fontSize'=>10,'showHeadings'=>0,'shaded'=>0,'showLines'=>0,'titleFontSize'=>12, 'xPos' => 'center','xOrientation' => 'center','rowGap' => 1 ,'showBgCol'=> 0,
			'cols'=>array('ITEM'=>array('justification'=>'right'))));
			$pdf->ezSetDy(-10,'');
			$pdf->addText(50,25,10,"$data_mhsw[nama] ($data_mhsw[nim]) - Cetak Nilai Ujian SPV                        <i>[hal $halaman dari $jumlah_halaman hal]</i>");
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
