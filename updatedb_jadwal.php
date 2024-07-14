<?php
include "config.php";
set_time_limit(0);
error_reporting("E_ALL ^ E_NOTICE");

//Semester 9
$data_semester9 = mysqli_query($con,"SELECT * FROM `semester_9_18mei` ORDER BY `nim`");
$nim=0;
$i=1;
while ($data9=mysqli_fetch_array($data_semester9))
{
  $jml_stase = 5;
  for ($i = 1; $i < ($jml_stase+1); $i++)
  {
    $stase_i = "stase".$i;
    $tglmulai_i = "mulai_stase".$i;
    $tglselesai_i = "akhir_stase".$i;
    if ($data9[$stase_i]!=M000)
    {
      $stase_id = "stase_".$data9[$stase_i];
      $cek_nim = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `$stase_id` WHERE `nim`='$data9[nim]'"));
      if ($cek_nim>=1)
      {
        $update_stase = mysqli_query($con,"UPDATE `$stase_id`
          SET `rotasi`='$i',`tgl_mulai`='$data9[$tglmulai_i]',`tgl_selesai`='$data9[$tglselesai_i]',`status`='0',`evaluasi`='0' WHERE `nim`='$data9[nim]'");
      }
      else
      {
        $insert_stase = mysqli_query($con,"INSERT INTO `$stase_id`
          ( `nim`, `rotasi`,
            `tgl_mulai`, `tgl_selesai`,
            `status`, `evaluasi`)
          VALUES
          ( '$data9[nim]','$i',
            '$data9[$tglmulai_i]','$data9[$tglselesai_i]',
            '0','0')");
      }
    }
  }
  $nim++;
}
echo "<br><br><<  Update data Semester 9 selesai, jumlah $nim mahasiswa >><br><br>";

//Semester 10
$data_semester10 = mysqli_query($con,"SELECT * FROM `semester_10_18mei` ORDER BY `nim`");
$nim=0;
$i=1;
while ($data10=mysqli_fetch_array($data_semester10))
{
  $jml_stase = 6;
  for ($i = 1; $i < ($jml_stase+1); $i++)
  {
    $stase_i = "stase".$i;
    $tglmulai_i = "mulai_stase".$i;
    $tglselesai_i = "akhir_stase".$i;
    if ($data10[$stase_i]!=M000)
    {
      $stase_id = "stase_".$data10[$stase_i];
      $cek_nim = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `$stase_id` WHERE `nim`='$data10[nim]'"));
      if ($cek_nim>=1)
      {
        $update_stase = mysqli_query($con,"UPDATE `$stase_id`
          SET `rotasi`='$i',`tgl_mulai`='$data10[$tglmulai_i]',`tgl_selesai`='$data10[$tglselesai_i]',`status`='0',`evaluasi`='0' WHERE `nim`='$data10[nim]'");
      }
      else
      {
        $insert_stase = mysqli_query($con,"INSERT INTO `$stase_id`
          ( `nim`, `rotasi`,
            `tgl_mulai`, `tgl_selesai`,
            `status`, `evaluasi`)
          VALUES
          ( '$data10[nim]','$i',
            '$data10[$tglmulai_i]','$data10[$tglselesai_i]',
            '0','0')");
      }
    }
  }
  $nim++;
}
echo "<br><br><<  Update data Semester 10 selesai, jumlah $nim mahasiswa >><br><br>";

//Semester 11
$data_semester11 = mysqli_query($con,"SELECT * FROM `semester_11_18mei` ORDER BY `nim`");
$nim=0;
$i=1;
while ($data11=mysqli_fetch_array($data_semester11))
{
  $jml_stase = 4;
  for ($i = 1; $i < ($jml_stase+1); $i++)
  {
    $stase_i = "stase".$i;
    $tglmulai_i = "mulai_stase".$i;
    $tglselesai_i = "akhir_stase".$i;
    if ($data11[$stase_i]!=M000)
    {
      $stase_id = "stase_".$data11[$stase_i];
      $cek_nim = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `$stase_id` WHERE `nim`='$data11[nim]'"));
      if ($cek_nim>=1)
      {
        $update_stase = mysqli_query($con,"UPDATE `$stase_id`
          SET `rotasi`='$i',`tgl_mulai`='$data11[$tglmulai_i]',`tgl_selesai`='$data11[$tglselesai_i]',`status`='0',`evaluasi`='0' WHERE `nim`='$data11[nim]'");
      }
      else
      {
        $insert_stase = mysqli_query($con,"INSERT INTO `$stase_id`
          ( `nim`, `rotasi`,
            `tgl_mulai`, `tgl_selesai`,
            `status`, `evaluasi`)
          VALUES
          ( '$data11[nim]','$i',
            '$data11[$tglmulai_i]','$data11[$tglselesai_i]',
            '0','0')");
      }
    }
  }
  $nim++;
}
echo "<br><br><<  Update data Semester 11 selesai, jumlah $nim mahasiswa >><br><br>";
?>
