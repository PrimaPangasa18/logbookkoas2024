<?php

include "config.php";

set_time_limit(0);
error_reporting("E_ALL ^ E_NOTICE");


  $action_mslh=mysqli_query($con,"SELECT * FROM `daftar_masalah` WHERE `id_sistem`='$_POST[sistem_masalah]' ORDER BY `masalah` ASC");
  echo "<option value=\"\">< Pilihan Masalah ></option>";
  while ($for_mslh=mysqli_fetch_array($action_mslh))
  {
    echo "<option value=\"$for_mslh[id]\">$for_mslh[masalah]</option>";
  }

?>
