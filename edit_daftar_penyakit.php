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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND $_COOKIE['level']==2)
	{
		include "menu2.php";
		echo "<div class=\"text_header\">EDIT PENYAKIT</div>";
		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    	<legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">EDIT PENYAKIT</font></h4>";

		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		echo "<input type=\"hidden\" name=\"id\" value=\"$_GET[id]\">";
		$data_penyakit = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `daftar_penyakit` WHERE `id`='$_GET[id]'"));

		echo "<table style=\"width:75%\">";
		//Nama Penyakit
	  echo "<tr class=\"ganjil\">";
	  echo "<td class=\"td_mid\" style=\"width:30%\">Nama Penyakit</td>";
	  echo "<td class=\"td_mid\" style=\"width:70%\">";
	  echo "<textarea name=\"penyakit\" rows=\"1\" class=\"select_art\" style=\"border:0.5px solid grey;border-radius:5px;\">$data_penyakit[penyakit]</textarea>";
	  echo "</td>";
	  echo "</tr>";
		//Level Penyakit SKDI
		echo "<tr class=\"ganjil\">";
		echo "<td>Level Penyakit (SKDI) </td>";
		echo "<td>";
		echo "<select class=\"select_art\" name=\"skdi_level\" id=\"skdi_level\">";
		if ($data_penyakit[skdi_level]=='-') echo "<option value=\"-\"> - </option>";
		else echo "<option value=\"$data_penyakit[skdi_level]\">Level $data_penyakit[skdi_level]</option>";
		echo "<option value=\"-\"> - </option>";
		echo "<option value=\"1\">Level 1</option>";
		echo "<option value=\"2\">Level 2</option>";
		echo "<option value=\"3\">Level 3</option>";
		echo "<option value=\"3A\">Level 3A</option>";
		echo "<option value=\"3B\">Level 3B</option>";
		echo "<option value=\"4A\">Level 4A</option>";
		echo "<option value=\"MKM\">Masalah Kesehatan Masyarakat</option>";
		echo "<option value=\"U\">Ujian</option>";
		echo "</select>";
		echo "</td>";
		echo "</tr>";
		//Level Penyakit Kemenkes
		echo "<tr class=\"ganjil\">";
		echo "<td>Level Penyakit (Kepmenkes) </td>";
		echo "<td>";
		echo "<select class=\"select_art\" name=\"k_level\" id=\"k_level\">";
		if ($data_penyakit[k_level]=='-') echo "<option value=\"-\"> - </option>";
		else echo "<option value=\"$data_penyakit[k_level]\">Level $data_penyakit[k_level]</option>";
		echo "<option value=\"-\"> - </option>";
		echo "<option value=\"1\">Level 1</option>";
		echo "<option value=\"2\">Level 2</option>";
		echo "<option value=\"3\">Level 3</option>";
		echo "<option value=\"3A\">Level 3A</option>";
		echo "<option value=\"3B\">Level 3B</option>";
		echo "<option value=\"4A\">Level 4A</option>";
		echo "<option value=\"MKM\">Masalah Kesehatan Masyarakat</option>";
		echo "<option value=\"U\">Ujian</option>";
		echo "</select>";
		echo "</td>";
		echo "</tr>";
		//Level Penyakit IPSG
		echo "<tr class=\"ganjil\">";
		echo "<td>Level Penyakit (IPSG) </td>";
		echo "<td>";
		echo "<select class=\"select_art\" name=\"ipsg_level\" id=\"ipsg_level\">";
		if ($data_penyakit[ipsg_level]=='-') echo "<option value=\"-\"> - </option>";
		else echo "<option value=\"$data_penyakit[ipsg_level]\">Level $data_penyakit[ipsg_level]</option>";
		echo "<option value=\"-\"> - </option>";
		echo "<option value=\"1\">Level 1</option>";
		echo "<option value=\"2\">Level 2</option>";
		echo "<option value=\"3\">Level 3</option>";
		echo "<option value=\"3A\">Level 3A</option>";
		echo "<option value=\"3B\">Level 3B</option>";
		echo "<option value=\"4A\">Level 4A</option>";
		echo "<option value=\"MKM\">Masalah Kesehatan Masyarakat</option>";
		echo "<option value=\"U\">Ujian</option>";
		echo "</select>";
		echo "</td>";
		echo "</tr>";
		//Level Penyakit Muatan Lokal
		echo "<tr class=\"ganjil\">";
		echo "<td>Level Penyakit (Muatan Lokal) </td>";
		echo "<td>";
		echo "<select class=\"select_art\" name=\"kml_level\" id=\"kml_level\">";
		if ($data_penyakit[kml_level]=='-') echo "<option value=\"-\"> - </option>";
		else echo "<option value=\"$data_penyakit[kml_level]\">Level $data_penyakit[kml_level]</option>";
		echo "<option value=\"-\"> - </option>";
		echo "<option value=\"1\">Level 1</option>";
		echo "<option value=\"2\">Level 2</option>";
		echo "<option value=\"3\">Level 3</option>";
		echo "<option value=\"3A\">Level 3A</option>";
		echo "<option value=\"3B\">Level 3B</option>";
		echo "<option value=\"4A\">Level 4A</option>";
		echo "<option value=\"MKM\">Masalah Kesehatan Masyarakat</option>";
		echo "<option value=\"U\">Ujian</option>";
		echo "</select>";
		echo "</td>";
		echo "</tr>";
		//Stase
		echo "<tr class=\"ganjil\">";
		echo "<td>Kepaniteraan (Stase)</td>";
		echo "<td>";
			$daftar_stase = mysqli_query($con,"SELECT * FROM `kepaniteraan` ORDER BY `id`");
			echo "<table style=\"border:0;width:100%;box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.2);\">";
			echo "<tr><td colspan=2 align=center>Include</td><td align=center>Target <font style=\"color:red\">*</font></td></tr>";
			while ($data=mysqli_fetch_array($daftar_stase))
			{
				echo "<tr>";
				$nama_cek = "include_".$data[id];
				$target = "target_".$data[id];
				echo "<td align=center style=\"vertical-align:middle;width:5%\">";
				if ($data_penyakit[$nama_cek]=='1')
				{
					echo "<input type=\"checkbox\" name=\"$nama_cek\" value=\"1\" id=\"$nama_cek\" checked></td>";
					echo "<td style=\"vertical-align:middle;width:80%\">$data[kepaniteraan]</td>";
					echo "<td style=\"vertical-align:middle;width:15%\">";
					echo "<input type=\"text\" style=\"width:100%;text-align:center\" name=\"$target\" id=\"$target\" value=\"$data_penyakit[$target]\"></td>";
				}
				else
				{
					echo "<input type=\"checkbox\" name=\"$nama_cek\" value=\"1\" id=\"$nama_cek\"></td>";
					echo "<td style=\"vertical-align:middle;width:80%\">$data[kepaniteraan]</td>";
					echo "<td style=\"vertical-align:middle;width:15%\">";
					echo "<input type=\"text\" style=\"width:100%;text-align:center\" name=\"$target\" id=\"$target\"></td>";
				}
				echo "</tr>";
			}
			echo "</table>";
		echo "</td>";
		echo "</tr>";
		echo "<tr class=\"genap\">";
		echo "<td colspan=2><font style=\"color:red;font-size:0.85em\">*</font><font style=\"font-size:0.85em\"><i>Ctt: Bila tidak disi, maka target jumlah kegiatan adalah nol (0).</i></font></td>";
		echo "</tr>";

		echo "</table>";
		echo "<br><br><input type=\"submit\" class=\"submit1\" name=\"batal\" value=\"BATAL\" />";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" class=\"submit1\" name=\"hapus\" value=\"HAPUS\" />";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" class=\"submit1\" name=\"ubah\" value=\"UBAH\" /></form>";


		if ($_POST[batal]=="BATAL")
		{
			echo "
			<script>
				window.location.href=\"cari_penyakit.php\";
			</script>
			";
		}

		if ($_POST[hapus]=="HAPUS")
		{
			$delete_penyakit = mysqli_query($con,"DELETE FROM `daftar_penyakit` WHERE `id`='$_POST[id]'");
			echo "
			<script>
				window.location.href=\"cari_penyakit.php?status=HAPUS\";
			</script>
			";
		}

		if ($_POST[ubah]=="UBAH")
		{
			$daftar_stase = mysqli_query($con,"SELECT * FROM `kepaniteraan` ORDER BY `id`");
			while ($data=mysqli_fetch_array($daftar_stase))
			{
				$nama_cek = "include_".$data[id];
				$target = "target_".$data[id];
				if ($_POST[$nama_cek]=='') $_POST[$nama_cek]='0';
				if ($_POST[$target]=='') $_POST[$target]='0';
			}
			$update_penyakit = mysqli_query($con,"UPDATE `daftar_penyakit`
				SET
				`penyakit`='$_POST[penyakit]',`skdi_level`='$_POST[skdi_level]',`k_level`='$_POST[k_level]',
				`ipsg_level`='$_POST[ipsg_level]',`kml_level`='$_POST[kml_level]',
				`include_M091`='$_POST[include_M091]',`target_M091`='$_POST[target_M091]',
				`include_M105`='$_POST[include_M105]',`target_M105`='$_POST[target_M105]',
				`include_M092`='$_POST[include_M092]',`target_M092`='$_POST[target_M092]',
				`include_M093`='$_POST[include_M093]',`target_M093`='$_POST[target_M093]',
				`include_M095`='$_POST[include_M095]',`target_M095`='$_POST[target_M095]',
				`include_M102`='$_POST[include_M102]',`target_M102`='$_POST[target_M102]',
				`include_M101`='$_POST[include_M101]',`target_M101`='$_POST[target_M101]',
				`include_M104`='$_POST[include_M104]',`target_M104`='$_POST[target_M104]',
				`include_M103`='$_POST[include_M103]',`target_M103`='$_POST[target_M103]',
				`include_M106`='$_POST[include_M106]',`target_M106`='$_POST[target_M106]',
				`include_M094`='$_POST[include_M094]',`target_M094`='$_POST[target_M094]',
				`include_M114`='$_POST[include_M114]',`target_M114`='$_POST[target_M114]',
				`include_M112`='$_POST[include_M112]',`target_M112`='$_POST[target_M112]',
				`include_M113`='$_POST[include_M113]',`target_M113`='$_POST[target_M113]',
				`include_M111`='$_POST[include_M111]',`target_M111`='$_POST[target_M111]',
				`include_M121`='$_POST[include_M121]',`target_M121`='$_POST[target_M121]'
				WHERE `id`='$_POST[id]'");

			echo "
			<script>
				window.location.href=\"cari_penyakit.php?status=UBAH&id=\"+\"$_POST[id]\";
			</script>
			";

		}

		echo "<br><br></fieldset>";



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

<!--</body></html>-->
</BODY>
</HTML>
