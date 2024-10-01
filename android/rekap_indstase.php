<?php
include 'connect.php';

// Get the input request parameters
$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, TRUE); // Convert JSON into array
error_reporting(E_ERROR | E_PARSE);
// Validate input
if (!isset($input['id']) || !isset($input['username'])) {
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

$id_stase = $input['id'];
$username = $input['username'];
$include_id = "include_".$id_stase;
$target_id = "target_".$id_stase;

// Query data for stase
$data_stase = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
$stase_id = "stase_" . $id_stase;
$data_stase_mhsw = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `$stase_id` WHERE `nim`='$username'"));
$mulai_stase = date_create($data_stase_mhsw['tgl_mulai']);
$akhir_stase = date_create($data_stase_mhsw['tgl_selesai']);
$hari_stase = date_diff($mulai_stase, $akhir_stase);
$batas_tengah = (int)(($hari_stase->days + 1) / 2) - 4;

// Process Jurnal Penyakit
$data_jurnal_penyakit = mysqli_query($conn, "SELECT * FROM `jurnal_penyakit` WHERE `nim`='$username' AND `stase`='$id_stase' AND `status`='1'");
$delete_dummy_penyakit = mysqli_query($conn, "DELETE FROM `jurnal_penyakit_dummy` WHERE `username` LIKE '%$username%'");

while ($data = mysqli_fetch_array($data_jurnal_penyakit)) {
    $insert_penyakit = mysqli_query($conn, "INSERT INTO `jurnal_penyakit_dummy` (
        `id`, `nim`, `nama`, `angkatan`, `grup`, `hari`, `tanggal`, `stase`,
        `jam_awal`, `jam_akhir`, `kelas`, `lokasi`, `kegiatan`, `penyakit1`, 
        `penyakit2`, `penyakit3`, `penyakit4`, `dosen`, `status`, `evaluasi`, `username`
    ) VALUES (
        '{$data['id']}', '{$data['nim']}', '{$data['nama']}', '{$data['angkatan']}', '{$data['grup']}', 
        '{$data['hari']}', '{$data['tanggal']}', '{$data['stase']}', '{$data['jam_awal']}', 
        '{$data['jam_akhir']}', '{$data['kelas']}', '{$data['lokasi']}', '{$data['kegiatan']}', 
        '{$data['penyakit1']}', '{$data['penyakit2']}', '{$data['penyakit3']}', '{$data['penyakit4']}', 
        '{$data['dosen']}', '{$data['status']}', '{$data['evaluasi']}', '$username'
    )");
}

// Process Jurnal Ketrampilan
$data_jurnal_ketrampilan = mysqli_query($conn, "SELECT * FROM `jurnal_ketrampilan` WHERE `nim`='$username' AND `stase`='$id_stase' AND `status`='1'");
$delete_dummy_ketrampilan = mysqli_query($conn, "DELETE FROM `jurnal_ketrampilan_dummy` WHERE `username` LIKE '%$username%'");

while ($data = mysqli_fetch_array($data_jurnal_ketrampilan)) {
    $insert_ketrampilan = mysqli_query($conn, "INSERT INTO `jurnal_ketrampilan_dummy` (
        `id`, `nim`, `nama`, `angkatan`, `grup`, `hari`, `tanggal`, `stase`,
        `jam_awal`, `jam_akhir`, `kelas`, `lokasi`, `kegiatan`, `ketrampilan1`, 
        `ketrampilan2`, `ketrampilan3`, `ketrampilan4`, `dosen`, `status`, `evaluasi`, `username`
    ) VALUES (
        '{$data['id']}', '{$data['nim']}', '{$data['nama']}', '{$data['angkatan']}', '{$data['grup']}', 
        '{$data['hari']}', '{$data['tanggal']}', '{$data['stase']}', '{$data['jam_awal']}', 
        '{$data['jam_akhir']}', '{$data['kelas']}', '{$data['lokasi']}', '{$data['kegiatan']}', 
        '{$data['ketrampilan1']}', '{$data['ketrampilan2']}', '{$data['ketrampilan3']}', 
        '{$data['ketrampilan4']}', '{$data['dosen']}', '{$data['status']}', '{$data['evaluasi']}', '$username'
    )");
}

// Fetch daftar penyakit
$daftar_penyakit = mysqli_query($conn, "SELECT * FROM `daftar_penyakit` WHERE `$include_id`='1' ORDER BY `$target_id` DESC, `penyakit` ASC");
$penyakit_results = [];

$jml_ketuntasan = 0;
$item = 0;
$ketuntasan = 0;

while ($data = mysqli_fetch_array($daftar_penyakit)) {
    if ($data['skdi_level'] == "MKM" || $data['k_level'] == "MKM" || $data['ipsg_level'] == "MKM" || $data['kml_level'] == "MKM") {
        $jml_MKM = mysqli_num_rows(mysqli_query($conn, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$username' AND `stase`='$id_stase'
            AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
            AND `status`='1' AND `evaluasi`='1' AND `username`='$username'"));
        
        $jml_1 = 0;
        $jml_2 = 0;
        $jml_3 = 0;
        $jml_4A = 0;
        $jml_U = 0;
    } else {
        $jml_1 = mysqli_num_rows(mysqli_query($conn, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$username' AND `stase`='$id_stase'
            AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]' OR `penyakit4`='$data[id]')
            AND (`kegiatan`='1' OR `kegiatan`='2')
            AND `status`='1' AND `evaluasi`='1' AND `username`='$username'"));
        $jml_2 = mysqli_num_rows(mysqli_query($conn, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$username' AND `stase`='$id_stase'
            AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
            AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='5' OR `kegiatan`='6')
            AND `status`='1' AND `evaluasi`='1' AND `username`='$username'"));
        $jml_3 = mysqli_num_rows(mysqli_query($conn, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$username' AND `stase`='$id_stase'
            AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
            AND (`kegiatan`='7' OR `kegiatan`='8' OR `kegiatan`='9')
            AND `status`='1' AND `evaluasi`='1' AND `username`='$username'"));
        $jml_4A = mysqli_num_rows(mysqli_query($conn, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$username' AND `stase`='$id_stase'
            AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
            AND `kegiatan`='10'
            AND `status`='1' AND `evaluasi`='1' AND `username`='$username'"));
        $jml_U = mysqli_num_rows(mysqli_query($conn, "SELECT `id` FROM `jurnal_penyakit_dummy` WHERE `nim`='$username' AND `stase`='$id_stase'
            AND (`penyakit1`='$data[id]' OR `penyakit2`='$data[id]' OR `penyakit3`='$data[id]'  OR `penyakit4`='$data[id]')
            AND (`kegiatan`='11' OR `kegiatan`='12')
            AND `status`='1' AND `evaluasi`='1' AND `username`='$username'"));
        $jml_MKM = 0;
    }

    $jumlah_total = $jml_1 + $jml_2 + $jml_3 + $jml_4A + $jml_MKM + $jml_U;
    $warna = "putih";



    //Kasus tidak ada target
    if ($data[$target_id]<1)
    {
      if ($jumlah_total>0)
      {
        //Blok warna hijau tua
        $ketuntasan = 100;
        $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
        $item++;
        $warna = "hijau_tua";
      }
      
    }
    else
    //Kasus ada target
    {
      $blocked = 0;
      //Start - Pewarnaan Capaian Level 4A
      if (($data['skdi_level']=="4A" OR $data['k_level']=="4A" OR $data['ipsg_level']=="4A" OR $data['kml_level']=="4A") AND $blocked == 0)
      {
        $batas_target = $data[$target_id]/2;
        $blocked = 1;
        if ($jml_4A>$batas_target)
        {
          //Blok warna hijau tua
          $ketuntasan = 100;
          $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
          $item++;
          $warna = "hijau_tua";
        }
        else
        {
          if ($jml_4A<=$batas_target AND $jml_4A>=1)
          //Blok warna hijau muda
          {
            $ketuntasan = 75;
            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
            $item++;
            $warna = "hijau_muda";
          }
          if ($jml_4A<1)
          {
            if ($jml_3>=$batas_target)
            //Blok warna hijau muda
            {
              $ketuntasan = 75;
              $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
              $item++;
              $warna = "hijau_muda";
            }
            else
            {
              if ($jumlah_total>=1)
              //Blok warna kuning
              {
                $ketuntasan = 50;
                $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
                $item++;
                $warna = "kuning";
              }
              else
              //Blok warna merah
              {
                $ketuntasan = 0;
                $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
                $item++;
                $warna = "merah";
              }
            }
          }
        }
      }
      //End - Pewarnaan Capaian Level 4A

      //Start - Pewarnaan Capaian Level 3A dan 3B
      if (($data['skdi_level']=="3" OR $data['k_level']=="3" OR $data['ipsg_level']=="3" OR $data['kml_level']=="3"
          OR $data['skdi_level']=="3A" OR $data['k_level']=="3A" OR $data['ipsg_level']=="3A" OR $data['kml_level']=="3A"
          OR $data['skdi_level']=="3B" OR $data['k_level']=="3B" OR $data['ipsg_level']=="3B" OR $data['kml_level']=="3B")
          AND $blocked == 0)
      {
        $batas_target = $data[$target_id]/2;
        $blocked = 1;
        if ($jml_3>$batas_target OR $jml_4A>$batas_target)
        {
          //Blok warna hijau tua
          $ketuntasan = 100;
          $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
          $item++;
          $warna = "hijau_tua";
        }
        else
        {
          if (($jml_3<=$batas_target AND $jml_3>=1) OR ($jml_4A<=$batas_target AND $jml_4A>=1))
          //Blok warna hijau muda
          {
            $ketuntasan = 75;
            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
            $item++;
            $warna = "hijau_muda";
          }
          else
          {
            if ($jumlah_total>=1)
            //Blok warna kuning
            {
              $ketuntasan = 50;
              $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
              $item++;
              $warna = "kuning";
            }
            else
            //Blok warna merah
            {
              $ketuntasan = 0;
              $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
              $item++;
              $warna = "merah";
            }
          }
        }
      }
      //End - Pewarnaan Capaian Level 3A dan 3B

      //Start - Pewarnaan Capaian Level 2
      if (($data['skdi_level']=="2" OR $data['k_level']=="2" OR $data['ipsg_level']=="2" OR $data['kml_level']=="2") AND $blocked == 0)
      {
        $batas_target = $data[$target_id]/2;
        $blocked = 1;
        if ($jml_2>$batas_target OR $jml_3>$batas_target OR $jml_4A>$batas_target)
        {
          //Blok warna hijau tua
          $ketuntasan = 100;
          $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
          $item++;
          $warna = "hijau_tua";
        }
        else
        {
          if (($jml_2<=$batas_target AND $jml_2>=1) OR ($jml_3<=$batas_target AND $jml_3>=1) OR ($jml_4A<=$batas_target AND $jml_4A>=1))
          //Blok warna hijau muda
          {
            $ketuntasan = 75;
            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
            $item++;
            $warna = "hijau_muda";
          }
          else
          {
            if ($jml_1>=1)
            //Blok warna kuning
            {
              $ketuntasan = 50;
              $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
              $item++;
              $warna = "kuning";
            }
            else
            //Blok warna merah
            {
              $ketuntasan = 0;
              $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
              $item++;
              $warna = "merah";
            }
          }
        }
      }
      //End - Pewarnaan Capaian Level 2

      //Start - Pewarnaan Capaian Level 1
      if (($data['skdi_level']=="1" OR $data['k_level']=="1" OR $data['ipsg_level']=="1" OR $data['kml_level']=="1") AND $blocked == 0)
      {
        $batas_target = $data[$target_id]/2;
        $blocked = 1;
        if ($jml_1>$batas_target OR $jml_2>$batas_target OR $jml_3>$batas_target OR $jml_4A>$batas_target)
        {
          //Blok warna hijau tua
          $ketuntasan = 100;
          $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
          $item++;
          $warna = "hijau_tua";
        }
        else
        {
          if ($jumlah_total>=1)
          //Blok warna hijau muda
          {
            $ketuntasan = 75;
            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
            $item++;
            $warna = "hijau_tua";
          }
          else
          {
            //Blok warna merah
            $ketuntasan = 0;
            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
            $item++;
            $warna = "merah";
          }
        }
      }
      //End - Pewarnaan Capaian Level 1

      //Start - Pewarnaan Capaian MKM
      if (($data['skdi_level']=="MKM" OR $data['k_level']=="MKM" OR $data['ipsg_level']=="MKM" OR $data['kml_level']=="MKM") AND $blocked == 0)
      {
        $batas_target = $data[$target_id]/2;
        $blocked = 1;
        if ($jml_MKM>$batas_target)
        {
          //Blok warna hijau tua
          $ketuntasan = 100;
          $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
          $item++;
          $warna = "hijau_tua";
        }
        else
        {
          if ($jml_MKM<=$batas_target AND $jml_MKM>=1)
          //Blok warna hijaumuda
          {
            $ketuntasan = 75;
            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
            $item++;
            $warna = "hijau_muda";
          }
          else
          {
            //Blok warna kuning
            if ($jml_1>=1 OR $jml_2>=1 OR $jml_3>=1 OR $jml_4A>=1 OR $jml_U>=1)
            {
              $ketuntasan = 50;
              $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
              $item++;
              $warna = "kuning";
            }
            else
            //Blok warna merah
            {
              $ketuntasan = 0;
              $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
              $item++;
              $warna = "merah";
            }
          }
        }
      }
      //End - Pewarnaan Capaian MKM
    }







    $ketuntasan_rata = $jml_ketuntasan/$item;
    if ($ketuntasan_rata<=100 AND $ketuntasan_rata>=80) $grade = "A";
    if ($ketuntasan_rata<80 AND $ketuntasan_rata>=70) $grade = "B";
    if ($ketuntasan_rata<70 AND $ketuntasan_rata>=60) $grade = "C";
    if ($ketuntasan_rata<60 AND $ketuntasan_rata>=50) $grade = "D";
    if ($ketuntasan_rata<50) $grade = "E";
    $ketuntasan_rata_penyakit = number_format($ketuntasan_rata,2);
    $grade_penyakit = $grade;


    $target_penyakit = $data[$target_id];
    $level_penyakit = $data['skdi_level']."/".$data['k_level']."/".$data['ipsg_level']."/".$data['kml_level'];
    $penyakit_results[] = [
        'id' => $data['id'],
        'penyakit' => $data['penyakit'],
        'jml_1' => $jml_1,
        'jml_2' => $jml_2,
        'jml_3' => $jml_3,
        'jml_4A' => $jml_4A,
        'jml_MKM' => $jml_MKM,
        'jml_U' => $jml_U,
        'jumlah_total' => $jumlah_total,
        'target_penyakit' =>  $target_penyakit,
        'level_penyakit' => $level_penyakit,
        'warna' => $warna
    ];
}

// Fetch daftar ketrampilan
$daftar_ketrampilan = mysqli_query($conn, "SELECT * FROM `daftar_ketrampilan` WHERE `$include_id`='1' ORDER BY `$target_id` DESC, `ketrampilan` ASC");
$ketrampilan_results = [];

$jml_ketuntasan = 0;
$item = 0;
$ketuntasan = 0;

while ($data = mysqli_fetch_array($daftar_ketrampilan)) {
    if ($data['skdi_level'] == "MKM" || $data['k_level'] == "MKM" || $data['ipsg_level'] == "MKM" || $data['kml_level'] == "MKM") {
        $jml_MKM = mysqli_num_rows(mysqli_query($conn, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$username' AND `stase`='$id_stase'
            AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
            AND `status`='1' AND `evaluasi`='1' AND `username`='$username'"));
        $jml_1 = 0;
        $jml_2 = 0;
        $jml_3 = 0;
        $jml_4A = 0;
        $jml_U = 0;
    } else {
        $jml_1 = mysqli_num_rows(mysqli_query($conn, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$username' AND `stase`='$id_stase'
            AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
            AND (`kegiatan`='1' OR `kegiatan`='2')
            AND `status`='1' AND `evaluasi`='1' AND `username`='$username'"));
        $jml_2 = mysqli_num_rows(mysqli_query($conn, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$username' AND `stase`='$id_stase'
            AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
            AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='5' OR `kegiatan`='6')
            AND `status`='1' AND `evaluasi`='1' AND `username`='$username'"));
        $jml_3 = mysqli_num_rows(mysqli_query($conn, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$username' AND `stase`='$id_stase'
            AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
            AND (`kegiatan`='7' OR `kegiatan`='8' OR `kegiatan`='9')
            AND `status`='1' AND `evaluasi`='1' AND `username`='$username'"));
        $jml_4A = mysqli_num_rows(mysqli_query($conn, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$username' AND `stase`='$id_stase'
            AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
            AND `kegiatan`='10'
            AND `status`='1' AND `evaluasi`='1' AND `username`='$username'"));
        $jml_U = mysqli_num_rows(mysqli_query($conn, "SELECT `id` FROM `jurnal_ketrampilan_dummy` WHERE `nim`='$username' AND `stase`='$id_stase'
            AND (`ketrampilan1`='$data[id]' OR `ketrampilan2`='$data[id]' OR `ketrampilan3`='$data[id]'  OR `ketrampilan4`='$data[id]')
            AND (`kegiatan`='11' OR `kegiatan`='12')
            AND `status`='1' AND `evaluasi`='1' AND `username`='$username'"));
        $jml_MKM = 0;
    }
    $jumlah_total = $jml_1 + $jml_2 + $jml_3 + $jml_4A + $jml_MKM + $jml_U;
    $warna = "putih";



    //Kasus tidak ada target
    if ($data[$target_id]<1)
    {
      if ($jumlah_total>0)
      {
        //Blok warna hijau tua
        $ketuntasan = 100;
        $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
        $item++;
        $warna = "hijau_tua";
      }
      
    }
    else
    //Kasus ada target
    {
      $blocked = 0;
      //Start - Pewarnaan Capaian Level 4A
      if (($data['skdi_level']=="4A" OR $data['k_level']=="4A" OR $data['ipsg_level']=="4A" OR $data['kml_level']=="4A") AND $blocked == 0)
      {
        $batas_target = $data[$target_id]/2;
        $blocked = 1;
        if ($jml_4A>$batas_target)
        {
          //Blok warna hijau tua
          $ketuntasan = 100;
          $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
          $item++;
          $warna = "hijau_tua";
        }
        else
        {
          if ($jml_4A<=$batas_target AND $jml_4A>=1)
          //Blok warna hijau muda
          {
            $ketuntasan = 75;
            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
            $item++;
            $warna = "hijau_muda";
          }
          if ($jml_4A<1)
          {
            if ($jml_3>=$batas_target)
            //Blok warna hijau muda
            {
              $ketuntasan = 75;
              $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
              $item++;
              $warna = "hijau_muda";
            }
            else
            {
              if ($jumlah_total>=1)
              //Blok warna kuning
              {
                $ketuntasan = 50;
                $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
                $item++;
                $warna = "kuning";
              }
              else
              //Blok warna merah
              {
                $ketuntasan = 0;
                $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
                $item++;
                $warna = "merah";
              }
            }
          }
        }
      }
      //End - Pewarnaan Capaian Level 4A

      //Start - Pewarnaan Capaian Level 3A dan 3B
      if (($data['skdi_level']=="3" OR $data['k_level']=="3" OR $data['ipsg_level']=="3" OR $data['kml_level']=="3"
          OR $data['skdi_level']=="3A" OR $data['k_level']=="3A" OR $data['ipsg_level']=="3A" OR $data['kml_level']=="3A"
          OR $data['skdi_level']=="3B" OR $data['k_level']=="3B" OR $data['ipsg_level']=="3B" OR $data['kml_level']=="3B")
          AND $blocked == 0)
      {
        $batas_target = $data[$target_id]/2;
        $blocked = 1;
        if ($jml_3>$batas_target OR $jml_4A>$batas_target)
        {
          //Blok warna hijau tua
          $ketuntasan = 100;
          $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
          $item++;
          $warna = "hijau_tua";
        }
        else
        {
          if (($jml_3<=$batas_target AND $jml_3>=1) OR ($jml_4A<=$batas_target AND $jml_4A>=1))
          //Blok warna hijau muda
          {
            $ketuntasan = 75;
            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
            $item++;
            $warna = "hijau_muda";
          }
          else
          {
            if ($jumlah_total>=1)
            //Blok warna kuning
            {
              $ketuntasan = 50;
              $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
              $item++;
              $warna = "kuning";
            }
            else
            //Blok warna merah
            {
              $ketuntasan = 0;
              $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
              $item++;
              $warna = "merah";
            }
          }
        }
      }
      //End - Pewarnaan Capaian Level 3A dan 3B

      //Start - Pewarnaan Capaian Level 2
      if (($data['skdi_level']=="2" OR $data['k_level']=="2" OR $data['ipsg_level']=="2" OR $data['kml_level']=="2") AND $blocked == 0)
      {
        $batas_target = $data[$target_id]/2;
        $blocked = 1;
        if ($jml_2>$batas_target OR $jml_3>$batas_target OR $jml_4A>$batas_target)
        {
          //Blok warna hijau tua
          $ketuntasan = 100;
          $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
          $item++;
          $warna = "hijau_tua";
        }
        else
        {
          if (($jml_2<=$batas_target AND $jml_2>=1) OR ($jml_3<=$batas_target AND $jml_3>=1) OR ($jml_4A<=$batas_target AND $jml_4A>=1))
          //Blok warna hijau muda
          {
            $ketuntasan = 75;
            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
            $item++;
            $warna = "hijau_muda";
          }
          else
          {
            if ($jml_1>=1)
            //Blok warna kuning
            {
              $ketuntasan = 50;
              $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
              $item++;
              $warna = "kuning";
            }
            else
            //Blok warna merah
            {
              $ketuntasan = 0;
              $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
              $item++;
              $warna = "merah";
            }
          }
        }
      }
      //End - Pewarnaan Capaian Level 2

      //Start - Pewarnaan Capaian Level 1
      if (($data['skdi_level']=="1" OR $data['k_level']=="1" OR $data['ipsg_level']=="1" OR $data['kml_level']=="1") AND $blocked == 0)
      {
        $batas_target = $data[$target_id]/2;
        $blocked = 1;
        if ($jml_1>$batas_target OR $jml_2>$batas_target OR $jml_3>$batas_target OR $jml_4A>$batas_target)
        {
          //Blok warna hijau tua
          $ketuntasan = 100;
          $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
          $item++;
          $warna = "hijau_tua";
        }
        else
        {
          if ($jumlah_total>=1)
          //Blok warna hijau muda
          {
            $ketuntasan = 75;
            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
            $item++;
            $warna = "hijau_tua";
          }
          else
          {
            //Blok warna merah
            $ketuntasan = 0;
            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
            $item++;
            $warna = "merah";
          }
        }
      }
      //End - Pewarnaan Capaian Level 1

      //Start - Pewarnaan Capaian MKM
      if (($data['skdi_level']=="MKM" OR $data['k_level']=="MKM" OR $data['ipsg_level']=="MKM" OR $data['kml_level']=="MKM") AND $blocked == 0)
      {
        $batas_target = $data[$target_id]/2;
        $blocked = 1;
        if ($jml_MKM>$batas_target)
        {
          //Blok warna hijau tua
          $ketuntasan = 100;
          $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
          $item++;
          $warna = "hijau_tua";
        }
        else
        {
          if ($jml_MKM<=$batas_target AND $jml_MKM>=1)
          //Blok warna hijaumuda
          {
            $ketuntasan = 75;
            $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
            $item++;
            $warna = "hijau_muda";
          }
          else
          {
            //Blok warna kuning
            if ($jml_1>=1 OR $jml_2>=1 OR $jml_3>=1 OR $jml_4A>=1 OR $jml_U>=1)
            {
              $ketuntasan = 50;
              $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
              $item++;
              $warna = "kuning";
            }
            else
            //Blok warna merah
            {
              $ketuntasan = 0;
              $jml_ketuntasan = $jml_ketuntasan + $ketuntasan;
              $item++;
              $warna = "merah";
            }
          }
        }
      }
      //End - Pewarnaan Capaian MKM
    }







    $ketuntasan_rata = $jml_ketuntasan/$item;
    if ($ketuntasan_rata<=100 AND $ketuntasan_rata>=80) $grade = "A";
    if ($ketuntasan_rata<80 AND $ketuntasan_rata>=70) $grade = "B";
    if ($ketuntasan_rata<70 AND $ketuntasan_rata>=60) $grade = "C";
    if ($ketuntasan_rata<60 AND $ketuntasan_rata>=50) $grade = "D";
    if ($ketuntasan_rata<50) $grade = "E";
    $ketuntasan_rata_ketrampilan = number_format($ketuntasan_rata,2);
    $grade_ketrampilan = $grade;



   
    $target_ketrampilan = $data[$target_id];
    $level_ketrampilan = $data['skdi_level']."/".$data['k_level']."/".$data['ipsg_level']."/".$data['kml_level'];
    $ketrampilan_results[] = [
        'id' => $data['id'],
        'ketrampilan' => $data['ketrampilan'],
        'jml_1' => $jml_1,
        'jml_2' => $jml_2,
        'jml_3' => $jml_3,
        'jml_4A' => $jml_4A,
        'jml_MKM' => $jml_MKM,
        'jml_U' => $jml_U,
        'jumlah_total' => $jumlah_total,
        'target_ketrampilan' =>  $target_ketrampilan,
        'level_ketrampilan' => $level_ketrampilan,
        'warna' => $warna
    ];
}

// Prepare the response
$response = [
    'penyakit' => $penyakit_results,
    'ketuntasan_penyakit' => $ketuntasan_rata_penyakit,
    'grade_penyakit' => $grade_penyakit,
    'ketrampilan' => $ketrampilan_results,
    'ketuntasan_ketrampilan' => $ketuntasan_rata_ketrampilan,
    'grade_ketrampilan' => $grade_ketrampilan
];

// Set response header to JSON
header('Content-Type: application/json');

// Send JSON response
echo json_encode($response);

// Close database connection
mysqli_close($conn);
?>
