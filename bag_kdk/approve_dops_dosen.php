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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN KEDOKTERAN KELUARGA</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
			echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">APPROVAL PENILAIAN DOPS<br>(Directly Observed Procedural Skill)</font></h4>";

				$id = $_GET[id];
				$id_stase = "M121";
				$data_dops = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kdk_nilai_dops` WHERE `id`='$id'"));
				$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
				$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_dops[nim]'"));
				$stase_id = "stase_".$id_stase;
				$data_stase_mhsw = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$data_dops[nim]'");
				$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);


				echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
				echo "<input type=\"hidden\" name=\"id_stase\" value=\"$id_stase\">";
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

				//Instansi
				echo "<tr>";
					echo "<td style=\"width:40%\">Instansi</td>";
					echo "<td style=\"width:60%\">$data_dops[instansi]</td>";
				echo "</tr>";
				//Lokasi Puskesmas/Klinik
				echo "<tr>";
					echo "<td>Nama Puskesmas / Klinik</td>";
					echo "<td>$data_dops[lokasi]</td>";
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
					echo "<td>Tanggal mulai kegiatan</td>";
					$mulai = tanggal_indo($data_dops[tgl_mulai]);
					echo "<td>$mulai</td>";
				echo "</tr>";
				//Tgl selesai kegiatan
				echo "<tr>";
					echo "<td>Tanggal selesai kegiatan</td>";
					$selesai = tanggal_indo($data_dops[tgl_selesai]);
					echo "<td>$selesai</td>";
				echo "</tr>";
				//Dokter Pembimbing
				echo "<tr>";
					echo "<td>Dokter Pembimbing</td>";
					echo "<td>";
					$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_dops[dosen]'"));
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
					echo "<th style=\"width:55%\">Aspek Yang Dinilai</th>";
					echo "<th style=\"width:20%\">Bobot</th>";
					echo "<th style=\"width:20%\">Nilai (0-100)</th>";
				echo "</thead>";
				//No 1
				echo "<tr>";
					echo "<td align=center>1</td>";
					echo "<td><b>Persiapan</b><br>";
					echo "a. Menyiapkan alat dan bahan<br>";
					echo "b. Memberitahu pasien/mengulang kontrak</td>";
					echo "<td align=center>20%</td>";
					echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_1\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"aspek_1\" value=\"$data_dops[aspek_1]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
				echo "</tr>";
				//No 2
				echo "<tr>";
					echo "<td align=center>2</td>";
					echo "<td><b>Fase orientasi</b><br>";
					echo "a. Menjelaskan tujuan<br>";
					echo "b. Menjelaskan prosedur tindakan<br>";
					echo "c. Mencuci tangan</td>";
					echo "<td align=center>20%</td>";
					echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_2\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"aspek_2\" value=\"$data_dops[aspek_2]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
				echo "</tr>";
				//No 3
				echo "<tr>";
					echo "<td align=center>3</td>";
					echo "<td><b>Fase kerja</b><br>";
					echo "a. Menjaga privacy<br>";
					echo "b. Melibatkan pasien/keluarga<br>";
					echo "c. Komunikasi terapetik<br>";
					echo "d. Penggunaan alat efisien<br>";
					echo "e. Penerapan prinsip kerja steril/bersih<br>";
					echo "f. Tindakan sistematik<br>";
					echo "g. Waktu efektif</td>";
					echo "<td align=center>40%</td>";
					echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_3\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"aspek_3\" value=\"$data_dops[aspek_3]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
				echo "</tr>";
				//No 4
				echo "<tr>";
					echo "<td align=center>4</td>";
					echo "<td><b>Fase terminasi</b><br>";
					echo "a. Cuci tangan<br>";
					echo "b. Menjelaskan rencana tindak lanjut</td>";
					echo "<td align=center>20%</td>";
					echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_4\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"aspek_4\" value=\"$data_dops[aspek_4]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
				echo "</tr>";
				//Rata-Rata Nilai
				echo "<tr>";
					echo "<td colspan=3 align=right>Rata-Rata Nilai (Jumlah Bobot x Nilai)</td>";
					echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_rata\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"nilai_rata\" value=\"$data_dops[nilai_rata]\" required/></td>";
				echo "</tr>";
				echo "</table><br>";

				//Umpan Balik
				echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
				echo "<tr>";
				  echo "<td>Umpan Balik:<br><textarea name=\"umpan_balik\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\">$data_dops[umpan_balik]</textarea></td>";
				echo "</tr>";
				echo "<tr>";
				  echo "<td>Saran:<br><textarea name=\"saran\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\">$data_dops[saran]</textarea></td>";
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
				window.location.href=\"penilaian_kdk_dosen.php?mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\";
			</script>
			";
		}

		if ($_POST[approve]=="APPROVE")
		{
			$tgl_mulai=$_POST[tgl_mulai];
			$tgl_selesai=$_POST[tgl_selesai];
			$approval=$_POST[approval];
			$mhsw=$_POST[mhsw];

			$aspek1 = number_format($_POST[aspek_1],2);
			$aspek2 = number_format($_POST[aspek_2],2);
			$aspek3 = number_format($_POST[aspek_3],2);
			$aspek4 = number_format($_POST[aspek_4],2);

			$nilai_rata = 0.20*$_POST[aspek_1] + 0.20*$_POST[aspek_2] + 0.40*$_POST[aspek_3] + 0.20*$_POST[aspek_4];
			$nilai_rata = number_format($nilai_rata,2);

			$umpan_balik = addslashes($_POST[umpan_balik]);
			$saran = addslashes($_POST[saran]);

			$update_dops=mysqli_query($con,"UPDATE `kdk_nilai_dops` SET
				`aspek_1`='$aspek1',`aspek_2`='$aspek2',
				`aspek_3`='$aspek3',`aspek_4`='$aspek4',`nilai_rata`='$nilai_rata',
				`umpan_balik`='$umpan_balik',`saran`='$saran',
				`tgl_approval`='$tgl',`status_approval`='1'
				WHERE `id`='$_POST[id]'");
			echo "
				<script>
					window.location.href=\"penilaian_kdk_dosen.php?mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\";
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
			var aspek1 = document.getElementById('aspek_1').value;
			var aspek2 = document.getElementById('aspek_2').value;
			var aspek3 = document.getElementById('aspek_3').value;
			var aspek4 = document.getElementById('aspek_4').value;

			var result = 0.20*parseFloat(aspek1) + 0.20*parseFloat(aspek2) + 0.40*parseFloat(aspek3) + 0.20*parseFloat(aspek4);
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
