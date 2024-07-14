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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND ($_COOKIE['level']==1 OR $_COOKIE['level']==2 OR $_COOKIE['level']==3))
	{
		if ($_COOKIE['level']==1) {include "menu1.php";}
	  if ($_COOKIE['level']==2) {include "menu2.php";}
	  if ($_COOKIE['level']==3) {include "menu3.php";}


		echo "<div class=\"text_header\">REKAP INDIVIDU LOGBOOK</div>";
		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">REKAP INDIVIDU JURNAL KEPANITERAAN (STASE)</font></h4><br>";
		$mhsw_nim = $_GET[user_name];
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$mhsw_nim'"));

		echo "<table border=\"1\">";
		echo "<tr class=\"ganjil\">";
		echo "<td colspan=\"2\" align=center>Nama Mahasiswa: $data_mhsw[nama]<br>NIM: $data_mhsw[nim]</td>";
		echo "</tr>";

		$stase = mysqli_query($con,"SELECT * FROM `kepaniteraan` ORDER BY `id` ASC");

		$kelas_baris = "ganjil";
		$no = 1;

		while ($data_stase = mysqli_fetch_array($stase))
		{
			$stase_id = "stase_".$data_stase[id];
			$jml_data = mysqli_num_rows(mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$mhsw_nim'"));
			if ($no==1)
			{
				echo "<tr class=\"$kelas_baris\">";
				$baris++;
			}
			if ($jml_data>0)
			{
			$data = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$mhsw_nim'"));
			$tglmulai = tanggal_indo($data[tgl_mulai]);
			$tglselesai = tanggal_indo($data[tgl_selesai]);
			if (strtotime($data[tgl_mulai])<=strtotime($tgl) and strtotime($tgl)<=strtotime($data[tgl_selesai]))
			{
				echo "<td align=center style=\"width:400px\"><a href=\"rekap_indstase_admin.php?id=$data_stase[id]&nim=$mhsw_nim\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
				echo "<font style=\"color:green;font-size:0.75em\"><i>Skedul: $tglmulai - $tglselesai<br>(Aktif)</i></font></td>";
			}
			if (strtotime($tgl)>strtotime($data[tgl_selesai]))
			{
				echo "<td align=center style=\"width:400px\"><a href=\"rekap_indstase_admin.php?id=$data_stase[id]&nim=$mhsw_nim\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
				echo "<font style=\"color:blue;font-size:0.75em\"><i>Skedul: $tglmulai - $tglselesai<br>(Sudah Terlewat)</i></font></td>";
			}
			if (strtotime($tgl)<strtotime($data[tgl_mulai]))
			{
				echo "<td align=center style=\"width:400px\"><a href=\"rekap_indstase_admin.php?id=$data_stase[id]&nim=$mhsw_nim\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
				echo "<font style=\"color:grey;font-size:0.75em\"><i>Skedul: $tglmulai - $tglselesai<br>(Belum aktif)</i></font></td>";
			}
			}
			else echo "<td align=center style=\"width:400px\"><a href=\"rekap_indstase_admin.php?id=$data_stase[id]&nim=$mhsw_nim\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font><font style=\"color:red;font-size:0.75em\"><i>Skedul: - <br>(Belum terjadwal)</i></font></td>";

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
