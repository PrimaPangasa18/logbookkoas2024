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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) IKGM</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">FORMULIR PENILAIAN JOURNAL READING<p>Kepaniteraan (Stase) IKGM</font></h4>";

		$id_stase = "M106";
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
			echo "<td class=\"td_mid\"><input type=\"text\" class=\"tanggal_ujian\" name=\"tanggal_ujian\" style=\"font-size:1em;font-family:TAHOMA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" placeholder=\"yyyy-mm-dd\" /></td>";
		echo "</tr>";
		//Dosen Penguji (Tutor)
		echo "<tr>";
			echo "<td>Dosen Penguji (Tutor)</td>";
			echo "<td>";
			echo "<select class=\"select_art\" name=\"dosen\" id=\"dosen\" required>";
			$dosen = mysqli_query($con,"SELECT `username`,`nama` FROM `admin` WHERE `level`='4' OR (`level`='6' AND `stase`='$id_stase') ORDER BY `nama`");
			echo "<option value=\"\"><< Dosen Penguji (Tutor) >></option>";
			while ($data=mysqli_fetch_array($dosen))
			{
				$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data[username]'"));
				echo "<option value=\"$data[username]\">$data_dosen[nama], $data_dosen[gelar] ($data_dosen[nip])</option>";
			}
			echo "</select>";
			echo "</td>";
		echo "</tr>";
		//Nama Jurnal
		echo "<tr>";
			echo "<td>Nama Jurnal</td>";
			echo "<td><textarea name=\"nama_jurnal\" style=\"width:97%;font-family:Tahoma;font-size:1em\" placeholder=\"<< Nama Jurnal >>\" required></textarea></td>";
		echo "</tr>";
		//Judul Artikel
		echo "<tr>";
			echo "<td>Judul Artikel</td>";
			echo "<td><textarea name=\"judul_paper\" style=\"width:97%;font-family:Tahoma;font-size:1em\" placeholder=\"<< Judul Artikel >>\" required></textarea></td>";
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
			echo "<input type=\"radio\" name=\"aspek_1\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;0 - Tidak Hadir<br>
						<input type=\"radio\" name=\"aspek_1\" value=\"2\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;2 - Hadir, terlambat 11 – 15 menit<br>
						<input type=\"radio\" name=\"aspek_1\" value=\"3\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;3 - Hadir, terlambat ≤ 10 menit<br>
						<input type=\"radio\" name=\"aspek_1\" value=\"4\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;4 - Hadir tepat waktu";
			echo "</td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>Tugas terjemahan, slide presentasi dan telaah jurnal</td>";
			echo "<td>";
			echo "<input type=\"radio\" name=\"aspek_2\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;1 - Tidak membuat<br>
						<input type=\"radio\" name=\"aspek_2\" value=\"2\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;2 - Kurang lengkap<br>
						<input type=\"radio\" name=\"aspek_2\" value=\"3\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;3 - Cukup lengkap<br>
						<input type=\"radio\" name=\"aspek_2\" value=\"4\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;4 - Lengkap";
			echo "</td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>Aktifitas saat diskusi<br>(<i>Dinilai dari frekuensi mengajukan masukan / komentar / pendapat / jawaban</i>)</td>";
			echo "<td>";
			echo "<input type=\"radio\" name=\"aspek_3\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;1 - Pasif<br>
						<input type=\"radio\" name=\"aspek_3\" value=\"2\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;2 - Kurang aktif<br>
						<input type=\"radio\" name=\"aspek_3\" value=\"3\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;3 - Cukup aktif<br>
						<input type=\"radio\" name=\"aspek_3\" value=\"4\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;4 - Sangat aktif";
			echo "</td>";
		echo "</tr>";
		//No 4
		echo "<tr>";
			echo "<td align=center>4</td>";
			echo "<td>Relevansi pembicaraan<br>(<i>Dinilai dari relevansi dan penguasaaan terhadap materi diskusi</i>)</td>";
			echo "<td>";
			echo "<input type=\"radio\" name=\"aspek_4\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;1 - Pembicaraan selalu tidak relevan<br>
						<input type=\"radio\" name=\"aspek_4\" value=\"2\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;2 - Pembicaraan sering tidak relevan<br>
						<input type=\"radio\" name=\"aspek_4\" value=\"3\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;3 - Pembicaraan cukup relevan<br>
						<input type=\"radio\" name=\"aspek_4\" value=\"4\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;4 - Pembicaraan selalu relevan";
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
			echo "<input type=\"radio\" name=\"aspek_5\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;1 - Kurang<br>
						<input type=\"radio\" name=\"aspek_5\" value=\"2\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;2 - Cukup<br>
						<input type=\"radio\" name=\"aspek_5\" value=\"3\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;3 - Baik<br>
						<input type=\"radio\" name=\"aspek_5\" value=\"4\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;4 - Sangat baik";
			echo "</td>";
		echo "</tr>";
		//Total Nilai
		echo "<tr>";
			echo "<td colspan=2 align=right>Total Nilai (10 x Jumlah Poin / 2)</td>";
			echo "<td><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_total\" style=\"width:30%;font-size:0.85em;text-align:center\" value=\"0\" id=\"nilai_total\" required/></td>";
		echo "</tr>";
		echo "</table><br><br>";

		//Catatan
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr><td><b>Catatan Dosen Penguji (Tutor) Terhadap Kegiatan:</b></td></tr>";
		echo "<tr>";
			echo "<td>Catatan:<br><textarea name=\"catatan\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\"></textarea></td>";
		echo "</tr>";
		echo "</table><br>";

		echo "<br><center><input type=\"submit\" class=\"submit1\" name=\"cancel\" value=\"CANCEL\" formnovalidate>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" class=\"submit1\" name=\"usulkan\" value=\"USULKAN\"></center></form><br><br></fieldset>";

		if ($_POST[cancel]=="CANCEL")
		{
			echo "
				<script>
					window.location.href=\"penilaian_ikgm.php\";
				</script>
				";
		}

		if ($_POST[usulkan]=="USULKAN")
		{
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

			$insert_jurnal=mysqli_query($con,"INSERT INTO `ikgm_nilai_jurnal`
				( `nim`, `dosen`, `tgl_ujian`, `nama_jurnal`, `judul_paper`,
					`aspek_1`, `aspek_2`, `aspek_3`, `aspek_4`, `aspek_5`,
					`catatan`, `nilai_total`, `tgl_isi`, `tgl_approval`, `status_approval`)
				VALUES
				( '$_POST[nim]','$_POST[dosen]','$_POST[tanggal_ujian]','$nama_jurnal','$judul_paper',
					'$aspek1','$aspek2','$aspek3','$aspek4','$aspek5',
					'$catatan','$nilai_total','$tgl','2000-01-01','0')");

			echo "
				<script>
					window.location.href=\"penilaian_ikgm.php\";
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
		 placeholder: "<< Dosen Penguji (Tutor) >>",
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
