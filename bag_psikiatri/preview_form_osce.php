<HTML>
<head>
	<link rel="stylesheet" href="../menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../mytable.css" type="text/css" media="screen" />
	<meta name="viewport" content="width=device-width, maximum-scale=1">

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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) ILMU KESEHATAN JIWA</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">PREVIEW NILAI UJIAN OSCE<p>Kepaniteraan (Stase) Ilmu Kesehatan Jiwa</font></h4>";

		$id_stase = "M093";
		$id = $_GET['id'];
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
		$stase_id = "stase_".$id_stase;
		$data_stase_mhsw = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'");
		$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);
		$data_osce = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `psikiatri_nilai_osce` WHERE `id`='$id'"));

		$tanggal_ujian = tanggal_indo($data_osce[tgl_ujian]);

		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";

		//Nama mahasiswa
		echo "<tr>";
			echo "<td style=\"width:40%\">Nama Mahasiswa Koas</td>";
			echo "<td style=\"width:60%\">$data_mhsw[nama]</td>";
		echo "</tr>";
		//NIM
		echo "<tr>";
			echo "<td>NIM</td>";
			echo "<td>$data_mhsw[nim]</td>";
		echo "</tr>";
		//Tanggal Ujian
		echo "<tr>";
			echo "<td>Tanggal Ujian</td>";
			echo "<td>$tanggal_ujian</td>";
		echo "</tr>";
		//Dosen Penguji
		echo "<tr>";
			echo "<td>Dosen Penguji</td>";
			echo "<td>";
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_osce[dosen]'"));
			echo "$data_dosen[nama], $data_dosen[gelar] ($data_dosen[nip])";
			echo "</select>";
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
			echo "<th style=\"width:65%\">Komponen Penilaian</th>";
			echo "<th style=\"width:15%\">Bobot</th>";
			echo "<th style=\"width:15%\">Nilai (0-100)</th>";
		echo "</thead>";
		//No 1
		echo "<tr>";
			echo "<td align=center class=\"td_mid\">1</td>";
			echo "<td>Anamnesis</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center class=\"td_mid\">$data_osce[aspek_1]</td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center class=\"td_mid\">2</td>";
			echo "<td>Pemeriksaan Status Mental</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center class=\"td_mid\">$data_osce[aspek_2]</td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center class=\"td_mid\">3</td>";
			echo "<td>Diagnosis</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center class=\"td_mid\">$data_osce[aspek_3]</td>";
		echo "</tr>";
		//No 4
		echo "<tr>";
			echo "<td align=center class=\"td_mid\">4</td>";
			echo "<td>Terapi farmakologi</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center class=\"td_mid\">$data_osce[aspek_4]</td>";
		echo "</tr>";
		//No 5
		echo "<tr>";
			echo "<td align=center class=\"td_mid\">5</td>";
			echo "<td>Komunikasi dan edukasi pasien</td>";
			echo "<td align=center>10%</td>";
			echo "<td align=center class=\"td_mid\">$data_osce[aspek_5]</td>";
		echo "</tr>";
		//No 6
		echo "<tr>";
			echo "<td align=center class=\"td_mid\">6</td>";
			echo "<td>Perilaku profesional</td>";
			echo "<td align=center>10%</td>";
			echo "<td align=center class=\"td_mid\">$data_osce[aspek_6]</td>";
		echo "</tr>";

		//Rata-Rata Nilai
		if ($data_osce[nilai_rata]>=80) $nilai_huruf = "A";
		if ($data_osce[nilai_rata]>=70 AND $data_osce[nilai_rata]<80) $nilai_huruf = "B";
		if ($data_osce[nilai_rata]<70) $nilai_huruf = "C";
		echo "<tr>";
			echo "<td colspan=3 align=right class=\"td_mid\">Rata-Rata Nilai (Jumlah Bobot x Nilai)</td>";
			echo "<td align=center class=\"td_mid\"><b>$data_osce[nilai_rata]</b></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td colspan=3 align=right class=\"td_mid\">Konversi Nilai ke Huruf</td>";
			echo "<td align=center class=\"td_mid\"><b>$nilai_huruf</b></td>";
		echo "</tr>";
		echo "<tr><td colspan=4><font style=\"font-size:0.75em;\">";
		echo "<i>Keterangan:<br>";
		echo "Nilai Batas Lulus (NBL) = 70<br>";
		echo "Nilai A = 80.00 - 100.00 (SUPERIOR)<br>";
		echo "Nilai B = 70.00 - 79.99 (LULUS)<br>";
		echo "Nilai C < 70.00 (TIDAK LULUS)";
		echo "</i></font></td></tr>";
		echo "</table><br>";

		//Umpan Balik
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr>";
		  echo "<td>Umpan Balik:<br><textarea name=\"umpan_balik\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_osce[umpan_balik]</textarea></td>";
		echo "</tr>";
		echo "<tr>";
		  echo "<td>Saran:<br><textarea name=\"saran\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_osce[saran]</textarea></td>";
		echo "</tr>";
		echo "<tr><td align=right><br><i>Status: <b>BELUM DISETUJUI</b><br>";
		echo "Dosen Penguji<p>$data_dosen[nama], $data_dosen[gelar]<br>NIP. $data_dosen[nip]</i>";
		echo "</td></tr>";
		echo "</table><br>";

		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		echo "<br><center><input type=\"submit\" class=\"submit1\" name=\"back\" value=\"BACK\" />";
		echo "<br><br></form></fieldset>";

		if ($_POST[back]=="BACK")
		{
			echo "
					<script>
					window.location.href=\"penilaian_psikiatri.php\";
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
