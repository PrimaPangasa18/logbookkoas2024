<HTML>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="../menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../mytable.css" type="text/css" media="screen" />
<!--</head>-->
</head>
<BODY>

<?php

include "../config.php";
include "../fungsi.php";

error_reporting("E_ALL ^ E_NOTICE");

	if (empty($_COOKIE['user']) || empty($_COOKIE['pass'])){
		echo "
		<script>
			window.location.href=\"../accessdenied.php\";
		</script>
		";
	}
	else{
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND $_COOKIE['level']==5)
	{
		if ($_COOKIE['level']==5) {include "menu5.php";}

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) IKM-KP</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">PREVIEW NILAI UJIAN KOMPREHENSIP<p>Kepaniteraan (Stase) IKM-KP</font></h4>";

		$id = $_GET['id'];
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
		$data_komprehensip = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `ikmkp_nilai_komprehensip` WHERE `id`='$id'"));

		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";

		//Nama Mahasiswa
		echo "<tr>";
			echo "<td>Nama Mahasiswa</td>";
			echo "<td>$data_mhsw[nama]</td>";
		echo "</tr>";
		//NIM
		echo "<tr>";
			echo "<td>NIM</td>";
			echo "<td>$data_mhsw[nim]</td>";
		echo "</tr>";
		//Dosen Pembimbing
		echo "<tr>";
			echo "<td>Dosen Pembimbing</td>";
			echo "<td>";
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_komprehensip[dosen]'"));
			echo "$data_dosen[nama], $data_dosen[gelar] ($data_dosen[nip])";
			echo "</td>";
		echo "</tr>";
		echo "</table><br><br>";

		//Form nilai
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr><td><b>Form Penilaian:</b></td></tr>";
		echo "</table>";
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<thead>";
		 	echo "<th style=\"width:5%\">No</th>";
			echo "<th style=\"width:55%\">Komponen Penilaian</th>";
			echo "<th style=\"width:20%\">Bobot</th>";
			echo "<th style=\"width:20%\">Nilai (0-100)</th>";
		echo "</thead>";
		//No 1
		echo "<tr>";
			echo "<td align=center>1</td>";
			echo "<td>Penilaian portofolio kegiatan mahasiwa selama kepaniteraan IKM-KP (kelengkapan laporan, kualitas laporan)</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center>$data_komprehensip[aspek_1]</td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>Kemampuan ujian lisan (kemampuan menjawab pertanyaan, penguasaan materi, kemampuan melakukan reasoning dan analisis)</td>";
			echo "<td align=center>50%</td>";
			echo "<td align=center>$data_komprehensip[aspek_2]</td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>Kemampuan ujian tertulis/penugasan</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center>$data_komprehensip[aspek_3]</td>";
		echo "</tr>";
		//No 4
		echo "<tr>";
			echo "<td align=center>4</td>";
			echo "<td>Sikap</td>";
			echo "<td align=center>10%</td>";
			echo "<td align=center>$data_komprehensip[aspek_4]</td>";
		echo "</tr>";
		//Nilai Total
		echo "<tr>";
			echo "<td colspan=3 align=right>Total Nilai (Bobot x Nilai)</td>";
			echo "<td align=center>$data_komprehensip[nilai_total]</td>";
		echo "</tr>";
		echo "</table><br>";

		//Umpan Balik
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr>";
		  echo "<td>Umpan Balik:<br><textarea name=\"umpan_balik\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_komprehensip[umpan_balik]</textarea></td>";
		echo "</tr>";
		echo "<tr>";
		  echo "<td>Saran:<br><textarea name=\"saran\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_komprehensip[saran]</textarea></td>";
		echo "</tr>";
		echo "<tr><td align=right><br><i>Status: <b>BELUM DISETUJUI</b><br>";
		echo "Dosen Pembimbing<p>$data_dosen[nama], $data_dosen[gelar]<br>NIP. $data_dosen[nip]</i>";
		echo "</td></tr>";
		echo "</table><br>";
		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		echo "<br><center><input type=\"submit\" class=\"submit1\" name=\"back\" value=\"BACK\" />";
		echo "<br><br></form></fieldset>";

		if ($_POST[back]=="BACK")
		{
			echo "
					<script>
					window.location.href=\"penilaian_ikmkp.php\";
					</script>
					";

		}

	}
	else
		echo "
		<script>
			window.location.href=\"../accessdenied.php\";
		</script>
		";
	}
?>

<script src="../jquery.min.js"></script>

<!--</body></html>-->
</BODY>
</HTML>
