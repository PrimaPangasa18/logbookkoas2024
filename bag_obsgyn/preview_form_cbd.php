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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) ILMU KEBIDANAN DAN PENYAKIT KANDUNGAN</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">PREVIEW NILAI CASE-BASED DISCUSSION (CBD)<p>KEPANITERAAN (STASE) ILMU KEBIDANAN DAN PENYAKIT KANDUNGAN</font></h4>";

		$id_stase = "M111";
		$id = $_GET['id'];
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
		$stase_id = "stase_".$id_stase;
		$data_stase_mhsw = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'");
		$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);
		$data_cbd = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `obsgyn_nilai_cbd` WHERE `id`='$id'"));

		$tanggal_ujian = tanggal_indo($data_cbd[tgl_ujian]);
		$tanggal_approval = tanggal_indo($data_cbd[tgl_approval]);
		$awal_periode = tanggal_indo($data_cbd[tgl_awal]);
		$akhir_periode = tanggal_indo($data_cbd[tgl_akhir]);

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
		//Kasus CBD ke-
		echo "<tr>";
			echo "<td>Kasus CBD ke-</td>";
			echo "<td>";
			if ($data_cbd[kasus_ke]=="1")
				echo "<input type=\"radio\" name=\"kasus_ke\" value=\"1\" checked/>&nbsp;&nbsp;1 (CBD Kesatu)";
			else echo "<input type=\"radio\" name=\"kasus_ke\" value=\"1\" disabled/>&nbsp;&nbsp;1 (CBD Kesatu)";
			echo "<br>";
			if ($data_cbd[kasus_ke]=="2")
				echo "<input type=\"radio\" name=\"kasus_ke\" value=\"2\" checked/>&nbsp;&nbsp;2 (CBD Kedua)";
			else echo "<input type=\"radio\" name=\"kasus_ke\" value=\"2\" disabled/>&nbsp;&nbsp;2 (CBD Kedua)";
			echo "<br>";
			if ($data_cbd[kasus_ke]=="3")
				echo "<input type=\"radio\" name=\"kasus_ke\" value=\"3\" checked/>&nbsp;&nbsp;3 (CBD Ketiga)";
			else echo "<input type=\"radio\" name=\"kasus_ke\" value=\"3\" disabled/>&nbsp;&nbsp;3 (CBD Ketiga)";
			echo "<br>";
			if ($data_cbd[kasus_ke]=="4")
				echo "<input type=\"radio\" name=\"kasus_ke\" value=\"4\" checked/>&nbsp;&nbsp;4 (CBD Keempat)";
			else echo "<input type=\"radio\" name=\"kasus_ke\" value=\"4\" disabled/>&nbsp;&nbsp;4 (CBD Keempat)";
		echo "</tr>";
		//Dosen Penilai/Penguji
		echo "<tr>";
			echo "<td>Dosen Penilai/Penguji</td>";
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_cbd[dosen]'"));
			echo "<td>$data_dosen[nama], $data_dosen[gelar] ($data_dosen[nip])</td>";
		echo "</tr>";
		//Situasi Ruangan
		echo "<tr>";
			echo "<td>Situasi Ruangan</td>";
			echo "<td>";
			if ($data_cbd[situasi_ruangan]=="UGD")
				echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"UGD\" checked/>&nbsp;&nbsp;UGD";
			else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"UGD\" disabled/>&nbsp;&nbsp;UGD";
			echo "<br>";
			if ($data_cbd[situasi_ruangan]=="Rawat Jalan")
				echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Jalan\" checked/>&nbsp;&nbsp;Rawat Jalan";
			else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Jalan\" disabled/>&nbsp;&nbsp;Rawat Jalan";
			echo "<br>";
			if ($data_cbd[situasi_ruangan]=="Rawat Inap")
				echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Inap\" checked/>&nbsp;&nbsp;Rawat Inap";
			else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Inap\" disabled/>&nbsp;&nbsp;Rawat Inap";
			echo "</td>";
		echo "</tr>";
		//Problem/Diagnosis Pasien
		echo "<tr>";
			echo "<td>Problem/Diagnosis Pasien</td>";
			echo "<td><textarea name=\"diagnosis\" style=\"width:97%;font-family:Tahoma;font-size:1em\" required disabled >$data_cbd[diagnosis]</textarea></td>";
		echo "</tr>";
		//Fokus Kasus
		echo "<tr>";
			echo "<td>Fokus Kasus</td>";
			echo "<td>";
			if ($data_cbd[fokus_kasus]=="Pembuatan Rekam Medik")
				echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Pembuatan Rekam Medik\" checked/>&nbsp;&nbsp;Pembuatan Rekam Medik<br>";
			else echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Pembuatan Rekam Medik\" disabled/>&nbsp;&nbsp;Pembuatan Rekam Medik<br>";
			if ($data_cbd[fokus_kasus]=="Clinical Assesment")
				echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Clinical Assesment\" checked/>&nbsp;&nbsp;Clinical Assesment<br>";
			else echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Clinical Assesment\" disabled/>&nbsp;&nbsp;Clinical Assesment<br>";
			if ($data_cbd[fokus_kasus]=="Tata Laksana")
				echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Tata Laksana\" checked/>&nbsp;&nbsp;Tata Laksana<br>";
			else echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Tata Laksana\" disabled/>&nbsp;&nbsp;Tata Laksana<br>";
			if ($data_cbd[fokus_kasus]=="Profesionalisme")
				echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Profesionalisme\" checked/>&nbsp;&nbsp;Profesionalisme<br>";
			else echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Profesionalisme\" disabled/>&nbsp;&nbsp;Profesionalisme<br>";
			echo "</td>";
		echo "</tr>";
		//Tingkat Kerumitan
		echo "<tr>";
			echo "<td>Tingkat Kerumitan</td>";
			echo "<td>";
			if ($data_cbd[tingkat_kerumitan]=="Rendah")
				echo "<input type=\"radio\" name=\"tingkat_kerumitan\" value=\"Rendah\" checked/>&nbsp;&nbsp;Rendah";
			else echo "<input type=\"radio\" name=\"tingkat_kerumitan\" value=\"Rendah\" disabled/>&nbsp;&nbsp;Rendah";
			echo "<br>";
			if ($data_cbd[tingkat_kerumitan]=="Sedang")
				echo "<input type=\"radio\" name=\"tingkat_kerumitan\" value=\"Sedang\" checked/>&nbsp;&nbsp;Sedang";
			else echo "<input type=\"radio\" name=\"tingkat_kerumitan\" value=\"Sedang\" disabled/>&nbsp;&nbsp;Sedang";
			echo "<br>";
			if ($data_cbd[tingkat_kerumitan]=="Tinggi")
				echo "<input type=\"radio\" name=\"tingkat_kerumitan\" value=\"Tinggi\" checked/>&nbsp;&nbsp;Tinggi";
			else echo "<input type=\"radio\" name=\"tingkat_kerumitan\" value=\"Tinggi\" disabled/>&nbsp;&nbsp;Tinggi";
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
			echo "<td>Penulisan/pembuatan rekam medik</td>";
			echo "<td align=center>";
			if ($data_cbd[observasi_1]=="1")
				echo "<input type=\"radio\" name=\"observasi_1\" value=\"1\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi_1\" value=\"1\" disabled />&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_cbd[observasi_1]=="0")
				echo "<input type=\"radio\" name=\"observasi_1\" value=\"0\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi_1\" value=\"0\" disabled />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center>$data_cbd[aspek_1]</td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>Penilaian klinis (<i>clinical assesment</i>)</td>";
			echo "<td align=center>";
			if ($data_cbd[observasi_2]=="1")
				echo "<input type=\"radio\" name=\"observasi_2\" value=\"1\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi_2\" value=\"1\" disabled />&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_cbd[observasi_2]=="0")
				echo "<input type=\"radio\" name=\"observasi_2\" value=\"0\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi_2\" value=\"0\" disabled />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center>$data_cbd[aspek_2]</td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>Investigasi dan rujukan (<i>investigation and referral</i>)</td>";
			echo "<td align=center>";
			if ($data_cbd[observasi_3]=="1")
				echo "<input type=\"radio\" name=\"observasi_3\" value=\"1\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi_3\" value=\"1\" disabled />&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_cbd[observasi_3]=="0")
				echo "<input type=\"radio\" name=\"observasi_3\" value=\"0\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi_3\" value=\"0\" disabled />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center>$data_cbd[aspek_3]</td>";
		echo "</tr>";
		//No 4
		echo "<tr>";
			echo "<td align=center>4</td>";
			echo "<td>Tata laksana</td>";
			echo "<td align=center>";
			if ($data_cbd[observasi_4]=="1")
				echo "<input type=\"radio\" name=\"observasi_4\" value=\"1\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi_4\" value=\"1\" disabled />&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_cbd[observasi_4]=="0")
				echo "<input type=\"radio\" name=\"observasi_4\" value=\"0\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi_4\" value=\"0\" disabled />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center>$data_cbd[aspek_4]</td>";
		echo "</tr>";
		//No 5
		echo "<tr>";
			echo "<td align=center>5</td>";
			echo "<td>Pemantauan dan rencana selanjutnya (<i>follow up and future planning</i>)</td>";
			echo "<td align=center>";
			if ($data_cbd[observasi_5]=="1")
				echo "<input type=\"radio\" name=\"observasi_5\" value=\"1\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi_5\" value=\"1\" disabled />&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_cbd[observasi_5]=="0")
				echo "<input type=\"radio\" name=\"observasi_5\" value=\"0\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi_5\" value=\"0\" disabled />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center>$data_cbd[aspek_5]</td>";
		echo "</tr>";
		//No 6
		echo "<tr>";
			echo "<td align=center>6</td>";
			echo "<td>Profesionalisme</td>";
			echo "<td align=center>";
			if ($data_cbd[observasi_6]=="1")
				echo "<input type=\"radio\" name=\"observasi_6\" value=\"1\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi_6\" value=\"1\" disabled />&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_cbd[observasi_6]=="0")
				echo "<input type=\"radio\" name=\"observasi_6\" value=\"0\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi_6\" value=\"0\" disabled />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center>$data_cbd[aspek_6]</td>";
		echo "</tr>";
		//No 7
		echo "<tr>";
			echo "<td align=center>7</td>";
			echo "<td>Penilaian klinis secara keseluruhan</td>";
			echo "<td align=center>";
			if ($data_cbd[observasi_7]=="1")
				echo "<input type=\"radio\" name=\"observasi_7\" value=\"1\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi_7\" value=\"1\" disabled />&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_cbd[observasi_7]=="0")
				echo "<input type=\"radio\" name=\"observasi_7\" value=\"0\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi_7\" value=\"0\" disabled />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center>$data_cbd[aspek_7]</td>";
		echo "</tr>";
		//Rata Nilai
		echo "<tr>";
			echo "<td align=right colspan=3>Rata-Rata Nilai (Jumlah Nilai / Jumlah Observasi)</td>";
			echo "<td align=center><b>$data_cbd[nilai_rata]</b></td>";
		echo "</tr>";
		echo "<tr><td colspan=4><font style=\"font-size:0.75em;\"><i>Keterangan: Nilai Batas Lulus (NBL) = 70</i></font></td></tr>";
		echo "</table><br><br>";

		//Umpan Balik
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr><td colspan=2 align=center><b>UMPAN BALIK TERHADAP DISKUSI KASUS</b></td></tr>";
		echo "<tr>";
			echo "<td align=center style=\"width:50%\">Sudah bagus</td>";
			echo "<td align=center style=\"width:50%\">Perlu perbaikan</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td><textarea name=\"ub_bagus\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_cbd[ub_bagus]</textarea></td>";
			echo "<td><textarea name=\"ub_perbaikan\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_cbd[ub_perbaikan]</textarea></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td colspan=2>Saran:<br><textarea name=\"saran\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" disabled>$data_cbd[saran]</textarea></td>";
		echo "</tr>";

		//Catatan
		echo "<tr><td colspan=2><b>Catatan:</b></td></tr>";
		echo "<tr><td colspan=2>Waktu Penilaian Kasus CBD:</td></tr>";
		echo "<tr>";
			echo "<td style=\"width:30%;padding:5px 5px 5px 25px;\">&nbsp;&nbsp;Observasi</td>";
			echo "<td style=\"width:70%\">";
			echo "$data_cbd[waktu_observasi] menit<br>";
			echo "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td style=\"width:30%;padding:5px 5px 5px 25px;\">&nbsp;&nbsp;Memberikan umpan balik</td>";
			echo "<td style=\"width:70%\">";
			echo "$data_cbd[waktu_ub] menit<br>";
			echo "</td>";
		echo "</tr>";
		echo "<tr><td colspan=2 align=right><br><i>Status: <b>BELUM DISETUJUI</b><br>";
		echo "Dosen Penilai/Penguji<p>$data_dosen[nama], $data_dosen[gelar]<br>NIP. $data_dosen[nip]</i>";
		echo "</td></tr>";
		echo "</table><br>";

		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		echo "<br><center><input type=\"submit\" class=\"submit1\" name=\"back\" value=\"BACK\" />";
		echo "<br><br></form></fieldset>";

		if ($_POST[back]=="BACK")
		{
			echo "
					<script>
					window.location.href=\"penilaian_obsgyn.php\";
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
