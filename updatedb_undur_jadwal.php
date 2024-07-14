<?php
include "config.php";
set_time_limit(0);
error_reporting("E_ALL ^ E_NOTICE");

$data_stase = mysqli_query($con,"SELECT `id` FROM `kepaniteraan`");
$no = 0;
$sukses = 0;
while ($data=mysqli_fetch_array($data_stase))
{
  $stase_id = "stase_".$data[id];
  $update_tgl = mysqli_query($con,"UPDATE `$stase_id` SET `tgl_selesai`='2020-05-25' WHERE `tgl_selesai`='2020-05-21'");
  if ($update_tgl) $sukses++;
  $no++;
}
echo "<br><br>&nbsp;&nbsp;&nbsp;&nbsp;Update data kepantiteraan: $sukses stase dari $no stase";

?>
