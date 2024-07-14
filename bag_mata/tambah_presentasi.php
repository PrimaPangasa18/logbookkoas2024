<HTML>
<head>
	<link rel="stylesheet" href="../menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../mytable.css" type="text/css" media="screen" />
	<meta name="viewport" content="width=device-width, maximum-scale=1">
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
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">FORMULIR NILAI PRESENTASI KASUS BESAR<p>Kepaniteraan (Stase) Ilmu Kesehatan Mata</font></h4>";

		$id_stase = "M104";
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
		//Periode Stase
		echo "<tr>";
			$mulai_stase = tanggal_indo($datastase_mhsw[tgl_mulai]);
			$selesai_stase = tanggal_indo($datastase_mhsw[tgl_selesai]);
			echo "<td class=\"td_mid\">Periode Kepaniteraan (Stase)</td>";
			echo "<td class=\"td_mid\">$mulai_stase s.d. $selesai_stase</td>";
		echo "</tr>";
		//Tanggal Penyajian
		echo "<tr>";
			echo "<td class=\"td_mid\">Tanggal Penyajian (yyyy-mm-dd)</td>";
			echo "<td class=\"td_mid\"><input type=\"text\" class=\"tanggal_penyajian\" name=\"tanggal_penyajian\" style=\"font-size:1em;font-family:TAHOMA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" placeholder=\"yyyy-mm-dd\" /></td>";
		echo "</tr>";
		//Judul
		echo "<tr>";
			echo "<td>Judul Presentasi Kasus</td>";
			echo "<td><textarea name=\"judul_presentasi\" style=\"width:97%;font-family:Tahoma;font-size:1em\" placeholder=\"<< Judul Presentasi Kasus >>\" required></textarea></td>";
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
		//1. Cara Penyajian
		echo "<tr>";
			echo "<td align=center>1</td>";
			echo "<td colspan=3>Cara Penyajian:</td>";
		echo "</tr>";
		//No 1.1
		echo "<tr>";
			echo "<td align=center>&nbsp;</td>";
			echo "<td>1.1. Penampilan</td>";
			echo "<td align=center>10%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek1\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"aspek1\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 1.2
		echo "<tr>";
			echo "<td align=center>&nbsp;</td>";
			echo "<td>1.2. Penyampaian</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek2\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"aspek2\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 1.3
		echo "<tr>";
			echo "<td align=center>&nbsp;</td>";
			echo "<td>1.3. Makalah</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek3\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"aspek3\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//2. Penguasaan Materi
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>Penguasaan Materi</td>";
			echo "<td align=center>30%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek4\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"aspek4\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//3. Pengetahuan Teori / Penunjang
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>Pengetahuan Teori / Penunjang</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek5\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"aspek5\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//Nilai Total
		echo "<tr>";
			echo "<td colspan=3 align=right>Nilai Total (Jumlah Bobot x Nilai)</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_total\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"nilai_total\" value=\"0\" required/></td>";
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

		//Nilai Penyanggah
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr><td><b>Penilaian Penyanggah:</b></td></tr>";
		echo "</table>";
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<thead>";
		 	echo "<th style=\"width:5%\">No</th>";
			echo "<th style=\"width:75%\">Nama / NIM Mahasiswa </th>";
			echo "<th style=\"width:20%\">Nilai (0-100)</th>";
		echo "</thead>";
		//Penyanggah 1-5
		$i=1;
		while ($i<6)
		{
			echo "<tr>";
				echo "<td align=center class=\"td_mid\">$i</td>";
				echo "<td class=\"td_mid\">Penyanggah-$i: ";
					$penyanggah = "penyanggah"."$i";
					$nilai = "nilai"."$i";
					echo "<select class=\"select_art\" name=\"$penyanggah\" id=\"$penyanggah\" >";
					$mhsw = mysqli_query($con,"SELECT `nim`,`nama` FROM `biodata_mhsw` ORDER BY `nama`");
					echo "<option value=\"\">< Penyanggah-$i ></option>";
					while ($data_penyanggah=mysqli_fetch_array($mhsw))
					echo "<option value=\"$data_penyanggah[nim]\">$data_penyanggah[nama] ($data_penyanggah[nim])</option>";
					echo "</select>";
				echo "</td>";
				echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"$nilai\" style=\"width:100%;font-size:0.85em;text-align:center\" placeholder=\"0-100\" /></td>";
			echo "</tr>";
			$i++;
		}
		echo "</table><br>";

		echo "<br><center><input type=\"submit\" class=\"submit1\" name=\"cancel\" value=\"CANCEL\" formnovalidate>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" class=\"submit1\" name=\"usulkan\" value=\"USULKAN\"></center></form><br><br></fieldset>";

		if ($_POST[cancel]=="CANCEL")
		{
			echo "
				<script>
					window.location.href=\"penilaian_mata.php\";
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

			if (!empty($_POST[nilai1])) $nilai_penyanggah1 = number_format($_POST[nilai1],2);
			else $nilai_penyanggah1 = "-";
			if (!empty($_POST[nilai2])) $nilai_penyanggah2 = number_format($_POST[nilai2],2);
			else $nilai_penyanggah2 = "-";
			if (!empty($_POST[nilai3])) $nilai_penyanggah3 = number_format($_POST[nilai3],2);
			else $nilai_penyanggah3 = "-";
			if (!empty($_POST[nilai4])) $nilai_penyanggah4 = number_format($_POST[nilai4],2);
			else $nilai_penyanggah4 = "-";
			if (!empty($_POST[nilai5])) $nilai_penyanggah5 = number_format($_POST[nilai5],2);
			else $nilai_penyanggah5 = "-";

			if (!empty($_POST[penyanggah1]) AND $_POST[penyanggah1]!="") $penyanggah1 = $_POST[penyanggah1];
			else {$penyanggah1 = "-";$nilai_penyanggah1 = "-";}
			if (!empty($_POST[penyanggah2]) AND $_POST[penyanggah2]!="") $penyanggah2 = $_POST[penyanggah2];
			else {$penyanggah2 = "-";$nilai_penyanggah2 = "-";}
			if (!empty($_POST[penyanggah3]) AND $_POST[penyanggah3]!="") $penyanggah3 = $_POST[penyanggah3];
			else {$penyanggah3 = "-";$nilai_penyanggah3 = "-";}
			if (!empty($_POST[penyanggah4]) AND $_POST[penyanggah4]!="") $penyanggah4 = $_POST[penyanggah4];
			else {$penyanggah4 = "-";$nilai_penyanggah4 = "-";}
			if (!empty($_POST[penyanggah5]) AND $_POST[penyanggah5]!="") $penyanggah5 = $_POST[penyanggah5];
			else {$penyanggah5 = "-";$nilai_penyanggah5 = "-";}

			$judul = addslashes($_POST[judul_presentasi]);

			$total_nilai = $_POST[aspek1]*0.1 + $_POST[aspek2]*0.2 + $_POST[aspek3]*0.2 + $_POST[aspek4]*0.3 + $_POST[aspek5]*0.2;
			$total = number_format($total_nilai,2);

			$umpan_balik = addslashes($_POST[umpan_balik]);
			$saran = addslashes($_POST[saran]);

			$insert_presentasi=mysqli_query($con,"INSERT INTO `mata_nilai_presentasi`
				( `nim`, `judul_presentasi`, `tgl_penyajian`, `dosen`,
					`aspek_1`, `aspek_2`, `aspek_3`, `aspek_4`, `aspek_5`,`nilai_total`,
					`umpan_balik`,`saran`,
					`penyanggah_1`, `nilai_penyanggah_1`,
					`penyanggah_2`, `nilai_penyanggah_2`,
					`penyanggah_3`, `nilai_penyanggah_3`,
					`penyanggah_4`, `nilai_penyanggah_4`,
					`penyanggah_5`, `nilai_penyanggah_5`,
					`tgl_isi`, `tgl_approval`, `status_approval`)
				VALUES
				( '$_POST[nim]','$judul','$_POST[tanggal_penyajian]','$_POST[dosen]',
					'$aspek1','$aspek2','$aspek3','$aspek4','$aspek5', '$total',
					'$umpan_balik','$saran',
					'$penyanggah1','$nilai_penyanggah1',
					'$penyanggah2','$nilai_penyanggah2',
					'$penyanggah3','$nilai_penyanggah3',
					'$penyanggah4','$nilai_penyanggah4',
					'$penyanggah5','$nilai_penyanggah5',
					'$tgl','2000-01-01','0')");

			echo "
				<script>
					window.location.href=\"penilaian_mata.php\";
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
	$("#penyanggah1").select2({
		 placeholder: "< Penyanggah-1 >",
		 allowClear: true
		});
	$("#penyanggah2").select2({
		 placeholder: "< Penyanggah-2 >",
		 allowClear: true
		});
	$("#penyanggah3").select2({
		 placeholder: "< Penyanggah-3 >",
		 allowClear: true
		});
	$("#penyanggah4").select2({
		 placeholder: "< Penyanggah-4 >",
		 allowClear: true
		});
	$("#penyanggah5").select2({
		 placeholder: "< Penyanggah-5 >",
		 allowClear: true
		});

 });
</script>
<script type="text/javascript" src="../jquery_ui/jquery-ui.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.tanggal_penyajian').datepicker({ dateFormat: 'yy-mm-dd' });
	});
</script>

<script>
function sum() {
      var aspek1 = document.getElementById('aspek1').value;
			var aspek2 = document.getElementById('aspek2').value;
			var aspek3 = document.getElementById('aspek3').value;
			var aspek4 = document.getElementById('aspek4').value;
			var aspek5 = document.getElementById('aspek5').value;
      var result = 0.1*parseFloat(aspek1) + 0.2*parseFloat(aspek2) + 0.2*parseFloat(aspek3) + 0.3*parseFloat(aspek4) + 0.2*parseFloat(aspek5);
      if (!isNaN(result)) {
         document.getElementById('nilai_total').value = number_format(result,2);
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
