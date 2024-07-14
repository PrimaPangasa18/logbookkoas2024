<?php
include "config.php";
set_time_limit(0);
error_reporting("E_ALL ^ E_NOTICE");


$data_koas = mysqli_query($con,"SELECT * FROM `import_jadwal_kompre_3okt21`");
$no = 0;
while ($data=mysqli_fetch_array($data_koas))
{

  $nim_mhsw = $data[nim];
  $stase = $data[stase_1];
  $tgl_mulai = $data[mulai_1];
  $tgl_selesai = $data[selesai_1];
  $stase_id = "stase_".$stase;
  $update_jadwal=mysqli_query($con,"UPDATE `$stase_id` SET `tgl_mulai`='$tgl_mulai',`tgl_selesai`='$tgl_selesai' WHERE `nim`='$nim_mhsw'");
  if ($update_jadwal) echo "$nim_mhsw - $stase_id - UPDATED <br>";
  else echo "$nim_mhsw - $stase_id - UPDATE ERROR ............... <br>";
  $no++;
}


echo "<br><br><< Update data jumlah $no mahasiswa koas >><br><br>";
?>
