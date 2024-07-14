<?php
include "config.php";
set_time_limit(0);
error_reporting("E_ALL ^ E_NOTICE");

$data_admin = mysqli_query($con,"SELECT * FROM `admin_123`");
$no=0;
while ($data=mysqli_fetch_array($data_admin))
{
  $insert_admin = mysqli_query($con,"INSERT INTO `admin`
    (`nama`, `username`, `password`, `level`)
    VALUES
    ('$data[nama]','$data[username]','$data[password]','$data[level]')");
  if ($data[level]=='1')
  {
    $insert_dosen = mysqli_query($con,"INSERT INTO `dosen`
      (`nip`, `nama`, `gelar`,
        `kode_bagian`, `pin`, `qr`)
      VALUES ('$data[username]','$data[nama]','Admin Fakultas/Prodi',
        'FK000','','')");
  }
  if ($data[level]=='2')
  {
    $insert_dosen = mysqli_query($con,"INSERT INTO `dosen`
      (`nip`, `nama`, `gelar`,
        `kode_bagian`, `pin`, `qr`)
      VALUES ('$data[username]','$data[nama]','Admin Bagian',
        'FK000','','')");
  }
  if ($data[level]=='3')
  {
    $insert_dosen = mysqli_query($con,"INSERT INTO `dosen`
      (`nip`, `nama`, `gelar`,
        `kode_bagian`, `pin`, `qr`)
      VALUES ('$data[username]','$data[nama]','Kepala Bagian',
        'FK000','','')");
  }

  $no++;
}
echo "<br><br><<  Update data= $no data >>";
?>
