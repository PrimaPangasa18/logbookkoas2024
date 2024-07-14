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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) NEUROLOGI</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">PREVIEW NILAI UJIAN MINI-CEX<p>Kepaniteraan (Stase) Neurologi</font></h4>";

		$id_stase = "M092";
		$id = $_GET['id'];
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
		$stase_id = "stase_".$id_stase;
		$data_stase_mhsw = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'");
		$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);
		$data_minicex = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `neuro_nilai_minicex` WHERE `id`='$id'"));

		$tanggal_ujian = tanggal_indo($data_minicex[tgl_ujian]);
		$tanggal_approval = tanggal_indo($data_minicex[tgl_approval]);
		$awal_periode = tanggal_indo($data_minicex[tgl_awal]);
		$akhir_periode = tanggal_indo($data_minicex[tgl_akhir]);

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
		//Periode Kegiatan
		echo "<tr>";
			echo "<td>Periode Kegiatan</td>";
			echo "<td>$awal_periode s.d. $akhir_periode</td>";
		echo "</tr>";
		//Tanggal Ujian
		echo "<tr>";
			echo "<td>Tanggal Ujian</td>";
			echo "<td>$tanggal_ujian</td>";
		echo "</tr>";
		//Dosen Penguji
		echo "<tr>";
			echo "<td>Dosen Penguji</td>";
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_minicex[dosen]'"));
			echo "<td>$data_dosen[nama], $data_dosen[gelar] ($data_dosen[nip])</td>";
		echo "</tr>";
		//Situasi Ruangan
		echo "<tr>";
			echo "<td>Situasi Ruangan</td>";
			echo "<td>";
			if ($data_minicex[situasi_ruangan]=="IRD")
				echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"IRD\" checked />&nbsp;&nbsp;IRD";
			else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"IRD\" disabled />&nbsp;&nbsp;IRD";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[situasi_ruangan]=="Rawat Jalan")
				echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Jalan\" checked />&nbsp;&nbsp;Rawat Jalan";
			else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Jalan\" disabled />&nbsp;&nbsp;Rawat Jalan";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[situasi_ruangan]=="Lain-lain")
				echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Lain-lain\" checked />&nbsp;&nbsp;Lain-lain";
			else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Lain-lain\" disabled />&nbsp;&nbsp;Lain-lain";
			echo "</td>";
		echo "</tr>";
		//Umur Pasien
		echo "<tr>";
			echo "<td>Umur Pasien</td>";
			echo "<td>$data_minicex[umur_pasien] tahun</td>";
		echo "</tr>";
		//Jenis Kelamin Pasien
		echo "<tr>";
			echo "<td>Jenis Kelamin Pasien</td>";
			echo "<td>";
			if ($data_minicex[jk_pasien]=="Laki-Laki") echo "<input type=\"radio\" name=\"jk_pasien\" value=\"Laki-Laki\" checked/>&nbsp;&nbsp;Laki-Laki&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			else echo "<input type=\"radio\" name=\"jk_pasien\" value=\"Laki-Laki\" disabled/>&nbsp;&nbsp;Laki-Laki&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[jk_pasien]=="Perempuan") echo "<input type=\"radio\" name=\"jk_pasien\" value=\"Perempuan\" checked/>&nbsp;&nbsp;Perempuan";
			else echo "<input type=\"radio\" name=\"jk_pasien\" value=\"Perempuan\" disabled/>&nbsp;&nbsp;Perempuan";
			echo "</td>";
		echo "</tr>";
		//Problem/Diagnosis Pasien
		echo "<tr>";
			echo "<td>Problem/Diagnosis Pasien</td>";
			echo "<td><textarea name=\"diagnosis\" style=\"width:97%;font-family:Tahoma;font-size:1em\" required disabled >$data_minicex[diagnosis]</textarea></td>";
		echo "</tr>";
		//Tingkat Kerumitan
		echo "<tr>";
			echo "<td>Tingkat Kerumitan</td>";
			echo "<td>";
			if ($data_minicex[tingkat_kerumitan]=="Rendah")
				echo "<input type=\"radio\" name=\"tingkat_kerumitan\" value=\"Rendah\" checked />&nbsp;&nbsp;Rendah";
			else echo "<input type=\"radio\" name=\"tingkat_kerumitan\" value=\"Rendah\" disabled />&nbsp;&nbsp;Rendah";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[tingkat_kerumitan]=="Sedang")
				echo "<input type=\"radio\" name=\"tingkat_kerumitan\" value=\"Sedang\" checked />&nbsp;&nbsp;Sedang";
			else echo "<input type=\"radio\" name=\"tingkat_kerumitan\" value=\"Sedang\" disabled />&nbsp;&nbsp;Sedang";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[tingkat_kerumitan]=="Tinggi")
				echo "<input type=\"radio\" name=\"tingkat_kerumitan\" value=\"Tinggi\" checked />&nbsp;&nbsp;Tinggi";
			else echo "<input type=\"radio\" name=\"tingkat_kerumitan\" value=\"Tinggi\" disabled />&nbsp;&nbsp;Tinggi";
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
			echo "<th style=\"width:75%\">Aspek Yang Dinilai</th>";
			echo "<th style=\"width:20%\">Nilai (0-100)</th>";
		echo "</thead>";
		//No 1
		echo "<tr>";
			echo "<td align=center>1</td>";
			echo "<td>Kemampuan Wawancara Medis (<i>Medical Interviewing Skills</i>)</td>";
			echo "<td align=center>$data_minicex[aspek_1]</td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>Kemampuan Pemeriksaan Fisik (<i>Physical Examination Skills</i>)</td>";
			echo "<td align=center>$data_minicex[aspek_2]</td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>Kualitas Humanisti / Profesionalisme</td>";
			echo "<td align=center>$data_minicex[aspek_3]</td>";
		echo "</tr>";
		//No 4
		echo "<tr>";
			echo "<td align=center>4</td>";
			echo "<td>Keputusan Klinis</td>";
			echo "<td align=center>$data_minicex[aspek_4]</td>";
		echo "</tr>";
		//No 5
		echo "<tr>";
			echo "<td align=center>5</td>";
			echo "<td>Kemampuan Penatalaksanaan Pasien</td>";
			echo "<td align=center>$data_minicex[aspek_5]</td>";
		echo "</tr>";
		//No 6
		echo "<tr>";
			echo "<td align=center>6</td>";
			echo "<td>Kemampuan Konseling</td>";
			echo "<td align=center>$data_minicex[aspek_6]</td>";
		echo "</tr>";
		//No 7
		echo "<tr>";
			echo "<td align=center>7</td>";
			echo "<td>Organisasi / Efisiensi</td>";
			echo "<td align=center>$data_minicex[aspek_7]</td>";
		echo "</tr>";
		//No 8
		echo "<tr>";
			echo "<td align=center>8</td>";
			echo "<td>Kompetensi Klinis Keseluruhan</td>";
			echo "<td align=center>$data_minicex[aspek_8]</td>";
		echo "</tr>";
		//Rata Nilai
		echo "<tr>";
			echo "<td align=right colspan=2>Rata-Rata Nilai (Total Nilai / 8)</td>";
			echo "<td align=center><b>$data_minicex[nilai_rata]</b></td>";
		echo "</tr>";
		echo "<tr><td colspan=3><font style=\"font-size:0.75em;\"><i>Keterangan: Nilai Batas Lulus (NBL) = 70</i></font></td></tr>";
		echo "</table><br><br>";

		//Umpan Balik
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr><td colspan=2 align=center><b>UMPAN BALIK TERHADAP MINI-CEX</b></td></tr>";
		echo "<tr>";
			echo "<td align=center style=\"width:50%\">Sudah bagus</td>";
			echo "<td align=center style=\"width:50%\">Perlu perbaikan</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td><textarea name=\"ub_bagus\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_minicex[ub_bagus]</textarea></td>";
			echo "<td><textarea name=\"ub_perbaikan\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_minicex[ub_perbaikan]</textarea></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td colspan=2>Saran:<br><textarea name=\"saran\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_minicex[saran]</textarea></td>";
		echo "</tr>";
		//Catatan
		echo "<tr><td colspan=2><b>Catatan:</b></td></tr>";
		echo "<tr><td colspan=2>Waktu Penilaian MINI-CEX:</td></tr>";
		echo "<tr>";
			echo "<td>&nbsp;&nbsp;Observasi</td>";
			echo "<td>";
			echo "$data_minicex[waktu_observasi] menit<br>";
			echo "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>&nbsp;&nbsp;Memberikan umpan balik</td>";
			echo "<td>";
			echo "$data_minicex[waktu_ub] menit<br>";
			echo "</td>";
		echo "</tr>";
		echo "<tr><td colspan=2 align=right><br><i>Status: <b>BELUM DISETUJUI</b><br>";
		echo "Dosen Penguji<p>$data_dosen[nama], $data_dosen[gelar]<br>NIP. $data_dosen[nip]</i>";
		echo "</td></tr>";
		echo "</table><br>";

		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		echo "<br><center><input type=\"submit\" class=\"submit1\" name=\"back\" value=\"BACK\" />";
		echo "<br><br></form></fieldset>";

		if ($_POST[back]=="BACK")
		{
			echo "
					<script>
					window.location.href=\"penilaian_neuro.php\";
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
