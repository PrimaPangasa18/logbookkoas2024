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
	if (!empty($_COOKIE['user']) and !empty($_COOKIE['pass']) and $_COOKIE['level'] == 1) {
		$stase_id = "stase_" . $_GET['stase'];
		$del_stase = mysqli_query($con, "DELETE FROM `$stase_id` WHERE `nim`='$_GET[nim]'");

		echo "
				<script>
					window.location.href=\"rotasi_inddelete.php?user_name=\"+\"$_GET[nim]\";
				</script>
				";
	} else
		echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
}
