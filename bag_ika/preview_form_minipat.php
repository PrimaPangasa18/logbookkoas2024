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
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">PREVIEW NILAI MINI PEER ASSESMENT TOOL (MINI-PAT)<p>KEPANITERAAN (STASE) ILMU KESEHATAN ANAK</font></h4>";

		$id = $_GET['id'];
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
		$data_minipat = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `ika_nilai_minipat` WHERE `id`='$id'"));

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
		//Periode Kegiatan
		echo "<tr>";
			$tanggal_awal = tanggal_indo($data_minipat[tgl_awal]);
			$tanggal_akhir = tanggal_indo($data_minipat[tgl_akhir]);
			echo "<td>Periode Stase</td>";
			echo "<td>$tanggal_awal s.d. $tanggal_akhir</td>";
		echo "</tr>";
		//Tanggal Penilaian
		echo "<tr>";
			$tanggal_penilaian = tanggal_indo($data_minipat[tgl_penilaian]);
			echo "<td>Tanggal Penilaian</td>";
			echo "<td>$tanggal_penilaian</td>";
		echo "</tr>";
		//Dosen Penilai
		echo "<tr>";
			echo "<td>Dosen Penilai</td>";
			echo "<td>";
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_minipat[dosen]'"));
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
			echo "<th style=\"width:80%\">Komponen Penilaian</th>";
			echo "<th style=\"width:15%\">Nilai (0-100)</th>";
		echo "</thead>";
		//Kemampuan Diagnosis
		echo "<tr>";
			echo "<td colspan=3>Kemampuan Diagnosis:</td>";
		echo "</tr>";
		//No 1
		echo "<tr>";
			echo "<td align=center>1</td>";
			echo "<td>Kemampuan menegakkan diagnosis</td>";
			echo "<td align=center>$data_minipat[aspek_1]</td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>Kemampuan memformulasikan rencana tatalaksana</td>";
			echo "<td align=center>$data_minipat[aspek_2]</td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>Kesadaran akan keterbatasan diri sendiri</td>";
			echo "<td align=center>$data_minipat[aspek_3]</td>";
		echo "</tr>";
		//No 4
		echo "<tr>";
			echo "<td align=center>4</td>";
			echo "<td>Kemampuan terhadap aspek psikososial dan penyakit</td>";
			echo "<td align=center>$data_minipat[aspek_4]</td>";
		echo "</tr>";
		//No 5
		echo "<tr>";
			echo "<td align=center>5</td>";
			echo "<td>Pemilihan/penggunaan alat penunjang medik</td>";
			echo "<td align=center>$data_minipat[aspek_5]</td>";
		echo "</tr>";
		//Menjaga Praktik Kedokteran
		echo "<tr>";
			echo "<td colspan=3>Menjaga Praktik Kedokteran:</td>";
		echo "</tr>";
		//No 6
		echo "<tr>";
			echo "<td align=center>6</td>";
			echo "<td>Kemampuan memanfaatkan waktu secara efektif dan prioritas</td>";
			echo "<td align=center>$data_minipat[aspek_6]</td>";
		echo "</tr>";
		//No 7
		echo "<tr>";
			echo "<td align=center>7</td>";
			echo "<td>Kemampuan melaksanakan kewajiban dokter dan kecakapan secara teknis</td>";
			echo "<td align=center>$data_minipat[aspek_7]</td>";
		echo "</tr>";
		//Partisipasi dalam Pendidikan
		echo "<tr>";
			echo "<td colspan=3>Partisipasi dalam Pendidikan:</td>";
		echo "</tr>";
		//No 8
		echo "<tr>";
			echo "<td align=center>8</td>";
			echo "<td>Keinginan dan kemampuan ikut mendidik sesama peserta didik dan peserta didik profesi lain</td>";
			echo "<td align=center>$data_minipat[aspek_8]</td>";
		echo "</tr>";
		//Hubungan dengan Pasien
		echo "<tr>";
			echo "<td colspan=3>Hubungan dengan Pasien:</td>";
		echo "</tr>";
		//No 9
		echo "<tr>";
			echo "<td align=center>9</td>";
			echo "<td>Komunikasi dengan pasien</td>";
			echo "<td align=center>$data_minipat[aspek_9]</td>";
		echo "</tr>";
		//No 10
		echo "<tr>";
			echo "<td align=center>10</td>";
			echo "<td>Komunikasi dengan keluarga pasien</td>";
			echo "<td align=center>$data_minipat[aspek_10]</td>";
		echo "</tr>";
		//No 11
		echo "<tr>";
			echo "<td align=center>11</td>";
			echo "<td>Menghargai hak pasien</td>";
			echo "<td align=center>$data_minipat[aspek_11]</td>";
		echo "</tr>";
		//Kerjasama
		echo "<tr>";
			echo "<td colspan=3>Kerjasama:</td>";
		echo "</tr>";
		//No 12
		echo "<tr>";
			echo "<td align=center>12</td>";
			echo "<td>Komunikasi verbal dengan teman sejawat</td>";
			echo "<td align=center>$data_minipat[aspek_12]</td>";
		echo "</tr>";
		//No 13
		echo "<tr>";
			echo "<td align=center>13</td>";
			echo "<td>Komunikasi tertulis dengan teman sejawat</td>";
			echo "<td align=center>$data_minipat[aspek_13]</td>";
		echo "</tr>";
		//No 14
		echo "<tr>";
			echo "<td align=center>14</td>";
			echo "<td>Kemampuan memahami dan menilai kontribusi orang lain</td>";
			echo "<td align=center>$data_minipat[aspek_14]</td>";
		echo "</tr>";
		//No 15
		echo "<tr>";
			echo "<td align=center>15</td>";
			echo "<td>Asesibilitas dan reliabilitas</td>";
			echo "<td align=center>$data_minipat[aspek_15]</td>";
		echo "</tr>";
		//No 16
		echo "<tr>";
			echo "<td align=center>16</td>";
			echo "<td>Penilaian secara keseluruhan terhadap peserta didik</td>";
			echo "<td align=center>$data_minipat[aspek_16]</td>";
		echo "</tr>";
		//Nilai Rata-Rata
		echo "<tr>";
			echo "<td colspan=2 align=right>Rata-Rata Nilai</td>";
			echo "<td align=center><b>$data_minipat[nilai_rata]</b></td>";
		echo "</tr>";
		echo "</table><br>";

		//Umpan Balik
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr>";
			echo "<td>Umpan Balik:<br><textarea name=\"umpan_balik\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_minipat[umpan_balik]</textarea></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>Saran:<br><textarea name=\"saran\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_minipat[saran]</textarea></td>";
		echo "</tr>";
		echo "<tr><td align=right><br><i>Status: <b>BELUM DISETUJUI</b><br>";
		echo "Dosen Penilai<p>$data_dosen[nama], $data_dosen[gelar]<br>NIP. $data_dosen[nip]</i>";
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
