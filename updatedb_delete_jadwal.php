<?php
include "config.php";
set_time_limit(0);
error_reporting("E_ALL ^ E_NOTICE");

$data_stase = mysqli_query($con,"SELECT * FROM `kepaniteraan` ORDER BY `id`");
while ($data=mysqli_fetch_array($data_stase))
{
  $stase_id = "stase_".$data[id];
  $delete_jadwal = mysqli_query($con,"DELETE FROM `$stase_id` WHERE `tgl_mulai`>'2021-07-25'");
  if ($delete_jadwal) echo "Stase $data[kepaniteraan] - DONE<br>";
  else echo "Stase $data[kepaniteraan] - FAILED<br>";
}
?>
