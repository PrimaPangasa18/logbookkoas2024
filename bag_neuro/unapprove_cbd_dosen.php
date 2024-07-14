<HTML>
<head>
<!--</head>-->
</head>
<BODY>

<?php

	include "../config.php";
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
		$id = $_GET['id'];
		$unapprove_cbd = mysqli_query($con,"UPDATE `neuro_nilai_cbd` SET
			`tgl_approval`='2000-01-01', `status_approval`='0' WHERE `id`='$id'");

		$tgl_mulai = $_GET[mulai];
		$tgl_selesai = $_GET[selesai];
		$approval = $_GET[approval];
		$mhsw = $_GET[mhsw];
		echo "
			<script>
				window.location.href=\"penilaian_neuro_dosen.php?mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\";
			</script>
			";
	}
	else
		echo "
		<script>
			window.location.href=\"../accessdenied.php\";
		</script>
		";
	}
?>

<!--</body></html>-->
</BODY>
</HTML>
