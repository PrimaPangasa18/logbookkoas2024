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
		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">REKAP EVALUASI AKHIR STASE - UMUM<br>EVALUASI DOSEN</font></h4>";

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
		$delete_dummy = mysqli_query($con,"DELETE FROM `dummy_evaluasi_dosen` WHERE `username`='$_COOKIE[user]'");

		while ($nim_mhsw = mysqli_fetch_array($mhsw))
		{
			$data_dosen = mysqli_query($con,"SELECT `dosen1`,`tatap_muka1`,`dosen2`,`tatap_muka2`,`dosen3`,`tatap_muka3` FROM `evaluasi_stase` WHERE `nim`='$nim_mhsw[nim]' AND `stase`='$id_stase'");
			$nip_dosen = mysqli_fetch_array($data_dosen);
			$jml_data_evaluasi = mysqli_num_rows($data_dosen);
			if ($jml_data_evaluasi>0)
			{
				$jml_dosen1 = mysqli_num_rows(mysqli_query($con,"SELECT `nip` FROM `dummy_evaluasi_dosen` WHERE `nip`='$nip_dosen[dosen1]' AND `username`='$_COOKIE[user]'"));
				if ($jml_dosen1<1)
				{
					$insert_dosen = mysqli_query($con,"INSERT INTO `dummy_evaluasi_dosen`
							(`nip`, `jml_review`,`jml_jam`,`username`)
							VALUES
							('$nip_dosen[dosen1]','1','$nip_dosen[tatap_muka1]','$_COOKIE[user]')");
				}
				else
				{
					$jml_review_asal = mysqli_fetch_array(mysqli_query($con,"SELECT `jml_review` FROM `dummy_evaluasi_dosen` WHERE `nip`='$nip_dosen[dosen1]' AND `username`='$_COOKIE[user]'"));
					$jml_jam_asal = mysqli_fetch_array(mysqli_query($con,"SELECT `jml_jam` FROM `dummy_evaluasi_dosen` WHERE `nip`='$nip_dosen[dosen1]' AND `username`='$_COOKIE[user]'"));
					$jml_review = $jml_review_asal[jml_review]+1;
					$jml_jam = $jml_jam_asal[jml_jam] + $nip_dosen[tatap_muka1];
					$update_dosen = mysqli_query($con,"UPDATE `dummy_evaluasi_dosen`
							SET `jml_review`='$jml_review',`jml_jam`='$jml_jam' WHERE `nip`='$nip_dosen[dosen1]' AND `username`='$_COOKIE[user]'");
				}
				$jml_dosen2 = mysqli_num_rows(mysqli_query($con,"SELECT `nip` FROM `dummy_evaluasi_dosen` WHERE `nip`='$nip_dosen[dosen2]' AND `username`='$_COOKIE[user]'"));
				if ($jml_dosen2<1)
				{
					$insert_dosen = mysqli_query($con,"INSERT INTO `dummy_evaluasi_dosen`
							(`nip`, `jml_review`,`jml_jam`,`username`)
							VALUES
							('$nip_dosen[dosen2]','1','$nip_dosen[tatap_muka2]','$_COOKIE[user]')");
				}
				else
				{
					$jml_review_asal = mysqli_fetch_array(mysqli_query($con,"SELECT `jml_review` FROM `dummy_evaluasi_dosen` WHERE `nip`='$nip_dosen[dosen2]' AND `username`='$_COOKIE[user]'"));
					$jml_jam_asal = mysqli_fetch_array(mysqli_query($con,"SELECT `jml_jam` FROM `dummy_evaluasi_dosen` WHERE `nip`='$nip_dosen[dosen2]' AND `username`='$_COOKIE[user]'"));
					$jml_review = $jml_review_asal[jml_review]+1;
					$jml_jam = $jml_jam_asal[jml_jam] + $nip_dosen[tatap_muka2];
					$update_dosen = mysqli_query($con,"UPDATE `dummy_evaluasi_dosen`
							SET `jml_review`='$jml_review',`jml_jam`='$jml_jam' WHERE `nip`='$nip_dosen[dosen2]' AND `username`='$_COOKIE[user]'");
				}
				$jml_dosen3 = mysqli_num_rows(mysqli_query($con,"SELECT `nip` FROM `dummy_evaluasi_dosen` WHERE `nip`='$nip_dosen[dosen3]' AND `username`='$_COOKIE[user]'"));
				if ($jml_dosen3<1)
				{
					$insert_dosen = mysqli_query($con,"INSERT INTO `dummy_evaluasi_dosen`
							(`nip`, `jml_review`, `jml_jam`,`username`)
							VALUES
							('$nip_dosen[dosen3]','1','$nip_dosen[tatap_muka3]','$_COOKIE[user]')");
				}
				else
				{
					$jml_review_asal = mysqli_fetch_array(mysqli_query($con,"SELECT `jml_review` FROM `dummy_evaluasi_dosen` WHERE `nip`='$nip_dosen[dosen3]' AND `username`='$_COOKIE[user]'"));
					$jml_jam_asal = mysqli_fetch_array(mysqli_query($con,"SELECT `jml_jam` FROM `dummy_evaluasi_dosen` WHERE `nip`='$nip_dosen[dosen3]' AND `username`='$_COOKIE[user]'"));
					$jml_review = $jml_review_asal[jml_review]+1;
					$jml_jam = $jml_jam_asal[jml_jam] + $nip_dosen[tatap_muka3];
					$update_dosen = mysqli_query($con,"UPDATE `dummy_evaluasi_dosen`
							SET `jml_review`='$jml_review',`jml_jam`='$jml_jam' WHERE `nip`='$nip_dosen[dosen3]' AND `username`='$_COOKIE[user]'");
				}
			}
		}

		$daftar_dosen = mysqli_query($con,"SELECT `nip` FROM `dummy_evaluasi_dosen` WHERE `username`='$_COOKIE[user]'");

		echo "<table style=\"width:90%\">";
		echo "<thead>
					<th style=\"width:5%\">No</th>
					<th style=\"width:45%\">Nama Dosen/Dokter</th>
					<th style=\"width:20%\">NIP/NIK</th>
					<th style=\"width:15%\">Jumlah Review</th>
					<th style=\"width:15%\">Jumlah Jam</th>
					</thead>";

		$id = 1;
		$kelas = "ganjil";
		while ($nip_dosen = mysqli_fetch_array($daftar_dosen))
		{
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$nip_dosen[nip]'"));
			echo "<tr class=$kelas>";
				echo "<td>$id</td>";
				echo "<td>$data_dosen[nama], $data_dosen[gelar]</td>";
				$jml_review = mysqli_fetch_array(mysqli_query($con,"SELECT `jml_review`,`jml_jam` FROM `dummy_evaluasi_dosen` WHERE `nip`='$nip_dosen[nip]' AND`username`='$_COOKIE[user]'"));
				echo "<td>$data_dosen[nip]</td>";
				echo "<td align=center>$jml_review[jml_review]</td>";
				echo "<td align=center>$jml_review[jml_jam]</td>";
			echo "</tr>";
			if ($kelas=="ganjil") $kelas="genap";
			else $kelas="ganjil";
			$id++;
		}

		echo "</table>";

		$delete_dummy = mysqli_query($con,"DELETE FROM `dummy_evaluasi_dosen` WHERE `username`='$_COOKIE[user]'");
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
