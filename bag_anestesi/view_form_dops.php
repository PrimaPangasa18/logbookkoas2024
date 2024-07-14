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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) ANESTESI DAN INTENSIVE CARE</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">NILAI DIRECT OBSERVATION OF PROCEDURAL SKILL (DOPS)<p>KEPANITERAAN (STASE) ANESTESI DAN INTENSIVE CARE</font></h4>";

		$id = $_GET['id'];
		$data_dops = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `anestesi_nilai_dops` WHERE `id`='$id'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_dops[nim]'"));

		$tanggal_ujian = tanggal_indo($data_dops[tgl_ujian]);
		$tanggal_approval = tanggal_indo($data_dops[tgl_approval]);

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
		//Tanggal Ujian/Presentasi
		echo "<tr>";
			echo "<td>Tanggal Ujian/Presentasi</td>";
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
			if ($data_dops[situasi_ruangan]=="IRD")
				echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"IRD\" checked/>&nbsp;&nbsp;IRD";
			else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"IRD\" disabled/>&nbsp;&nbsp;IRD";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[situasi_ruangan]=="Bangsal")
				echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Bangsal\" checked/>&nbsp;&nbsp;Bangsal";
			else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Bangsal\" disabled/>&nbsp;&nbsp;Bangsal";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[situasi_ruangan]=="Lapangan/Lain-lain")
				echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Lapangan/Lain-lain\" checked/>&nbsp;&nbsp;Lapangan/Lain-lain";
			else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Lapangan/Lain-lain\" disabled/>&nbsp;&nbsp;Lapangan/Lain-lain";
			echo "</td>";
		echo "</tr>";
		//Jenis Tindak Medik
		echo "<tr>";
			echo "<td>Jenis Tindak Medik</td>";
			echo "<td><textarea name=\"tindak_medik\" style=\"width:97%;font-family:Tahoma;font-size:1em\" disabled>$data_dops[tindak_medik]</textarea></td>";
		echo "</tr>";
		//Jumlah tindak medik serupa yang pernah diobservasi penilai
		echo "<tr>";
			echo "<td>Jumlah tindak medik serupa yang pernah diobservasi penilai</td>";
			echo "<td>";
			if ($data_dops[obs_penilai]=="0")
				echo "<input type=\"radio\" name=\"obs_penilai\" value=\"0\" checked/>&nbsp;&nbsp;0";
			else echo "<input type=\"radio\" name=\"obs_penilai\" value=\"0\" disabled/>&nbsp;&nbsp;0";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[obs_penilai]=="1")
				echo "<input type=\"radio\" name=\"obs_penilai\" value=\"1\" checked/>&nbsp;&nbsp;1";
			else echo "<input type=\"radio\" name=\"obs_penilai\" value=\"1\" disabled/>&nbsp;&nbsp;1";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[obs_penilai]=="2")
				echo "<input type=\"radio\" name=\"obs_penilai\" value=\"2\" checked/>&nbsp;&nbsp;2";
			else echo "<input type=\"radio\" name=\"obs_penilai\" value=\"2\" disabled/>&nbsp;&nbsp;2";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[obs_penilai]=="3")
				echo "<input type=\"radio\" name=\"obs_penilai\" value=\"3\" checked/>&nbsp;&nbsp;3";
			else echo "<input type=\"radio\" name=\"obs_penilai\" value=\"3\" disabled/>&nbsp;&nbsp;3";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[obs_penilai]=="5-9")
				echo "<input type=\"radio\" name=\"obs_penilai\" value=\"5-9\" checked/>&nbsp;&nbsp;5-9";
			else echo "<input type=\"radio\" name=\"obs_penilai\" value=\"5-9\" disabled/>&nbsp;&nbsp;5-9";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[obs_penilai]==">9")
				echo "<input type=\"radio\" name=\"obs_penilai\" value=\">9\" checked/>&nbsp;&nbsp;>9";
			else echo "<input type=\"radio\" name=\"obs_penilai\" value=\">9\" disabled/>&nbsp;&nbsp;>9";
			echo "</td>";
		echo "</tr>";
		//Jumlah tindak medik serupa yang pernah dilakukan mahasiswa
		echo "<tr>";
			echo "<td>Jumlah tindak medik serupa yang pernah dilakukan mahasiswa</td>";
			echo "<td>";
			if ($data_dops[obs_mhsw]=="0")
				echo "<input type=\"radio\" name=\"obs_mhsw\" value=\"0\" checked/>&nbsp;&nbsp;0";
			else echo "<input type=\"radio\" name=\"obs_mhsw\" value=\"0\" disabled/>&nbsp;&nbsp;0";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[obs_mhsw]=="1")
				echo "<input type=\"radio\" name=\"obs_mhsw\" value=\"1\" checked/>&nbsp;&nbsp;1";
			else echo "<input type=\"radio\" name=\"obs_mhsw\" value=\"1\" disabled/>&nbsp;&nbsp;1";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[obs_mhsw]=="2")
				echo "<input type=\"radio\" name=\"obs_mhsw\" value=\"2\" checked/>&nbsp;&nbsp;2";
			else echo "<input type=\"radio\" name=\"obs_mhsw\" value=\"2\" disabled/>&nbsp;&nbsp;2";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[obs_mhsw]=="3")
				echo "<input type=\"radio\" name=\"obs_mhsw\" value=\"3\" checked/>&nbsp;&nbsp;3";
			else echo "<input type=\"radio\" name=\"obs_mhsw\" value=\"3\" disabled/>&nbsp;&nbsp;3";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[obs_mhsw]=="5-9")
				echo "<input type=\"radio\" name=\"obs_mhsw\" value=\"5-9\" checked/>&nbsp;&nbsp;5-9";
			else echo "<input type=\"radio\" name=\"obs_mhsw\" value=\"5-9\" disabled/>&nbsp;&nbsp;5-9";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[obs_mhsw]==">9")
				echo "<input type=\"radio\" name=\"obs_mhsw\" value=\">9\" checked/>&nbsp;&nbsp;>9";
			else echo "<input type=\"radio\" name=\"obs_mhsw\" value=\">9\" disabled/>&nbsp;&nbsp;>9";
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
			echo "<th style=\"width:20%\">Status Observasi</th>";
			echo "<th style=\"width:20%\">Nilai (0-100)</th>";
		echo "</thead>";
		//No 1
		echo "<tr>";
			echo "<td align=center>1</td>";
			echo "<td>Mempunyai pengetahuan tentang indikasi relevansi anatomic dan teknik tindak medik</td>";
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
			echo "<td>Mendapat persetujuan tindak medik</td>";
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
			echo "<td>Mampu mengajukan persiapan yang sesuai sebelum tindak medik</td>";
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
			echo "<td>Mampu memberikan analgesic yang sesuai atau sedasi yang aman</td>";
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
			echo "<td>Kemampuan secara teknik</td>";
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
		  echo "<td>Melakukan tindakan aseptic</td>";
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
		  echo "<td>Mencari bantuan bila diperlukan</td>";
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
		  echo "<td>Tatalaksana paska tindakan</td>";
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
		  echo "<td>Kecakapan komunikasi</td>";
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
		  echo "<td>Kemampuan secara keseluruhan dalam melakukan tindak medik</td>";
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
		echo "<tr><td colspan=2 align=center><b>UMPAN BALIK TERHADAP KECAKAPAN TINDAK MEDIK</b></td></tr>";
		echo "<tr>";
			echo "<td align=center style=\"width:50%\">Sudah bagus</td>";
			echo "<td align=center style=\"width:50%\">Perlu perbaikan</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td><textarea name=\"ub_bagus\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_dops[ub_bagus]</textarea></td>";
			echo "<td><textarea name=\"ub_perbaikan\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_dops[ub_perbaikan]</textarea></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td colspan=2>Saran:<br><textarea name=\"saran\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_dops[saran]</textarea></td>";
		echo "</tr>";
		///Catatan
		echo "<tr><td colspan=2><b>Catatan:</b></td></tr>";
		echo "<tr><td colspan=2>Waktu Penilaian Diskusi Kasus:</td></tr>";
		echo "<tr>";
			echo "<td>&nbsp;&nbsp;Observasi</td>";
			echo "<td>";
			echo "$data_dops[waktu_observasi]&nbsp;&nbsp;menit<br>";
			echo "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>&nbsp;&nbsp;Memberikan umpan balik</td>";
			echo "<td>";
			echo "$data_dops[waktu_ub]&nbsp;&nbsp;menit<br>";
			echo "</td>";
		echo "</tr>";

		echo "<tr><td colspan=2 align=right><br><i>Status: <b>DISETUJUI</b><br>pada tanggal $tanggal_approval<br>";
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
					window.location.href=\"penilaian_anestesi.php\";
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
					window.location.href=\"penilaian_anestesi_dosen.php?mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\";
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
