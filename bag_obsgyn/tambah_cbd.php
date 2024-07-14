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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) ILMU KEBIDANAN DAN PENYAKIT KANDUNGAN</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">FORMULIR PENILAIAN CASE-BASED DISCUSSION (CBD)<p>KEPANITERAAN (STASE) ILMU KEBIDANAN DAN PENYAKIT KANDUNGAN</font></h4>";

		$id_stase = "M111";
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
		//Periode Kegiatan
		echo "<tr>";
			echo "<td class=\"td_mid\">Periode Kegiatan (yyyy-mm-dd)</td>";
			echo "<td class=\"td_mid\"><input type=\"text\" class=\"periode_awal\" name=\"periode_awal\" style=\"font-size:1em;font-family:TAHOMA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" value=\"$datastase_mhsw[tgl_mulai]\" />";
			echo " s.d. <input type=\"text\" class=\"periode_akhir\" name=\"periode_akhir\" style=\"font-size:1em;font-family:TAHOMA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" value=\"$datastase_mhsw[tgl_selesai]\" /></td>";
		echo "</tr>";
		//Tanggal Ujian
		echo "<tr>";
			echo "<td class=\"td_mid\">Tanggal Ujian (yyyy-mm-dd)</td>";
			echo "<td class=\"td_mid\"><input type=\"text\" class=\"tanggal_ujian\" name=\"tanggal_ujian\" style=\"font-size:1em;font-family:TAHOMA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" placeholder=\"yyyy-mm-dd\" /></td>";
		echo "</tr>";
		//Kasus CBD ke-
		echo "<tr>";
			echo "<td>Kasus CBD ke-</td>";
			echo "<td>";
			echo "<input type=\"radio\" name=\"kasus_ke\" value=\"1\" checked/>&nbsp;&nbsp;1 (CBD Kesatu)<br>
						<input type=\"radio\" name=\"kasus_ke\" value=\"2\" />&nbsp;&nbsp;2 (CBD Kedua)<br>
						<input type=\"radio\" name=\"kasus_ke\" value=\"3\" />&nbsp;&nbsp;3 (CBD Ketiga)<br>
						<input type=\"radio\" name=\"kasus_ke\" value=\"4\" />&nbsp;&nbsp;4 (CBD Keempat)";
			echo "</td>";
		echo "</tr>";
		//Dosen Penilai/Penguji
		echo "<tr>";
			echo "<td>Dosen Penilai/Penguji</td>";
			echo "<td>";
			echo "<select class=\"select_art\" name=\"dosen\" id=\"dosen\" required>";
			$dosen = mysqli_query($con,"SELECT `username`,`nama` FROM `admin` WHERE `level`='4' OR (`level`='6' AND `stase`='$id_stase') ORDER BY `nama`");
			echo "<option value=\"\"><< Dosen Penilai/Penguji >></option>";
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
						<input type=\"radio\" name=\"situasi_ruangan\" value=\"UGD\" checked/>&nbsp;&nbsp;UGD<br>
						<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Jalan\" />&nbsp;&nbsp;Rawat Jalan<br>
						<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Inap\" />&nbsp;&nbsp;Rawat Inap
						</td>";
		echo "</tr>";
		//Problem/Diagnosis Pasien
		echo "<tr>";
			echo "<td>Problem/Diagnosis Pasien</td>";
			echo "<td><textarea name=\"diagnosis\" style=\"width:97%;font-family:Tahoma;font-size:1em\" placeholder=\"<< Problem Pasien/Diagnosis >>\" required></textarea></td>";
		echo "</tr>";
		//Fokus Kasus
		echo "<tr>";
			echo "<td>Fokus Kasus</td>";
			echo "<td>
						<input type=\"radio\" name=\"fokus_kasus\" value=\"Pembuatan Rekam Medik\" checked/>&nbsp;&nbsp;Pembuatan Rekam Medik<br>
						<input type=\"radio\" name=\"fokus_kasus\" value=\"Clinical Assesment\" />&nbsp;&nbsp;Clinical Assesment<br>
						<input type=\"radio\" name=\"fokus_kasus\" value=\"Tata Laksana\" />&nbsp;&nbsp;Tata Laksana<br>
						<input type=\"radio\" name=\"fokus_kasus\" value=\"Profesionalisme\" />&nbsp;&nbsp;Profesionalisme
						</td>";
		echo "</tr>";
		//Tingkat Kerumitan
		echo "<tr>";
			echo "<td>Tingkat Kerumitan</td>";
			echo "<td>
						<input type=\"radio\" name=\"tingkat_kerumitan\" value=\"Rendah\" checked/>&nbsp;&nbsp;Rendah<br>
						<input type=\"radio\" name=\"tingkat_kerumitan\" value=\"Sedang\" />&nbsp;&nbsp;Sedang<br>
						<input type=\"radio\" name=\"tingkat_kerumitan\" value=\"Tinggi\" />&nbsp;&nbsp;Tinggi
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
			echo "<td>Penulisan/pembuatan rekam medik</td>";
			echo "<td align=center><input type=\"radio\" name=\"observasi1\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"observasi1\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek1\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"aspek1\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>Penilaian klinis (<i>clinical assesment</i>)</td>";
			echo "<td align=center><input type=\"radio\" name=\"observasi2\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"observasi2\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek2\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"aspek2\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>Investigasi dan rujukan (<i>investigation and referral</i>)</td>";
			echo "<td align=center><input type=\"radio\" name=\"observasi3\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"observasi3\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek3\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"aspek3\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 4
		echo "<tr>";
			echo "<td align=center>4</td>";
			echo "<td>Tata laksana</td>";
			echo "<td align=center><input type=\"radio\" name=\"observasi4\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"observasi4\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek4\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"aspek4\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 5
		echo "<tr>";
			echo "<td align=center>5</td>";
			echo "<td>Pemantauan dan rencana selanjutnya (<i>follow up and future planning</i>)</td>";
			echo "<td align=center><input type=\"radio\" name=\"observasi5\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"observasi5\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek5\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"aspek5\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 6
		echo "<tr>";
			echo "<td align=center>6</td>";
			echo "<td>Profesionalisme</td>";
			echo "<td align=center><input type=\"radio\" name=\"observasi6\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"observasi6\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek6\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"aspek6\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 7
		echo "<tr>";
			echo "<td align=center>7</td>";
			echo "<td>Penilaian klinis secara keseluruhan</td>";
			echo "<td align=center><input type=\"radio\" name=\"observasi7\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"observasi7\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek7\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"aspek7\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
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
		echo "<tr><td colspan=2 align=center><b>UMPAN BALIK TERHADAP DISKUSI KASUS</b></td></tr>";
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
		echo "<tr><td colspan=2>Waktu Penilaian Kasus CBD:</td></tr>";
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
					window.location.href=\"penilaian_obsgyn.php\";
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

			$diagnosis = addslashes($_POST[diagnosis]);
			$ub_bagus = addslashes($_POST[ub_bagus]);
			$ub_perbaikan = addslashes($_POST[ub_perbaikan]);
			$saran = addslashes($_POST[saran]);
			$jml_observasi = $_POST[observasi1]+$_POST[observasi2]+$_POST[observasi3]+$_POST[observasi4]+$_POST[observasi5]+$_POST[observasi6]+$_POST[observasi7];
			$nilai_total = $_POST[observasi1]*$_POST[aspek1]+$_POST[observasi2]*$_POST[aspek2]+$_POST[observasi3]*$_POST[aspek3]+$_POST[observasi4]*$_POST[aspek4]+$_POST[observasi5]*$_POST[aspek5]+$_POST[observasi6]*$_POST[aspek6]+$_POST[observasi7]*$_POST[aspek7];
			if ($jml_observasi==0) $nilai_rata = 0;
			else $nilai_rata = $nilai_total/$jml_observasi;
			$nilai_rata = number_format($nilai_rata,2);

			$insert_cbd=mysqli_query($con,"INSERT INTO `obsgyn_nilai_cbd`
				( `nim`, `dosen`, `tgl_awal`, `tgl_akhir`, `tgl_ujian`,`kasus_ke`,
					`diagnosis`, `situasi_ruangan`, `tingkat_kerumitan`, `fokus_kasus`,
					`aspek_1`, `observasi_1`, `aspek_2`, `observasi_2`,
					`aspek_3`, `observasi_3`, `aspek_4`, `observasi_4`,
					`aspek_5`, `observasi_5`, `aspek_6`, `observasi_6`,
					`aspek_7`, `observasi_7`, `ub_bagus`, `ub_perbaikan`,
					`saran`, `waktu_observasi`, `waktu_ub`, `nilai_rata`,
					`tgl_isi`, `tgl_approval`, `status_approval`)
				VALUES
				( '$_POST[nim]','$_POST[dosen]','$_POST[periode_awal]','$_POST[periode_akhir]','$_POST[tanggal_ujian]','$_POST[kasus_ke]',
					'$diagnosis','$_POST[situasi_ruangan]','$_POST[tingkat_kerumitan]','$_POST[fokus_kasus]',
					'$aspek1','$_POST[observasi1]','$aspek2','$_POST[observasi2]',
					'$aspek3','$_POST[observasi3]','$aspek4','$_POST[observasi4]',
					'$aspek5','$_POST[observasi5]','$aspek6','$_POST[observasi6]',
					'$aspek7','$_POST[observasi7]','$ub_bagus','$ub_perbaikan',
					'$saran','$_POST[waktu_observasi]','$_POST[waktu_ub]','$nilai_rata',
					'$tgl','2000-01-01','0')");
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
<script src="../select2/dist/js/select2.js"></script>
<script>
$(document).ready(function() {
	$("#dosen").select2({
		 placeholder: "<< Dosen Penguji/Penilai >>",
     allowClear: true
	 });
 });
</script>
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
      var aspek1 = document.getElementById('aspek1').value;
			var aspek2 = document.getElementById('aspek2').value;
			var aspek3 = document.getElementById('aspek3').value;
			var aspek4 = document.getElementById('aspek4').value;
			var aspek5 = document.getElementById('aspek5').value;
			var aspek6 = document.getElementById('aspek6').value;
			var aspek7 = document.getElementById('aspek7').value;
			var observasi1 = $("input[name=observasi1]:checked").val();
			var observasi2 = $("input[name=observasi2]:checked").val();
			var observasi3 = $("input[name=observasi3]:checked").val();
			var observasi4 = $("input[name=observasi4]:checked").val();
			var observasi5 = $("input[name=observasi5]:checked").val();
			var observasi6 = $("input[name=observasi6]:checked").val();
			var observasi7 = $("input[name=observasi7]:checked").val();

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
