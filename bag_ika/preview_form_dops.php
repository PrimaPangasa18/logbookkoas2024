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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) ILMU KESEHATAN ANAK</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">PREVIEW NILAI DIRECT OBSERVATION OF PROCEDURAL SKILL (DOPS)<br>(PENILAIAN KETRAMPILAN KLINIS)<p>KEPANITERAAN (STASE) ILMU KESEHATAN ANAK</font></h4>";

		$id_stase = "M113";
		$id = $_GET['id'];
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
		$stase_id = "stase_".$id_stase;
		$data_stase_mhsw = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'");
		$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);
		$data_dops = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `ika_nilai_dops` WHERE `id`='$id'"));

		$tanggal_ujian = tanggal_indo($data_dops[tgl_ujian]);

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
		//Dosen Penilai
		echo "<tr>";
			echo "<td>Dosen Penilai</td>";
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_dops[dosen]'"));
			echo "<td>$data_dosen[nama], $data_dosen[gelar] ($data_dosen[nip])</td>";
		echo "</tr>";
		//Situasi Ruangan
		echo "<tr>";
			echo "<td>Situasi Ruangan</td>";
			echo "<td>";
			if ($data_dops[situasi_ruangan]=="Rawat Jalan")
				echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Jalan\" checked/>&nbsp;&nbsp;Rawat Jalan";
			else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Jalan\" disabled/>&nbsp;&nbsp;Rawat Jalan";
			echo "<br>";
			if ($data_dops[situasi_ruangan]=="Rawat Inap")
				echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Inap\" checked/>&nbsp;&nbsp;Rawat Inap";
			else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Inap\" disabled/>&nbsp;&nbsp;Rawat Inap";
			echo "<br>";
			if ($data_dops[situasi_ruangan]=="IRD")
				echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"IRD\" checked/>&nbsp;&nbsp;IRD";
			else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"IRD\" disabled/>&nbsp;&nbsp;IRD";
			echo "<br>";
			if ($data_dops[situasi_ruangan]=="Lain-lain")
				echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Lain-lain\" checked/>&nbsp;&nbsp;Lain-lain";
			else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Lain-lain\" disabled/>&nbsp;&nbsp;Lain-lain";
			echo "</td>";
		echo "</tr>";
		//Jenis Tindakan Medik
		echo "<tr>";
			echo "<td>Jenis Tindakan Medik</td>";
			echo "<td><textarea name=\"tindak_medik\" style=\"width:97%;font-family:Tahoma;font-size:1em\" disabled>$data_dops[tindak_medik]</textarea></td>";
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
			echo "<th style=\"width:20%\">Status Observasi</th>";
			echo "<th style=\"width:20%\">Nilai (0-100)</th>";
		echo "</thead>";
		//No 1
		echo "<tr>";
			echo "<td align=center>1</td>";
			echo "<td>Pengetahuan: indikasi, anatomi, teknik</td>";
			echo "<td align=center>";
			if ($data_dops[observasi_1]=="1")
				echo "<input type=\"radio\" name=\"observasi1\" value=\"1\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi1\" value=\"1\" disabled/>&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[observasi_1]=="0")
				echo "<input type=\"radio\" name=\"observasi1\" value=\"0\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi1\" value=\"0\" disabled/>&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center>$data_dops[aspek_1]</td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>Mendapatkan persetujuan tindakan</td>";
			echo "<td align=center>";
			if ($data_dops[observasi_2]=="1")
				echo "<input type=\"radio\" name=\"observasi2\" value=\"1\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi2\" value=\"1\" disabled/>&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[observasi_2]=="0")
				echo "<input type=\"radio\" name=\"observasi2\" value=\"0\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi2\" value=\"0\" disabled/>&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center>$data_dops[aspek_2]</td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>Persiapan sebelum tindakan</td>";
			echo "<td align=center>";
			if ($data_dops[observasi_3]=="1")
				echo "<input type=\"radio\" name=\"observasi3\" value=\"1\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi3\" value=\"1\" disabled/>&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[observasi_3]=="0")
				echo "<input type=\"radio\" name=\"observasi3\" value=\"0\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi3\" value=\"0\" disabled/>&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center>$data_dops[aspek_3]</td>";
		echo "</tr>";
		//No 4
		echo "<tr>";
			echo "<td align=center>4</td>";
			echo "<td>Pemberian sedasi/analgesi yang sesuai</td>";
			echo "<td align=center>";
			if ($data_dops[observasi_4]=="1")
				echo "<input type=\"radio\" name=\"observasi4\" value=\"1\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi4\" value=\"1\" disabled/>&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[observasi_4]=="0")
				echo "<input type=\"radio\" name=\"observasi4\" value=\"0\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi4\" value=\"0\" disabled/>&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center>$data_dops[aspek_4]</td>";
		echo "</tr>";
		//No 5
		echo "<tr>";
			echo "<td align=center>5</td>";
			echo "<td>Kemampuan teknik melakukan tindakan</td>";
			echo "<td align=center>";
			if ($data_dops[observasi_5]=="1")
				echo "<input type=\"radio\" name=\"observasi5\" value=\"1\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi5\" value=\"1\" disabled/>&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[observasi_5]=="0")
				echo "<input type=\"radio\" name=\"observasi5\" value=\"0\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi5\" value=\"0\" disabled/>&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center>$data_dops[aspek_5]</td>";
		echo "</tr>";
		//No 6
		echo "<tr>";
		  echo "<td align=center>6</td>";
		  echo "<td>Kemampuan melakukan teknik aseptik</td>";
		  echo "<td align=center>";
		  if ($data_dops[observasi_6]=="1")
		    echo "<input type=\"radio\" name=\"observasi6\" value=\"1\" checked/>&nbsp;&nbsp;Ya";
		  else echo "<input type=\"radio\" name=\"observasi6\" value=\"1\" disabled/>&nbsp;&nbsp;Ya";
		  echo "&nbsp;&nbsp;&nbsp;&nbsp;";
		  if ($data_dops[observasi_6]=="0")
		    echo "<input type=\"radio\" name=\"observasi6\" value=\"0\" checked/>&nbsp;&nbsp;Tidak</td>";
		  else echo "<input type=\"radio\" name=\"observasi6\" value=\"0\" disabled/>&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center>$data_dops[aspek_6]</td>";
		echo "</tr>";
		//No 7
		echo "<tr>";
		  echo "<td align=center>7</td>";
		  echo "<td>Inisiatif mencari bantuan bila diperlukan</td>";
		  echo "<td align=center>";
		  if ($data_dops[observasi_7]=="1")
		    echo "<input type=\"radio\" name=\"observasi7\" value=\"1\" checked/>&nbsp;&nbsp;Ya";
		  else echo "<input type=\"radio\" name=\"observasi7\" value=\"1\" disabled/>&nbsp;&nbsp;Ya";
		  echo "&nbsp;&nbsp;&nbsp;&nbsp;";
		  if ($data_dops[observasi_7]=="0")
		    echo "<input type=\"radio\" name=\"observasi7\" value=\"0\" checked/>&nbsp;&nbsp;Tidak</td>";
		  else echo "<input type=\"radio\" name=\"observasi7\" value=\"0\" disabled/>&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center>$data_dops[aspek_7]</td>";
		echo "</tr>";
		//No 8
		echo "<tr>";
		  echo "<td align=center>8</td>";
		  echo "<td>Kemampuan tatalaksana paska tindakan</td>";
		  echo "<td align=center>";
		  if ($data_dops[observasi_8]=="1")
		    echo "<input type=\"radio\" name=\"observasi8\" value=\"1\" checked/>&nbsp;&nbsp;Ya";
		  else echo "<input type=\"radio\" name=\"observasi8\" value=\"1\" disabled/>&nbsp;&nbsp;Ya";
		  echo "&nbsp;&nbsp;&nbsp;&nbsp;";
		  if ($data_dops[observasi_8]=="0")
		    echo "<input type=\"radio\" name=\"observasi8\" value=\"0\" checked/>&nbsp;&nbsp;Tidak</td>";
		  else echo "<input type=\"radio\" name=\"observasi8\" value=\"0\" disabled/>&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center>$data_dops[aspek_8]</td>";
		echo "</tr>";
		//No 9
		echo "<tr>";
		  echo "<td align=center>9</td>";
		  echo "<td>Kemampuan komunikasi</td>";
		  echo "<td align=center>";
		  if ($data_dops[observasi_9]=="1")
		    echo "<input type=\"radio\" name=\"observasi9\" value=\"1\" checked/>&nbsp;&nbsp;Ya";
		  else echo "<input type=\"radio\" name=\"observasi9\" value=\"1\" disabled/>&nbsp;&nbsp;Ya";
		  echo "&nbsp;&nbsp;&nbsp;&nbsp;";
		  if ($data_dops[observasi_9]=="0")
		    echo "<input type=\"radio\" name=\"observasi9\" value=\"0\" checked/>&nbsp;&nbsp;Tidak</td>";
		  else echo "<input type=\"radio\" name=\"observasi9\" value=\"0\" disabled/>&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center>$data_dops[aspek_9]</td>";
		echo "</tr>";
		//No 10
		echo "<tr>";
		  echo "<td align=center>10</td>";
		  echo "<td>Mempertimbangkan kondisi pasien / profesionalisme</td>";
		  echo "<td align=center>";
		  if ($data_dops[observasi_10]=="1")
		    echo "<input type=\"radio\" name=\"observasi10\" value=\"1\" checked/>&nbsp;&nbsp;Ya";
		  else echo "<input type=\"radio\" name=\"observasi10\" value=\"1\" disabled/>&nbsp;&nbsp;Ya";
		  echo "&nbsp;&nbsp;&nbsp;&nbsp;";
		  if ($data_dops[observasi_10]=="0")
		    echo "<input type=\"radio\" name=\"observasi10\" value=\"0\" checked/>&nbsp;&nbsp;Tidak</td>";
		  else echo "<input type=\"radio\" name=\"observasi10\" value=\"0\" disabled/>&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center>$data_dops[aspek_10]</td>";
		echo "</tr>";
		//No 11
		echo "<tr>";
		  echo "<td align=center>11</td>";
		  echo "<td>Kemampuan keseluruhan tindakan medik</td>";
		  echo "<td align=center>";
		  if ($data_dops[observasi_11]=="1")
		    echo "<input type=\"radio\" name=\"observasi11\" value=\"1\" checked/>&nbsp;&nbsp;Ya";
		  else echo "<input type=\"radio\" name=\"observasi11\" value=\"1\" disabled/>&nbsp;&nbsp;Ya";
		  echo "&nbsp;&nbsp;&nbsp;&nbsp;";
		  if ($data_dops[observasi_11]=="0")
		    echo "<input type=\"radio\" name=\"observasi11\" value=\"0\" checked/>&nbsp;&nbsp;Tidak</td>";
		  else echo "<input type=\"radio\" name=\"observasi11\" value=\"0\" disabled/>&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center>$data_dops[aspek_11]</td>";
		echo "</tr>";
		//Rata Nilai
		echo "<tr>";
			echo "<td align=right colspan=3>Rata-Rata Nilai (Jumlah Nilai / Jumlah Observasi)</td>";
			echo "<td align=center>$data_dops[nilai_rata]</td>";
		echo "</tr>";
		echo "<tr><td colspan=4><font style=\"font-size:0.75em;\"><i>Keterangan: Nilai Batas Lulus (NBL) = 70</i></font></td></tr>";
		echo "</table><br><br>";

		//Umpan Balik
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr><td colspan=2><b>Umpan Balik:</b><br>
					<textarea name=\"umpan_balik\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_dops[umpan_balik]</textarea></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td colspan=2><b>Rencana tindak lanjut yang disetujui bersama:</b><br>
					<textarea name=\"saran\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_dops[saran]</textarea></td>";
		echo "</tr>";
		//Catatan
		echo "<tr><td colspan=2><b>Catatan:</b></td></tr>";
		echo "<tr><td colspan=2>1. Waktu Penilaian DOPS:</td></tr>";
		echo "<tr>";
			echo "<td style=\"width:40%\">&nbsp;&nbsp;&nbsp;&nbsp;Observasi</td>";
			echo "<td>";
			echo "$data_dops[waktu_observasi]&nbsp;&nbsp;menit<br>";
			echo "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;Memberikan umpan balik</td>";
			echo "<td>";
			echo "$data_dops[waktu_ub]&nbsp;&nbsp;menit<br>";
			echo "</td>";
		echo "</tr>";
		echo "<tr><td colspan=2>2. Kepuasan penilai terhadap pelaksanaan DOPS:</td></tr>";
		echo "<tr><td colspan=2 style=\"padding:5px 5px 5px 25px;\">";
		if ($data_dops[kepuasan_penilai]=="Kurang sekali")
		  echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Kurang sekali\" checked />&nbsp;&nbsp;Kurang sekali&nbsp;&nbsp;&nbsp;&nbsp;";
		else echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Kurang sekali\" disabled/>&nbsp;&nbsp;Kurang sekali&nbsp;&nbsp;&nbsp;&nbsp;";
		if ($data_dops[kepuasan_penilai]=="Kurang")
		  echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Kurang\" checked />&nbsp;&nbsp;Kurang&nbsp;&nbsp;&nbsp;&nbsp;";
		else echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Kurang\" disabled/>&nbsp;&nbsp;Kurang&nbsp;&nbsp;&nbsp;&nbsp;";
		if ($data_dops[kepuasan_penilai]=="Cukup")
		  echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Cukup\" checked />&nbsp;&nbsp;Cukup&nbsp;&nbsp;&nbsp;&nbsp;";
		else echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Cukup\" disabled/>&nbsp;&nbsp;Cukup&nbsp;&nbsp;&nbsp;&nbsp;";
		if ($data_dops[kepuasan_penilai]=="Baik")
		  echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Baik\" checked />&nbsp;&nbsp;Baik&nbsp;&nbsp;&nbsp;&nbsp;";
		else echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Baik\" disabled/>&nbsp;&nbsp;Baik&nbsp;&nbsp;&nbsp;&nbsp;";
		if ($data_dops[kepuasan_penilai]=="Baik sekali")
		  echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Baik sekali\" checked />&nbsp;&nbsp;Baik sekali";
		else echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Baik sekali\" disabled/>&nbsp;&nbsp;Baik sekali";
		echo "</td></tr>";
		echo "<tr><td colspan=2>3. Kepuasan mahasiswa terhadap pelaksanaan DOPS:</td></tr>";
		echo "<tr><td colspan=2 style=\"padding:5px 5px 5px 25px;\">";
		if ($data_dops[kepuasan_residen]=="Kurang sekali")
		  echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Kurang sekali\" checked/>&nbsp;&nbsp;Kurang sekali&nbsp;&nbsp;&nbsp;&nbsp;";
		else echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Kurang sekali\" disabled/>&nbsp;&nbsp;Kurang sekali&nbsp;&nbsp;&nbsp;&nbsp;";
		if ($data_dops[kepuasan_residen]=="Kurang")
		  echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Kurang\" checked/>&nbsp;&nbsp;Kurang&nbsp;&nbsp;&nbsp;&nbsp;";
		else echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Kurang\" disabled/>&nbsp;&nbsp;Kurang&nbsp;&nbsp;&nbsp;&nbsp;";
		if ($data_dops[kepuasan_residen]=="Cukup")
		  echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Cukup\" checked/>&nbsp;&nbsp;Cukup&nbsp;&nbsp;&nbsp;&nbsp;";
		else echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Cukup\" disabled/>&nbsp;&nbsp;Cukup&nbsp;&nbsp;&nbsp;&nbsp;";
		if ($data_dops[kepuasan_residen]=="Baik")
		  echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Baik\" checked/>&nbsp;&nbsp;Baik&nbsp;&nbsp;&nbsp;&nbsp;";
		else echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Baik\" disabled/>&nbsp;&nbsp;Baik&nbsp;&nbsp;&nbsp;&nbsp;";
		if ($data_dops[kepuasan_residen]=="Baik sekali")
		  echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Baik sekali\" checked/>&nbsp;&nbsp;Baik sekali";
		else echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Baik sekali\" disabled/>&nbsp;&nbsp;Baik sekali";
		echo "</td></tr>";
		echo "<tr><td colspan=2 align=right><br><i>Status: <b>BELUM DISETUJUI</b><br>";
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
