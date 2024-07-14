<HTML>
<head>
	<link rel="stylesheet" href="menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="mytable.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="select2/dist/css/select2.css"/>

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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND $_COOKIE['level']==5)
	{
		if ($_COOKIE['level']==5) {include "menu5.php";}

		echo "<div class=\"text_header\">EDIT ISI LOGBOOK - PENYAKIT</div>";

		echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

		echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">EDIT ISI LOGBOOK KEPANITERAAN (STASE) - PENYAKIT</font></h4>";
		echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";

		$id_logbook = $_GET['id'];
		$data_logbook = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `jurnal_penyakit` WHERE `id`='$id_logbook'"));
		$stase = $data_logbook[stase];
		$hapus = mysqli_query($con,"DELETE FROM `jurnal_penyakit` WHERE `id`='$id_logbook'");

		echo "
				<script>
					window.location.href=\"edit_logbook.php?id=\"+\"$stase\";
				</script>
			";

	}
		else
		echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
	}
?>
<script src="jquery.min.js"></script>



<!--</body></html>-->
</BODY>
</HTML>
