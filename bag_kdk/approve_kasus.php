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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND $_COOKIE['level']==5)
	{
		if ($_COOKIE['level']==5) {include "menu5.php";}

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN KEDOKTERAN KELUARGA</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">NILAI PORTOFOLIO LAPORAN KASUS<p>Kedokteran Keluarga dengan Kunjungan Rumah</font></h4>";

		$id_stase = "M121";
		$id = $_GET['id'];
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
		$stase_id = "stase_".$id_stase;
		$data_stase_mhsw = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'");
		$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);
		$data_kasus = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kdk_nilai_kasus` WHERE `id`='$id'"));

		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";

		//Lokasi Puskesmas/Klinik
		echo "<tr>";
			echo "<td style=\"width:40%\">Nama Puskesmas / Klinik</td>";
			echo "<td style=\"width:60%\">$data_kasus[lokasi]</td>";
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
			$mulai = tanggal_indo($data_kasus[tgl_mulai]);
			echo "<td>$mulai</td>";
		echo "</tr>";
		//Tgl selesai kegiatan
		echo "<tr>";
			echo "<td>Tanggal selesai kegiatan</td>";
			$selesai = tanggal_indo($data_kasus[tgl_selesai]);
			echo "<td>$selesai</td>";
		echo "</tr>";
		///Pilihan Kasus
		echo "<tr>";
			echo "<td>Kasus</td>";
			echo "<td>$data_kasus[kasus]</td>";
		echo "</tr>";
		//Dosen Pembimbing Lapangan
		echo "<tr>";
			echo "<td>Dosen Pembimbing Lapangan</td>";
			echo "<td>";
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_kasus[dosen]'"));
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
			echo "<th style=\"width:65%\">Aspek Yang Dinilai</th>";
			echo "<th style=\"width:15%\">Bobot</th>";
			echo "<th style=\"width:15%\">Nilai (0-100)</th>";
		echo "</thead>";
		//No 1
		echo "<tr>";
			echo "<td align=center>1</td>";
			echo "<td>Kejelasan penyajian/presentasi</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_1\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"aspek_1\" value=\"$data_kasus[aspek_1]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>Kualitas kemampuan identifikasi masalah pasien dan keluarga (dilihat dari kejelasan isi dan penulisan)</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_2\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"aspek_2\" value=\"$data_kasus[aspek_2]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>Kualitas kemampuan diagnosis holistic (dilihat dari kejelasan isi dan penulisan)</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_3\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"aspek_3\" value=\"$data_kasus[aspek_3]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 4
		echo "<tr>";
			echo "<td align=center>4</td>";
			echo "<td>Kualitas kemampuan penatalaksanaan kasus (dilihat dari kejelasan isi dan penulisan)</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_4\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"aspek_4\" value=\"$data_kasus[aspek_4]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 5
		echo "<tr>";
			echo "<td align=center>5</td>";
			echo "<td>Kelancaran diskusi</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_5\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"aspek_5\" value=\"$data_kasus[aspek_5]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//Rata-Rata Nilai
		echo "<tr>";
			echo "<td colspan=3 align=right>Rata-Rata Nilai (Jumlah Bobot x Nilai)</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_rata\" style=\"width:60%;font-size:0.85em;text-align:center\" id=\"nilai_rata\" value=\"$data_kasus[nilai_rata]\" required/></td>";
		echo "</tr>";
		echo "</table><br>";

		//Umpan Balik
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr>";
		  echo "<td>Umpan Balik:<br><textarea name=\"umpan_balik\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\">$data_kasus[umpan_balik]</textarea></td>";
		echo "</tr>";
		echo "<tr>";
		  echo "<td>Saran:<br><textarea name=\"saran\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\">$data_kasus[saran]</textarea></td>";
		echo "</tr>";
		echo "</table><br>";

		echo "<br><br><center><<< APPROVAL >>><br><br>";
		echo "<table>";
		echo "<tr class=\"ganjil\"><td>Nama Dosen/Dokter/Residen</td><td>$data_dosen[nama], $data_dosen[gelar]<br>($data_dosen[nip])</td></tr>";
		echo "<tr class=\"genap\"><td>Password Approval</td>";
		echo "<td>";
		?>
		<input type="text" name="dosenpass" class="inputpass" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" />
		<?php
		echo "</td></tr>";
		echo "<tr class=\"ganjil\"><td colspan=\"2\">atau</td></tr>";
		echo "<tr class=\"genap\"><td>";
		echo "Masukkan OTP Dosen/Dokter/Residen</td><td>";
		?>
		<input name="dosenpin" autocomplete="off" type="text" style="width:250px"/><br>
		<?php
		echo "</td></tr>";
		echo "<tr class=\"ganjil\"><td colspan=\"2\">atau</td></tr>";
		echo "<tr class=\"genap\"><td>";
		echo "Scanning QR Code<br><font style=\"font-size:0.625em\"><i>(gunakan smartphone)</i></font></td><td>";
		?>
		<input type=text name="dosenqr" size=16 placeholder="Tracking QR-Code" class=qrcode-text style="width:250px" />
		<label class=qrcode-text-btn>
		  <input type=file accept="image/*" capture=environment onchange="openQRCamera(this);" tabindex=-1>
		</label>
		<?php
		echo "</td></tr></table><br><br>";

		echo "<input type=\"submit\" class=\"submit1\" name=\"cancel\" value=\"CANCEL\" formnovalidate>";
		echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" class=\"submit1\" name=\"approve\" value=\"APPROVE\">";
    echo "</form>";

		if ($_POST[cancel]=="CANCEL")
		{
			echo "
			<script>
				window.location.href=\"penilaian_kdk.php\";
			</script>
			";
		}

		if ($_POST[approve]=="APPROVE")
		{
			$kasus = mysqli_fetch_array(mysqli_query($con,"SELECT `dosen` FROM `kdk_nilai_kasus` WHERE `id`='$_POST[id]'"));
			$user_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `password` FROM `admin` WHERE `username`='$kasus[dosen]'"));
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `pin`,`qr` FROM `dosen` WHERE `nip`='$kasus[dosen]'"));
			$dosenpass_md5 = md5($_POST['dosenpass']);
			if (($_POST['dosenpass']!="" AND $dosenpass_md5==$user_dosen['password'])
			 OR ($_POST[dosenpin]!="" AND $_POST[dosenpin]==$data_dosen[pin])
			 OR ($_POST[dosenqr]!="" AND $_POST[dosenqr]==$data_dosen[qr]))
			{
				$aspek1 = number_format($_POST[aspek_1],2);
				$aspek2 = number_format($_POST[aspek_2],2);
				$aspek3 = number_format($_POST[aspek_3],2);
				$aspek4 = number_format($_POST[aspek_4],2);
				$aspek5 = number_format($_POST[aspek_5],2);

				$nilai_rata = 0.2*$_POST[aspek_1] + 0.2*$_POST[aspek_2] + 0.2*$_POST[aspek_3] + 0.2*$_POST[aspek_4] + 0.2*$_POST[aspek_5];
				$nilai_rata = number_format($nilai_rata,2);

				$umpan_balik = addslashes($_POST[umpan_balik]);
				$saran = addslashes($_POST[saran]);

				$update_kasus=mysqli_query($con,"UPDATE `kdk_nilai_kasus` SET
					`aspek_1`='$aspek1',`aspek_2`='$aspek2',`aspek_3`='$aspek3',`aspek_4`='$aspek4',`aspek_5`='$aspek5',`nilai_rata`='$nilai_rata',
					`umpan_balik`='$umpan_balik',`saran`='$saran',
					`tgl_approval`='$tgl',`status_approval`='1'
					WHERE `id`='$_POST[id]'");
					echo "
					<script>
					alert(\"Approval SUKSES ...\");
					window.location.href = \"penilaian_kdk.php\";
	        </script>
					";
			}
			else
			{
				echo "
				<script>
				alert(\"Approval GAGAL ...\");
				window.location.href = \"approve_kasus.php?id=\"+\"$_POST[id]\";
        </script>
				";
			}
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
      var aspek1 = document.getElementById('aspek_1').value;
			var aspek2 = document.getElementById('aspek_2').value;
			var aspek3 = document.getElementById('aspek_3').value;
			var aspek4 = document.getElementById('aspek_4').value;
			var aspek5 = document.getElementById('aspek_5').value;

      var result = 0.2*parseFloat(aspek1) + 0.2*parseFloat(aspek2) + 0.2*parseFloat(aspek3) + 0.2*parseFloat(aspek4) + 0.2*parseFloat(aspek5);
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
