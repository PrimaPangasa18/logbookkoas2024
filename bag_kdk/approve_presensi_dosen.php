<HTML>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="../menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../mytable.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../qr_code.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../text-security-master/dist/text-security.css" type="text/css" media="screen" />
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
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">APPROVAL PENILAIAN PRESENSI / KEHADIRAN</font></h4>";

			$id = $_GET[id];
			$id_stase = "M121";
			$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
			$data_presensi = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kdk_nilai_presensi` WHERE `id`='$id'"));
			$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_presensi[nim]'"));
			$stase_id = "stase_".$id_stase;
			$data_stase_mhsw = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$data_presensi[nim]'");
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
				echo "<td style=\"width:60%\">$data_presensi[instansi]</td>";
			echo "</tr>";
			//Lokasi Puskesmas/Klinik
			echo "<tr>";
				echo "<td>Nama Puskesmas / Klinik</td>";
				echo "<td>$data_presensi[lokasi]</td>";
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
				$mulai = tanggal_indo($data_presensi[tgl_mulai]);
				echo "<td>$mulai</td>";
			echo "</tr>";
			//Tgl selesai kegiatan
			echo "<tr>";
				echo "<td>Tanggal selesai kegiatan</td>";
				$selesai = tanggal_indo($data_presensi[tgl_selesai]);
				echo "<td>$selesai</td>";
			echo "</tr>";
			//Dokter Pembimbing
			echo "<tr>";
				echo "<td>Dokter Pembimbing</td>";
				echo "<td>";
				$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_presensi[dosen]'"));
				echo "$data_dosen[nama], $data_dosen[gelar] ($data_dosen[nip])";
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

				$nilai_total = number_format($_POST[nilai_total],2);
				$nilai_masuk = number_format($_POST[nilai_masuk],2);
				$nilai_absen = number_format($_POST[nilai_absen],2);
				$nilai_ijin = number_format($_POST[nilai_ijin],2);

				$update_presensi=mysqli_query($con,"UPDATE `kdk_nilai_presensi` SET
					`hari_kerja`='$_POST[hari_kerja]', `hari_masuk`='$_POST[hari_masuk]',
					`hari_ijin`='$_POST[hari_ijin]', `hari_alpa`='$_POST[hari_alpa]',
					`nilai_masuk`='$nilai_masuk',`nilai_absen`='$nilai_absen',
					`nilai_ijin`='$nilai_ijin',`nilai_total`='$nilai_total',
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
<script src="../qr_packed.js"></script>
<script type="text/javascript">
	function openQRCamera(node) {
		var reader = new FileReader();
		reader.onload = function() {
			node.value = "";
			qrcode.callback = function(res) {
				if(res instanceof Error) {
					alert("No QR code found. Please make sure the QR code is within the camera's frame and try again.");
				} else {
					node.parentNode.previousElementSibling.value = res;
				}
			};
			qrcode.decode(reader.result);
		};
		reader.readAsDataURL(node.files[0]);
	}
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
