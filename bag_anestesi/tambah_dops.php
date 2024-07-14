<HTML>
<head>
	<link rel="stylesheet" href="../menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../mytable.css" type="text/css" media="screen" />
	<link rel="stylesheet" type="text/css" href="../jquery_ui/jquery-ui.css">
	<meta name="viewport" content="width=device-width, maximum-scale=1">
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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) ANESTESI DAN INTENSIVE CARE</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">FORMULIR PENILAIAN DIRECT OBSERVATION OF PROCEDURAL SKILL (DOPS)<p>KEPANITERAAN (STASE) ANESTESI DAN INTENSIVE CARE</font></h4>";

		$id_stase = "M102";
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
		$stase_id = "stase_".$id_stase;
		$data_stase_mhsw = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'");
		$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);

		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		echo "<input type=\"hidden\" name=\"id_stase\" value=\"$id_stase\">";
		echo "<input type=\"hidden\" name=\"nim\" value=\"$data_mhsw[nim]\">";
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
			echo "<td class=\"td_mid\"><input type=\"text\" class=\"tanggal_ujian\" name=\"tanggal_ujian\" style=\"font-size:1em;font-family:TAHOMA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" placeholder=\"yyyy-mm-dd\" /></td>";
		echo "</tr>";
		//Dosen Penilai
		echo "<tr>";
			echo "<td>Dosen Penilai</td>";
			echo "<td>";
			echo "<select class=\"select_art\" name=\"dosen\" id=\"dosen\" required>";
			$dosen = mysqli_query($con,"SELECT `username`,`nama` FROM `admin` WHERE `level`='4' OR (`level`='6' AND `stase`='$id_stase') ORDER BY `nama`");
			echo "<option value=\"\"><< Dosen Penilai >></option>";
			while ($data=mysqli_fetch_array($dosen))
			{
				$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data[username]'"));
				echo "<option value=\"$data[username]\">$data_dosen[nama], $data_dosen[gelar] ($data_dosen[nip])</option>";
			}
			echo "</select>";
			echo "</td>";
		echo "</tr>";
		//Situasi Ruangan
		echo "<tr>";
			echo "<td>Situasi Ruangan</td>";
			echo "<td>
						<input type=\"radio\" name=\"situasi_ruangan\" value=\"IRD\" checked/>&nbsp;&nbsp;IRD
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"situasi_ruangan\" value=\"Bangsal\" />&nbsp;&nbsp;Bangsal
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"situasi_ruangan\" value=\"Lapangan/Lain-lain\" />&nbsp;&nbsp;Lapangan/Lain-lain
						</td>";
		echo "</tr>";
		//Jenis Tindak Medik
		echo "<tr>";
			echo "<td>Jenis Tindak Medik</td>";
			echo "<td><textarea name=\"tindak_medik\" style=\"width:97%;font-family:Tahoma;font-size:1em\" placeholder=\"<< Jenis Tindak Medik >>\" required></textarea></td>";
		echo "</tr>";
		//Jumlah tindak medik serupa yang pernah diobservasi penilai
		echo "<tr>";
			echo "<td>Jumlah tindak medik serupa yang pernah diobservasi penilai</td>";
			echo "<td>
						<input type=\"radio\" name=\"obs_penilai\" value=\"0\" checked/>&nbsp;&nbsp;0
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"obs_penilai\" value=\"1\" />&nbsp;&nbsp;1
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"obs_penilai\" value=\"2\" />&nbsp;&nbsp;2
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"obs_penilai\" value=\"3\" />&nbsp;&nbsp;3
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"obs_penilai\" value=\"5-9\" />&nbsp;&nbsp;5-9
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"obs_penilai\" value=\">9\" />&nbsp;&nbsp;>9
						</td>";
		echo "</tr>";
		//Jumlah tindak medik serupa yang pernah dilakukan mahasiswa
		echo "<tr>";
			echo "<td>Jumlah tindak medik serupa yang pernah dilakukan mahasiswa</td>";
			echo "<td>
						<input type=\"radio\" name=\"obs_mhsw\" value=\"0\" checked/>&nbsp;&nbsp;0
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"obs_mhsw\" value=\"1\" />&nbsp;&nbsp;1
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"obs_mhsw\" value=\"2\" />&nbsp;&nbsp;2
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"obs_mhsw\" value=\"3\" />&nbsp;&nbsp;3
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"obs_mhsw\" value=\"5-9\" />&nbsp;&nbsp;5-9
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"obs_mhsw\" value=\">9\" />&nbsp;&nbsp;>9
						</td>";
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
			echo "<td align=center><input type=\"radio\" name=\"observasi1\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"observasi1\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek1\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"aspek1\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>Mendapat persetujuan tindak medik</td>";
			echo "<td align=center><input type=\"radio\" name=\"observasi2\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"observasi2\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek2\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"aspek2\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>Mampu mengajukan persiapan yang sesuai sebelum tindak medik</td>";
			echo "<td align=center><input type=\"radio\" name=\"observasi3\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"observasi3\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek3\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"aspek3\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 4
		echo "<tr>";
			echo "<td align=center>4</td>";
			echo "<td>Mampu memberikan analgesic yang sesuai atau sedasi yang aman</td>";
			echo "<td align=center><input type=\"radio\" name=\"observasi4\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"observasi4\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek4\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"aspek4\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 5
		echo "<tr>";
			echo "<td align=center>5</td>";
			echo "<td>Kemampuan secara teknik</td>";
			echo "<td align=center><input type=\"radio\" name=\"observasi5\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"observasi5\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek5\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"aspek5\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 6
		echo "<tr>";
			echo "<td align=center>6</td>";
			echo "<td>Melakukan tindakan aseptic</td>";
			echo "<td align=center><input type=\"radio\" name=\"observasi6\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"observasi6\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek6\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"aspek6\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 7
		echo "<tr>";
			echo "<td align=center>7</td>";
			echo "<td>Mencari bantuan bila diperlukan</td>";
			echo "<td align=center><input type=\"radio\" name=\"observasi7\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"observasi7\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek7\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"aspek7\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 8
		echo "<tr>";
			echo "<td align=center>8</td>";
			echo "<td>Tatalaksana paska tindakan</td>";
			echo "<td align=center><input type=\"radio\" name=\"observasi8\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"observasi8\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek8\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"aspek8\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 9
		echo "<tr>";
			echo "<td align=center>9</td>";
			echo "<td>Kecakapan komunikasi</td>";
			echo "<td align=center><input type=\"radio\" name=\"observasi9\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"observasi9\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek9\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"aspek9\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 10
		echo "<tr>";
			echo "<td align=center>10</td>";
			echo "<td>Mempertimbangkan kondisi pasien / profesionalisme</td>";
			echo "<td align=center><input type=\"radio\" name=\"observasi10\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"observasi10\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek10\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"aspek10\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 11
		echo "<tr>";
			echo "<td align=center>11</td>";
			echo "<td>Kemampuan secara keseluruhan dalam melakukan tindak medik</td>";
			echo "<td align=center><input type=\"radio\" name=\"observasi11\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"observasi11\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek11\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"aspek11\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";

		//Rata Nilai
		echo "<tr>";
			echo "<td align=right colspan=3>Rata-Rata Nilai (Jumlah Nilai / Jumlah Observasi)</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_rata\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"nilai_rata\" value=\"0\" required/></td>";
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
			echo "<td><textarea name=\"ub_bagus\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\"></textarea></td>";
			echo "<td><textarea name=\"ub_perbaikan\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\"></textarea></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td colspan=2>Saran:<br><textarea name=\"saran\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\"></textarea></td>";
		echo "</tr>";
		//Catatan
		echo "<tr><td colspan=2><b>Catatan:</b></td></tr>";
		echo "<tr><td colspan=2>Waktu Penilaian Diskusi Kasus:</td></tr>";
		echo "<tr>";
			echo "<td>&nbsp;&nbsp;Observasi</td>";
			echo "<td>";
			echo "<input type=\"number\" step=\"5\" min=\"0\" max=\"1500\" name=\"waktu_observasi\" style=\"width:20%;font-size:0.85em\" placeholder=\"0\" required/>&nbsp;&nbsp;menit<br>";
			echo "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>&nbsp;&nbsp;Memberikan umpan balik</td>";
			echo "<td>";
			echo "<input type=\"number\" step=\"5\" min=\"0\" max=\"1500\" name=\"waktu_ub\" style=\"width:20%;font-size:0.85em\" placeholder=\"0\" required/>&nbsp;&nbsp;menit<br>";
			echo "</td>";
		echo "</tr>";
		echo "</table><br>";

		echo "<br><center><input type=\"submit\" class=\"submit1\" name=\"cancel\" value=\"CANCEL\" formnovalidate>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" class=\"submit1\" name=\"usulkan\" value=\"USULKAN\"></center></form><br><br></fieldset>";

		if ($_POST[cancel]=="CANCEL")
		{
			echo "
				<script>
					window.location.href=\"penilaian_anestesi.php\";
				</script>
				";
		}

		if ($_POST[usulkan]=="USULKAN")
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

			$insert_dops=mysqli_query($con,"INSERT INTO `anestesi_nilai_dops`
				( `nim`, `dosen`, `tgl_ujian`, `situasi_ruangan`,
					`tindak_medik`, `obs_penilai`, `obs_mhsw`,
					`aspek_1`, `observasi_1`, `aspek_2`, `observasi_2`,
					`aspek_3`, `observasi_3`, `aspek_4`, `observasi_4`,
					`aspek_5`, `observasi_5`, `aspek_6`, `observasi_6`,
					`aspek_7`, `observasi_7`, `aspek_8`, `observasi_8`,
					`aspek_9`, `observasi_9`, `aspek_10`, `observasi_10`,
					`aspek_11`, `observasi_11`, `ub_bagus`, `ub_perbaikan`,
					`saran`, `waktu_observasi`, `waktu_ub`, `nilai_rata`,
					`tgl_isi`, `tgl_approval`, `status_approval`)
				VALUES
				( '$_POST[nim]','$_POST[dosen]','$_POST[tanggal_ujian]','$_POST[situasi_ruangan]',
					'$tindak_medik','$_POST[obs_penilai]','$_POST[obs_mhsw]',
					'$aspek1','$_POST[observasi1]','$aspek2','$_POST[observasi2]',
					'$aspek3','$_POST[observasi3]','$aspek4','$_POST[observasi4]',
					'$aspek5','$_POST[observasi5]','$aspek6','$_POST[observasi6]',
					'$aspek7','$_POST[observasi7]','$aspek8','$_POST[observasi8]',
					'$aspek9','$_POST[observasi9]','$aspek10','$_POST[observasi10]',
					'$aspek11','$_POST[observasi11]','$ub_bagus','$ub_perbaikan',
					'$saran','$_POST[waktu_observasi]','$_POST[waktu_ub]','$nilai_rata',
					'$tgl','2000-01-01','0')");

			echo "
				<script>
					window.location.href=\"penilaian_anestesi.php\";
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
<script src="../select2/dist/js/select2.js"></script>
<script>
$(document).ready(function() {
	$("#dosen").select2({
		 placeholder: "<< Dosen Penilai >>",
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
