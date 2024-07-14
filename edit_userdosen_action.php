<HTML>
<head>
    <meta charset="utf-8">
    <title>::Edit User::</title>
	<link rel="stylesheet" href="menu.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="mytable.css" type="text/css" media="screen" />

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
if (!empty($_COOKIE['user']) AND !empty($_COOKIE['pass'])AND ($_COOKIE['level']==1 OR $_COOKIE['level']==2 OR $_COOKIE['level']==3 OR $_COOKIE['level']==4))
{
  if ($_COOKIE['level']==1 OR $_COOKIE['level']==2) {
    if ($_COOKIE['level']==1) include "menu1.php";
    if ($_COOKIE['level']==2) include "menu2.php";
    echo "<div class=\"text_header\">EDIT USER</div>";
    echo "<br><br><br><fieldset class=\"fieldset_art\">
      <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
    echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">EDIT USER DOSEN/RESIDEN</font></h4><br>";

  }
  else {
    if ($_COOKIE['level']==3) {include "menu3.php";}
    if ($_COOKIE['level']==4) {include "menu4.php";}
    echo "<div class=\"text_header\">UPDATE PROFIL</div>";
    echo "<br><br><br><fieldset class=\"fieldset_art\">
      <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
    echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">UPDATE PROFIL</font></h4>";
  }

  if (empty($_POST[cancel]) and empty($_POST[simpan]) and empty($_POST[hapus]))
  {
    if ($_COOKIE['level']==1 OR $_COOKIE['level']==2) {
      $data_user = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `admin` WHERE `username`='$_GET[user_name]'"));
    }
    else {
      $data_user = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `admin` WHERE `username`='$_COOKIE[user]'"));
      }

    $dosen = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `dosen` WHERE `nip`='$data_user[username]'"));
    $bagian = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `bagian_ilmu` WHERE `id`='$dosen[kode_bagian]'"));
    $level_user = mysqli_fetch_array(mysqli_query($con,"SELECT * FROM `level` WHERE `id`='$data_user[level]'"));

  echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
  echo "<table>";
  echo "<tr class=\"ganjil\">";
    echo "<td style=\"width:200px\">";
      echo "Username";
    echo "</td>";
    echo "<td>";
      echo "$data_user[username]";
      if ($_COOKIE[level]=="3" OR $_COOKIE['level']=="4")
      {
        echo "<br>&nbsp;<font style=\"font-size:0.625em;font-family:GEORGIA\"><i>(Username tidak bisa diubah, NIP/NIDK/NIM/ID dari user.<br>&nbsp;&nbsp;Hubungi admin fakultas untuk melakukan perubahan.)</i></font>";
        echo "<input type=\"hidden\" name=\"user_name\" value=\"$data_user[username]\" />";
      }
    echo "</td>";
  echo "</tr>";

  if ($_COOKIE[level]=="1" OR $_COOKIE['level']=="2")
  {
  echo "<tr class=\"genap\">";
    echo "<td style=\"width:200px\">";
      echo "&nbsp;&nbsp;&nbsp;&nbsp;<i>Username Baru</i>";
    echo "</td>";
    echo "<td>";
      echo "<input type=\"hidden\" name=\"user_name_lama\" value=\"$data_user[username]\" />";
      echo "<input type=\"text\" value=\"$data_user[username]\" name=\"user_name\" class=\"select_art\">";
      echo "<br>&nbsp;<font style=\"font-size:0.625em;font-family:GEORGIA\"><i>(Pastikan untuk menghubungi pemilik akun setelah melakukan perubahan username dan password!)</i></font>";
    echo "</td>";
  echo "</tr>";
  }

  echo "<tr class=\"genap\">";
    echo "<td style=\"width:200px\">";
      echo "&nbsp;&nbsp;&nbsp;&nbsp;<i>Password Baru</i>";
    echo "</td>";
    echo "<td>";
      echo "<input type=\"password\" id=\"form-password\" value=\"\" name=\"user_pass\" class=\"select_art\">";
      echo "<br><input type=\"checkbox\" id=\"form-checkbox\">&nbsp;<font style=\"font-size:0.625em\"><i>Show password</i></font>";
      echo "<br>&nbsp;<font style=\"font-size:0.625em;font-family:GEORGIA\"><i>(Jika kosong/tidak diisi, password tidak berubah)</i></font>";
    echo "</td>";
  echo "</tr>";
  echo "<tr class=\"ganjil\">";
    echo "<td>";
      echo "Nama Lengkap";
    echo "</td>";
    echo "<td>";
      echo "$dosen[nama]";
    echo "</td>";
  echo "</tr>";
  echo "<tr class=\"genap\">";
    echo "<td>";
      echo "&nbsp;&nbsp;&nbsp;&nbsp;<i>Nama Lengkap Baru</i>";
    echo "</td>";
    echo "<td>";
      echo "<input type=\"text\" name=\"user_surename\" value=\"$dosen[nama]\" class=\"select_art\">";
    echo "</td>";
  echo "</tr>";
  echo "<tr class=\"ganjil\">";
    echo "<td>";
      echo "Gelar";
    echo "</td>";
    echo "<td>";
      echo "$dosen[gelar]";
    echo "</td>";
  echo "</tr>";
  echo "<tr class=\"genap\">";
    echo "<td>";
      echo "&nbsp;&nbsp;&nbsp;&nbsp;<i>Gelar Baru</i>";
    echo "</td>";
    echo "<td>";
      echo "<input type=\"text\" name=\"user_gelar\" value=\"$dosen[gelar]\" class=\"select_art\" >";
    echo "</td>";
  echo "</tr>";
  echo "<tr class=\"ganjil\">";
    echo "<td>";
      echo "Bagian";
    echo "</td>";
    echo "<td>";
      echo "$bagian[bagian]";
    echo "</td>";
  echo "</tr>";
  echo "<tr class=\"genap\">";
    echo "<td>";
      echo "&nbsp;&nbsp;&nbsp;&nbsp;<i>Bagian Baru</i>";
    echo "</td>";
    echo "<td>";
      echo "<select name=\"bagian\" class=\"select_art\" >";
      $action_bag=mysqli_query($con,"SELECT * FROM `bagian_ilmu` ORDER BY `id` ASC");
		  echo "<option value=\"$bagian[id]\">$bagian[id] - $bagian[bagian]</option>";
			while ($for_bag=mysqli_fetch_array($action_bag))
      {
        echo "<option value=\"$for_bag[id]\">$for_bag[id] - $for_bag[bagian]</option>";
      }
      echo "</select>";
    echo "</td>";
  echo "</tr>";
  if ($_COOKIE[level]==1)
  {
    echo "<tr class=\"ganjil\">";
      echo "<td>";
      echo "Level User";
      echo "</td>";
      echo "<td>";
      echo "$level_user[level]";
      echo "</td>";
    echo "</tr>";

    echo "<tr class=\"genap\">";
      echo "<td>";
      echo "&nbsp;&nbsp;&nbsp;&nbsp;<i>Level User Baru</i>";
      echo "</td>";
      echo "<td>";
      echo "<select name=\"user_level\" class=\"select_art\" >";
      $action_level=mysqli_query($con,"SELECT * FROM `level` ORDER BY `id` ASC");
		  echo "<option value=\"$level_user[id]\">$level_user[id] - $level_user[level]</option>";
			while ($for_level=mysqli_fetch_array($action_level))
      {
        echo "<option value=\"$for_level[id]\">$for_level[id] - $for_level[level]</option>";
      }
      echo "</select>";
      echo "</td>";
    echo "</tr>";
  }
  if ($_COOKIE[level]==2)
  {
    echo "<input type=\"hidden\" name=\"user_level\" value=\"$level_user[id]\" >";
  }

  echo "<tr class=\"genap\">";
    echo "<td colspan=2 style=\"text-align:center\">";
      echo "<br><input type=\"submit\" class=\"submit1\" name=\"cancel\" value=\"CANCEL\" formnovalidate />";
      echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" class=\"submit1\" name=\"simpan\" value=\"SIMPAN\" />";
      if ($_COOKIE[level]==1) echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" class=\"submit1\" name=\"hapus\" value=\"HAPUS\" formnovalidate />";
    echo "<br><br></td>";
  echo "</tr>";
  echo "</table></form>";
  }

  if ($_POST['cancel']=="CANCEL")
  {
    if ($_COOKIE[level]==1 OR $_COOKIE[level]==2)
    echo "
		<script>
			window.location.href=\"edit_user_dosen.php\";
		</script>
		";
    else
    echo "
		<script>
			window.location.href=\"profil_dosen.php\";
		</script>
		";
  }

  if ($_POST['simpan']=="SIMPAN")
  {
    $nama = addslashes($_POST[user_surename]);
    if (($_COOKIE['level']=="1" OR $_COOKIE['level']=="2") AND $_POST['user_name']!=$_POST['user_name_lama'])
    {
      $user_password=MD5($_POST[user_name]);
      $update_admin=mysqli_query($con,"UPDATE `admin`
        SET
        `nama`='$nama',
        `username`='$_POST[user_name]',
        `password`='$user_password'
        WHERE `username`='$_POST[user_name_lama]'");
      $update_dosen=mysqli_query($con,"UPDATE `dosen`
        SET
        `nip`='$_POST[user_name]',
        `nama`='$nama'
        WHERE `nip`='$_POST[user_name_lama]'");


        $dosen_baru = $_POST[user_name];
        $dosen_lama = $_POST[user_name_lama];

        //tabel evaluasi_stase
        $update_evaluasi_stase_dosen1=mysqli_query($con,"UPDATE `evaluasi_stase` SET `dosen1`='$dosen_baru' WHERE `dosen1`='$dosen_lama'");
        $update_evaluasi_stase_dosen2=mysqli_query($con,"UPDATE `evaluasi_stase` SET `dosen2`='$dosen_baru' WHERE `dosen2`='$dosen_lama'");
        $update_evaluasi_stase_dosen3=mysqli_query($con,"UPDATE `evaluasi_stase` SET `dosen3`='$dosen_baru' WHERE `dosen3`='$dosen_lama'");
        //tabel internal_Mxxx
        $stase=mysqli_query($con,"SELECT `id` FROM `kepaniteraan` ORDER BY `id`");
        while ($data_stase=mysqli_fetch_array($stase))
        {
          $internal_id = "internal_".$data_stase[id];
          $update_internal_id_dosen1=mysqli_query($con,"UPDATE `$internal_id` SET `dosen1`='$dosen_baru' WHERE `dosen1`='$dosen_lama'");
          $update_internal_id_dosen2=mysqli_query($con,"UPDATE `$internal_id` SET `dosen2`='$dosen_baru' WHERE `dosen2`='$dosen_lama'");
          $update_internal_id_dosen3=mysqli_query($con,"UPDATE `$internal_id` SET `dosen3`='$dosen_baru' WHERE `dosen3`='$dosen_lama'");
          $update_internal_id_dosen4=mysqli_query($con,"UPDATE `$internal_id` SET `dosen4`='$dosen_baru' WHERE `dosen4`='$dosen_lama'");
          $update_internal_id_dosen5=mysqli_query($con,"UPDATE `$internal_id` SET `dosen5`='$dosen_baru' WHERE `dosen5`='$dosen_lama'");
          $update_internal_id_dosen6=mysqli_query($con,"UPDATE `$internal_id` SET `dosen6`='$dosen_baru' WHERE `dosen6`='$dosen_lama'");
        }
        //tabel jurnal_ketrampilan
        $update_jurnal_ketrampilan=mysqli_query($con,"UPDATE `jurnal_ketrampilan` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        //tabel jurnal_penyakit
        $update_jurnal_penyakit=mysqli_query($con,"UPDATE `jurnal_penyakit` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");

        //update nilai bagian
        //M091 - Ilmu Penyakit Dalam
        $update_ipd_nilai_kasus=mysqli_query($con,"UPDATE `ipd_nilai_kasus` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_ipd_nilai_minicex=mysqli_query($con,"UPDATE `ipd_nilai_minicex` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_ipd_nilai_test=mysqli_query($con,"UPDATE `ipd_nilai_test` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_ipd_nilai_ujian=mysqli_query($con,"UPDATE `ipd_nilai_ujian` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        //M092 - Neurologi
        $update_neuro_nilai_cbd=mysqli_query($con,"UPDATE `neuro_nilai_cbd` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_neuro_nilai_jurnal=mysqli_query($con,"UPDATE `neuro_nilai_jurnal` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_neuro_nilai_minicex=mysqli_query($con,"UPDATE `neuro_nilai_minicex` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_neuro_nilai_spv=mysqli_query($con,"UPDATE `neuro_nilai_spv` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_neuro_nilai_test=mysqli_query($con,"UPDATE `neuro_nilai_test` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        //M093 - Ilmu Kesehatan Jiwa
        $update_psikiatri_nilai_cbd=mysqli_query($con,"UPDATE `psikiatri_nilai_cbd` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_psikiatri_nilai_jurnal=mysqli_query($con,"UPDATE `psikiatri_nilai_jurnal` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_psikiatri_nilai_minicex=mysqli_query($con,"UPDATE `psikiatri_nilai_minicex` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_psikiatri_nilai_osce=mysqli_query($con,"UPDATE `psikiatri_nilai_osce` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_psikiatri_nilai_test=mysqli_query($con,"UPDATE `psikiatri_nilai_test` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        //M105 - Ilmu Kesehatan THT-KL
        $update_thtkl_nilai_jurnal=mysqli_query($con,"UPDATE `thtkl_nilai_jurnal` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_thtkl_nilai_osce=mysqli_query($con,"UPDATE `thtkl_nilai_osce` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_thtkl_nilai_presentasi=mysqli_query($con,"UPDATE `thtkl_nilai_presentasi` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_thtkl_nilai_responsi=mysqli_query($con,"UPDATE `thtkl_nilai_responsi` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_thtkl_nilai_test=mysqli_query($con,"UPDATE `thtkl_nilai_test` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        //M095 - IKM-KP
        $update_ikmkp_nilai_komprehensip=mysqli_query($con,"UPDATE `ikmkp_nilai_komprehensip` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_ikmkp_nilai_p2ukm=mysqli_query($con,"UPDATE `ikmkp_nilai_p2ukm` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_ikmkp_nilai_pkbi=mysqli_query($con,"UPDATE `ikmkp_nilai_pkbi` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_ikmkp_nilai_test=mysqli_query($con,"UPDATE `ikmkp_nilai_test` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        //M101 - Ilmu Bedah
        $update_bedah_nilai_mentor=mysqli_query($con,"UPDATE `bedah_nilai_mentor` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_bedah_nilai_test=mysqli_query($con,"UPDATE `bedah_nilai_test` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        //M102 - Anestesi dan Intensive Care
        $update_anestesi_nilai_dops=mysqli_query($con,"UPDATE `anestesi_nilai_dops` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_anestesi_nilai_osce=mysqli_query($con,"UPDATE `anestesi_nilai_osce` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_anestesi_nilai_test=mysqli_query($con,"UPDATE `anestesi_nilai_test` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        //M103 - Radiologi
        $update_radiologi_nilai_cbd=mysqli_query($con,"UPDATE `radiologi_nilai_cbd` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_radiologi_nilai_jurnal=mysqli_query($con,"UPDATE `radiologi_nilai_jurnal` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_radiologi_nilai_test=mysqli_query($con,"UPDATE `radiologi_nilai_test` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        //M104 - Ilmu Kesehatan Mata
        $update_mata_nilai_jurnal=mysqli_query($con,"UPDATE `mata_nilai_jurnal` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_mata_nilai_minicex=mysqli_query($con,"UPDATE `mata_nilai_minicex` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_mata_nilai_penyanggah=mysqli_query($con,"UPDATE `mata_nilai_penyanggah` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_mata_nilai_presentasi=mysqli_query($con,"UPDATE `mata_nilai_presentasi` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_mata_nilai_test=mysqli_query($con,"UPDATE `mata_nilai_test` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        //M094 - IKFR
        $update_ikfr_nilai_kasus=mysqli_query($con,"UPDATE `ikfr_nilai_kasus` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_ikfr_nilai_minicex=mysqli_query($con,"UPDATE `ikfr_nilai_minicex` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_ikfr_nilai_test=mysqli_query($con,"UPDATE `ikfr_nilai_test` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        //M106 - IKGM
        $update_ikgm_nilai_jurnal=mysqli_query($con,"UPDATE `ikgm_nilai_jurnal` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_ikgm_nilai_kasus=mysqli_query($con,"UPDATE `ikgm_nilai_kasus` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_ikgm_nilai_responsi=mysqli_query($con,"UPDATE `ikgm_nilai_responsi` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_ikgm_nilai_test=mysqli_query($con,"UPDATE `ikgm_nilai_test` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        //M111 - Ilmu Kebidanan dan Penyakit Kandungan
        $update_obsgyn_nilai_cbd=mysqli_query($con,"UPDATE `obsgyn_nilai_cbd` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_obsgyn_nilai_jurnal=mysqli_query($con,"UPDATE `obsgyn_nilai_jurnal` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_obsgyn_nilai_minicex=mysqli_query($con,"UPDATE `obsgyn_nilai_minicex` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_obsgyn_nilai_test=mysqli_query($con,"UPDATE `obsgyn_nilai_test` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        //M112 - Kedokteran Forensik dan Medikolegal
        $update_forensik_nilai_jaga=mysqli_query($con,"UPDATE `forensik_nilai_jaga` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_forensik_nilai_referat=mysqli_query($con,"UPDATE `forensik_nilai_referat` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_forensik_nilai_substase=mysqli_query($con,"UPDATE `forensik_nilai_substase` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_forensik_nilai_test=mysqli_query($con,"UPDATE `forensik_nilai_test` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_forensik_nilai_visum=mysqli_query($con,"UPDATE `forensik_nilai_visum` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        //M113 - Ilmu Kesehatan Anak
        $update_ika_nilai_cbd=mysqli_query($con,"UPDATE `ika_nilai_cbd` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_ika_nilai_dops=mysqli_query($con,"UPDATE `ika_nilai_dops` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_ika_nilai_jurnal=mysqli_query($con,"UPDATE `ika_nilai_jurnal` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_ika_nilai_kasus=mysqli_query($con,"UPDATE `ika_nilai_kasus` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_ika_nilai_minicex=mysqli_query($con,"UPDATE `ika_nilai_minicex` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_ika_nilai_minipat=mysqli_query($con,"UPDATE `ika_nilai_minipat` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_ika_nilai_test=mysqli_query($con,"UPDATE `ika_nilai_test` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_ika_nilai_ujian=mysqli_query($con,"UPDATE `ika_nilai_ujian` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        //M114 - Ilmu Kesehatan Kulit dan Kelamin
        $update_kulit_nilai_cbd=mysqli_query($con,"UPDATE `kulit_nilai_cbd` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_kulit_nilai_test=mysqli_query($con,"UPDATE `kulit_nilai_test` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        //M121 - Komprehensip dan Kedokteran Keluarga
        //Komprehensip
        $update_kompre_nilai_cbd=mysqli_query($con,"UPDATE `kompre_nilai_cbd` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_kompre_nilai_laporan=mysqli_query($con,"UPDATE `kompre_nilai_laporan` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_kompre_nilai_presensi=mysqli_query($con,"UPDATE `kompre_nilai_presensi` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_kompre_nilai_sikap=mysqli_query($con,"UPDATE `kompre_nilai_sikap` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        //Kedokteran Keluarga
        $update_kdk_nilai_dops=mysqli_query($con,"UPDATE `kdk_nilai_dops` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_kdk_nilai_kasus=mysqli_query($con,"UPDATE `kdk_nilai_kasus` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_kdk_nilai_minicex=mysqli_query($con,"UPDATE `kdk_nilai_minicex` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_kdk_nilai_presensi=mysqli_query($con,"UPDATE `kdk_nilai_presensi` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
        $update_kdk_nilai_sikap=mysqli_query($con,"UPDATE `kdk_nilai_sikap` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");

    }

    if ($_COOKIE[level]==1 OR $_COOKIE[level]==2) $level = $_POST[user_level];
    else $level = $_COOKIE[level];
    if ($_POST['user_pass']!="")
    {
      $user_password = MD5($_POST['user_pass']);
      $update_admin=mysqli_query($con,"UPDATE `admin`
        SET
        `nama`='$nama',
        `password`='$user_password',
        `level`='$level'
        WHERE `username`='$_POST[user_name]'");
    }
    else {
      $update_admin=mysqli_query($con,"UPDATE `admin`
        SET
        `nama`='$nama',
        `level`='$level'
        WHERE `username`='$_POST[user_name]'");
    }

    $update_dosen=mysqli_query($con,"UPDATE `dosen`
        SET
        `nama`='$nama',
        `gelar`='$_POST[user_gelar]',
        `kode_bagian`='$_POST[bagian]'
        WHERE `nip`='$_POST[user_name]'");



    if ($_COOKIE[level]==1 OR $_COOKIE[level]==2)
    echo "
		<script>
			window.location.href=\"edit_user_dosen.php?status=SIMPAN\";
		</script>
		";
    else
    echo "
		<script>
			window.location.href=\"profil_dosen.php\";
		</script>
		";
  }

  if ($_POST['hapus']=="HAPUS")
  {
    $delete_admin=mysqli_query($con,"DELETE FROM `admin` WHERE `username`='$_POST[user_name]'");
    $delete_dosen=mysqli_query($con,"DELETE FROM `dosen` WHERE `nip`='$_POST[user_name]'");

    //tabel evaluasi_stase
    $update_evaluasi_stase_dosen1=mysqli_query($con,"UPDATE `evaluasi_stase` SET `dosen1`='' WHERE `dosen1`='$_POST[user_name]'");
    $update_evaluasi_stase_dosen2=mysqli_query($con,"UPDATE `evaluasi_stase` SET `dosen2`='' WHERE `dosen2`='$_POST[user_name]'");
    $update_evaluasi_stase_dosen3=mysqli_query($con,"UPDATE `evaluasi_stase` SET `dosen3`='' WHERE `dosen3`='$_POST[user_name]'");
    //tabel internal_Mxxx
    $stase=mysqli_query($con,"SELECT `id` FROM `kepaniteraan` ORDER BY `id`");
    while ($data_stase=mysqli_fetch_array($stase))
    {
      $internal_id = "internal_".$data_stase[id];
      $update_internal_id_dosen1=mysqli_query($con,"UPDATE `$internal_id` SET `dosen1`='' WHERE `dosen1`='$_POST[user_name]'");
      $update_internal_id_dosen2=mysqli_query($con,"UPDATE `$internal_id` SET `dosen2`='' WHERE `dosen2`='$_POST[user_name]'");
      $update_internal_id_dosen3=mysqli_query($con,"UPDATE `$internal_id` SET `dosen3`='' WHERE `dosen3`='$_POST[user_name]'");
      $update_internal_id_dosen4=mysqli_query($con,"UPDATE `$internal_id` SET `dosen4`='' WHERE `dosen4`='$_POST[user_name]'");
      $update_internal_id_dosen5=mysqli_query($con,"UPDATE `$internal_id` SET `dosen5`='' WHERE `dosen5`='$_POST[user_name]'");
      $update_internal_id_dosen6=mysqli_query($con,"UPDATE `$internal_id` SET `dosen6`='' WHERE `dosen6`='$_POST[user_name]'");
    }

    //tabel jurnal_ketrampilan
    $delete_jurnal_ketrampilan=mysqli_query($con,"DELETE FROM `jurnal_ketrampilan` WHERE `dosen`='$_POST[user_name]'");
    //tabel jurnal_penyakit
    $delete_jurnal_penyakit=mysqli_query($con,"DELETE FROM `jurnal_penyakit` WHERE `dosen`='$_POST[user_name]'");

    //delete nilai bagian
    //M091 - Ilmu Penyakit Dalam
    $delete_ipd_nilai_kasus=mysqli_query($con,"DELETE FROM `ipd_nilai_kasus` WHERE `dosen`='$_POST[user_name]'");
    $delete_ipd_nilai_minicex=mysqli_query($con,"DELETE FROM `ipd_nilai_minicex` WHERE `dosen`='$_POST[user_name]'");
    $delete_ipd_nilai_test=mysqli_query($con,"DELETE FROM `ipd_nilai_test` WHERE `dosen`='$_POST[user_name]'");
    $delete_ipd_nilai_ujian=mysqli_query($con,"DELETE FROM `ipd_nilai_ujian` WHERE `dosen`='$_POST[user_name]'");
    //M092 - Neurologi
    $delete_neuro_nilai_cbd=mysqli_query($con,"DELETE FROM `neuro_nilai_cbd` WHERE `dosen`='$_POST[user_name]'");
    $delete_neuro_nilai_jurnal=mysqli_query($con,"DELETE FROM `neuro_nilai_jurnal` WHERE `dosen`='$_POST[user_name]'");
    $delete_neuro_nilai_minicex=mysqli_query($con,"DELETE FROM `neuro_nilai_minicex` WHERE `dosen`='$_POST[user_name]'");
    $delete_neuro_nilai_spv=mysqli_query($con,"DELETE FROM `neuro_nilai_spv` WHERE `dosen`='$_POST[user_name]'");
    $delete_neuro_nilai_test=mysqli_query($con,"DELETE FROM `neuro_nilai_test` WHERE `dosen`='$_POST[user_name]'");
    //M093 - Ilmu Kesehatan Jiwa
    $delete_psikiatri_nilai_cbd=mysqli_query($con,"DELETE FROM `psikiatri_nilai_cbd` WHERE `dosen`='$_POST[user_name]'");
    $delete_psikiatri_nilai_jurnal=mysqli_query($con,"DELETE FROM `psikiatri_nilai_jurnal` WHERE `dosen`='$_POST[user_name]'");
    $delete_psikiatri_nilai_minicex=mysqli_query($con,"DELETE FROM `psikiatri_nilai_minicex` WHERE `dosen`='$_POST[user_name]'");
    $delete_psikiatri_nilai_osce=mysqli_query($con,"DELETE FROM `psikiatri_nilai_osce` WHERE `dosen`='$_POST[user_name]'");
    $delete_psikiatri_nilai_test=mysqli_query($con,"DELETE FROM `psikiatri_nilai_test` WHERE `dosen`='$_POST[user_name]'");
    //M105 - Ilmu Kesehatan THT-KL
    $delete_thtkl_nilai_jurnal=mysqli_query($con,"DELETE FROM `thtkl_nilai_jurnal` WHERE `dosen`='$_POST[user_name]'");
    $delete_thtkl_nilai_osce=mysqli_query($con,"DELETE FROM `thtkl_nilai_osce` WHERE `dosen`='$_POST[user_name]'");
    $delete_thtkl_nilai_presentasi=mysqli_query($con,"DELETE FROM `thtkl_nilai_presentasi` WHERE `dosen`='$_POST[user_name]'");
    $delete_thtkl_nilai_responsi=mysqli_query($con,"DELETE FROM `thtkl_nilai_responsi` WHERE `dosen`='$_POST[user_name]'");
    $delete_thtkl_nilai_test=mysqli_query($con,"DELETE FROM `thtkl_nilai_test` WHERE `dosen`='$_POST[user_name]'");
    //M095 - IKM-KP
    $delete_ikmkp_nilai_komprehensip=mysqli_query($con,"DELETE FROM `ikmkp_nilai_komprehensip` WHERE `dosen`='$_POST[user_name]'");
    $delete_ikmkp_nilai_p2ukm=mysqli_query($con,"DELETE FROM `ikmkp_nilai_p2ukm` WHERE `dosen`='$_POST[user_name]'");
    $delete_ikmkp_nilai_pkbi=mysqli_query($con,"DELETE FROM `ikmkp_nilai_pkbi` WHERE `dosen`='$_POST[user_name]'");
    $delete_ikmkp_nilai_test=mysqli_query($con,"DELETE FROM `ikmkp_nilai_test` WHERE `dosen`='$_POST[user_name]'");
    //M101 - Ilmu Bedah
    $delete_bedah_nilai_mentor=mysqli_query($con,"DELETE FROM `bedah_nilai_mentor` WHERE `dosen`='$_POST[user_name]'");
    $delete_bedah_nilai_test=mysqli_query($con,"DELETE FROM `bedah_nilai_test` WHERE `dosen`='$_POST[user_name]'");
    //M102 - Anestesi dan Intensive Care
    $delete_anestesi_nilai_dops=mysqli_query($con,"DELETE FROM `anestesi_nilai_dops` WHERE `dosen`='$_POST[user_name]'");
    $delete_anestesi_nilai_osce=mysqli_query($con,"DELETE FROM `anestesi_nilai_osce` WHERE `dosen`='$_POST[user_name]'");
    $delete_anestesi_nilai_test=mysqli_query($con,"DELETE FROM `anestesi_nilai_test` WHERE `dosen`='$_POST[user_name]'");
    //M103 - Radiologi
    $delete_radiologi_nilai_cbd=mysqli_query($con,"DELETE FROM `radiologi_nilai_cbd` WHERE `dosen`='$_POST[user_name]'");
    $delete_radiologi_nilai_jurnal=mysqli_query($con,"DELETE FROM `radiologi_nilai_jurnal` WHERE `dosen`='$_POST[user_name]'");
    $delete_radiologi_nilai_test=mysqli_query($con,"DELETE FROM `radiologi_nilai_test` WHERE `dosen`='$_POST[user_name]'");
    //M104 - Ilmu Kesehatan Mata
    $delete_mata_nilai_jurnal=mysqli_query($con,"DELETE FROM `mata_nilai_jurnal` WHERE `dosen`='$_POST[user_name]'");
    $delete_mata_nilai_minicex=mysqli_query($con,"DELETE FROM `mata_nilai_minicex` WHERE `dosen`='$_POST[user_name]'");
    $delete_mata_nilai_penyanggah=mysqli_query($con,"DELETE FROM `mata_nilai_penyanggah` WHERE `dosen`='$_POST[user_name]'");
    $delete_mata_nilai_presentasi=mysqli_query($con,"DELETE FROM `mata_nilai_presentasi` WHERE `dosen`='$_POST[user_name]'");
    $delete_mata_nilai_test=mysqli_query($con,"DELETE FROM `mata_nilai_test` WHERE `dosen`='$_POST[user_name]'");
    //M094 - IKFR
    $delete_ikfr_nilai_kasus=mysqli_query($con,"DELETE FROM `ikfr_nilai_kasus` WHERE `dosen`='$_POST[user_name]'");
    $delete_ikfr_nilai_minicex=mysqli_query($con,"DELETE FROM `ikfr_nilai_minicex` WHERE `dosen`='$_POST[user_name]'");
    $delete_ikfr_nilai_test=mysqli_query($con,"DELETE FROM `ikfr_nilai_test` WHERE `dosen`='$_POST[user_name]'");
    //M106 - IKGM
    $delete_ikgm_nilai_jurnal=mysqli_query($con,"DELETE FROM `ikgm_nilai_jurnal` WHERE `dosen`='$_POST[user_name]'");
    $delete_ikgm_nilai_kasus=mysqli_query($con,"DELETE FROM `ikgm_nilai_kasus` WHERE `dosen`='$_POST[user_name]'");
    $delete_ikgm_nilai_responsi=mysqli_query($con,"DELETE FROM `ikgm_nilai_responsi` WHERE `dosen`='$_POST[user_name]'");
    $delete_ikgm_nilai_test=mysqli_query($con,"DELETE FROM `ikgm_nilai_test` WHERE `dosen`='$_POST[user_name]'");
    //M111 - Ilmu Kebidanan dan Penyakit Kandungan
    $delete_obsgyn_nilai_cbd=mysqli_query($con,"DELETE FROM `obsgyn_nilai_cbd` WHERE `dosen`='$_POST[user_name]'");
    $delete_obsgyn_nilai_jurnal=mysqli_query($con,"DELETE FROM `obsgyn_nilai_jurnal` WHERE `dosen`='$_POST[user_name]'");
    $delete_obsgyn_nilai_minicex=mysqli_query($con,"DELETE FROM `obsgyn_nilai_minicex` WHERE `dosen`='$_POST[user_name]'");
    $delete_obsgyn_nilai_test=mysqli_query($con,"DELETE FROM `obsgyn_nilai_test` WHERE `dosen`='$_POST[user_name]'");
    //M112 - Kedokteran Forensik dan Medikolegal
    $delete_forensik_nilai_jaga=mysqli_query($con,"DELETE FROM `forensik_nilai_jaga` WHERE `dosen`='$_POST[user_name]'");
    $delete_forensik_nilai_referat=mysqli_query($con,"DELETE FROM `forensik_nilai_referat` WHERE `dosen`='$_POST[user_name]'");
    $delete_forensik_nilai_substase=mysqli_query($con,"DELETE FROM `forensik_nilai_substase` WHERE `dosen`='$_POST[user_name]'");
    $delete_forensik_nilai_test=mysqli_query($con,"DELETE FROM `forensik_nilai_test` WHERE `dosen`='$_POST[user_name]'");
    $delete_forensik_nilai_visum=mysqli_query($con,"DELETE FROM `forensik_nilai_visum` WHERE `dosen`='$_POST[user_name]'");
    //M113 - Ilmu Kesehatan Anak
    $delete_ika_nilai_cbd=mysqli_query($con,"DELETE FROM `ika_nilai_cbd` WHERE `dosen`='$_POST[user_name]'");
    $delete_ika_nilai_dops=mysqli_query($con,"DELETE FROM `ika_nilai_dops` WHERE `dosen`='$_POST[user_name]'");
    $delete_ika_nilai_jurnal=mysqli_query($con,"DELETE FROM `ika_nilai_jurnal` WHERE `dosen`='$_POST[user_name]'");
    $delete_ika_nilai_kasus=mysqli_query($con,"DELETE FROM `ika_nilai_kasus` WHERE `dosen`='$_POST[user_name]'");
    $delete_ika_nilai_minicex=mysqli_query($con,"DELETE FROM `ika_nilai_minicex` WHERE `dosen`='$_POST[user_name]'");
    $delete_ika_nilai_minipat=mysqli_query($con,"DELETE FROM `ika_nilai_minipat` WHERE `dosen`='$_POST[user_name]'");
    $delete_ika_nilai_test=mysqli_query($con,"DELETE FROM `ika_nilai_test` WHERE `dosen`='$_POST[user_name]'");
    $delete_ika_nilai_ujian=mysqli_query($con,"DELETE FROM `ika_nilai_ujian` WHERE `dosen`='$_POST[user_name]'");
    //M114 - Ilmu Kesehatan Kulit dan Kelamin
    $delete_kulit_nilai_cbd=mysqli_query($con,"DELETE FROM `kulit_nilai_cbd` WHERE `dosen`='$_POST[user_name]'");
    $delete_kulit_nilai_test=mysqli_query($con,"DELETE FROM `kulit_nilai_test` WHERE `dosen`='$_POST[user_name]'");
    //M121 - Komprehensip dan Kedokteran Keluarga
    //Komprehensip
    $delete_kompre_nilai_cbd=mysqli_query($con,"DELETE FROM `kompre_nilai_cbd` WHERE `dosen`='$_POST[user_name]'");
    $delete_kompre_nilai_laporan=mysqli_query($con,"DELETE FROM `kompre_nilai_laporan` WHERE `dosen`='$_POST[user_name]'");
    $delete_kompre_nilai_presensi=mysqli_query($con,"DELETE FROM `kompre_nilai_presensi` WHERE `dosen`='$_POST[user_name]'");
    $delete_kompre_nilai_sikap=mysqli_query($con,"DELETE FROM `kompre_nilai_sikap` WHERE `dosen`='$_POST[user_name]'");
    //Kedokteran Keluarga
    $delete_kdk_nilai_dops=mysqli_query($con,"DELETE FROM `kdk_nilai_dops` WHERE `dosen`='$_POST[user_name]'");
    $delete_kdk_nilai_kasus=mysqli_query($con,"DELETE FROM `kdk_nilai_kasus` WHERE `dosen`='$_POST[user_name]'");
    $delete_kdk_nilai_minicex=mysqli_query($con,"DELETE FROM `kdk_nilai_minicex` WHERE `dosen`='$_POST[user_name]'");
    $delete_kdk_nilai_presensi=mysqli_query($con,"DELETE FROM `kdk_nilai_presensi` WHERE `dosen`='$_POST[user_name]'");
    $delete_kdk_nilai_sikap=mysqli_query($con,"DELETE FROM `kdk_nilai_sikap` WHERE `dosen`='$_POST[user_name]'");

    echo "
		<script>
			window.location.href=\"edit_user_dosen.php?status=HAPUS\";
		</script>
		";
  }

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
<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#form-checkbox').click(function(){
			if($(this).is(':checked')){
				$('#form-password').attr('type','text');
			}else{
				$('#form-password').attr('type','password');
			}
		});
	});
</script>



<!--</body></html>-->
</BODY>
</HTML>
