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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) ILMU KEBIDANAN DAN PENYAKIT KANDUNGAN</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">APPROVAL NILAI UJIAN MINI-CEX<p>KEPANITERAAN (STASE) ILMU KEBIDANAN DAN PENYAKIT KANDUNGAN</font></h4>";

		$id_stase = "M111";
		$id = $_GET['id'];
		$data_minicex = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `obsgyn_nilai_minicex` WHERE `id`='$id'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_minicex[nim]'"));

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
		//Tanggal Ujian
		echo "<tr>";
			echo "<td class=\"td_mid\">Tanggal Ujian (yyyy-mm-dd)</td>";
			echo "<td class=\"td_mid\"><input type=\"text\" class=\"tanggal_ujian\" name=\"tanggal_ujian\" style=\"font-size:1em;font-family:TAHOMA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" value=\"$data_minicex[tgl_ujian]\" /></td>";
		echo "</tr>";
		//Dosen Penilai
		echo "<tr>";
			echo "<td>Dosen Penilai</td>";
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_minicex[dosen]'"));
			echo "<td>$data_dosen[nama], $data_dosen[gelar] ($data_dosen[nip])</td>";
		echo "</tr>";
		//Situasi Ruangan
		echo "<tr>";
			echo "<td>Situasi Ruangan</td>";
			echo "<td>";
			if ($data_minicex[situasi_ruangan]=="UGD")
				echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"UGD\" checked/>&nbsp;&nbsp;UGD";
			else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"UGD\" />&nbsp;&nbsp;UGD";
			echo "<br>";
			if ($data_minicex[situasi_ruangan]=="Rawat Jalan")
				echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Jalan\" checked/>&nbsp;&nbsp;Rawat Jalan";
			else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Jalan\" />&nbsp;&nbsp;Rawat Jalan";
			echo "<br>";
			if ($data_minicex[situasi_ruangan]=="Rawat Inap")
				echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Inap\" checked/>&nbsp;&nbsp;Rawat Inap";
			else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Inap\" />&nbsp;&nbsp;Rawat Inap";
			echo "</td>";
		echo "</tr>";
		//Diagnosis Pasien
		echo "<tr>";
			echo "<td>Diagnosis Pasien</td>";
			echo "<td><textarea name=\"diagnosis\" style=\"width:97%;font-family:Tahoma;font-size:1em\" required>$data_minicex[diagnosis]</textarea></td>";
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
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_1\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_1]\" id=\"aspek_1\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>Kemampuan Pemeriksaan Fisik (<i>Physical Examination Skills</i>)</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_2\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_2]\" id=\"aspek_2\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>Kualitas Humanisti / Profesionalisme</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_3\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_3]\" id=\"aspek_3\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 4
		echo "<tr>";
			echo "<td align=center>4</td>";
			echo "<td>Keputusan Klinis</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_4\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_4]\" id=\"aspek_4\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 5
		echo "<tr>";
			echo "<td align=center>5</td>";
			echo "<td>Kemampuan Penatalaksanaan Pasien</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_5\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_5]\" id=\"aspek_5\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 6
		echo "<tr>";
			echo "<td align=center>6</td>";
			echo "<td>Kemampuan Konseling</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_6\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_6]\" id=\"aspek_6\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 7
		echo "<tr>";
			echo "<td align=center>7</td>";
			echo "<td>Organisasi / Efisiensi</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_7\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_7]\" id=\"aspek_7\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 8
		echo "<tr>";
			echo "<td align=center>8</td>";
			echo "<td>Kompetensi Klinis Keseluruhan</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_8\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_8]\" id=\"aspek_8\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//Rata Nilai
		echo "<tr>";
			echo "<td align=right colspan=2>Rata-Rata Nilai (Total Nilai / 8)</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_rata\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_minicex[nilai_rata]\" id=\"nilai_rata\" required/></td>";
		echo "</tr>";
		echo "<tr><td colspan=3><font style=\"font-size:0.75em;\"><i>Keterangan: Nilai Batas Lulus (NBL) = 70</i></font></td></tr>";
		echo "</table><br><br>";

		//Umpan Balik
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr><td colspan=2><b>Umpan Balik Terhadap MINI-CEX:</b><br>
					<textarea name=\"umpan_balik\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\">$data_minicex[umpan_balik]</textarea></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td colspan=2><b>Saran:</b><br>
					<textarea name=\"saran\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\">$data_minicex[saran]</textarea></td>";
		echo "</tr>";
		//Catatan
		echo "<tr><td colspan=2><b>Catatan:</b></td></tr>";
		echo "<tr><td colspan=2>Waktu Penilaian MINI-CEX:</td></tr>";
		echo "<tr>";
			echo "<td>&nbsp;&nbsp;Observasi</td>";
			echo "<td>";
			echo "<input type=\"number\" step=\"5\" min=\"0\" max=\"1500\" name=\"waktu_observasi\" style=\"width:20%;font-size:0.85em\" value=\"$data_minicex[waktu_observasi]\" required/>&nbsp;&nbsp;menit<br>";
			echo "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>&nbsp;&nbsp;Memberikan umpan balik</td>";
			echo "<td>";
			echo "<input type=\"number\" step=\"5\" min=\"0\" max=\"1500\" name=\"waktu_ub\" style=\"width:20%;font-size:0.85em\" value=\"$data_minicex[waktu_ub]\" required/>&nbsp;&nbsp;menit<br>";
			echo "</td>";
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
				window.location.href=\"penilaian_obsgyn_dosen.php?mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\";
			</script>
			";
		}

		if ($_POST[approve]=="APPROVE")
		{
			$tgl_mulai=$_POST[tgl_mulai];
			$tgl_selesai=$_POST[tgl_selesai];
			$approval=$_POST[approval];
			$mhsw=$_POST[mhsw];
			$aspek1 = number_format($_POST[aspek_1],2);
			$aspek2 = number_format($_POST[aspek_2],2);
			$aspek3 = number_format($_POST[aspek_3],2);
			$aspek4 = number_format($_POST[aspek_4],2);
			$aspek5 = number_format($_POST[aspek_5],2);
			$aspek6 = number_format($_POST[aspek_6],2);
			$aspek7 = number_format($_POST[aspek_7],2);
			$aspek8 = number_format($_POST[aspek_8],2);

			$diagnosis = addslashes($_POST[diagnosis]);
			$umpan_balik = addslashes($_POST[umpan_balik]);
			$saran = addslashes($_POST[saran]);
			$nilai_total = $_POST[aspek_1]+$_POST[aspek_2]+$_POST[aspek_3]+$_POST[aspek_4]+$_POST[aspek_5]+$_POST[aspek_6]+$_POST[aspek_7]+$_POST[aspek_8];
			$nilai_rata = $nilai_total/8;
			$nilai_total = number_format($nilai_total,2);
			$nilai_rata = number_format($nilai_rata,2);

			$umur_pasien = (int)$_POST[umur_pasien];

			$update_minicex=mysqli_query($con,"UPDATE `obsgyn_nilai_minicex` SET
				`tgl_ujian`='$_POST[tanggal_ujian]',
				`diagnosis`='$diagnosis',`situasi_ruangan`='$_POST[situasi_ruangan]',
				`aspek_1`='$aspek1',
				`aspek_2`='$aspek2',`aspek_3`='$aspek3',
				`aspek_4`='$aspek4',`aspek_5`='$aspek5',
				`aspek_6`='$aspek6',`aspek_7`='$aspek7',
				`aspek_8`='$aspek8',`umpan_balik`='$umpan_balik',
				`saran`='$saran',
				`waktu_observasi`='$_POST[waktu_observasi]',`waktu_ub`='$_POST[waktu_ub]',
				`nilai_rata`='$nilai_rata',`tgl_approval`='$tgl',`status_approval`='1'
				WHERE `id`='$_POST[id]'");

			echo "
				<script>
				window.location.href = \"penilaian_obsgyn_dosen.php?mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\";
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
			var aspek8 = document.getElementById('aspek_8').value;
      var result = (parseFloat(aspek1) + parseFloat(aspek2) + parseFloat(aspek3) + parseFloat(aspek4) + parseFloat(aspek5) + parseFloat(aspek6) + parseFloat(aspek7) + parseFloat(aspek8))/8;
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
