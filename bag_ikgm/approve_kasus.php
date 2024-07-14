<HTML>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="../menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../mytable.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../qr_code.css" type="text/css" media="screen" />
	<link rel="stylesheet" type="text/css" href="../jquery_ui/jquery-ui.css">
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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) IKGM</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">APPROVAL NILAI LAPORAN KASUS<p>Kepaniteraan (Stase) IKGM</font></h4>";

		$id = $_GET['id'];
		$data_kasus = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `ikgm_nilai_kasus` WHERE `id`='$id'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_kasus[nim]'"));

		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";

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
		///Tanggal Presentasi
		echo "<tr>";
			echo "<td class=\"td_mid\">Tanggal Ujian/Presentasi (yyyy-mm-dd)</td>";
			echo "<td class=\"td_mid\"><input type=\"text\" class=\"tanggal_ujian\" name=\"tanggal_ujian\" style=\"font-size:1em;font-family:TAHOMA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" value=\"$data_kasus[tgl_ujian]\" /></td>";
		echo "</tr>";
		//Dosen Pembimbing
		echo "<tr>";
			echo "<td>Dosen Pembimbing</td>";
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_kasus[dosen]'"));
			echo "<td>$data_dosen[nama], $data_dosen[gelar] ($data_dosen[nip])</td>";
		echo "</tr>";
		//Nama/Judul Kasus
		echo "<tr>";
			echo "<td>Nama/Judul Kasus</td>";
			echo "<td><textarea name=\"nama_kasus\" style=\"width:97%;font-family:Tahoma;font-size:1em\" required>$data_kasus[nama_kasus]</textarea></td>";
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
			echo "<td>Kehadiran Presentasi</td>";
			echo "<td>";
			if ($data_kasus[aspek_1]=="0")
				echo "<input type=\"radio\" name=\"aspek_1\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;0 - Tidak Hadir<br>";
			else echo "<input type=\"radio\" name=\"aspek_1\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;0 - Tidak Hadir<br>";
			if ($data_kasus[aspek_1]=="1")
				echo "<input type=\"radio\" name=\"aspek_1\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;1 - Hadir, terlambat<br>";
			else echo "<input type=\"radio\" name=\"aspek_1\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;1 - Hadir, terlambat<br>";
			if ($data_kasus[aspek_1]=="2")
				echo "<input type=\"radio\" name=\"aspek_1\" value=\"2\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;2 - Hadir tepat waktu<br>";
			else echo "<input type=\"radio\" name=\"aspek_1\" value=\"2\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;2 - Hadir tepat waktu<br>";
			echo "</td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>Aktifitas saat diskusi<br>(<i>Dinilai dari frekuensi mengajukan masukan / komentar / pendapat / jawaban</i>)</td>";
			echo "<td>";
			if ($data_kasus[aspek_2]=="1")
				echo "<input type=\"radio\" name=\"aspek_2\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;1 - Pasif<br>";
			else echo "<input type=\"radio\" name=\"aspek_2\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;1 - Pasif<br>";
			if ($data_kasus[aspek_2]=="2")
				echo "<input type=\"radio\" name=\"aspek_2\" value=\"2\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;2 - Kurang aktif<br>";
			else echo "<input type=\"radio\" name=\"aspek_2\" value=\"2\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;2 - Kurang aktif<br>";
			if ($data_kasus[aspek_2]=="3")
				echo "<input type=\"radio\" name=\"aspek_2\" value=\"3\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;3 - Cukup aktif<br>";
			else echo "<input type=\"radio\" name=\"aspek_2\" value=\"3\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;3 - Cukup aktif<br>";
			if ($data_kasus[aspek_2]=="4")
				echo "<input type=\"radio\" name=\"aspek_2\" value=\"4\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;4 - Lebih aktif<br>";
			else echo "<input type=\"radio\" name=\"aspek_2\" value=\"4\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;4 - Lebih aktif<br>";
			if ($data_kasus[aspek_2]=="5")
				echo "<input type=\"radio\" name=\"aspek_2\" value=\"5\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;5 - Sangat aktif";
			else echo "<input type=\"radio\" name=\"aspek_2\" value=\"5\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;5 - Sangat aktif";
			echo "</td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>Ketrampilan presentasi / berkomunikasi<br>(<i>Dinilai dari kemampuan berinteraksi dengan peserta lain dan menjaga etika</i>)</td>";
			echo "<td>";
			if ($data_kasus[aspek_3]=="1")
			  echo "<input type=\"radio\" name=\"aspek_3\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;1 - Sangat kurang<br>";
			else echo "<input type=\"radio\" name=\"aspek_3\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;1 - Sangat kurang<br>";
			if ($data_kasus[aspek_3]=="2")
			  echo "<input type=\"radio\" name=\"aspek_3\" value=\"2\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;2 - Kurang<br>";
			else echo "<input type=\"radio\" name=\"aspek_3\" value=\"2\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;2 - Kurang<br>";
			if ($data_kasus[aspek_3]=="3")
			  echo "<input type=\"radio\" name=\"aspek_3\" value=\"3\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;3 - Cukup<br>";
			else echo "<input type=\"radio\" name=\"aspek_3\" value=\"3\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;3 - Cukup<br>";
			if ($data_kasus[aspek_3]=="4")
			  echo "<input type=\"radio\" name=\"aspek_3\" value=\"4\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;4 - Baik<br>";
			else echo "<input type=\"radio\" name=\"aspek_3\" value=\"4\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;4 - Baik<br>";
			if ($data_kasus[aspek_3]=="5")
			  echo "<input type=\"radio\" name=\"aspek_3\" value=\"5\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;5 - Sangat baik";
			else echo "<input type=\"radio\" name=\"aspek_3\" value=\"5\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;5 - Sangat baik";
			echo "</td>";
		echo "</tr>";
		//No 4
		echo "<tr>";
			echo "<td align=center>4</td>";
			echo "<td>Kelengkapan laporan dan materi presentasi<br>(<i>Dinilai dari materi presentasi yang disiapkan dan disajikan, serta kelengkapan isi dan kerapian makalah / laporan yang dikumpulkan</i>)</td>";
			echo "<td>";
			if ($data_kasus[aspek_4]=="1")
			  echo "<input type=\"radio\" name=\"aspek_4\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;1 - Sangat kurang<br>";
			else echo "<input type=\"radio\" name=\"aspek_4\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;1 - Sangat kurang<br>";
			if ($data_kasus[aspek_4]=="2")
			  echo "<input type=\"radio\" name=\"aspek_4\" value=\"2\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;2 - Kurang<br>";
			else echo "<input type=\"radio\" name=\"aspek_4\" value=\"2\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;2 - Kurang<br>";
			if ($data_kasus[aspek_4]=="3")
			  echo "<input type=\"radio\" name=\"aspek_4\" value=\"3\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;3 - Cukup<br>";
			else echo "<input type=\"radio\" name=\"aspek_4\" value=\"3\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;3 - Cukup<br>";
			if ($data_kasus[aspek_4]=="4")
			  echo "<input type=\"radio\" name=\"aspek_4\" value=\"4\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;4 - Baik<br>";
			else echo "<input type=\"radio\" name=\"aspek_4\" value=\"4\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;4 - Baik<br>";
			if ($data_kasus[aspek_4]=="5")
			  echo "<input type=\"radio\" name=\"aspek_4\" value=\"5\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;5 - Sangat baik";
			else echo "<input type=\"radio\" name=\"aspek_4\" value=\"5\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;5 - Sangat baik";
			echo "</td>";
		echo "</tr>";
		//Total Nilai
		echo "<tr>";
			echo "<td colspan=2 align=right>Total Nilai (10 x Jumlah Poin / 1.7)</td>";
			echo "<td><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_total\" style=\"width:30%;font-size:0.85em;text-align:center\" value=\"$data_kasus[nilai_total]\" id=\"nilai_total\" required/></td>";
		echo "</tr>";
		echo "</table><br><br>";

		//Catatan
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr><td><b>Catatan Dosen Pembimbing Terhadap Kegiatan:</b></td></tr>";
		echo "<tr>";
			echo "<td>Catatan:<br><textarea name=\"catatan\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\">$data_kasus[catatan]</textarea></td>";
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
				window.location.href=\"penilaian_ikgm.php\";
			</script>
			";
		}

		if ($_POST[approve]=="APPROVE")
		{
			$dosen_kasus = mysqli_fetch_array(mysqli_query($con,"SELECT `dosen` FROM `ikgm_nilai_kasus` WHERE `id`='$_POST[id]'"));
			$user_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `password` FROM `admin` WHERE `username`='$dosen_kasus[dosen]'"));
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `pin`,`qr` FROM `dosen` WHERE `nip`='$dosen_kasus[dosen]'"));
			$dosenpass_md5 = md5($_POST['dosenpass']);
			if (($_POST['dosenpass']!="" AND $dosenpass_md5==$user_dosen['password'])
			 OR ($_POST[dosenpin]!="" AND $_POST[dosenpin]==$data_dosen[pin])
			 OR ($_POST[dosenqr]!="" AND $_POST[dosenqr]==$data_dosen[qr]))
			{
				$nama_kasus = addslashes($_POST[nama_kasus]);
				$catatan = addslashes($_POST[catatan]);

				$nilai_total = ($_POST[aspek_1]+$_POST[aspek_2]+$_POST[aspek_3]+$_POST[aspek_4])/1.7;
				$nilai_total = number_format(10*$nilai_total,2);

				$update_kasus=mysqli_query($con,"UPDATE `ikgm_nilai_kasus` SET
					`tgl_ujian`='$_POST[tanggal_ujian]',`nama_kasus`='$nama_kasus',
					`aspek_1`='$_POST[aspek_1]',`aspek_2`='$_POST[aspek_2]',
					`aspek_3`='$_POST[aspek_3]',`aspek_4`='$_POST[aspek_4]',
					`catatan`='$catatan',`nilai_total`='$nilai_total',
					`tgl_approval`='$tgl',`status_approval`='1'
					WHERE `id`='$_POST[id]'");
				
				echo "
					<script>
					alert(\"Approval SUKSES ...\");
					window.location.href = \"penilaian_ikgm.php\";
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

			var total = parseInt(aspek1) + parseInt(aspek2) + parseInt(aspek3) + parseInt(aspek4);
			var result = 10*parseInt(total)/1.7;

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
