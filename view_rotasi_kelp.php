<HTML>
	<head>
		<link rel="stylesheet" href="menu.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="mytable.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="select2/dist/css/select2.css"/>
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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND ($_COOKIE['level']==1 OR $_COOKIE['level']==2 OR $_COOKIE['level']==3))
	{
		if ($_COOKIE['level']==1) {include "menu1.php";}
	  if ($_COOKIE['level']==2) {include "menu2.php";}
	  if ($_COOKIE['level']==3) {include "menu3.php";}
		echo "<div class=\"text_header\">ROTASI KEPANITERAAN (STASE)</div>";
		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">ROTASI KEPANITERAAN (STASE)</font></h4><br>";

		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";

		echo "<table border=\"0\">";
		echo "<tr class=\"ganjil\">";
		echo "<td class=\"td_mid\">Angkatan Mahasiswa</td>";
		echo "<td class=\"td_mid\">";
		echo "<select class=\"select_artwide\" name=\"angk_mhsw\" id=\"angk_mhsw\">";
		$angk_mhsw = mysqli_query($con,"SELECT DISTINCT `angkatan` FROM `biodata_mhsw` ORDER BY `angkatan`");
		echo "<option value=\"all\">Semua Angkatan</option>";
		while ($data1=mysqli_fetch_array($angk_mhsw))
		echo "<option value=\"$data1[angkatan]\">Angkatan $data1[angkatan]</option>";
		echo "</select>";
		echo "</td>";
		echo "</tr>";
		echo "</table>";
		echo "<br><br><input type=\"submit\" class=\"submit1\" name=\"submit\" value=\"SUBMIT\">";
		echo "</form>";

		if ($_POST[submit]=="SUBMIT")
		echo "
		<script>
			window.location.href=\"view_rotasi_all.php?angk=\"+\"$_POST[angk_mhsw]\";
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
<script src="select2/dist/js/select2.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$("#angk_mhsw").select2({
			 	placeholder: "Semua Angkatan",
	      allowClear: true
			});

	});
</script>

<!--</body></html>-->
</BODY>
</HTML>
