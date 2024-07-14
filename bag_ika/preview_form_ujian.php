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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) ILMU KESEHATAN ANAK</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">PREVIEW NILAI UJIAN AKHIR KEPANITERAAN<p>KEPANITERAAN (STASE) ILMU KESEHATAN ANAK</font></h4>";

		$id = $_GET['id'];
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
		$data_ujian = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `ika_nilai_ujian` WHERE `id`='$id'"));

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
		//Ujian ke-
		echo "<tr>";
			echo "<td>Ujian ke-</td>";
			echo "<td>$data_ujian[ujian_ke]</td>";
		echo "</tr>";
		//Kasus
		echo "<tr>";
			echo "<td>Kasus</td>";
			echo "<td><textarea name=\"kasus\" style=\"width:97%;font-family:Tahoma;font-size:1em\" disabled>$data_ujian[kasus]</textarea></td>";
		echo "</tr>";
		//Tanggal Ujian
		echo "<tr>";
			$tanggal_ujian = tanggal_indo($data_ujian[tgl_ujian]);
			echo "<td>Tanggal Ujian</td>";
			echo "<td>$tanggal_ujian</td>";
		echo "</tr>";
		//Dosen Penguji
		echo "<tr>";
			echo "<td>Dosen Penguji</td>";
			echo "<td>";
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_ujian[dosen]'"));
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
			echo "<th style=\"width:75%\">Komponen Penilaian</th>";
			echo "<th style=\"width:20%\">Nilai (0-100)</th>";
		echo "</thead>";
		//Penilaian Ketrampilan
		echo "<tr>";
			echo "<td colspan=3>Penilaian Ketrampilan:</td>";
		echo "</tr>";
		//No 1
		echo "<tr>";
			echo "<td align=center>1</td>";
			echo "<td>Anamnesis	(<i>sacred seven</i>, <i>fundamental four</i>, tumbuh kembang, nutrisi)</td>";
			echo "<td align=center>$data_ujian[aspek_1]</td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>Pemeriksaan fisik (status lokalis, status generalis)</td>";
			echo "<td align=center>$data_ujian[aspek_2]</td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>Pemeriksaan laboratorium (usulan pemeriksaan, interpretasi)</td>";
			echo "<td align=center>$data_ujian[aspek_3]</td>";
		echo "</tr>";
		//No 4
		echo "<tr>";
			echo "<td align=center>4</td>";
			echo "<td>Kelengkapan pengumpulan data (sistematik, kejelasan, ketepatan waktu)</td>";
			echo "<td align=center>$data_ujian[aspek_4]</td>";
		echo "</tr>";
		//Nilai Rata-Rata Ketrampilan
		echo "<tr>";
			echo "<td colspan=2 align=right>Nilai Rata-Rata Ketrampilan</td>";
			echo "<td align=center><b>$data_ujian[nilai_rata_ketrampilan]</b></td>";
		echo "</tr>";
		//Penilaian Kemampuan Berpikir
		echo "<tr>";
			echo "<td colspan=3>Penilaian Kemampuan Berpikir:</td>";
		echo "</tr>";
		//No 5
		echo "<tr>";
			echo "<td align=center>5</td>";
			echo "<td>Assesment</td>";
			echo "<td align=center>$data_ujian[aspek_5]</td>";
		echo "</tr>";
		//No 6
		echo "<tr>";
			echo "<td align=center>6</td>";
			echo "<td><i>Initial plan</i> (diagnosis, terapi, monitoring, edukasi)</td>";
			echo "<td align=center>$data_ujian[aspek_6]</td>";
		echo "</tr>";
		//No 7
		echo "<tr>";
			echo "<td align=center>7</td>";
			echo "<td>Diskusi komplikasi dan pencegahan</td>";
			echo "<td align=center>$data_ujian[aspek_7]</td>";
		echo "</tr>";
		//No 8
		echo "<tr>";
			echo "<td align=center>8</td>";
			echo "<td>Prognosis</td>";
			echo "<td align=center>$data_ujian[aspek_8]</td>";
		echo "</tr>";
		//Nilai Rata-Rata Kemampuan Berpikir
		echo "<tr>";
			echo "<td colspan=2 align=right>Nilai Rata-Rata Kemampuan Berpikir</td>";
			echo "<td align=center><b>$data_ujian[nilai_rata_berpikir]</b></td>";
		echo "</tr>";
		//Penilaian Pengetahuan Teoritik
		echo "<tr>";
			echo "<td colspan=3>Penilaian Pengetahuan Teoritik:</td>";
		echo "</tr>";
		//No 9
		echo "<tr>";
			echo "<td align=center>9</td>";
			echo "<td>Diskusi tentang patofisiologi</td>";
			echo "<td align=center>$data_ujian[aspek_9]</td>";
		echo "</tr>";
		//No 10
		echo "<tr>";
			echo "<td align=center>10</td>";
			echo "<td>Diskusi tentang tumbuh kembang (imunisasi, nutrisi, perkembangan)</td>";
			echo "<td align=center>$data_ujian[aspek_10]</td>";
		echo "</tr>";
		//No 11
		echo "<tr>";
			echo "<td align=center>11</td>";
			echo "<td>Diskusi lain-lain	(hal-hal yang tercantum dalam SKDI, minimal 3)</td>";
			echo "<td align=center>$data_ujian[aspek_11]</td>";
		echo "</tr>";
		//Nilai Rata-Rata Pengetahuan Teoritik
		echo "<tr>";
			echo "<td colspan=2 align=right>Nilai Rata-Rata Pengetahuan Teoritik</td>";
			echo "<td align=center><b>$data_ujian[nilai_rata_teoritik]</b></td>";
		echo "</tr>";
		//Nilai Rata-Rata
		echo "<tr>";
			echo "<td colspan=2 align=right>Rata-Rata Nilai</td>";
			echo "<td align=center><b>$data_ujian[nilai_rata]</b></td>";
		echo "</tr>";
		echo "</table><br>";

		//Umpan Balik
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr>";
			echo "<td>Umpan Balik:<br><textarea name=\"umpan_balik\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_ujian[umpan_balik]</textarea></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>Saran:<br><textarea name=\"saran\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_ujian[saran]</textarea></td>";
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
					window.location.href=\"penilaian_ika.php\";
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
