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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) ILMU KESEHATAN JIWA</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">EDIT NILAI UJIAN MINI-CEX - UJIAN KOMPETENSI KLINIK<p>Kepaniteraan (Stase) Ilmu Kesehatan Jiwa</font></h4>";

		$id_stase = "M093";
		$id = $_GET['id'];
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
		$stase_id = "stase_".$id_stase;
		$data_stase_mhsw = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'");
		$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);
		$data_minicex = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `psikiatri_nilai_minicex` WHERE `id`='$id'"));

		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
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
		//Periode Kegiatan
		echo "<tr>";
			echo "<td class=\"td_mid\">Periode Kegiatan (yyyy-mm-dd)</td>";
			echo "<td class=\"td_mid\"><input type=\"text\" class=\"periode_awal\" name=\"periode_awal\" style=\"font-size:1em;font-family:TAHOMA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" value=\"$datastase_mhsw[tgl_mulai]\" />";
			echo " s.d. <input type=\"text\" class=\"periode_akhir\" name=\"periode_akhir\" style=\"font-size:1em;font-family:TAHOMA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" value=\"$datastase_mhsw[tgl_selesai]\" /></td>";
		echo "</tr>";
		//Tanggal Ujian
		echo "<tr>";
			echo "<td class=\"td_mid\">Tanggal Ujian (yyyy-mm-dd)</td>";
			echo "<td class=\"td_mid\"><input type=\"text\" class=\"tanggal_ujian\" name=\"tanggal_ujian\" style=\"font-size:1em;font-family:TAHOMA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" value=\"$data_minicex[tgl_ujian]\" /></td>";
		echo "</tr>";
		//Dosen Penilai/Penguji
		echo "<tr>";
			echo "<td>Dosen Penilai/Penguji</td>";
			echo "<td>";
			echo "<select class=\"select_art\" name=\"dosen\" id=\"dosen\" required>";
			$data_dosen_isian = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_minicex[dosen]'"));
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
		//Tempat/Lokasi
		echo "<tr>";
			echo "<td>Tempat / Lokasi</td>";
			echo "<td><input type=\"text\" name=\"lokasi\" class=\"select_art\" value=\"$data_minicex[lokasi]\" required /></td>";
		echo "</tr>";
		//Nama Pasien
		echo "<tr>";
			echo "<td>Nama Pasien</td>";
			echo "<td><input type=\"text\" name=\"nama_pasien\" class=\"select_art\" value=\"$data_minicex[nama_pasien]\" required /></td>";
		echo "</tr>";
		//Umur Pasien
		echo "<tr>";
			echo "<td>Umur Pasien</td>";
			echo "<td><input type=\"number\" step=\"1\" min=\"0\" max=\"150\" name=\"umur_pasien\" style=\"width:15%;font-size:0.85em;text-align:center\" value=\"$data_minicex[umur_pasien]\" required/>&nbsp;&nbsp;tahun</td>";
		echo "</tr>";
		//Jenis Kelamin Pasien
		echo "<tr>";
			echo "<td>Jenis Kelamin Pasien</td>";
			echo "<td>";
			if ($data_minicex[jk_pasien]=="Laki-Laki") echo "<input type=\"radio\" name=\"jk_pasien\" value=\"Laki-Laki\" checked/>&nbsp;&nbsp;Laki-Laki";
			else echo "<input type=\"radio\" name=\"jk_pasien\" value=\"Laki-Laki\" />&nbsp;&nbsp;Laki-Laki";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[jk_pasien]=="Perempuan") echo "<input type=\"radio\" name=\"jk_pasien\" value=\"Perempuan\" checked/>&nbsp;&nbsp;Perempuan";
			else echo "<input type=\"radio\" name=\"jk_pasien\" value=\"Perempuan\" />&nbsp;&nbsp;Perempuan";
			echo "</td>";
		echo "</tr>";
		//Status Kasus
		echo "<tr>";
			echo "<td>Status Kasus</td>";
			echo "<td>";
			if ($data_minicex[status_kasus]=="Baru") echo "<input type=\"radio\" name=\"status_kasus\" value=\"Baru\" checked/>&nbsp;&nbsp;Baru";
			else echo "<input type=\"radio\" name=\"status_kasus\" value=\"Baru\" />&nbsp;&nbsp;Baru";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			if ($data_minicex[status_kasus]=="Follow-up") echo "<input type=\"radio\" name=\"status_kasus\" value=\"Follow-up\" checked/>&nbsp;&nbsp;Follow-up";
			else echo "<input type=\"radio\" name=\"status_kasus\" value=\"Follow-up\" />&nbsp;&nbsp;Follow-up";
			echo "</td>";
		echo "</tr>";
		echo "</table><br><br>";

		//Form nilai
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr><td><b>Form Penilaian:</b></td></tr>";
		echo "</table>";
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<thead>";
		 	echo "<th style=\"width:7%\">No</th>";
			echo "<th style=\"width:78%\">Aspek Yang Dinilai</th>";
			echo "<th style=\"width:15%\">Nilai (0-100)</th>";
		echo "</thead>";
		//No A.1
		echo "<tr>";
			echo "<td align=center class=\"td_mid\">A.1</td>";
			echo "<td>Kemampuan wawancara</td>";
			echo "<td align=center class=\"td_mid\"><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_1\" style=\"width:75%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_1]\" id=\"aspek_1\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No A.2
		echo "<tr>";
			echo "<td align=center class=\"td_mid\">A.2</td>";
			echo "<td>Pemeriksaan status mental</td>";
			echo "<td align=center class=\"td_mid\"><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_2\" style=\"width:75%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_2]\" id=\"aspek_2\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No A.3
		echo "<tr>";
			echo "<td align=center class=\"td_mid\">A.3</td>";
			echo "<td>Kemampuanan diagnosis</td>";
			echo "<td align=center class=\"td_mid\"><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_3\" style=\"width:75%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_3]\" id=\"aspek_3\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No A.4
		echo "<tr>";
			echo "<td align=center class=\"td_mid\">A.4</td>";
			echo "<td>Kemampuan terapi</td>";
			echo "<td align=center class=\"td_mid\"><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_4\" style=\"width:75%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_4]\" id=\"aspek_4\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No A.5
		echo "<tr>";
			echo "<td align=center class=\"td_mid\">A.5</td>";
			echo "<td>Kemampuan konseling</td>";
			echo "<td align=center class=\"td_mid\"><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_5\" style=\"width:75%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_5]\" id=\"aspek_5\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No A.6
		echo "<tr>";
			echo "<td align=center class=\"td_mid\">A.6</td>";
			echo "<td>Profesionalisme dan etika</td>";
			echo "<td align=center class=\"td_mid\"><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_6\" style=\"width:75%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_6]\" id=\"aspek_6\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No B
		echo "<tr>";
			echo "<td align=center class=\"td_mid\">B</td>";
			echo "<td>Teori</td>";
			echo "<td align=center class=\"td_mid\"><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_7\" style=\"width:75%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_7]\" id=\"aspek_7\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";

		//Rata-Rata Nilai
		echo "<tr>";
			echo "<td colspan=2 align=right class=\"td_mid\">Rata-Rata Nilai (Nilai Total / 7)</td>";
			echo "<td align=center class=\"td_mid\"><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_rata\" style=\"width:75%;font-size:0.85em;text-align:center\" value=\"$data_minicex[nilai_rata]\" id=\"nilai_rata\" required/></td>";
		echo "</tr>";
		echo "<tr><td colspan=3><font style=\"font-size:0.75em;\">";
		echo "<i>Keterangan:<br>";
		echo "Nilai Batas Lulus (NBL) = 70<br>";
		echo "Nilai A = 80.00 - 100.00 (SUPERIOR)<br>";
		echo "Nilai B = 70.00 - 79.99 (LULUS)<br>";
		echo "Nilai C < 70.00 (TIDAK LULUS)";
		echo "</i></font></td></tr>";
		echo "</table><br><br>";

		//Umpan Balik
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr><td colspan=2>1. Waktu Penilaian MINI-CEX:</td></tr>";
		echo "<tr>";
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;Observasi</td>";
			echo "<td>";
			echo "<input type=\"number\" step=\"5\" min=\"0\" max=\"1500\" name=\"waktu_observasi\" style=\"width:20%;font-size:0.85em\" value=\"$data_minicex[waktu_observasi]\" required/>&nbsp;&nbsp;menit";
			echo "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;Memberikan umpan balik</td>";
			echo "<td>";
			echo "<input type=\"number\" step=\"5\" min=\"0\" max=\"1500\" name=\"waktu_ub\" style=\"width:20%;font-size:0.85em\" value=\"$data_minicex[waktu_ub]\" required/>&nbsp;&nbsp;menit";
			echo "</td>";
		echo "</tr>";
		//Kepuasaan penilai terhadap minicex
		echo "<tr>";
			echo "<td colspan=2>2. Kepuasaan penilai terhadap MINI-CEX:<br><br>";
			if ($data_minicex[kepuasan1]=="Kurang sekali") echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan1\" value=\"Kurang sekali\" checked/>&nbsp;&nbsp;Kurang sekali";
			else echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan1\" value=\"Kurang sekali\" />&nbsp;&nbsp;Kurang sekali";
			if ($data_minicex[kepuasan1]=="Kurang") echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan1\" value=\"Kurang\" checked/>&nbsp;&nbsp;Kurang";
			else echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan1\" value=\"Kurang\" />&nbsp;&nbsp;Kurang";
			if ($data_minicex[kepuasan1]=="Cukup") echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan1\" value=\"Cukup\" checked/>&nbsp;&nbsp;Cukup";
			else echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan1\" value=\"Cukup\" />&nbsp;&nbsp;Cukup";
			if ($data_minicex[kepuasan1]=="Baik") echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan1\" value=\"Baik\" checked/>&nbsp;&nbsp;Baik";
			else echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan1\" value=\"Baik\" />&nbsp;&nbsp;Baik";
			if ($data_minicex[kepuasan1]=="Baik sekali") echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan1\" value=\"Baik sekali\" checked/>&nbsp;&nbsp;Baik sekali";
			else echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan1\" value=\"Baik sekali\" />&nbsp;&nbsp;Baik sekali";
			echo "</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td colspan=2>3. Kepuasaan peserta ujian terhadap MINI-CEX:<br><br>";
			if ($data_minicex[kepuasan2]=="Kurang sekali") echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan2\" value=\"Kurang sekali\" checked/>&nbsp;&nbsp;Kurang sekali";
			else echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan2\" value=\"Kurang sekali\" />&nbsp;&nbsp;Kurang sekali";
			if ($data_minicex[kepuasan2]=="Kurang") echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan2\" value=\"Kurang\" checked/>&nbsp;&nbsp;Kurang";
			else echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan2\" value=\"Kurang\" />&nbsp;&nbsp;Kurang";
			if ($data_minicex[kepuasan2]=="Cukup") echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan2\" value=\"Cukup\" checked/>&nbsp;&nbsp;Cukup";
			else echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan2\" value=\"Cukup\" />&nbsp;&nbsp;Cukup";
			if ($data_minicex[kepuasan2]=="Baik") echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan2\" value=\"Baik\" checked/>&nbsp;&nbsp;Baik";
			else echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan2\" value=\"Baik\" />&nbsp;&nbsp;Baik";
			if ($data_minicex[kepuasan2]=="Baik sekali") echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan2\" value=\"Baik sekali\" checked/>&nbsp;&nbsp;Baik sekali";
			else echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan2\" value=\"Baik sekali\" />&nbsp;&nbsp;Baik sekali";
			echo "</td>";
		echo "</tr>";
		echo "</table><br>";

		echo "<br><center><input type=\"submit\" class=\"submit1\" name=\"cancel\" value=\"CANCEL\" formnovalidate>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" class=\"submit1\" name=\"ubah\" value=\"UBAH\"></center></form><br><br></fieldset>";

		if ($_POST[cancel]=="CANCEL")
		{
			echo "
				<script>
					window.location.href=\"penilaian_psikiatri.php\";
				</script>
				";
		}

		if ($_POST[ubah]=="UBAH")
		{
			$aspek1 = number_format($_POST[aspek_1],2);
			$aspek2 = number_format($_POST[aspek_2],2);
			$aspek3 = number_format($_POST[aspek_3],2);
			$aspek4 = number_format($_POST[aspek_4],2);
			$aspek5 = number_format($_POST[aspek_5],2);
			$aspek6 = number_format($_POST[aspek_6],2);
			$aspek7 = number_format($_POST[aspek_7],2);

			$nama_pasien = addslashes($_POST[nama_pasien]);
			$lokasi = addslashes($_POST[lokasi]);
			$nilai_total = $_POST[aspek_1]+$_POST[aspek_2]+$_POST[aspek_3]+$_POST[aspek_4]+$_POST[aspek_5]+$_POST[aspek_6]+$_POST[aspek_7];
			$nilai_rata = $nilai_total/7;
			$nilai_rata = number_format($nilai_rata,2);

			$update_minicex=mysqli_query($con,"UPDATE `psikiatri_nilai_minicex` SET
				`dosen`='$_POST[dosen]',`tgl_awal`='$_POST[periode_awal]',`tgl_akhir`='$_POST[periode_akhir]',`tgl_ujian`='$_POST[tanggal_ujian]',`lokasi`='$lokasi',
				`nama_pasien`='$nama_pasien',`umur_pasien`='$_POST[umur_pasien]',`jk_pasien`='$_POST[jk_pasien]',`status_kasus`='$_POST[status_kasus]',
				`aspek_1`='$aspek1',`aspek_2`='$aspek2',`aspek_3`='$aspek3',`aspek_4`='$aspek4',
				`aspek_5`='$aspek5',`aspek_6`='$aspek6',`aspek_7`='$aspek7',`nilai_rata`='$nilai_rata',
				`waktu_observasi`='$_POST[waktu_observasi]',`waktu_ub`='$_POST[waktu_ub]',
				`kepuasan1`='$_POST[kepuasan1]',`kepuasan2`='$_POST[kepuasan2]',
				`tgl_isi`='$tgl'
				WHERE `id`='$_POST[id]'");

			echo "
				<script>
					window.location.href=\"penilaian_psikiatri.php\";
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
		 placeholder: "<< Dosen Penguji/Penilai >>",
     allowClear: true
	 });
 });
</script>
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
			var aspek7 = document.getElementById('aspek_7').value;
      var result = (parseFloat(aspek1) + parseFloat(aspek2) + parseFloat(aspek3) + parseFloat(aspek4) + parseFloat(aspek5) + parseFloat(aspek6) + parseFloat(aspek7))/7;
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
