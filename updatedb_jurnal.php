<?php
include "config.php";
set_time_limit(0);
error_reporting("E_ALL ^ E_NOTICE");

$data_mhsw = mysqli_query($con,"SELECT `nim`,`angkatan`,`grup` FROM `biodata_mhsw` ORDER BY `nim`");
$no=0;
while ($data=mysqli_fetch_array($data_mhsw))
{
  $update_jurnal_penyakit=mysqli_query($con,"UPDATE `jurnal_penyakit` SET
    `angkatan`='$data[angkatan]',`grup`='$data[grup]'
     WHERE `nim`='$data[nim]'");
  $update_jurnal_ketrampilan=mysqli_query($con,"UPDATE `jurnal_ketrampilan` SET
    `angkatan`='$data[angkatan]',`grup`='$data[grup]'
     WHERE `nim`='$data[nim]'");
  $no++;
}
echo "<br><br><<  Update data= $no data >>";
?>
