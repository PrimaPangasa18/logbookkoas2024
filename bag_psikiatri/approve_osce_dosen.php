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
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">APPROVAL NILAI UJIAN OSCE<p>Kepaniteraan (Stase) Ilmu Kesehatan Jiwa</font></h4>";

		$id_stase = "M093";
		$id = $_GET['id'];
		$data_osce = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `psikiatri_nilai_osce` WHERE `id`='$id'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_osce[nim]'"));
		$stase_id = "stase_".$id_stase;
		$data_stase_mhsw = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$data_osce[nim]'");
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
		//Tanggal Ujian
		echo "<tr>";
			echo "<td class=\"td_mid\">Tanggal Ujian (yyyy-mm-dd)</td>";
			echo "<td class=\"td_mid\"><input type=\"text\" class=\"tanggal_ujian\" name=\"tanggal_ujian\" style=\"font-size:1em;font-family:TAHOMA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" value=\"$data_osce[tgl_ujian]\" /></td>";
		echo "</tr>";
		//Dosen Penguji
		echo "<tr>";
			echo "<td>Dosen Penguji</td>";
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_osce[dosen]'"));
			echo "<td>$data_dosen[nama], $data_dosen[gelar] ($data_dosen[nip])</td>";
		echo "</tr>";
		echo "</table><br><br>";

		//Form nilai
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr><td><b>Form Penilaian:</b></td></tr>";
		echo "</table>";
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<thead>";
		 	echo "<th style=\"width:5%\">No</th>";
			echo "<th style=\"width:65%\">Komponen Penilaian</th>";
			echo "<th style=\"width:15%\">Bobot</th>";
			echo "<th style=\"width:15%\">Nilai (0-100)</th>";
		echo "</thead>";
		//No 1
		echo "<tr>";
			echo "<td align=center class=\"td_mid\">1</td>";
			echo "<td>Anamnesis</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center class=\"td_mid\"><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_1\" style=\"width:75%;font-size:0.85em;text-align:center\" id=\"aspek_1\" value=\"$data_osce[aspek_1]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center class=\"td_mid\">2</td>";
			echo "<td>Pemeriksaan Status Mental</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center class=\"td_mid\"><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_2\" style=\"width:75%;font-size:0.85em;text-align:center\" id=\"aspek_2\" value=\"$data_osce[aspek_2]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center class=\"td_mid\">3</td>";
			echo "<td>Diagnosis</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center class=\"td_mid\"><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_3\" style=\"width:75%;font-size:0.85em;text-align:center\" id=\"aspek_3\" value=\"$data_osce[aspek_3]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 4
		echo "<tr>";
			echo "<td align=center class=\"td_mid\">4</td>";
			echo "<td>Terapi farmakologi</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center class=\"td_mid\"><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_4\" style=\"width:75%;font-size:0.85em;text-align:center\" id=\"aspek_4\" value=\"$data_osce[aspek_4]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 5
		echo "<tr>";
			echo "<td align=center class=\"td_mid\">5</td>";
			echo "<td>Komunikasi dan edukasi pasien</td>";
			echo "<td align=center>10%</td>";
			echo "<td align=center class=\"td_mid\"><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_5\" style=\"width:75%;font-size:0.85em;text-align:center\" id=\"aspek_5\" value=\"$data_osce[aspek_5]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 6
		echo "<tr>";
			echo "<td align=center class=\"td_mid\">6</td>";
			echo "<td>Perilaku profesional</td>";
			echo "<td align=center>10%</td>";
			echo "<td align=center class=\"td_mid\"><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_6\" style=\"width:75%;font-size:0.85em;text-align:center\" id=\"aspek_6\" value=\"$data_osce[aspek_6]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";

		//Rata-Rata Nilai
		echo "<tr>";
			echo "<td colspan=3 align=right class=\"td_mid\">Rata-Rata Nilai (Jumlah Bobot x Nilai)</td>";
			echo "<td align=center class=\"td_mid\"><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_rata\" style=\"width:75%;font-size:0.85em;text-align:center\" id=\"nilai_rata\" value=\"$data_osce[nilai_rata]\" required/></td>";
		echo "</tr>";
		echo "<tr><td colspan=4><font style=\"font-size:0.75em;\">";
		echo "<i>Keterangan:<br>";
		echo "Nilai Batas Lulus (NBL) = 70<br>";
		echo "Nilai A = 80.00 - 100.00 (SUPERIOR)<br>";
		echo "Nilai B = 70.00 - 79.99 (LULUS)<br>";
		echo "Nilai C < 70.00 (TIDAK LULUS)";
		echo "</i></font></td></tr>";
		echo "</table><br>";

		//Umpan Balik
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr>";
		  echo "<td>Umpan Balik:<br><textarea name=\"umpan_balik\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\">$data_osce[umpan_balik]</textarea></td>";
		echo "</tr>";
		echo "<tr>";
		  echo "<td>Saran:<br><textarea name=\"saran\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\">$data_osce[saran]</textarea></td>";
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
			$aspek1 = number_format($_POST[aspek_1],2);
			$aspek2 = number_format($_POST[aspek_2],2);
			$aspek3 = number_format($_POST[aspek_3],2);
			$aspek4 = number_format($_POST[aspek_4],2);
			$aspek5 = number_format($_POST[aspek_5],2);
			$aspek6 = number_format($_POST[aspek_6],2);

			$nilai_rata = 0.2*$_POST[aspek_1]+0.2*$_POST[aspek_2]+0.2*$_POST[aspek_3]+0.2*$_POST[aspek_4]+0.1*$_POST[aspek_5]+0.1*$_POST[aspek_6];
			$nilai_rata = number_format($nilai_rata,2);

			$umpan_balik = addslashes($_POST[umpan_balik]);
			$saran = addslashes($_POST[saran]);

			$update_osce=mysqli_query($con,"UPDATE `psikiatri_nilai_osce` SET
				`tgl_ujian`='$_POST[tanggal_ujian]',
				`aspek_1`='$aspek1',`aspek_2`='$aspek2',
				`aspek_3`='$aspek3',`aspek_4`='$aspek4',
				`aspek_5`='$aspek5',`aspek_6`='$aspek6',
				`nilai_rata`='$nilai_rata',`umpan_balik`='$umpan_balik',`saran`='$saran',
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
	var result = 0.2*parseFloat(aspek1) + 0.2*parseFloat(aspek2) + 0.2*parseFloat(aspek3) + 0.2*parseFloat(aspek4) + 0.1*parseFloat(aspek5) + 0.1*parseFloat(aspek6);
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
