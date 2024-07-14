<HTML>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="../menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../mytable.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../qr_code.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../text-security-master/dist/text-security.css" type="text/css" media="screen" />
	<link rel="stylesheet" type="text/css" href="../jquery_ui/jquery-ui.css">
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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN (STASE) ILMU KESEHATAN MATA</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">APPROVAL NILAI PRESENTASI JOURNAL READING<p>Kepaniteraan (Stase) Ilmu Kesehatan Mata</font></h4>";

		$id_stase = "M104";
		$id = $_GET['id'];
		$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
		$stase_id = "stase_".$id_stase;
		$data_stase_mhsw = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'");
		$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);
		$data_jurnal = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `mata_nilai_jurnal` WHERE `id`='$id'"));

		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
		echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";

		//Nama mahasiswa koas
		echo "<tr>";
			echo "<td>Nama Mahasiswa</td>";
			echo "<td>$data_mhsw[nama]</td>";
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
		//Tanggal Penyajian
		echo "<tr>";
			echo "<td class=\"td_mid\">Tanggal Penyajian (yyyy-mm-dd)</td>";
			echo "<td class=\"td_mid\"><input type=\"text\" class=\"tanggal_penyajian\" name=\"tanggal_penyajian\" style=\"font-size:1em;font-family:TAHOMA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" value=\"$data_jurnal[tgl_penyajian]\" /></td>";
		echo "</tr>";
		//Judul
		echo "<tr>";
			echo "<td>Judul Artikel</td>";
			echo "<td><textarea name=\"judul_presentasi\" style=\"width:97%;font-family:Tahoma;font-size:1em\" placeholder=\"<< Judul Artikel >>\" required>$data_jurnal[judul_presentasi]</textarea></td>";
		echo "</tr>";
		//Dosen Penilai
		echo "<tr>";
			echo "<td>Dosen Penilai</td>";
			echo "<td>";
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_jurnal[dosen]'"));
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
			echo "<th style=\"width:55%\">Komponen Penilaian</th>";
			echo "<th style=\"width:20%\">Bobot</th>";
			echo "<th style=\"width:20%\">Nilai (0-100)</th>";
		echo "</thead>";
		//1. Cara Penyajian
		echo "<tr>";
			echo "<td align=center>1</td>";
			echo "<td colspan=3>Cara Penyajian:</td>";
		echo "</tr>";
		//No 1.1
		echo "<tr>";
			echo "<td align=center>&nbsp;</td>";
			echo "<td>1.1. Penampilan</td>";
			echo "<td align=center>10%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek1\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_jurnal[aspek_1]\" id=\"aspek1\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 1.2
		echo "<tr>";
			echo "<td align=center>&nbsp;</td>";
			echo "<td>1.2. Penyampaian</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek2\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_jurnal[aspek_2]\" id=\"aspek2\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 1.3
		echo "<tr>";
			echo "<td align=center>&nbsp;</td>";
			echo "<td>1.3. Makalah</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek3\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_jurnal[aspek_3]\" id=\"aspek3\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//2. Penguasaan Materi
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>Penguasaan Materi</td>";
			echo "<td align=center>30%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek4\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_jurnal[aspek_4]\" id=\"aspek4\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//3. Pengetahuan Teori / Penunjang
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>Pengetahuan Teori / Penunjang</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek5\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_jurnal[aspek_5]\" id=\"aspek5\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//Nilai Total
		echo "<tr>";
			echo "<td colspan=3 align=right>Nilai Total (Jumlah Bobot x Nilai)</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_total\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_jurnal[nilai_total]\" id=\"nilai_total\" required/></td>";
		echo "</tr>";
		echo "</table><br>";

		//Umpan Balik
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr>";
		  echo "<td>Umpan Balik:<br><textarea name=\"umpan_balik\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\">$data_jurnal[umpan_balik]</textarea></td>";
		echo "</tr>";
		echo "<tr>";
		  echo "<td>Saran:<br><textarea name=\"saran\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\">$data_jurnal[saran]</textarea></td>";
		echo "</tr>";
		echo "</table><br>";

		//Nilai Penyanggah
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr><td><b>Penilaian Penyanggah:</b></td></tr>";
		echo "</table>";
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<thead>";
		 	echo "<th style=\"width:5%\">No</th>";
			echo "<th style=\"width:75%\">Nama / NIM Mahasiswa </th>";
			echo "<th style=\"width:20%\">Nilai (0-100)</th>";
		echo "</thead>";
		//Penyanggah 1-5
		$i=1;
		while ($i<6)
		{
			echo "<tr>";
				echo "<td align=center class=\"td_mid\">$i</td>";
				echo "<td class=\"td_mid\">Penyanggah-$i: ";
					$penyanggah = "penyanggah"."$i";
					$nilai = "nilai"."$i";
					$penyanggah_i = "penyanggah_"."$i";
					$nilai_penyanggah_i = "nilai_penyanggah_"."$i";
					echo "<select class=\"select_art\" name=\"$penyanggah\" id=\"$penyanggah\" >";
					if ($data_jurnal[$penyanggah_i]=="-")
						echo "<option value=\"\">< Penyanggah-$i ></option>";
					else
					{
						$mhsw_i = mysqli_fetch_array(mysqli_query($con,"SELECT `nim`,`nama` FROM `biodata_mhsw` WHERE `nim`='$data_jurnal[$penyanggah_i]'"));
						echo "<option value=\"$mhsw_i[nim]\">$mhsw_i[nama] ($mhsw_i[nim])</option>";
					}
					$mhsw = mysqli_query($con,"SELECT `nim`,`nama` FROM `biodata_mhsw` ORDER BY `nama`");
					while ($data_penyanggah=mysqli_fetch_array($mhsw))
					echo "<option value=\"$data_penyanggah[nim]\">$data_penyanggah[nama] ($data_penyanggah[nim])</option>";
					echo "</select>";
				echo "</td>";
				if ($data_jurnal[$nilai_penyanggah_i]=="-") echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"$nilai\" style=\"width:100%;font-size:0.85em;text-align:center\" placeholder=\"0-100\" /></td>";
				else echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"$nilai\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_jurnal[$nilai_penyanggah_i]\" /></td>";
			echo "</tr>";
			$i++;
		}
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
				window.location.href=\"penilaian_mata.php\";
			</script>
			";
		}

		if ($_POST[approve]=="APPROVE")
		{
			$dosen_jurnal = mysqli_fetch_array(mysqli_query($con,"SELECT `dosen` FROM `mata_nilai_jurnal` WHERE `id`='$_POST[id]'"));
			$user_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `password` FROM `admin` WHERE `username`='$dosen_jurnal[dosen]'"));
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `pin`,`qr` FROM `dosen` WHERE `nip`='$dosen_jurnal[dosen]'"));
			$dosenpass_md5 = md5($_POST['dosenpass']);
			if (($_POST['dosenpass']!="" AND $dosenpass_md5==$user_dosen['password'])
			 OR ($_POST[dosenpin]!="" AND $_POST[dosenpin]==$data_dosen[pin])
			 OR ($_POST[dosenqr]!="" AND $_POST[dosenqr]==$data_dosen[qr]))
			{
				$aspek1 = number_format($_POST[aspek1],2);
				$aspek2 = number_format($_POST[aspek2],2);
				$aspek3 = number_format($_POST[aspek3],2);
				$aspek4 = number_format($_POST[aspek4],2);
				$aspek5 = number_format($_POST[aspek5],2);

				if (!empty($_POST[nilai1])) $nilai_penyanggah1 = number_format($_POST[nilai1],2);
				else $nilai_penyanggah1 = "-";
				if (!empty($_POST[nilai2])) $nilai_penyanggah2 = number_format($_POST[nilai2],2);
				else $nilai_penyanggah2 = "-";
				if (!empty($_POST[nilai3])) $nilai_penyanggah3 = number_format($_POST[nilai3],2);
				else $nilai_penyanggah3 = "-";
				if (!empty($_POST[nilai4])) $nilai_penyanggah4 = number_format($_POST[nilai4],2);
				else $nilai_penyanggah4 = "-";
				if (!empty($_POST[nilai5])) $nilai_penyanggah5 = number_format($_POST[nilai5],2);
				else $nilai_penyanggah5 = "-";

				if (!empty($_POST[penyanggah1]) AND $_POST[penyanggah1]!="") $penyanggah1 = $_POST[penyanggah1];
				else {$penyanggah1 = "-";$nilai_penyanggah1 = "-";}
				if (!empty($_POST[penyanggah2]) AND $_POST[penyanggah2]!="") $penyanggah2 = $_POST[penyanggah2];
				else {$penyanggah2 = "-";$nilai_penyanggah2 = "-";}
				if (!empty($_POST[penyanggah3]) AND $_POST[penyanggah3]!="") $penyanggah3 = $_POST[penyanggah3];
				else {$penyanggah3 = "-";$nilai_penyanggah3 = "-";}
				if (!empty($_POST[penyanggah4]) AND $_POST[penyanggah4]!="") $penyanggah4 = $_POST[penyanggah4];
				else {$penyanggah4 = "-";$nilai_penyanggah4 = "-";}
				if (!empty($_POST[penyanggah5]) AND $_POST[penyanggah5]!="") $penyanggah5 = $_POST[penyanggah5];
				else {$penyanggah5 = "-";$nilai_penyanggah5 = "-";}

				$judul = addslashes($_POST[judul_presentasi]);

				$total_nilai = $_POST[aspek1]*0.1 + $_POST[aspek2]*0.2 + $_POST[aspek3]*0.2 + $_POST[aspek4]*0.3 + $_POST[aspek5]*0.2;
				$total = number_format($total_nilai,2);

				$umpan_balik = addslashes($_POST[umpan_balik]);
				$saran = addslashes($_POST[saran]);

				$update_jurnal=mysqli_query($con,"UPDATE `mata_nilai_jurnal` SET
					`judul_presentasi`='$judul',`tgl_penyajian`='$_POST[tanggal_penyajian]',
					`aspek_1`='$aspek1',`aspek_2`='$aspek2',`aspek_3`='$aspek3',
					`aspek_4`='$aspek4',`aspek_5`='$aspek5',`nilai_total`='$total',
					`umpan_balik`='$umpan_balik',`saran`='$saran',
					`penyanggah_1`='$penyanggah1',`nilai_penyanggah_1`='$nilai_penyanggah1',
					`penyanggah_2`='$penyanggah2',`nilai_penyanggah_2`='$nilai_penyanggah2',
					`penyanggah_3`='$penyanggah3',`nilai_penyanggah_3`='$nilai_penyanggah3',
					`penyanggah_4`='$penyanggah4',`nilai_penyanggah_4`='$nilai_penyanggah4',
					`penyanggah_5`='$penyanggah5',`nilai_penyanggah_5`='$nilai_penyanggah5',
					`tgl_approval`='$tgl', `status_approval`='1'
					WHERE `id`='$_POST[id]'");

				//Penyanggah 1-5
				$i = 1;
				while ($i<6)
				{
					if (!empty($_POST["penyanggah"."$i"]) AND $_POST["penyanggah"."$i"]!="")
					{
						$penyanggah = $_POST["penyanggah"."$i"];
						$data_penyanggah = mysqli_query($con,"SELECT `id` FROM `mata_nilai_penyanggah` WHERE `nim`='$penyanggah' AND `dosen`='$_POST[dosen]' AND `presenter`='$_COOKIE[user]' AND `jenis_presentasi`='Journal Reading'");
						$cek_penyanggah = mysqli_num_rows($data_penyanggah);
						$nilai_penyanggah = number_format($_POST["nilai"."$i"],2);
						if ($cek_penyanggah>0)
						{
							$data = mysqli_fetch_array($data_penyanggah);
							$update_penyanggah=mysqli_query($con,"UPDATE `mata_nilai_penyanggah` SET
								`id_presentasi`='$_POST[id]',
								`tgl_presentasi`='$_POST[tanggal_penyajian]',
								`judul_presentasi`='$judul',
								`nilai`='$nilai_penyanggah',
								`tgl_approval`='$tgl',
								`status_approval`='1' WHERE `id`='$data[id]'");
						}
						else
						{
							$insert_penyanggah=mysqli_query($con,"INSERT INTO `mata_nilai_penyanggah`
								( `id_presentasi`,`nim`, `presenter`, `jenis_presentasi`, `tgl_presentasi`,
									`judul_presentasi`, `dosen`, `nilai`,
									`tgl_approval`, `status_approval`)
								VALUES
								( '$_POST[id]','$penyanggah','$_COOKIE[user]','Journal Reading','$_POST[tanggal_penyajian]',
									'$judul','$dosen_jurnal[dosen]','$nilai_penyanggah',
									'$tgl','1')");
						}
					}
					$i++;
				}


				echo "
					<script>
					alert(\"Approval SUKSES ...\");
					window.location.href = \"penilaian_mata.php\";
	        </script>
					";
			}
			else
			{
				echo "
				<script>
				alert(\"Approval GAGAL ...\");
				window.location.href = \"approve_jurnal.php?id=\"+\"$_POST[id]\";
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
<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
<script src="../select2/dist/js/select2.js"></script>
<script>
$(document).ready(function() {
	$("#dosen").select2({
		 placeholder: "<< Dosen Pembimbing/Penguji >>",
     allowClear: true
	 });
	$("#penyanggah1").select2({
		 placeholder: "< Penyanggah-1 >",
		 allowClear: true
		});
	$("#penyanggah2").select2({
		 placeholder: "< Penyanggah-2 >",
		 allowClear: true
		});
	$("#penyanggah3").select2({
		 placeholder: "< Penyanggah-3 >",
		 allowClear: true
		});
	$("#penyanggah4").select2({
		 placeholder: "< Penyanggah-4 >",
		 allowClear: true
		});
	$("#penyanggah5").select2({
		 placeholder: "< Penyanggah-5 >",
		 allowClear: true
		});

 });
</script>
<script type="text/javascript" src="../jquery_ui/jquery-ui.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.tanggal_penyajian').datepicker({ dateFormat: 'yy-mm-dd' });
	});
</script>

<script>
function sum() {
      var aspek1 = document.getElementById('aspek1').value;
			var aspek2 = document.getElementById('aspek2').value;
			var aspek3 = document.getElementById('aspek3').value;
			var aspek4 = document.getElementById('aspek4').value;
			var aspek5 = document.getElementById('aspek5').value;
      var result = 0.1*parseFloat(aspek1) + 0.2*parseFloat(aspek2) + 0.2*parseFloat(aspek3) + 0.3*parseFloat(aspek4) + 0.2*parseFloat(aspek5);
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
