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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND $_COOKIE['level']==4)
	{
		if ($_COOKIE['level']==4) {include "menu4.php";}

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) ILMU KESEHATAN THT-KL</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">APPROVAL NILAI RESPONSI KASUS KECIL<p>Kepaniteraan (Stase) Ilmu Kesehatan THT-KL</font></h4>";

		$id_stase = "M105";
		$id = $_GET['id'];
		$data_responsi = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `thtkl_nilai_responsi` WHERE `id`='$id'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_responsi[nim]'"));

		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		$tgl_mulai = $_GET[mulai];
		$tgl_selesai = $_GET[selesai];
		$approval = $_GET[approval];
		$mhsw = $_GET[mhsw];
		echo "<input type=\"hidden\" name=\"tgl_mulai\" value=\"$tgl_mulai\" />";
		echo "<input type=\"hidden\" name=\"tgl_selesai\" value=\"$tgl_selesai\" />";
		echo "<input type=\"hidden\" name=\"approval\" value=\"$approval\" />";
		echo "<input type=\"hidden\" name=\"mhsw\" value=\"$mhsw\" />";

		echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";

		//Nama Mahasiswa
		echo "<tr>";
			echo "<td>Nama Mahasiswa</td>";
			echo "<td>$data_mhsw[nama]</td>";
		echo "</tr>";
		//NIM
		echo "<tr>";
			echo "<td>NIM</td>";
			echo "<td>$data_mhsw[nim]</td>";
		echo "</tr>";
		//Responsi Kasus Ke-
		echo "<tr>";
			echo "<td>Responsi Kasus Ke-</td>";
			echo "<td>$data_responsi[kasus_ke]</td>";
		echo "</tr>";
		//Dosen Pembimbing/Penguji
		echo "<tr>";
			echo "<td>Dosen Pembimbing/Penguji</td>";
			echo "<td>";
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_responsi[dosen]'"));
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
			echo "<th style=\"width:55%\">Komponen Penilaian</th>";
			echo "<th style=\"width:20%\">Bobot</th>";
			echo "<th style=\"width:20%\">Nilai (0-100)</th>";
		echo "</thead>";
		//No 1
		echo "<tr>";
			echo "<td align=center>1</td>";
			echo "<td>Anamnesis</td>";
			echo "<td align=center>15%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek1\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_responsi[aspek_1]\" id=\"aspek1\" onkeyup=\"sum();\" onchange=\"sum();\"  required/></td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>Pemeriksaan Fisik</td>";
			echo "<td align=center>15%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek2\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_responsi[aspek_2]\" id=\"aspek2\" onkeyup=\"sum();\" onchange=\"sum();\"  required/></td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>Pemeriksaan Penunjang</td>";
			echo "<td align=center>15%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek3\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_responsi[aspek_3]\" id=\"aspek3\" onkeyup=\"sum();\" onchange=\"sum();\"  required/></td>";
		echo "</tr>";
		//No 4
		echo "<tr>";
			echo "<td align=center>4</td>";
			echo "<td>Diagnosis dan Diagnosis Banding</td>";
			echo "<td align=center>15%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek4\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_responsi[aspek_4]\" id=\"aspek4\" onkeyup=\"sum();\" onchange=\"sum();\"  required/></td>";
		echo "</tr>";
		//No 5
		echo "<tr>";
			echo "<td align=center>5</td>";
			echo "<td>Penatalaksanaan (farmakoterapi atau/dan non farmakoterapi)</td>";
			echo "<td align=center>15%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek5\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_responsi[aspek_5]\" id=\"aspek5\" onkeyup=\"sum();\" onchange=\"sum();\"  required/></td>";
		echo "</tr>";
		//No 6
		echo "<tr>";
			echo "<td align=center>6</td>";
			echo "<td>Komunikasi, Informasi dan Edukasi kepada Pasien atau/dan keluarga</td>";
			echo "<td align=center>10%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek6\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_responsi[aspek_6]\" id=\"aspek6\" onkeyup=\"sum();\" onchange=\"sum();\"  required/></td>";
		echo "</tr>";
		//No 7
		echo "<tr>";
			echo "<td align=center>7</td>";
			echo "<td>Profesionalitas</td>";
			echo "<td align=center>15%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek7\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_responsi[aspek_7]\" id=\"aspek7\" onkeyup=\"sum();\" onchange=\"sum();\"  required/></td>";
		echo "</tr>";
		//Nilai Rata-Rata
		echo "<tr>";
			echo "<td colspan=3 align=right>Rata-Rata Nilai (Jumlah Bobot x Nilai)</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_rata\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_responsi[nilai_rata]\" id=\"nilai_rata\" required/></td>";
		echo "</tr>";
		echo "</table><br>";

		//Umpan Balik
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr>";
		  echo "<td>Umpan Balik:<br><textarea name=\"umpan_balik\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\">$data_responsi[umpan_balik]</textarea></td>";
		echo "</tr>";
		echo "<tr>";
		  echo "<td>Saran:<br><textarea name=\"saran\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\">$data_responsi[saran]</textarea></td>";
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
				window.location.href=\"penilaian_thtkl_dosen.php?mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\";
			</script>
			";
		}

		if ($_POST[approve]=="APPROVE")
		{
			$tgl_mulai=$_POST[tgl_mulai];
			$tgl_selesai=$_POST[tgl_selesai];
			$approval=$_POST[approval];
			$mhsw=$_POST[mhsw];
			$aspek1 = number_format($_POST[aspek1],2);
			$aspek2 = number_format($_POST[aspek2],2);
			$aspek3 = number_format($_POST[aspek3],2);
			$aspek4 = number_format($_POST[aspek4],2);
			$aspek5 = number_format($_POST[aspek5],2);
			$aspek6 = number_format($_POST[aspek6],2);
			$aspek7 = number_format($_POST[aspek7],2);
			$nilai_rata = 0.15*$_POST[aspek1] + 0.15*$_POST[aspek2] + 0.15*$_POST[aspek3] + 0.15*$_POST[aspek4] + 0.15*$_POST[aspek5] + 0.1*$_POST[aspek6] + 0.15*$_POST[aspek7];
			$nilai_rata = number_format($nilai_rata,2);

			$umpan_balik = addslashes($_POST[umpan_balik]);
			$saran = addslashes($_POST[saran]);

			$update_responsi=mysqli_query($con,"UPDATE `thtkl_nilai_responsi` SET
				`aspek_1`='$aspek1',
				`aspek_2`='$aspek2',
				`aspek_3`='$aspek3',
				`aspek_4`='$aspek4',
				`aspek_5`='$aspek5',
				`aspek_6`='$aspek6',
				`aspek_7`='$aspek7',
				`nilai_rata`='$nilai_rata',
				`umpan_balik`='$umpan_balik',`saran`='$saran',
				`tgl_approval`='$tgl',`status_approval`='1'
				WHERE `id`='$_POST[id]'");

			echo "
				<script>
				window.location.href = \"penilaian_thtkl_dosen.php?mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\";
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

<script>
function sum() {
      var aspek1 = document.getElementById('aspek1').value;
			var aspek2 = document.getElementById('aspek2').value;
			var aspek3 = document.getElementById('aspek3').value;
			var aspek4 = document.getElementById('aspek4').value;
			var aspek5 = document.getElementById('aspek5').value;
			var aspek6 = document.getElementById('aspek6').value;
			var aspek7 = document.getElementById('aspek7').value;
			var result = 0.15*parseFloat(aspek1) + 0.15*parseFloat(aspek2) + 0.15*parseFloat(aspek3) + 0.15*parseFloat(aspek4) + 0.15*parseFloat(aspek5) + 0.1*parseFloat(aspek6) + 0.15*parseFloat(aspek7);
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
