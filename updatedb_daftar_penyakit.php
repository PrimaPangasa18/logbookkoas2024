<?php
include "config.php";
set_time_limit(0);
error_reporting("E_ALL ^ E_NOTICE");

$data_penyakit = mysqli_query($con,"SELECT * FROM `daftar_penyakit` WHERE
  `skdi_level` LIKE '%3B%' OR `skdi_level` LIKE '%4A%' OR
  `k_level` LIKE '%3B%' OR `k_level` LIKE '%4A%' ORDER BY `id`");
$no=0;
while ($data=mysqli_fetch_array($data_penyakit))
{
  $stase=mysqli_query($con,"SELECT `id` FROM `kepaniteraan` ORDER BY `id`");
  while ($data_stase=mysqli_fetch_array($stase))
  {
    $include_id = "include_".$data_stase[id];
    $target_id = "target_".$data_stase[id];
    if ($data[$include_id]==1 AND $data[$target_id]==0)
    $update_penyakit=mysqli_query($con,"UPDATE `daftar_penyakit` SET
      `$target_id`='1' WHERE `id`='$data[id]'");
  }
  $no++;
}
echo "<br><br><<  Update daftar penyakit= $no data >><br><br>";

$data_ketrampilan = mysqli_query($con,"SELECT * FROM `daftar_ketrampilan` WHERE
  `skdi_level` LIKE '%3%' OR `skdi_level` LIKE '%4A%' OR
  `k_level` LIKE '%3%' OR `k_level` LIKE '%4A%' OR
  `ipsg_level` LIKE '%3%' OR `ipsg_level` LIKE '%4A%' ORDER BY `id`");
$no=0;
while ($data=mysqli_fetch_array($data_ketrampilan))
{
  $stase=mysqli_query($con,"SELECT `id` FROM `kepaniteraan` ORDER BY `id`");
  while ($data_stase=mysqli_fetch_array($stase))
  {
    $include_id = "include_".$data_stase[id];
    $target_id = "target_".$data_stase[id];
    if ($data[$include_id]==1 AND $data[$target_id]==0)
    $update_ketrampilan=mysqli_query($con,"UPDATE `daftar_ketrampilan` SET
      `$target_id`='1' WHERE `id`='$data[id]'");
  }
  $no++;
}
echo "<br><br><<  Update daftar ketrampilan= $no data >><br><br>";
?>
