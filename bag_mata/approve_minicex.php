<HTML>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="../menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../mytable.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../qr_code.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../text-security-master/dist/text-security.css" type="text/css" media="screen" />
	<link rel="stylesheet" type="text/css" href="../jquery_ui/jquery-ui.css">
	<link rel="stylesheet" href="../select2/dist/css/select2.css"/>
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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) ILMU KESEHATAN MATA</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">APPROVAL NILAI UJIAN MINI-CEX<p>Kepaniteraan (Stase) Ilmu Kesehatan Mata</font></h4>";

		$id_stase = "M104";
		$id = $_GET['id'];
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
		$stase_id = "stase_".$id_stase;
		$data_stase_mhsw = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'");
		$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);
		$data_minicex = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `mata_nilai_minicex` WHERE `id`='$id'"));

		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";

		//Nama mahasiswa koas
		echo "<tr>";
			echo "<td>Nama Mahasiswa</td>";
			echo "<td>$data_mhsw[nama]</td>";
		echo "</tr>";
		//NIM
		echo "<tr>";
			echo "<td>NIM</td>";
			echo "<td>$data_mhsw[nim]</td>";
		echo "</tr>";
		//Periode Stase
		echo "<tr>";
			$mulai_stase = tanggal_indo($datastase_mhsw[tgl_mulai]);
			$selesai_stase = tanggal_indo($datastase_mhsw[tgl_selesai]);
			echo "<td class=\"td_mid\">Periode Kepaniteraan (Stase)</td>";
			echo "<td class=\"td_mid\">$mulai_stase s.d. $selesai_stase</td>";
		echo "</tr>";
		//Tanggal Ujian
		echo "<tr>";
			echo "<td class=\"td_mid\">Tanggal Ujian (yyyy-mm-dd)</td>";
			echo "<td class=\"td_mid\"><input type=\"text\" class=\"tanggal_ujian\" name=\"tanggal_ujian\" style=\"font-size:1em;font-family:TAHOMA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" value=\"$data_minicex[tgl_ujian]\" required/></td>";
		echo "</tr>";
		//Waktu Observasi
		echo "<tr>";
			echo "<td class=\"td_mid\">Waktu Observasi</td>";
			echo "<td class=\"td_mid\"><input type=\"number\" step=\"5\" min=\"0\" max=\"3600\" name=\"waktu_obs\" style=\"width:20%;font-size:0.85em;text-align:center\" value=\"$data_minicex[waktu_obs]\" required/>&nbsp;&nbsp;menit</td>";
		echo "</tr>";
		//Waktu Umpan Balik
		echo "<tr>";
			echo "<td class=\"td_mid\">Waktu Umpan Balik</td>";
			echo "<td class=\"td_mid\"><input type=\"number\" step=\"5\" min=\"0\" max=\"3600\" name=\"waktu_ub\" style=\"width:20%;font-size:0.85em;text-align:center\" value=\"$data_minicex[waktu_ub]\" required/>&nbsp;&nbsp;menit</td>";
		echo "</tr>";
		//Dosen Penguji
		echo "<tr>";
			echo "<td>Dosen Penguji</td>";
			echo "<td>";
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_minicex[dosen]'"));
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
			echo "<th style=\"width:58%\">Komponen Penilaian Ketrampilan</th>";
			echo "<th style=\"width:22%\">Observasi</th>";
			echo "<th style=\"width:20%\">Nilai (0-100)</th>";
		echo "</thead>";
		//KOMUNIKASI
		echo "<tr>";
			echo "<td colspan=4>1. KOMUNIKASI</td>";
		echo "</tr>";
		//No 1.1.
		echo "<tr>";
			echo "<td align=center>1.1.</td>";
			echo "<td>Memperkenalkan diri dan menjelaskan perannya kepada pasien</td>";
			echo "<td>";
			if ($data_minicex[observasi_11]=="1")
				echo "<input type=\"radio\" name=\"observasi11\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type=\"radio\" name=\"observasi11\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
			else
				echo "<input type=\"radio\" name=\"observasi11\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type=\"radio\" name=\"observasi11\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
			echo "</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek11\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_11]\" id=\"aspek11\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 1.2.
		echo "<tr>";
			echo "<td align=center>1.2.</td>";
			echo "<td>Memperlihatkan kontak mata yang baik</td>";
			echo "<td>";
			if ($data_minicex[observasi_12]=="1")
				echo "<input type=\"radio\" name=\"observasi12\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type=\"radio\" name=\"observasi12\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
			else
				echo "<input type=\"radio\" name=\"observasi12\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type=\"radio\" name=\"observasi12\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
			echo "</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek12\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_12]\" id=\"aspek12\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 1.3.
		echo "<tr>";
			echo "<td align=center>1.3.</td>";
			echo "<td>Mendengarkan pasien tanpa menginterupsi</td>";
			echo "<td>";
			if ($data_minicex[observasi_13]=="1")
			  echo "<input type=\"radio\" name=\"observasi13\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi13\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
			else
			  echo "<input type=\"radio\" name=\"observasi13\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi13\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
			echo "</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek13\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_13]\" id=\"aspek13\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
			echo "</tr>";
		//No 1.4.
		echo "<tr>";
			echo "<td align=center>1.4.</td>";
			echo "<td>Mengekspresikan perhatiannya kepada pasien</td>";
			echo "<td>";
			if ($data_minicex[observasi_14]=="1")
			  echo "<input type=\"radio\" name=\"observasi14\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi14\" value=\"0\" />&nbsp;&nbsp;Tidak";
			else
			  echo "<input type=\"radio\" name=\"observasi14\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi14\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
			echo "</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek14\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_14]\" id=\"aspek14\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
			echo "</tr>";
		//No 1.5.
		echo "<tr>";
			echo "<td align=center>1.5.</td>";
			echo "<td>Bertanya dengan pertanyaan terbuka</td>";
			echo "<td>";
			if ($data_minicex[observasi_15]=="1")
			  echo "<input type=\"radio\" name=\"observasi15\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi15\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
			else
			  echo "<input type=\"radio\" name=\"observasi15\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi15\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
			echo "</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek15\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_15]\" id=\"aspek15\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
			echo "</tr>";
		//No 1.6.
		echo "<tr>";
			echo "<td align=center>1.6.</td>";
			echo "<td>Memberi kesempatan kepada pasien untuk bertanya</td>";
			echo "<td>";
			if ($data_minicex[observasi_16]=="1")
			  echo "<input type=\"radio\" name=\"observasi16\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi16\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
			else
			  echo "<input type=\"radio\" name=\"observasi16\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi16\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
			echo "</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek16\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_16]\" id=\"aspek16\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
			echo "</tr>";
		//No 1.7.
		echo "<tr>";
			echo "<td align=center>1.7.</td>";
			echo "<td>Menjelaskan perencanaan selanjutnya dengan baik</td>";
			echo "<td>";
			if ($data_minicex[observasi_17]=="1")
			  echo "<input type=\"radio\" name=\"observasi17\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi17\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
			else
			  echo "<input type=\"radio\" name=\"observasi17\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi17\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
			echo "</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek17\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_17]\" id=\"aspek17\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
			echo "</tr>";

		//2. PROFESIONALISME
		echo "<tr>";
			echo "<td colspan=4>2. PROFESIONALISME</td>";
		echo "</tr>";
		//No 2.1.
		echo "<tr>";
			echo "<td align=center>2.1.</td>";
			echo "<td>Mengenakan pakaian yang pantas</td>";
			echo "<td>";
			if ($data_minicex[observasi_21]=="1")
			  echo "<input type=\"radio\" name=\"observasi21\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi21\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
			else
			  echo "<input type=\"radio\" name=\"observasi21\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi21\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
			echo "</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek21\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_21]\" id=\"aspek21\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
			echo "</tr>";
		//No 2.2.
		echo "<tr>";
			echo "<td align=center>2.2.</td>";
			echo "<td>Sopan / hormat pada pasien</td>";
			echo "<td>";
			if ($data_minicex[observasi_22]=="1")
			  echo "<input type=\"radio\" name=\"observasi22\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi22\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
			else
			  echo "<input type=\"radio\" name=\"observasi22\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi22\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
			echo "</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek22\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_22]\" id=\"aspek22\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
			echo "</tr>";
		//No 2.3.
		echo "<tr>";
			echo "<td align=center>2.3.</td>";
			echo "<td>Memperlihatkan sikap profesional (memanggil nama pasien, memperlihatkan keseriusan dan kompeten)</td>";
			echo "<td>";
			if ($data_minicex[observasi_23]=="1")
			  echo "<input type=\"radio\" name=\"observasi23\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi23\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
			else
			  echo "<input type=\"radio\" name=\"observasi23\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi23\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
			echo "</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek23\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_23]\" id=\"aspek23\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
			echo "</tr>";
		//No 2.4.
		echo "<tr>";
			echo "<td align=center>2.4.</td>";
			echo "<td>Menghargai kebebasan / kerahasiaan pribadi</td>";
			echo "<td>";
			if ($data_minicex[observasi_24]=="1")
			  echo "<input type=\"radio\" name=\"observasi24\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi24\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
			else
			  echo "<input type=\"radio\" name=\"observasi24\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi24\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
			echo "</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek24\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_24]\" id=\"aspek24\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
			echo "</tr>";

		//KETRAMPILAN KLINIK
		echo "<tr>";
			echo "<td colspan=4>3. KETRAMPILAN KLINIK</td>";
		echo "</tr>";
		//No 3.1.
		echo "<tr>";
			echo "<td align=center>3.1.</td>";
			echo "<td>Mencuci tangan sebelum dan sesudah  memeriksa pasien</td>";
			echo "<td>";
			if ($data_minicex[observasi_31]=="1")
			  echo "<input type=\"radio\" name=\"observasi31\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi31\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
			else
			  echo "<input type=\"radio\" name=\"observasi31\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi31\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
			echo "</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek31\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_31]\" id=\"aspek31\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
			echo "</tr>";
		//No 3.2.
		echo "<tr>";
			echo "<td align=center>3.2.</td>";
			echo "<td>Pemeriksaan visus</td>";
			echo "<td>";
			if ($data_minicex[observasi_32]=="1")
			  echo "<input type=\"radio\" name=\"observasi32\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi32\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
			else
			  echo "<input type=\"radio\" name=\"observasi32\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi32\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
			echo "</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek32\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_32]\" id=\"aspek32\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
			echo "</tr>";
		//No 3.3.
		echo "<tr>";
			echo "<td align=center>3.3.</td>";
			echo "<td>Pemeriksaan segmen anterior</td>";
			echo "<td>";
			if ($data_minicex[observasi_33]=="1")
			  echo "<input type=\"radio\" name=\"observasi33\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi33\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
			else
			  echo "<input type=\"radio\" name=\"observasi33\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi33\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
			echo "</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek33\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_33]\" id=\"aspek33\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
			echo "</tr>";
		//No 3.4.
		echo "<tr>";
			echo "<td align=center>3.4.</td>";
			echo "<td>Pemeriksaan funduskopi</td>";
			echo "<td>";
			if ($data_minicex[observasi_34]=="1")
			  echo "<input type=\"radio\" name=\"observasi34\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi34\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
			else
			  echo "<input type=\"radio\" name=\"observasi34\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi34\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
			echo "</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek34\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_34]\" id=\"aspek34\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
			echo "</tr>";
		//No 3.5.
		echo "<tr>";
			echo "<td align=center>3.5.</td>";
			echo "<td>Pemeriksaan tonometri</td>";
			echo "<td>";
			if ($data_minicex[observasi_35]=="1")
			  echo "<input type=\"radio\" name=\"observasi35\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi35\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
			else
			  echo "<input type=\"radio\" name=\"observasi35\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi35\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
			echo "</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek35\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_35]\" id=\"aspek35\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
			echo "</tr>";
		//No 3.6.
		echo "<tr>";
			echo "<td align=center>3.6.</td>";
			echo "<td>Pemeriksaan lapang pandang</td>";
			echo "<td>";
			if ($data_minicex[observasi_36]=="1")
			  echo "<input type=\"radio\" name=\"observasi36\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi36\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
			else
			  echo "<input type=\"radio\" name=\"observasi36\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi36\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
			echo "</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek36\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_36]\" id=\"aspek36\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
			echo "</tr>";

		//CLINICAL REASONING
		echo "<tr>";
			echo "<td colspan=4>4. CLINICAL REASONING</td>";
		echo "</tr>";
		//No 4.1.
		echo "<tr>";
			echo "<td align=center>4.1.</td>";
			echo "<td>Diagnosis</td>";
			echo "<td>";
			if ($data_minicex[observasi_41]=="1")
			  echo "<input type=\"radio\" name=\"observasi41\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi41\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
			else
			  echo "<input type=\"radio\" name=\"observasi41\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi41\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
			echo "</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek41\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_41]\" id=\"aspek41\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
			echo "</tr>";
		//No 4.2.
		echo "<tr>";
			echo "<td align=center>4.2.</td>";
			echo "<td>Tatalaksana</td>";
			echo "<td>";
			if ($data_minicex[observasi_42]=="1")
			  echo "<input type=\"radio\" name=\"observasi42\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi42\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
			else
			  echo "<input type=\"radio\" name=\"observasi42\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi42\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
			echo "</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek42\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_42]\" id=\"aspek42\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
			echo "</tr>";
		//No 4.3.
		echo "<tr>";
			echo "<td align=center>5.3.</td>";
			echo "<td>Edukasi</td>";
			echo "<td>";
			if ($data_minicex[observasi_43]=="1")
			  echo "<input type=\"radio\" name=\"observasi43\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi43\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
			else
			  echo "<input type=\"radio\" name=\"observasi43\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi43\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
			echo "</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek43\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_43]\" id=\"aspek43\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
			echo "</tr>";

		//Nilai Total
		echo "<tr>";
			echo "<td colspan=3 align=right>Nilai Rata-Rata (Jumlah Nilai / Jumlah Observasi)</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_rata\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_minicex[nilai_rata]\" id=\"nilai_rata\" required/></td>";
		echo "</tr>";

		//Komentar / Saran
		echo "<tr>";
			echo "<td colspan=4>Komentar / Saran:<br><textarea name=\"saran\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\">$data_minicex[saran]</textarea></td>";
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
				window.location.href=\"penilaian_mata.php\";
			</script>
			";
		}

		if ($_POST[approve]=="APPROVE")
		{
			$dosen_minicex = mysqli_fetch_array(mysqli_query($con,"SELECT `dosen` FROM `mata_nilai_minicex` WHERE `id`='$_POST[id]'"));
			$user_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `password` FROM `admin` WHERE `username`='$dosen_minicex[dosen]'"));
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `pin`,`qr` FROM `dosen` WHERE `nip`='$dosen_minicex[dosen]'"));
			$dosenpass_md5 = md5($_POST['dosenpass']);
			if (($_POST['dosenpass']!="" AND $dosenpass_md5==$user_dosen['password'])
			 OR ($_POST[dosenpin]!="" AND $_POST[dosenpin]==$data_dosen[pin])
			 OR ($_POST[dosenqr]!="" AND $_POST[dosenqr]==$data_dosen[qr]))
			{
				$aspek11 = number_format($_POST[observasi11]*$_POST[aspek11],2);
				$aspek12 = number_format($_POST[observasi12]*$_POST[aspek12],2);
				$aspek13 = number_format($_POST[observasi13]*$_POST[aspek13],2);
				$aspek14 = number_format($_POST[observasi14]*$_POST[aspek14],2);
				$aspek15 = number_format($_POST[observasi15]*$_POST[aspek15],2);
				$aspek16 = number_format($_POST[observasi16]*$_POST[aspek16],2);
				$aspek17 = number_format($_POST[observasi17]*$_POST[aspek17],2);

				$aspek21 = number_format($_POST[observasi21]*$_POST[aspek21],2);
				$aspek22 = number_format($_POST[observasi22]*$_POST[aspek22],2);
				$aspek23 = number_format($_POST[observasi23]*$_POST[aspek23],2);
				$aspek24 = number_format($_POST[observasi24]*$_POST[aspek24],2);

				$aspek31 = number_format($_POST[observasi31]*$_POST[aspek31],2);
				$aspek32 = number_format($_POST[observasi32]*$_POST[aspek32],2);
				$aspek33 = number_format($_POST[observasi33]*$_POST[aspek33],2);
				$aspek34 = number_format($_POST[observasi34]*$_POST[aspek34],2);
				$aspek35 = number_format($_POST[observasi35]*$_POST[aspek35],2);
				$aspek36 = number_format($_POST[observasi36]*$_POST[aspek36],2);

				$aspek41 = number_format($_POST[observasi41]*$_POST[aspek41],2);
				$aspek42 = number_format($_POST[observasi42]*$_POST[aspek42],2);
				$aspek43 = number_format($_POST[observasi43]*$_POST[aspek43],2);

				$obs_komunikasi = $_POST[observasi11]+$_POST[observasi12]+$_POST[observasi13]+$_POST[observasi14]+$_POST[observasi15]+$_POST[observasi16]+$_POST[observasi17];
				$asp_komunikasi = $_POST[observasi11]*$_POST[aspek11]+$_POST[observasi12]*$_POST[aspek12]+$_POST[observasi13]*$_POST[aspek13]+$_POST[observasi14]*$_POST[aspek14]+$_POST[observasi15]*$_POST[aspek15]+$_POST[observasi16]*$_POST[aspek16]+$_POST[observasi17]*$_POST[aspek17];

				$obs_profesionalisme = $_POST[observasi21]+$_POST[observasi22]+$_POST[observasi23]+$_POST[observasi24];
				$asp_profesionalisme = $_POST[observasi21]*$_POST[aspek21]+$_POST[observasi22]*$_POST[aspek22]+$_POST[observasi23]*$_POST[aspek23]+$_POST[observasi24]*$_POST[aspek24];

				$obs_ketrampilan = $_POST[observasi31]+$_POST[observasi32]+$_POST[observasi33]+$_POST[observasi34]+$_POST[observasi35]+$_POST[observasi36];
				$asp_ketrampilan = $_POST[observasi31]*$_POST[aspek31]+$_POST[observasi32]*$_POST[aspek32]+$_POST[observasi33]*$_POST[aspek33]+$_POST[observasi34]*$_POST[aspek34]+$_POST[observasi35]*$_POST[aspek35]+$_POST[observasi36]*$_POST[aspek36];

				$obs_clinical = $_POST[observasi41]+$_POST[observasi42]+$_POST[observasi43];
				$asp_clinical = $_POST[observasi41]*$_POST[aspek41]+$_POST[observasi42]*$_POST[aspek42]+$_POST[observasi43]*$_POST[aspek43];

				$jml_aspek = $asp_komunikasi + $asp_profesionalisme + $asp_ketrampilan + $asp_clinical;
				$jml_obs = $obs_komunikasi + $obs_profesionalisme + $obs_ketrampilan + $obs_clinical;

				if ($jml_obs==0) $rata_nilai = 0;
				else $rata_nilai = $jml_aspek/$jml_obs;
				$rata_nilai = number_format($rata_nilai,2);

				$saran = addslashes($_POST[saran]);

				$update_minicex=mysqli_query($con,"UPDATE `mata_nilai_minicex` SET
					`tgl_ujian`='$_POST[tanggal_ujian]',
					`aspek_11`='$aspek11',`observasi_11`='$_POST[observasi11]',
					`aspek_12`='$aspek12',`observasi_12`='$_POST[observasi12]',
					`aspek_13`='$aspek13',`observasi_13`='$_POST[observasi13]',
					`aspek_14`='$aspek14',`observasi_14`='$_POST[observasi14]',
					`aspek_15`='$aspek15',`observasi_15`='$_POST[observasi15]',
					`aspek_16`='$aspek16',`observasi_16`='$_POST[observasi16]',
					`aspek_17`='$aspek17',`observasi_17`='$_POST[observasi17]',
					`aspek_21`='$aspek21',`observasi_21`='$_POST[observasi21]',
					`aspek_22`='$aspek22',`observasi_22`='$_POST[observasi22]',
					`aspek_23`='$aspek23',`observasi_23`='$_POST[observasi23]',
					`aspek_24`='$aspek24',`observasi_24`='$_POST[observasi24]',
					`aspek_31`='$aspek31',`observasi_31`='$_POST[observasi31]',
					`aspek_32`='$aspek32',`observasi_32`='$_POST[observasi32]',
					`aspek_33`='$aspek33',`observasi_33`='$_POST[observasi33]',
					`aspek_34`='$aspek34',`observasi_34`='$_POST[observasi34]',
					`aspek_35`='$aspek35',`observasi_35`='$_POST[observasi35]',
					`aspek_36`='$aspek36',`observasi_36`='$_POST[observasi36]',
					`aspek_41`='$aspek41',`observasi_41`='$_POST[observasi41]',
					`aspek_42`='$aspek42',`observasi_42`='$_POST[observasi42]',
					`aspek_43`='$aspek43',`observasi_43`='$_POST[observasi43]',
					`saran`='$saran',`waktu_obs`='$_POST[waktu_obs]',`waktu_ub`='$_POST[waktu_ub]',
					`nilai_rata`='$rata_nilai',`tgl_approval`='$tgl',`status_approval`='1'
					WHERE `id`='$_POST[id]'");


				echo "
					<script>
					alert(\"Approval SUKSES ...\");
					window.location.href = \"penilaian_mata.php\";
	        </script>
					";
			}
			else
			{
				echo "
				<script>
				alert(\"Approval GAGAL ...\");
				window.location.href = \"approve_minicex.php?id=\"+\"$_POST[id]\";
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
<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
<script src="../select2/dist/js/select2.js"></script>
<script>
$(document).ready(function() {
	$("#dosen").select2({
		 placeholder: "<< Dosen Penguji >>",
     allowClear: true
	 });

 });
</script>
<script type="text/javascript" src="../jquery_ui/jquery-ui.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.tanggal_ujian').datepicker({ dateFormat: 'yy-mm-dd' });
	});
</script>

<script>
function sum() {
      var aspek11 = document.getElementById('aspek11').value;
			var aspek12 = document.getElementById('aspek12').value;
			var aspek13 = document.getElementById('aspek13').value;
			var aspek14 = document.getElementById('aspek14').value;
			var aspek15 = document.getElementById('aspek15').value;
			var aspek16 = document.getElementById('aspek16').value;
			var aspek17 = document.getElementById('aspek17').value;
			var observasi11 = $("input[name=observasi11]:checked").val();
			var observasi12 = $("input[name=observasi12]:checked").val();
			var observasi13 = $("input[name=observasi13]:checked").val();
			var observasi14 = $("input[name=observasi14]:checked").val();
			var observasi15 = $("input[name=observasi15]:checked").val();
			var observasi16 = $("input[name=observasi16]:checked").val();
			var observasi17 = $("input[name=observasi17]:checked").val();

			var total1 = parseInt(observasi11)*parseFloat(aspek11) + parseInt(observasi12)*parseFloat(aspek12) + parseInt(observasi13)*parseFloat(aspek13) + parseInt(observasi14)*parseFloat(aspek14) + parseInt(observasi15)*parseFloat(aspek15) + parseInt(observasi16)*parseFloat(aspek16) + parseInt(observasi17)*parseFloat(aspek17);
			var pembagi1 = parseInt(observasi11) + parseInt(observasi12) + parseInt(observasi13) + parseInt(observasi14) + parseInt(observasi15) + parseInt(observasi16) + parseInt(observasi17);

			var aspek21 = document.getElementById('aspek21').value;
			var aspek22 = document.getElementById('aspek22').value;
			var aspek23 = document.getElementById('aspek23').value;
			var aspek24 = document.getElementById('aspek24').value;
			var observasi21 = $("input[name=observasi21]:checked").val();
			var observasi22 = $("input[name=observasi22]:checked").val();
			var observasi23 = $("input[name=observasi23]:checked").val();
			var observasi24 = $("input[name=observasi24]:checked").val();

			var total2 = parseInt(observasi21)*parseFloat(aspek21) + parseInt(observasi22)*parseFloat(aspek22) + parseInt(observasi23)*parseFloat(aspek23) + parseInt(observasi24)*parseFloat(aspek24);
			var pembagi2 = parseInt(observasi21) + parseInt(observasi22) + parseInt(observasi23) + parseInt(observasi24);

			var aspek31 = document.getElementById('aspek31').value;
			var aspek32 = document.getElementById('aspek32').value;
			var aspek33 = document.getElementById('aspek33').value;
			var aspek34 = document.getElementById('aspek34').value;
			var aspek35 = document.getElementById('aspek35').value;
			var aspek36 = document.getElementById('aspek36').value;
			var observasi31 = $("input[name=observasi31]:checked").val();
			var observasi32 = $("input[name=observasi32]:checked").val();
			var observasi33 = $("input[name=observasi33]:checked").val();
			var observasi34 = $("input[name=observasi34]:checked").val();
			var observasi35 = $("input[name=observasi35]:checked").val();
			var observasi36 = $("input[name=observasi36]:checked").val();

			var total3 = parseInt(observasi31)*parseFloat(aspek31) + parseInt(observasi32)*parseFloat(aspek32) + parseInt(observasi33)*parseFloat(aspek33) + parseInt(observasi34)*parseFloat(aspek34) + parseInt(observasi35)*parseFloat(aspek35) + parseInt(observasi36)*parseFloat(aspek36);
			var pembagi3 = parseInt(observasi31) + parseInt(observasi32) + parseInt(observasi33) + parseInt(observasi34) + parseInt(observasi35) + parseInt(observasi36);

			var aspek41 = document.getElementById('aspek41').value;
			var aspek42 = document.getElementById('aspek42').value;
			var aspek43 = document.getElementById('aspek43').value;
			var observasi41 = $("input[name=observasi41]:checked").val();
			var observasi42 = $("input[name=observasi42]:checked").val();
			var observasi43 = $("input[name=observasi43]:checked").val();

			var total4 = parseInt(observasi41)*parseFloat(aspek41) + parseInt(observasi42)*parseFloat(aspek42) + parseInt(observasi43)*parseFloat(aspek43);
			var pembagi4 = parseInt(observasi41) + parseInt(observasi42) + parseInt(observasi43);

      var result_total = parseFloat(total1) + parseFloat(total2) + parseFloat(total3) + parseFloat(total4);
			var pembagi_total = parseInt(pembagi1) + parseInt(pembagi2) + parseInt(pembagi3) + parseInt(pembagi4);
			var result = parseFloat(result_total)/parseInt(pembagi_total);

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
