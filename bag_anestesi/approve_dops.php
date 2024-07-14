<HTML>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="../menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../mytable.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../qr_code.css" type="text/css" media="screen" />
	<link rel="stylesheet" type="text/css" href="../jquery_ui/jquery-ui.css">
	<link rel="stylesheet" href="../text-security-master/dist/text-security.css" type="text/css" media="screen" />
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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) ANESTESI DAN INTENSIVE CARE</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">APPROVAL NILAI DIRECT OBSERVATION OF PROCEDURAL SKILL (DOPS)<p>KEPANITERAAN (STASE) ANESTESI DAN INTENSIVE CARE</font></h4>";

		$id = $_GET['id'];
		$data_dops = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `anestesi_nilai_dops` WHERE `id`='$id'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_dops[nim]'"));

		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
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
			echo "<td class=\"td_mid\">Tanggal Ujian/Presentasi (yyyy-mm-dd)</td>";
			echo "<td class=\"td_mid\"><input type=\"text\" class=\"tanggal_ujian\" name=\"tanggal_ujian\" style=\"font-size:1em;font-family:TAHOMA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" value=\"$data_dops[tgl_ujian]\" /></td>";
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
			else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"IRD\" />&nbsp;&nbsp;IRD";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[situasi_ruangan]=="Bangsal")
				echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Bangsal\" checked/>&nbsp;&nbsp;Bangsal";
			else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Bangsal\" />&nbsp;&nbsp;Bangsal";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[situasi_ruangan]=="Lapangan/Lain-lain")
				echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Lapangan/Lain-lain\" checked/>&nbsp;&nbsp;Lapangan/Lain-lain";
			else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Lapangan/Lain-lain\" />&nbsp;&nbsp;Lapangan/Lain-lain";
			echo "</td>";
		echo "</tr>";
		//Jenis Tindak Medik
		echo "<tr>";
			echo "<td>Jenis Tindak Medik</td>";
			echo "<td><textarea name=\"tindak_medik\" style=\"width:97%;font-family:Tahoma;font-size:1em\" required>$data_dops[tindak_medik]</textarea></td>";
		echo "</tr>";
		//Jumlah tindak medik serupa yang pernah diobservasi penilai
		echo "<tr>";
			echo "<td>Jumlah tindak medik serupa yang pernah diobservasi penilai</td>";
			echo "<td>";
			if ($data_dops[obs_penilai]=="0")
				echo "<input type=\"radio\" name=\"obs_penilai\" value=\"0\" checked/>&nbsp;&nbsp;0";
			else echo "<input type=\"radio\" name=\"obs_penilai\" value=\"0\" />&nbsp;&nbsp;0";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[obs_penilai]=="1")
				echo "<input type=\"radio\" name=\"obs_penilai\" value=\"1\" checked/>&nbsp;&nbsp;1";
			else echo "<input type=\"radio\" name=\"obs_penilai\" value=\"1\" />&nbsp;&nbsp;1";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[obs_penilai]=="2")
				echo "<input type=\"radio\" name=\"obs_penilai\" value=\"2\" checked/>&nbsp;&nbsp;2";
			else echo "<input type=\"radio\" name=\"obs_penilai\" value=\"2\" />&nbsp;&nbsp;2";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[obs_penilai]=="3")
				echo "<input type=\"radio\" name=\"obs_penilai\" value=\"3\" checked/>&nbsp;&nbsp;3";
			else echo "<input type=\"radio\" name=\"obs_penilai\" value=\"3\" />&nbsp;&nbsp;3";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[obs_penilai]=="5-9")
				echo "<input type=\"radio\" name=\"obs_penilai\" value=\"5-9\" checked/>&nbsp;&nbsp;5-9";
			else echo "<input type=\"radio\" name=\"obs_penilai\" value=\"5-9\" />&nbsp;&nbsp;5-9";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[obs_penilai]==">9")
				echo "<input type=\"radio\" name=\"obs_penilai\" value=\">9\" checked/>&nbsp;&nbsp;>9";
			else echo "<input type=\"radio\" name=\"obs_penilai\" value=\">9\" />&nbsp;&nbsp;>9";
			echo "</td>";
		echo "</tr>";
		//Jumlah tindak medik serupa yang pernah dilakukan mahasiswa
		echo "<tr>";
			echo "<td>Jumlah tindak medik serupa yang pernah dilakukan mahasiswa</td>";
			echo "<td>";
			if ($data_dops[obs_mhsw]=="0")
				echo "<input type=\"radio\" name=\"obs_mhsw\" value=\"0\" checked/>&nbsp;&nbsp;0";
			else echo "<input type=\"radio\" name=\"obs_mhsw\" value=\"0\" />&nbsp;&nbsp;0";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[obs_mhsw]=="1")
				echo "<input type=\"radio\" name=\"obs_mhsw\" value=\"1\" checked/>&nbsp;&nbsp;1";
			else echo "<input type=\"radio\" name=\"obs_mhsw\" value=\"1\" />&nbsp;&nbsp;1";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[obs_mhsw]=="2")
				echo "<input type=\"radio\" name=\"obs_mhsw\" value=\"2\" checked/>&nbsp;&nbsp;2";
			else echo "<input type=\"radio\" name=\"obs_mhsw\" value=\"2\" />&nbsp;&nbsp;2";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[obs_mhsw]=="3")
				echo "<input type=\"radio\" name=\"obs_mhsw\" value=\"3\" checked/>&nbsp;&nbsp;3";
			else echo "<input type=\"radio\" name=\"obs_mhsw\" value=\"3\" />&nbsp;&nbsp;3";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[obs_mhsw]=="5-9")
				echo "<input type=\"radio\" name=\"obs_mhsw\" value=\"5-9\" checked/>&nbsp;&nbsp;5-9";
			else echo "<input type=\"radio\" name=\"obs_mhsw\" value=\"5-9\" />&nbsp;&nbsp;5-9";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[obs_mhsw]==">9")
				echo "<input type=\"radio\" name=\"obs_mhsw\" value=\">9\" checked/>&nbsp;&nbsp;>9";
			else echo "<input type=\"radio\" name=\"obs_mhsw\" value=\">9\" />&nbsp;&nbsp;>9";
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
				echo "<input type=\"radio\" name=\"observasi1\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi1\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[observasi_1]=="0")
				echo "<input type=\"radio\" name=\"observasi1\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi1\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek1\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_dops[aspek_1]\" id=\"aspek1\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>Mendapat persetujuan tindak medik</td>";
			echo "<td align=center>";
			if ($data_dops[observasi_2]=="1")
				echo "<input type=\"radio\" name=\"observasi2\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi2\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[observasi_2]=="0")
				echo "<input type=\"radio\" name=\"observasi2\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi2\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek2\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_dops[aspek_2]\" id=\"aspek2\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>Mampu mengajukan persiapan yang sesuai sebelum tindak medik</td>";
			echo "<td align=center>";
			if ($data_dops[observasi_3]=="1")
				echo "<input type=\"radio\" name=\"observasi3\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi3\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[observasi_3]=="0")
				echo "<input type=\"radio\" name=\"observasi3\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi3\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek3\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_dops[aspek_3]\" id=\"aspek3\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 4
		echo "<tr>";
			echo "<td align=center>4</td>";
			echo "<td>Mampu memberikan analgesic yang sesuai atau sedasi yang aman</td>";
			echo "<td align=center>";
			if ($data_dops[observasi_4]=="1")
				echo "<input type=\"radio\" name=\"observasi4\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi4\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[observasi_4]=="0")
				echo "<input type=\"radio\" name=\"observasi4\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi4\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek4\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_dops[aspek_4]\" id=\"aspek4\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 5
		echo "<tr>";
			echo "<td align=center>5</td>";
			echo "<td>Kemampuan secara teknik</td>";
			echo "<td align=center>";
			if ($data_dops[observasi_5]=="1")
				echo "<input type=\"radio\" name=\"observasi5\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi5\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_dops[observasi_5]=="0")
				echo "<input type=\"radio\" name=\"observasi5\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi5\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek5\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_dops[aspek_5]\" id=\"aspek5\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 6
		echo "<tr>";
		  echo "<td align=center>6</td>";
		  echo "<td>Melakukan tindakan aseptic</td>";
		  echo "<td align=center>";
		  if ($data_dops[observasi_6]=="1")
		    echo "<input type=\"radio\" name=\"observasi6\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
		  else echo "<input type=\"radio\" name=\"observasi6\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
		  echo "&nbsp;&nbsp;&nbsp;&nbsp;";
		  if ($data_dops[observasi_6]=="0")
		    echo "<input type=\"radio\" name=\"observasi6\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
		  else echo "<input type=\"radio\" name=\"observasi6\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
		  echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek6\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_dops[aspek_6]\" id=\"aspek6\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 7
		echo "<tr>";
		  echo "<td align=center>7</td>";
		  echo "<td>Mencari bantuan bila diperlukan</td>";
		  echo "<td align=center>";
		  if ($data_dops[observasi_7]=="1")
		    echo "<input type=\"radio\" name=\"observasi7\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
		  else echo "<input type=\"radio\" name=\"observasi7\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
		  echo "&nbsp;&nbsp;&nbsp;&nbsp;";
		  if ($data_dops[observasi_7]=="0")
		    echo "<input type=\"radio\" name=\"observasi7\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
		  else echo "<input type=\"radio\" name=\"observasi7\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
		  echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek7\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_dops[aspek_7]\" id=\"aspek7\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 8
		echo "<tr>";
		  echo "<td align=center>8</td>";
		  echo "<td>Tatalaksana paska tindakan</td>";
		  echo "<td align=center>";
		  if ($data_dops[observasi_8]=="1")
		    echo "<input type=\"radio\" name=\"observasi8\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
		  else echo "<input type=\"radio\" name=\"observasi8\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
		  echo "&nbsp;&nbsp;&nbsp;&nbsp;";
		  if ($data_dops[observasi_8]=="0")
		    echo "<input type=\"radio\" name=\"observasi8\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
		  else echo "<input type=\"radio\" name=\"observasi8\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
		  echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek8\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_dops[aspek_8]\" id=\"aspek8\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 9
		echo "<tr>";
		  echo "<td align=center>9</td>";
		  echo "<td>Kecakapan komunikasi</td>";
		  echo "<td align=center>";
		  if ($data_dops[observasi_9]=="1")
		    echo "<input type=\"radio\" name=\"observasi9\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
		  else echo "<input type=\"radio\" name=\"observasi9\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
		  echo "&nbsp;&nbsp;&nbsp;&nbsp;";
		  if ($data_dops[observasi_9]=="0")
		    echo "<input type=\"radio\" name=\"observasi9\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
		  else echo "<input type=\"radio\" name=\"observasi9\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
		  echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek9\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_dops[aspek_9]\" id=\"aspek9\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 10
		echo "<tr>";
		  echo "<td align=center>10</td>";
		  echo "<td>Mempertimbangkan kondisi pasien / profesionalisme</td>";
		  echo "<td align=center>";
		  if ($data_dops[observasi_10]=="1")
		    echo "<input type=\"radio\" name=\"observasi10\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
		  else echo "<input type=\"radio\" name=\"observasi10\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
		  echo "&nbsp;&nbsp;&nbsp;&nbsp;";
		  if ($data_dops[observasi_10]=="0")
		    echo "<input type=\"radio\" name=\"observasi10\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
		  else echo "<input type=\"radio\" name=\"observasi10\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
		  echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek10\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_dops[aspek_10]\" id=\"aspek10\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 11
		echo "<tr>";
		  echo "<td align=center>11</td>";
		  echo "<td>Kemampuan secara keseluruhan dalam melakukan tindak medik</td>";
		  echo "<td align=center>";
		  if ($data_dops[observasi_11]=="1")
		    echo "<input type=\"radio\" name=\"observasi11\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
		  else echo "<input type=\"radio\" name=\"observasi11\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
		  echo "&nbsp;&nbsp;&nbsp;&nbsp;";
		  if ($data_dops[observasi_11]=="0")
		    echo "<input type=\"radio\" name=\"observasi11\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
		  else echo "<input type=\"radio\" name=\"observasi11\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
		  echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek11\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_dops[aspek_11]\" id=\"aspek11\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//Rata Nilai
		echo "<tr>";
			echo "<td align=right colspan=3>Rata-Rata Nilai (Jumlah Nilai / Jumlah Observasi)</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_rata\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_dops[nilai_rata]\" id=\"nilai_rata\" required/></td>";
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
			echo "<td><textarea name=\"ub_bagus\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\">$data_dops[ub_bagus]</textarea></td>";
			echo "<td><textarea name=\"ub_perbaikan\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\">$data_dops[ub_perbaikan]</textarea></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td colspan=2>Saran:<br><textarea name=\"saran\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\">$data_dops[saran]</textarea></td>";
		echo "</tr>";
		///Catatan
		echo "<tr><td colspan=2><b>Catatan:</b></td></tr>";
		echo "<tr><td colspan=2>Waktu Penilaian Diskusi Kasus:</td></tr>";
		echo "<tr>";
			echo "<td>&nbsp;&nbsp;Observasi</td>";
			echo "<td>";
			echo "<input type=\"number\" step=\"5\" min=\"0\" max=\"1500\" name=\"waktu_observasi\" style=\"width:20%;font-size:0.85em\" value=\"$data_dops[waktu_observasi]\" required/>&nbsp;&nbsp;menit<br>";
			echo "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>&nbsp;&nbsp;Memberikan umpan balik</td>";
			echo "<td>";
			echo "<input type=\"number\" step=\"5\" min=\"0\" max=\"1500\" name=\"waktu_ub\" style=\"width:20%;font-size:0.85em\" value=\"$data_dops[waktu_ub]\" required/>&nbsp;&nbsp;menit<br>";
			echo "</td>";
		echo "</tr>";
		echo "</table><br>";

		echo "<br><br><center><<< APPROVAL >>><br><br>";
		echo "<table>";
		echo "<tr class=\"ganjil\"><td>Nama Dosen/Dokter/Residen</td><td>$data_dosen[nama], $data_dosen[gelar]<br>($data_dosen[nip])</td></tr>";
		echo "<tr class=\"genap\"><td>Password Approval</td>";
		echo "<td>";
		?>
		<input type="text" name="dosenpass" class="inputpass" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" />
		<?php
		echo "</td></tr>";
		echo "<tr class=\"ganjil\"><td colspan=\"2\">atau</td></tr>";
		echo "<tr class=\"genap\"><td>";
		echo "Masukkan OTP Dosen/Dokter/Residen</td><td>";
		?>
		<input name="dosenpin" autocomplete="off" type="text" style="width:250px"/><br>
		<?php
		echo "</td></tr>";
		echo "<tr class=\"ganjil\"><td colspan=\"2\">atau</td></tr>";
		echo "<tr class=\"genap\"><td>";
		echo "Scanning QR Code<br><font style=\"font-size:0.625em\"><i>(gunakan smartphone)</i></font></td><td>";
		?>
		<input type=text name="dosenqr" size=16 placeholder="Tracking QR-Code" class=qrcode-text style="width:250px" />
		<label class=qrcode-text-btn>
		  <input type=file accept="image/*" capture=environment onchange="openQRCamera(this);" tabindex=-1>
		</label>
		<?php
		echo "</td></tr></table><br><br>";

		echo "<input type=\"submit\" class=\"submit1\" name=\"cancel\" value=\"CANCEL\" formnovalidate>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" class=\"submit1\" name=\"approve\" value=\"APPROVE\">";
    echo "</form>";

		if ($_POST[cancel]=="CANCEL")
		{
			echo "
			<script>
				window.location.href=\"penilaian_anestesi.php\";
			</script>
			";
		}

		if ($_POST[approve]=="APPROVE")
		{
			$dosen_dops = mysqli_fetch_array(mysqli_query($con,"SELECT `dosen` FROM `anestesi_nilai_dops` WHERE `id`='$_POST[id]'"));
			$user_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `password` FROM `admin` WHERE `username`='$dosen_dops[dosen]'"));
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `pin`,`qr` FROM `dosen` WHERE `nip`='$dosen_dops[dosen]'"));
			$dosenpass_md5 = md5($_POST['dosenpass']);
			if (($_POST['dosenpass']!="" AND $dosenpass_md5==$user_dosen['password'])
			 OR ($_POST[dosenpin]!="" AND $_POST[dosenpin]==$data_dosen[pin])
			 OR ($_POST[dosenqr]!="" AND $_POST[dosenqr]==$data_dosen[qr]))
			{
				$aspek1 = number_format($_POST[observasi1]*$_POST[aspek1],2);
				$aspek2 = number_format($_POST[observasi2]*$_POST[aspek2],2);
				$aspek3 = number_format($_POST[observasi3]*$_POST[aspek3],2);
				$aspek4 = number_format($_POST[observasi4]*$_POST[aspek4],2);
				$aspek5 = number_format($_POST[observasi5]*$_POST[aspek5],2);
				$aspek6 = number_format($_POST[observasi6]*$_POST[aspek6],2);
				$aspek7 = number_format($_POST[observasi7]*$_POST[aspek7],2);
				$aspek8 = number_format($_POST[observasi8]*$_POST[aspek8],2);
				$aspek9 = number_format($_POST[observasi9]*$_POST[aspek9],2);
				$aspek10 = number_format($_POST[observasi10]*$_POST[aspek10],2);
				$aspek11 = number_format($_POST[observasi11]*$_POST[aspek11],2);

				$tindak_medik = addslashes($_POST[tindak_medik]);
				$ub_bagus = addslashes($_POST[ub_bagus]);
				$ub_perbaikan = addslashes($_POST[ub_perbaikan]);
				$saran = addslashes($_POST[saran]);
				$jml_observasi = $_POST[observasi1]+$_POST[observasi2]+$_POST[observasi3]+$_POST[observasi4]+$_POST[observasi5]+$_POST[observasi6]+$_POST[observasi7]+$_POST[observasi8]+$_POST[observasi9]+$_POST[observasi10]+$_POST[observasi11];
				$nilai_total = $_POST[observasi1]*$_POST[aspek1]+$_POST[observasi2]*$_POST[aspek2]+$_POST[observasi3]*$_POST[aspek3]+$_POST[observasi4]*$_POST[aspek4]+$_POST[observasi5]*$_POST[aspek5]+$_POST[observasi6]*$_POST[aspek6]+$_POST[observasi7]*$_POST[aspek7]
											+$_POST[observasi8]*$_POST[aspek8]+$_POST[observasi9]*$_POST[aspek9]+$_POST[observasi10]*$_POST[aspek10]+$_POST[observasi11]*$_POST[aspek11];
				if ($jml_observasi==0) $nilai_rata = 0;
				else $nilai_rata = $nilai_total/$jml_observasi;
				$nilai_rata = number_format($nilai_rata,2);

				$update_dops=mysqli_query($con,"UPDATE `anestesi_nilai_dops` SET
					`tgl_ujian`='$_POST[tanggal_ujian]',
					`situasi_ruangan`='$_POST[situasi_ruangan]',`tindak_medik`='$tindak_medik',
					`obs_penilai`='$_POST[obs_penilai]', `obs_mhsw`='$_POST[obs_mhsw]',
					`aspek_1`='$aspek1',`observasi_1`='$_POST[observasi1]',
					`aspek_2`='$aspek2',`observasi_2`='$_POST[observasi2]',
					`aspek_3`='$aspek3',`observasi_3`='$_POST[observasi3]',
					`aspek_4`='$aspek4',`observasi_4`='$_POST[observasi4]',
					`aspek_5`='$aspek5',`observasi_5`='$_POST[observasi5]',
					`aspek_6`='$aspek6',`observasi_6`='$_POST[observasi6]',
					`aspek_7`='$aspek7',`observasi_7`='$_POST[observasi7]',
					`aspek_8`='$aspek8',`observasi_8`='$_POST[observasi8]',
					`aspek_9`='$aspek9',`observasi_9`='$_POST[observasi9]',
					`aspek_10`='$aspek10',`observasi_10`='$_POST[observasi10]',
					`aspek_11`='$aspek11',`observasi_11`='$_POST[observasi11]',
					`ub_bagus`='$ub_bagus',`ub_perbaikan`='$ub_perbaikan',`saran`='$saran',
					`waktu_observasi`='$_POST[waktu_observasi]',`waktu_ub`='$_POST[waktu_ub]',
					`nilai_rata`='$nilai_rata', `tgl_approval`='$tgl',`status_approval`='1'
					WHERE `id`='$_POST[id]'");

				echo "
					<script>
					alert(\"Approval SUKSES ...\");
					window.location.href = \"penilaian_anestesi.php\";
	        </script>
					";
			}
			else
			{
				echo "
				<script>
				alert(\"Approval GAGAL ...\");
				window.location.href = \"approve_dops.php?id=\"+\"$_POST[id]\";
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
<script src="../qr_packed.js"></script>
<script type="text/javascript">
	function openQRCamera(node) {
		var reader = new FileReader();
		reader.onload = function() {
			node.value = "";
			qrcode.callback = function(res) {
				if(res instanceof Error) {
					alert("No QR code found. Please make sure the QR code is within the camera's frame and try again.");
				} else {
					node.parentNode.previousElementSibling.value = res;
				}
			};
			qrcode.decode(reader.result);
		};
		reader.readAsDataURL(node.files[0]);
	}
</script>
<script type="text/javascript" src="../jquery_ui/jquery-ui.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.tanggal_ujian').datepicker({ dateFormat: 'yy-mm-dd' });
	});
</script>

<script>
function sum() {
	var aspek1 = document.getElementById('aspek1').value;
	var aspek2 = document.getElementById('aspek2').value;
	var aspek3 = document.getElementById('aspek3').value;
	var aspek4 = document.getElementById('aspek4').value;
	var aspek5 = document.getElementById('aspek5').value;
	var aspek6 = document.getElementById('aspek6').value;
	var aspek7 = document.getElementById('aspek7').value;
	var aspek8 = document.getElementById('aspek8').value;
	var aspek9 = document.getElementById('aspek9').value;
	var aspek10 = document.getElementById('aspek10').value;
	var aspek11 = document.getElementById('aspek11').value;

	var observasi1 = $("input[name=observasi1]:checked").val();
	var observasi2 = $("input[name=observasi2]:checked").val();
	var observasi3 = $("input[name=observasi3]:checked").val();
	var observasi4 = $("input[name=observasi4]:checked").val();
	var observasi5 = $("input[name=observasi5]:checked").val();
	var observasi6 = $("input[name=observasi6]:checked").val();
	var observasi7 = $("input[name=observasi7]:checked").val();
	var observasi8 = $("input[name=observasi8]:checked").val();
	var observasi9 = $("input[name=observasi9]:checked").val();
	var observasi10 = $("input[name=observasi10]:checked").val();
	var observasi11 = $("input[name=observasi11]:checked").val();

	var total1 = parseInt(observasi1)*parseFloat(aspek1) + parseInt(observasi2)*parseFloat(aspek2) + parseInt(observasi3)*parseFloat(aspek3) + parseInt(observasi4)*parseFloat(aspek4) + parseInt(observasi5)*parseFloat(aspek5) + parseInt(observasi6)*parseFloat(aspek6) + parseInt(observasi7)*parseFloat(aspek7);
	var total2 = parseInt(observasi8)*parseFloat(aspek8) + parseInt(observasi9)*parseFloat(aspek9) + parseInt(observasi10)*parseFloat(aspek10) + parseInt(observasi11)*parseFloat(aspek11);
	var total = parseFloat(total1) + parseFloat(total2);

	var pembagi1 = parseInt(observasi1) + parseInt(observasi2) + parseInt(observasi3) + parseInt(observasi4) + parseInt(observasi5) + parseInt(observasi6) + parseInt(observasi7);
	var pembagi2 = parseInt(observasi8) + parseInt(observasi9) + parseInt(observasi10) + parseInt(observasi11);
	var pembagi = parseInt(pembagi1) + parseInt(pembagi2);

	var result = parseFloat(total)/parseInt(pembagi);

			if (!isNaN(result)) {
         document.getElementById('nilai_rata').value = number_format(result,2);
      }

	function number_format (number, decimals, decPoint, thousandsSep) {
  	number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
 		var n = !isFinite(+number) ? 0 : +number
 		var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
 		var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
 		var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
 		var s = ''

 		var toFixedFix = function (n, prec) {
  	var k = Math.pow(10, prec)
  	return '' + (Math.round(n * k) / k)
    	.toFixed(prec)
 		}

 		// @todo: for IE parseFloat(0.55).toFixed(0) = 0;
 		s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.')
 		if (s[0].length > 3) {
  	s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
 		}
 		if ((s[1] || '').length < prec) {
  	s[1] = s[1] || ''
  	s[1] += new Array(prec - s[1].length + 1).join('0')
 		}

 		return s.join(dec)
	}
}
</script>

<!--</body></html>-->
</BODY>
</HTML>
