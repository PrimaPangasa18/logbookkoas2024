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
	if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND $_COOKIE['level']==5)
	{
		$id = $_GET['id'];
		$hapus_presentasi = mysqli_query($con,"DELETE FROM `mata_nilai_presentasi` WHERE `id`='$id'");
		$hapus_penyanggah = mysqli_query($con,"DELETE FROM `mata_nilai_penyanggah` WHERE `id_presentasi`='$id'");
		echo "
				<script>
					window.location.href=\"penilaian_mata.php\";
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
