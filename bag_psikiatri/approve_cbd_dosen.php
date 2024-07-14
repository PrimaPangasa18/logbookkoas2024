<HTML>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="../menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../mytable.css" type="text/css" media="screen" />
	<link rel="stylesheet" type="text/css" href="../jquery_ui/jquery-ui.css">
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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) ILMU KESEHATAN JIWA</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">APPROVAL NILAI CBD - LEMBAR PENILAIAN FORMATIF KOMPETENSI KLINIK<p>Kepaniteraan (Stase) Ilmu Kesehatan Jiwa</font></h4>";

		$id_stase = "M093";
		$id = $_GET['id'];
		$data_cbd = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `psikiatri_nilai_cbd` WHERE `id`='$id'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_cbd[nim]'"));
		$stase_id = "stase_".$id_stase;
		$data_stase_mhsw = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$data_cbd[nim]'");
		$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);

		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";

		$tgl_mulai = $_GET[mulai];
		$tgl_selesai = $_GET[selesai];
		$approval = $_GET[approval];
		$mhsw = $_GET[mhsw];
		echo "<input type=\"hidden\" name=\"tgl_mulai\" value=\"$tgl_mulai\" />";
		echo "<input type=\"hidden\" name=\"tgl_selesai\" value=\"$tgl_selesai\" />";
		echo "<input type=\"hidden\" name=\"approval\" value=\"$approval\" />";
		echo "<input type=\"hidden\" name=\"mhsw\" value=\"$mhsw\" />";

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
			echo "<td class=\"td_mid\">Periode Kegiatan (yyyy-mm-dd)</td>";
			echo "<td class=\"td_mid\"><input type=\"text\" class=\"periode_awal\" name=\"periode_awal\" style=\"font-size:1em;font-family:TAHOMA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" value=\"$datastase_mhsw[tgl_mulai]\" />";
			echo " s.d. <input type=\"text\" class=\"periode_akhir\" name=\"periode_akhir\" style=\"font-size:1em;font-family:TAHOMA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" value=\"$datastase_mhsw[tgl_selesai]\" /></td>";
		echo "</tr>";
		//Tanggal Ujian
		echo "<tr>";
			echo "<td class=\"td_mid\">Tanggal Ujian (yyyy-mm-dd)</td>";
			echo "<td class=\"td_mid\"><input type=\"text\" class=\"tanggal_ujian\" name=\"tanggal_ujian\" style=\"font-size:1em;font-family:TAHOMA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" value=\"$data_cbd[tgl_ujian]\" /></td>";
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
			if ($data_cbd[situasi_ruangan]=="Rawat Jalan")
				echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Jalan\" checked/>&nbsp;&nbsp;Rawat Jalan";
			else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Jalan\" />&nbsp;&nbsp;Rawat Jalan";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_cbd[situasi_ruangan]=="UGD")
				echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"UGD\" checked/>&nbsp;&nbsp;UGD";
			else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"UGD\" />&nbsp;&nbsp;UGD";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_cbd[situasi_ruangan]=="Rawat Inap")
				echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Inap\" checked/>&nbsp;&nbsp;Rawat Inap";
			else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Inap\" />&nbsp;&nbsp;Rawat Inap";
			if ($data_cbd[situasi_ruangan]=="Lain-lain")
				echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Lain-lain\" checked/>&nbsp;&nbsp;Lain-lain";
			else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Lain-lain\" />&nbsp;&nbsp;Lain-lain";
			echo "</td>";
		echo "</tr>";
		//Nama Pasien
		echo "<tr>";
			echo "<td>Nama Pasien</td>";
			echo "<td><input type=\"text\" name=\"nama_pasien\" class=\"select_art\" value=\"$data_cbd[nama_pasien]\" required /></td>";
		echo "</tr>";
		//Nama Pasien
		echo "<tr>";
			echo "<td>Umur Pasien</td>";
			echo "<td><input type=\"number\" step=\"1\" min=\"0\" max=\"150\" name=\"umur_pasien\" style=\"width:15%;font-size:0.85em;text-align:center\" value=\"$data_cbd[umur_pasien]\" required/>&nbsp;&nbsp;tahun</td>";
		echo "</tr>";
		//Jenis Kelamin Pasien
		echo "<tr>";
			echo "<td>Jenis Kelamin Pasien</td>";
			echo "<td>";
			if ($data_cbd[jk_pasien]=="Laki-Laki") echo "<input type=\"radio\" name=\"jk_pasien\" value=\"Laki-Laki\" checked/>&nbsp;&nbsp;Laki-Laki";
			else echo "<input type=\"radio\" name=\"jk_pasien\" value=\"Laki-Laki\" />&nbsp;&nbsp;Laki-Laki";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_cbd[jk_pasien]=="Perempuan") echo "<input type=\"radio\" name=\"jk_pasien\" value=\"Perempuan\" checked/>&nbsp;&nbsp;Perempuan";
			else echo "<input type=\"radio\" name=\"jk_pasien\" value=\"Perempuan\" />&nbsp;&nbsp;Perempuan";
			echo "</td>";
		echo "</tr>";
		//Status Kasus
		echo "<tr>";
			echo "<td>Status Kasus</td>";
			echo "<td>";
			if ($data_cbd[status_kasus]=="Baru") echo "<input type=\"radio\" name=\"status_kasus\" value=\"Baru\" checked/>&nbsp;&nbsp;Baru";
			else echo "<input type=\"radio\" name=\"status_kasus\" value=\"Baru\" />&nbsp;&nbsp;Baru";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_cbd[status_kasus]=="Follow-up") echo "<input type=\"radio\" name=\"status_kasus\" value=\"Follow-up\" checked/>&nbsp;&nbsp;Follow-up";
			else echo "<input type=\"radio\" name=\"status_kasus\" value=\"Follow-up\" />&nbsp;&nbsp;Follow-up";
			echo "</td>";
		echo "</tr>";
		//Tingkat Kerumitan
		echo "<tr>";
			echo "<td>Tingkat Kerumitan</td>";
			echo "<td>";
			if ($data_cbd[tingkat_kerumitan]=="Rendah")
				echo "<input type=\"radio\" name=\"tingkat_kerumitan\" value=\"Rendah\" checked/>&nbsp;&nbsp;Rendah";
			else echo "<input type=\"radio\" name=\"tingkat_kerumitan\" value=\"Rendah\" />&nbsp;&nbsp;Rendah";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_cbd[tingkat_kerumitan]=="Sedang")
				echo "<input type=\"radio\" name=\"tingkat_kerumitan\" value=\"Sedang\" checked/>&nbsp;&nbsp;Sedang";
			else echo "<input type=\"radio\" name=\"tingkat_kerumitan\" value=\"Sedang\" />&nbsp;&nbsp;Sedang";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_cbd[tingkat_kerumitan]=="Tinggi")
				echo "<input type=\"radio\" name=\"tingkat_kerumitan\" value=\"Tinggi\" checked/>&nbsp;&nbsp;Tinggi";
			else echo "<input type=\"radio\" name=\"tingkat_kerumitan\" value=\"Tinggi\" />&nbsp;&nbsp;Tinggi";
			echo "</td>";
		echo "</tr>";
		//Fokus Pertemuan Klinik
		echo "<tr>";
			echo "<td>Fokus Pertemuan Klinik</td>";
			echo "<td>";
			if ($data_cbd[fokus_pertemuan]=="Pengumpulan Data") echo "<input type=\"radio\" name=\"fokus_pertemuan\" value=\"Pengumpulan Data\" checked/>&nbsp;&nbsp;Pengumpulan Data<br>";
			else echo "<input type=\"radio\" name=\"fokus_pertemuan\" value=\"Pengumpulan Data\" />&nbsp;&nbsp;Pengumpulan Data<br>";
			if ($data_cbd[fokus_pertemuan]=="Manajemen Pasien") echo "<input type=\"radio\" name=\"fokus_pertemuan\" value=\"Manajemen Pasien\" checked/>&nbsp;&nbsp;Manajemen Pasien<br>";
			else echo "<input type=\"radio\" name=\"fokus_pertemuan\" value=\"Manajemen Pasien\" />&nbsp;&nbsp;Manajemen Pasien<br>";
			if ($data_cbd[fokus_pertemuan]=="Diagnosis") echo "<input type=\"radio\" name=\"fokus_pertemuan\" value=\"Diagnosis\" checked/>&nbsp;&nbsp;Diagnosis<br>";
			else echo "<input type=\"radio\" name=\"fokus_pertemuan\" value=\"Diagnosis\" />&nbsp;&nbsp;Diagnosis<br>";
			if ($data_cbd[fokus_pertemuan]=="Konseling") echo "<input type=\"radio\" name=\"fokus_pertemuan\" value=\"Konseling\" checked/>&nbsp;&nbsp;Konseling";
			else echo "<input type=\"radio\" name=\"fokus_pertemuan\" value=\"Konseling\" />&nbsp;&nbsp;Konseling";
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
			echo "<th style=\"width:55%\">Komponen Kompetensi</th>";
			echo "<th style=\"width:20%\">Status Observasi</th>";
			echo "<th style=\"width:20%\">Nilai (0-100)</th>";
		echo "</thead>";
		//No 1
		echo "<tr>";
			echo "<td align=center>1</td>";
			echo "<td>Kemampuan membuat catatan medis</td>";
			echo "<td align=center>";
			if ($data_cbd[observasi_1]=="1")
				echo "<input type=\"radio\" name=\"observasi_1\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi_1\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_cbd[observasi_1]=="0")
				echo "<input type=\"radio\" name=\"observasi_1\" value=\"0\" conkeyup=\"sum();\" onchange=\"sum();\" hecked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi_1\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_1\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_cbd[aspek_1]\" id=\"aspek_1\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>Clinical assesment</td>";
			echo "<td align=center>";
			if ($data_cbd[observasi_2]=="1")
				echo "<input type=\"radio\" name=\"observasi_2\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi_2\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_cbd[observasi_2]=="0")
				echo "<input type=\"radio\" name=\"observasi_2\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi_2\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_2\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_cbd[aspek_2]\" id=\"aspek_2\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>Investigasi dan rujukan</td>";
			echo "<td align=center>";
			if ($data_cbd[observasi_3]=="1")
				echo "<input type=\"radio\" name=\"observasi_3\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi_3\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_cbd[observasi_3]=="0")
				echo "<input type=\"radio\" name=\"observasi_3\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi_3\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_3\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_cbd[aspek_3]\" id=\"aspek_3\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 4
		echo "<tr>";
			echo "<td align=center>4</td>";
			echo "<td>Terapi</td>";
			echo "<td align=center>";
			if ($data_cbd[observasi_4]=="1")
				echo "<input type=\"radio\" name=\"observasi_4\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi_4\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_cbd[observasi_4]=="0")
				echo "<input type=\"radio\" name=\"observasi_4\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi_4\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_4\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_cbd[aspek_4]\" id=\"aspek_4\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 5
		echo "<tr>";
			echo "<td align=center>5</td>";
			echo "<td>Follow up dan rencana pengelolaan selanjutnya</td>";
			echo "<td align=center>";
			if ($data_cbd[observasi_5]=="1")
				echo "<input type=\"radio\" name=\"observasi_5\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi_5\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_cbd[observasi_5]=="0")
				echo "<input type=\"radio\" name=\"observasi_5\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi_5\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_5\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_cbd[aspek_5]\" id=\"aspek_5\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 6
		echo "<tr>";
			echo "<td align=center>6</td>";
			echo "<td>Profesionalisme</td>";
			echo "<td align=center>";
			if ($data_cbd[observasi_6]=="1")
				echo "<input type=\"radio\" name=\"observasi_6\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi_6\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_cbd[observasi_6]=="0")
				echo "<input type=\"radio\" name=\"observasi_6\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi_6\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_6\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_cbd[aspek_6]\" id=\"aspek_6\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 7
		echo "<tr>";
			echo "<td align=center>7</td>";
			echo "<td>Penilaian klinik secara keseluruhan</td>";
			echo "<td align=center>";
			if ($data_cbd[observasi_7]=="1")
				echo "<input type=\"radio\" name=\"observasi_7\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
			else echo "<input type=\"radio\" name=\"observasi_7\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_cbd[observasi_7]=="0")
				echo "<input type=\"radio\" name=\"observasi_7\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
			else echo "<input type=\"radio\" name=\"observasi_7\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_7\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_cbd[aspek_7]\" id=\"aspek_7\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//Rata Nilai
		echo "<tr>";
			echo "<td align=right colspan=3>Rata-Rata Nilai (Jumlah Nilai / Jumlah Observasi)</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_rata\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_cbd[nilai_rata]\" id=\"nilai_rata\" required/></td>";
		echo "</tr>";
		echo "</table><br><br>";

		//Umpan Balik
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr><td colspan=2 align=center><b>UMPAN BALIK TERHADAP KINERJA PESERTA UJIAN</b></td></tr>";
		echo "<tr>";
			echo "<td align=center style=\"width:50%\">Aspek yang sudah bagus</td>";
			echo "<td align=center style=\"width:50%\">Aspek yang perlu diperbaiki</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td><textarea name=\"ub_bagus\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\">$data_cbd[ub_bagus]</textarea></td>";
			echo "<td><textarea name=\"ub_perbaikan\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\">$data_cbd[ub_perbaikan]</textarea></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td colspan=2>Action plan yang disetujui bersama:<br><textarea name=\"saran\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\">$data_cbd[saran]</textarea></td>";
		echo "</tr>";
		//Kepuasaan penilai terhadap CBD
		echo "<tr>";
			echo "<td colspan=2><b>Catatan:</b><br><br>Kepuasaan penilai terhadap CBD:<br><br>";
			if ($data_cbd[kepuasan]=="Kurang sekali") echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan\" value=\"Kurang sekali\" checked/>&nbsp;&nbsp;Kurang sekali";
			else echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan\" value=\"Kurang sekali\" />&nbsp;&nbsp;Kurang sekali";
			if ($data_cbd[kepuasan]=="Kurang") echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan\" value=\"Kurang\" checked/>&nbsp;&nbsp;Kurang";
			else echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan\" value=\"Kurang\" />&nbsp;&nbsp;Kurang";
			if ($data_cbd[kepuasan]=="Cukup") echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan\" value=\"Cukup\" checked/>&nbsp;&nbsp;Cukup";
			else echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan\" value=\"Cukup\" />&nbsp;&nbsp;Cukup";
			if ($data_cbd[kepuasan]=="Baik") echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan\" value=\"Baik\" checked/>&nbsp;&nbsp;Baik";
			else echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan\" value=\"Baik\" />&nbsp;&nbsp;Baik";
			if ($data_cbd[kepuasan]=="Baik sekali") echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan\" value=\"Baik sekali\" checked/>&nbsp;&nbsp;Baik sekali";
			else echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan\" value=\"Baik sekali\" />&nbsp;&nbsp;Baik sekali";
			echo "<br><br></td>";
		echo "</tr>";
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
				window.location.href=\"penilaian_psikiatri_dosen.php?mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\";
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

			$nama_pasien = addslashes($_POST[nama_pasien]);
			$ub_bagus = addslashes($_POST[ub_bagus]);
			$ub_perbaikan = addslashes($_POST[ub_perbaikan]);
			$saran = addslashes($_POST[saran]);
			$jml_observasi = $_POST[observasi_1]+$_POST[observasi_2]+$_POST[observasi_3]+$_POST[observasi_4]+$_POST[observasi_5]+$_POST[observasi_6]+$_POST[observasi_7];
			$nilai_total = $_POST[observasi_1]*$_POST[aspek_1]+$_POST[observasi_2]*$_POST[aspek_2]+$_POST[observasi_3]*$_POST[aspek_3]+$_POST[observasi_4]*$_POST[aspek_4]+$_POST[observasi_5]*$_POST[aspek_5]+$_POST[observasi_6]*$_POST[aspek_6]+$_POST[observasi_7]*$_POST[aspek_7];
			if ($jml_observasi==0) $nilai_rata = 0;
			else $nilai_rata = $nilai_total/$jml_observasi;
			$nilai_rata = number_format($nilai_rata,2);

			$update_cbd=mysqli_query($con,"UPDATE `psikiatri_nilai_cbd` SET
				`tgl_awal`='$_POST[periode_awal]',`tgl_akhir`='$_POST[periode_akhir]',`tgl_ujian`='$_POST[tanggal_ujian]',`situasi_ruangan`='$_POST[situasi_ruangan]',
				`nama_pasien`='$nama_pasien',`umur_pasien`='$_POST[umur_pasien]',`jk_pasien`='$_POST[jk_pasien]',`status_kasus`='$_POST[status_kasus]',
				`tingkat_kerumitan`='$_POST[tingkat_kerumitan]',`fokus_pertemuan`='$_POST[fokus_pertemuan]',
				`aspek_1`='$aspek1',`observasi_1`='$_POST[observasi_1]',
				`aspek_2`='$aspek2',`observasi_2`='$_POST[observasi_2]',
				`aspek_3`='$aspek3',`observasi_3`='$_POST[observasi_3]',
				`aspek_4`='$aspek4',`observasi_4`='$_POST[observasi_4]',
				`aspek_5`='$aspek5',`observasi_5`='$_POST[observasi_5]',
				`aspek_6`='$aspek6',`observasi_6`='$_POST[observasi_6]',
				`aspek_7`='$aspek7',`observasi_7`='$_POST[observasi_7]',
				`ub_bagus`='$ub_bagus',`ub_perbaikan`='$ub_perbaikan',`saran`='$saran',
				`nilai_rata`='$nilai_rata',`kepuasan`='$_POST[kepuasan]',
				`tgl_approval`='$tgl',`status_approval`='1'
				WHERE `id`='$_POST[id]'");

			echo "
				<script>
				window.location.href = \"penilaian_psikiatri_dosen.php?mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\";
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
<script type="text/javascript" src="../jquery_ui/jquery-ui.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.tanggal_ujian').datepicker({ dateFormat: 'yy-mm-dd' });
		$('.periode_awal').datepicker({ dateFormat: 'yy-mm-dd' });
		$('.periode_akhir').datepicker({ dateFormat: 'yy-mm-dd' });
	});
