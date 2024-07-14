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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND $_COOKIE['level']==4)
	{
		if ($_COOKIE['level']==4) {include "menu4.php";}

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN KEDOKTERAN KELUARGA</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">APPROVAL PENILAIAN MINI-CEX</font></h4>";

		$id = $_GET[id];
		$id_state = "M121";
		$data_minicex = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kdk_nilai_minicex` WHERE `id`='$id'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_minicex[nim]'"));


		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";

		$tgl_mulai = $_GET[mulai];
		$tgl_selesai = $_GET[selesai];
		$approval = $_GET[approval];
		$mhsw = $_GET[mhsw];
		echo "<input type=\"hidden\" name=\"tgl_mulai\" value=\"$tgl_mulai\" />";
		echo "<input type=\"hidden\" name=\"tgl_selesai\" value=\"$tgl_selesai\" />";
		echo "<input type=\"hidden\" name=\"approval\" value=\"$approval\" />";
		echo "<input type=\"hidden\" name=\"mhsw\" value=\"$mhsw\" />";

		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";

		//Instansi
		echo "<tr>";
			echo "<td style=\"width:40%\">Instansi</td>";
			echo "<td style=\"width:60%\">$data_minicex[instansi]</td>";
		echo "</tr>";
		//Lokasi Puskesmas/Klinik
		echo "<tr>";
			echo "<td>Nama Puskesmas / Klinik Pratama</td>";
			echo "<td>$data_minicex[lokasi]</td>";
		echo "</tr>";
		//DPJP
		echo "<tr>";
			echo "<td>Nama Penilai/DPJP</td>";
			echo "<td>";
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_minicex[dosen]'"));
			echo "$data_dosen[nama], $data_dosen[gelar] ($data_dosen[nip])";
			echo "</td>";
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
		//Tahap
		echo "<tr>";
			echo "<td>Tahap</td>";
			echo "<td>Kepaniteraan Kedokteran Keluarga</td>";
		echo "</tr>";
		//Ujian ke-
		echo "<tr>";
			echo "<td>Ujian ke-</td>";
			echo "<td>";
			if ($data_minicex[no_ujian]=="1")
				echo "<input type=\"radio\" name=\"no_ujian\" value=\"1\" checked/>&nbsp;&nbsp;1";
			else echo "<input type=\"radio\" name=\"no_ujian\" value=\"1\" />&nbsp;&nbsp;1";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[no_ujian]=="2")
				echo "<input type=\"radio\" name=\"no_ujian\" value=\"2\" checked/>&nbsp;&nbsp;2";
			else echo "<input type=\"radio\" name=\"no_ujian\" value=\"2\" />&nbsp;&nbsp;2";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[no_ujian]=="3")
				echo "<input type=\"radio\" name=\"no_ujian\" value=\"3\" checked/>&nbsp;&nbsp;3";
			else echo "<input type=\"radio\" name=\"no_ujian\" value=\"3\" />&nbsp;&nbsp;3";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[no_ujian]=="4")
				echo "<input type=\"radio\" name=\"no_ujian\" value=\"4\" checked/>&nbsp;&nbsp;4";
			else echo "<input type=\"radio\" name=\"no_ujian\" value=\"4\" />&nbsp;&nbsp;4";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[no_ujian]=="5")
				echo "<input type=\"radio\" name=\"no_ujian\" value=\"5\" checked/>&nbsp;&nbsp;5";
			else echo "<input type=\"radio\" name=\"no_ujian\" value=\"5\" />&nbsp;&nbsp;5";
			echo "</td>";
		echo "</tr>";
		//Problem Pasien/Diagnosis
		echo "<tr>";
			echo "<td>Problem Pasien/Diagnosis</td>";
			echo "<td><textarea name=\"diagnosis\" style=\"width:97%;font-family:Tahoma;font-size:1em\" value=\"$data_minicex[diagnosis]\" required>$data_minicex[diagnosis]</textarea></td>";
		echo "</tr>";
		//Situasi Ruangan
		echo "<tr>";
			echo "<td>Situasi Ruangan</td>";
			echo "<td>";
			if ($data_minicex[situasi_ruangan]=="Rawat Jalan")
				echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Jalan\" checked/>&nbsp;&nbsp;Rawat Jalan";
			else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Jalan\" />&nbsp;&nbsp;Rawat Jalan";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[situasi_ruangan]=="Rawat Inap")
				echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Inap\" checked/>&nbsp;&nbsp;Rawat Inap";
			else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Inap\" />&nbsp;&nbsp;Rawat Inap";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[situasi_ruangan]=="IRD")
				echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"IRD\" checked/>&nbsp;&nbsp;IRD";
			else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"IRD\" />&nbsp;&nbsp;IRD";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[situasi_ruangan]=="Lain-lain")
				echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Lain-lain\" checked/>&nbsp;&nbsp;Lain-lain";
			else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Lain-lain\" />&nbsp;&nbsp;Lain-lain";
			echo "</td>";
		echo "</tr>";
		//Umur Pasien
		echo "<tr>";
			echo "<td>Umur Pasien</td>";
			echo "<td><input type=\"number\" step=\"1\" min=\"0\" max=\"150\" name=\"umur_pasien\" style=\"width:10%;font-size:0.85em\" value=\"$data_minicex[umur_pasien]\" required/>&nbsp;&nbsp;tahun</td>";
		echo "</tr>";
		//Jenis Kelamin Pasien
		echo "<tr>";
			echo "<td>Jenis Kelamin Pasien</td>";
			echo "<td>";
			if ($data_minicex[jk_pasien]=="Laki-Laki")
				echo "<input type=\"radio\" name=\"jk_pasien\" value=\"Laki-Laki\" checked/>&nbsp;&nbsp;Laki-Laki";
			else echo "<input type=\"radio\" name=\"jk_pasien\" value=\"Laki-Laki\" />&nbsp;&nbsp;Laki-Laki";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[jk_pasien]=="Perempuan")
				echo "<input type=\"radio\" name=\"jk_pasien\" value=\"Perempuan\" checked />&nbsp;&nbsp;Perempuan";
			else echo "<input type=\"radio\" name=\"jk_pasien\" value=\"Perempuan\" />&nbsp;&nbsp;Perempuan";
			echo "</td>";
		echo "</tr>";
		//Status Pasien
		echo "<tr>";
			echo "<td>Status Pasien</td>";
			echo "<td>";
			if ($data_minicex[status_pasien]=="Baru")
				echo "<input type=\"radio\" name=\"status_pasien\" value=\"Baru\" checked/>&nbsp;&nbsp;Baru";
			else echo "<input type=\"radio\" name=\"status_pasien\" value=\"Baru\" />&nbsp;&nbsp;Baru";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[status_pasien]=="Follow-up")
				echo "<input type=\"radio\" name=\"status_pasien\" value=\"Follow-up\" checked/>&nbsp;&nbsp;Follow-up";
			else echo "<input type=\"radio\" name=\"status_pasien\" value=\"Follow-up\" />&nbsp;&nbsp;Follow-up";
			echo "</td>";
		echo "</tr>";
		//Tingkat Kesulitan
		echo "<tr>";
			echo "<td>Tingkat Kesulitan</td>";
			echo "<td>";
			if ($data_minicex[tingkat_kesulitan]=="Rendah")
				echo "<input type=\"radio\" name=\"tingkat_kesulitan\" value=\"Rendah\" checked/>&nbsp;&nbsp;Rendah";
			else echo "<input type=\"radio\" name=\"tingkat_kesulitan\" value=\"Rendah\" />&nbsp;&nbsp;Rendah";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[tingkat_kesulitan]=="Sedang")
				echo "<input type=\"radio\" name=\"tingkat_kesulitan\" value=\"Sedang\" checked/>&nbsp;&nbsp;Sedang";
			else echo "<input type=\"radio\" name=\"tingkat_kesulitan\" value=\"Sedang\" />&nbsp;&nbsp;Sedang";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[tingkat_kesulitan]=="Tinggi")
				echo "<input type=\"radio\" name=\"tingkat_kesulitan\" value=\"Tinggi\" checked/>&nbsp;&nbsp;Tinggi";
			else echo "<input type=\"radio\" name=\"tingkat_kesulitan\" value=\"Tinggi\" />&nbsp;&nbsp;Tinggi";
			echo "</td>";
		echo "</tr>";
		//Fokus Kasus
		echo "<tr>";
			echo "<td>Fokus Kasus</td>";
			echo "<td>";
			if ($data_minicex[fokus_kasus]=="Anamnesis")
				echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Anamnesis\" checked/>&nbsp;&nbsp;Anamnesis<br>";
			else echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Anamnesis\" />&nbsp;&nbsp;Anamnesis<br>";
			if ($data_minicex[fokus_kasus]=="Pemeriksaan fisik")
				echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Pemeriksaan fisik\" checked/>&nbsp;&nbsp;Pemeriksaan fisik<br>";
			else echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Pemeriksaan fisik\" />&nbsp;&nbsp;Pemeriksaan fisik<br>";
			if ($data_minicex[fokus_kasus]=="Diagnosis")
				echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Diagnosis\" checked/>&nbsp;&nbsp;Diagnosis<br>";
			else echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Diagnosis\" />&nbsp;&nbsp;Diagnosis<br>";
			if ($data_minicex[fokus_kasus]=="Terapi")
				echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Terapi\" checked/>&nbsp;&nbsp;Terapi<br>";
			else echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Terapi\" />&nbsp;&nbsp;Terapi<br>";
			if ($data_minicex[fokus_kasus]=="Konseling")
				echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Konseling\" checked/>&nbsp;&nbsp;Konseling";
			else echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Konseling\" />&nbsp;&nbsp;Konseling";
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
			echo "<td>Kemampuan wawancara medis</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_1]=="1")
				echo "<input type=\"radio\" name=\"observasi_1\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi_1\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[observasi_1]=="0")
				echo "<input type=\"radio\" name=\"observasi_1\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi_1\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_1\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_1]\" id=\"aspek_1\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>Kemampuan pemeriksaan fisik</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_2]=="1")
				echo "<input type=\"radio\" name=\"observasi_2\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi_2\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[observasi_2]=="0")
				echo "<input type=\"radio\" name=\"observasi_2\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi_2\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_2\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_2]\" id=\"aspek_2\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>Kualitas humanistik/profesionalisme</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_3]=="1")
				echo "<input type=\"radio\" name=\"observasi_3\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi_3\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[observasi_3]=="0")
				echo "<input type=\"radio\" name=\"observasi_3\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi_3\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_3\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_3]\" id=\"aspek_3\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 4
		echo "<tr>";
			echo "<td align=center>4</td>";
			echo "<td>Keputusan klinis/diagnostis</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_4]=="1")
				echo "<input type=\"radio\" name=\"observasi_4\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi_4\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[observasi_4]=="0")
				echo "<input type=\"radio\" name=\"observasi_4\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi_4\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_4\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_4]\" id=\"aspek_4\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 5
		echo "<tr>";
			echo "<td align=center>5</td>";
			echo "<td>Kemampuan mengelola pasien</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_5]=="1")
				echo "<input type=\"radio\" name=\"observasi_5\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi_5\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[observasi_5]=="0")
				echo "<input type=\"radio\" name=\"observasi_5\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi_5\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_5\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_5]\" id=\"aspek_5\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 6
		echo "<tr>";
			echo "<td align=center>6</td>";
			echo "<td>Kemampuan konseling</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_6]=="1")
				echo "<input type=\"radio\" name=\"observasi_6\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi_6\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[observasi_6]=="0")
				echo "<input type=\"radio\" name=\"observasi_6\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi_6\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_6\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_6]\" id=\"aspek_6\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 7
		echo "<tr>";
			echo "<td align=center>7</td>";
			echo "<td>Organisasi/efisiensi</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_7]=="1")
				echo "<input type=\"radio\" name=\"observasi_7\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi_7\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[observasi_7]=="0")
				echo "<input type=\"radio\" name=\"observasi_7\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi_7\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_7\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_7]\" id=\"aspek_7\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 8
		echo "<tr>";
			echo "<td align=center>8</td>";
			echo "<td>Kompetensi klinis keseluruhan</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_8]=="1")
				echo "<input type=\"radio\" name=\"observasi_8\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi_8\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[observasi_8]=="0")
				echo "<input type=\"radio\" name=\"observasi_8\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi_8\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_8\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_8]\" id=\"aspek_8\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//Rata Nilai
		echo "<tr>";
			echo "<td align=right colspan=3>Rata-Rata Nilai (Jumlah Nilai / Jumlah Observasi)</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_rata\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_minicex[nilai_rata]\" id=\"nilai_rata\" required/></td>";
		echo "</tr>";
		echo "<tr><td colspan=4><font style=\"font-size:0.75em;\"><i>Keterangan: Nilai Batas Lulus (NBL) = 70</i></font></td></tr>";
		echo "</table><br><br>";

		//Umpan Balik
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr><td colspan=2 align=center><b>UMPAN BALIK KOMPETENSI KLINIS</b></td></tr>";
		echo "<tr>";
			echo "<td align=center style=\"width:50%\">Sudah bagus</td>";
			echo "<td align=center style=\"width:50%\">Perlu perbaikan</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td><textarea name=\"ub_bagus\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\">$data_minicex[ub_bagus]</textarea></td>";
			echo "<td><textarea name=\"ub_perbaikan\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\">$data_minicex[ub_perbaikan]</textarea></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td colspan=2>Action plan yang disetujui bersama:<br><textarea name=\"action_plan\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\">$data_minicex[action_plan]</textarea></td>";
		echo "</tr>";
		echo "</table><br><br>";

		//Catatan
		echo "<table border=0 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr><td colspan=2><b>Catatan:</b></td></tr>";
		echo "<tr><td colspan=2>1. Waktu Mini-CEX:</td></tr>";
		echo "<tr>";
			echo "<td style=\"width:30%;padding:5px 5px 5px 25px;\">Observasi</td>";
			echo "<td style=\"width:70%\">";
			echo ": <input type=\"number\" step=\"5\" min=\"0\" max=\"1500\" name=\"waktu_observasi\" style=\"width:10%;font-size:0.85em\" value=\"$data_minicex[waktu_observasi]\" required/>&nbsp;&nbsp;menit<br>";
			echo "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td style=\"width:30%;padding:5px 5px 5px 25px;\">Memberikan umpan balik</td>";
			echo "<td style=\"width:70%\">";
			echo ": <input type=\"number\" step=\"5\" min=\"0\" max=\"1500\" name=\"waktu_ub\" style=\"width:10%;font-size:0.85em\" value=\"$data_minicex[waktu_ub]\" required/>&nbsp;&nbsp;menit<br>";
			echo "</td>";
		echo "</tr>";
		echo "<tr><td colspan=2>2. Kepuasan penilai terhadap Mini-CEX:</td></tr>";
		echo "<tr><td colspan=2 style=\"padding:5px 5px 5px 25px;\">";
		if ($data_minicex[kepuasan_penilai]=="Kurang sekali")
			echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Kurang sekali\" checked />&nbsp;&nbsp;Kurang sekali&nbsp;&nbsp;&nbsp;&nbsp;";
		else echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Kurang sekali\" />&nbsp;&nbsp;Kurang sekali&nbsp;&nbsp;&nbsp;&nbsp;";
		if ($data_minicex[kepuasan_penilai]=="Kurang")
			echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Kurang\" checked />&nbsp;&nbsp;Kurang&nbsp;&nbsp;&nbsp;&nbsp;";
		else echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Kurang\" />&nbsp;&nbsp;Kurang&nbsp;&nbsp;&nbsp;&nbsp;";
		if ($data_minicex[kepuasan_penilai]=="Cukup")
			echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Cukup\" checked />&nbsp;&nbsp;Cukup&nbsp;&nbsp;&nbsp;&nbsp;";
		else echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Cukup\" />&nbsp;&nbsp;Cukup&nbsp;&nbsp;&nbsp;&nbsp;";
		if ($data_minicex[kepuasan_penilai]=="Baik")
			echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Baik\" checked />&nbsp;&nbsp;Baik&nbsp;&nbsp;&nbsp;&nbsp;";
		else echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Baik\" />&nbsp;&nbsp;Baik&nbsp;&nbsp;&nbsp;&nbsp;";
		if ($data_minicex[kepuasan_penilai]=="Baik sekali")
			echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Baik sekali\" checked />&nbsp;&nbsp;Baik sekali";
		else echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Baik sekali\" />&nbsp;&nbsp;Baik sekali";
		echo "</td></tr>";
		echo "<tr><td colspan=2>2. Kepuasan residen terhadap Mini-CEX:</td></tr>";
		echo "<tr><td colspan=2 style=\"padding:5px 5px 5px 25px;\">";
		if ($data_minicex[kepuasan_residen]=="Kurang sekali")
			echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Kurang sekali\" checked/>&nbsp;&nbsp;Kurang sekali&nbsp;&nbsp;&nbsp;&nbsp;";
		else echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Kurang sekali\" />&nbsp;&nbsp;Kurang sekali&nbsp;&nbsp;&nbsp;&nbsp;";
		if ($data_minicex[kepuasan_residen]=="Kurang")
			echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Kurang\" checked/>&nbsp;&nbsp;Kurang&nbsp;&nbsp;&nbsp;&nbsp;";
		else echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Kurang\" />&nbsp;&nbsp;Kurang&nbsp;&nbsp;&nbsp;&nbsp;";
		if ($data_minicex[kepuasan_residen]=="Cukup")
			echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Cukup\" checked/>&nbsp;&nbsp;Cukup&nbsp;&nbsp;&nbsp;&nbsp;";
		else echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Cukup\" />&nbsp;&nbsp;Cukup&nbsp;&nbsp;&nbsp;&nbsp;";
		if ($data_minicex[kepuasan_residen]=="Baik")
			echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Baik\" checked/>&nbsp;&nbsp;Baik&nbsp;&nbsp;&nbsp;&nbsp;";
		else echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Baik\" />&nbsp;&nbsp;Baik&nbsp;&nbsp;&nbsp;&nbsp;";
		if ($data_minicex[kepuasan_residen]=="Baik sekali")
			echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Baik sekali\" checked/>&nbsp;&nbsp;Baik sekali";
		else echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Baik sekali\" />&nbsp;&nbsp;Baik sekali";
		echo "</td></tr>";
		echo "</table><br>";

		echo "<br><center><input type=\"submit\" class=\"submit1\" name=\"cancel\" value=\"CANCEL\" formnovalidate>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		echo "<input type=\"submit\" class=\"submit1\" name=\"approve\" value=\"APPROVE\"></center>";
		echo "</form><br><br></fieldset>";

		if ($_POST[cancel]=="CANCEL")
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

		if ($_POST[approve]=="APPROVE")
		{
			$tgl_mulai=$_POST[tgl_mulai];
			$tgl_selesai=$_POST[tgl_selesai];
			$approval=$_POST[approval];
			$mhsw=$_POST[mhsw];

			$aspek1 = number_format($_POST[observasi_1]*$_POST[aspek_1],2);
			$aspek2 = number_format($_POST[observasi_2]*$_POST[aspek_2],2);
			$aspek3 = number_format($_POST[observasi_3]*$_POST[aspek_3],2);
			$aspek4 = number_format($_POST[observasi_4]*$_POST[aspek_4],2);
			$aspek5 = number_format($_POST[observasi_5]*$_POST[aspek_5],2);
			$aspek6 = number_format($_POST[observasi_6]*$_POST[aspek_6],2);
			$aspek7 = number_format($_POST[observasi_7]*$_POST[aspek_7],2);
			$aspek8 = number_format($_POST[observasi_8]*$_POST[aspek_8],2);

			$diagnosis = addslashes($_POST[diagnosis]);
			$ub_bagus = addslashes($_POST[ub_bagus]);
			$ub_perbaikan = addslashes($_POST[ub_perbaikan]);
			$action_plan = addslashes($_POST[action_plan]);

			$jml_observasi = $_POST[observasi_1]+$_POST[observasi_2]+$_POST[observasi_3]+$_POST[observasi_4]+$_POST[observasi_5]+$_POST[observasi_6]+$_POST[observasi_7]+$_POST[observasi_8];
			$nilai_total = $_POST[observasi_1]*$_POST[aspek_1]+$_POST[observasi_2]*$_POST[aspek_2]+$_POST[observasi_3]*$_POST[aspek_3]+$_POST[observasi_4]*$_POST[aspek_4]+$_POST[observasi_5]*$_POST[aspek_5]+$_POST[observasi_6]*$_POST[aspek_6]+$_POST[observasi_7]*$_POST[aspek_7]+$_POST[observasi_8]*$_POST[aspek_8];
			if ($jml_observasi==0) $nilai_rata = 0;
			else $nilai_rata = $nilai_total/$jml_observasi;
			$nilai_rata = number_format($nilai_rata,2);

			$update_minicex=mysqli_query($con,"UPDATE `kdk_nilai_minicex` SET
				`no_ujian`='$_POST[no_ujian]',
				`diagnosis`='$_POST[diagnosis]',`situasi_ruangan`='$_POST[situasi_ruangan]',
				`umur_pasien`='$_POST[umur_pasien]',`jk_pasien`='$_POST[jk_pasien]',
				`status_pasien`='$_POST[status_pasien]',`tingkat_kesulitan`='$_POST[tingkat_kesulitan]',
				`fokus_kasus`='$_POST[fokus_kasus]',
				`aspek_1`='$aspek1',`observasi_1`='$_POST[observasi_1]',
				`aspek_2`='$aspek2',`observasi_2`='$_POST[observasi_2]',
				`aspek_3`='$aspek3',`observasi_3`='$_POST[observasi_3]',
				`aspek_4`='$aspek4',`observasi_4`='$_POST[observasi_4]',
				`aspek_5`='$aspek5',`observasi_5`='$_POST[observasi_5]',
				`aspek_6`='$aspek6',`observasi_6`='$_POST[observasi_6]',
				`aspek_7`='$aspek7',`observasi_7`='$_POST[observasi_7]',
				`aspek_8`='$aspek8',`observasi_8`='$_POST[observasi_8]',
				`nilai_rata`='$nilai_rata',`ub_bagus`='$_POST[ub_bagus]',`ub_perbaikan`='$_POST[ub_perbaikan]',
				`action_plan`='$action_plan',
				`waktu_observasi`='$_POST[waktu_observasi]',`waktu_ub`='$_POST[waktu_ub]',
				`kepuasan_penilai`='$_POST[kepuasan_penilai]',`kepuasan_residen`='$_POST[kepuasan_residen]',
				`tgl_approval`='$tgl',`status_approval`='1'
				WHERE `id`='$_POST[id]'");
			echo "
				<script>
					window.location.href=\"penilaian_kdk_dosen.php?mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\";
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

<script>
function sum() {
      var aspek1 = document.getElementById('aspek_1').value;
			var aspek2 = document.getElementById('aspek_2').value;
			var aspek3 = document.getElementById('aspek_3').value;
			var aspek4 = document.getElementById('aspek_4').value;
			var aspek5 = document.getElementById('aspek_5').value;
			var aspek6 = document.getElementById('aspek_6').value;
			var aspek7 = document.getElementById('aspek_7').value;
			var aspek8 = document.getElementById('aspek_8').value;
			var observasi1 = $("input[name=observasi_1]:checked").val();
			var observasi2 = $("input[name=observasi_2]:checked").val();
			var observasi3 = $("input[name=observasi_3]:checked").val();
			var observasi4 = $("input[name=observasi_4]:checked").val();
			var observasi5 = $("input[name=observasi_5]:checked").val();
			var observasi6 = $("input[name=observasi_6]:checked").val();
			var observasi7 = $("input[name=observasi_7]:checked").val();
			var observasi8 = $("input[name=observasi_8]:checked").val();

			var total = parseInt(observasi1)*parseFloat(aspek1) + parseInt(observasi2)*parseFloat(aspek2) + parseInt(observasi3)*parseFloat(aspek3) + parseInt(observasi4)*parseFloat(aspek4) + parseInt(observasi5)*parseFloat(aspek5) + parseInt(observasi6)*parseFloat(aspek6) + parseInt(observasi7)*parseFloat(aspek7) + parseInt(observasi8)*parseFloat(aspek8);
			var pembagi = parseInt(observasi1) + parseInt(observasi2) + parseInt(observasi3) + parseInt(observasi4) + parseInt(observasi5) + parseInt(observasi6) + parseInt(observasi7) + parseInt(observasi8);
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
