<HTML>

<head>
	<link rel="stylesheet" href="menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="mytable.css" type="text/css" media="screen" />
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
		if (!empty($_COOKIE['user']) and !empty($_COOKIE['pass']) and ($_COOKIE['level'] == 2 or $_COOKIE['level'] == 4 or $_COOKIE['level'] == 6)) {
			if ($_COOKIE['level'] == 2) {
				include "menu2.php";
			}
			if ($_COOKIE['level'] == 4) {
				include "menu4.php";
			}
			if ($_COOKIE['level'] == 6) {
				include "menu6.php";
			}


			echo "<div class=\"text_header\" id=\"top\">DAFTAR KEGIATAN DOSEN/RESIDEN</div>";
			echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

			echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">DAFTAR KEGIATAN DOSEN/RESIDEN</font></h4><br>";

			$user_dosen = $_GET[dosen];
			$jurnal = $_GET[jurnal];
			$id_jurnal = $_GET[id];
			$status = $_GET[status];
			$status_filter = $_GET[appstatus];
			$mhsw_filter = $_GET[mhsw];
			$stase_filter = $_GET[stase];
			$tanggal_filter = $_GET[tgl_kegiatan];

			if ($jurnal == "penyakit") {
				$update = mysqli_query($con, "UPDATE `jurnal_penyakit` SET `status`='$status' WHERE `id`='$id_jurnal'");
			}
			if ($jurnal == "ketrampilan") {
				$update = mysqli_query($con, "UPDATE `jurnal_ketrampilan` SET `status`='$status' WHERE `id`='$id_jurnal'");
			}

			if ($_COOKIE[level] == 2) {
				echo "
			<script>
				window.location.href=\"daftar_kegiatan_dosen_admin.php?dosen=\"+\"$user_dosen\"+\"&mhsw=\"+\"$mhsw_filter\"+\"&stase=\"+\"$stase_filter\"+\"&tgl_kegiatan=\"+\"$tanggal_filter\"+\"&appstatus=\"+\"$status_filter\";
			</script>
			";
			} else {
				echo "
			<script>
				window.location.href=\"daftar_kegiatan_dosen.php?mhsw=\"+\"$mhsw_filter\"+\"&stase=\"+\"$stase_filter\"+\"&tgl_kegiatan=\"+\"$tanggal_filter\"+\"&appstatus=\"+\"$status_filter\";
			</script>
			";
			}


			echo "</fieldset>";
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