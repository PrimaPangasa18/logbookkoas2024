<?php
include "config.php";
set_time_limit(0);
error_reporting("E_ALL ^ E_NOTICE");

$data_jurnal_penyakit = mysqli_query($con,"SELECT `id`,`jam_awal` FROM `jurnal_penyakit` ORDER BY `id`");
$data_jurnal_ketrampilan = mysqli_query($con,"SELECT `id`,`jam_awal` FROM `jurnal_ketrampilan` ORDER BY `id`");
$no=0;
while ($data1=mysqli_fetch_array($data_jurnal_penyakit))
{
  $jam_mulai_kegiatan = strtotime($data1[jam_awal]);
  $datakelas=mysqli_query($con,"SELECT * FROM `kelas` ORDER BY `id`");
  $kelas = "0";
  while ($data_kelas=mysqli_fetch_array($datakelas))
  {
    $jam_mulai_kelas = strtotime($data_kelas[jam_mulai]);
    $jam_selesai_kelas = strtotime($data_kelas[jam_selesai]);
    if ($jam_mulai_kegiatan>=$jam_mulai_kelas AND $jam_mulai_kegiatan<=$jam_selesai_kelas)
      $kelas = $data_kelas[id];
  }
  $update_jurnal_penyakit=mysqli_query($con,"UPDATE `jurnal_penyakit` SET
    `kelas`='$kelas'
     WHERE `id`='$data1[id]'");
  $no++;
}
echo "<br><br><<  Update data jurnal penyakit= $no data >>";

$no=0;
while ($data2=mysqli_fetch_array($data_jurnal_ketrampilan))
{
  $jam_mulai_kegiatan = strtotime($data2[jam_awal]);
  $datakelas=mysqli_query($con,"SELECT * FROM `kelas` ORDER BY `id`");
  $kelas = "0";
  while ($data_kelas=mysqli_fetch_array($datakelas))
  {
    $jam_mulai_kelas = strtotime($data_kelas[jam_mulai]);
    $jam_selesai_kelas = strtotime($data_kelas[jam_selesai]);
    if ($jam_mulai_kegiatan>=$jam_mulai_kelas AND $jam_mulai_kegiatan<=$jam_selesai_kelas)
      $kelas = $data_kelas[id];
  }
  $update_jurnal_penyakit=mysqli_query($con,"UPDATE `jurnal_ketrampilan` SET
    `kelas`='$kelas'
     WHERE `id`='$data2[id]'");
  $no++;
}
echo "<br><br><<  Update data jurnal ketrampilan= $no data >>";
?>
