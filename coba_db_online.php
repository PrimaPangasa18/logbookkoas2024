<?php
	require_once "config.php";

	$insert_db = mysqli_query($con,"INSERT INTO `daftar_koas_temp`
		(`id`,`nim`, `nama`, `username`)
		VALUES
		('1','1111111','dummy_name','dosen_trial')");
?>
