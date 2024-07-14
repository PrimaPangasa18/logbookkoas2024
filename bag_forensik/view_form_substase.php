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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) KEDOKTERAN FORENSIK DAN MEDIKOLEGAL</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">NILAI SUBSTASE<p>KEPANITERAAN (STASE) KEDOKTERAN FORENSIK DAN MEDIKOLEGAL</font></h4>";

		$id = $_GET['id'];
		$data_substase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `forensik_nilai_substase` WHERE `id`='$id'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_substase[nim]'"));

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
		//Dosen Penilai Ke-
		echo "<tr>";
			echo "<td>Dosen Penilai Ke-</td>";
			echo "<td>$data_substase[dosen_ke]</td>";
		echo "</tr>";
		//Dosen Penilai
		echo "<tr>";
			echo "<td>Dosen Penilai</td>";
			echo "<td>";
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_substase[dosen]'"));
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
			echo "<td>Kehadiran:<br>";
			echo "<font style=\"font-size:0.75em\"><i>";
			echo "-	Tepat waktu = 100<br>
						-	Terlambat < 5 menit = 90<br>
						-	Terlambat 5 – 10 menit = 80<br>
						-	Terlambat 10 – 15 menit = 70<br>
						-	Terlambat > 15 menit = 50<br>
						-	Tidak hadir   = 0
						";
			echo "</i></font></td>";
			echo "<td align=center>30%</td>";
			echo "<td align=center>$data_substase[aspek_1]</td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>Keaktifan bertanya</td>";
			echo "<td align=center>30%</td>";
			echo "<td align=center>$data_substase[aspek_2]</td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>Keaktifan menjawab dengan benar</td>";
			echo "<td align=center>40%</td>";
			echo "<td align=center>$data_substase[aspek_3]</td>";
		echo "</tr>";
		//Nilai Rata-Rata
		echo "<tr>";
			echo "<td colspan=3 align=right>Rata-Rata Nilai (Jumlah Bobot x Nilai)</td>";
			echo "<td align=center><b>$data_substase[nilai_rata]</b></td>";
		echo "</tr>";
		//Catatan
		echo "<tr>";
			echo "<td colspan=4><b>Umpan Balik:</b><br><br>";
			echo "Catatan:<br>";
			echo "<textarea name=\"catatan\" style=\"width:100%;font-family:Tahoma;font-size:1em\" rows=5 disabled>$data_substase[catatan]</textarea><br><br>";
			echo "Saran:<br>";
			echo "<textarea name=\"saran\" style=\"width:100%;font-family:Tahoma;font-size:1em\" rows=5 disabled>$data_substase[saran]</textarea>";
			echo "</td>";
		echo "</tr>";

		$tanggal_approval = tanggal_indo($data_substase[tgl_approval]);
		echo "<tr><td colspan=4 align=right><br><i>Status: <b>DISETUJUI</b><br>pada tanggal $tanggal_approval<br>";
		echo "Dosen Penilai<p>$data_dosen[nama], $data_dosen[gelar]<br>NIP. $data_dosen[nip]</i>";
		echo "</td></tr>";
		echo "</table><br>";
		echo "<br><center><input type=\"submit\" class=\"submit1\" name=\"back\" value=\"BACK\" />";
		echo "<br><br></form></fieldset>";

		if ($_POST[back]=="BACK")
		{
			if ($_COOKIE['level']==5)
			echo "
					<script>
					window.location.href=\"penilaian_forensik.php\";
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
					window.location.href=\"penilaian_forensik_dosen.php?mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\";
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
