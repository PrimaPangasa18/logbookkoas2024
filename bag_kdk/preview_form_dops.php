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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN KEDOKTERAN KELUARGA</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">PREVIEW NILAI DOPS (Directly Observed Procedural Skill)</font></h4>";

		$id = $_GET['id'];
		$data_dops = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kdk_nilai_dops` WHERE `id`='$id'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_dops[nim]'"));

		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";

		//Instansi
		echo "<tr>";
			echo "<td style=\"width:40%\">Instansi</td>";
			echo "<td style=\"width:60%\">$data_dops[instansi]</td>";
		echo "</tr>";
		//Lokasi Puskesmas/Klinik
		echo "<tr>";
			echo "<td>Nama Puskesmas / Klinik</td>";
			echo "<td>$data_dops[lokasi]</td>";
		echo "</tr>";
		//Nama dokter muda/koas
		echo "<tr>";
			echo "<td>Nama dokter muda</td>";
			echo "<td>$data_mhsw[nama]</td>";
		echo "</tr>";
		//NIM
		echo "<tr>";
			echo "<td>NIM</td>";
			echo "<td>$data_mhsw[nim]</td>";
		echo "</tr>";
		//Tgl mulai kegiatan
		echo "<tr>";
			echo "<td>Tanggal mulai kegiatan</td>";
			$mulai = tanggal_indo($data_dops[tgl_mulai]);
			echo "<td>$mulai</td>";
		echo "</tr>";
		//Tgl selesai kegiatan
		echo "<tr>";
			echo "<td>Tanggal selesai kegiatan</td>";
			$selesai = tanggal_indo($data_dops[tgl_selesai]);
			echo "<td>$selesai</td>";
		echo "</tr>";
		//Dokter Pembimbing
		echo "<tr>";
			echo "<td>Dokter Pembimbing</td>";
			echo "<td>";
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_dops[dosen]'"));
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
			echo "<th style=\"width:55%\">Aspek Yang Dinilai</th>";
			echo "<th style=\"width:20%\">Bobot</th>";
			echo "<th style=\"width:20%\">Nilai (0-100)</th>";
		echo "</thead>";
		//No 1
		echo "<tr>";
			echo "<td align=center>1</td>";
			echo "<td><b>Persiapan</b><br>";
			echo "a. Menyiapkan alat dan bahan<br>";
			echo "b. Memberitahu pasien/mengulang kontrak</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center>$data_dops[aspek_1]</td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td><b>Fase orientasi</b><br>";
			echo "a. Menjelaskan tujuan<br>";
			echo "b. Menjelaskan prosedur tindakan<br>";
			echo "c. Mencuci tangan</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center>$data_dops[aspek_2]</td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td><b>Fase kerja</b><br>";
			echo "a. Menjaga privacy<br>";
			echo "b. Melibatkan pasien/keluarga<br>";
			echo "c. Komunikasi terapetik<br>";
			echo "d. Penggunaan alat efisien<br>";
			echo "e. Penerapan prinsip kerja steril/bersih<br>";
			echo "f. Tindakan sistematik<br>";
			echo "g. Waktu efektif</td>";
			echo "<td align=center>40%</td>";
			echo "<td align=center>$data_dops[aspek_3]</td>";
		echo "</tr>";
		//No 4
		echo "<tr>";
			echo "<td align=center>4</td>";
			echo "<td><b>Fase terminasi</b><br>";
			echo "a. Cuci tangan<br>";
			echo "b. Menjelaskan rencana tindak lanjut</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center>$data_dops[aspek_4]</td>";
		echo "</tr>";
		//Rata-Rata Nilai
		echo "<tr>";
			echo "<td colspan=3 align=right>Rata-Rata Nilai (Jumlah Bobot x Nilai)</td>";
			echo "<td align=center><b>$data_dops[nilai_rata]</b></td>";
		echo "</tr>";
		echo "</table><br>";

		//Umpan Balik
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr>";
		  echo "<td>Umpan Balik:<br><textarea name=\"umpan_balik\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_dops[umpan_balik]</textarea></td>";
		echo "</tr>";
		echo "<tr>";
		  echo "<td>Saran:<br><textarea name=\"saran\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_dops[saran]</textarea></td>";
		echo "</tr>";
		echo "<tr><td align=right><br><i>Status: <b>BELUM DISETUJUI</b><br>";
		echo "Dokter Pembimbing<p>$data_dosen[nama], $data_dosen[gelar]<br>NIP. $data_dosen[nip]</i>";
		echo "</td></tr>";
		echo "</table><br>";
		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		echo "<br><center><input type=\"submit\" class=\"submit1\" name=\"back\" value=\"BACK\" />";
		echo "<br><br></form></fieldset>";

		if ($_POST[back]=="BACK")
		{
			echo "
					<script>
					window.location.href=\"penilaian_kdk.php\";
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
