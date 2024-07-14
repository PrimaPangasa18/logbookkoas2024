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

		echo "<div class=\"text_header\">PENILAIAN KEPANITERAAN KOMPREHENSIP</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4 id=\"top\"><font style=\"color:#006400;text-shadow:1px 1px black;\">APPROVAL NILAI PORTOFOLIO LAPORAN<p>Kepaniteraan Komprehensip di Rumah Sakit / Puskesmas</font></h4>";

		$id_stase = "M121";
		$id = $_GET['id'];
		$data_laporan = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `kompre_nilai_laporan` WHERE `id`='$id'"));
		$data_mhsw = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_laporan[nim]'"));

		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
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
			echo "<td style=\"width:60%\">$data_laporan[instansi]</td>";
		echo "</tr>";
		//Lokasi Puskesmas/Klinik
		echo "<tr>";
			echo "<td>Nama Rumah Sakit / Puskesmas</td>";
			echo "<td>$data_laporan[lokasi]</td>";
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
			$mulai = tanggal_indo($data_laporan[tgl_mulai]);
			echo "<td>$mulai</td>";
		echo "</tr>";
		//Tgl selesai kegiatan
		echo "<tr>";
			echo "<td>Tanggal selesai kegiatan</td>";
			$selesai = tanggal_indo($data_laporan[tgl_selesai]);
			echo "<td>$selesai</td>";
		echo "</tr>";
		//Dosen Pembimbing Lapangan
		echo "<tr>";
			echo "<td>Dosen Pembimbing Lapangan</td>";
			echo "<td>";
			$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_laporan[dosen]'"));
			echo "$data_dosen[nama], $data_dosen[gelar] ($data_dosen[nip])";
			echo "</td>";
		echo "</tr>";
		echo "</table><br><br>";

		//Form nilai
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr><td><b>Form Penilaian:</b></td></tr></table>";
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<thead>";
		 	echo "<th style=\"width:5%\">No</th>";
			echo "<th style=\"width:50%\">Aspek Yang Dinilai</th>";
			echo "<th style=\"width:15%\">Bobot</th>";
			echo "<th style=\"width:15%\">Nilai Individu<br>(0-100)</th>";
			echo "<th style=\"width:15%\">Nilai Kelompok<br>(0-100)</th>";
		echo "</thead>";
		//No 1
		echo "<tr>";
			echo "<td align=center>1</td>";
			echo "<td>Kelengkapan isi laporan</td>";
			echo "<td align=center>10%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek1_ind\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_laporan[aspek1_ind]\" id=\"aspek1_ind\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek1_kelp\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_laporan[aspek1_kelp]\" id=\"aspek1_kelp\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 2
		echo "<tr>";
			echo "<td align=center>2</td>";
			echo "<td>Kejelasan penulisan (sistimatika) hasil dan pembahasan</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek2_ind\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_laporan[aspek2_ind]\" id=\"aspek2_ind\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek2_kelp\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_laporan[aspek2_kelp]\" id=\"aspek2_kelp\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 3
		echo "<tr>";
			echo "<td align=center>3</td>";
			echo "<td>Penyerahan laporan tepat waktu</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek3_ind\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_laporan[aspek3_ind]\" id=\"aspek3_ind\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek3_kelp\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_laporan[aspek3_kelp]\" id=\"aspek3_kelp\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 4
		echo "<tr>";
			echo "<td align=center>4</td>";
			echo "<td>Kelancaran diskusi (menjawab dengan benar)</td>";
			echo "<td align=center>30%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek4_ind\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_laporan[aspek4_ind]\" id=\"aspek4_ind\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek4_kelp\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_laporan[aspek4_kelp]\" id=\"aspek4_kelp\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		//No 5
		echo "<tr>";
			echo "<td align=center>5</td>";
			echo "<td>Kejelasan penyajian</td>";
			echo "<td align=center>20%</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek5_ind\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_laporan[aspek5_ind]\" id=\"aspek5_ind\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek5_kelp\" style=\"width:100%;font-size:0.85em;text-align:center\" value=\"$data_laporan[aspek5_kelp]\" id=\"aspek5_kelp\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td colspan=3 align=right>Rata-Rata Nilai (Jumlah Bobot x Nilai)</td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_rata_ind\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"nilai_rata_ind\" value=\"$data_laporan[nilai_rata_ind]\" required/></td>";
			echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_rata_kelp\" style=\"width:100%;font-size:0.85em;text-align:center\" id=\"nilai_rata_kelp\" value=\"$data_laporan[nilai_rata_kelp]\" required/></td>";
		echo "</tr>";
		echo "</table><br>";

		//Umpan Balik
		echo "<table border=1 style=\"width:70%;background:rgb(244, 241, 217);\">";
		echo "<tr>";
		  echo "<td>Umpan Balik:<br><textarea name=\"umpan_balik\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\">$data_laporan[umpan_balik]</textarea></td>";
		echo "</tr>";
		echo "<tr>";
		  echo "<td>Saran:<br><textarea name=\"saran\" rows=5 style=\"width:100%;font-family:Tahoma;font-size:1em\">$data_laporan[saran]</textarea></td>";
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
				window.location.href=\"penilaian_kompre_dosen.php?mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\";
			</script>
			";
		}

		if ($_POST[approve]=="APPROVE")
		{
			$tgl_mulai=$_POST[tgl_mulai];
			$tgl_selesai=$_POST[tgl_selesai];
			$approval=$_POST[approval];
			$mhsw=$_POST[mhsw];
			$aspek1_ind = number_format($_POST[aspek1_ind],2);
			$aspek2_ind = number_format($_POST[aspek2_ind],2);
			$aspek3_ind = number_format($_POST[aspek3_ind],2);
			$aspek4_ind = number_format($_POST[aspek4_ind],2);
			$aspek5_ind = number_format($_POST[aspek5_ind],2);
			$nilai_rata_ind = 0.1*$_POST[aspek1_ind] + 0.2*$_POST[aspek2_ind] + 0.2*$_POST[aspek3_ind] + 0.3*$_POST[aspek4_ind] + 0.2*$_POST[aspek5_ind];
			$nilai_rata_ind = number_format($nilai_rata_ind,2);

			$aspek1_kelp = number_format($_POST[aspek1_kelp],2);
			$aspek2_kelp = number_format($_POST[aspek2_kelp],2);
			$aspek3_kelp = number_format($_POST[aspek3_kelp],2);
			$aspek4_kelp = number_format($_POST[aspek4_kelp],2);
			$aspek5_kelp = number_format($_POST[aspek5_kelp],2);
			$nilai_rata_kelp = 0.1*$_POST[aspek1_kelp] + 0.2*$_POST[aspek2_kelp] + 0.2*$_POST[aspek3_kelp] + 0.3*$_POST[aspek4_kelp] + 0.2*$_POST[aspek5_kelp];
			$nilai_rata_kelp = number_format($nilai_rata_kelp,2);

			$umpan_balik = addslashes($_POST[umpan_balik]);
			$saran = addslashes($_POST[saran]);

			$update_laporan=mysqli_query($con,"UPDATE `kompre_nilai_laporan` SET
				`aspek1_ind`='$aspek1_ind',`aspek1_kelp`='$aspek1_kelp',
				`aspek2_ind`='$aspek2_ind',`aspek2_kelp`='$aspek2_kelp',
				`aspek3_ind`='$aspek3_ind',`aspek3_kelp`='$aspek3_kelp',
				`aspek4_ind`='$aspek4_ind',`aspek4_kelp`='$aspek4_kelp',
				`aspek5_ind`='$aspek5_ind',`aspek5_kelp`='$aspek5_kelp',
				`nilai_rata_ind`='$nilai_rata_ind',`nilai_rata_kelp`='$nilai_rata_kelp',
				`umpan_balik`='$umpan_balik',`saran`='$saran',
				`tgl_approval`='$tgl',`status_approval`='1'
				WHERE `id`='$_POST[id]'");

			echo "
				<script>
				window.location.href = \"penilaian_kompre_dosen.php?mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\";
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
      var aspek1_ind = document.getElementById('aspek1_ind').value;
			var aspek2_ind = document.getElementById('aspek2_ind').value;
			var aspek3_ind = document.getElementById('aspek3_ind').value;
			var aspek4_ind = document.getElementById('aspek4_ind').value;
			var aspek5_ind = document.getElementById('aspek5_ind').value;
      var result_ind = 0.1*parseFloat(aspek1_ind) + 0.2*parseFloat(aspek2_ind) + 0.2*parseFloat(aspek3_ind) + 0.3*parseFloat(aspek4_ind) + 0.2*parseFloat(aspek5_ind);
      if (!isNaN(result_ind)) {
         document.getElementById('nilai_rata_ind').value = number_format(result_ind,2);
      }

			var aspek1_kelp = document.getElementById('aspek1_kelp').value;
			var aspek2_kelp = document.getElementById('aspek2_kelp').value;
			var aspek3_kelp = document.getElementById('aspek3_kelp').value;
			var aspek4_kelp = document.getElementById('aspek4_kelp').value;
			var aspek5_kelp = document.getElementById('aspek5_kelp').value;
      var result_kelp = 0.1*parseFloat(aspek1_kelp) + 0.2*parseFloat(aspek2_kelp) + 0.2*parseFloat(aspek3_kelp) + 0.3*parseFloat(aspek4_kelp) + 0.2*parseFloat(aspek5_kelp);
      if (!isNaN(result_kelp)) {
         document.getElementById('nilai_rata_kelp').value = number_format(result_kelp,2);
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
