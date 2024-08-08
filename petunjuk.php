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

	if (empty($_COOKIE['user']) || empty($_COOKIE['pass'])) {
		echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
	} else {
		if (!empty($_COOKIE['user']) and !empty($_COOKIE['pass'])) {
			if ($_COOKIE['level'] == 1) {
				include "menu1.php";
			}
			if ($_COOKIE['level'] == 2) {
				include "menu2.php";
			}
			if ($_COOKIE['level'] == 3) {
				include "menu3.php";
			}
			if ($_COOKIE['level'] == 4) {
				include "menu4.php";
			}
			if ($_COOKIE['level'] == 5) {
				include "menu5.php";
			}

			echo "<div class=\"text_header\">PETUNJUK PENGGUNAAN</div>";
			echo "<br><br><br><fieldset class=\"fieldset_art\">
				<legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
			echo "<br><br><center><h1> <font style=\"color:#006400;font-size:30px;font-style:italic\">PETUNJUK PENGGUNAAN</font></h1><br>";
			echo "<br><br><br><< UNDER CONSTRUCTION >><br><br><br></fieldset></center>";
		} else
			echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
	}
	?>


	<!--</body></html>-->
</BODY>

</HTML>