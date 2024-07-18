<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>On-line Rotasi Stase Logbook Koas Pendidikan Dokter FK-UNDIP</title>
  <link rel="shortcut icon" type="x-icon" href="images/undipsolid.png">
  <link rel="stylesheet" href="style/style1.css" />
  <link rel="stylesheet" href="style/buttontopup.css">
  <link rel="stylesheet" href="mytable.css" type="text/css" media="screen" />

  <!-- Link Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <!-- Link CDN Icon -->
  <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
  <div class="wrapper">
    <?php

    include "config.php";
    include "fungsi.php";
    set_time_limit(0);
    error_reporting("E_ALL ^ E_NOTICE");

    if (empty($_COOKIE['user']) || empty($_COOKIE['pass'])) {
      echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
    } else {
      if (!empty($_COOKIE['user']) and !empty($_COOKIE['pass']) and ($_COOKIE['level'] == 1 or $_COOKIE['level'] == 2 or $_COOKIE['level'] == 5)) {
        if ($_COOKIE['level'] == 1) {
          include "menu1.php";
          echo "<div class=\"text_header\">EDIT USER</div>";
          echo "<br><br><br><fieldset class=\"fieldset_art\">
      <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
          echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">EDIT USER MAHASISWA</font></h4><br>";
        }
        if ($_COOKIE['level'] == 2) {
          include "menu2.php";
          echo "<div class=\"text_header\">EDIT USER</div>";
          echo "<br><br><br><fieldset class=\"fieldset_art\">
      <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
          echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">EDIT USER MAHASISWA</font></h4><br>";
        }
        if ($_COOKIE['level'] == 5) {
          include "menu5.php";
          echo "<div class=\"text_header\">UPDATE PROFIL</div>";
          echo "<br><br><br><fieldset class=\"fieldset_art\">
      <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";
          echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">UPDATE PROFIL</font></h4><br>";
        }
        echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\" enctype=\"multipart/form-data\">";

        if (empty($_POST[cancel]) and empty($_POST[simpan]) and empty($_POST[hapus])) {
          if ($_COOKIE['level'] == 1 or $_COOKIE['level'] == 2) {
            $data_user = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `admin` WHERE `username`='$_GET[user_name]'"));
            $biodata_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$_GET[user_name]'"));
          }
          if ($_COOKIE['level'] == 5) {
            $data_user = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `admin` WHERE `username`='$_COOKIE[user]'"));
            $biodata_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
          }
          echo "<input type=\"hidden\" name=\"foto_data\" value=\"$biodata_mhsw[foto]\" />";
        }
        if ($_POST['cancel'] == "CANCEL") {
          if ($_COOKIE[level] == 1 or $_COOKIE[level] == 2)
            echo "
		<script>
			window.location.href=\"edit_user_mhsw.php\";
		</script>
		";
          if ($_COOKIE[level] == 5)
            echo "
		<script>
			window.location.href=\"biodata.php\";
		</script>
		";
        }

        if ($_POST['simpan'] == "SIMPAN") {
          $nama = addslashes($_POST[nama]);
          $nama_ortu = addslashes($_POST[nama_ortu]);
          $alamat = addslashes($_POST[alamat]);
          $alamat_ortu = addslashes($_POST[alamat_ortu]);
          if (($_COOKIE['level'] == "1" or $_COOKIE[level] == "2") and $_POST['user_name'] != $_POST['user_name_lama']) {
            $user_password = MD5($_POST[user_name]);
            $update_admin = mysqli_query($con, "UPDATE `admin`
          SET
          `username`='$_POST[user_name]',
          `password`='$user_password'
          WHERE `username`='$_POST[user_name_lama]'");

            $nim_baru = $_POST[user_name];
            $nim_lama = $_POST[user_name_lama];
            //tabel biodata_mhsw
            $update_biodata_mhsw = mysqli_query($con, "UPDATE `biodata_mhsw` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            //tabel evaluasi
            $update_evaluasi = mysqli_query($con, "UPDATE `evaluasi` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            //tabel evaluasi_stase
            $update_evaluasi_stase = mysqli_query($con, "UPDATE `evaluasi_stase` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            //tabel evaluasi
            $update_evaluasi = mysqli_query($con, "UPDATE `evaluasi` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            //tabel internal_Mxxx dan stase_Mxxx
            $stase = mysqli_query($con, "SELECT `id` FROM `kepaniteraan` ORDER BY `id`");
            while ($data_stase = mysqli_fetch_array($stase)) {
              $internal_id = "internal_" . $data_stase[id];
              $stase_id = "stase_" . $data_stase[id];
              $update_internal_id = mysqli_query($con, "UPDATE `$internal_id` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
              $update_stase_id = mysqli_query($con, "UPDATE `$stase_id` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            }
            //tabel jurnal_ketrampilan
            $update_jurnal_ketrampilan = mysqli_query($con, "UPDATE `jurnal_ketrampilan` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            //tabel jurnal_penyakit
            $update_jurnal_penyakit = mysqli_query($con, "UPDATE `jurnal_penyakit` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");

            //update nilai bagian
            //M091 - Ilmu Penyakit Dalam
            $update_ipd_nilai_kasus = mysqli_query($con, "UPDATE `ipd_nilai_kasus` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_ipd_nilai_minicex = mysqli_query($con, "UPDATE `ipd_nilai_minicex` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_ipd_nilai_test = mysqli_query($con, "UPDATE `ipd_nilai_test` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_ipd_nilai_ujian = mysqli_query($con, "UPDATE `ipd_nilai_ujian` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            //M092 - Neurologi
            $update_neuro_nilai_cbd = mysqli_query($con, "UPDATE `neuro_nilai_cbd` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_neuro_nilai_jurnal = mysqli_query($con, "UPDATE `neuro_nilai_jurnal` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_neuro_nilai_minicex = mysqli_query($con, "UPDATE `neuro_nilai_minicex` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_neuro_nilai_spv = mysqli_query($con, "UPDATE `neuro_nilai_spv` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_neuro_nilai_test = mysqli_query($con, "UPDATE `neuro_nilai_test` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            //M093 - Ilmu Kesehatan Jiwa
            $update_psikiatri_nilai_cbd = mysqli_query($con, "UPDATE `psikiatri_nilai_cbd` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_psikiatri_nilai_jurnal = mysqli_query($con, "UPDATE `psikiatri_nilai_jurnal` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_psikiatri_nilai_minicex = mysqli_query($con, "UPDATE `psikiatri_nilai_minicex` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_psikiatri_nilai_osce = mysqli_query($con, "UPDATE `psikiatri_nilai_osce` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_psikiatri_nilai_test = mysqli_query($con, "UPDATE `psikiatri_nilai_test` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            //M105 - Ilmu Kesehatan THT-KL
            $update_thtkl_nilai_jurnal = mysqli_query($con, "UPDATE `thtkl_nilai_jurnal` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_thtkl_nilai_osce = mysqli_query($con, "UPDATE `thtkl_nilai_osce` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_thtkl_nilai_presentasi = mysqli_query($con, "UPDATE `thtkl_nilai_presentasi` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_thtkl_nilai_responsi = mysqli_query($con, "UPDATE `thtkl_nilai_responsi` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_thtkl_nilai_test = mysqli_query($con, "UPDATE `thtkl_nilai_test` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            //M095 - IKM-KP
            $update_ikmkp_nilai_komprehensip = mysqli_query($con, "UPDATE `ikmkp_nilai_komprehensip` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_ikmkp_nilai_p2ukm = mysqli_query($con, "UPDATE `ikmkp_nilai_p2ukm` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_ikmkp_nilai_pkbi = mysqli_query($con, "UPDATE `ikmkp_nilai_pkbi` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_ikmkp_nilai_test = mysqli_query($con, "UPDATE `ikmkp_nilai_test` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            //M101 - Ilmu Bedah
            $update_bedah_nilai_mentor = mysqli_query($con, "UPDATE `bedah_nilai_mentor` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_bedah_nilai_test = mysqli_query($con, "UPDATE `bedah_nilai_test` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            //M102 - Anestesi dan Intensive Care
            $update_anestesi_nilai_dops = mysqli_query($con, "UPDATE `anestesi_nilai_dops` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_anestesi_nilai_osce = mysqli_query($con, "UPDATE `anestesi_nilai_osce` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_anestesi_nilai_test = mysqli_query($con, "UPDATE `anestesi_nilai_test` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            //M103 - Radiologi
            $update_radiologi_nilai_cbd = mysqli_query($con, "UPDATE `radiologi_nilai_cbd` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_radiologi_nilai_jurnal = mysqli_query($con, "UPDATE `radiologi_nilai_jurnal` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_radiologi_nilai_test = mysqli_query($con, "UPDATE `radiologi_nilai_test` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            //M104 - Ilmu Kesehatan Mata
            $update_mata_nilai_jurnal = mysqli_query($con, "UPDATE `mata_nilai_jurnal` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_mata_nilai_jurnal_penyanggah_1 = mysqli_query($con, "UPDATE `mata_nilai_jurnal` SET `penyanggah_1`='$nim_baru' WHERE `penyanggah_1`='$nim_lama'");
            $update_mata_nilai_jurnal_penyanggah_2 = mysqli_query($con, "UPDATE `mata_nilai_jurnal` SET `penyanggah_2`='$nim_baru' WHERE `penyanggah_2`='$nim_lama'");
            $update_mata_nilai_jurnal_penyanggah_3 = mysqli_query($con, "UPDATE `mata_nilai_jurnal` SET `penyanggah_3`='$nim_baru' WHERE `penyanggah_3`='$nim_lama'");
            $update_mata_nilai_jurnal_penyanggah_4 = mysqli_query($con, "UPDATE `mata_nilai_jurnal` SET `penyanggah_4`='$nim_baru' WHERE `penyanggah_4`='$nim_lama'");
            $update_mata_nilai_jurnal_penyanggah_5 = mysqli_query($con, "UPDATE `mata_nilai_jurnal` SET `penyanggah_5`='$nim_baru' WHERE `penyanggah_5`='$nim_lama'");
            $update_mata_nilai_minicex = mysqli_query($con, "UPDATE `mata_nilai_minicex` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_mata_nilai_penyanggah = mysqli_query($con, "UPDATE `mata_nilai_penyanggah` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_mata_nilai_penyanggah_presenter = mysqli_query($con, "UPDATE `mata_nilai_penyanggah` SET `presenter`='$nim_baru' WHERE `presenter`='$nim_lama'");
            $update_mata_nilai_presentasi = mysqli_query($con, "UPDATE `mata_nilai_presentasi` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_mata_nilai_presentasi_penyanggah_1 = mysqli_query($con, "UPDATE `mata_nilai_presentasi` SET `penyanggah_1`='$nim_baru' WHERE `penyanggah_1`='$nim_lama'");
            $update_mata_nilai_presentasi_penyanggah_2 = mysqli_query($con, "UPDATE `mata_nilai_presentasi` SET `penyanggah_2`='$nim_baru' WHERE `penyanggah_2`='$nim_lama'");
            $update_mata_nilai_presentasi_penyanggah_3 = mysqli_query($con, "UPDATE `mata_nilai_presentasi` SET `penyanggah_3`='$nim_baru' WHERE `penyanggah_3`='$nim_lama'");
            $update_mata_nilai_presentasi_penyanggah_4 = mysqli_query($con, "UPDATE `mata_nilai_presentasi` SET `penyanggah_4`='$nim_baru' WHERE `penyanggah_4`='$nim_lama'");
            $update_mata_nilai_presentasi_penyanggah_5 = mysqli_query($con, "UPDATE `mata_nilai_presentasi` SET `penyanggah_5`='$nim_baru' WHERE `penyanggah_5`='$nim_lama'");
            $update_mata_nilai_test = mysqli_query($con, "UPDATE `mata_nilai_test` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            //M094 - IKFR
            $update_ikfr_nilai_kasus = mysqli_query($con, "UPDATE `ikfr_nilai_kasus` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_ikfr_nilai_minicex = mysqli_query($con, "UPDATE `ikfr_nilai_minicex` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_ikfr_nilai_test = mysqli_query($con, "UPDATE `ikfr_nilai_test` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            //M106 - IKGM
            $update_ikgm_nilai_jurnal = mysqli_query($con, "UPDATE `ikgm_nilai_jurnal` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_ikgm_nilai_kasus = mysqli_query($con, "UPDATE `ikgm_nilai_kasus` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_ikgm_nilai_responsi = mysqli_query($con, "UPDATE `ikgm_nilai_responsi` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_ikgm_nilai_test = mysqli_query($con, "UPDATE `ikgm_nilai_test` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            //M111 - Ilmu Kebidanan dan Penyakit Kandungan
            $update_obsgyn_nilai_cbd = mysqli_query($con, "UPDATE `obsgyn_nilai_cbd` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_obsgyn_nilai_jurnal = mysqli_query($con, "UPDATE `obsgyn_nilai_jurnal` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_obsgyn_nilai_minicex = mysqli_query($con, "UPDATE `obsgyn_nilai_minicex` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_obsgyn_nilai_test = mysqli_query($con, "UPDATE `obsgyn_nilai_test` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            //M112 - Kedokteran Forensik dan Medikolegal
            $update_forensik_nilai_jaga = mysqli_query($con, "UPDATE `forensik_nilai_jaga` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_forensik_nilai_referat = mysqli_query($con, "UPDATE `forensik_nilai_referat` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_forensik_nilai_substase = mysqli_query($con, "UPDATE `forensik_nilai_substase` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_forensik_nilai_test = mysqli_query($con, "UPDATE `forensik_nilai_test` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_forensik_nilai_visum = mysqli_query($con, "UPDATE `forensik_nilai_visum` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            //M113 - Ilmu Kesehatan Anak
            $update_ika_nilai_cbd = mysqli_query($con, "UPDATE `ika_nilai_cbd` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_ika_nilai_dops = mysqli_query($con, "UPDATE `ika_nilai_dops` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_ika_nilai_jurnal = mysqli_query($con, "UPDATE `ika_nilai_jurnal` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_ika_nilai_kasus = mysqli_query($con, "UPDATE `ika_nilai_kasus` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_ika_nilai_minicex = mysqli_query($con, "UPDATE `ika_nilai_minicex` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_ika_nilai_minipat = mysqli_query($con, "UPDATE `ika_nilai_minipat` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_ika_nilai_test = mysqli_query($con, "UPDATE `ika_nilai_test` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_ika_nilai_ujian = mysqli_query($con, "UPDATE `ika_nilai_ujian` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            //M114 - Ilmu Kesehatan Kulit dan Kelamin
            $update_kulit_nilai_cbd = mysqli_query($con, "UPDATE `kulit_nilai_cbd` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_kulit_nilai_test = mysqli_query($con, "UPDATE `kulit_nilai_test` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            //M121 - Komprehensip dan Kedokteran Keluarga
            //Komprehensip
            $update_kompre_nilai_cbd = mysqli_query($con, "UPDATE `kompre_nilai_cbd` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_kompre_nilai_laporan = mysqli_query($con, "UPDATE `kompre_nilai_laporan` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_kompre_nilai_presensi = mysqli_query($con, "UPDATE `kompre_nilai_presensi` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_kompre_nilai_sikap = mysqli_query($con, "UPDATE `kompre_nilai_sikap` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            //Kedokteran Keluarga
            $update_kdk_nilai_dops = mysqli_query($con, "UPDATE `kdk_nilai_dops` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_kdk_nilai_kasus = mysqli_query($con, "UPDATE `kdk_nilai_kasus` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_kdk_nilai_minicex = mysqli_query($con, "UPDATE `kdk_nilai_minicex` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_kdk_nilai_presensi = mysqli_query($con, "UPDATE `kdk_nilai_presensi` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
            $update_kdk_nilai_sikap = mysqli_query($con, "UPDATE `kdk_nilai_sikap` SET `nim`='$nim_baru' WHERE `nim`='$nim_lama'");
          }

          if ($_POST['user_pass'] != "") {
            $user_password = MD5($_POST['user_pass']);
            $update_admin = mysqli_query($con, "UPDATE `admin`
          SET
          `nama`='$nama',
          `password`='$user_password'
          WHERE `username`='$_POST[user_name]'");
          } else {
            $update_admin = mysqli_query($con, "UPDATE `admin`
          SET
          `nama`='$nama'
          WHERE `username`='$_POST[user_name]'");
          }

          $profile_file_name = $_FILES['foto_profile']['name'];
          $profile_file_temp = $_FILES['foto_profile']['tmp_name'];
          if ($profile_file_name != "" and $profile_file_temp != "") {
            $data_gbr = mysqli_fetch_array(mysqli_query($con, "SELECT `foto` FROM `biodata_mhsw` WHERE `nim`='$_POST[user_name]'"));
            if ($data_gbr['foto'] != "profil_blank.png") unlink("foto/" . $data_gbr['foto']);
            $ektensi = strtolower(pathinfo($profile_file_name, PATHINFO_EXTENSION)); //dapatkan info gambar
            $foto_profile = $_POST[user_name] . rand(1, 1000000) . "." . $ektensi;
            $dest_path = "foto/" . $foto_profile;
            move_uploaded_file($profile_file_temp, $dest_path);
          } else {
            $data_gbr = mysqli_fetch_array(mysqli_query($con, "SELECT `foto` FROM `biodata_mhsw` WHERE `nim`='$_POST[user_name]'"));
            if ($data_gbr['foto'] != "profil_blank.png") unlink("foto/" . $data_gbr['foto']);
            $foto_profile = "profil_blank.png";
          }


          $update_mhsw = mysqli_query($con, "UPDATE `biodata_mhsw` SET
      `nama`='$nama',`nim`='$_POST[user_name]',
      `kota_lahir`='$_POST[kota_lahir]',`prop_lahir`='$_POST[prop_lahir]',`tanggal_lahir`='$_POST[tgl_lahir]',
      `alamat`='$alamat',`kota_alamat`='$_POST[kota_alamat]',`prop_alamat`='$_POST[prop_alamat]',
      `no_hp`='$_POST[no_hp]',`email`='$_POST[surel]',`nama_ortu`='$nama_ortu',
      `alamat_ortu`='$alamat_ortu',`kota_ortu`='$_POST[kota_ortu]',`prop_ortu`='$_POST[prop_ortu]',
      `no_hportu`='$_POST[no_hportu]',`foto`='$foto_profile', `dosen_wali`='$_POST[dosen_wali]' WHERE `nim`='$_POST[user_name]'");

          //Update Angkatan
          $angkatan_lama = mysqli_fetch_array(mysqli_query($con, "SELECT `angkatan` FROM `biodata_mhsw` WHERE `nim`='$_POST[user_name]'"));
          if ($angkatan_lama[angkatan] != $_POST[angkatan]) {
            $update_biodata = mysqli_query($con, "UPDATE `biodata_mhsw`
        SET `angkatan`='$_POST[angkatan]' WHERE `nim`='$_POST[user_name]'");
            $update_evaluasi = mysqli_query($con, "UPDATE `evaluasi`
        SET `angkatan`='$_POST[angkatan]' WHERE `nim`='$_POST[user_name]'");
            $update_jurnal_penyakit = mysqli_query($con, "UPDATE `jurnal_penyakit`
        SET `angkatan`='$_POST[angkatan]' WHERE `nim`='$_POST[user_name]'");
            $update_jurnal_ketrampilan = mysqli_query($con, "UPDATE `jurnal_ketrampilan`
        SET `angkatan`='$_POST[angkatan]' WHERE `nim`='$_POST[user_name]'");
          }



          if ($_COOKIE[level] == 1 or $_COOKIE[level] == 2)
            echo "
		<script>
			window.location.href=\"edit_user_mhsw.php?status=SIMPAN\";
		</script>
		";
          if ($_COOKIE[level] == 5)
            echo "
		<script>
			window.location.href=\"biodata.php\";
		</script>
		";
        }

        if ($_POST['hapus'] == "HAPUS") {
          $delete_admin = mysqli_query($con, "DELETE FROM `admin` WHERE `username`='$_POST[user_name]'");
          $delete_mhsw = mysqli_query($con, "DELETE FROM `biodata_mhsw` WHERE `nim`='$_POST[user_name]'");
          $delete_jurnal_penyakit = mysqli_query($con, "DELETE FROM `jurnal_penyakit` WHERE `nim`='$_POST[user_name]'");
          $delete_jurnal_ketrampilan = mysqli_query($con, "DELETE FROM `jurnal_ketrampilan` WHERE `nim`='$_POST[user_name]'");
          $delete_evaluasi = mysqli_query($con, "DELETE FROM `evaluasi` WHERE `nim`='$_POST[user_name]'");
          $delete_eval_stase = mysqli_query($con, "DELETE FROM `evaluasi_stase` WHERE `nim`='$_POST[user_name]'");
          $daftar_stase = mysqli_query($con, "SELECT `id` FROM `kepaniteraan` ORDER BY `id`");
          while ($data_stase = mysqli_fetch_array($daftar_stase)) {
            $stase_id = "stase_" . $data_stase[id];
            $internal_id = "internal_" . $data_stase[id];
            $delete_stase = mysqli_query($con, "DELETE FROM `$stase_id` WHERE `nim`='$_POST[user_name]'");
            $delete_internal = mysqli_query($con, "DELETE FROM `$internal_id` WHERE `nim`='$_POST[user_name]'");
          }


          //Delete nilai bagian
          //M091 - Ilmu Penyakit Dalam
          $delete_ipd_nilai_kasus = mysqli_query($con, "DELETE FROM `ipd_nilai_kasus` WHERE `nim`='$_POST[user_name]'");
          $delete_ipd_nilai_minicex = mysqli_query($con, "DELETE FROM `ipd_nilai_minicex` WHERE `nim`='$_POST[user_name]'");
          $delete_ipd_nilai_test = mysqli_query($con, "DELETE FROM `ipd_nilai_test` WHERE `nim`='$_POST[user_name]'");
          $delete_ipd_nilai_ujian = mysqli_query($con, "DELETE FROM `ipd_nilai_ujian` WHERE `nim`='$_POST[user_name]'");
          //M092 - Neurologi
          $delete_neuro_nilai_cbd = mysqli_query($con, "DELETE FROM `neuro_nilai_cbd` WHERE `nim`='$_POST[user_name]'");
          $delete_neuro_nilai_jurnal = mysqli_query($con, "DELETE FROM `neuro_nilai_jurnal` WHERE `nim`='$_POST[user_name]'");
          $delete_neuro_nilai_minicex = mysqli_query($con, "DELETE FROM `neuro_nilai_minicex` WHERE `nim`='$_POST[user_name]'");
          $delete_neuro_nilai_spv = mysqli_query($con, "DELETE FROM `neuro_nilai_spv` WHERE `nim`='$_POST[user_name]'");
          $delete_neuro_nilai_test = mysqli_query($con, "DELETE FROM `neuro_nilai_test` WHERE `nim`='$_POST[user_name]'");
          //M093 - Ilmu Kesehatan Jiwa
          $delete_psikiatri_nilai_cbd = mysqli_query($con, "DELETE FROM `psikiatri_nilai_cbd` WHERE `nim`='$_POST[user_name]'");
          $delete_psikiatri_nilai_jurnal = mysqli_query($con, "DELETE FROM `psikiatri_nilai_jurnal` WHERE `nim`='$_POST[user_name]'");
          $delete_psikiatri_nilai_minicex = mysqli_query($con, "DELETE FROM `psikiatri_nilai_minicex` WHERE `nim`='$_POST[user_name]'");
          $delete_psikiatri_nilai_osce = mysqli_query($con, "DELETE FROM `psikiatri_nilai_osce` WHERE `nim`='$_POST[user_name]'");
          $delete_psikiatri_nilai_test = mysqli_query($con, "DELETE FROM `psikiatri_nilai_test` WHERE `nim`='$_POST[user_name]'");
          //M105 - Ilmu Kesehatan THT-KL
          $delete_thtkl_nilai_jurnal = mysqli_query($con, "DELETE FROM `thtkl_nilai_jurnal` WHERE `nim`='$_POST[user_name]'");
          $delete_thtkl_nilai_osce = mysqli_query($con, "DELETE FROM `thtkl_nilai_osce` WHERE `nim`='$_POST[user_name]'");
          $delete_thtkl_nilai_presentasi = mysqli_query($con, "DELETE FROM `thtkl_nilai_presentasi` WHERE `nim`='$_POST[user_name]'");
          $delete_thtkl_nilai_responsi = mysqli_query($con, "DELETE FROM `thtkl_nilai_responsi` WHERE `nim`='$_POST[user_name]'");
          $delete_thtkl_nilai_test = mysqli_query($con, "DELETE FROM `thtkl_nilai_test` WHERE `nim`='$_POST[user_name]'");
          //M095 - IKM-KP
          $delete_ikmkp_nilai_komprehensip = mysqli_query($con, "DELETE FROM `ikmkp_nilai_komprehensip` WHERE `nim`='$_POST[user_name]'");
          $delete_ikmkp_nilai_p2ukm = mysqli_query($con, "DELETE FROM `ikmkp_nilai_p2ukm` WHERE `nim`='$_POST[user_name]'");
          $delete_ikmkp_nilai_pkbi = mysqli_query($con, "DELETE FROM `ikmkp_nilai_pkbi` WHERE `nim`='$_POST[user_name]'");
          $delete_ikmkp_nilai_test = mysqli_query($con, "DELETE FROM `ikmkp_nilai_test` WHERE `nim`='$_POST[user_name]'");
          //M101 - Ilmu Bedah
          $delete_bedah_nilai_mentor = mysqli_query($con, "DELETE FROM `bedah_nilai_mentor` WHERE `nim`='$_POST[user_name]'");
          $delete_bedah_nilai_test = mysqli_query($con, "DELETE FROM `bedah_nilai_test` WHERE `nim`='$_POST[user_name]'");
          //M102 - Anestesi dan Intensive Care
          $delete_anestesi_nilai_dops = mysqli_query($con, "DELETE FROM `anestesi_nilai_dops` WHERE `nim`='$_POST[user_name]'");
          $delete_anestesi_nilai_osce = mysqli_query($con, "DELETE FROM `anestesi_nilai_osce` WHERE `nim`='$_POST[user_name]'");
          $delete_anestesi_nilai_test = mysqli_query($con, "DELETE FROM `anestesi_nilai_test` WHERE `nim`='$_POST[user_name]'");
          //M103 - Radiologi
          $delete_radiologi_nilai_cbd = mysqli_query($con, "DELETE FROM `radiologi_nilai_cbd` WHERE `nim`='$_POST[user_name]'");
          $delete_radiologi_nilai_jurnal = mysqli_query($con, "DELETE FROM `radiologi_nilai_jurnal` WHERE `nim`='$_POST[user_name]'");
          $delete_radiologi_nilai_test = mysqli_query($con, "DELETE FROM `radiologi_nilai_test` WHERE `nim`='$_POST[user_name]'");
          //M104 - Ilmu Kesehatan Mata
          $delete_mata_nilai_jurnal = mysqli_query($con, "DELETE FROM `mata_nilai_jurnal` WHERE `nim`='$_POST[user_name]'");
          $delete_mata_nilai_jurnal_penyanggah_1 = mysqli_query($con, "UPDATE `mata_nilai_jurnal` SET `penyanggah_1`='' WHERE `penyanggah_1`='$_POST[user_name]'");
          $delete_mata_nilai_jurnal_penyanggah_2 = mysqli_query($con, "UPDATE `mata_nilai_jurnal` SET `penyanggah_2`='' WHERE `penyanggah_2`='$_POST[user_name]'");
          $delete_mata_nilai_jurnal_penyanggah_3 = mysqli_query($con, "UPDATE `mata_nilai_jurnal` SET `penyanggah_3`='' WHERE `penyanggah_3`='$_POST[user_name]'");
          $delete_mata_nilai_jurnal_penyanggah_4 = mysqli_query($con, "UPDATE `mata_nilai_jurnal` SET `penyanggah_4`='' WHERE `penyanggah_4`='$_POST[user_name]'");
          $delete_mata_nilai_jurnal_penyanggah_5 = mysqli_query($con, "UPDATE `mata_nilai_jurnal` SET `penyanggah_5`='' WHERE `penyanggah_5`='$_POST[user_name]'");
          $delete_mata_nilai_minicex = mysqli_query($con, "DELETE FROM `mata_nilai_minicex` WHERE `nim`='$_POST[user_name]'");
          $delete_mata_nilai_penyanggah = mysqli_query($con, "DELETE FROM `mata_nilai_penyanggah` WHERE `nim`='$_POST[user_name]'");
          $delete_mata_nilai_penyanggah_presenter = mysqli_query($con, "DELETE FROM `mata_nilai_penyanggah` WHERE `presenter`='$_POST[user_name]'");
          $delete_mata_nilai_presentasi = mysqli_query($con, "DELETE FROM `mata_nilai_presentasi` WHERE `nim`='$_POST[user_name]'");
          $delete_mata_nilai_presentasi_penyanggah_1 = mysqli_query($con, "UPDATE `mata_nilai_presentasi` SET `penyanggah_1`='' WHERE `penyanggah_1`='$_POST[user_name]'");
          $delete_mata_nilai_presentasi_penyanggah_2 = mysqli_query($con, "UPDATE `mata_nilai_presentasi` SET `penyanggah_2`='' WHERE `penyanggah_2`='$_POST[user_name]'");
          $delete_mata_nilai_presentasi_penyanggah_3 = mysqli_query($con, "UPDATE `mata_nilai_presentasi` SET `penyanggah_3`='' WHERE `penyanggah_3`='$_POST[user_name]'");
          $delete_mata_nilai_presentasi_penyanggah_4 = mysqli_query($con, "UPDATE `mata_nilai_presentasi` SET `penyanggah_4`='' WHERE `penyanggah_4`='$_POST[user_name]'");
          $delete_mata_nilai_presentasi_penyanggah_5 = mysqli_query($con, "UPDATE `mata_nilai_presentasi` SET `penyanggah_5`='' WHERE `penyanggah_5`='$_POST[user_name]'");
          $delete_mata_nilai_test = mysqli_query($con, "DELETE FROM `mata_nilai_test` WHERE `nim`='$_POST[user_name]'");
          //M094 - IKFR
          $delete_ikfr_nilai_kasus = mysqli_query($con, "DELETE FROM `ikfr_nilai_kasus` WHERE `nim`='$_POST[user_name]'");
          $delete_ikfr_nilai_minicex = mysqli_query($con, "DELETE FROM `ikfr_nilai_minicex` WHERE `nim`='$_POST[user_name]'");
          $delete_ikfr_nilai_test = mysqli_query($con, "DELETE FROM `ikfr_nilai_test` WHERE `nim`='$_POST[user_name]'");
          //M106 - IKGM
          $delete_ikgm_nilai_jurnal = mysqli_query($con, "DELETE FROM `ikgm_nilai_jurnal` WHERE `nim`='$_POST[user_name]'");
          $delete_ikgm_nilai_kasus = mysqli_query($con, "DELETE FROM `ikgm_nilai_kasus` WHERE `nim`='$_POST[user_name]'");
          $delete_ikgm_nilai_responsi = mysqli_query($con, "DELETE FROM `ikgm_nilai_responsi` WHERE `nim`='$_POST[user_name]'");
          $delete_ikgm_nilai_test = mysqli_query($con, "DELETE FROM `ikgm_nilai_test` WHERE `nim`='$_POST[user_name]'");
          //M111 - Ilmu Kebidanan dan Penyakit Kandungan
          $delete_obsgyn_nilai_cbd = mysqli_query($con, "DELETE FROM `obsgyn_nilai_cbd` WHERE `nim`='$_POST[user_name]'");
          $delete_obsgyn_nilai_jurnal = mysqli_query($con, "DELETE FROM `obsgyn_nilai_jurnal` WHERE `nim`='$_POST[user_name]'");
          $delete_obsgyn_nilai_minicex = mysqli_query($con, "DELETE FROM `obsgyn_nilai_minicex` WHERE `nim`='$_POST[user_name]'");
          $delete_obsgyn_nilai_test = mysqli_query($con, "DELETE FROM `obsgyn_nilai_test` WHERE `nim`='$_POST[user_name]'");
          //M112 - Kedokteran Forensik dan Medikolegal
          $delete_forensik_nilai_jaga = mysqli_query($con, "DELETE FROM `forensik_nilai_jaga` WHERE `nim`='$_POST[user_name]'");
          $delete_forensik_nilai_referat = mysqli_query($con, "DELETE FROM `forensik_nilai_referat` WHERE `nim`='$_POST[user_name]'");
          $delete_forensik_nilai_substase = mysqli_query($con, "DELETE FROM `forensik_nilai_substase` WHERE `nim`='$_POST[user_name]'");
          $delete_forensik_nilai_test = mysqli_query($con, "DELETE FROM `forensik_nilai_test` WHERE `nim`='$_POST[user_name]'");
          $delete_forensik_nilai_visum = mysqli_query($con, "DELETE FROM `forensik_nilai_visum` WHERE `nim`='$_POST[user_name]'");
          //M113 - Ilmu Kesehatan Anak
          $delete_ika_nilai_cbd = mysqli_query($con, "DELETE FROM `ika_nilai_cbd` WHERE `nim`='$_POST[user_name]'");
          $delete_ika_nilai_dops = mysqli_query($con, "DELETE FROM `ika_nilai_dops` WHERE `nim`='$_POST[user_name]'");
          $delete_ika_nilai_jurnal = mysqli_query($con, "DELETE FROM `ika_nilai_jurnal` WHERE `nim`='$_POST[user_name]'");
          $delete_ika_nilai_kasus = mysqli_query($con, "DELETE FROM `ika_nilai_kasus` WHERE `nim`='$_POST[user_name]'");
          $delete_ika_nilai_minicex = mysqli_query($con, "DELETE FROM `ika_nilai_minicex` WHERE `nim`='$_POST[user_name]'");
          $delete_ika_nilai_minipat = mysqli_query($con, "DELETE FROM `ika_nilai_minipat` WHERE `nim`='$_POST[user_name]'");
          $delete_ika_nilai_test = mysqli_query($con, "DELETE FROM `ika_nilai_test` WHERE `nim`='$_POST[user_name]'");
          $delete_ika_nilai_ujian = mysqli_query($con, "DELETE FROM `ika_nilai_ujian` WHERE `nim`='$_POST[user_name]'");
          //M114 - Ilmu Kesehatan Kulit dan Kelamin
          $delete_kulit_nilai_cbd = mysqli_query($con, "DELETE FROM `kulit_nilai_cbd` WHERE `nim`='$_POST[user_name]'");
          $delete_kulit_nilai_test = mysqli_query($con, "DELETE FROM `kulit_nilai_test` WHERE `nim`='$_POST[user_name]'");
          //M121 - Komprehensip dan Kedokteran Keluarga
          //Komprehensip
          $delete_kompre_nilai_cbd = mysqli_query($con, "DELETE FROM `kompre_nilai_cbd` WHERE `nim`='$_POST[user_name]'");
          $delete_kompre_nilai_laporan = mysqli_query($con, "DELETE FROM `kompre_nilai_laporan` WHERE `nim`='$_POST[user_name]'");
          $delete_kompre_nilai_presensi = mysqli_query($con, "DELETE FROM `kompre_nilai_presensi` WHERE `nim`='$_POST[user_name]'");
          $delete_kompre_nilai_sikap = mysqli_query($con, "DELETE FROM `kompre_nilai_sikap` WHERE `nim`='$_POST[user_name]'");
          //Kedokteran Keluarga
          $delete_kdk_nilai_dops = mysqli_query($con, "DELETE FROM `kdk_nilai_dops` WHERE `nim`='$_POST[user_name]'");
          $delete_kdk_nilai_kasus = mysqli_query($con, "DELETE FROM `kdk_nilai_kasus` WHERE `nim`='$_POST[user_name]'");
          $delete_kdk_nilai_minicex = mysqli_query($con, "DELETE FROM `kdk_nilai_minicex` WHERE `nim`='$_POST[user_name]'");
          $delete_kdk_nilai_presensi = mysqli_query($con, "DELETE FROM `kdk_nilai_presensi` WHERE `nim`='$_POST[user_name]'");
          $delete_kdk_nilai_sikap = mysqli_query($con, "DELETE FROM `kdk_nilai_sikap` WHERE `nim`='$_POST[user_name]'");

          echo "
		<script>
			window.location.href=\"edit_user_mhsw.php?status=HAPUS\";
		</script>
		";
        }
        $nama = isset($_COOKIE['nama']) ? $_COOKIE['nama'] : 'User';
        $gelar = isset($_COOKIE['gelar']) ? $_COOKIE['gelar'] : '';
      } else
        echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
    }
    ?>
    <?php
    echo "<table>";

    //Usernama dan Password
    echo "<tr class=\"ganjil\">";
    echo "<td style=\"width:300px\">";
    echo "Username (NIM)<br><br>Password";
    echo "</td>";
    echo "<td>";
    if ($_COOKIE[level] != "1" and $_COOKIE['level'] != "2") {
      echo "$data_user[username]</font>";
      echo "<input type=\"hidden\" name=\"user_name\" value=\"$data_user[username]\">";
      echo "<br><font style=\"font-size:0.625em;font-family:GEORGIA\"><i>(Username tidak bisa diubah, NIM mahasiswa. Jika terjadi kesalahan, silakan hubungi admin Prodi!)</i></font><br>";
    } else {
      echo "<input type=\"hidden\" name=\"user_name_lama\" value=\"$data_user[username]\">";
      echo "<input type=\"text\" name=\"user_name\" value=\"$data_user[username]\" class=\"select_art\" >";
      echo "<br><font style=\"font-size:0.625em;font-family:GEORGIA\"><i>(Pastikan untuk menghubungi pemilik akun setelah melakukan perubahan username dan password!)</i></font><br>";
    }
    echo "<input type=\"password\" id=\"form-password\" class=\"select_art\" name=\"user_pass\" />";
    echo "&nbsp;&nbsp;<input type=\"checkbox\" id=\"form-checkbox\" /> &nbsp;<font style=\"font-size:0.625em\"><i>Show password</i></font>";
    echo "<br><font style=\"font-size:0.625em;font-family:GEORGIA\"><i>(Jika kosong/tidak diisi, password tidak berubah!)</i></font>";
    echo "</td>";
    echo "</tr>";

    //Angkatan
    echo "<tr class=\"ganjil\">";
    echo "<td>";
    echo "Angkatan Koas";
    echo "</td>";
    if ($_COOKIE[level] != "5") {
      echo "<td>";
      echo "<input type=\"text\" class=\"select_artwide\" name=\"angkatan\" value=\"$biodata_mhsw[angkatan]\" />";
      echo "</td>";
    } else {
      echo "<td>";
      echo "$biodata_mhsw[angkatan]<br>";
      echo "<input type=\"hidden\" name=\"angkatan\" value=\"$biodata_mhsw[angkatan]\" />";
      echo "<font style=\"font-size:0.625em;font-family:GEORGIA\"><i>(Jika terjadi kesalahan, silakan hubungi admin Prodi!)</i></font>";
      echo "</td>";
    }
    echo "</tr>";

    //Nama Lengkap
    echo "<tr class=\"genap\">";
    echo "<td>";
    echo "Nama Lengkap";
    echo "</td>";
    echo "<td>";
    echo "<input type=\"text\" class=\"select_artwide\" name=\"nama\" value=\"$data_user[nama]\" />";
    echo "</td>";
    echo "</tr>";

    //Propinsi Lahir
    echo "<tr class=\"ganjil\">";
    echo "<td>";
    echo "Propinsi Lahir";
    echo "</td>";
    echo "<td>";
    echo "<select class=\"select_artwide\" name=\"prop_lahir\" id=\"prop_lahir\">";
    $prop = mysqli_query($con, "SELECT * FROM `prop` ORDER BY `prop`");
    $dataprop = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `prop` WHERE `id_prop`='$biodata_mhsw[prop_lahir]'"));
    echo "<option value=\"$dataprop[id_prop]\">$dataprop[prop]</option>";
    while ($dat_prop = mysqli_fetch_array($prop))
      echo "<option value=\"$dat_prop[id_prop]\">$dat_prop[prop]</option>";
    echo "</select>";
    echo "</td>";
    echo "</tr>";

    //Kota Lahir
    echo "<tr class=\"genap\">";
    echo "<td>";
    echo "Kota Lahir";
    echo "</td>";
    echo "<td>";
    echo "<select name=\"kota_lahir\" id=\"kota_lahir\" class=\"select_artwide\">";
    $data_kota = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kota` WHERE `id_kota`='$biodata_mhsw[kota_lahir]'"));
    echo "<option value=\"$data_kota[id_kota]\">$data_kota[kota]</option>";
    echo "</select>";
    echo "</td>";
    echo "</tr>";

    //Tanggal Lahir
    echo "<tr class=\"ganjil\">";
    echo "<td>";
    echo "Tanggal Lahir <i>(yyyy-mm-dd)</i>";
    echo "</td>";
    echo "<td>";
    echo "<input type=\"text\" id=\"input-tanggal\" name=\"tgl_lahir\" class=\"select_artwide\" value=\"$biodata_mhsw[tanggal_lahir]\" />";
    echo "</td>";
    echo "</tr>";

    //Alamat
    echo "<tr class=\"genap\">";
    echo "<td>";
    echo "Alamat";
    echo "</td>";
    echo "<td>";
    echo "<input type=\"text\" class=\"select_artwide\" name=\"alamat\" value=\"$biodata_mhsw[alamat]\" />";
    echo "</td>";
    echo "</tr>";

    //Propinsi Alamat
    echo "<tr class=\"ganjil\">";
    echo "<td>";
    echo "Propinsi Alamat";
    echo "</td>";
    echo "<td>";
    echo "<select class=\"select_artwide\" name=\"prop_alamat\" id=\"prop_alamat\">";
    $prop = mysqli_query($con, "SELECT * FROM `prop` ORDER BY `prop`");
    $dataprop = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `prop` WHERE `id_prop`='$biodata_mhsw[prop_alamat]'"));
    echo "<option value=\"$dataprop[id_prop]\">$dataprop[prop]</option>";
    while ($dat_prop = mysqli_fetch_array($prop))
      echo "<option value=\"$dat_prop[id_prop]\">$dat_prop[prop]</option>";
    echo "</select>";
    echo "</td>";
    echo "</tr>";

    //Kota Alamat
    echo "<tr class=\"genap\">";
    echo "<td>";
    echo "Kota Alamat";
    echo "</td>";
    echo "<td>";
    echo "<select name=\"kota_alamat\" id=\"kota_alamat\" class=\"select_artwide\">";
    $data_kota = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kota` WHERE `id_kota`='$biodata_mhsw[kota_alamat]'"));
    echo "<option value=\"$data_kota[id_kota]\">$data_kota[kota]</option>";
    echo "</select>";
    echo "</td>";
    echo "</tr>";

    //No HP (Telepon)
    echo "<tr class=\"ganjil\">";
    echo "<td>";
    echo "No HP (Telepon)";
    echo "</td>";
    echo "<td>";
    echo "<input type=\"text\" class=\"select_artwide\" name=\"no_hp\" value=\"$biodata_mhsw[no_hp]\" />";
    echo "</td>";
    echo "</tr>";

    //Alamat Email
    echo "<tr class=\"genap\">";
    echo "<td>";
    echo "Alamat Surat Elektronik (E-m@il)";
    echo "</td>";
    echo "<td>";
    echo "<input type=\"text\" class=\"select_artwide\" name=\"surel\" value=\"$biodata_mhsw[email]\" />";
    echo "</td>";
    echo "</tr>";


    //Nama Wali (Orang Tua)
    echo "<tr class=\"ganjil\">";
    echo "<td>";
    echo "Nama Wali (Orang Tua)";
    echo "</td>";
    echo "<td>";
    echo "<input type=\"text\" class=\"select_artwide\" name=\"nama_ortu\" value=\"$biodata_mhsw[nama_ortu]\" />";
    echo "</td>";
    echo "</tr>";

    //Alamat Wali (Orang Tua)
    echo "<tr class=\"genap\">";
    echo "<td>";
    echo "Alamat Wali (Orang Tua)";
    echo "</td>";
    echo "<td>";
    echo "<input type=\"text\" class=\"select_artwide\" name=\"alamat_ortu\" value=\"$biodata_mhsw[alamat_ortu]\" />";
    echo "</td>";
    echo "</tr>";

    //Propinsi Alamat Wali (Orang Tua)
    echo "<tr class=\"ganjil\">";
    echo "<td>";
    echo "Propinsi Alamat Wali (Orang Tua)";
    echo "</td>";
    echo "<td>";
    echo "<select class=\"select_artwide\" name=\"prop_ortu\" id=\"prop_ortu\">";
    $prop = mysqli_query($con, "SELECT * FROM `prop` ORDER BY `prop`");
    $dataprop = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `prop` WHERE `id_prop`='$biodata_mhsw[prop_ortu]'"));
    echo "<option value=\"$dataprop[id_prop]\">$dataprop[prop]</option>";
    while ($dat_prop = mysqli_fetch_array($prop))
      echo "<option value=\"$dat_prop[id_prop]\">$dat_prop[prop]</option>";
    echo "</select>";
    echo "</td>";
    echo "</tr>";

    //Kota Alamat Wali (Orang Tua)
    echo "<tr class=\"genap\">";
    echo "<td>";
    echo "Kota Alamat Wali (Orang Tua)";
    echo "</td>";
    echo "<td>";
    echo "<select name=\"kota_ortu\" id=\"kota_ortu\" class=\"select_artwide\">";
    $data_kota = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kota` WHERE `id_kota`='$biodata_mhsw[kota_ortu]'"));
    echo "<option value=\"$data_kota[id_kota]\">$data_kota[kota]</option>";
    echo "</select>";
    echo "</td>";
    echo "</tr>";

    //No HP (Telepon) Ortu
    echo "<tr class=\"ganjil\">";
    echo "<td>";
    echo "No HP (Telepon) Wali (Orang Tua)";
    echo "</td>";
    echo "<td>";
    echo "<input type=\"text\" class=\"select_artwide\" name=\"no_hportu\" value=\"$biodata_mhsw[no_hportu]\" />";
    echo "</td>";
    echo "</tr>";

    //Dosen Wali
    echo "<tr class=\"genap\">";
    echo "<td>";
    echo "Dosen Wali";
    echo "</td>";
    echo "<td>";
    $dosen_wali = mysqli_fetch_array(mysqli_query($con, "SELECT `nama`,`gelar` FROM `dosen` WHERE `nip`='$biodata_mhsw[dosen_wali]'"));
    echo "<select class=\"select_artwide\" name=\"dosen_wali\" id=\"dosen_wali\" required>";
    $dosen = mysqli_query($con, "SELECT `username`,`nama` FROM `admin` WHERE `level`='4' ORDER BY `nama`");
    if ($biodata_mhsw[dosen_wali] == "")
      echo "<option value=\"\">< Pilihan Dosen Wali ></option>";
    else
      echo "<option value=\"$biodata_mhsw[dosen_wali]\">$dosen_wali[nama], $dosen_wali[gelar] (NIP. $biodata_mhsw[dosen_wali])</option>";
    while ($dat9 = mysqli_fetch_array($dosen)) {
      $data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$dat9[username]'"));
      echo "<option value=\"$dat9[username]\">$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</option>";
    }
    echo "</select>";
    echo "</td>";
    echo "</tr>";

    echo "<tr class=\"ganjil\"><td colspan=2>&nbsp;</td></tr>";

    echo "<tr class=\"genap\">";
    echo "<td colspan=2 style=\"text-align:center\">";
    echo "<br><input type=\"submit\" class=\"submit1\" name=\"cancel\" value=\"CANCEL\" formnovalidate />";
    echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" class=\"submit1\" name=\"simpan\" value=\"SIMPAN\" />";
    if ($_COOKIE[level] == 1) echo "&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" class=\"submit1\" name=\"hapus\" value=\"HAPUS\" formnovalidate/>";
    echo "<br><br></td>";
    echo "</tr>";

    echo "</table></form>";


    echo "</fieldset>";
    ?>
    <!-- End Content -->
    <!-- Back to Top Button -->
    <button onclick="topFunction()" id="backToTopBtn" title="Go to top">
      <i class="fa-solid fa-arrow-up"></i>
      <div>Top</div>
    </button>

    <!-- Start Footer -->
    <footer class="footer">
      <div class="container-fluid">
        <div class="row text-body-secondary">
          <div class="col-6 text-start">
            <a href="#" class="text-body-secondary">
              <strong>Program Studi Pendidikan Profesi Dokter <br>
                Universitas Diponegoro
                Jl.Prof. H. Soedarto, SH. Tembalang Semarang
              </strong>
              <br>
              <strong>
                Kode Pos: 50275 |
              </strong>
              <strong>
                <i class="lni lni-phone" style="color: inherit;"></i>
                :024 – 76928010 |
              </strong>
              <strong>
                Kotak Pos: 1269
              </strong>
              <br>
              <strong>
                Fax.: 024 – 76928011 |
              </strong>
              <strong>
                <i class="lni lni-envelope" style="color: inherit;"></i>
                :dean@fk.undip.ac.id
              </strong>
            </a>
          </div>
          <div class="col-6 text-end text-body-secondary d-none d-md-block">
            <a href="#" class="text-body-secondary">
              <strong>Ketua Prodi Pendidikan Profesi Dokter <br>
                Fakultas Kedokteran UNDIP - Gd A Lt. 2
              </strong>
              <br>
              <strong>
                <i class="lni lni-phone" style="color: inherit;"></i>
                :+62 812-2868-576 |
              </strong>
              <strong>
                <i class="lni lni-envelope" style="color: inherit;"></i>
                :cnawangsih@yahoo.com
              </strong>
              <br>
              <strong style="color: #0A3967;">
                Build since @2024
              </strong>
            </a>
          </div>
          <div class="col-12 text-center  d-none d-md-block" style="color: #0A3967; ">
            <a href=" https://play.google.com/store/apps/details?id=logbook.koas.logbookkoas&hl=in" target="blank">
              <strong>
                <<< Install Aplikasi Android di Playstore>>>
              </strong>
            </a>
          </div>
        </div>
      </div>
    </footer>
    <!-- End Footer -->

  </div>
  </div>
  <!-- Script Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

  <script src="javascript/script1.js"></script>
  <script src="javascript/buttontopup.js"></script>
</body>

</HTML>