</script>

<script>
function sum() {
      var aspek1 = document.getElementById('aspek_1').value;
			var aspek2 = document.getElementById('aspek_2').value;
			var aspek3 = document.getElementById('aspek_3').value;
			var aspek4 = document.getElementById('aspek_4').value;
			var aspek5 = document.getElementById('aspek_5').value;
			var aspek6 = document.getElementById('aspek_6').value;
			var aspek7 = document.getElementById('aspek_7').value;
			var observasi1 = $("input[name=observasi_1]:checked").val();
			var observasi2 = $("input[name=observasi_2]:checked").val();
			var observasi3 = $("input[name=observasi_3]:checked").val();
			var observasi4 = $("input[name=observasi_4]:checked").val();
			var observasi5 = $("input[name=observasi_5]:checked").val();
			var observasi6 = $("input[name=observasi_6]:checked").val();
			var observasi7 = $("input[name=observasi_7]:checked").val();

			var total = parseInt(observasi1)*parseFloat(aspek1) + parseInt(observasi2)*parseFloat(aspek2) + parseInt(observasi3)*parseFloat(aspek3) + parseInt(observasi4)*parseFloat(aspek4) + parseInt(observasi5)*parseFloat(aspek5) + parseInt(observasi6)*parseFloat(aspek6) + parseInt(observasi7)*parseFloat(aspek7);
			var pembagi = parseInt(observasi1) + parseInt(observasi2) + parseInt(observasi3) + parseInt(observasi4) + parseInt(observasi5) + parseInt(observasi6) + parseInt(observasi7);
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
