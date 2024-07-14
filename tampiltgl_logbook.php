<HTML>
<head>
	<link rel="stylesheet" href="menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="mytable_color.css" type="text/css" media="screen" />
	<meta name="viewport" content="width=device-width, maximum-scale=1">
	</style>

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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND $_COOKIE['level']==5)
	{
		if ($_COOKIE['level']==5) {include "menu5.php";}

		echo "<div class=\"text_header\">CEK LOGBOOK</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">CEK LOGBOOK KEPANITERAAN (STASE)</font></h4>";

		$id_stase = $_GET['id'];
		$tgl_kegiatan = $_GET['tglkeg'];
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		$stase_id = "stase_".$id_stase;
		$data_stase_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'"));

		echo "<table style=\"border:collapse;\">";
			echo "<tr><td>Kepaniteraan (stase)</td><td>: $data_stase[kepaniteraan]</td></tr>";
			$tgl_mulai = tanggal_indo($data_stase_mhsw[tgl_mulai]);
			$tgl_selesai = tanggal_indo($data_stase_mhsw[tgl_selesai]);
			$tgl_isi = tanggal_indo($tgl_kegiatan);
			echo "<tr><td>Tanggal mulai kepaniteraan (stase)</td><td>: $tgl_mulai</td></tr>";
			echo "<tr><td>Tanggal selesai kepaniteraan (stase)</td><td>: $tgl_selesai</td></tr>";
			echo "<tr><td>Tanggal cek log book</td><td>: $tgl_isi</td></tr>";
			echo "<tr><td>Status pengisian log book</td>";
			switch ($data_stase_mhsw[status])
			{
				case '0':
					{
						$status = "BELUM AKTIF/DILAKSANAKAN";
						echo "<td>: <font style=\"color:blue\">$status</font></td></tr>";
					}
				break;
				case '1':
					{
						$status = "SEDANG BERJALAN (AKTIF)";
						echo "<td>: <font style=\"color:green\">$status</font></td></tr>";
					}
				break;
				case '2':
					{
						$status = "SUDAH SELESAI/TERLEWATI";
						echo "<td>: <font style=\"color:red\">$status</font></td></tr>";
					}
				break;
			}

		echo "</table><br>";

		echo "</center><br><a href=\"#penyakit\"><i>Cek Jurnal Penyakit</i></a><br>";
		echo "<a href=\"#trampil\"><i>Cek Jurnal Ketrampilan Klinik</i></a><br>";
		echo "<a href=\"#evaluasi\"><i>Cek Evaluasi Diri dan Rencana Esok Hari</i></a><br><br>";
		//---------------------

		echo "<a id=\"penyakit\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Jurnal Penyakit</font></a><br><br>";
		$log_penyakit = mysqli_query($con,"SELECT * FROM `jurnal_penyakit` WHERE `stase`='$id_stase' AND `tanggal`='$tgl_kegiatan' AND `nim`='$_COOKIE[user]' ORDER BY `jam_awal`");
		echo "<table style=\"width:100%\" id=\"freeze\">";
		echo "<thead>
					<th style=\"width:8%\">Jam</th>
					<th style=\"width:12%\">Lokasi</th>
					<th style=\"width:25%\">Kegiatan (Level Penguasaan)</th>
					<th style=\"width:25%\">Penyakit (Level SKDI/<br>Kepmenkes/IPSG/Muatan Lokal)</th>
					<th style=\"width:20%\">Dosen/Residen</th>
					<th style=\"width:10%\">Status</th>
					</thead>";

		$kelas = "ganjil";
		$no=0;
		while ($data=mysqli_fetch_array($log_penyakit))
		{
			if ($kelas=="ganjil") echo "<tr class=\"ganjil\">";
			else echo "<tr class=\"genap\">";
			echo "<td>$data[jam_awal] - $data[jam_akhir]</td>";
			$lokasi = mysqli_fetch_array(mysqli_query($con,"SELECT `lokasi` FROM `lokasi` WHERE `id`='$data[lokasi]'"));
			echo "<td>$lokasi[lokasi]</td>";
			$kegiatan = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kegiatan` WHERE `id`='$data[kegiatan]'"));
			echo "<td>$kegiatan[kegiatan] ($kegiatan[level]).</td>";

			//Penyakit
			echo "<td>";
			$penyakit1 = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `daftar_penyakit` WHERE `id`='$data[penyakit1]'"));
			$penyakit1_kapital = strtoupper($penyakit1[penyakit]);
			echo "$penyakit1_kapital (Level: $penyakit1[skdi_level]/$penyakit1[k_level]/$penyakit1[ipsg_level]/$penyakit1[kml_level]).<p>";

			if ($data[penyakit2]!="")
			{
				$penyakit2 = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `daftar_penyakit` WHERE `id`='$data[penyakit2]'"));
				$penyakit2_kapital = strtoupper($penyakit2[penyakit]);
				echo "<font style=\"color:brown\">$penyakit2_kapital (Level: $penyakit2[skdi_level]/$penyakit2[k_level]/$penyakit2[ipsg_level]/$penyakit2[kml_level]).</font><p>";
			}
			if ($data[penyakit3]!="")
			{
				$penyakit3 = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `daftar_penyakit` WHERE `id`='$data[penyakit3]'"));
				$penyakit3_kapital = strtoupper($penyakit3[penyakit]);
				echo "<font style=\"color:blue\">$penyakit3_kapital (Level: $penyakit3[skdi_level]/$penyakit3[k_level]/$penyakit3[ipsg_level]/$penyakit3[kml_level]).</font><p>";
			}
			if ($data[penyakit4]!="")
			{
				$penyakit4 = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `daftar_penyakit` WHERE `id`='$data[penyakit4]'"));
				$penyakit4_kapital = strtoupper($penyakit4[penyakit]);
				echo "<font style=\"color:green\">$penyakit4_kapital (Level: $penyakit4[skdi_level]/$penyakit4[k_level]/$penyakit4[ipsg_level]/$penyakit4[kml_level]).</font><p>";
			}
			echo "</td>";
			//----------------

			$dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data[dosen]'"));
			echo "<td>$dosen[nama], $dosen[gelar] ($dosen[nip])</td>";
			echo "<td align=center>";
			if ($data[status]=='0')
			{
				echo "<font style=\"color:red\">Not Approved</font>";
			}
			else echo "<font style=\"color:green\">Approved</font>";
			echo "</td>";
			echo "</tr>";
			if ($kelas=="ganjil") $kelas="genap";
			else $kelas="ganjil";
			$no++;
		}
		if ($no==0) echo "<tr><td colspan=\"8\" align=\"center\"><i><< EMPTY >></i><br></td></tr>";
		echo "</table><br>";


		//---------------------

		echo "</center><br><b><a id=\"trampil\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Jurnal Ketrampilan Klinik</font></a></b><br><br>";
		$log_ketrampilan = mysqli_query($con,"SELECT * FROM `jurnal_ketrampilan` WHERE `stase`='$id_stase' AND `tanggal`='$tgl_kegiatan' AND `nim`='$_COOKIE[user]' ORDER BY `jam_awal`");
		echo "<table style=\"width:100%\" id=\"freeze1\">";
		echo "<thead>
					<th style=\"width:8%\">Jam</th>
					<th style=\"width:12%\">Lokasi</th>
					<th style=\"width:25%\">Kegiatan (Level Penguasaan)</th>
					<th style=\"width:25%\">Ketrampilan (Level SKDI/<br>Kepmenkes/IPSG/Muatan Lokal)</th>
					<th style=\"width:20%\">Dosen/Residen</th>
					<th style=\"width:10%\">Status</th>
					</thead>";

		$kelas = "ganjil";
		$no=0;
		while ($data2=mysqli_fetch_array($log_ketrampilan))
		{
			if ($kelas=="ganjil") echo "<tr class=\"ganjil\">";
			else echo "<tr class=\"genap\">";
			echo "<td>$data2[jam_awal] - $data2[jam_akhir]</td>";
			$lokasi = mysqli_fetch_array(mysqli_query($con,"SELECT `lokasi` FROM `lokasi` WHERE `id`='$data2[lokasi]'"));
			echo "<td>$lokasi[lokasi]</td>";
			$kegiatan = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kegiatan` WHERE `id`='$data2[kegiatan]'"));
			echo "<td>$kegiatan[kegiatan] ($kegiatan[level]).</td>";

			//Ketrampilan
			echo "<td>";
			$ketrampilan1 = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `daftar_ketrampilan` WHERE `id`='$data2[ketrampilan1]'"));
			$ketrampilan1_kapital = strtoupper($ketrampilan1[ketrampilan]);
			echo "$ketrampilan1_kapital (Level: $ketrampilan1[skdi_level]/$ketrampilan1[k_level]/$ketrampilan1[ipsg_level]/$ketrampilan1[kml_level]).<p>";

			if ($data2[ketrampilan2]!="")
			{
				$ketrampilan2 = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `daftar_ketrampilan` WHERE `id`='$data2[ketrampilan2]'"));
				$ketrampilan2_kapital = strtoupper($ketrampilan2[ketrampilan]);
				echo "<font style=\"color:brown\">$ketrampilan2_kapital (Level: $ketrampilan2[skdi_level]/$ketrampilan2[k_level]/$ketrampilan2[ipsg_level]/$ketrampilan2[kml_level]).</font><p>";
			}
			if ($data2[ketrampilan3]!="")
			{
				$ketrampilan3 = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `daftar_ketrampilan` WHERE `id`='$data2[ketrampilan3]'"));
				$ketrampilan3_kapital = strtoupper($ketrampilan3[ketrampilan]);
				echo "<font style=\"color:blue\">$ketrampilan3_kapital (Level: $ketrampilan3[skdi_level]/$ketrampilan3[k_level]/$ketrampilan3[ipsg_level]/$ketrampilan3[kml_level]).</font><p>";
			}
			if ($data2[ketrampilan4]!="")
			{
				$ketrampilan4 = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `daftar_ketrampilan` WHERE `id`='$data2[ketrampilan4]'"));
				$ketrampilan4_kapital = strtoupper($ketrampilan4[ketrampilan]);
				echo "<font style=\"color:green\">$ketrampilan4_kapital (Level: $ketrampilan4[skdi_level]/$ketrampilan4[k_level]/$ketrampilan4[ipsg_level]/$ketrampilan4[kml_level]).</font><p>";
			}
			echo "</td>";
			//------------------

			$dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data2[dosen]'"));
			echo "<td>$dosen[nama], $dosen[gelar] ($dosen[nip])</td>";
			echo "<td align=center>";
			if ($data2[status]=='0')
			{
				echo "<font style=\"color:red\">Not Approved</font>";
			}
			else echo "<font style=\"color:green\">Approved</font>";
			echo "</td>";
			echo "</tr>";
			if ($kelas=="ganjil") $kelas="genap";
			else $kelas="ganjil";
			$no++;
		}
		if ($no==0) echo "<tr><td colspan=\"6\" align=\"center\"><i><< EMPTY >></i><br></td></tr>";
		echo "</table><br><br>";


		//---------------------

		$jml_evaluasi = mysqli_num_rows(mysqli_query($con,"SELECT * FROM `evaluasi` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase' AND `tanggal`='$tgl_kegiatan'"));
		if ($jml_evaluasi>0)
		{
			$evaluasi = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `evaluasi` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase' AND `tanggal`='$tgl_kegiatan'"));
			$id_eval = $evaluasi['id'];
			$eval = $evaluasi['evaluasi'];
			$renc = $evaluasi['rencana'];
		}
		else
		{
			$id_eval = "0";
			$eval = "<< EMPTY >>";
			$renc = "<< EMPTY >>";
		}
		echo "<center><input type=\"hidden\" name=\"id_eval\" value=\"$id_eval\">";
		echo "<br><a id=\"evaluasi\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Evaluasi Diri dan Rencana Esok Hari</font></a><br><br>";
		echo "<table border=\"1\" style=\"width:75%\">";
		echo "<tr>";
		echo "<td class=\"ganjil\" style=\"padding:5px 5px 5px 5px\"><font style=\"font-size:0.875em;font-weight:bold\">Evaluasi Diri:</font><br>";
		echo "<br><i>$eval</i><br></td></tr>";
		echo "<tr>";
		echo "<td class=\"genap\" style=\"padding:5px 5px 5px 5px\"><font style=\"font-size:0.875em;font-weight:bold\">Rencana Esok Hari:</font><br>";
		echo "<br><i>$renc</i><br></td></tr>";
		echo "</table>";

		echo "</form>";

		echo "<center><br><a href=\"#top\"><i>Goto top</i></a><br><br>";
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
