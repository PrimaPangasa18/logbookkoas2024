<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>On-line Update NIM KOAS Logbook Koas Pendidikan Dokter FK-UNDIP</title>
  <link rel="shortcut icon" type="x-icon" href="images/undipsolid.png">
  <link rel="stylesheet" href="style/style1.css" />
  <link rel="stylesheet" href="style/buttontopup.css">


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

    error_reporting("E_ALL ^ E_NOTICE");
    set_time_limit(0);

    if (empty($_COOKIE['user']) || empty($_COOKIE['pass'])) {
      echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
    } else {
      if (!empty($_COOKIE['user']) and !empty($_COOKIE['pass']) and ($_COOKIE['level'] == 1)) {
        include "menu1.php";
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
    if ($_COOKIE['level'] != 5) {
      $data_nim = $_GET['nim'];
      $data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_nim'"));
    } else {
      $data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
    }

    // Menentukan path gambar
    $foto_path = "foto/" . $data_mhsw['foto'];
    $default_foto = "foto/profil_blank.png";

    // Mengecek apakah file gambar ada
    if (!file_exists($foto_path) || empty($data_mhsw['foto'])) {
      $foto_path = $default_foto;
    }
    ?>
    <!-- End Sidebar -->
    <div class="main">
      <!-- Start Navbar -->
      <nav class="navbar navbar-expand px-4 py-3" style="background-color: #006400; ">
        <form action="#" class="d-none d-sm-inline-block">
          <div class="input-group input-group-navbar">
            <img src="images/undipsolid.png" alt="" style="width: 45px;">
          </div>
        </form>
        <div class="navbar-collapse collapse">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown d-flex align-item-center">
              <span class="navbar-text me-2" style="color: white;">Halo, <?php echo $nama . ' , <span class="gelar" >' . $gelar . '</span>'; ?></span>
              <a href="#" class="nav-icon pe-md-0" data-bs-toggle="dropdown">
                <img src="<?php echo $foto_path; ?>" class="avatar img-fluid rounded-circle" alt="" style=" width:40px; height:40px" />
              </a>
              <div class="dropdown-menu dropdown-menu-end rounded">

                <div class="dropdown-menu dropdown-menu-end rounded"></div>
                <a href="logout.php" class="dropdown-item">
                  <i class="lni lni-exit"></i>
                  <span>Logout</span>
                </a>
              </div>
            </li>
          </ul>
        </div>
      </nav>
      <!-- End Navbar -->

      <!-- Main Content -->
      <main class="content px-3 py-4" style="background-image: url('images/undip_watermark_color.png'), url('images/undip_watermark_color.png'); ">
        <div class="container-fluid">
          <div class="mb-3">
            <h3 class="fw-bold fs-4 mb-3">UPDATE NIM MAHASISWA KOAS</h3>
            <br />
            <h2 class="fw-bold fs-4 mb-3 text-center" style="color: #0a3967">
              UPDATE NIM MAHASISWA KOAS
            </h2>
            <br><br>
            <?php
            if (isset($_POST['import']) and !empty($_FILES['update_nim_koas']['tmp_name'])) {
              $file = $_FILES['update_nim_koas']['tmp_name'];
              $handle = fopen($file, "r");
              $separator = $_POST['separator'];
              $sukses = 0;
              $fail = 0;
              $id = 1;
              while (($filesop = fgetcsv($handle, 1000, $separator)) !== false) {
                if ($id > 1) {
                  $nim_lama = $filesop[2];
                  $nim_baru = $filesop[3];
                  $password_new = md5($nim_baru);
                  $jml_username = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `admin` WHERE `username`='$nim_lama'"));
                  if ($jml_username > 0) {
                    //tabel admin
                    $update_admin = mysqli_query($con, "UPDATE `admin` SET `username`='$nim_baru',`password`='$password_new' WHERE `username`='$nim_lama'");
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
                      $internal_id = "internal_" . $data_stase['id'];
                      $stase_id = "stase_" . $data_stase['id'];
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
                    echo "<br><br>";
                    echo "<center>";
                    echo "<div class='alert alert-success' style='width: 300px; font-weight:600;'>Updating NIM Lama: $nim_lama ----> NIM Baru: $nim_baru .... SUKSES</div>";
                    echo "</center>";
                    $sukses++;
                  } else {
                    echo "<center>";
                    echo "<div class='alert alert-danger' style='width: 300px; font-weight:600;'>Mahasiswa Koas dengan NIM $nim_lama belum memiliki akun di aplikasi E-Logbook Koas!!! .... GAGAL</div>";
                    echo "</center>";
                    $fail++;
                  }
                }
                $id++;
              }
              echo "<center>";
              echo "<div class='mt-4' style='width: 300px; font-weight:600;'>";
              echo "<h4>Proses update NIM selesai .... !!!</h4>";
              echo "<p>Jumlah mahasiswa koas yang telah diupdate NIM: <span class='badge bg-success'>$sukses</span></p>";
              echo "<p>Jumlah status GAGAL update: <span class='badge bg-danger'>$fail</span></p>";
              echo "</div>";
              echo "</center>";
            } else {
              echo "<center>";
              echo "<div class='alert alert-warning mt-4' style='width: 300px; font-weight:600;'>Maaf! Ada kesalahan input file!!!</div>";
              echo "</center>";
            }
            echo "<center>";
            echo "
<div class='mt-4'>
  <form method='POST' action='$_SERVER[PHP_SELF]'>
    <button type='submit' class='btn btn-primary' name='back' value='BACK'><i class='fa-solid fa-backward-step me-2'></i>BACK</button>
  </form>
</div>";
            echo "</center>";

            if ($_POST['back'] == "BACK") {
              echo "
  <script>
    window.location.href='update_nim_koas.php';
  </script>";
            }

            ?>
          </div>
        </div>
      </main>


      <!-- End Content -->
      <!-- Back to Top Button -->
      <button onclick="topFunction()" id="backToTopBtn" title="Go to top">
        <i class="fa-solid fa-arrow-up"></i>
        <div>Top</div>
      </button>

      <!-- Start Footer -->
      <footer class="footer py-3" style="background-color: #0e2238;; color: #fff;">
        <div class="container-fluid">
          <div class="row text-white">
            <div class="col-12 col-md-6 text-start mb-3 mb-md-0">
              <a href="#" class="text-white">
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
            <div class="col-12 col-md-6 text-end text-white mb-3 mb-md-0">
              <a href="#" class="text-white">
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
                <strong style="color: inherit;">
                  Build since @2024
                </strong>
              </a>
            </div>
            <div class="col-12 text-center mt-3 mt-md-0" style="color: white;">
              <a href="https://play.google.com/store/apps/details?id=logbook.koas.logbookkoas&hl=in" target="blank" style="color: inherit; text-decoration: underline;">
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