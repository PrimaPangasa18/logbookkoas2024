<?php
include "config.php";
set_time_limit(0);
error_reporting("E_ALL ^ E_NOTICE");


$data_koas = mysqli_query($con,"SELECT * FROM `import_jadwal_mulai_10jan2022`");
$no = 0;
while ($data=mysqli_fetch_array($data_koas))
{
  //Delete jadwal sebelumnya
  $data_stase = mysqli_query($con,"SELECT * FROM `kepaniteraan` ORDER BY `id`");
  while ($datastase=mysqli_fetch_array($data_stase))
  {
    $stase_id = "stase_".$datastase[id];
    $delete_jadwal = mysqli_query($con,"DELETE FROM `$stase_id` WHERE `nim`='$data[nim]' AND `tgl_mulai`>'2022-01-09'");
    if ($delete_jadwal) echo "Delete NIM $data[nim] : Stase $datastase[kepaniteraan] - DONE<br>";
    else echo "Delete NIM $data[nim] : Stase $datastase[kepaniteraan] - FAILED<br>";
  }


  $nim_mhsw = $data[nim];
  for ($i = 1; $i < 7; $i++)
  {
    $stase_i = "stase".$i;
    $mulai_i = "mulai".$i;
    $selesai_i = "selesai".$i;
    $stase = $data[$stase_i];
    $tgl_mulai = $data[$mulai_i];
    $tgl_selesai = $data[$selesai_i];
    $stase_id = "stase_".$stase;

    $rotasi=$i;

    if ($stase!="")
    {
      $ada_jadwal = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `$stase_id` WHERE `nim`='$nim_mhsw'"));
      if ($ada_jadwal>0)
      {
        $update_jadwal=mysqli_query($con,"UPDATE `$stase_id` SET `rotasi`='$rotasi',`tgl_mulai`='$tgl_mulai',`tgl_selesai`='$tgl_selesai',`status`='0',`evaluasi`='0' WHERE `nim`='$nim_mhsw'");
        if ($update_jadwal) echo "$nim_mhsw - $stase_id - UPDATED <br>";
        else echo "$nim_mhsw - $stase_id - UPDATE ERROR ............... <br>";
      }
      else
      {

        $insert_jadwal=mysqli_query($con,"INSERT INTO `$stase_id`
          ( `nim`, `rotasi`, `tgl_mulai`, `tgl_selesai`,
            `status`, `evaluasi`)
          VALUES
          ( '$nim_mhsw','$rotasi','$tgl_mulai','$tgl_selesai',
            '0','0')");
        if ($insert_jadwal) echo "$nim_mhsw - $stase_id - INSERTED <br>";
        else echo "$nim_mhsw - $stase_id - INSERT ERROR ............... <br>";
      }
    }
  }

  $no++;
}


echo "<br><br><< Update data jumlah $no mahasiswa koas >><br><br>";
?>
