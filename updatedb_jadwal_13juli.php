<?php
include "config.php";
set_time_limit(0);
error_reporting("E_ALL ^ E_NOTICE");

$data_koas = mysqli_query($con,"SELECT * FROM `jadwal_tambahan_13juli`");
$no = 0;
while ($data=mysqli_fetch_array($data_koas))
{
  $stase_id = "stase_".$data[stase];
  $cekjadwal = mysqli_query($con,"SELECT `id` FROM `$stase_id` WHERE `nim`='$data[nim]'");
  $jml_cekjadwal = mysqli_num_rows($cekjadwal);

  if ($jml_cekjadwal==0)
  {
    $insert_data = mysqli_query($con,"INSERT INTO `$stase_id`
      ( `nim`, `rotasi`, `tgl_mulai`, `tgl_selesai`,
        `status`, `evaluasi`)
      VALUES
      ( '$data[nim]','9','$data[mulai]','$data[selesai]',
        '1','0')");
  }
  else
  {
    $jadwalada = mysqli_fetch_array($cekjadwal);
    $update_data = mysqli_query($con,"UPDATE `$stase_id` SET
      `rotasi`='9',`tgl_mulai`='$data[mulai]',`tgl_selesai`='$data[selesai]',`status`='1' WHERE `id`='$jadwalada[id]'");
  }
  $no++;
}

echo "<br><br><< Update data jumlah $no mahasiswa koas >><br><br>";
?>
