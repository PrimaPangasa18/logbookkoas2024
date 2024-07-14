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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND $_COOKIE[level]==1)
	{
		$stase_id = "stase_".$_GET[stase];
		$del_stase = mysqli_query($con,"DELETE FROM `$stase_id` WHERE `tgl_mulai`='$_GET[tgl_mulai]'");

		echo "
				<script>
					window.location.href=\"rotasi_kelpdelete.php?get_stase=\"+\"$_GET[stase]\"+\"&get_submit=\"+\"SUBMIT\";
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
