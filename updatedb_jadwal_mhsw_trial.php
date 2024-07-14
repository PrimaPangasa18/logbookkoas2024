<?php
include "config.php";
set_time_limit(0);
error_reporting("E_ALL ^ E_NOTICE");


$kepaniteraan = mysqli_query($con,"SELECT `id` FROM `kepaniteraan` ORDER BY `id`");
$no = 1;
while ($data=mysqli_fetch_array($kepaniteraan))
{
  $nim_mhsw = "mhsw_trial";
  $tgl_mulai = "2024-03-18";
  $tgl_selesai = "2024-04-18";
  $stase_id = "stase_".$data[id];
  //$rotasi = substr($data[id], 3, 1);
  $delete_jadwal=mysqli_query($con,"DELETE FROM `$stase_id` WHERE `nim`='$nim_mhsw'");
  $insert_jadwal=mysqli_query($con,"INSERT INTO `$stase_id`
    (`nim`, `rotasi`, `tgl_mulai`, `tgl_selesai`, `status`, `evaluasi`)
    VALUES
    ('$nim_mhsw','$no','$tgl_mulai','$tgl_selesai','1','0')");
  if ($insert_jadwal) echo "$nim_mhsw - $stase_id - UPDATED <br>";
  else echo "$nim_mhsw - $stase_id - UPDATE ERROR ............... <br>";
  $no++;
}


echo "<br><br><< Update data jumlah $no stase ... >><br><br>";
?>
