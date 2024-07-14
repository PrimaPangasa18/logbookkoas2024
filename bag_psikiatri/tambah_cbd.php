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
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">FORMULIR CBD - LEMBAR PENILAIAN FORMATIF KOMPETENSI KLINIK<p>Kepaniteraan (Stase) Ilmu Kesehatan Jiwa</font></h4>";

		$id_stase = "M093";
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
		//Periode Kegiatan
		echo "<tr>";
			echo "<td class=\"td_mid\">Periode Kegiatan (yyyy-mm-dd)</td>";
			echo "<td class=\"td_mid\"><input type=\"text\" class=\"periode_awal\" name=\"periode_awal\" style=\"font-size:1em;font-family:TAHOMA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" value=\"$datastase_mhsw[tgl_mulai]\" />";
			echo " s.d. <input type=\"text\" class=\"periode_akhir\" name=\"periode_akhir\" style=\"font-size:1em;font-family:TAHOMA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" value=\"$datastase_mhsw[tgl_selesai]\" /></td>";
		echo "</tr>";
		//Tanggal Ujian
		echo "<tr>";
			echo "<td class=\"td_mid\">Tanggal Ujian (yyyy-mm-dd)</td>";
			echo "<td class=\"td_mid\"><input type=\"text\" class=\"tanggal_ujian\" name=\"tanggal_ujian\" style=\"font-size:1em;font-family:TAHOMA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" placeholder=\"yyyy-mm-dd\" /></td>";
		echo "</tr>";
		//Dosen Penilai/Penguji
		echo "<tr>";
			echo "<td>Dosen Penilai/Penguji</td>";
			echo "<td>";
			echo "<select class=\"select_art\" name=\"dosen\" id=\"dosen\" required>";
			$dosen = mysqli_query($con,"SELECT `username`,`nama` FROM `admin` WHERE `level`='4' OR (`level`='6' AND `stase`='$id_stase') ORDER BY `nama`");
			echo "<option value=\"\"><< Dosen Penilai/Penguji >></option>";
			while ($data=mysqli_fetch_array($dosen))
			{
				$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data[username]'"));
				echo "<option value=\"$data[username]\">$data_dosen[nama], $data_dosen[gelar] ($data_dosen[nip])</option>";
			}
			echo "</select>";
			echo "</td>";
		echo "</tr>";
		//Situasi Ruangan
		echo "<tr>";
			echo "<td>Situasi Ruangan</td>";
			echo "<td>
						<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Jalan\" checked/>&nbsp;&nbsp;Rawat Jalan
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"situasi_ruangan\" value=\"UGD\" />&nbsp;&nbsp;UGD
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Inap\" />&nbsp;&nbsp;Rawat Inap
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"situasi_ruangan\" value=\"Lain-lain\" />&nbsp;&nbsp;Lain-lain
						</td>";
		echo "</tr>";
		//Nama Pasien
		echo "<tr>";
			echo "<td>Nama Pasien</td>";
			echo "<td><input type=\"text\" name=\"nama_pasien\" class=\"select_art\" placeholder=\"<< Nama Pasien >>\" required /></td>";
		echo "</tr>";
		//Umur Pasien
		echo "<tr>";
			echo "<td>Umur Pasien</td>";
			echo "<td><input type=\"number\" step=\"1\" min=\"0\" max=\"150\" name=\"umur_pasien\" style=\"width:15%;font-size:0.85em;text-align:center\" placeholder=\"0-150\" required/>&nbsp;&nbsp;tahun</td>";
		echo "</tr>";
		//Jenis Kelamin Pasien
		echo "<tr>";
			echo "<td>Jenis Kelamin Pasien</td>";
			echo "<td>
						<input type=\"radio\" name=\"jk_pasien\" value=\"Laki-Laki\" checked/>&nbsp;&nbsp;Laki-Laki
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"jk_pasien\" value=\"Perempuan\" />&nbsp;&nbsp;Perempuan
						</td>";
		echo "</tr>";
		//Status Kasus
		echo "<tr>";
			echo "<td>Status Kasus</td>";
			echo "<td>
						<input type=\"radio\" name=\"status_kasus\" value=\"Baru\" checked/>&nbsp;&nbsp;Baru
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"status_kasus\" value=\"Follow-up\" />&nbsp;&nbsp;Follow-up
						</td>";
		echo "</tr>";
		//Tingkat Kerumitan
		echo "<tr>";
			echo "<td>Tingkat Kerumitan</td>";
			echo "<td>
						<input type=\"radio\" name=\"tingkat_kerumitan\" value=\"Rendah\" checked/>&nbsp;&nbsp;Rendah
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"tingkat_kerumitan\" value=\"Sedang\" />&nbsp;&nbsp;Sedang
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"tingkat_kerumitan\" value=\"Tinggi\" />&nbsp;&nbsp;Tinggi
						</td>";
		echo "</tr>";
		//Fokus Pertemuan Klinik
		echo "<tr>";
			echo "<td>Fokus Pertemuan Klinik</td>";
			echo "<td>
						<input type=\"radio\" name=\"fokus_pertemuan\" value=\"Pengumpulan Data\" checked/>&nbsp;&nbsp;Pengumpulan Data<br>
						<input type=\"radio\" name=\"fokus_pertemuan\" value=\"Manajemen Pasien\" />&nbsp;&nbsp;Manajemen Pasien<br>
						<input type=\"radio\" name=\"fokus_pertemuan\" value=\"Diagnosis\" />&nbsp;&nbsp;Diagnosis<br>
						<input type=\"radio\" name=\"fokus_pertemuan\" value=\"Konseling\" />&nbsp;&nbsp;Konseling
						</td>";
		echo "</tr>";
		echo "</table><br><br>";

		//Form nilai
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr><td><b>Form Penilaian:</b></td></tr>";
		echo "</table>";
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<thead>";
		 	echo "<th style=\"width:5%\">No</th>";
			echo "<th style=\"width:55%\">Komponen Kompetensi</th>";
			echo "<th style=\"width:20%\">Status Observasi</th>";
			echo "<th style=\"width:20%\">Nilai (0-100)</th>";
		echo "</thead>";
		//No 1
		echo "<tr>";
			echo "<td align=center>1</td>";
			echo "<td>Kemampuan membuat catatan medis</td>";
			echo "<td align=center><input type=\"radio\" name=\"observasi_1\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"observasi_1\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_1\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"aspek_1\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>Clinical assesment</td>";
			echo "<td align=center><input type=\"radio\" name=\"observasi_2\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"observasi_2\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_2\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"aspek_2\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>Investigasi dan rujukan</td>";
			echo "<td align=center><input type=\"radio\" name=\"observasi_3\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"observasi_3\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_3\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"aspek_3\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 4
		echo "<tr>";
			echo "<td align=center>4</td>";
			echo "<td>Terapi</td>";
			echo "<td align=center><input type=\"radio\" name=\"observasi_4\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"observasi_4\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_4\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"aspek_4\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 5
		echo "<tr>";
			echo "<td align=center>5</td>";
			echo "<td>Follow up dan rencana pengelolaan selanjutnya</td>";
			echo "<td align=center><input type=\"radio\" name=\"observasi_5\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"observasi_5\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_5\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"aspek_5\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 6
		echo "<tr>";
			echo "<td align=center>6</td>";
			echo "<td>Profesionalisme</td>";
			echo "<td align=center><input type=\"radio\" name=\"observasi_6\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"observasi_6\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_6\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"aspek_6\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 7
		echo "<tr>";
			echo "<td align=center>7</td>";
			echo "<td>Penilaian klinik secara keseluruhan</td>";
			echo "<td align=center><input type=\"radio\" name=\"observasi_7\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=\"radio\" name=\"observasi_7\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_7\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"aspek_7\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//Rata Nilai
		echo "<tr>";
			echo "<td align=right colspan=3>Rata-Rata Nilai (Jumlah Nilai / Jumlah Observasi)</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_rata\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"nilai_rata\" value=\"0\" required/></td>";
		echo "</tr>";
		echo "</table><br><br>";

		//Umpan Balik
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr><td colspan=2 align=center><b>UMPAN BALIK TERHADAP KINERJA PESERTA UJIAN</b></td></tr>";
		echo "<tr>";
			echo "<td align=center style=\"width:50%\">Aspek yang sudah bagus</td>";
			echo "<td align=center style=\"width:50%\">Aspek yang perlu diperbaiki</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td><textarea name=\"ub_bagus\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\"></textarea></td>";
			echo "<td><textarea name=\"ub_perbaikan\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\"></textarea></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td colspan=2>Action plan yang disetujui bersama:<br><textarea name=\"saran\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\"></textarea></td>";
		echo "</tr>";
		//Kepuasaan penilai terhadap CBD
		echo "<tr>";
			echo "<td colspan=2><b>Catatan:</b><br><br>Kepuasaan penilai terhadap CBD:<br><br>";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan\" value=\"Kurang sekali\" checked/>&nbsp;&nbsp;Kurang sekali
						<br>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan\" value=\"Kurang\" />&nbsp;&nbsp;Kurang
						<br>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan\" value=\"Cukup\" />&nbsp;&nbsp;Cukup
						<br>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan\" value=\"Baik\" />&nbsp;&nbsp;Baik
						<br>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"kepuasan\" value=\"Baik sekali\" />&nbsp;&nbsp;Baik sekali
						<br><br></td>";
		echo "</tr>";
		echo "</table><br>";

		echo "<br><center><input type=\"submit\" class=\"submit1\" name=\"cancel\" value=\"CANCEL\" formnovalidate>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" class=\"submit1\" name=\"usulkan\" value=\"USULKAN\"></center></form><br><br></fieldset>";

		if ($_POST[cancel]=="CANCEL")
		{
			echo "
				<script>
					window.location.href=\"penilaian_psikiatri.php\";
				</script>
				";
		}

		if ($_POST[usulkan]=="USULKAN")
		{
			$aspek1 = number_format($_POST[observasi_1]*$_POST[aspek_1],2);
			$aspek2 = number_format($_POST[observasi_2]*$_POST[aspek_2],2);
			$aspek3 = number_format($_POST[observasi_3]*$_POST[aspek_3],2);
			$aspek4 = number_format($_POST[observasi_4]*$_POST[aspek_4],2);
			$aspek5 = number_format($_POST[observasi_5]*$_POST[aspek_5],2);
			$aspek6 = number_format($_POST[observasi_6]*$_POST[aspek_6],2);
			$aspek7 = number_format($_POST[observasi_7]*$_POST[aspek_7],2);

			$nama_pasien = addslashes($_POST[nama_pasien]);
			$ub_bagus = addslashes($_POST[ub_bagus]);
			$ub_perbaikan = addslashes($_POST[ub_perbaikan]);
			$saran = addslashes($_POST[saran]);
			$jml_observasi = $_POST[observasi_1]+$_POST[observasi_2]+$_POST[observasi_3]+$_POST[observasi_4]+$_POST[observasi_5]+$_POST[observasi_6]+$_POST[observasi_7];
			$nilai_total = $_POST[observasi_1]*$_POST[aspek_1]+$_POST[observasi_2]*$_POST[aspek_2]+$_POST[observasi_3]*$_POST[aspek_3]+$_POST[observasi_4]*$_POST[aspek_4]+$_POST[observasi_5]*$_POST[aspek_5]+$_POST[observasi_6]*$_POST[aspek_6]+$_POST[observasi_7]*$_POST[aspek_7];
			if ($jml_observasi==0) $nilai_rata = 0;
			else $nilai_rata = $nilai_total/$jml_observasi;
			$nilai_rata = number_format($nilai_rata,2);

			$insert_cbd=mysqli_query($con,"INSERT INTO `psikiatri_nilai_cbd`
				( `nim`, `dosen`, `tgl_awal`, `tgl_akhir`, `tgl_ujian`,`situasi_ruangan`,
					`nama_pasien`,`umur_pasien`,`jk_pasien`, `status_kasus`,`tingkat_kerumitan`, `fokus_pertemuan`,
					`aspek_1`, `observasi_1`, `aspek_2`, `observasi_2`,
					`aspek_3`, `observasi_3`, `aspek_4`, `observasi_4`,
					`aspek_5`, `observasi_5`, `aspek_6`, `observasi_6`,
					`aspek_7`, `observasi_7`, `ub_bagus`, `ub_perbaikan`,
					`saran`, `nilai_rata`, `kepuasan`,
					`tgl_isi`, `tgl_approval`, `status_approval`)
				VALUES
				( '$_POST[nim]','$_POST[dosen]','$_POST[periode_awal]','$_POST[periode_akhir]','$_POST[tanggal_ujian]','$_POST[situasi_ruangan]',
					'$nama_pasien','$_POST[umur_pasien]','$_POST[jk_pasien]','$_POST[status_kasus]','$_POST[tingkat_kerumitan]','$_POST[fokus_pertemuan]',
					'$aspek1','$_POST[observasi_1]','$aspek2','$_POST[observasi_2]',
					'$aspek3','$_POST[observasi_3]','$aspek4','$_POST[observasi_4]',
					'$aspek5','$_POST[observasi_5]','$aspek6','$_POST[observasi_6]',
					'$aspek7','$_POST[observasi_7]','$ub_bagus','$ub_perbaikan',
					'$saran','$nilai_rata','$_POST[kepuasan]',
					'$tgl','2000-01-01','0')");
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
			var observasi1 = $("input[name=observasi_1]:checked").val();
			var observasi2 = $("input[name=observasi_2]:checked").val();
			var observasi3 = $("input[name=observasi_3]:checked").val();
			var observasi4 = $("input[name=observasi_4]:checked").val();
			var observasi5 = $("input[name=observasi_5]:checked").val();
			var observasi6 = $("input[name=observasi_6]:checked").val();
			var observasi7 = $("input[name=observasi_7]:checked").val();

			var total = parseInt(observasi1)*parseFloat(aspek1) + parseInt(observasi2)*parseFloat(aspek2) + parseInt(observasi3)*parseFloat(aspek3) + parseInt(observasi4)*parseFloat(aspek4) + parseInt(observasi5)*parseFloat(aspek5) + parseInt(observasi6)*parseFloat(aspek6) + parseInt(observasi7)*parseFloat(aspek7);
			var pembagi = parseInt(observasi1) + parseInt(observasi2) + parseInt(observasi3) + parseInt(observasi4) + parseInt(observasi5) + parseInt(observasi6) + parseInt(observasi7);
			var result = parseFloat(total)/parseInt(pembagi);

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
