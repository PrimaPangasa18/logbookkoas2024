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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN KOMPREHENSIP</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
			echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">EDIT FORMULIR PENILAIAN PRESENSI / KEHADIRAN</font></h4>";

			$id = $_GET[id];
			$id_stase = "M121";
			$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
			$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
			$stase_id = "stase_".$id_stase;
			$data_stase_mhsw = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'");
			$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);
			$data_presensi = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kompre_nilai_presensi` WHERE `id`='$id'"));

			echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
			echo "<input type=\"hidden\" name=\"id_stase\" value=\"$id_stase\">";
			echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
			echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";

			//Instansi
			echo "<tr>";
				echo "<td style=\"width:40%\">Instansi</td>";
				echo "<td style=\"width:60%\"><select class=\"select_art\" name=\"instansi\" id=\"instansi\" required>";
				echo "<option value=\"$data_presensi[instansi]\">$data_presensi[instansi]</option>";
				if ($data_presensi[instansi]!="Puskesmas") echo "<option value=\"Puskesmas\">Puskesmas</option>";
				if ($data_presensi[instansi]!="Rumah Sakit") echo "<option value=\"Rumah Sakit\">Rumah Sakit</option>";
				echo "</select></td>";
			echo "</tr>";
			//Lokasi Puskesmas/Rumah Sakit
			echo "<tr>";
				echo "<td>Nama Puskesmas / Rumah Sakit</td>";
				echo "<td><textarea name=\"lokasi\" value=\"$data_presensi[lokasi]\" style=\"width:97%;font-family:Tahoma;font-size:1em\" required>$data_presensi[lokasi]</textarea></td>";
			echo "</tr>";
			//Nama dokter muda/koas
			echo "<tr>";
				echo "<td>Nama dokter muda</td>";
				echo "<td>$data_mhsw[nama]</td>";
			echo "</tr>";
			//NIM
			echo "<tr>";
				echo "<td>NIM</td>";
				echo "<td>$data_mhsw[nim]</td>";
			echo "</tr>";
			//Tgl mulai kegiatan
			echo "<tr>";
				echo "<td>Tanggal mulai kegiatan (yyyy-mm-dd)</td>";
				echo "<td><input type=\"text\" name=\"tgl_mulai\" class=\"tgl_mulai\" value=\"$data_presensi[tgl_mulai]\" style=\"font-size:0.85em\" required/></td>";
			echo "</tr>";
			//Tgl selesai kegiatan
			echo "<tr>";
				echo "<td>Tanggal selesai kegiatan (yyyy-mm-dd)</td>";
				echo "<td><input type=\"text\" name=\"tgl_selesai\" class=\"tgl_selesai\" value=\"$data_presensi[tgl_selesai]\" style=\"font-size:0.85em\" required/></td>";
			echo "</tr>";
			//Dokter Pembimbing
			echo "<tr>";
				echo "<td>Dokter Pembimbing</td>";
				echo "<td>";
				echo "<select class=\"select_art\" name=\"dosen\" id=\"dosen\" required>";
				$dosen = mysqli_query($con,"SELECT `username`,`nama` FROM `admin` WHERE `level`='4' OR (`level`='6' AND `stase`='$id_stase') ORDER BY `nama`");
				$data_dosen_db = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_presensi[dosen]'"));
				echo "<option value=\"$data_presensi[dosen]\">$data_dosen_db[nama], $data_dosen_db[gelar] ($data_dosen_db[nip])</option>";
				while ($data=mysqli_fetch_array($dosen))
				{
					$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data[username]'"));
					echo "<option value=\"$data[username]\">$data_dosen[nama], $data_dosen[gelar] ($data_dosen[nip])</option>";
				}
				echo "</select>";
				echo "</td>";
			echo "</tr>";
			echo "</table><br><br>";

			//Hari kegiatan
			//Jumlah Hari Kerja
			echo "<table border=0 style=\"border-collapse:collapse;width:70%;background:rgb(244, 241, 217);\">";
			echo "<tr><td colspan=3><b>Jumlah Hari Kegiatan:</b></td></tr>";
			echo "<tr>";
				echo "<td style=\"width:4%\" align=center>A.</td>";
				echo "<td style=\"width:36%\">Jumlah hari kerja<br>";
				echo "<font style=\"font-size:0.65em\"><i>(Hari kerja)</i></td>";
				echo "<td style=\"width:60%\">: <input type=\"number\" step=\"1\" min=\"0\" max=\"100\" name=\"hari_kerja\" style=\"width:25%;font-size:0.85em;text-align:center\" id=\"hari_kerja\" value=\"$data_presensi[hari_kerja]\" onkeyup=\"sum();\" onchange=\"sum();\" required/>&nbsp;&nbsp;hari</td>";
			echo "</tr>";
			//Jumlah hari tidak masuk dengan ijin
			echo "<tr>";
				echo "<td align=center>B.</td>";
				echo "<td>Jumlah hari tidak masuk DENGAN IJIN<br>";
				echo "<font style=\"font-size:0.65em\"><i>(Hari ijin)</i></td>";
				echo "<td>: <input type=\"number\" step=\"1\" min=\"0\" max=\"100\" name=\"hari_ijin\" style=\"width:25%;font-size:0.85em;text-align:center\" id=\"hari_ijin\" value=\"$data_presensi[hari_ijin]\" onkeyup=\"sum();\" onchange=\"sum();\" required/>&nbsp;&nbsp;hari</td>";
			echo "</tr>";
			//Jumlah hari tidak masuk tanpa ijin
			echo "<tr>";
				echo "<td align=center>C.</td>";
				echo "<td>Jumlah hari tidak masuk TANPA IJIN<br>";
				echo "<font style=\"font-size:0.65em\"><i>(Hari alpa)</i></td>";
				echo "<td>: <input type=\"number\" step=\"1\" min=\"0\" max=\"100\" name=\"hari_alpa\" style=\"width:25%;font-size:0.85em;text-align:center\" id=\"hari_alpa\" value=\"$data_presensi[hari_alpa]\" onkeyup=\"sum();\" onchange=\"sum();\" required/>&nbsp;&nbsp;hari</td>";
			echo "</tr>";
			//Jumlah Hari Masuk
			echo "<tr>";
				echo "<td align=center>D.</td>";
				echo "<td>Jumlah hari masuk kegiatan<br>";
				echo "<font style=\"font-size:0.65em\"><i>(Hari masuk = hari kerja - (hari ijin + hari alpa))</i></td>";
				echo "<td>: <input type=\"number\" step=\"1\" min=\"0\" max=\"100\" name=\"hari_masuk\" style=\"width:25%;font-size:0.85em;text-align:center\" id=\"hari_masuk\" value=\"$data_presensi[hari_masuk]\" required/>&nbsp;&nbsp;hari</td>";
			echo "</tr>";
			echo "</table><br><br>";

			//Form nilai
			echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
			echo "<tr><td><b>Form Penilaian:</b></td></tr>";
			echo "</table>";
			echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
			echo "<thead>";
			 	echo "<th style=\"width:5%\">No</th>";
				echo "<th style=\"width:75%\">Unsur Penilaian</th>";
				echo "<th style=\"width:20%\">Skor</th>";
			echo "</thead>";
			//No 1
			echo "<tr>";
				echo "<td align=center>1</td>";
				echo "<td>Bila setiap hari masuk dan kegiatan memenuhi (skor maksimal 100)<br>
							<font style=\"font-size:0.65em\"><i>Rumus skor = 100 x (Jml hari masuk)/(Jml hari kerja)</i></font></td>";
				echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_masuk\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"nilai_masuk\" value=\"$data_presensi[nilai_masuk]\" required/></td>";
			echo "</tr>";
			//No 2
			echo "<tr>";
				echo "<td align=center>2</td>";
				echo "<td>Bila tidak masuk TANPA IJIN, nilai dipotong 5 / hari<br>
							<font style=\"font-size:0.65em\"><i>Rumus skor pengurangan = 5 x Jml hari tidak masuk tanpa ijin</i></font></td>";
				echo "<td align=center><input type=\"number\" step=\"5\" min=\"-100\" max=\"0\" name=\"nilai_absen\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"nilai_absen\" value=\"$data_presensi[nilai_absen]\" required/></td>";
			echo "</tr>";
			//No 3
			echo "<tr>";
				echo "<td align=center>3</td>";
				echo "<td>Bila tidak masuk DENGAN IJIN, nilai dipotong 2 / hari<br>
							<font style=\"font-size:0.65em\"><i>Rumus skor pengurangan = 2 x Jml hari tidak masuk dengan ijin</i></font></td>";
				echo "<td align=center><input type=\"number\" step=\"2\" min=\"-100\" max=\"0\" name=\"nilai_ijin\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"nilai_ijin\" value=\"$data_presensi[nilai_ijin]\" required/></td>";
			echo "</tr>";
			//No 4
			echo "<tr>";
				echo "<td align=right colspan=2>Total Nilai (Skor 1 + Skor 2 + Skor 3)</td>";
				echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_total\" style=\"font-weight:bold;width:60%;font-size:0.85em;text-align:center\" id=\"nilai_total\" value=\"$data_presensi[nilai_total]\" required/></td>";
			echo "</tr>";
			echo "</table><br>";
			echo "<br><center><input type=\"submit\" class=\"submit1\" name=\"cancel\" value=\"CANCEL\" formnovalidate>";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			echo "<input type=\"submit\" class=\"submit1\" name=\"ubah\" value=\"UBAH\"></center>";
			echo "</form><br><br></fieldset>";

			if ($_POST[cancel]=="CANCEL")
			{
				echo "
				<script>
					window.location.href=\"penilaian_kompre.php\";
				</script>
				";
			}

			if ($_POST[ubah]=="UBAH")
			{
				$lokasi = addslashes($_POST[lokasi]);
				$nilai_total = number_format($_POST[nilai_total],2);
				$nilai_masuk = number_format($_POST[nilai_masuk],2);
				$nilai_absen = number_format($_POST[nilai_absen],2);
				$nilai_ijin = number_format($_POST[nilai_ijin],2);

				$update_presensi=mysqli_query($con,"UPDATE `kompre_nilai_presensi` SET
					`dosen`='$_POST[dosen]',`lokasi`='$lokasi',`instansi`='$_POST[instansi]',
					`tgl_mulai`='$_POST[tgl_mulai]',`tgl_selesai`='$_POST[tgl_selesai]',
					`hari_kerja`='$_POST[hari_kerja]', `hari_masuk`='$_POST[hari_masuk]',
					`hari_ijin`='$_POST[hari_ijin]', `hari_alpa`='$_POST[hari_alpa]',
					`nilai_masuk`='$nilai_masuk',`nilai_absen`='$nilai_absen',
					`nilai_ijin`='$nilai_ijin',`nilai_total`='$nilai_total',
					`tgl_isi`='$tgl',`tgl_approval`='2000-01-01',`status_approval`='0'
					WHERE `id`='$_POST[id]'");
				echo "
					<script>
						window.location.href=\"penilaian_kompre.php\";
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
	<script type="text/javascript" src="../jquery_ui/jquery-ui.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.tgl_mulai').datepicker({ dateFormat: 'yy-mm-dd' });
		});
		$(document).ready(function(){
			$('.tgl_selesai').datepicker({ dateFormat: 'yy-mm-dd' });
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
			 placeholder: "<< Dokter Pembimbing >>",
	     allowClear: true
		 });
	 });
	</script>

	<script>
	function sum() {
	      var hari_kerja = document.getElementById('hari_kerja').value;
				var hari_ijin = document.getElementById('hari_ijin').value;
				var hari_alpa = document.getElementById('hari_alpa').value;

				var result = parseInt(hari_kerja) - parseInt(hari_ijin) - parseInt(hari_alpa);
	      if (!isNaN(result)) {
	         document.getElementById('hari_masuk').value = result;
	      }
				var result1 = 100*(parseInt(result)/parseInt(hari_kerja));
	      if (!isNaN(result1)) {
	         document.getElementById('nilai_masuk').value = number_format(result1,2);
	      }
				var result2 = -5*parseInt(hari_alpa);
	      if (!isNaN(result2)) {
	         document.getElementById('nilai_absen').value = number_format(result2,2);
	      }
				var result3 = -2*parseInt(hari_ijin);
	      if (!isNaN(result3)) {
	         document.getElementById('nilai_ijin').value = number_format(result3,2);
	      }
				var result4 = parseFloat(result1) + parseInt(result2) + parseInt(result3);
	      if (!isNaN(result4)) {
	         document.getElementById('nilai_total').value = number_format(result4,2);
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
