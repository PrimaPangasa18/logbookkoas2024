<?php
include "config.php";
set_time_limit(0);
error_reporting("E_ALL ^ E_NOTICE");


$data_koas = mysqli_query($con,"SELECT * FROM `import_jadwal_22feb21`");
$no = 0;
while ($data=mysqli_fetch_array($data_koas))
{
  $nim_mhsw = $data[nim];
  for ($i = 1; $i < 8; $i++)
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
        $info_jadwal = mysqli_fetch_array(mysqli_query($con,"SELECT `tgl_mulai`,`tgl_selesai`,`rotasi`,`status` FROM `$stase_id` WHERE `nim`='$nim_mhsw'"));
        $info_mulai = date_create($info_jadwal[tgl_mulai]);
        $info_selesai = date_create($info_jadwal[tgl_selesai]);
        $data_mulai = date_create($tgl_mulai);
        $data_selesai = date_create($tgl_selesai);
        $duasatu_feb = date_create('2021-02-21');
        $duadua_feb = date_create('2021-02-22');

        $status = 0;
        $rotasi = 9;

        //Kasus stase sudah selesai dan tdk ada perubahan jadwal
        if (($info_mulai==$data_mulai) AND ($info_selesai==$data_selesai))
        {
          $status = 2;
          $rotasi = $info_jadwal[rotasi];
        }
        //Kasus melanjutkan stase
        if (($info_selesai==$duasatu_feb) AND ($data_mulai<=$duadua_feb) AND ($data_selesai>$duadua_feb))
        {
          $status = 1;
          $rotasi = 9;
        }
        //Kasus stase tambahan
        if (($info_selesai<$duadua_feb) AND ($data_mulai>$duadua_feb))
        {
          $status = 0;
          $rotasi = 9;
        }

        $update_jadwal=mysqli_query($con,"UPDATE `$stase_id` SET `rotasi`='$rotasi',`tgl_mulai`='$tgl_mulai',`tgl_selesai`='$tgl_selesai',`status`='$status' WHERE `nim`='$nim_mhsw'");
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
