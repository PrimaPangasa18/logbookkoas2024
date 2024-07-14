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
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">FORMULIR PENILAIAN JOURNAL READING<p>Kepaniteraan (Stase) Ilmu Kebidanan dan Penyakit Kandungan</font></h4>";

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
			echo "<td style=\"width:40%\">Nama Mahasiswa</td>";
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
			echo "<th style=\"width:80%\">Aspek Yang Dinilai</th>";
			echo "<th style=\"width:15%\">Nilai (0-100)</th>";
		echo "</thead>";
		//No 1
		echo "<tr>";
			echo "<td align=center class=\"td_mid\">1</td>";
			echo "<td>Transparansi</td>";
			echo "<td align=center class=\"td_mid\"><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_1\" style=\"width:75%;font-size:0.85em;text-align:center\" id=\"aspek_1\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center class=\"td_mid\">2</td>";
			echo "<td>Cara Penyajian</td>";
			echo "<td align=center class=\"td_mid\"><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_2\" style=\"width:75%;font-size:0.85em;text-align:center\" id=\"aspek_2\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center class=\"td_mid\">3</td>";
			echo "<td>Penguasaan Materi</td>";
			echo "<td align=center class=\"td_mid\"><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_3\" style=\"width:75%;font-size:0.85em;text-align:center\" id=\"aspek_3\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 4
		echo "<tr>";
			echo "<td align=center class=\"td_mid\">4</td>";
			echo "<td>Keaktifan</td>";
			echo "<td align=center class=\"td_mid\"><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_4\" style=\"width:75%;font-size:0.85em;text-align:center\" id=\"aspek_4\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";

		//Rata-Rata Nilai
		echo "<tr>";
			echo "<td colspan=2 align=right class=\"td_mid\">Rata-Rata Nilai (Total Nilai / 4)</td>";
			echo "<td align=center class=\"td_mid\"><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_rata\" style=\"width:75%;font-size:0.85em;text-align:center\" id=\"nilai_rata\" value=\"0\" required/></td>";
		echo "</tr>";
		echo "</table><br>";

		//Catatan
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr><td><b>Catatan Dosen Penilai Terhadap Kegiatan:</b></td></tr>";
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
					window.location.href=\"penilaian_obsgyn.php\";
				</script>
				";
		}

		if ($_POST[usulkan]=="USULKAN")
		{
			$aspek1 = number_format($_POST[aspek_1],2);
			$aspek2 = number_format($_POST[aspek_2],2);
			$aspek3 = number_format($_POST[aspek_3],2);
			$aspek4 = number_format($_POST[aspek_4],2);

			$nama_jurnal = addslashes($_POST[nama_jurnal]);
			$judul_paper = addslashes($_POST[judul_paper]);
			$catatan = addslashes($_POST[catatan]);

			$nilai_all = $_POST[aspek_1]+$_POST[aspek_2]+$_POST[aspek_3]+$_POST[aspek_4];
			$nilai_rata = number_format($nilai_all/4,2);

			$insert_jurnal=mysqli_query($con,"INSERT INTO `obsgyn_nilai_jurnal`
				( `nim`, `dosen`, `tgl_ujian`, `nama_jurnal`, `judul_paper`,
					`aspek_1`, `aspek_2`, `aspek_3`, `aspek_4`, `catatan`,
					`nilai_rata`,`tgl_isi`, `tgl_approval`, `status_approval`)
				VALUES
				( '$_POST[nim]','$_POST[dosen]','$_POST[tanggal_ujian]','$nama_jurnal','$judul_paper',
					'$aspek1','$aspek2','$aspek3','$aspek4','$catatan',
					'$nilai_rata','$tgl','2000-01-01','0')");

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
		 placeholder: "<< Dosen Penilai >>",
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
      var aspek1 = document.getElementById('aspek_1').value;
			var aspek2 = document.getElementById('aspek_2').value;
			var aspek3 = document.getElementById('aspek_3').value;
			var aspek4 = document.getElementById('aspek_4').value;
			var result = (parseFloat(aspek1) + parseFloat(aspek2) + parseFloat(aspek3) + parseFloat(aspek4))/4;
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
