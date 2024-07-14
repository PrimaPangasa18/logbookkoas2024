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
			echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">FORMULIR NILAI CASE BASED DISCUSSION (CBD)</font></h4>";

			$id = $_GET[id];
			$id_stase = "M121";
			$data_stase = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
			$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
			$stase_id = "stase_".$id_stase;
			$data_stase_mhsw = mysqli_query($con,"SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'");
			$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);
			$data_cbd = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kompre_nilai_cbd` WHERE `id`='$id'"));

			echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
			echo "<input type=\"hidden\" name=\"id_stase\" value=\"$id_stase\">";
			echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
			echo "<table border=1 style=\"width:75%;background:rgb(244, 241, 217);\">";

			//Judul Kasus
			echo "<tr>";
				echo "<td style=\"width:40%\">Judul Kasus</td>";
				echo "<td style=\"width:60%\"><textarea name=\"kasus\" value=\"$data_cbd[kasus]\" style=\"width:97%;font-family:Tahoma;font-size:1em\" required>$data_cbd[kasus]</textarea></td>";
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
			//Pengajuan
			echo "<tr>";
				echo "<td colspan=2>Diajukan pada:</td>";
			echo "</tr>";
			//Tgl isi kegiatan
			echo "<tr>";
				echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;Tanggal isi kegiatan (yyyy-mm-dd)</td>";
				echo "<td><input type=\"text\" name=\"tgl_isi\" class=\"tgl_isi\" value=\"$data_cbd[tgl_isi]\" style=\"font-size:0.85em\" required/></td>";
			echo "</tr>";
			//Jam kegiatan
			echo "<tr>";
				echo "<td>&nbsp;&nbsp;&nbsp;&nbsp;Jam isi kegiatan</td>";
				echo "<td>";
					$jam_isi = substr($data_cbd[jam_isi],0,2);
					$menit_isi = substr($data_cbd[jam_isi],3,2);
					$lokal_isi = substr($data_cbd[jam_isi],6,strlen($data_cbd[jam_isi])-6);
					echo "<select class=\"select_short\" name=\"jam_isi\" id=\"jam_isi\" required>";
					$jam = mysqli_query($con,"SELECT `jam` FROM `jam` ORDER BY `id`");
					echo "<option value=\"$jam_isi\">$jam_isi</option>";
					while ($dat_jam=mysqli_fetch_array($jam))
					echo "<option value=\"$dat_jam[jam]\">$dat_jam[jam]</option>";
					echo "</select>&nbsp;&nbsp;:&nbsp;&nbsp;";
					echo "<select class=\"select_short\" name=\"menit_isi\" id=\"menit_isi\" required>";
					$menit = mysqli_query($con,"SELECT `menit` FROM `menit` ORDER BY `id`");
					echo "<option value=\"$menit_isi\">$menit_isi</option>";
					while ($dat_menit=mysqli_fetch_array($menit))
					echo "<option value=\"$dat_menit[menit]\">$dat_menit[menit]</option>";
					echo "</select>&nbsp;&nbsp;-&nbsp;&nbsp;";
					echo "<select class=\"select_short\" name=\"lokal_isi\" id=\"lokal_isi\" required>";
					echo "<option value=\"$lokal_isi\">$lokal_isi</option>";
					echo "<option value=\"WIB\">WIB</option>";
					echo "<option value=\"WITA\">WITA</option>";
					echo "<option value=\"WIT\">WIT</option>";
					echo "</select>";
				echo "</td>";
			echo "</tr>";
			//Dosen Pembimbing Lapangan
			echo "<tr>";
				echo "<td>Dosen Pembimbing Lapangan</td>";
				echo "<td>";
				echo "<select class=\"select_art\" name=\"dosen\" id=\"dosen\" required>";
				$dosen = mysqli_query($con,"SELECT `username`,`nama` FROM `admin` WHERE `level`='4' OR (`level`='6' AND `stase`='$id_stase') ORDER BY `nama`");
				$datadosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_cbd[dosen]'"));
				echo "<option value=\"$data_cbd[dosen]\">$datadosen[nama], $datadosen[gelar] ($datadosen[nip])</option>";
				while ($data=mysqli_fetch_array($dosen))
				{
					$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data[username]'"));
					echo "<option value=\"$data[username]\">$data_dosen[nama], $data_dosen[gelar] ($data_dosen[nip])</option>";
				}
				echo "</select>";
				echo "</td>";
			echo "</tr>";
			echo "</table><br><br>";

			//Form nilai
			echo "<table border=1 style=\"width:75%;background:rgb(244, 241, 217);\">";
			echo "<tr><td><b>Form Penilaian:</b></td></tr>";
			echo "</table>";
			echo "<table border=1 style=\"width:75%;background:rgb(244, 241, 217);\">";
			echo "<thead>";
			 	echo "<th style=\"width:5%\">No</th>";
				echo "<th style=\"width:65%\">Aspek Yang Dinilai</th>";
				echo "<th style=\"width:15%\">Bobot</th>";
				echo "<th style=\"width:15%\">Nilai (0-100)</th>";
			echo "</thead>";
			//No 1
			echo "<tr>";
				echo "<td align=center>1</td>";
				echo "<td>Kemampuan Anamnesis dan Pemeriksaan Fisik</td>";
				echo "<td align=center>25%</td>";
				echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_1\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_cbd[aspek_1]\" id=\"aspek_1\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
			echo "</tr>";
			//No 2
			echo "<tr>";
				echo "<td align=center>2</td>";
				echo "<td>Kemampuan Keputusan klinis (usulan pemeriksaan penunjang level FKTP dan penegakan diagnosis)</td>";
				echo "<td align=center>25%</td>";
				echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_2\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_cbd[aspek_2]\" id=\"aspek_2\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
			echo "</tr>";
			//No 3
			echo "<tr>";
				echo "<td align=center>3</td>";
				echo "<td>Kemampuan Penatalaksanaan Pasien (resep)</td>";
				echo "<td align=center>25%</td>";
				echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_3\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_cbd[aspek_3]\" id=\"aspek_3\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
			echo "</tr>";
			//No 4
			echo "<tr>";
				echo "<td align=center>4</td>";
				echo "<td>Kemampuan edukasi, komunikasi dan profesionalisme</td>";
				echo "<td align=center>25%</td>";
				echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_4\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_cbd[aspek_4]\" id=\"aspek_4\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
			echo "</tr>";
			//Rata-Rata Nilai
			echo "<tr>";
				echo "<td colspan=3 align=right>Rata-Rata Nilai (Jumlah Bobot x Nilai)</td>";
				echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_rata\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"nilai_rata\" value=\"$data_cbd[nilai_rata]\" id=\"nilai_rata\" required/></td>";
			echo "</tr>";
			echo "</table><br>";

			//Umpan Balik
			echo "<table border=1 style=\"width:75%;background:rgb(244, 241, 217);\">";
			echo "<tr>";
			  echo "<td>Umpan Balik:<br><textarea name=\"umpan_balik\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\">$data_cbd[umpan_balik]</textarea></td>";
			echo "</tr>";
			echo "<tr>";
			  echo "<td>Saran:<br><textarea name=\"saran\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\">$data_cbd[saran]</textarea></td>";
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
				$kasus = addslashes($_POST[kasus]);
				$jam_isi = $_POST[jam_isi].":".$_POST[menit_isi]." ".$_POST[lokal_isi];
				$aspek_1 = number_format($_POST[aspek_1],2);
				$aspek_2 = number_format($_POST[aspek_2],2);
				$aspek_3 = number_format($_POST[aspek_3],2);
				$aspek_4 = number_format($_POST[aspek_4],2);

				$nilai_rata = 0.25*$_POST[aspek_1] + 0.25*$_POST[aspek_2] + 0.25*$_POST[aspek_3] + 0.25*$_POST[aspek_4];
				$nilai_rata = number_format($nilai_rata,2);

				$umpan_balik = addslashes($_POST[umpan_balik]);
				$saran = addslashes($_POST[saran]);

				$update_cbd = mysqli_query($con,"UPDATE `kompre_nilai_cbd` SET
					`dosen`='$_POST[dosen]',`kasus`='$kasus',
					`aspek_1`='$aspek_1',`aspek_2`='$aspek_2',
					`aspek_3`='$aspek_3',`aspek_4`='$aspek_4',`nilai_rata`='$nilai_rata',
					`umpan_balik`='$umpan_balik',`saran`='$saran',
					`tgl_isi`='$_POST[tgl_isi]',`jam_isi`='$jam_isi',
					`tgl_approval`='2000-01-01',`status_approval`='0'
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
			$('.tgl_isi').datepicker({ dateFormat: 'yy-mm-dd' });
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
		$("#kasus").select2({
			 placeholder: "<< Pilihan Kasus >>",
	     allowClear: true
		 });
		$("#jam_isi").select2({
			placeholder: "< Jam >",
	    allowClear: true
		});
		$("#menit_isi").select2({
			placeholder: "< Menit >",
		  allowClear: true
		});
		$("#lokal_isi").select2({
			placeholder: "< Zona Waktu >",
		  allowClear: true
		});
		$("#dosen").select2({
			 placeholder: "<< Dosen Pembimbing Lapangan >>",
	     allowClear: true
		 });
	 });
	</script>

	<script>
	function sum() {
	      var aspek1 = document.getElementById('aspek_1').value;
				var aspek2 = document.getElementById('aspek_2').value;
				var aspek3 = document.getElementById('aspek_3').value;
				var aspek4 = document.getElementById('aspek_4').value;
				var result = 0.25*parseFloat(aspek1) + 0.25*parseFloat(aspek2) + 0.25*parseFloat(aspek3) + 0.25*parseFloat(aspek4);
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
