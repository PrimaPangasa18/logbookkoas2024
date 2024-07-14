<HTML>
<head>
	<link rel="stylesheet" href="menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="mytable.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="select2/dist/css/select2.css"/>
	<link rel="stylesheet" type="text/css" href="jquery_ui/jquery-ui.css">
	<meta name="viewport" content="width=device-width, maximum-scale=1">
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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND $_COOKIE['level']==2)
	{
		if ($_COOKIE['level']==2) {include "menu2.php";}

		echo "<div class=\"text_header\" id=\"top\">DAFTAR KEGIATAN DOSEN/RESIDEN</div>";
		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">FILTER TAMPIL DAFTAR KEGIATAN DOSEN/RESIDEN</font></h4><br>";
		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";

		$user_dosen = $_GET[user_name];
		echo "<input type=\"hidden\" name=\"userdosen\" value=\"$user_dosen\"/>";
		$data_dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$user_dosen'"));
		echo "<table>";
		echo "<tr class=\"ganjil\">";
		echo "<td class=\"td_mid\">Nama Dosen/Residen</td>";
		echo "<td class=\"td_mid\">$data_dosen[nama], $data_dosen[gelar]</td>";
		echo "</tr>";
		echo "<tr class=\"ganjil\">";
		echo "<td class=\"td_mid\">Username Dosen/Residen</td>";
		echo "<td class=\"td_mid\">$data_dosen[nip]</td>";
		echo "</tr>";
		echo "<tr class=\"ganjil\">";
		echo "<td class=\"td_mid\">Kepaniteraan (stase)</td>";
		echo "<td class=\"td_mid\">";
		echo "<select class=\"select_artwide\" name=\"stase\" id=\"stase\">";
		$data_stase = mysqli_query($con,"SELECT * FROM `kepaniteraan` ORDER BY `id`");
		echo "<option value=\"all\">Semua Kepaniteraan (Stase)</option>";
		while ($data=mysqli_fetch_array($data_stase))
		echo "<option value=\"$data[id]\">$data[kepaniteraan]</option>";
		echo "</select>";
		echo "</td>";
		echo "</tr>";
		echo "<tr class=\"ganjil\">";
		echo "<td class=\"td_mid\">Nama Mahasiswa</td>";
		echo "<td class=\"td_mid\">";
		echo "<select class=\"select_artwide\" name=\"mhsw\" id=\"mhsw\">";
		$data_mhsw = mysqli_query($con,"SELECT `nim`,`nama` FROM `biodata_mhsw` ORDER BY `nama`");
		echo "<option value=\"all\">Semua Mahasiswa</option>";
		while ($data1=mysqli_fetch_array($data_mhsw))
		echo "<option value=\"$data1[nim]\">$data1[nama]</option>";
		echo "</select>";
		echo "</td>";
		echo "</tr>";
		echo "<tr class=\"ganjil\">";
		echo "<td class=\"td_mid\">Status Approval</td>";
		echo "<td class=\"td_mid\">";
		echo "<select class=\"select_artwide\" name=\"appstatus\" id=\"appstatus\">";
		echo "<option value=\"all\">Semua Status</option>";
		echo "<option value=\"1\">Approved</option>";
		echo "<option value=\"0\">Unapproved</option>";
		echo "</select>";
		echo "</td>";
		echo "</tr>";
		echo "<tr class=\"ganjil\">";
		echo "<td class=\"td_mid\">Tanggal kegiatan (yyyy-mm-dd)</td><td class=\"td_mid\"><input type=\"text\" class=\"input-tanggal\" name=\"tgl_kegiatan\" value=\"Semua Tanggal\" style=\"font-size:1em;font-family:TAHOMA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" /></td>";
		echo "</tr>";
		echo "</table>";
		echo "<br><br><input type=\"submit\" class=\"submit1\" name=\"submit\" value=\"SUBMIT\">";
		echo "</form>";

		if ($_POST[submit]=="SUBMIT")
		echo "
		<script>
			window.location.href=\"daftar_kegiatan_dosen_admin.php?dosen=\"+\"$_POST[userdosen]\"+\"&mhsw=\"+\"$_POST[mhsw]\"+\"&stase=\"+\"$_POST[stase]\"+\"&tgl_kegiatan=\"+\"$_POST[tgl_kegiatan]\"+\"&appstatus=\"+\"$_POST[appstatus]\";
		</script>
		";

		echo "</fieldset>";

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
<script type="text/javascript" src="jquery_ui/jquery-ui.js"></script>
<script src="select2/dist/js/select2.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.input-tanggal').datepicker({ dateFormat: 'yy-mm-dd' });
		$("#stase").select2({
			 	placeholder: "< Kepaniteraan (Stase) >",
	      allowClear: true
		 	});
		$("#mhsw").select2({
		   	placeholder: "< Nama Mahasiswa >",
	      allowClear: true
	 		});
		$("#appstatus").select2({
			 	placeholder: "< Status Approval >",
	      allowClear: true
			});
	});
</script>



<!--</body></html>-->
</BODY>
</HTML>
