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
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">PREVIEW NILAI PRESENSI / KEHADIRAN</font></h4>";

		$id = $_GET['id'];
		$data_presensi = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kdk_nilai_presensi` WHERE `id`='$id'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_presensi[nim]'"));

		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";

		//Instansi
		echo "<tr>";
			echo "<td style=\"width:40%\">Instansi</td>";
			echo "<td style=\"width:60%\">$data_presensi[instansi]</td>";
		echo "</tr>";
		//Lokasi Puskesmas/Klinik
		echo "<tr>";
			echo "<td>Nama Puskesmas / Klinik</td>";
			echo "<td>$data_presensi[lokasi]</td>";
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
			$mulai = tanggal_indo($data_presensi[tgl_mulai]);
			echo "<td>$mulai</td>";
		echo "</tr>";
		//Tgl selesai kegiatan
		echo "<tr>";
			echo "<td>Tanggal selesai kegiatan</td>";
			$selesai = tanggal_indo($data_presensi[tgl_selesai]);
			echo "<td>$selesai</td>";
		echo "</tr>";
		//Dokter Pembimbing
		echo "<tr>";
			echo "<td>Dokter Pembimbing</td>";
			echo "<td>";
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_presensi[dosen]'"));
			echo "$data_dosen[nama], $data_dosen[gelar] ($data_dosen[nip])";
			echo "</td>";
		echo "</tr>";
		echo "</table><br><br>";

		//Hari kegiatan
		//Jumlah Hari Kerja
		echo "<table border=0 style=\"border-collapse:collapse;width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr><td colspan=3><b>Jumlah Hari Kegiatan:</b></td></tr>";
		echo "<tr>";
			echo "<td style=\"width:4%\" align=center>A.</td>";
			echo "<td style=\"width:36%\">Jumlah hari kerja<br>";
			echo "<font style=\"font-size:0.65em\"><i>(Hari kerja)</i></td>";
			echo "<td style=\"width:60%\">: $data_presensi[hari_kerja]&nbsp;&nbsp;hari</td>";
		echo "</tr>";
		//Jumlah hari tidak masuk dengan ijin
		echo "<tr>";
			echo "<td align=center>B.</td>";
			echo "<td>Jumlah hari tidak masuk DENGAN IJIN<br>";
			echo "<font style=\"font-size:0.65em\"><i>(Hari ijin)</i></td>";
			echo "<td>: $data_presensi[hari_ijin]&nbsp;&nbsp;hari</td>";
		echo "</tr>";
		//Jumlah hari tidak masuk tanpa ijin
		echo "<tr>";
			echo "<td align=center>C.</td>";
			echo "<td>Jumlah hari tidak masuk TANPA IJIN<br>";
			echo "<font style=\"font-size:0.65em\"><i>(Hari alpa)</i></td>";
			echo "<td>: $data_presensi[hari_alpa]&nbsp;&nbsp;hari</td>";
		echo "</tr>";
		//Jumlah Hari Masuk
		echo "<tr>";
			echo "<td align=center>D.</td>";
			echo "<td>Jumlah hari masuk kegiatan<br>";
			echo "<font style=\"font-size:0.65em\"><i>(Hari masuk = hari kerja - (hari ijin + hari alpa))</i></td>";
			echo "<td>: $data_presensi[hari_masuk]&nbsp;&nbsp;hari</td>";
		echo "</tr>";
		echo "</table><br><br>";

		//Form nilai
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr><td><b>Form Penilaian:</b></td></tr>";
		echo "</table>";
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<thead>";
			echo "<th style=\"width:5%\">No</th>";
			echo "<th style=\"width:75%\">Unsur Penilaian</th>";
			echo "<th style=\"width:20%\">Skor</th>";
		echo "</thead>";
		//No 1
		echo "<tr>";
			echo "<td align=center>1</td>";
			echo "<td>Bila setiap hari masuk dan kegiatan memenuhi (skor maksimal 100)<br>
						<font style=\"font-size:0.65em\"><i>Rumus skor = 100 x (Jml hari masuk)/(Jml hari kerja)</i></font></td>";
			echo "<td align=center>$data_presensi[nilai_masuk]</td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>Bila tidak masuk TANPA IJIN, nilai dipotong 5 / hari<br>
						<font style=\"font-size:0.65em\"><i>Rumus skor pengurangan = 5 x Jml hari tidak masuk tanpa ijin</i></font></td>";
			echo "<td align=center>$data_presensi[nilai_absen]</td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>Bila tidak masuk DENGAN IJIN, nilai dipotong 2 / hari<br>
						<font style=\"font-size:0.65em\"><i>Rumus skor pengurangan = 2 x Jml hari tidak masuk dengan ijin</i></font></td>";
			echo "<td align=center>$data_presensi[nilai_ijin]</td>";
		echo "</tr>";
		//No 4
		echo "<tr>";
			echo "<td align=right colspan=2>Total Nilai (Skor 1 + Skor 2 + Skor 3)</td>";
			echo "<td align=center><b>$data_presensi[nilai_total]</b></td>";
		echo "</tr>";
		echo "<tr><td colspan=4 align=right><br><i>Status: <b>BELUM DISETUJUI</b><br>";
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
