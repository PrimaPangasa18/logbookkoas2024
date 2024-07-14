<HTML>
<head>
	<link rel="stylesheet" href="menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="mytable.css" type="text/css" media="screen" />
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

		echo "<div class=\"text_header\">PENILAIAN BAGIAN / KEPANITERAAN (STASE) LOGBOOK</div>";
		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">PENILAIAN BAGIAN / KEPANITERAAN (STASE)</font></h4><br>";

		$sekarang = date_create($tgl);
		$stase = mysqli_query($con,"SELECT * FROM `kepaniteraan` ORDER BY `id` ASC");

		$kelas_baris = "ganjil";
		$no = 1;
		echo "<table border=\"1\">";
		while ($data_stase = mysqli_fetch_array($stase))
		{
			$stase_id = "stase_".$data_stase[id];
			$jml_data = mysqli_num_rows(mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'"));
			if ($no==1)
			{
				echo "<tr class=\"$kelas_baris\">";
				$baris++;
			}
			if ($jml_data>0)
			{
				$data = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'"));
				$tglmulai = tanggal_indo($data[tgl_mulai]);
				$tglselesai = tanggal_indo($data[tgl_selesai]);
				if ($data_stase[id]=="M091") echo "<td align=center style=\"width:400px\"><a href=\"bag_ipd/penilaian_ipd.php\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
				if ($data_stase[id]=="M092") echo "<td align=center style=\"width:400px\"><a href=\"bag_neuro/penilaian_neuro.php\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
				if ($data_stase[id]=="M093") echo "<td align=center style=\"width:400px\"><a href=\"bag_psikiatri/penilaian_psikiatri.php\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
				if ($data_stase[id]=="M094") echo "<td align=center style=\"width:400px\"><a href=\"bag_ikfr/penilaian_ikfr.php\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
				if ($data_stase[id]=="M095") echo "<td align=center style=\"width:400px\"><a href=\"bag_ikmkp/penilaian_ikmkp.php\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
				//if ($data_stase[id]=="M096") echo "<td align=center style=\"width:400px\"><a href=\"bag_ijp/penilaian_ijp.php\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
				if ($data_stase[id]=="M096") echo "<td align=center style=\"width:400px\"><a href=\"/\" onclick=\"return false;\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:grey\">$data_stase[kepaniteraan]</a><br></font>";
				if ($data_stase[id]=="M101") echo "<td align=center style=\"width:400px\"><a href=\"bag_bedah/penilaian_bedah.php\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
				if ($data_stase[id]=="M102") echo "<td align=center style=\"width:400px\"><a href=\"bag_anestesi/penilaian_anestesi.php\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
				if ($data_stase[id]=="M103") echo "<td align=center style=\"width:400px\"><a href=\"bag_radiologi/penilaian_radiologi.php\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
				if ($data_stase[id]=="M104") echo "<td align=center style=\"width:400px\"><a href=\"bag_mata/penilaian_mata.php\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
				if ($data_stase[id]=="M105") echo "<td align=center style=\"width:400px\"><a href=\"bag_thtkl/penilaian_thtkl.php\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
				if ($data_stase[id]=="M106") echo "<td align=center style=\"width:400px\"><a href=\"bag_ikgm/penilaian_ikgm.php\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
				if ($data_stase[id]=="M111") echo "<td align=center style=\"width:400px\"><a href=\"bag_obsgyn/penilaian_obsgyn.php\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
				if ($data_stase[id]=="M112") echo "<td align=center style=\"width:400px\"><a href=\"bag_forensik/penilaian_forensik.php\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
				if ($data_stase[id]=="M113") echo "<td align=center style=\"width:400px\"><a href=\"bag_ika/penilaian_ika.php\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
				if ($data_stase[id]=="M114") echo "<td align=center style=\"width:400px\"><a href=\"bag_kulit/penilaian_kulit.php\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
				if ($data_stase[id]=="M121")
				{
					echo "<td align=center style=\"width:400px\"><a href=\"bag_kompre/penilaian_kompre.php\" title=\"Penilaian Komprehensip\"><font style=\"color:green\">Komprehensip</font></a> dan ";
					echo "<a href=\"bag_kdk/penilaian_kdk.php\" title=\"Penilaian Kedokteran Keluarga\"><font style=\"color:green\">Kedokteran Keluarga</font></a><br>";
				}
				echo "<font style=\"font-size:0.75em\"><i>Skedul: $tglmulai - $tglselesai</i></font></td>";
			}
			else echo "<td align=center style=\"width:400px\"><a href=\"/\" onclick=\"return false;\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:grey\">$data_stase[kepaniteraan]</a><br></font><font style=\"color:red;font-size:0.75em\"><i>Skedul: - (Belum terjadwal)</i></font></td>";

			if ($no==2)
			{
				echo "</tr>";
				if ($kelas_baris=="ganjil") $kelas_baris = "genap";
				else $kelas_baris = "ganjil";
				$no=0;
			}
			$no++;
		}
		if ($no==2) echo "<td>&nbsp;</td></tr>";

		echo "</table><br>";

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



<!--</body></html>-->
</BODY>
</HTML>
