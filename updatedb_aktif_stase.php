<?php
include "config.php";
set_time_limit(0);
error_reporting("E_ALL ^ E_NOTICE");

$data_stase = mysqli_query($con,"SELECT * FROM `kepaniteraan` ORDER BY `id`");
while ($data=mysqli_fetch_array($data_stase))
{
  $stase_id = "stase_".$data[id];
  $update_jadwal = mysqli_query($con,"UPDATE `$stase_id` SET `status`='1' WHERE (`tgl_mulai`>'2020-05-10' AND `tgl_mulai`<'2020-05-19')");
  if ($update_jadwal) echo "Stase $data[kepaniteraan] - DONE<br>";
  else echo "Stase $data[kepaniteraan] - FAILED<br>";
}
?>
