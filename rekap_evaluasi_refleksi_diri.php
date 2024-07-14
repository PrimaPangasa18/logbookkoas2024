<HTML>
<head>
	<link rel="stylesheet" href="menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="mytable.css" type="text/css" media="screen" />
	<meta name="viewport" content="width=device-width, maximum-scale=1">
<!--</head>-->

</head>
<BODY>

<?php

	include "config.php";
	include "fungsi.php";

	error_reporting("E_ALL ^ E_NOTICE");

	if (empty($_COOKIE['user']) || empty($_COOKIE['pass'])){
		echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
	}
	else{
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND ($_COOKIE['level']==1 OR $_COOKIE['level']==2 OR $_COOKIE['level']==3))
	{
		if ($_COOKIE['level']==1) {include "menu1.php";}
	  if ($_COOKIE['level']==2) {include "menu2.php";}
	  if ($_COOKIE['level']==3) {include "menu3.php";}
		echo "<div class=\"text_header\">REKAP EVALUASI AKHIR STASE - UMUM</div>";
		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    	<legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">REKAP EVALUASI AKHIR STASE - UMUM<br>EVALUASI REFLEKSI DIRI</font></h4>";

		$id_stase = $_GET[stase];
		$angkatan_filter = $_GET[angk];
		$stase_id = "stase_".$id_stase;
		$include_id = "include_".$id_stase;
		$target_id = "target_".$id_stase;
		$tgl_awal = $_GET[tglawal];
		$tgl_akhir = $_GET[tglakhir];

		$filterstase = "`stase`="."'$id_stase'";
		$filtertgl = " AND (`tanggal`>="."'$tgl_awal'"." AND `tanggal`<="."'$tgl_akhir')";
		$filter = $filterstase.$filtertgl;

		$mhsw = mysqli_query($con,"SELECT `nim` FROM `$stase_id` WHERE `tgl_mulai`>='$tgl_awal' AND `tgl_selesai`<='$tgl_akhir' ORDER BY `nim`");
		$jml_mhsw = mysqli_num_rows($mhsw);
		$stase = mysqli_fetch_array(mysqli_query($con,"SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$id_stase'"));

		//--------------------
		echo "<table style=\"width:50%\">";
		echo "<tr>";
		echo "<td style=\"width:30%\">Kepaniteraan (Stase)</td><td style=\"width:70%\">: $stase[kepaniteraan]</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>Angkatan</td>";
		if ($angkatan_filter=="all") echo "<td>: Semua Angkatan</td>";
		else echo "<td>: Angkatan $angkatan_filter</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>Jumlah Mahasiswa</td><td>: $jml_mhsw orang</td>";
		echo "</tr>";
		$tglawal=tanggal_indo($tgl_awal);
		$tglakhir=tanggal_indo($tgl_akhir);
		echo "<tr>";
		echo "<td>Periode Kegiatan</td><td>: $tglawal s.d $tglakhir</td>";
		echo "</tr>";
		echo "</table><br><br>";
		//------------------

		echo "<table style=\"width:90%\">";
		echo "<thead>
					<th style=\"width:5%\">No</th>
					<th style=\"width:30%\">Nama Mahasiswa</th>
					<th style=\"width:10%\">NIM</th>
					<th style=\"width:5%\">Angkatan</th>
					<th style=\"width:50%\">Refleksi Diri</th>
					</thead>";
		$id = 1;
		$kelas = "ganjil";
		while ($nim_mhsw = mysqli_fetch_array($mhsw))
		{
			$data_evaluasi = mysqli_query($con,"SELECT `refleksi` FROM `evaluasi_stase` WHERE `nim`='$nim_mhsw[nim]' AND `stase`='$id_stase'");
			$jml_data_evaluasi = mysqli_num_rows($data_evaluasi);
			$refleksi_diri = mysqli_fetch_array($data_evaluasi);
			echo "<tr class=$kelas>";
				echo "<td>$id</td>";
				$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `nama`,`angkatan` FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw[nim]'"));
				echo "<td>$data_mhsw[nama]</td>";
				echo "<td>$nim_mhsw[nim]</td>";
				echo "<td align=center>$data_mhsw[angkatan]</td>";
				if ($jml_data_evaluasi>0) echo "<td>$refleksi_diri[refleksi]</td>";
				else echo "<td><i>Belum Mengisi Evaluasi</i></td>";
			echo "</tr>";
			if ($kelas=="ganjil") $kelas="genap";
			else $kelas="ganjil";
			$id++;
		}
		echo "</table>";

		echo "</fieldset>";

	}
		else
		echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
	}
?>

<script src="jquery.min.js"></script>
<script type="text/javascript" src="freezeheader/js/jquery.freezeheader.js"></script>
<script>
  $(document).ready(function(){
	   $("#freeze").freezeHeader();
		 $("#freeze1").freezeHeader();
  });
</script>

<!--</body></html>-->
</BODY>
</HTML>
