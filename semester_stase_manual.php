<?php

include "config.php";

set_time_limit(0);
error_reporting("E_ALL ^ E_NOTICE");

	$dat_stase = mysqli_query($con,"SELECT * FROM `kepaniteraan` WHERE `semester`='$_POST[semester]' ORDER BY `id`");
	echo "<select class=\"select_artwide\" name=\"stase\" required>";
	echo "<option value=\"\">< Pilihan Kepaniteraan (Stase) ></option>";
	while ($dat=mysqli_fetch_array($dat_stase))
  {
		$pekan_stase = $dat[hari_stase]/7;
		echo "<option value=\"$dat[id]\">$dat[kepaniteraan] - Periode: $pekan_stase pekan ($dat[hari_stase] hari)</option>";
  }
  echo "</select>";
?>
