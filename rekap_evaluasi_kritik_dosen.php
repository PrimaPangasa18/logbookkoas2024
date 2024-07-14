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
		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">REKAP EVALUASI AKHIR STASE - UMUM<br>MASUKAN UNTUK DOSEN/DOKTER</font></h4>";

		$id_stase = $_GET[stase];
		$angkatan_filter = $_GET[angk];
		$stase_id = "stase_".$id_stase;
		$include_id = "include_".$id_stase;
		$target_id = "target_".$id_stase;
		$tgl_awal = $_GET[tglawal];
		$tgl_akhir = $_GET[tglakhir];
		$jml_review = $_GET[review];

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
		echo "<tr>";
		echo "<td>Jumlah Review</td><td>: $jml_review review</td>";
		echo "</tr>";
		echo "</table><br><br>";
		//------------------

    echo "<table style=\"width:90%\">";
		echo "<thead>
					<th style=\"width:5%\">No</th>
					<th style=\"width:30%\">Nama Dosen/Dokter</th>
					<th style=\"width:10%\">NIP/NIK</th>
					<th style=\"width:55%\">Komentar, Usul, Saran atau Masukan</th>
					</thead>";
		$id = 1;
		$kelas = "ganjil";
		while ($nim_mhsw = mysqli_fetch_array($mhsw))
		{
      //Dosen 1
			$data_dosen1 = mysqli_query($con,"SELECT `dosen1`,`komentar_dosen1` FROM `evaluasi_stase` WHERE `nim`='$nim_mhsw[nim]' AND `stase`='$id_stase'");
			$jml_data_dosen1 = mysqli_num_rows($data_dosen1);
			$dosen1 = mysqli_fetch_array($data_dosen1);
      if ($jml_data_dosen1>0)
      {
        echo "<tr class=$kelas>";
  				echo "<td>$id</td>";
  				$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$dosen1[dosen1]'"));
  				echo "<td>$data_dosen[nama], $data_dosen[gelar]</td>";
  				echo "<td>$data_dosen[nip]</td>";
  				echo "<td>$dosen1[komentar_dosen1]</td>";
  			echo "</tr>";
  			if ($kelas=="ganjil") $kelas="genap";
  			else $kelas="ganjil";
  			$id++;
      }

      //Dosen 2
			$data_dosen2 = mysqli_query($con,"SELECT `dosen2`,`komentar_dosen2` FROM `evaluasi_stase` WHERE `nim`='$nim_mhsw[nim]' AND `stase`='$id_stase'");
			$jml_data_dosen2 = mysqli_num_rows($data_dosen2);
			$dosen2 = mysqli_fetch_array($data_dosen2);
      if ($jml_data_dosen2>0)
      {
        echo "<tr class=$kelas>";
  				echo "<td>$id</td>";
  				$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$dosen2[dosen2]'"));
  				echo "<td>$data_dosen[nama], $data_dosen[gelar]</td>";
  				echo "<td>$data_dosen[nip]</td>";
  				echo "<td>$dosen2[komentar_dosen2]</td>";
  			echo "</tr>";
  			if ($kelas=="ganjil") $kelas="genap";
  			else $kelas="ganjil";
  			$id++;
      }

      //Dosen 3
			$data_dosen3 = mysqli_query($con,"SELECT `dosen3`,`komentar_dosen3` FROM `evaluasi_stase` WHERE `nim`='$nim_mhsw[nim]' AND `stase`='$id_stase'");
			$jml_data_dosen3 = mysqli_num_rows($data_dosen3);
			$dosen3 = mysqli_fetch_array($data_dosen3);
      if ($jml_data_dosen3>0)
      {
        echo "<tr class=$kelas>";
  				echo "<td>$id</td>";
  				$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$dosen3[dosen3]'"));
  				echo "<td>$data_dosen[nama], $data_dosen[gelar]</td>";
  				echo "<td>$data_dosen[nip]</td>";
  				echo "<td>$dosen3[komentar_dosen3]</td>";
  			echo "</tr>";
  			if ($kelas=="ganjil") $kelas="genap";
  			else $kelas="ganjil";
  			$id++;
      }
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
