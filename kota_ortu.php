<?php

include "config.php";

set_time_limit(0);
error_reporting("E_ALL ^ E_NOTICE");


  $kota=mysqli_query($con,"SELECT * FROM `kota` WHERE `id_prop`='$_POST[prop_ortu]' ORDER BY `kota` ASC");
  echo "<option value=\"\">< Pilihan Kota ></option>";
  while ($dat_kota=mysqli_fetch_array($kota))
  {
    echo "<option value=\"$dat_kota[id_kota]\">$dat_kota[kota]</option>";
  }

?>
