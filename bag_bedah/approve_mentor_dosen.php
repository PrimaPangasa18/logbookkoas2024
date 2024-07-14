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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) ILMU BEDAH</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">APPROVAL NILAI MENTORING<p>KEPANITERAAN (STASE) ILMU BEDAH</font></h4>";

		$id = $_GET[id];
		$data_mentor = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `bedah_nilai_mentor` WHERE `id`='$id'"));
		$id_stase = "M101";
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_mentor[nim]'"));
		$stase_id = "stase_".$id_stase;
		$data_stase_mhsw = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$data_mentor[nim]'");
		$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);


		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		echo "<input type=\"hidden\" name=\"id_stase\" value=\"$id_stase\">";
		echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
		echo "<table border=1 style=\"width:75%;background:rgb(244, 241, 217);\">";
		$tgl_mulai = $_GET[mulai];
		$tgl_selesai = $_GET[selesai];
		$approval = $_GET[approval];
		$mhsw = $_GET[mhsw];
		echo "<input type=\"hidden\" name=\"tgl_mulai\" value=\"$tgl_mulai\" />";
		echo "<input type=\"hidden\" name=\"tgl_selesai\" value=\"$tgl_selesai\" />";
		echo "<input type=\"hidden\" name=\"approval\" value=\"$approval\" />";
		echo "<input type=\"hidden\" name=\"mhsw\" value=\"$mhsw\" />";

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
		//Mentoring Bulan ke-
		echo "<tr>";
			echo "<td>Mentoring Bulan ke-</td>";
			echo "<td>";
			if ($data_mentor[bulan_ke]=="1")
			echo "<input type=\"radio\" name=\"bulan_ke\" value=\"1\" checked/>&nbsp;&nbsp;1 (<i>Bulan ke-1</i>)<br>
						<input type=\"radio\" name=\"bulan_ke\" value=\"2\" />&nbsp;&nbsp;2 (<i>Bulan ke-2</i>)";
			if ($data_mentor[bulan_ke]=="2")
			echo "<input type=\"radio\" name=\"bulan_ke\" value=\"1\" />&nbsp;&nbsp;1 (<i>Bulan ke-1</i>)<br>
						<input type=\"radio\" name=\"bulan_ke\" value=\"2\" checked/>&nbsp;&nbsp;2 (<i>Bulan ke-2</i>)";
			echo "</td>";
		echo "</tr>";
		//Periode Kegiatan
		echo "<tr>";
			echo "<td class=\"td_mid\">Periode Penilaian (yyyy-mm-dd)</td>";
			echo "<td class=\"td_mid\"><input type=\"text\" class=\"tgl_awal\" name=\"tgl_awal\" style=\"font-size:1em;font-family:TAHOMA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" value=\"$data_mentor[tgl_awal]\" />";
			echo " s.d. <input type=\"text\" class=\"tgl_akhir\" name=\"tgl_akhir\" style=\"font-size:1em;font-family:TAHOMA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" value=\"$data_mentor[tgl_akhir]\" /></td>";
		echo "</tr>";
		//Dosen Penilai (Mentor)
		echo "<tr>";
			echo "<td>Dosen Penilai (Mentor)</td>";
			echo "<td>";
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_mentor[dosen]'"));
			echo "$data_dosen[nama], $data_dosen[gelar] ($data_dosen[nip])";
			echo "</td>";
		echo "</tr>";
		echo "</table><br><br>";

		//Form nilai
		echo "<table border=1 style=\"width:75%;background:rgb(244, 241, 217);\">";
		echo "<tr><td><b>Form Penilaian:</b></td></tr>";
		echo "</table>";
		echo "<table border=1 style=\"width:75%;background:rgb(244, 241, 217);\">";
		echo "<thead>";
			echo "<th style=\"width:5%\">No</th>";
			echo "<th style=\"width:75%\">Aspek Yang Dinilai</th>";
			echo "<th style=\"width:20%\">Nilai (0-100)</th>";
		echo "</thead>";
		//No 1
		echo "<tr>";
			echo "<td align=center>1</td>";
			echo "<td>Persentase Kehadiran</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_1\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"aspek_1\" value=\"$data_mentor[aspek_1]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>Pengetahuan Bedah</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_2\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"aspek_2\" value=\"$data_mentor[aspek_2]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>Keaktifan</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_3\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"aspek_3\" value=\"$data_mentor[aspek_3]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 4
		echo "<tr>";
			echo "<td align=center>4</td>";
			echo "<td>Keterampilan</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_4\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"aspek_4\" value=\"$data_mentor[aspek_4]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 5
		echo "<tr>";
			echo "<td align=center>5</td>";
			echo "<td>Kerjasama</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_5\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"aspek_5\" value=\"$data_mentor[aspek_5]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 6
		echo "<tr>";
			echo "<td align=center>6</td>";
			echo "<td>Sikap Kesopanan</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_6\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"aspek_6\" value=\"$data_mentor[aspek_6]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//Rata-Rata Nilai
		echo "<tr>";
			echo "<td colspan=2 align=right>Rata-Rata Nilai</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_rata\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"nilai_rata\" value=\"$data_mentor[nilai_rata]\" required/></td>";
		echo "</tr>";
		echo "</table><br>";

		echo "<table border=1 style=\"width:75%;background:rgb(244, 241, 217);\">";
		//Kasus Ilmiah / Problem Pasien
		echo "<tr>";
			echo "<td>Kasus Ilmiah / Problem Pasien yang Pernah Didiskusikan:<br><textarea name=\"kasus\" rows=10 style=\"width:100%;font-family:Tahoma;font-size:1em\">$data_mentor[kasus]</textarea></td>";
		echo "</tr>";
		//Umpan Balik
		echo "<tr>";
			echo "<td>Umpan Balik:<br><textarea name=\"umpan_balik\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\">$data_mentor[umpan_balik]</textarea></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>Saran:<br><textarea name=\"saran\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\">$data_mentor[saran]</textarea></td>";
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
				window.location.href=\"penilaian_bedah_dosen.php?mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\";
			</script>
			";
		}

		if ($_POST[approve]=="APPROVE")
		{
			$tgl_mulai=$_POST[tgl_mulai];
			$tgl_selesai=$_POST[tgl_selesai];
			$approval=$_POST[approval];
			$mhsw=$_POST[mhsw];

			$aspek_1 = number_format($_POST[aspek_1],2);
			$aspek_2 = number_format($_POST[aspek_2],2);
			$aspek_3 = number_format($_POST[aspek_3],2);
			$aspek_4 = number_format($_POST[aspek_4],2);
			$aspek_5 = number_format($_POST[aspek_5],2);
			$aspek_6 = number_format($_POST[aspek_6],2);

			$umpan_balik = addslashes($_POST[umpan_balik]);
			$saran = addslashes($_POST[saran]);
			$kasus = addslashes($_POST[kasus]);

			$nilai_rata = ($_POST[aspek_1] + $_POST[aspek_2] + $_POST[aspek_3] + $_POST[aspek_4] + $_POST[aspek_5] + $_POST[aspek_6])/6;
			$nilai_rata = number_format($nilai_rata,2);

			$update_mentor = mysqli_query($con,"UPDATE `bedah_nilai_mentor` SET
				`tgl_awal`='$_POST[tgl_awal]',`tgl_akhir`='$_POST[tgl_akhir]',
				`bulan_ke`='$_POST[bulan_ke]',
				`aspek_1`='$aspek_1',`aspek_2`='$aspek_2',
				`aspek_3`='$aspek_3',`aspek_4`='$aspek_4',
				`aspek_5`='$aspek_5',`aspek_6`='$aspek_6',
				`nilai_rata`='$nilai_rata',
				`umpan_balik`='$umpan_balik',`saran`='$saran',`kasus`='$kasus',
				`tgl_approval`='$tgl',`status_approval`='1'
				WHERE `id`='$_POST[id]'");

			echo "
				<script>
				window.location.href = \"penilaian_bedah_dosen.php?mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\";
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
		$('.tgl_awal').datepicker({ dateFormat: 'yy-mm-dd' });
		$('.tgl_akhir').datepicker({ dateFormat: 'yy-mm-dd' });
	});
</script>
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
		 placeholder: "<< Dosen Penilai (Mentor) >>",
		 allowClear: true
	 });
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
	var result = (parseFloat(aspek1) + parseFloat(aspek2) + parseFloat(aspek3) + parseFloat(aspek4) + parseFloat(aspek5) + parseFloat(aspek6))/6;
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
