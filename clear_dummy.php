<?php
	require_once "config.php";
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
		$delete_dummy_penyakit = mysqli_query($con,"DELETE FROM `jurnal_penyakit_dummy` WHERE `username` LIKE '%$_COOKIE[user]%'");
		$delete_dummy_ketrampilan = mysqli_query($con,"DELETE FROM `jurnal_ketrampilan_dummy` WHERE `username` LIKE '%$_COOKIE[user]%'");
		$delete_dummy_mhsw = mysqli_query($con,"DELETE FROM `daftar_koas_temp` WHERE `username` LIKE '%$_COOKIE[user]%'");
		$hapus_dummy = mysqli_query($con,"DELETE FROM `dummy_rotasi` WHERE `username` LIKE '%$_COOKIE[user]%'");
		$delete_dummy_evaluasi = mysqli_query($con,"DELETE FROM `dummy_evaluasi_stase` WHERE `username`='$_COOKIE[user]'");
	}
		else
		echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
	}
?>
