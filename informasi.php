<HTML>
<head>
	<link rel="stylesheet" href="menu.css" type="text/css" media="screen" />
<!--</head>-->
</head>
<BODY>

<?php

	include "config.php";
	include "fungsi.php";

	error_reporting("E_ALL ^ E_NOTICE");

	if (empty($_COOKIE['user']) || empty($_COOKIE['pass'])){
		echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
	}
	else{
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']))
	{
		if ($_COOKIE['level']==1) {include "menu1.php";}
	  if ($_COOKIE['level']==2) {include "menu2.php";}
	  if ($_COOKIE['level']==3) {include "menu3.php";}
	  if ($_COOKIE['level']==4) {include "menu4.php";}
		if ($_COOKIE['level']==5) {include "menu5.php";}
		if ($_COOKIE['level']==6) {include "menu6.php";}

		echo "<div class=\"text_header\" id=\"top\">INFORMASI E-LOGBOOK KOAS</div>";
		echo "<br><br><br><fieldset class=\"fieldset_art\">
				<legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
		echo "<center><h4><font style=\"font-family:Georgia;text-shadow:1px 1px black;color:#006400;font-size:1.25em\">INFORMASI E-LOGBOOK KOAS</font></h4>";
		echo "<br><br>";
		echo "File Download untuk Informasi Penting<br><br>";
		echo "<table border=1 style=\"width:85%\">";
		echo "<thead>";
		echo "<th style=\"width:10%\">No</th>";
		echo "<th style=\"width:60%\">Item Informasi</th>";
		echo "<th style=\"width:30%\">File Download</th>";
		echo "</thead>";
		echo "<tr>";
			echo "<td align=center style=\"padding:5px\">1</td>";
			echo "<td style=\"padding:5px\">Modul Program Kepaniteraan (Stase) Ilmu Penyakit Dalam</td>";
			echo "<td align=center style=\"padding:5px\"><a href=\"file_download/Pedoman_Stase_Ilmu_Penyakit_Dalam.pdf\" target=\"_blank\">";
			echo "<img src=\"pdf.png\" style=\"width:10%\"><br>";
			echo "<font style=\"font-size:0.75em\"><i>Pedoman_Stase_Ilmu_Penyakit_Dalam.pdf</i></font></a>";
		echo "</tr>";
		echo "<tr>";
			echo "<td align=center style=\"padding:5px\">2</td>";
			echo "<td style=\"padding:5px\">Modul Program Kepaniteraan (Stase) Neurologi</td>";
			echo "<td align=center style=\"padding:5px\"><a href=\"file_download/Pedoman_Stase_Neurologi.pdf\" target=\"_blank\">";
			echo "<img src=\"pdf.png\" style=\"width:10%\"><br>";
			echo "<font style=\"font-size:0.75em\"><i>Pedoman_Stase_Neurologi.pdf</i></font></a>";
		echo "</tr>";
		echo "<tr>";
			echo "<td align=center style=\"padding:5px\">3</td>";
			echo "<td style=\"padding:5px\">Modul Program Kepaniteraan (Stase) Ilmu Kesehatan Jiwa</td>";
			echo "<td align=center style=\"padding:5px\"><a href=\"file_download/Pedoman_Stase_Ilmu_Kesehatan_Jiwa.pdf\" target=\"_blank\">";
			echo "<img src=\"pdf.png\" style=\"width:10%\"><br>";
			echo "<font style=\"font-size:0.75em\"><i>Pedoman_Stase_Ilmu_Kesehatan_Jiwa.pdf</i></font></a>";
		echo "</tr>";
		echo "<tr>";
			echo "<td align=center style=\"padding:5px\">4</td>";
			echo "<td style=\"padding:5px\">Modul Program Kepaniteraan (Stase) IKFR</td>";
			echo "<td align=center style=\"padding:5px\"><a href=\"file_download/Pedoman_Stase_IKFR.pdf\" target=\"_blank\">";
			echo "<img src=\"pdf.png\" style=\"width:10%\"><br>";
			echo "<font style=\"font-size:0.75em\"><i>Pedoman_Stase_IKFR.pdf</i></font></a>";
		echo "</tr>";
		echo "<tr>";
			echo "<td align=center style=\"padding:5px\">5</td>";
			echo "<td style=\"padding:5px\">Modul Program Kepaniteraan (Stase) IKM-KP</td>";
			echo "<td align=center style=\"padding:5px\"><a href=\"file_download/Pedoman_Stase_IKM_KP.pdf\" target=\"_blank\">";
			echo "<img src=\"pdf.png\" style=\"width:10%\"><br>";
			echo "<font style=\"font-size:0.75em\"><i>Pedoman_Stase_IKM_KP.pdf</i></font></a>";
		echo "</tr>";
		echo "<tr>";
			echo "<td align=center style=\"padding:5px\">6</td>";
			echo "<td style=\"padding:5px\">Modul Program Kepaniteraan (Stase) Ilmu Jantung dan Pembuluh Darah</td>";
			echo "<td align=center style=\"padding:5px\"><img src=\"pdf.png\" style=\"width:10%\"><br>";
			echo "<font style=\"font-size:0.75em\"><i>Pedoman_Stase_Ilmu_Jantung_dan_Pembuluh_Darah.pdf<br>(masih dalam penyusunan)</i></font></a>";
		echo "</tr>";
		echo "<tr>";
			echo "<td align=center style=\"padding:5px\">7</td>";
			echo "<td style=\"padding:5px\">Modul Program Kepaniteraan (Stase) Ilmu Bedah</td>";
			echo "<td align=center style=\"padding:5px\"><a href=\"file_download/Pedoman_Stase_Ilmu_Bedah.pdf\" target=\"_blank\">";
			echo "<img src=\"pdf.png\" style=\"width:10%\"><br>";
			echo "<font style=\"font-size:0.75em\"><i>Pedoman_Stase_Ilmu_Bedah.pdf</i></font></a>";
		echo "</tr>";
		echo "<tr>";
			echo "<td align=center style=\"padding:5px\">8</td>";
			echo "<td style=\"padding:5px\">Modul Program Kepaniteraan (Stase) Anestesi dan Intensive Care</td>";
			echo "<td align=center style=\"padding:5px\"><a href=\"file_download/Pedoman_Stase_Anestesi_dan_Intensive_Care.pdf\" target=\"_blank\">";
			echo "<img src=\"pdf.png\" style=\"width:10%\"><br>";
			echo "<font style=\"font-size:0.75em\"><i>Pedoman_Stase_Anestesi_dan_Intensive_Care.pdf</i></font></a>";
		echo "</tr>";
		echo "<tr>";
			echo "<td align=center style=\"padding:5px\">9</td>";
			echo "<td style=\"padding:5px\">Modul Program Kepaniteraan (Stase) Radiologi</td>";
			echo "<td align=center style=\"padding:5px\"><a href=\"file_download/Pedoman_Stase_Radiologi.pdf\" target=\"_blank\">";
			echo "<img src=\"pdf.png\" style=\"width:10%\"><br>";
			echo "<font style=\"font-size:0.75em\"><i>Pedoman_Stase_Radiologi.pdf</i></font></a>";
		echo "</tr>";
		echo "<tr>";
			echo "<td align=center style=\"padding:5px\">10</td>";
			echo "<td style=\"padding:5px\">Modul Program Kepaniteraan (Stase) Ilmu Kesehatan Mata</td>";
			echo "<td align=center style=\"padding:5px\"><a href=\"file_download/Pedoman_Stase_Ilmu_Kesehatan_Mata.pdf\" target=\"_blank\">";
			echo "<img src=\"pdf.png\" style=\"width:10%\"><br>";
			echo "<font style=\"font-size:0.75em\"><i>Pedoman_Stase_Ilmu_Kesehatan_Mata.pdf</i></font></a>";
		echo "</tr>";
		echo "<tr>";
			echo "<td align=center style=\"padding:5px\">11</td>";
			echo "<td style=\"padding:5px\">Modul Program Kepaniteraan (Stase) Ilmu Kesehatan THT-KL</td>";
			echo "<td align=center style=\"padding:5px\"><a href=\"file_download/Pedoman_Stase_Ilmu_Kesehatan_THT_KL.pdf\" target=\"_blank\">";
			echo "<img src=\"pdf.png\" style=\"width:10%\"><br>";
			echo "<font style=\"font-size:0.75em\"><i>Pedoman_Stase_Ilmu_Kesehatan_THT_KL.pdf</i></font></a>";
		echo "</tr>";
		echo "<tr>";
			echo "<td align=center style=\"padding:5px\">12</td>";
			echo "<td style=\"padding:5px\">Modul Program Kepaniteraan (Stase) IKGM</td>";
			echo "<td align=center style=\"padding:5px\"><a href=\"file_download/Pedoman_Stase_IKGM.pdf\" target=\"_blank\">";
			echo "<img src=\"pdf.png\" style=\"width:10%\"><br>";
			echo "<font style=\"font-size:0.75em\"><i>Pedoman_Stase_IKGM.pdf</i></font></a>";
		echo "</tr>";
		echo "<tr>";
			echo "<td align=center style=\"padding:5px\">13</td>";
			echo "<td style=\"padding:5px\">Modul Program Kepaniteraan (Stase) Ilmu Kebidanan dan Penyakit Kandungan</td>";
			echo "<td align=center style=\"padding:5px\"><a href=\"file_download/Pedoman_Stase_Ilmu_Kebidanan_dan_Penyakit_Kandungan.pdf\" target=\"_blank\">";
			echo "<img src=\"pdf.png\" style=\"width:10%\"><br>";
			echo "<font style=\"font-size:0.75em\"><i>Pedoman_Stase_Ilmu_Kebidanan_dan_Penyakit_Kandungan.pdf</i></font></a>";
		echo "</tr>";
		echo "<tr>";
			echo "<td align=center style=\"padding:5px\">14</td>";
			echo "<td style=\"padding:5px\">Modul Program Kepaniteraan (Stase) Kedokteran Forensik dan Medikolegal</td>";
			echo "<td align=center style=\"padding:5px\"><a href=\"file_download/Pedoman_Stase_Kedokteran_Forensik_dan_Medikolegal.pdf\" target=\"_blank\">";
			echo "<img src=\"pdf.png\" style=\"width:10%\"><br>";
			echo "<font style=\"font-size:0.75em\"><i>Pedoman_Stase_Kedokteran_Forensik_dan_Medikolegal.pdf</i></font></a>";
		echo "</tr>";
		echo "<tr>";
			echo "<td align=center style=\"padding:5px\">15</td>";
			echo "<td style=\"padding:5px\">Modul Program Kepaniteraan (Stase) Ilmu Kesehatan Anak</td>";
			echo "<td align=center style=\"padding:5px\"><a href=\"file_download/Pedoman_Stase_Ilmu_Kesehatan_Anak.pdf\" target=\"_blank\">";
			echo "<img src=\"pdf.png\" style=\"width:10%\"><br>";
			echo "<font style=\"font-size:0.75em\"><i>Pedoman_Stase_Ilmu_Kesehatan_Anak.pdf</i></font></a>";
		echo "</tr>";
		echo "<tr>";
			echo "<td align=center style=\"padding:5px\">16</td>";
			echo "<td style=\"padding:5px\">Modul Program Kepaniteraan (Stase) Ilmu Kesehatan Kulit dan Kelamin</td>";
			echo "<td align=center style=\"padding:5px\"><a href=\"file_download/Pedoman_Stase_Ilmu_Kesehatan_Kulit_dan_Kelamin.pdf\" target=\"_blank\">";
			echo "<img src=\"pdf.png\" style=\"width:10%\"><br>";
			echo "<font style=\"font-size:0.75em\"><i>Pedoman_Stase_Ilmu_Kesehatan_Kulit_dan_Kelamin.pdf</i></font></a>";
		echo "</tr>";
		echo "<tr>";
			echo "<td align=center style=\"padding:5px\">17</td>";
			echo "<td style=\"padding:5px\">Modul Program Kepaniteraan (Stase) Komprehensip dan Kedokteran Keluarga</td>";
			echo "<td align=center style=\"padding:5px\"><a href=\"file_download/Pedoman_Stase_Kedokteran_Keluarga.pdf\" target=\"_blank\">";
			echo "<img src=\"pdf.png\" style=\"width:10%\"><br>";
			echo "<font style=\"font-size:0.75em\"><i>Pedoman_Stase_Kedokteran_Keluarga.pdf</i></font></a><br><br>";
			echo "<a href=\"file_download/Pedoman_Stase_Komprehensip.pdf\" target=\"_blank\">";
			echo "<img src=\"pdf.png\" style=\"width:10%\"><br>";
			echo "<font style=\"font-size:0.75em\"><i>Pedoman_Stase_Komprehensip.pdf</i></font></a>";
		echo "</tr>";
		echo "</table><br><br>";
		echo "<center><a href=\"#top\"><i>Goto top</i></a></center><br><br>";
		echo "<table border=\"0\" style=\"border-collapse:collapse;width:100%;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);\">";
		echo "<tr>";
		echo "<td><img src=\"images/Image001.jpg\" style=\"width:100%;\" class=\"appearing\"></td>";
		echo "<td><img src=\"images/Image002.jpg\" style=\"width:100%;\" class=\"appearing\"></td>";
		echo "<td><img src=\"images/Image003.jpg\" style=\"width:100%;\" class=\"appearing\"></td>";
		echo "<td><img src=\"images/Image004.jpg\" style=\"width:100%;\" class=\"appearing\"></td>";
		echo "<td><img src=\"images/Image005.jpg\" style=\"width:100%;\" class=\"appearing\"></td>";
		echo "</tr>";
		echo "</table>";
		echo "<br>";
		echo "<table border=\"0\" style=\"border-collapse:collapse;width:100%;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);\">";
		echo "<tr style=\"background-color:#006400;border:1px solid #006400\"><td>&nbsp;</td><td>&nbsp;</td></tr>";
		echo "<tr style=\"background-color:#006400;border:1px solid #006400\">";
		echo "<td style=\"width:50%;vertical-align:top;padding:10px;\">
			<font style=\"font-family:Arial;font-size:1.275em;color:white;\">
			Program Studi Pendidikan Profesi Dokter</font><br><br>
			<font style=\"font-family:Arial;font-size:0.875em;color:white;\">
			Fakultas Kedokteran<br>
			Universitas Diponegoro (UNDIP)<br><br>
			Kampus UNDIP Tembalang<br>
			Jl.Prof. H. Soedarto, SH. Tembalang Semarang Kotak Pos 1269<br>
			Tembalang, Semarang<br>
			Kode Pos 50275<br>
			Telp. : 024 – 76928010<br>
			Fax. : 024 – 76928011<br>
			Email: dean@fk.undip.ac.id
			</font>";
		echo "</td>";
		echo "<td align=\"right\" style=\"width:50%;vertical-align:top;padding:10px;\">
			<font style=\"font-family:Arial;font-size:1.275em;color:white;\">
			Link:</font><br><br>
			<font style=\"font-family:Arial;font-size:0.875em;color:white;\">
			<a href=\"https://www.undip.ac.id/language/id/\" target=\"_blank\" style=\"color:#00FFFF\">Website Universitas Diponegoro (UNDIP)</a><br>
			<a href=\"https://fk.undip.ac.id\" target=\"_blank\" style=\"color:#00FFFF\">Website Fakultas Kedokteran - UNDIP</a><br>
			<a href=\"https://fk.undip.ac.id/?page_id=887\" target=\"_blank\" style=\"color:#00FFFF\">Prodi Pendidikan Profesi Dokter</a></font><br><br>
			<font style=\"font-family:Arial;font-size:1.275em;color:white;\">
			Contact Person:</font><br><br>
			<font style=\"font-family:Arial;font-size:0.875em;color:white;\">
			Ketua Prodi Pendidikan Profesi Dokter<br>
			Fakultas Kedokteran UNDIP - Gd A Lt. 2<br>
			Email: helmia.f@fk.undip.ac.id<br>
			Hp: +62 812-2521-7878<br>
			</font>";
		echo "</td>";
		echo "</tr>";
		echo "<tr style=\"background-color:#006400;border:1px solid #006400\"><td>&nbsp;</td><td>&nbsp;</td></tr>";
		echo "<tr><td colspan=2 align=\"center\" style=\"padding:10px; background-color:lightgrey;border:1px solid grey\">";
		echo "<font style=\"font-family:Arial;font-size:0.575em;color:black\">@ $tahun aristriwiyatno</font>";
		echo "</td></tr>";
		echo "</table>";
		echo "<br></fieldset>";
	}
		else
		echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
	}
?>
<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.appearing').hover(function() {
			$(this).addClass('transisi');
		}, function() {
			$(this).removeClass('transisi');
		});
	});
</script>

<!--</body></html>-->
</BODY>
</HTML>
