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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']))
	{
		if ($_COOKIE['level']==1) {include "menu1.php";}
		if ($_COOKIE['level']==2) {include "menu2.php";}
		if ($_COOKIE['level']==3) {include "menu3.php";}
		if ($_COOKIE['level']==4) {include "menu4.php";}
		if ($_COOKIE['level']==5) {include "menu5.php";}
		if ($_COOKIE['level']==6) {include "menu6.php";}

		echo "<div class=\"text_header\">BIODATA</div>";
		echo "<br><br><br><fieldset class=\"fieldset_art\">
      <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
    echo "<center><font style=\"font-family:Georgia;text-shadow:1px 1px black;color:#006400;font-size:1.25em\">BIODATA MAHASISWA</font>";
    echo "<br><br><br>";
		if ($_COOKIE['level']!=5)
		{
			$data_nim = $_GET[nim];
			$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_nim'"));
		}
		else {
			$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
		}

		echo "<img src=\"foto/$data_mhsw[foto]\" style=\"width:150px;height:auto\">";
		echo "<br><br><br>";
		echo "<table>";
			echo "<tr class=\"ganjil\">";
				echo "<td style=\"width:150px\">Nama Peserta</td>";
				echo "<td style=\"width:500px\">$data_mhsw[nama]</td>";
			echo "</tr>";
			echo "<tr class=\"genap\">";
				echo "<td>NIM Peserta</td>";
				echo "<td>$data_mhsw[nim]</td>";
			echo "</tr>";
			echo "<tr class=\"ganjil\">";
				echo "<td>Tempat, Tgl Lahir</td>";
				$kota_lahir=mysqli_fetch_array(mysqli_query($con,"SELECT `kota` FROM `kota` WHERE `id_kota`='$data_mhsw[kota_lahir]'"));
				$tgl_lahir=tanggal_indo($data_mhsw[tanggal_lahir]);
				if ($data_mhsw[tanggal_lahir]=="2000-01-01") echo "<td>&nbsp</td>";
				else echo "<td>$kota_lahir[kota], $tgl_lahir</td>";
			echo "</tr>";
			echo "<tr class=\"genap\">";
				echo "<td>Alamat</td>";
				$kota_alamat=mysqli_fetch_array(mysqli_query($con,"SELECT `kota` FROM `kota` WHERE `id_kota`='$data_mhsw[kota_alamat]'"));
				$prop_alamat=mysqli_fetch_array(mysqli_query($con,"SELECT `prop` FROM `prop` WHERE `id_prop`='$data_mhsw[prop_alamat]'"));
				echo "<td>$data_mhsw[alamat] - $kota_alamat[kota] - $prop_alamat[prop] </td>";
			echo "</tr>";
			echo "<tr class=\"ganjil\">";
				echo "<td>No HP</td>";
				echo "<td>$data_mhsw[no_hp]</td>";
			echo "</tr>";
			echo "<tr class=\"genap\">";
				echo "<td>Email</td>";
				echo "<td>$data_mhsw[email]</td>";
			echo "</tr>";
			echo "<tr class=\"ganjil\">";
				echo "<td>Nama Orang Tua</td>";
				echo "<td>$data_mhsw[nama_ortu]</td>";
			echo "</tr>";
			echo "<tr class=\"genap\">";
				echo "<td>Alamat Orang Tua</td>";
				$kota_ortu=mysqli_fetch_array(mysqli_query($con,"SELECT `kota` FROM `kota` WHERE `id_kota`='$data_mhsw[kota_ortu]'"));
				$prop_ortu=mysqli_fetch_array(mysqli_query($con,"SELECT `prop` FROM `prop` WHERE `id_prop`='$data_mhsw[prop_ortu]'"));
				echo "<td>$data_mhsw[alamat_ortu] - $kota_ortu[kota] - $prop_ortu[prop] </td>";
			echo "</tr>";
			echo "<tr class=\"ganjil\">";
				echo "<td>No HP Orang Tua</td>";
				echo "<td>$data_mhsw[no_hportu]</td>";
			echo "</tr>";
			echo "<tr class=\"genap\">";
				echo "<td>Dosen Wali</td>";
				$dosen_wali = mysqli_fetch_array(mysqli_query($con,"SELECT `nama`,`gelar` FROM `dosen` WHERE `nip`='$data_mhsw[dosen_wali]'"));
				echo "<td>$dosen_wali[nama], $dosen_wali[gelar] (NIP. $data_mhsw[dosen_wali])</td>";
			echo "</tr>";

		echo "</table>";
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

<!--</body></html>-->
</BODY>
</HTML>
