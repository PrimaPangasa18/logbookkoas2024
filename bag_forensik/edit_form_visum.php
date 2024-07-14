<HTML>
<head>
	<link rel="stylesheet" href="../menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../mytable.css" type="text/css" media="screen" />
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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) KEDOKTERAN FORENSIK DAN MEDIKOLEGAL</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">EDIT FORMULIR PENILAIAN VISUM BAYANGAN<p>KEPANITERAAN (STASE) KEDOKTERAN FORENSIK DAN MEDIKOLEGAL</font></h4>";

		$id_stase = "M112";
		$id = $_GET['id'];
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
		$stase_id = "stase_".$id_stase;
		$data_stase_mhsw = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'");
		$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);
		$data_visum = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `forensik_nilai_visum` WHERE `id`='$id'"));

		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
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
		//Dosen Penilai Ke-
		echo "<tr>";
			echo "<td>Dosen Penilai Ke-</td>";
			echo "<td>";
			echo "<select class=\"select_short\" name=\"dosen_ke\" id=\"dosen_ke\" required>";
			echo "<option value=\"$data_visum[dosen_ke]\">$data_visum[dosen_ke]</option>";
			echo "<option value=\"1\">1</option>";
			echo "<option value=\"2\">2</option>";
			echo "<option value=\"3\">3</option>";
			echo "<option value=\"4\">4</option>";
			echo "</td>";
		echo "</tr>";
		//Dosen Penilai
		echo "<tr>";
			echo "<td>Dosen Penilai</td>";
			echo "<td>";
			echo "<select class=\"select_art\" name=\"dosen\" id=\"dosen\" required>";
			$data_dosen_isian = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_visum[dosen]'"));
			echo "<option value=\"$data_dosen_isian[nip]\">$data_dosen_isian[nama], $data_dosen_isian[gelar] ($data_dosen_isian[nip])</option>";
			$dosen = mysqli_query($con,"SELECT `username`,`nama` FROM `admin` WHERE `level`='4' OR (`level`='6' AND `stase`='$id_stase') ORDER BY `nama`");
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
		//No 1
		echo "<tr>";
			echo "<td align=center>1</td>";
			echo "<td>Kehadiran:<br>";
			echo "<font style=\"font-size:0.75em\"><i>";
			echo "-	Tepat waktu = 100<br>
						-	Terlambat < 5 menit = 90<br>
						-	Terlambat 5 – 10 menit = 80<br>
						-	Terlambat 10 – 15 menit = 70<br>
						-	Terlambat > 15 menit = 50<br>
						-	Tidak hadir   = 0
						";
			echo "</i></font></td>";
			echo "<td align=center>30%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek1\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"aspek1\" value=\"$data_visum[aspek_1]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>Keaktifan bertanya</td>";
			echo "<td align=center>30%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek2\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"aspek2\" value=\"$data_visum[aspek_2]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>Keaktifan menjawab dengan benar</td>";
			echo "<td align=center>40%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek3\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"aspek3\" value=\"$data_visum[aspek_3]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//Nilai Rata-Rata
		echo "<tr>";
			echo "<td colspan=3 align=right>Rata-Rata Nilai (Jumlah Bobot x Nilai)</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_rata\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"nilai_rata\" value=\"$data_visum[nilai_rata]\" required/></td>";
		echo "</tr>";
		//Catatan
		echo "<tr>";
			echo "<td colspan=4><b>Umpan Balik:</b><br><br>";
			echo "Catatan:<br>";
			echo "<textarea name=\"catatan\" style=\"width:100%;font-family:Tahoma;font-size:1em\" rows=5 >$data_visum[catatan]</textarea><br><br>";
			echo "Saran:<br>";
			echo "<textarea name=\"saran\" style=\"width:100%;font-family:Tahoma;font-size:1em\" rows=5 >$data_visum[saran]</textarea>";
			echo "</td>";
		echo "</tr>";
		echo "</table><br>";
		echo "<br><center><input type=\"submit\" class=\"submit1\" name=\"cancel\" value=\"CANCEL\" formnovalidate>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" class=\"submit1\" name=\"ubah\" value=\"UBAH\"></center></form><br><br></fieldset>";

		if ($_POST[cancel]=="CANCEL")
		{
			echo "
				<script>
					window.location.href=\"penilaian_forensik.php\";
				</script>
				";
		}

		if ($_POST[ubah]=="UBAH")
		{
			$aspek1 = number_format($_POST[aspek1],2);
			$aspek2 = number_format($_POST[aspek2],2);
			$aspek3 = number_format($_POST[aspek3],2);
			$catatan = addslashes($_POST[catatan]);
			$saran = addslashes($_POST[saran]);

			$nilai_rata = 0.3*$_POST[aspek1] + 0.3*$_POST[aspek2] + 0.4*$_POST[aspek3];
			$nilai_rata = number_format($nilai_rata,2);

			$update_visum=mysqli_query($con,"UPDATE `forensik_nilai_visum` SET
				`dosen`='$_POST[dosen]',`dosen_ke`='$_POST[dosen_ke]',
				`aspek_1`='$aspek1',`aspek_2`='$aspek2',
				`aspek_3`='$aspek3',`nilai_rata`='$nilai_rata',
				`catatan`='$catatan',`saran`='$saran',
				`tgl_isi`='$tgl'
				WHERE `id`='$_POST[id]'");

			echo "
				<script>
					window.location.href=\"penilaian_forensik.php\";
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
	 $("#dosen_ke").select2({
 		 placeholder: "",
      allowClear: true
 	 });
 });
</script>

<script>
function sum() {
	var aspek1 = document.getElementById('aspek1').value;
	var aspek2 = document.getElementById('aspek2').value;
	var aspek3 = document.getElementById('aspek3').value;
	var result = 0.3*parseFloat(aspek1) + 0.3*parseFloat(aspek2) + 0.4*parseFloat(aspek3);
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
