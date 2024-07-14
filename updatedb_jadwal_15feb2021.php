<?php
include "config.php";
set_time_limit(0);
error_reporting("E_ALL ^ E_NOTICE");

/*
//Delete data stase satu semester kecuali yang masih aktif atau sudah selesai
$data_koas = mysqli_query($con,"SELECT * FROM `import_jadwal_15feb21`");
$no = 0;
while ($data=mysqli_fetch_array($data_koas))
{
  $nim_mhsw = $data[nim];
  $semester = substr($data[stase1], 1, 2);

  for ($i = 1; $i < 7; $i++)
  {
    $stase_id = "M".$semester.$i;
    $stase = "stase_".$stase_id;
    $jadwal = mysqli_query($con,"SELECT `id`,`status` FROM `$stase` WHERE `nim`='$nim_mhsw'");
    $cek_jadwal = mysqli_num_rows($jadwal);
    if ($cek_jadwal>0)
    {
      $isi_jadwal = mysqli_fetch_array($jadwal);
      $cek = "ADA";
      $cek_status = $isi_jadwal[status];
      $aksi = "BIARKAN";
      if ($cek_status=='0')
      {
        $delete_jadwal = mysqli_query($con,"DELETE FROM `$stase` WHERE `nim`='$nim_mhsw'");
        $aksi = "HAPUS";
      }
    }
    else
    {
      $cek = "TIDAK ADA";
      $cek_status = "0";
      $aksi = "-";
    }
    echo "$nim_mhsw (Semester: $semester) --> $i - $stase_id ==> $cek, $cek_status, $aksi<br>";
  }
  echo "<br>";

  $no++;
}

*/

$data_koas = mysqli_query($con,"SELECT * FROM `import_jadwal_15feb21`");
$no = 0;
while ($data=mysqli_fetch_array($data_koas))
{
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

    if ($stase!="0")
    {
      $ada_jadwal = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `$stase_id` WHERE `nim`='$nim_mhsw'"));
      if ($ada_jadwal>0)
      {
        $update_jadwal=mysqli_query($con,"UPDATE `$stase_id` SET `rotasi`='$rotasi',`tgl_mulai`='$tgl_mulai',`tgl_selesai`='$tgl_selesai' WHERE `nim`='$nim_mhsw'");
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
            '1','0')");
        if ($insert_jadwal) echo "$nim_mhsw - $stase_id - INSERTED <br>";
        else echo "$nim_mhsw - $stase_id - INSERT ERROR ............... <br>";
      }
    }
  }

  $no++;
}


echo "<br><br><< Update data jumlah $no mahasiswa koas >><br><br>";
?>
