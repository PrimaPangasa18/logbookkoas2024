<HTML>
<head>
	<link rel="stylesheet" href="menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="mytable_color.css" type="text/css" media="screen" />
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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND $_COOKIE['level']==5)
	{
		if ($_COOKIE['level']==5) {include "menu5.php";}

		echo "<div class=\"text_header\">ISI LOGBOOK</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">ISI LOGBOOK KEPANITERAAN (STASE)</font></h4>";

		$id_stase = $_GET['id'];
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
		$stase_id = "stase_".$id_stase;
		$data_stase_mhsw = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'");
		$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);
		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		echo "<input type=\"hidden\" name=\"id_stase\" value=\"$id_stase\">";
		echo "<table style=\"border:collapse;\">";
			echo "<tr><td>Kepaniteraan (stase)</td><td>: $data_stase[kepaniteraan]</td></tr>";
			$tgl_mulai = tanggal_indo($datastase_mhsw[tgl_mulai]);
			$mulai = date_create($datastase_mhsw[tgl_mulai]);
			$tgl_selesai = tanggal_indo($datastase_mhsw[tgl_selesai]);
			$tgl_isi = tanggal_indo($tgl);
			$sekarang = date_create($tgl);
			$jmlhari_stase = $data_stase[hari_stase];
			$hari_skrg = date_diff($mulai,$sekarang);
			$jmlhari_skrg = $hari_skrg->days+1;


			echo "<tr><td>Tanggal mulai kepaniteraan (stase)</td><td>: $tgl_mulai</td></tr>";
			echo "<tr><td>Tanggal selesai kepaniteraan (stase)</td><td>: $tgl_selesai</td></tr>";
			echo "<tr><td>Tanggal pengisian log book</td><td>: $tgl_isi</td></tr>";
			echo "<tr><td>Hari pengisian log book</td><td>: Hari ke-$jmlhari_skrg dari $jmlhari_stase hari masa kepaniteraan (stase)</td></tr>";
		echo "</table><br>";

		echo "</center><br><a href=\"#penyakit\"><i>Pengisian Jurnal Penyakit</i></a><br>";
		echo "<a href=\"#trampil\"><i>Pengisian Jurnal Ketrampilan Klinik</i></a><br>";
		echo "<a href=\"#evaluasi\"><i>Pengisian Evaluasi Diri dan Rencana Esok Hari</i></a><br><br>";
		//---------------------

		echo "<a id=\"penyakit\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Jurnal Penyakit</font></a><br><br>";
		$log_penyakit = mysqli_query($con,"SELECT * FROM `jurnal_penyakit` WHERE `stase`='$id_stase' AND `tanggal`='$tgl' AND `nim`='$_COOKIE[user]' ORDER BY `jam_awal`");
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
			echo "<font style=\"color:black\">$penyakit1_kapital (Level: $penyakit1[skdi_level]/$penyakit1[k_level]/$penyakit1[ipsg_level]/$penyakit1[kml_level]).</font><p>";

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
			echo "<td>$dosen[nama], $dosen[gelar] ($dosen[nip])<p>";
			if ($data[status]=='0')
			{
				echo "<a href=\"approve.php?id=$data[id]\"><input type=\"button\" name=\"approve\".\"$data[id]\" style=\"color:red\" value=\"APPROVE\"></a>";
			}
			echo "</td>";
			echo "<td align=center>";
			if ($data[status]=='0')
			{
				echo "<a href=\"edit_isi.php?id=$data[id]\"><input type=\"button\" class=\"submit2\" name=\"edit\".\"$data[id]\" value=\"EDIT\"></a><p>";
				echo "<a href=\"hapus_isi.php?id=$data[id]\"><input type=\"button\" class=\"submit2\" name=\"hapus\".\"$data[id]\" value=\"HAPUS\"></a>";
			}
			else echo "<font style=\"color:green\">Approved</font>";
			echo "</td>";
			echo "</tr>";
			if ($kelas=="ganjil") $kelas="genap";
			else $kelas="ganjil";
		}
		echo "</table>";

		echo "<br><center><a href=\"tambah_logbook.php?id=$id_stase\"><input type=\"button\" class=\"submit1\" name=\"tambah\" value=\"TAMBAH\"></a><br>";

		//---------------------

		echo "</center><br><br><b><a id=\"trampil\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Jurnal Ketrampilan Klinik</font></a></b><br><br>";
		$log_ketrampilan = mysqli_query($con,"SELECT * FROM `jurnal_ketrampilan` WHERE `stase`='$id_stase' AND `tanggal`='$tgl' AND `nim`='$_COOKIE[user]' ORDER BY `jam_awal`");
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
			echo "<font style=\"color:black\">$ketrampilan1_kapital (Level: $ketrampilan1[skdi_level]/$ketrampilan1[k_level]/$ketrampilan1[ipsg_level]/$ketrampilan1[kml_level]).</font><p>";

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
			echo "<td>$dosen[nama], $dosen[gelar] ($dosen[nip])<p>";
			if ($data2[status]=='0')
			{
				echo "<a href=\"approve2.php?id=$data2[id]\"><input type=\"button\" name=\"approve\".\"$data2[id]\" style=\"color:red\" value=\"APPROVE\"></a>";
			}
			echo "</td>";
			echo "<td align=center>";
			if ($data2[status]=='0')
			{
				echo "<a href=\"edit_isi2.php?id=$data2[id]\"><input type=\"button\" class=\"submit2\" name=\"edit\".\"$data2[id]\" value=\"EDIT\"></a><p>";
				echo "<a href=\"hapus_isi2.php?id=$data2[id]\"><input type=\"button\" class=\"submit2\" name=\"hapus\".\"$data2[id]\" value=\"HAPUS\"></a>";
			}
			else echo "<font style=\"color:green\">Approved</font>";
			echo "</td>";
			echo "</tr>";
			if ($kelas=="ganjil") $kelas="genap";
			else $kelas="ganjil";
		}
		echo "</table>";

		echo "<br><center><a href=\"tambah_logbook2.php?id=$id_stase\"><input type=\"button\" class=\"submit1\" name=\"tambah2\" value=\"TAMBAH\"></a><br><br>";

		//---------------------

		$jml_evaluasi = mysqli_num_rows(mysqli_query($con,"SELECT * FROM `evaluasi` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase' AND `tanggal`='$tgl'"));
		if ($jml_evaluasi>0)
		{
			$evaluasi = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `evaluasi` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase' AND `tanggal`='$tgl'"));
			$id_eval = $evaluasi['id'];
			$eval = $evaluasi['evaluasi'];
			$renc = $evaluasi['rencana'];
		}
		else
		{
			$id_eval = "0";
			$eval = "";
			$renc = "";
		}
		echo "<input type=\"hidden\" name=\"id_eval\" value=\"$id_eval\">";
		echo "<br><a id=\"evaluasi\"><font style=\"font-size:1.125em;font-weight:bold;font-family:GEORGIA\">Evaluasi Diri dan Rencana Esok Hari</font></a><br><br>";
		if ($id_eval=="0") echo "<a class=\"blink\"><font style=\"font-size:0.875em;color:red;font-family:GEORGIA\"><i>Catatan Log-book Anda hari ini tidak akan masuk ke data base rekapitulasi<br>jika belum mengisi evaluasi diri dan rencana kegiatan esok hari</i></font></a><br><br>";
		else echo "<font style=\"font-size:0.875em;color:green;font-family:GEORGIA\"><i>Evaluasi diri dan rencana esok hari sudah diisi.<br>Anda bisa menggantinya dengan mengedit text area di bawah dan tekan tombol ENTRY.</i></font><br><br>";
		echo "<table border=\"1\" style=\"width:75%\">";
		echo "<tr>";
		echo "<td style=\"padding:5px 5px 5px 5px\"><font style=\"font-size:0.875em;font-weight:bold\">Evaluasi Diri:</font><br>";
		echo "<font style=\"font-size:0.75em\"><i>(maksimal 500 karakter, termasuk spasi)</i></font><br>";
		?>
		<textarea class="ckeditor" name="evaluasi" required autofocus="0"><?php echo "$eval"; ?>
    </textarea>
		<?php
		echo "<br></td></tr>";
		echo "<tr>";
		echo "<td style=\"padding:5px 5px 5px 5px\"><font style=\"font-size:0.875em;font-weight:bold\">Rencana Esok Hari:</font><br>";
		echo "<font style=\"font-size:0.75em\"><i>(maksimal 500 karakter, termasuk spasi)</i></font><br>";
		?>
		<textarea class="ckeditor" name="rencana" required autofocus="0"><?php echo "$renc"; ?>
    </textarea>
		<?php
		echo "<br></td></tr>";
		echo "<tr>";
		echo "<td><br><center><input type=\"submit\" class=\"submit1\" name=\"entry\" value=\"ENTRY\"><br><br>";
		echo "</td>";
		echo "</tr>";
		echo "</table>";

		echo "</form>";

		echo "<center><br><a href=\"#top\"><i>Goto top</i></a><br><br>";
		echo "</fieldset>";

		if ($_POST['entry']=="ENTRY")
		{
			$angkatan_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT `angkatan`,`grup` FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
			if ($_POST['id_eval']=="0")
			{
				$insert_evaluasi = mysqli_query($con,"INSERT INTO `evaluasi`
					(	`nim`, `angkatan`,`grup`,`stase`, `tanggal`,
						`evaluasi`, `rencana`)
					VALUES
					(	'$_COOKIE[user]','$angkatan_mhsw[angkatan]','$angkatan_mhsw[grup]','$_POST[id_stase]','$tgl',
						'$_POST[evaluasi]','$_POST[rencana]')");
			}
			else
			{
				$update_evaluasi = mysqli_query($con,"UPDATE `evaluasi`
					SET `evaluasi`='$_POST[evaluasi]',`rencana`='$_POST[rencana]' WHERE `id`='$_POST[id_eval]'");
			}
			$update_penyakit = mysqli_query($con,"UPDATE `jurnal_penyakit`
				SET `evaluasi`='1' WHERE `nim`='$_COOKIE[user]' AND `tanggal`='$tgl'");
			$update_ketrampilan = mysqli_query($con,"UPDATE `jurnal_ketrampilan`
				SET `evaluasi`='1' WHERE `nim`='$_COOKIE[user]' AND `tanggal`='$tgl'");

			echo "
				<script>
					window.location.href=\"edit_logbook.php?id=\"+\"$_POST[id_stase]\";
				</script>
				";

		}

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
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
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
