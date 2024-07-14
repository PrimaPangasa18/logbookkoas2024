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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND ($_COOKIE['level']==5 OR $_COOKIE['level']==4))
	{
		if ($_COOKIE['level']==5) {include "menu5.php";}
		if ($_COOKIE['level']==4) {include "menu4.php";}

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN KEDOKTERAN KELUARGA</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">NILAI SIKAP/PERILAKU</font></h4>";

		$id = $_GET['id'];
		$data_sikap = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kdk_nilai_sikap` WHERE `id`='$id'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_sikap[nim]'"));

		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		$tgl_mulai = $_GET[mulai];
		$tgl_selesai = $_GET[selesai];
		$approval = $_GET[approval];
		$mhsw = $_GET[mhsw];
		echo "<input type=\"hidden\" name=\"tgl_mulai\" value=\"$tgl_mulai\" />";
		echo "<input type=\"hidden\" name=\"tgl_selesai\" value=\"$tgl_selesai\" />";
		echo "<input type=\"hidden\" name=\"approval\" value=\"$approval\" />";
		echo "<input type=\"hidden\" name=\"mhsw\" value=\"$mhsw\" />";

		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr>";
			echo "<td style=\"width:40%\">Instansi</td>";
			echo "<td style=\"width:60%\">$data_sikap[instansi]</td>";
		echo "</tr>";
		//Lokasi Puskesmas/Klinik
		echo "<tr>";
			echo "<td>Nama Puskesmas / Klinik Pratama</td>";
			echo "<td>$data_sikap[lokasi]</td>";
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
			$mulai = tanggal_indo($data_sikap[tgl_mulai]);
			echo "<td>$mulai</td>";
		echo "</tr>";
		//Tgl selesai kegiatan
		echo "<tr>";
			echo "<td>Tanggal selesai kegiatan</td>";
			$selesai = tanggal_indo($data_sikap[tgl_selesai]);
			echo "<td>$selesai</td>";
		echo "</tr>";
		//Dokter Pembimbing
		echo "<tr>";
			echo "<td>Dokter Pembimbing</td>";
			echo "<td>";
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_sikap[dosen]'"));
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
			echo "<td>DISIPLIN<br>(tepat waktu, mengikuti tata tertib)</td>";
			echo "<td align=center>15%</td>";
			echo "<td align=center>$data_sikap[aspek_1]</td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>KERJASAMA<br>(dengan teman, pembimbing dan tenaga kesehatan lain)</td>";
			echo "<td align=center>15%</td>";
			echo "<td align=center>$data_sikap[aspek_2]</td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>KETELITIAN</td>";
			echo "<td align=center>15%</td>";
			echo "<td align=center>$data_sikap[aspek_3]</td>";
		echo "</tr>";
		//No 4
		echo "<tr>";
			echo "<td align=center>4</td>";
			echo "<td>INISIATIF / KREATIVITAS<br>(mengambil keputusan, menyelesaikan masalan, dll)</td>";
			echo "<td align=center>15%</td>";
			echo "<td align=center>$data_sikap[aspek_4]</td>";
		echo "</tr>";
		//No 5
		echo "<tr>";
			echo "<td align=center>5</td>";
			echo "<td>SOPAN SANTUN<br>(dengan pasien, pengunjung dan tenaga kesehatan lain)</td>";
			echo "<td align=center>15%</td>";
			echo "<td align=center>$data_sikap[aspek_5]</td>";
		echo "</tr>";
		//No 6
		echo "<tr>";
			echo "<td align=center>6</td>";
			echo "<td>TANGGUNG JAWAB<br>(menyelesaikan tugas kelompok, tugas individu dan tugas lain dari pembimbing)</td>";
			echo "<td align=center>15%</td>";
			echo "<td align=center>$data_sikap[aspek_6]</td>";
		echo "</tr>";
		//No 7
		echo "<tr>";
			echo "<td align=center>7</td>";
			echo "<td>KERAMAHAN<br>(dengan pasien, pengunjung dan tenaga kesehatan lain)</td>";
			echo "<td align=center>10%</td>";
			echo "<td align=center>$data_sikap[aspek_7]</td>";
		echo "</tr>";
		//Rata-Rata Nilai
		echo "<tr>";
			echo "<td colspan=3 align=right>Rata-Rata Nilai (Jumlah Bobot x Nilai)</td>";
			echo "<td align=center><b>$data_sikap[nilai_rata]</b></td>";
		echo "</tr>";
		echo "</table><br>";

		//Umpan Balik
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr>";
		  echo "<td>Umpan Balik:<br><textarea name=\"umpan_balik\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_sikap[umpan_balik]</textarea></td>";
		echo "</tr>";
		echo "<tr>";
		  echo "<td>Saran:<br><textarea name=\"saran\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_sikap[saran]</textarea></td>";
		echo "</tr>";
		$tanggal_approval = tanggal_indo($data_sikap[tgl_approval]);
		echo "<tr><td align=right><br><i>Status: <b>DISETUJUI</b><br>pada tanggal $tanggal_approval<br>";
		echo "Dokter Pembimbing<p>$data_dosen[nama], $data_dosen[gelar]<br>NIP. $data_dosen[nip]</i>";
		echo "</td></tr>";
		echo "</table><br>";
		echo "<br><center><input type=\"submit\" class=\"submit1\" name=\"back\" value=\"BACK\" />";
		echo "<br><br></form></fieldset>";

		if ($_POST[back]=="BACK")
		{
			if ($_COOKIE['level']==5)
			echo "
					<script>
					window.location.href=\"penilaian_kdk.php\";
					</script>
					";

			if ($_COOKIE['level']==4)
			{
				$tgl_mulai=$_POST[tgl_mulai];
				$tgl_selesai=$_POST[tgl_selesai];
				$approval=$_POST[approval];
				$mhsw=$_POST[mhsw];
				echo "
				<script>
					window.location.href=\"penilaian_kdk_dosen.php?mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\";
				</script>
				";
			}
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
