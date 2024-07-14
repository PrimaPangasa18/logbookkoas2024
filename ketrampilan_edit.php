<?php

include "config.php";

set_time_limit(0);
error_reporting("E_ALL ^ E_NOTICE");


  $action_ket=mysqli_query($con,"SELECT * FROM `daftar_ketrampilan` WHERE `id_sistem`='$_POST[sistem_ketrampilan]' ORDER BY `ketrampilan` ASC");
  while ($for_ket=mysqli_fetch_array($action_ket))
  {
    echo "<option value=\"$for_ket[id]\">$for_ket[ketrampilan] ($for_ket[skdi_level])</option>";
  }

?>
