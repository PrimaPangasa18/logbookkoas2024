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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) IKGM</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">APPROVAL NILAI JOURNAL READING<p>Kepaniteraan (Stase) IKGM</font></h4>";

		$id_stase = "M106";
		$id = $_GET['id'];
		$data_jurnal = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `ikgm_nilai_jurnal` WHERE `id`='$id'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_jurnal[nim]'"));
		$stase_id = "stase_".$id_stase;
		$data_stase_mhsw = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$data_jurnal[nim]'");
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
		//Periode Stase
		echo "<tr>";
			$mulai_stase = tanggal_indo($datastase_mhsw[tgl_mulai]);
			$selesai_stase = tanggal_indo($datastase_mhsw[tgl_selesai]);
			echo "<td class=\"td_mid\">Periode Kepaniteraan (Stase)</td>";
			echo "<td class=\"td_mid\">$mulai_stase s.d. $selesai_stase</td>";
		echo "</tr>";
		//Tanggal Ujian/Presentasi
		echo "<tr>";
			echo "<td class=\"td_mid\">Tanggal Ujian/Presentasi (yyyy-mm-dd)</td>";
			echo "<td class=\"td_mid\"><input type=\"text\" class=\"tanggal_ujian\" name=\"tanggal_ujian\" style=\"font-size:1em;font-family:TAHOMA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" value=\"$data_jurnal[tgl_ujian]\" /></td>";
		echo "</tr>";
		//Dosen Penguji (Tutor)
		echo "<tr>";
			echo "<td>Dosen Penguji (Tutor)</td>";
			echo "<td>";
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_jurnal[dosen]'"));
			echo "$data_dosen[nama], $data_dosen[gelar] ($data_dosen[nip])";
			echo "</td>";
		echo "</tr>";
		//Nama Jurnal
		echo "<tr>";
			echo "<td>Nama Jurnal</td>";
			echo "<td><textarea name=\"nama_jurnal\" style=\"width:97%;font-family:Tahoma;font-size:1em\" required>$data_jurnal[nama_jurnal]</textarea></td>";
		echo "</tr>";
		//Judul Artikel
		echo "<tr>";
			echo "<td>Judul Artikel</td>";
			echo "<td><textarea name=\"judul_paper\" style=\"width:97%;font-family:Tahoma;font-size:1em\" required>$data_jurnal[judul_paper]</textarea></td>";
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
			echo "<th style=\"width:40%\">Penilaian</th>";
		echo "</thead>";
		//No 1
		echo "<tr>";
			echo "<td align=center>1</td>";
			echo "<td>Kehadiran</td>";
			echo "<td>";
			if ($data_jurnal[aspek_1]=="0")
				echo "<input type=\"radio\" name=\"aspek_1\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;0 - Tidak Hadir<br>";
			else echo "<input type=\"radio\" name=\"aspek_1\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;0 - Tidak Hadir<br>";
			if ($data_jurnal[aspek_1]=="2")
				echo "<input type=\"radio\" name=\"aspek_1\" value=\"2\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;2 - Hadir, terlambat 11 – 15 menit<br>";
			else echo "<input type=\"radio\" name=\"aspek_1\" value=\"2\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;2 - Hadir, terlambat 11 – 15 menit<br>";
			if ($data_jurnal[aspek_1]=="3")
				echo "<input type=\"radio\" name=\"aspek_1\" value=\"3\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;3 - Hadir, terlambat ≤ 10 menit<br>";
			else echo "<input type=\"radio\" name=\"aspek_1\" value=\"3\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;3 - Hadir, terlambat ≤ 10 menit<br>";
			if ($data_jurnal[aspek_1]=="4")
				echo "<input type=\"radio\" name=\"aspek_1\" value=\"4\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;4 - Hadir tepat waktu";
			else echo "<input type=\"radio\" name=\"aspek_1\" value=\"4\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;4 - Hadir tepat waktu";
			echo "</td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>Tugas terjemahan, slide presentasi dan telaah jurnal</td>";
			echo "<td>";
			if ($data_jurnal[aspek_2]=="1")
				echo "<input type=\"radio\" name=\"aspek_2\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;1 - Tidak membuat<br>";
			else echo "<input type=\"radio\" name=\"aspek_2\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;1 - Tidak membuat<br>";
			if ($data_jurnal[aspek_2]=="2")
				echo "<input type=\"radio\" name=\"aspek_2\" value=\"2\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;2 - Kurang lengkap<br>";
			else echo "<input type=\"radio\" name=\"aspek_2\" value=\"2\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;2 - Kurang lengkap<br>";
			if ($data_jurnal[aspek_2]=="3")
				echo "<input type=\"radio\" name=\"aspek_2\" value=\"3\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;3 - Cukup lengkap<br>";
			else echo "<input type=\"radio\" name=\"aspek_2\" value=\"3\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;3 - Cukup lengkap<br>";
			if ($data_jurnal[aspek_2]=="4")
				echo "<input type=\"radio\" name=\"aspek_2\" value=\"4\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;4 - Lengkap";
			else echo "<input type=\"radio\" name=\"aspek_2\" value=\"4\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;4 - Lengkap";
			echo "</td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>Aktifitas saat diskusi<br>(<i>Dinilai dari frekuensi mengajukan masukan / komentar / pendapat / jawaban</i>)</td>";
			echo "<td>";
			if ($data_jurnal[aspek_3]=="1")
			  echo "<input type=\"radio\" name=\"aspek_3\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;1 - Pasif<br>";
			else echo "<input type=\"radio\" name=\"aspek_3\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;1 - Pasif<br>";
			if ($data_jurnal[aspek_3]=="2")
			  echo "<input type=\"radio\" name=\"aspek_3\" value=\"2\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;2 - Kurang aktif<br>";
			else echo "<input type=\"radio\" name=\"aspek_3\" value=\"2\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;2 - Kurang aktif<br>";
			if ($data_jurnal[aspek_3]=="3")
			  echo "<input type=\"radio\" name=\"aspek_3\" value=\"3\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;3 - Cukup aktif<br>";
			else echo "<input type=\"radio\" name=\"aspek_3\" value=\"3\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;3 - Cukup aktif<br>";
			if ($data_jurnal[aspek_3]=="4")
			  echo "<input type=\"radio\" name=\"aspek_3\" value=\"4\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;4 - Sangat aktif";
			else echo "<input type=\"radio\" name=\"aspek_3\" value=\"4\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;4 - Sangat aktif";
			echo "</td>";
		echo "</tr>";
		//No 4
		echo "<tr>";
			echo "<td align=center>4</td>";
			echo "<td>Relevansi pembicaraan<br>(<i>Dinilai dari relevansi dan penguasaaan terhadap materi diskusi</i>)</td>";
			echo "<td>";
			if ($data_jurnal[aspek_4]=="1")
			  echo "<input type=\"radio\" name=\"aspek_4\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;1 - Pembicaraan selalu tidak relevan<br>";
			else echo "<input type=\"radio\" name=\"aspek_4\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;1 - Pembicaraan selalu tidak relevan<br>";
			if ($data_jurnal[aspek_4]=="2")
			  echo "<input type=\"radio\" name=\"aspek_4\" value=\"2\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;2 - Pembicaraan sering tidak relevan<br>";
			else echo "<input type=\"radio\" name=\"aspek_4\" value=\"2\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;2 - Pembicaraan sering tidak relevan<br>";
			if ($data_jurnal[aspek_4]=="3")
			  echo "<input type=\"radio\" name=\"aspek_4\" value=\"3\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;3 - Pembicaraan cukup relevan<br>";
			else echo "<input type=\"radio\" name=\"aspek_4\" value=\"3\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;3 - Pembicaraan cukup relevan<br>";
			if ($data_jurnal[aspek_4]=="4")
			  echo "<input type=\"radio\" name=\"aspek_4\" value=\"4\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;4 - Pembicaraan selalu relevan";
			else echo "<input type=\"radio\" name=\"aspek_4\" value=\"4\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;4 - Pembicaraan selalu relevan";
			echo "</td>";
		echo "</tr>";
		//No 5
		echo "<tr>";
			echo "<td align=center>5</td>";
			echo "<td>Keterampilan presentasi/berkomunikasi<br>(<i>Dinilai dari kemampuan berinteraksi dengan peserta lain.
								Tunjuk jari bila mau menyampaikan pendapat / bertanya;
								memperhatikan saat peserta lain berbicara;
								tidak emosional / tidak memotong pembicaraan orang lain / tidak mendominasi diskusi.</i>)</td>";
			echo "<td>";
			if ($data_jurnal[aspek_5]=="1")
			  echo "<input type=\"radio\" name=\"aspek_5\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;1 - Kurang<br>";
			else echo "<input type=\"radio\" name=\"aspek_5\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;1 - Kurang<br>";
			if ($data_jurnal[aspek_5]=="2")
			  echo "<input type=\"radio\" name=\"aspek_5\" value=\"2\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;2 - Cukup<br>";
			else echo "<input type=\"radio\" name=\"aspek_5\" value=\"2\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;2 - Cukup<br>";
			if ($data_jurnal[aspek_5]=="3")
			  echo "<input type=\"radio\" name=\"aspek_5\" value=\"3\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;3 - Baik<br>";
			else echo "<input type=\"radio\" name=\"aspek_5\" value=\"3\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;3 - Baik<br>";
			if ($data_jurnal[aspek_5]=="4")
			  echo "<input type=\"radio\" name=\"aspek_5\" value=\"4\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;4 - Sangat baik";
			else echo "<input type=\"radio\" name=\"aspek_5\" value=\"4\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;4 - Sangat baik";
			echo "</td>";
		echo "</tr>";
		//Total Nilai
		echo "<tr>";
			echo "<td colspan=2 align=right>Total Nilai (10 x Jumlah Poin / 2)</td>";
			echo "<td><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_total\" style=\"width:30%;font-size:0.85em;text-align:center\" value=\"$data_jurnal[nilai_total]\" id=\"nilai_total\" required/></td>";
		echo "</tr>";
		echo "</table><br><br>";

		//Catatan
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr><td><b>Catatan Dosen Penguji (Tutor) Terhadap Kegiatan:</b></td></tr>";
		echo "<tr>";
			echo "<td>Catatan:<br><textarea name=\"catatan\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\">$data_jurnal[catatan]</textarea></td>";
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
				window.location.href=\"penilaian_ikgm_dosen.php?mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\";
			</script>
			";
		}

		if ($_POST[approve]=="APPROVE")
		{
			$tgl_mulai=$_POST[tgl_mulai];
			$tgl_selesai=$_POST[tgl_selesai];
			$approval=$_POST[approval];
			$mhsw=$_POST[mhsw];
			$aspek1 = number_format($_POST[aspek_1]);
			$aspek2 = number_format($_POST[aspek_2]);
			$aspek3 = number_format($_POST[aspek_3]);
			$aspek4 = number_format($_POST[aspek_4]);
			$aspek5 = number_format($_POST[aspek_5]);

			$nama_jurnal = addslashes($_POST[nama_jurnal]);
			$judul_paper = addslashes($_POST[judul_paper]);
			$catatan = addslashes($_POST[catatan]);

			$nilai_total = ($_POST[aspek_1]+$_POST[aspek_2]+$_POST[aspek_3]+$_POST[aspek_4]+$_POST[aspek_5])/2;
			$nilai_total = number_format(10*$nilai_total,2);

			$update_jurnal=mysqli_query($con,"UPDATE `ikgm_nilai_jurnal` SET
				`tgl_ujian`='$_POST[tanggal_ujian]',
				`nama_jurnal`='$nama_jurnal',`judul_paper`='$judul_paper',
				`aspek_1`='$aspek1',`aspek_2`='$aspek2',
				`aspek_3`='$aspek3',`aspek_4`='$aspek4',
				`aspek_5`='$aspek5',`catatan`='$catatan',
				`nilai_total`='$nilai_total',`tgl_approval`='$tgl',`status_approval`='1'
				WHERE `id`='$_POST[id]'");

			echo "
				<script>
				window.location.href = \"penilaian_ikgm_dosen.php?mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\";
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
      var aspek1 = $("input[name=aspek_1]:checked").val();
			var aspek2 = $("input[name=aspek_2]:checked").val();
			var aspek3 = $("input[name=aspek_3]:checked").val();
			var aspek4 = $("input[name=aspek_4]:checked").val();
			var aspek5 = $("input[name=aspek_5]:checked").val();

			var total = parseInt(aspek1) + parseInt(aspek2) + parseInt(aspek3) + parseInt(aspek4) + parseInt(aspek5);
			var result = 10*parseInt(total)/2;

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
