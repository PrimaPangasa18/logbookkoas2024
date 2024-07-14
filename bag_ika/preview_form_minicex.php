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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) ILMU KESEHATAN ANAK</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">PREVIEW NILAI MINI-CEX<p>KEPANITERAAN (STASE) ILMU KESEHATAN ANAK</font></h4>";

		$id = $_GET['id'];
		$data_minicex = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `ika_nilai_minicex` WHERE `id`='$id'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_minicex[nim]'"));

		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";

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
		//Jenis Evaluasi
		echo "<tr>";
			echo "<td style=\"width:40%\">Jenis Evaluasi</td>";
			$nama_evaluasi = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `ika_evaluasi_ref` WHERE `id`='$data_minicex[evaluasi]'"));
			echo "<td style=\"width:60%\">$nama_evaluasi[evaluasi]</td>";
		echo "</tr>";
		//Tanggal Ujian
		echo "<tr>";
			$tanggal_ujian = tanggal_indo($data_minicex[tgl_ujian]);
			echo "<td>Tanggal Ujian</td>";
			echo "<td>$tanggal_ujian</td>";
		echo "</tr>";
		//Dosen Penilai / DPJP
		echo "<tr>";
			echo "<td>Dosen Penilai / DPJP</td>";
			echo "<td>";
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_minicex[dosen]'"));
			echo "$data_dosen[nama], $data_dosen[gelar] ($data_dosen[nip])";
			echo "</td>";
		echo "</tr>";
		//Situasi Ruangan
		echo "<tr>";
			echo "<td>Situasi Ruangan</td>";
			echo "<td>";
			if ($data_minicex[situasi_ruangan]=="Rawat Jalan")
				echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Jalan\" checked/>&nbsp;&nbsp;Rawat Jalan";
			else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Jalan\" disabled/>&nbsp;&nbsp;Rawat Jalan";
			echo "<br>";
			if ($data_minicex[situasi_ruangan]=="Rawat Inap")
				echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Inap\" checked/>&nbsp;&nbsp;Rawat Inap";
			else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Inap\" disabled/>&nbsp;&nbsp;Rawat Inap";
			echo "<br>";
			if ($data_minicex[situasi_ruangan]=="IRD")
				echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"IRD\" checked/>&nbsp;&nbsp;IRD";
			else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"IRD\" disabled/>&nbsp;&nbsp;IRD";
			echo "<br>";
			if ($data_minicex[situasi_ruangan]=="Lain-lain")
				echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Lain-lain\" checked/>&nbsp;&nbsp;Lain-lain";
			else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Lain-lain\" disabled/>&nbsp;&nbsp;Lain-lain";
			echo "</td>";
		echo "</tr>";
		//Inisial Pasien
		echo "<tr>";
			echo "<td>Inisial Pasien</td>";
			echo "<td>$data_minicex[inisial_pasien]</td>";
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
			if ($data_minicex[jk_pasien]=="Laki-Laki")
				echo "<input type=\"radio\" name=\"jk_pasien\" value=\"Laki-Laki\" checked/>&nbsp;&nbsp;Laki-Laki";
			else echo "<input type=\"radio\" name=\"jk_pasien\" value=\"Laki-Laki\" disabled/>&nbsp;&nbsp;Laki-Laki";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[jk_pasien]=="Perempuan")
				echo "<input type=\"radio\" name=\"jk_pasien\" value=\"Perempuan\" checked />&nbsp;&nbsp;Perempuan";
			else echo "<input type=\"radio\" name=\"jk_pasien\" value=\"Perempuan\" disabled/>&nbsp;&nbsp;Perempuan";
			echo "</td>";
		echo "</tr>";
		//Problem Pasien/Diagnosis
		echo "<tr>";
			echo "<td>Problem Pasien/Diagnosis</td>";
			echo "<td><textarea name=\"diagnosis\" style=\"width:97%;font-family:Tahoma;font-size:1em\" value=\"$data_minicex[diagnosis]\" disabled>$data_minicex[diagnosis]</textarea></td>";
		echo "</tr>";
		//Tingkat Kesulitan
		echo "<tr>";
			echo "<td>Tingkat Kesulitan</td>";
			echo "<td>";
			if ($data_minicex[tingkat_kesulitan]=="Rendah")
				echo "<input type=\"radio\" name=\"tingkat_kesulitan\" value=\"Rendah\" checked/>&nbsp;&nbsp;Rendah";
			else echo "<input type=\"radio\" name=\"tingkat_kesulitan\" value=\"Rendah\" disabled/>&nbsp;&nbsp;Rendah";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[tingkat_kesulitan]=="Sedang")
				echo "<input type=\"radio\" name=\"tingkat_kesulitan\" value=\"Sedang\" checked/>&nbsp;&nbsp;Sedang";
			else echo "<input type=\"radio\" name=\"tingkat_kesulitan\" value=\"Sedang\" disabled/>&nbsp;&nbsp;Sedang";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[tingkat_kesulitan]=="Tinggi")
				echo "<input type=\"radio\" name=\"tingkat_kesulitan\" value=\"Tinggi\" checked/>&nbsp;&nbsp;Tinggi";
			else echo "<input type=\"radio\" name=\"tingkat_kesulitan\" value=\"Tinggi\" disabled/>&nbsp;&nbsp;Tinggi";
			echo "</td>";
		echo "</tr>";
		//Fokus Kasus
		echo "<tr>";
			echo "<td>Fokus Kasus</td>";
			echo "<td>";
			if ($data_minicex[fokus_kasus]=="Anamnesis")
				echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Anamnesis\" checked/>&nbsp;&nbsp;Anamnesis<br>";
			else echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Anamnesis\" disabled/>&nbsp;&nbsp;Anamnesis<br>";
			if ($data_minicex[fokus_kasus]=="Pemeriksaan fisik")
				echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Pemeriksaan fisik\" checked/>&nbsp;&nbsp;Pemeriksaan fisik<br>";
			else echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Pemeriksaan fisik\" disabled/>&nbsp;&nbsp;Pemeriksaan fisik<br>";
			if ($data_minicex[fokus_kasus]=="Diagnosis")
				echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Diagnosis\" checked/>&nbsp;&nbsp;Diagnosis<br>";
			else echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Diagnosis\" disabled/>&nbsp;&nbsp;Diagnosis<br>";
			if ($data_minicex[fokus_kasus]=="Terapi")
				echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Terapi\" checked/>&nbsp;&nbsp;Terapi<br>";
			else echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Terapi\" disabled/>&nbsp;&nbsp;Terapi<br>";
			if ($data_minicex[fokus_kasus]=="Konseling")
				echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Konseling\" checked/>&nbsp;&nbsp;Konseling";
			else echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Konseling\" disabled/>&nbsp;&nbsp;Konseling";
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
			echo "<td>Kemampuan Wawancara Medis</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_1]=="1")
				echo "<input type=\"radio\" name=\"observasi_1\" value=\"1\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi_1\" value=\"1\" disabled/>&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[observasi_1]=="0")
				echo "<input type=\"radio\" name=\"observasi_1\" value=\"0\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi_1\" value=\"0\" disabled/>&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center>$data_minicex[aspek_1]</td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>Kemampuan Pemeriksaan Fisik</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_2]=="1")
				echo "<input type=\"radio\" name=\"observasi_2\" value=\"1\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi_2\" value=\"1\" disabled/>&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[observasi_2]=="0")
				echo "<input type=\"radio\" name=\"observasi_2\" value=\"0\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi_2\" value=\"0\" disabled/>&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center>$data_minicex[aspek_2]</td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>Kualitas Humanisti / Profesionalisme</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_3]=="1")
				echo "<input type=\"radio\" name=\"observasi_3\" value=\"1\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi_3\" value=\"1\" disabled/>&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[observasi_3]=="0")
				echo "<input type=\"radio\" name=\"observasi_3\" value=\"0\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi_3\" value=\"0\" disabled/>&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center>$data_minicex[aspek_3]</td>";
		echo "</tr>";
		//No 4
		echo "<tr>";
			echo "<td align=center>4</td>";
			echo "<td>Keputusan Klinis</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_4]=="1")
				echo "<input type=\"radio\" name=\"observasi_4\" value=\"1\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi_4\" value=\"1\" disabled/>&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[observasi_4]=="0")
				echo "<input type=\"radio\" name=\"observasi_4\" value=\"0\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi_4\" value=\"0\" disabled/>&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center>$data_minicex[aspek_4]</td>";
		echo "</tr>";
		//No 5
		echo "<tr>";
			echo "<td align=center>5</td>";
			echo "<td>Kemampuan Tatalaksana Kasus</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_5]=="1")
				echo "<input type=\"radio\" name=\"observasi_5\" value=\"1\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi_5\" value=\"1\" disabled/>&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[observasi_5]=="0")
				echo "<input type=\"radio\" name=\"observasi_5\" value=\"0\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi_5\" value=\"0\" disabled/>&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center>$data_minicex[aspek_5]</td>";
		echo "</tr>";
		//No 6
		echo "<tr>";
			echo "<td align=center>6</td>";
			echo "<td>Kemampuan Konseling</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_6]=="1")
				echo "<input type=\"radio\" name=\"observasi_6\" value=\"1\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi_6\" value=\"1\" disabled/>&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[observasi_6]=="0")
				echo "<input type=\"radio\" name=\"observasi_6\" value=\"0\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi_6\" value=\"0\" disabled/>&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center>$data_minicex[aspek_6]</td>";
		echo "</tr>";
		//No 7
		echo "<tr>";
			echo "<td align=center>7</td>";
			echo "<td>Organisasi / Efisiensi</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_7]=="1")
				echo "<input type=\"radio\" name=\"observasi_7\" value=\"1\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi_7\" value=\"1\" disabled/>&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[observasi_7]=="0")
				echo "<input type=\"radio\" name=\"observasi_7\" value=\"0\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi_7\" value=\"0\" disabled/>&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center>$data_minicex[aspek_7]</td>";
		echo "</tr>";
		//No 8
		echo "<tr>";
			echo "<td align=center>8</td>";
			echo "<td>Kompetensi Klinis Keseluruhan</td>";
			echo "<td align=center>";
			if ($data_minicex[observasi_8]=="1")
				echo "<input type=\"radio\" name=\"observasi_8\" value=\"1\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi_8\" value=\"1\" disabled/>&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[observasi_8]=="0")
				echo "<input type=\"radio\" name=\"observasi_8\" value=\"0\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi_8\" value=\"0\" disabled/>&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center>$data_minicex[aspek_8]</td>";
		echo "</tr>";
		//Rata Nilai
		echo "<tr>";
			echo "<td align=right colspan=3>Rata-Rata Nilai (Jumlah Nilai / Jumlah Observasi)</td>";
			echo "<td align=center><b>$data_minicex[nilai_rata]</b></td>";
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
			echo "<td><textarea name=\"ub_bagus\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_minicex[ub_bagus]</textarea></td>";
			echo "<td><textarea name=\"ub_perbaikan\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_minicex[ub_perbaikan]</textarea></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td colspan=2>Rencana tindak lanjut yang disetujui bersama:<br><textarea name=\"action_plan\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_minicex[action_plan]</textarea></td>";
		echo "</tr>";
		echo "</table><br><br>";

		//Catatan
		echo "<table border=0 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr><td colspan=2><b>Catatan:</b></td></tr>";
		echo "<tr><td colspan=2>1. Waktu Mini-CEX:</td></tr>";
		echo "<tr>";
			echo "<td style=\"width:30%;padding:5px 5px 5px 25px;\">Observasi</td>";
			echo "<td style=\"width:70%\">";
			echo ": $data_minicex[waktu_observasi]&nbsp;&nbsp;menit<br>";
			echo "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td style=\"width:30%;padding:5px 5px 5px 25px;\">Memberikan umpan balik</td>";
			echo "<td style=\"width:70%\">";
			echo ": $data_minicex[waktu_ub]&nbsp;&nbsp;menit<br>";
			echo "</td>";
		echo "</tr>";
		echo "<tr><td colspan=2>2. Kepuasan penilai terhadap Mini-CEX:</td></tr>";
		echo "<tr><td colspan=2 style=\"padding:5px 5px 5px 25px;\">";
		if ($data_minicex[kepuasan_penilai]=="Kurang sekali")
			echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Kurang sekali\" checked/>&nbsp;&nbsp;Kurang sekali&nbsp;&nbsp;&nbsp;&nbsp;";
		else echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Kurang sekali\" disabled/>&nbsp;&nbsp;Kurang sekali&nbsp;&nbsp;&nbsp;&nbsp;";
		if ($data_minicex[kepuasan_penilai]=="Kurang")
			echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Kurang\" checked/>&nbsp;&nbsp;Kurang&nbsp;&nbsp;&nbsp;&nbsp;";
		else echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Kurang\" disabled/>&nbsp;&nbsp;Kurang&nbsp;&nbsp;&nbsp;&nbsp;";
		if ($data_minicex[kepuasan_penilai]=="Cukup")
			echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Cukup\" checked/>&nbsp;&nbsp;Cukup&nbsp;&nbsp;&nbsp;&nbsp;";
		else echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Cukup\" disabled/>&nbsp;&nbsp;Cukup&nbsp;&nbsp;&nbsp;&nbsp;";
		if ($data_minicex[kepuasan_penilai]=="Baik")
			echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Baik\" checked/>&nbsp;&nbsp;Baik&nbsp;&nbsp;&nbsp;&nbsp;";
		else echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Baik\" disabled/>&nbsp;&nbsp;Baik&nbsp;&nbsp;&nbsp;&nbsp;";
		if ($data_minicex[kepuasan_penilai]=="Baik sekali")
			echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Baik sekali\" checked/>&nbsp;&nbsp;Baik sekali";
		else echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Baik sekali\" disabled/>&nbsp;&nbsp;Baik sekali";
		echo "</td></tr>";
		echo "<tr><td colspan=2>3. Kepuasan residen terhadap Mini-CEX:</td></tr>";
		echo "<tr><td colspan=2 style=\"padding:5px 5px 5px 25px;\">";
		if ($data_minicex[kepuasan_residen]=="Kurang sekali")
			echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Kurang sekali\" checked/>&nbsp;&nbsp;Kurang sekali&nbsp;&nbsp;&nbsp;&nbsp;";
		else echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Kurang sekali\" disabled/>&nbsp;&nbsp;Kurang sekali&nbsp;&nbsp;&nbsp;&nbsp;";
		if ($data_minicex[kepuasan_residen]=="Kurang")
			echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Kurang\" checked/>&nbsp;&nbsp;Kurang&nbsp;&nbsp;&nbsp;&nbsp;";
		else echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Kurang\" disabled/>&nbsp;&nbsp;Kurang&nbsp;&nbsp;&nbsp;&nbsp;";
		if ($data_minicex[kepuasan_residen]=="Cukup")
			echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Cukup\" checked/>&nbsp;&nbsp;Cukup&nbsp;&nbsp;&nbsp;&nbsp;";
		else echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Cukup\" disabled/>&nbsp;&nbsp;Cukup&nbsp;&nbsp;&nbsp;&nbsp;";
		if ($data_minicex[kepuasan_residen]=="Baik")
			echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Baik\" checked/>&nbsp;&nbsp;Baik&nbsp;&nbsp;&nbsp;&nbsp;";
		else echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Baik\" disabled/>&nbsp;&nbsp;Baik&nbsp;&nbsp;&nbsp;&nbsp;";
		if ($data_minicex[kepuasan_residen]=="Baik sekali")
			echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Baik sekali\" checked/>&nbsp;&nbsp;Baik sekali";
		else echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Baik sekali\" disabled/>&nbsp;&nbsp;Baik sekali";
		echo "</td></tr></table>";
		echo "<table border=0 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr><td align=right><br><i>Status: <b>BELUM DISETUJUI</b><br>";
		echo "Dosen Penilai / DPJP<p>$data_dosen[nama], $data_dosen[gelar]<br>NIP. $data_dosen[nip]</i>";
		echo "</td></tr>";
		echo "</table><br>";
		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		echo "<br><center><input type=\"submit\" class=\"submit1\" name=\"back\" value=\"BACK\" />";
		echo "<br><br></form></fieldset>";

		if ($_POST[back]=="BACK")
		{
			if ($_COOKIE['level']==5)
			echo "
					<script>
					window.location.href=\"penilaian_ika.php\";
					</script>
					";

			if ($_COOKIE['level']==4)
			echo "
				<script>
				window.location.href=\"penilaian_ika_dosen.php\";
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
