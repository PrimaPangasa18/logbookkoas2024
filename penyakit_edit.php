<?php

include "config.php";

set_time_limit(0);
error_reporting("E_ALL ^ E_NOTICE");


  $action_penyakit=mysqli_query($con,"SELECT * FROM `daftar_penyakit` WHERE `id_sistem`='$_POST[sistem_penyakit]' ORDER BY `penyakit` ASC");
  while ($for_peny=mysqli_fetch_array($action_penyakit))
  {
    echo "<option value=\"$for_peny[id]\">$for_peny[penyakit] ($for_peny[skdi_level])</option>";
  }

?>
