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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) IKGM</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">PREVIEW NILAI LAPORAN KASUS<p>Kepaniteraan (Stase) IKGM</font></h4>";

		$id = $_GET['id'];
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
		$data_kasus = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `ikgm_nilai_kasus` WHERE `id`='$id'"));

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
		///Tanggal Presentasi
		echo "<tr>";
			$tanggal_ujian = tanggal_indo($data_kasus[tgl_ujian]);
			echo "<td>Tanggal Ujian/Presentasi</td>";
			echo "<td>$tanggal_ujian</td>";
		echo "</tr>";
		//Dosen Pembimbing
		echo "<tr>";
			echo "<td>Dosen Pembimbing</td>";
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_kasus[dosen]'"));
			echo "<td>$data_dosen[nama], $data_dosen[gelar] ($data_dosen[nip])</td>";
		echo "</tr>";
		//Nama/Judul Kasus
		echo "<tr>";
			echo "<td>Nama/Judul Kasus</td>";
			echo "<td><textarea name=\"nama_kasus\" style=\"width:97%;font-family:Tahoma;font-size:1em\" disabled>$data_kasus[nama_kasus]</textarea></td>";
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
			echo "<th style=\"width:40%\">Penilaian</th>";
		echo "</thead>";
		//No 1
		echo "<tr>";
			echo "<td align=center>1</td>";
			echo "<td>Kehadiran Presentasi</td>";
			echo "<td>";
			if ($data_kasus[aspek_1]=="0")
				echo "<input type=\"radio\" name=\"aspek_1\" value=\"0\" checked/>&nbsp;&nbsp;0 - Tidak Hadir<br>";
			else echo "<input type=\"radio\" name=\"aspek_1\" value=\"0\" disabled />&nbsp;&nbsp;0 - Tidak Hadir<br>";
			if ($data_kasus[aspek_1]=="1")
				echo "<input type=\"radio\" name=\"aspek_1\" value=\"1\" checked/>&nbsp;&nbsp;1 - Hadir, terlambat<br>";
			else echo "<input type=\"radio\" name=\"aspek_1\" value=\"1\" disabled />&nbsp;&nbsp;1 - Hadir, terlambat<br>";
			if ($data_kasus[aspek_1]=="2")
				echo "<input type=\"radio\" name=\"aspek_1\" value=\"2\" checked/>&nbsp;&nbsp;2 - Hadir tepat waktu<br>";
			else echo "<input type=\"radio\" name=\"aspek_1\" value=\"2\" disabled />&nbsp;&nbsp;2 - Hadir tepat waktu<br>";
			echo "</td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>Aktifitas saat diskusi<br>(<i>Dinilai dari frekuensi mengajukan masukan / komentar / pendapat / jawaban</i>)</td>";
			echo "<td>";
			if ($data_kasus[aspek_2]=="1")
				echo "<input type=\"radio\" name=\"aspek_2\" value=\"1\" checked/>&nbsp;&nbsp;1 - Pasif<br>";
			else echo "<input type=\"radio\" name=\"aspek_2\" value=\"1\" disabled />&nbsp;&nbsp;1 - Pasif<br>";
			if ($data_kasus[aspek_2]=="2")
				echo "<input type=\"radio\" name=\"aspek_2\" value=\"2\" checked/>&nbsp;&nbsp;2 - Kurang aktif<br>";
			else echo "<input type=\"radio\" name=\"aspek_2\" value=\"2\" disabled />&nbsp;&nbsp;2 - Kurang aktif<br>";
			if ($data_kasus[aspek_2]=="3")
				echo "<input type=\"radio\" name=\"aspek_2\" value=\"3\" checked/>&nbsp;&nbsp;3 - Cukup aktif<br>";
			else echo "<input type=\"radio\" name=\"aspek_2\" value=\"3\" disabled />&nbsp;&nbsp;3 - Cukup aktif<br>";
			if ($data_kasus[aspek_2]=="4")
				echo "<input type=\"radio\" name=\"aspek_2\" value=\"4\" checked/>&nbsp;&nbsp;4 - Lebih aktif<br>";
			else echo "<input type=\"radio\" name=\"aspek_2\" value=\"4\" disabled />&nbsp;&nbsp;4 - Lebih aktif<br>";
			if ($data_kasus[aspek_2]=="5")
				echo "<input type=\"radio\" name=\"aspek_2\" value=\"5\" checked/>&nbsp;&nbsp;5 - Sangat aktif";
			else echo "<input type=\"radio\" name=\"aspek_2\" value=\"5\" disabled />&nbsp;&nbsp;5 - Sangat aktif";
			echo "</td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>Ketrampilan presentasi / berkomunikasi<br>(<i>Dinilai dari kemampuan berinteraksi dengan peserta lain dan menjaga etika</i>)</td>";
			echo "<td>";
			if ($data_kasus[aspek_3]=="1")
			  echo "<input type=\"radio\" name=\"aspek_3\" value=\"1\" checked/>&nbsp;&nbsp;1 - Sangat kurang<br>";
			else echo "<input type=\"radio\" name=\"aspek_3\" value=\"1\" disabled />&nbsp;&nbsp;1 - Sangat kurang<br>";
			if ($data_kasus[aspek_3]=="2")
			  echo "<input type=\"radio\" name=\"aspek_3\" value=\"2\" checked/>&nbsp;&nbsp;2 - Kurang<br>";
			else echo "<input type=\"radio\" name=\"aspek_3\" value=\"2\" disabled />&nbsp;&nbsp;2 - Kurang<br>";
			if ($data_kasus[aspek_3]=="3")
			  echo "<input type=\"radio\" name=\"aspek_3\" value=\"3\" checked/>&nbsp;&nbsp;3 - Cukup<br>";
			else echo "<input type=\"radio\" name=\"aspek_3\" value=\"3\" disabled />&nbsp;&nbsp;3 - Cukup<br>";
			if ($data_kasus[aspek_3]=="4")
			  echo "<input type=\"radio\" name=\"aspek_3\" value=\"4\" checked/>&nbsp;&nbsp;4 - Baik<br>";
			else echo "<input type=\"radio\" name=\"aspek_3\" value=\"4\" disabled />&nbsp;&nbsp;4 - Baik<br>";
			if ($data_kasus[aspek_3]=="5")
			  echo "<input type=\"radio\" name=\"aspek_3\" value=\"5\" checked/>&nbsp;&nbsp;5 - Sangat baik";
			else echo "<input type=\"radio\" name=\"aspek_3\" value=\"5\" disabled />&nbsp;&nbsp;5 - Sangat baik";
			echo "</td>";
		echo "</tr>";
		//No 4
		echo "<tr>";
			echo "<td align=center>4</td>";
			echo "<td>Kelengkapan laporan dan materi presentasi<br>(<i>Dinilai dari materi presentasi yang disiapkan dan disajikan, serta kelengkapan isi dan kerapian makalah / laporan yang dikumpulkan</i>)</td>";
			echo "<td>";
			if ($data_kasus[aspek_4]=="1")
			  echo "<input type=\"radio\" name=\"aspek_4\" value=\"1\" checked/>&nbsp;&nbsp;1 - Sangat kurang<br>";
			else echo "<input type=\"radio\" name=\"aspek_4\" value=\"1\" disabled />&nbsp;&nbsp;1 - Sangat kurang<br>";
			if ($data_kasus[aspek_4]=="2")
			  echo "<input type=\"radio\" name=\"aspek_4\" value=\"2\" checked/>&nbsp;&nbsp;2 - Kurang<br>";
			else echo "<input type=\"radio\" name=\"aspek_4\" value=\"2\" disabled />&nbsp;&nbsp;2 - Kurang<br>";
			if ($data_kasus[aspek_4]=="3")
			  echo "<input type=\"radio\" name=\"aspek_4\" value=\"3\" checked/>&nbsp;&nbsp;3 - Cukup<br>";
			else echo "<input type=\"radio\" name=\"aspek_4\" value=\"3\" disabled />&nbsp;&nbsp;3 - Cukup<br>";
			if ($data_kasus[aspek_4]=="4")
			  echo "<input type=\"radio\" name=\"aspek_4\" value=\"4\" checked/>&nbsp;&nbsp;4 - Baik<br>";
			else echo "<input type=\"radio\" name=\"aspek_4\" value=\"4\" disabled />&nbsp;&nbsp;4 - Baik<br>";
			if ($data_kasus[aspek_4]=="5")
			  echo "<input type=\"radio\" name=\"aspek_4\" value=\"5\" checked/>&nbsp;&nbsp;5 - Sangat baik";
			else echo "<input type=\"radio\" name=\"aspek_4\" value=\"5\" disabled />&nbsp;&nbsp;5 - Sangat baik";
			echo "</td>";
		echo "</tr>";
		//Total Nilai
		echo "<tr>";
			echo "<td colspan=2 align=right>Total Nilai (10 x Jumlah Poin / 1.7)</td>";
			echo "<td><b>$data_kasus[nilai_total]</b></td>";
		echo "</tr>";
		echo "</table><br><br>";

		//Catatan
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr><td><b>Catatan Dosen Pembimbing Terhadap Kegiatan:</b></td></tr>";
		echo "<tr>";
			echo "<td>Catatan:<br><textarea name=\"catatan\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_kasus[catatan]</textarea></td>";
		echo "</tr>";
		echo "<tr><td colspan=4 align=right><br><i>Status: <b>BELUM DISETUJUI</b><br>";
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
					window.location.href=\"penilaian_ikgm.php\";
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
