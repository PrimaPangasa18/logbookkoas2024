<?php
include "config.php";
set_time_limit(0);
error_reporting("E_ALL ^ E_NOTICE");

$evaluasi = mysqli_query($con,"SELECT * FROM `evaluasi` ORDER BY `nim`,`tanggal`");
$no=0;
while ($data=mysqli_fetch_array($evaluasi))
{
  $update_jurnal_penyakit=mysqli_query($con,"UPDATE `jurnal_penyakit` SET
    `evaluasi`='1'
     WHERE `nim`='$data[nim]' and `tanggal`='$data[tanggal]'");
  $update_jurnal_ketrampilan=mysqli_query($con,"UPDATE `jurnal_ketrampilan` SET
     `evaluasi`='1'
     WHERE `nim`='$data[nim]' and `tanggal`='$data[tanggal]'");
  $no++;
}
echo "<br><br><<  Update data= $no data >>";
?>
