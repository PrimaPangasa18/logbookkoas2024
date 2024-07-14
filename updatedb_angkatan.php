<?php
include "config.php";
set_time_limit(0);
error_reporting("E_ALL ^ E_NOTICE");
/*
//Update PPP64
$ppp64 = mysqli_query($con,"SELECT * FROM `ppp64` ORDER BY `nim`");
while ($data=mysqli_fetch_array($ppp64))
{
  $update_biodata64 = mysqli_query($con,"UPDATE `biodata_mhsw`
    SET `angkatan`='PPP64' WHERE `nim`='$data[nim]'");
  $update_evaluasi64 = mysqli_query($con,"UPDATE `evaluasi`
    SET `angkatan`='PPP64' WHERE `nim`='$data[nim]'");
  $update_jurnal_penyakit64 = mysqli_query($con,"UPDATE `jurnal_penyakit`
    SET `angkatan`='PPP64' WHERE `nim`='$data[nim]'");
  $update_jurnal_ketrampilan64 = mysqli_query($con,"UPDATE `jurnal_ketrampilan`
    SET `angkatan`='PPP64' WHERE `nim`='$data[nim]'");
}
//Update PPP65
$ppp65 = mysqli_query($con,"SELECT * FROM `ppp65` ORDER BY `nim`");
while ($data=mysqli_fetch_array($ppp65))
{
  $update_biodata65 = mysqli_query($con,"UPDATE `biodata_mhsw`
    SET `angkatan`='PPP65' WHERE `nim`='$data[nim]'");
  $update_evaluasi65 = mysqli_query($con,"UPDATE `evaluasi`
    SET `angkatan`='PPP65' WHERE `nim`='$data[nim]'");
  $update_jurnal_penyakit65 = mysqli_query($con,"UPDATE `jurnal_penyakit`
    SET `angkatan`='PPP65' WHERE `nim`='$data[nim]'");
  $update_jurnal_ketrampilan65 = mysqli_query($con,"UPDATE `jurnal_ketrampilan`
    SET `angkatan`='PPP65' WHERE `nim`='$data[nim]'");
}
//Update PPP66
$ppp66 = mysqli_query($con,"SELECT * FROM `ppp66` ORDER BY `nim`");
while ($data=mysqli_fetch_array($ppp66))
{
  $update_biodata66 = mysqli_query($con,"UPDATE `biodata_mhsw`
    SET `angkatan`='PPP66' WHERE `nim`='$data[nim]'");
  $update_evaluasi66 = mysqli_query($con,"UPDATE `evaluasi`
    SET `angkatan`='PPP66' WHERE `nim`='$data[nim]'");
  $update_jurnal_penyakit66 = mysqli_query($con,"UPDATE `jurnal_penyakit`
    SET `angkatan`='PPP66' WHERE `nim`='$data[nim]'");
  $update_jurnal_ketrampilan66 = mysqli_query($con,"UPDATE `jurnal_ketrampilan`
    SET `angkatan`='PPP66' WHERE `nim`='$data[nim]'");
}
//Update PPP67
$ppp67 = mysqli_query($con,"SELECT * FROM `ppp67` ORDER BY `nim`");
while ($data=mysqli_fetch_array($ppp67))
{
  $update_biodata67 = mysqli_query($con,"UPDATE `biodata_mhsw`
    SET `angkatan`='PPP67' WHERE `nim`='$data[nim]'");
  $update_evaluasi67 = mysqli_query($con,"UPDATE `evaluasi`
    SET `angkatan`='PPP67' WHERE `nim`='$data[nim]'");
  $update_jurnal_penyakit67 = mysqli_query($con,"UPDATE `jurnal_penyakit`
    SET `angkatan`='PPP67' WHERE `nim`='$data[nim]'");
  $update_jurnal_ketrampilan67 = mysqli_query($con,"UPDATE `jurnal_ketrampilan`
    SET `angkatan`='PPP67' WHERE `nim`='$data[nim]'");
}
//Update PPP68
$ppp68 = mysqli_query($con,"SELECT * FROM `ppp68` ORDER BY `nim`");
while ($data=mysqli_fetch_array($ppp68))
{
  $update_biodata68 = mysqli_query($con,"UPDATE `biodata_mhsw`
    SET `angkatan`='PPP68' WHERE `nim`='$data[nim]'");
  $update_evaluasi68 = mysqli_query($con,"UPDATE `evaluasi`
    SET `angkatan`='PPP68' WHERE `nim`='$data[nim]'");
  $update_jurnal_penyakit68 = mysqli_query($con,"UPDATE `jurnal_penyakit`
    SET `angkatan`='PPP68' WHERE `nim`='$data[nim]'");
  $update_jurnal_ketrampilan68 = mysqli_query($con,"UPDATE `jurnal_ketrampilan`
    SET `angkatan`='PPP68' WHERE `nim`='$data[nim]'");
}
//Update PPP69
$ppp69 = mysqli_query($con,"SELECT * FROM `ppp69` ORDER BY `nim`");
while ($data=mysqli_fetch_array($ppp69))
{
  $update_biodata69 = mysqli_query($con,"UPDATE `biodata_mhsw`
    SET `angkatan`='PPP69' WHERE `nim`='$data[nim]'");
  $update_evaluasi69 = mysqli_query($con,"UPDATE `evaluasi`
    SET `angkatan`='PPP69' WHERE `nim`='$data[nim]'");
  $update_jurnal_penyakit69 = mysqli_query($con,"UPDATE `jurnal_penyakit`
    SET `angkatan`='PPP69' WHERE `nim`='$data[nim]'");
  $update_jurnal_ketrampilan69 = mysqli_query($con,"UPDATE `jurnal_ketrampilan`
    SET `angkatan`='PPP69' WHERE `nim`='$data[nim]'");
}
//Cek 64
if ($update_biodata64) echo "Update biodata 64 ... DONE!<br>";
else echo "Update biodata 64 ... ERROR!<br>";
if ($update_evaluasi64) echo "Update evaluasi 64 ... DONE!<br>";
else echo "Update evaluasi 64 ... ERROR!<br>";
if ($update_jurnal_penyakit64) echo "Update jurnal penyakit 64 ... DONE!<br>";
else echo "Update jurnal penyakit 64 ... ERROR!<br>";
if ($update_jurnal_ketrampilan64) echo "Update jurnal ketrampilan 64 ... DONE!<br>";
else echo "Update jurnal ketrampilan 64 ... ERROR!<br>";
//Cek 65
if ($update_biodata65) echo "Update biodata 65 ... DONE!<br>";
else echo "Update biodata 65 ... ERROR!<br>";
if ($update_evaluasi65) echo "Update evaluasi 65 ... DONE!<br>";
else echo "Update evaluasi 65 ... ERROR!<br>";
if ($update_jurnal_penyakit65) echo "Update jurnal penyakit 65 ... DONE!<br>";
else echo "Update jurnal penyakit 65 ... ERROR!<br>";
if ($update_jurnal_ketrampilan65) echo "Update jurnal ketrampilan 65 ... DONE!<br>";
else echo "Update jurnal ketrampilan 65 ... ERROR!<br>";
//Cek 66
if ($update_biodata66) echo "Update biodata 66 ... DONE!<br>";
else echo "Update biodata 66 ... ERROR!<br>";
if ($update_evaluasi66) echo "Update evaluasi 66 ... DONE!<br>";
else echo "Update evaluasi 66 ... ERROR!<br>";
if ($update_jurnal_penyakit66) echo "Update jurnal penyakit 66 ... DONE!<br>";
else echo "Update jurnal penyakit 66 ... ERROR!<br>";
if ($update_jurnal_ketrampilan66) echo "Update jurnal ketrampilan 66 ... DONE!<br>";
else echo "Update jurnal ketrampilan 66 ... ERROR!<br>";
//Cek 67
if ($update_biodata67) echo "Update biodata 67 ... DONE!<br>";
else echo "Update biodata 67 ... ERROR!<br>";
if ($update_evaluasi67) echo "Update evaluasi 67 ... DONE!<br>";
else echo "Update evaluasi 67 ... ERROR!<br>";
if ($update_jurnal_penyakit67) echo "Update jurnal penyakit 67 ... DONE!<br>";
else echo "Update jurnal penyakit 67 ... ERROR!<br>";
if ($update_jurnal_ketrampilan67) echo "Update jurnal ketrampilan 67 ... DONE!<br>";
else echo "Update jurnal ketrampilan 67 ... ERROR!<br>";
//Cek 68
if ($update_biodata68) echo "Update biodata 68 ... DONE!<br>";
else echo "Update biodata 68 ... ERROR!<br>";
if ($update_evaluasi68) echo "Update evaluasi 68 ... DONE!<br>";
else echo "Update evaluasi 68 ... ERROR!<br>";
if ($update_jurnal_penyakit68) echo "Update jurnal penyakit 68 ... DONE!<br>";
else echo "Update jurnal penyakit 68 ... ERROR!<br>";
if ($update_jurnal_ketrampilan68) echo "Update jurnal ketrampilan 68 ... DONE!<br>";
else echo "Update jurnal ketrampilan 68 ... ERROR!<br>";
//Cek 69
if ($update_biodata69) echo "Update biodata 69 ... DONE!<br>";
else echo "Update biodata 69 ... ERROR!<br>";
if ($update_evaluasi69) echo "Update evaluasi 69 ... DONE!<br>";
else echo "Update evaluasi 69 ... ERROR!<br>";
if ($update_jurnal_penyakit69) echo "Update jurnal penyakit 69 ... DONE!<br>";
else echo "Update jurnal penyakit 69 ... ERROR!<br>";
if ($update_jurnal_ketrampilan69) echo "Update jurnal ketrampilan 69 ... DONE!<br>";
else echo "Update jurnal ketrampilan 69 ... ERROR!<br>";

$mhsw_lulus = mysqli_query($con,"SELECT `nim` FROM `biodata_mhsw` WHERE `angkatan` NOT LIKE '%PPP%'");
while ($data=mysqli_fetch_array($mhsw_lulus))
{
  $update_biodataold = mysqli_query($con,"UPDATE `biodata_mhsw`
    SET `angkatan`='PPPxx' WHERE `nim`='$data[nim]'");
  $update_evaluasiold = mysqli_query($con,"UPDATE `evaluasi`
    SET `angkatan`='PPPxx' WHERE `nim`='$data[nim]'");
  $update_jurnal_penyakitold = mysqli_query($con,"UPDATE `jurnal_penyakit`
    SET `angkatan`='PPPxx' WHERE `nim`='$data[nim]'");
  $update_jurnal_ketrampilanold = mysqli_query($con,"UPDATE `jurnal_ketrampilan`
    SET `angkatan`='PPPxx' WHERE `nim`='$data[nim]'");
}

$mhsw_lulus = mysqli_query($con,"SELECT `nim` FROM `biodata_mhsw` WHERE `angkatan` NOT LIKE '%PPP%'");
while ($data=mysqli_fetch_array($mhsw_lulus))
{
  $delete_admin = mysqli_query($con,"DELETE FROM `admin` WHERE `username`='$data[nim]'");
  $delete_biodata = mysqli_query($con,"DELETE FROM `biodata_mhsw` WHERE `nim`='$data[nim]'");
  $delete_jurnal_penyakit = mysqli_query($con,"DELETE FROM `jurnal_penyakit` WHERE `nim`='$data[nim]'");
  $delete_jurnal_ketrampilan = mysqli_query($con,"DELETE FROM `jurnal_ketrampilan` WHERE `nim`='$data[nim]'");
  $stase = mysqli_query($con,"SELECT `id` FROM `kepaniteraan` ORDER BY `id`");
  while ($data_stase=mysqli_fetch_array($stase))
  {
    $stase_id = "stase_".$data_stase[id];
    $internal_id = "internal_".$data_stase[id];
    $delete_stase = mysqli_query($con,"DELETE FROM `$stase_id` WHERE `nim`='$data[nim]'");
    $delete_internal = mysqli_query($con,"DELETE FROM `$internal_id` WHERE `nim`='$data[nim]'");
  }
}
*/

$mhsw_ppp70 = mysqli_query($con,"SELECT `nim_koas` FROM `nim_ppp_70`");
$no = 1;
while ($data = mysqli_fetch_array($mhsw_ppp70))
{
  $update_ppp = mysqli_query($con,"UPDATE `biodata_mhsw` SET `angkatan`='PPP70' WHERE `nim`='$data[nim_koas]'");
  if ($update_ppp) echo "$no. NIM $data[nim_koas] ---> PPP70 UPDATED<br>";
  else echo "$no. NIM $data[nim_koas] ---> FAILED<br>";
  $no++;
}
?>
