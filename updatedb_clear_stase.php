<?php

	include "config.php";
	include "fungsi.php";

	error_reporting("E_ALL ^ E_NOTICE");

	$stase = mysqli_query($con,"SELECT `id`,`kepaniteraan` FROM `kepaniteraan` ORDER BY `id`");
	while ($data=mysqli_fetch_array($stase))
	{
		echo "STASE: $data[kepaniteraan] ($data[id])<br>----------------<br>";
		$stase_id = "stase_"."$data[id]";
		$mhsw = mysqli_query($con,"SELECT `nim` FROM `$stase_id` ORDER BY `nim`");
		while ($data_mhsw=mysqli_fetch_array($mhsw))
		{
			$cek_mhsw = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `biodata_mhsw` WHERE `nim`='$data_mhsw[nim]'"));
			if ($cek_mhsw<1)
			{
				$delete_jadwal = mysqli_query($con,"DELETE FROM `$stase_id` WHERE `nim`='$data_mhsw[nim]'");
				echo "NIM: $data_mhsw[nim] - DELETE<br>";
			}
		}
	}

  ?>
