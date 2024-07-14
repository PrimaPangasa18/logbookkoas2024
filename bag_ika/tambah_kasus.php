<HTML>
<head>
	<link rel="stylesheet" href="../menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../mytable.css" type="text/css" media="screen" />
	<meta name="viewport" content="width=device-width, maximum-scale=1">
	<link rel="stylesheet" href="../select2/dist/css/select2.css"/>
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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND $_COOKIE['level']==5)
	{
		if ($_COOKIE['level']==5) {include "menu5.php";}

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) ILMU KESEHATAN ANAK</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">FORMULIR PENILAIAN PENYAJIAN KASUS BESAR<p>KEPANITERAAN (STASE) ILMU KESEHATAN ANAK</font></h4>";

		$id_stase = "M113";
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
		$stase_id = "stase_".$id_stase;
		$data_stase_mhsw = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'");
		$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);

		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		echo "<input type=\"hidden\" name=\"id_stase\" value=\"$id_stase\">";
		echo "<input type=\"hidden\" name=\"nim\" value=\"$data_mhsw[nim]\">";

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
		//Periode Kegiatan
		echo "<tr>";
			echo "<td class=\"td_mid\">Periode Stase (yyyy-mm-dd)</td>";
			echo "<td class=\"td_mid\"><input type=\"text\" class=\"tgl_awal\" name=\"tgl_awal\" style=\"font-size:1em;font-family:TAHOMA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" value=\"$datastase_mhsw[tgl_mulai]\" />";
			echo " s.d. <input type=\"text\" class=\"tgl_akhir\" name=\"tgl_akhir\" style=\"font-size:1em;font-family:TAHOMA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" value=\"$datastase_mhsw[tgl_selesai]\" /></td>";
		echo "</tr>";
		//Judul Kasus
		echo "<tr>";
			echo "<td>Kasus</td>";
			echo "<td><textarea name=\"kasus\" style=\"width:97%;font-family:Tahoma;font-size:1em\" placeholder=\"<< Kasus >>\" required></textarea></td>";
		echo "</tr>";
		//Tanggal Penyajian
		echo "<tr>";
			echo "<td class=\"td_mid\">Tanggal Penyajian (yyyy-mm-dd)</td>";
			echo "<td class=\"td_mid\"><input type=\"text\" class=\"tgl_penyajian\" name=\"tgl_penyajian\" style=\"font-size:1em;font-family:TAHOMA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" placeholder=\"yyyy-mm-dd\" /></td>";
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
		echo "</table><br><br>";

		//Form nilai
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr><td><b>Form Penilaian:</b></td></tr>";
		echo "</table>";
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<thead>";
		 	echo "<th style=\"width:5%\">No</th>";
			echo "<th style=\"width:55%\">Komponen Penilaian</th>";
			echo "<th style=\"width:20%\">Bobot</th>";
			echo "<th style=\"width:20%\">Nilai (0-100)</th>";
		echo "</thead>";
		//Aspek Penyajian
		echo "<tr>";
			echo "<td colspan=4>Aspek Penyajian:</td>";
		echo "</tr>";
		//No 1
		echo "<tr>";
			echo "<td align=center>1</td>";
			echo "<td>Slide</td>";
			echo "<td align=center>5%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek1\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"aspek1\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>Sikap dan suara</td>";
			echo "<td align=center>10%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek2\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"aspek2\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//Aspek Naskah
		echo "<tr>";
			echo "<td colspan=4>Aspek Naskah:</td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>Kesesuaian format penulisan</td>";
			echo "<td align=center>2.5%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek3\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"aspek3\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 4
		echo "<tr>";
			echo "<td align=center>4</td>";
			echo "<td>Ringkasan kasus</td>";
			echo "<td align=center>5%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek4\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"aspek4\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 5
		echo "<tr>";
			echo "<td align=center>5</td>";
			echo "<td>Pembahasan dan aplikasi EBM (PICO-VIA)</td>";
			echo "<td align=center>5%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek5\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"aspek5\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 6
		echo "<tr>";
			echo "<td align=center>6</td>";
			echo "<td>Kepustakaan</td>";
			echo "<td align=center>2.5%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek6\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"aspek6\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//Aspek Diskusi
		echo "<tr>";
			echo "<td colspan=4>Aspek Diskusi:</td>";
		echo "</tr>";
		//No 7
		echo "<tr>";
			echo "<td align=center>7</td>";
			echo "<td>Anamnesis (<i>sacred seven, fundamental four</i>)</td>";
			echo "<td align=center>10%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek7\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"aspek7\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 8
		echo "<tr>";
			echo "<td align=center>8</td>";
			echo "<td>Pemeriksaan Fisik (<i>status lokalis dan generalis</i>)</td>";
			echo "<td align=center>10%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek8\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"aspek8\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 9
		echo "<tr>";
			echo "<td align=center>9</td>";
			echo "<td>Pemeriksaan Penunjang (<i>jenis dan interpretasi hasil</i>)</td>";
			echo "<td align=center>10%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek9\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"aspek9\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 10
		echo "<tr>";
			echo "<td align=center>10</td>";
			echo "<td>Diagnosis Banding/Diagnosis Kerja</td>";
			echo "<td align=center>10%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek10\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"aspek10\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 11
		echo "<tr>";
			echo "<td align=center>11</td>";
			echo "<td>Tatalaksana</td>";
			echo "<td align=center>10%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek11\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"aspek11\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 12
		echo "<tr>";
			echo "<td align=center>12</td>";
			echo "<td>Komplikasi, Pencegahan, Prognosis</td>";
			echo "<td align=center>5%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek12\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"aspek12\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 13
		echo "<tr>";
			echo "<td align=center>13</td>";
			echo "<td>Tumbuh kembang (<i>nutrisi, imunisasi, perkembangan</i>)</td>";
			echo "<td align=center>10%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek13\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"aspek13\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 14
		echo "<tr>";
			echo "<td align=center>14</td>";
			echo "<td>Diskusi lain</td>";
			echo "<td align=center>5%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek14\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"aspek14\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//Nilai Rata-Rata
		echo "<tr>";
			echo "<td colspan=3 align=right>Rata-Rata Nilai (Jumlah Bobot x Nilai)</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_rata\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"nilai_rata\" value=\"0\" required/></td>";
		echo "</tr>";
		echo "</table><br>";

		//Umpan Balik
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr>";
			echo "<td>Umpan Balik:<br><textarea name=\"umpan_balik\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" placeholder=\"<< Umpan Balik >>\"></textarea></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>Saran:<br><textarea name=\"saran\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\" placeholder=\"<< Saran >>\"></textarea></td>";
		echo "</tr>";
		echo "</table><br>";
		echo "<br><center><input type=\"submit\" class=\"submit1\" name=\"cancel\" value=\"CANCEL\" formnovalidate>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" class=\"submit1\" name=\"usulkan\" value=\"USULKAN\"></center></form><br><br></fieldset>";

		if ($_POST[cancel]=="CANCEL")
		{
			echo "
				<script>
					window.location.href=\"penilaian_ika.php\";
				</script>
				";
		}

		if ($_POST[usulkan]=="USULKAN")
		{
			$aspek1 = number_format($_POST[aspek1],2);
			$aspek2 = number_format($_POST[aspek2],2);
			$aspek3 = number_format($_POST[aspek3],2);
			$aspek4 = number_format($_POST[aspek4],2);
			$aspek5 = number_format($_POST[aspek5],2);
			$aspek6 = number_format($_POST[aspek6],2);
			$aspek7 = number_format($_POST[aspek7],2);
			$aspek8 = number_format($_POST[aspek8],2);
			$aspek9 = number_format($_POST[aspek9],2);
			$aspek10 = number_format($_POST[aspek10],2);
			$aspek11 = number_format($_POST[aspek11],2);
			$aspek12 = number_format($_POST[aspek12],2);
			$aspek13 = number_format($_POST[aspek13],2);
			$aspek14 = number_format($_POST[aspek14],2);

			$kasus = addslashes($_POST[kasus]);
			$umpan_balik = addslashes($_POST[umpan_balik]);
			$saran = addslashes($_POST[saran]);

			$nilai_rata = 0.05*$_POST[aspek1] + 0.1*$_POST[aspek2] + 0.025*$_POST[aspek3] + 0.05*$_POST[aspek4] + 0.05*$_POST[aspek5] + 0.025*$_POST[aspek6] + 0.1*$_POST[aspek7] + 0.1*$_POST[aspek8] + 0.1*$_POST[aspek9] + 0.1*$_POST[aspek10] + 0.1*$_POST[aspek11] + 0.05*$_POST[aspek12] + 0.1*$_POST[aspek13] + 0.05*$_POST[aspek14];
			$nilai_rata = number_format($nilai_rata,2);

			$insert_kasus=mysqli_query($con,"INSERT INTO `ika_nilai_kasus`
				( `nim`, `tgl_awal`,`tgl_akhir`,`dosen`, `kasus`,
					`aspek_1`, `aspek_2`,`aspek_3`,`aspek_4`,`aspek_5`,
					`aspek_6`, `aspek_7`,`aspek_8`,`aspek_9`,`aspek_10`,
					`aspek_11`, `aspek_12`,`aspek_13`,`aspek_14`,
					`nilai_rata`,`umpan_balik`,`saran`,
					`tgl_isi`,`tgl_penyajian`,`tgl_approval`, `status_approval`)
				VALUES
				( '$_POST[nim]','$_POST[tgl_awal]','$_POST[tgl_akhir]','$_POST[dosen]','$kasus',
					'$aspek1','$aspek2','$aspek3','$aspek4','$aspek5',
					'$aspek6','$aspek7','$aspek8','$aspek9','$aspek10',
					'$aspek11','$aspek12','$aspek13','$aspek14',
					'$nilai_rata','$umpan_balik','$saran',
					'$tgl','$_POST[tgl_penyajian]','2000-01-01','0')");

			echo "
				<script>
					window.location.href=\"penilaian_ika.php\";
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
<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
<script src="../select2/dist/js/select2.js"></script>
<script type="text/javascript" src="../freezeheader/js/jquery.freezeheader.js"></script>
<script>
  $(document).ready(function(){
	   $("#freeze").freezeHeader();
		 $("#freeze1").freezeHeader();
  });
</script>
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
		$('.tgl_penyajian').datepicker({ dateFormat: 'yy-mm-dd' });
		$('.tgl_awal').datepicker({ dateFormat: 'yy-mm-dd' });
		$('.tgl_akhir').datepicker({ dateFormat: 'yy-mm-dd' });
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
			var aspek12 = document.getElementById('aspek12').value;
			var aspek13 = document.getElementById('aspek13').value;
			var aspek14 = document.getElementById('aspek14').value;

			var result = 0.05*parseFloat(aspek1) + 0.1*parseFloat(aspek2) + 0.025*parseFloat(aspek3) + 0.05*parseFloat(aspek4) + 0.05*parseFloat(aspek5) + 0.025*parseFloat(aspek6) + 0.1*parseFloat(aspek7) + 0.1*parseFloat(aspek8) + 0.1*parseFloat(aspek9) + 0.1*parseFloat(aspek10) + 0.1*parseFloat(aspek11) + 0.05*parseFloat(aspek12) + 0.1*parseFloat(aspek13) + 0.05*parseFloat(aspek14);
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
