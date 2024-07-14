<HTML>
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="menu.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="mytable.css" type="text/css" media="screen" />
  <script type="text/javascript" src="jquery.min.js"></script>
<!--</head>-->
</head>
<BODY>
<?php
include "config.php";
include "fungsi.php";

set_time_limit(0);
error_reporting("E_ALL ^ E_NOTICE");

if (empty($_COOKIE['user']) || empty($_COOKIE['pass'])){
	echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
}
else{
if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass']) AND $_COOKIE[level]==2)
{
  if ($_COOKIE['level']==2) {include "menu2.php";}

  echo "<div class=\"text_header\">IMPORT NILAI MAHASISWA</div>";
  echo "<br><br><br><fieldset class=\"fieldset_art\">
    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

  $stase_id = $_POST[stase];
  $jenis_nilai = $_POST[jenis_nilai];
  $nama_test = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `jenis_test` WHERE `id`='$jenis_nilai'"));
  $status_ujian = $_POST[status_ujian];
  $nama_status_ujian = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `status_ujian` WHERE `id`='$status_ujian'"));
  $tgl_ujian = $_POST[tgl_ujian];
  $tanggal_ujian = tanggal_indo($tgl_ujian);
  $tgl_approval = $_POST[tgl_approval];
  $tanggal_yudisium = tanggal_indo($tgl_approval);

  if(isset($_POST['import']) AND !empty($_FILES['import_nilai']['tmp_name']))
	{
		$file = $_FILES['import_nilai']['tmp_name'];
		$handle = fopen($file, "r");
    $separator = $_POST[separator];
    $kepaniteraan = mysqli_fetch_array(mysqli_query($con,"SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$stase_id'"));

    echo "<center><h4>Import Nilai Kepaniteraan/Stase $kepaniteraan[kepaniteraan]</h4><br>";
    echo "<table style=\"width:40%\">";
    echo "<tr><td style=\"width:50%\">Jenis Penilaian</td><td style=\"width:50%\">: $nama_test[jenis_test]</td></tr>";
    echo "<tr><td>Status Ujian</td><td>: $nama_status_ujian[status_ujian]</td></tr>";
    echo "<tr><td>Tanggal Ujian</td><td>: $tanggal_ujian</td></tr>";
    echo "<tr><td>Tanggal Yudisium Nilai</td><td>: $tanggal_yudisium</td></tr>";
    echo "</table><br><br>";

    //Stase yang upload nilai:
    //Stase Neurologi (M092)
    //Stase Ilmu Kesehatan Jiwa (M093)
    //Stase IKM-KP (M095)
    //Stase THT-KL (M105)
    //Stase Anestesi (M102)
    //Stase Radiologi (M103)
    //Stase Ilmu Kesehatan Mata (M104)
    //Stase IKFR (M094)
    //Stase IKGM (M106)
    //Stase Forensik (M112)
    //Stase Kulit dan Kelamin (M114)
    //Stase Ilmu Penyakit Dalam (M091)
    //Stase Ilmu Kebidanan dan Penyakit Kandungan (M111)
    //Stase Ilmu Bedah (M101)
    //Stase Ilmu Kesehatan Anak (M113)

    if ($stase_id =="M092" or $stase_id =="M093" or $stase_id =="M095" or $stase_id =="M105" OR $stase_id =="M102"
    OR $stase_id =="M103" OR $stase_id =="M104" OR $stase_id =="M094" OR $stase_id =="M106" OR $stase_id =="M112"
    OR $stase_id =="M114" OR $stase_id =="M091" OR $stase_id =="M111" OR $stase_id =="M101" OR $stase_id =="M113")
    {
      echo "<table id=\"freeze\" style=\"width:100%\">";
      echo "<thead>
            <th style=\"width:5%\">No</th>
            <th style=\"width:30%\">Nama Mahasiswa</th>
            <th style=\"width:15%\">NIM</th>
            <th style=\"width:15%\">Nilai</th>
            <th style=\"width:15%\">Status</th>
            <th style=\"width:20%\">Catatan</th>
            </thead>";

      if ($stase_id =="M095") {$db_tabel = "`ikmkp_nilai_test`";$id_kordik="K095";}
      if ($stase_id =="M105") {$db_tabel = "`thtkl_nilai_test`";$id_kordik="K105";}
      if ($stase_id =="M092") {$db_tabel = "`neuro_nilai_test`";$id_kordik="K092";}
      if ($stase_id =="M093") {$db_tabel = "`psikiatri_nilai_test`";$id_kordik="K093";}
      if ($stase_id =="M102") {$db_tabel = "`anestesi_nilai_test`";$id_kordik="K102";}
      if ($stase_id =="M103") {$db_tabel = "`radiologi_nilai_test`";$id_kordik="K103";}
      if ($stase_id =="M104") {$db_tabel = "`mata_nilai_test`";$id_kordik="K104";}
      if ($stase_id =="M094") {$db_tabel = "`ikfr_nilai_test`";$id_kordik="K094";}
      if ($stase_id =="M106") {$db_tabel = "`ikgm_nilai_test`";$id_kordik="K106";}
      if ($stase_id =="M112") {$db_tabel = "`forensik_nilai_test`";$id_kordik="K112";}
      if ($stase_id =="M114") {$db_tabel = "`kulit_nilai_test`";$id_kordik="K114";}
      if ($stase_id =="M091") {$db_tabel = "`ipd_nilai_test`";$id_kordik="K091";}
      if ($stase_id =="M111") {$db_tabel = "`obsgyn_nilai_test`";$id_kordik="K111";}
      if ($stase_id =="M101") {$db_tabel = "`bedah_nilai_test`";$id_kordik="K101";}
      if ($stase_id =="M113") {$db_tabel = "`ika_nilai_test`";$id_kordik="K113";}

      $kelas="ganjil";
      $header = 1;
      while(($filesop = fgetcsv($handle, 1000, $separator)) !== false)
  		{
        if ($header>1)
        {
          $no = $filesop[0];
          $nama = $filesop[1];
          $nim = $filesop[2];
          $nilai = number_format($filesop[3],2);
          if ($filesop[4]!="") $catatan = addslashes($filesop[4]);
          else $catatan = "-";
          $cek_nim = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM `admin` WHERE `username`='$nim'"));

          if ($cek_nim>0)
          {
            $dosen = mysqli_fetch_array(mysqli_query($con,"SELECT `username` FROM `admin` WHERE `stase`='$id_kordik'"));
            $cek_nim_stase = mysqli_num_rows(mysqli_query($con,"SELECT `id` FROM $db_tabel WHERE `nim`='$nim' AND `jenis_test`='$jenis_nilai' AND `status_ujian`='$status_ujian'"));

            if ($cek_nim_stase>0)
            {
              $nilai_update = mysqli_query($con,"UPDATE $db_tabel
                SET `dosen`='$dosen[username]',`nilai`='$nilai',`catatan`='$catatan',`tgl_test`='$tgl_ujian',`tgl_approval`='$tgl_approval',`status_approval`='1' WHERE `nim`='$nim' AND `jenis_test`='$jenis_nilai' AND `status_ujian`='$status_ujian'");

              echo "<tr class=\"$kelas\">";
              echo "<td align=center>$no</td>";
              echo "<td>$nama</td>";
              echo "<td align=center>$nim</td>";
              echo "<td align=center>$nilai</td>";
              if (!$nilai_update) echo "<td align=center><font style=\"color=red\">ERROR</font></td>";
              else echo "<td align=center>$nama_status_ujian<br>[ <font style=\"color:green\">UPDATED</font> ]</td>";
              echo "<td align=center><i>$catatan</i></td>";
              echo "</tr>";
            }
            else
            {
              $nilai_insert = mysqli_query($con,"INSERT INTO $db_tabel
                  ( `nim`, `dosen`,
                    `jenis_test`,`status_ujian`,
                    `nilai`, `catatan`,
                    `tgl_test`, `tgl_approval`, `status_approval`)
                  VALUES
                  ( '$nim','$dosen[username]',
                    '$jenis_nilai','$status_ujian',
                    '$nilai','$catatan',
                    '$tgl_ujian','$tgl_approval','1')");

              echo "<tr class=\"$kelas\">";
              echo "<td align=center>$no</td>";
              echo "<td>$nama</td>";
              echo "<td align=center>$nim</td>";
              echo "<td align=center>$nilai</td>";
              if (!$nilai_insert) echo "<td align=center><font style=\"color=red\">ERROR</font></td>";
              else echo "<td align=center>$nama_status_ujian[status_ujian]<br>[ <font style=\"color:green\">ISSUED</font> ]</td>";
              echo "<td align=center>$catatan</td>";
              echo "</tr>";
            }
          }
          else
          {
            echo "<tr class=\"$kelas\"><td align=center>$no</td><td colspan=5><font style=\"color:red\">Data $nama (NIM: $nim) tidak dikenali pada database sistem E-Logbook Koas. Silakan cek NIM-nya!!!</font></td></tr>";
          }
          if ($kelas=="ganjil") $kelas="genap";
          else $kelas="ganjil";
        }
        else
        {
          $header = 2;
        }
      }
      echo "</table>";
    }
    //Stase yang tidak upload nilai
    else
    {
      echo "<font style=\"color:red\">Catatan: Untuk Kepaniteraan/Stase ini tidak ada kegiatan $nama_test[jenis_test]!!!</font>";
    }
  }
  else
  {
    echo "<font style=\"color:red\">Maaf! Ada kesalahan input file!!!</font>";
  }

	echo "<br><br><form method=POST action=\"$_SERVER[PHP_SELF]\">
		<input type=submit class=\"submit1\" name=back value=BACK>
		</form>";

	if ($_POST['back']=="BACK")
  echo "
		<script>
			window.location.href=\"upload_nilai.php\";
		</script>
		";
  echo "</fieldset>";
}
else
echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
}
?>

<script src="../jquery.min.js"></script>
<script type="text/javascript" src="../freezeheader/js/jquery.freezeheader.js"></script>
<script>
  $(document).ready(function(){
	   $("#freeze").freezeHeader();
	});
</script>

<!--</body></html>-->
</BODY>
</HTML>
