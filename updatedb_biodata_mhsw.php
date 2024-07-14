<HTML>
<head>
</head>

<BODY>
<?php

include "config.php";
include "fungsi.php";
set_time_limit(0);
error_reporting("E_ALL ^ E_NOTICE");

$data_user = mysqli_query($con,"SELECT * FROM `admin` WHERE `level`='5' ORDER BY `username`");
$no=1;
while ($data=mysqli_fetch_array($data_user))
{
  $cek_mhsw = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `biodata_mhsw` WHERE `nim`='$data[username]'"));
  if ($cek_mhsw<1)
  {
    echo "$no - $data[username] - $data[nama] ... belum masuk<br>";
    $no++;
    $nama = addslashes($data[nama]);
    $insert_biodata = mysqli_query($con,"INSERT INTO `biodata_mhsw`
      ( `nama`, `nim`, `angkatan`,
        `grup`, `kota_lahir`, `prop_lahir`,
        `tanggal_lahir`, `alamat`, `kota_alamat`,
        `prop_alamat`, `no_hp`, `email`,
        `nama_ortu`, `alamat_ortu`, `kota_ortu`,
        `prop_ortu`, `no_hportu`, `foto`,
        `dosen_wali`)
      VALUES
      ( '$nama','$data[username]','PPP72',
        '1','','',
        '2001-01-01','','',
        '','','',
        '','','',
        '','','profil_blank.png',
        '')");
    if (!$insert_biodata) echo "Masukkan Biodata Error ....<br>";
    else echo "Masukkan Biodata OK ....<br>";
  }
}
$no--;
echo "<br>Ada sejumlah $no mhsw blm masuk biodata ...";
?>



<!--</body></html>-->
</BODY>
</HTML>
