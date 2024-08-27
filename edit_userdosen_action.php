<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>On-line Update Profil Logbook Koas Pendidikan Dokter FK-UNDIP</title>
  <link rel="shortcut icon" type="x-icon" href="images/undipsolid.png">
  <link rel="stylesheet" href="style/style1.css" />
  <link rel="stylesheet" href="style/buttontopup.css">
  <link rel="stylesheet" href="style/updateadmin.css">

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

    if (empty($_COOKIE['user']) || empty($_COOKIE['pass'])) {
      echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
    } else {
      if (!empty($_COOKIE['user']) and !empty($_COOKIE['pass']) and ($_COOKIE['level'] == 1 or $_COOKIE['level'] == 2 or $_COOKIE['level'] == 3 or $_COOKIE['level'] == 4)) {
        if ($_COOKIE['level'] == 1 or $_COOKIE['level'] == 2) {
          if ($_COOKIE['level'] == 1) include "menu1.php";
          if ($_COOKIE['level'] == 2) include "menu2.php";
        } else {
          if ($_COOKIE['level'] == 3) {
            include "menu3.php";
          }
          if ($_COOKIE['level'] == 4) {
            include "menu4.php";
          }
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
      <nav class="navbar navbar-expand px-4 py-3" style="background-color: #ff6f61; ">
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
            <h3 class="fw-bold fs-4 mb-3">EDIT USER DOSEN/RESIDEN</h3>
            <br />
            <h2 class="fw-bold fs-4 mb-3 text-center" style="color: #0a3967">
              SILAHKAN UPDATE PROFIL
            </h2>
            <br><br>
            <!-- DISINI -->
            <?php
            if (empty($_POST['cancel']) and empty($_POST['simpan']) and empty($_POST['hapus'])) {
              if ($_COOKIE['level'] == 1 or $_COOKIE['level'] == 2) {
                $data_user = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `admin` WHERE `username`='$_GET[user_name]'"));
              } else {
                $data_user = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `admin` WHERE `username`='$_COOKIE[user]'"));
              }

              $dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_user[username]'"));
              $bagian = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `bagian_ilmu` WHERE `id`='$dosen[kode_bagian]'"));
              $level_user = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `level` WHERE `id`='$data_user[level]'"));
            ?>
              <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <table class="table table-bordered">
                  <thead class="table-primary">
                    <tr style="border-width: 1px; border-color: #000;">
                      <th colspan="2" class="text-center">USER INFORMATION</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr style="border-width: 1px; border-color: #000;" class="table-success">
                      <td><strong>Username</strong></td>
                      <td> <strong>
                          <?php echo $data_user['username']; ?></strong>
                        <br>
                        <?php if ($_COOKIE['level'] == "3" or $_COOKIE['level'] == "4") { ?>
                          <br>
                          <p class="text-danger" style="font-weight: 600;">(Username tidak bisa diubah, NIP/NIDK/NIM/ID dari user.<br>Hubungi admin fakultas untuk melakukan perubahan.)</p>
                          <input type="hidden" name="user_name" value="<?php echo $data_user['username']; ?>" />
                        <?php } ?>
                      </td>
                    </tr>
                    <?php if ($_COOKIE['level'] == "1" or $_COOKIE['level'] == "2") { ?>
                      <tr style="border-width: 1px; border-color: #000;" class="table-primary">
                        <td>
                          Username Baru
                        </td>
                        <td>
                          <input type="hidden" name="user_name_lama" value="<?php echo $data_user['username']; ?>" />
                          <input type="text" value="<?php echo $data_user['username']; ?>" name="user_name" class="form-control">
                          <br>
                          <p class="text-danger" style="font-weight: 600;">(Pastikan untuk menghubungi pemilik akun setelah melakukan perubahan username dan password!)</p>
                        </td>
                      </tr>
                    <?php } ?>
                    <tr style="border-width: 1px; border-color: #000;" class="table-primary">
                      <td>Password Baru</td>
                      <td>
                        <input type="password" id="form-password" value="" name="user_pass" class="form-control">
                        <br><input type="checkbox" id="form-checkbox" style="width: 18px; height: 15px; transform: scale(1.3); ">&nbsp; Show password
                        <br><br>
                        <p class="text-danger" style="font-weight: 600;">(Jika kosong/tidak diisi, password tidak berubah)</p>
                      </td>
                    </tr>
                    <tr style="border-width: 1px; border-color: #000;" class="table-success">
                      <td><strong>Nama Lengkap</strong></td>
                      <td><strong><?php echo $dosen['nama']; ?></strong></td>
                    </tr>
                    <tr style="border-width: 1px; border-color: #000;" class="table-primary">
                      <td>Nama Lengkap Baru</td>
                      <td><input type="text" name="user_surename" value="<?php echo $dosen['nama']; ?>" class="form-control"></td>
                    </tr>
                    <tr style="border-width: 1px; border-color: #000;" class="table-success">
                      <td><strong>Gelar</strong></td>
                      <td><strong><?php echo $dosen['gelar']; ?></strong></td>
                    </tr>
                    <tr style="border-width: 1px; border-color: #000;" class="table-primary">
                      <td>Gelar Baru</td>
                      <td><input type="text" name="user_gelar" value="<?php echo $dosen['gelar']; ?>" class="form-control"></td>
                    </tr>
                    <tr style="border-width: 1px; border-color: #000;" class="table-success">
                      <td><strong>Bagian</strong></td>
                      <td><strong><?php echo $bagian['bagian']; ?></strong></td>
                    </tr>
                    <tr>
                      <td style="border-width: 1px; border-color: #000;" class="table-primary">Bagian Baru</td>
                      <td style="border-width: 1px; border-color: #000;" class="table-primary">
                        <select name="bagian" class="form-select">
                          <option value="<?php echo $bagian['id']; ?>"><?php echo $bagian['id']; ?> - <?php echo $bagian['bagian']; ?></option>
                          <?php
                          $action_bag = mysqli_query($con, "SELECT * FROM `bagian_ilmu` ORDER BY `id` ASC");
                          while ($for_bag = mysqli_fetch_array($action_bag)) {
                            echo "<option value=\"$for_bag[id]\">$for_bag[id] - $for_bag[bagian]</option>";
                          }
                          ?>
                        </select>
                      </td>
                    </tr>
                    <?php if ($_COOKIE['level'] == 1) { ?>
                      <tr class="table-success">
                        <td style="border-width: 1px; border-color: #000;"><strong>Level User</strong></td>
                        <td style="border-width: 1px; border-color: #000;"><strong><?php echo $level_user['level']; ?></strong></td>
                      </tr>
                      <tr class="table-primary">
                        <td style="border-width: 1px; border-color: #000;">Level User Baru</td>
                        <td style="border-width: 1px; border-color: #000;">
                          <select name="user_level" class="form-select">
                            <option value="<?php echo $level_user['id']; ?>"><?php echo $level_user['id']; ?> - <?php echo $level_user['level']; ?></option>
                            <?php
                            $action_level = mysqli_query($con, "SELECT * FROM `level` ORDER BY `id` ASC");
                            while ($for_level = mysqli_fetch_array($action_level)) {
                              echo "<option value=\"$for_level[id]\">$for_level[id] - $for_level[level]</option>";
                            }
                            ?>
                          </select>
                        </td>
                      </tr>
                    <?php } ?>
                    <?php if ($_COOKIE['level'] == 2) { ?>
                      <input type="hidden" name="user_level" value="<?php echo $level_user['id']; ?>" />
                    <?php } ?>
                  </tbody>
                </table>
                <br>
                <div class="button-group">
                  <button type="submit" class="btn btn-danger" name="cancel" value="CANCEL" formnovalidate>
                    <i class="fa-solid fa-circle-xmark me-2"></i>
                    CANCEL
                  </button>
                  <button type="submit" class="btn btn-success" name="simpan" value="SIMPAN">
                    <i class="fa-solid fa-floppy-disk me-2"></i>
                    SIMPAN
                  </button>
                  <?php if ($_COOKIE['level'] == 1) { ?>
                    <button type="submit" class="btn btn-danger" name="hapus" value="HAPUS" formnovalidate>
                      <i class="fa-solid fa-trash me-2"></i>
                      HAPUS
                    </button>
                  <?php } ?>
                </div>
              </form>

              <?php
            }

            if ($_POST['cancel'] == "CANCEL") {
              if ($_COOKIE['level'] == 1 or $_COOKIE['level'] == 2)
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

            if ($_POST['simpan'] == "SIMPAN") {
              $nama = addslashes($_POST['user_surename']);
              if (($_COOKIE['level'] == "1" or $_COOKIE['level'] == "2") and $_POST['user_name'] != $_POST['user_name_lama']) {
                $user_password = MD5($_POST['user_name']);
                $update_admin = mysqli_query($con, "UPDATE `admin`
        SET
        `nama`='$nama',
        `username`='$_POST[user_name]',
        `password`='$user_password'
        WHERE `username`='$_POST[user_name_lama]'");
                $update_dosen = mysqli_query($con, "UPDATE `dosen`
        SET
        `nip`='$_POST[user_name]',
        `nama`='$nama'
        WHERE `nip`='$_POST[user_name_lama]'");
              ?>
            <?php
                $dosen_baru = $_POST['user_name'];
                $dosen_lama = $_POST['user_name_lama'];

                //tabel evaluasi_stase
                $update_evaluasi_stase_dosen1 = mysqli_query($con, "UPDATE `evaluasi_stase` SET `dosen1`='$dosen_baru' WHERE `dosen1`='$dosen_lama'");
                $update_evaluasi_stase_dosen2 = mysqli_query($con, "UPDATE `evaluasi_stase` SET `dosen2`='$dosen_baru' WHERE `dosen2`='$dosen_lama'");
                $update_evaluasi_stase_dosen3 = mysqli_query($con, "UPDATE `evaluasi_stase` SET `dosen3`='$dosen_baru' WHERE `dosen3`='$dosen_lama'");
                //tabel internal_Mxxx
                $stase = mysqli_query($con, "SELECT `id` FROM `kepaniteraan` ORDER BY `id`");
                while ($data_stase = mysqli_fetch_array($stase)) {
                  $internal_id = "internal_" . $data_stase['id'];
                  $update_internal_id_dosen1 = mysqli_query($con, "UPDATE `$internal_id` SET `dosen1`='$dosen_baru' WHERE `dosen1`='$dosen_lama'");
                  $update_internal_id_dosen2 = mysqli_query($con, "UPDATE `$internal_id` SET `dosen2`='$dosen_baru' WHERE `dosen2`='$dosen_lama'");
                  $update_internal_id_dosen3 = mysqli_query($con, "UPDATE `$internal_id` SET `dosen3`='$dosen_baru' WHERE `dosen3`='$dosen_lama'");
                  $update_internal_id_dosen4 = mysqli_query($con, "UPDATE `$internal_id` SET `dosen4`='$dosen_baru' WHERE `dosen4`='$dosen_lama'");
                  $update_internal_id_dosen5 = mysqli_query($con, "UPDATE `$internal_id` SET `dosen5`='$dosen_baru' WHERE `dosen5`='$dosen_lama'");
                  $update_internal_id_dosen6 = mysqli_query($con, "UPDATE `$internal_id` SET `dosen6`='$dosen_baru' WHERE `dosen6`='$dosen_lama'");
                }
                //tabel jurnal_ketrampilan
                $update_jurnal_ketrampilan = mysqli_query($con, "UPDATE `jurnal_ketrampilan` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                //tabel jurnal_penyakit
                $update_jurnal_penyakit = mysqli_query($con, "UPDATE `jurnal_penyakit` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");

                //update nilai bagian
                //M091 - Ilmu Penyakit Dalam
                $update_ipd_nilai_kasus = mysqli_query($con, "UPDATE `ipd_nilai_kasus` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_ipd_nilai_minicex = mysqli_query($con, "UPDATE `ipd_nilai_minicex` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_ipd_nilai_test = mysqli_query($con, "UPDATE `ipd_nilai_test` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_ipd_nilai_ujian = mysqli_query($con, "UPDATE `ipd_nilai_ujian` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                //M092 - Neurologi
                $update_neuro_nilai_cbd = mysqli_query($con, "UPDATE `neuro_nilai_cbd` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_neuro_nilai_jurnal = mysqli_query($con, "UPDATE `neuro_nilai_jurnal` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_neuro_nilai_minicex = mysqli_query($con, "UPDATE `neuro_nilai_minicex` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_neuro_nilai_spv = mysqli_query($con, "UPDATE `neuro_nilai_spv` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_neuro_nilai_test = mysqli_query($con, "UPDATE `neuro_nilai_test` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                //M093 - Ilmu Kesehatan Jiwa
                $update_psikiatri_nilai_cbd = mysqli_query($con, "UPDATE `psikiatri_nilai_cbd` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_psikiatri_nilai_jurnal = mysqli_query($con, "UPDATE `psikiatri_nilai_jurnal` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_psikiatri_nilai_minicex = mysqli_query($con, "UPDATE `psikiatri_nilai_minicex` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_psikiatri_nilai_osce = mysqli_query($con, "UPDATE `psikiatri_nilai_osce` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_psikiatri_nilai_test = mysqli_query($con, "UPDATE `psikiatri_nilai_test` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                //M105 - Ilmu Kesehatan THT-KL
                $update_thtkl_nilai_jurnal = mysqli_query($con, "UPDATE `thtkl_nilai_jurnal` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_thtkl_nilai_osce = mysqli_query($con, "UPDATE `thtkl_nilai_osce` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_thtkl_nilai_presentasi = mysqli_query($con, "UPDATE `thtkl_nilai_presentasi` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_thtkl_nilai_responsi = mysqli_query($con, "UPDATE `thtkl_nilai_responsi` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_thtkl_nilai_test = mysqli_query($con, "UPDATE `thtkl_nilai_test` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                //M095 - IKM-KP
                $update_ikmkp_nilai_komprehensip = mysqli_query($con, "UPDATE `ikmkp_nilai_komprehensip` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_ikmkp_nilai_p2ukm = mysqli_query($con, "UPDATE `ikmkp_nilai_p2ukm` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_ikmkp_nilai_pkbi = mysqli_query($con, "UPDATE `ikmkp_nilai_pkbi` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_ikmkp_nilai_test = mysqli_query($con, "UPDATE `ikmkp_nilai_test` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                //M101 - Ilmu Bedah
                $update_bedah_nilai_mentor = mysqli_query($con, "UPDATE `bedah_nilai_mentor` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_bedah_nilai_test = mysqli_query($con, "UPDATE `bedah_nilai_test` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                //M102 - Anestesi dan Intensive Care
                $update_anestesi_nilai_dops = mysqli_query($con, "UPDATE `anestesi_nilai_dops` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_anestesi_nilai_osce = mysqli_query($con, "UPDATE `anestesi_nilai_osce` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_anestesi_nilai_test = mysqli_query($con, "UPDATE `anestesi_nilai_test` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                //M103 - Radiologi
                $update_radiologi_nilai_cbd = mysqli_query($con, "UPDATE `radiologi_nilai_cbd` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_radiologi_nilai_jurnal = mysqli_query($con, "UPDATE `radiologi_nilai_jurnal` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_radiologi_nilai_test = mysqli_query($con, "UPDATE `radiologi_nilai_test` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                //M104 - Ilmu Kesehatan Mata
                $update_mata_nilai_jurnal = mysqli_query($con, "UPDATE `mata_nilai_jurnal` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_mata_nilai_minicex = mysqli_query($con, "UPDATE `mata_nilai_minicex` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_mata_nilai_penyanggah = mysqli_query($con, "UPDATE `mata_nilai_penyanggah` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_mata_nilai_presentasi = mysqli_query($con, "UPDATE `mata_nilai_presentasi` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_mata_nilai_test = mysqli_query($con, "UPDATE `mata_nilai_test` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                //M094 - IKFR
                $update_ikfr_nilai_kasus = mysqli_query($con, "UPDATE `ikfr_nilai_kasus` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_ikfr_nilai_minicex = mysqli_query($con, "UPDATE `ikfr_nilai_minicex` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_ikfr_nilai_test = mysqli_query($con, "UPDATE `ikfr_nilai_test` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                //M106 - IKGM
                $update_ikgm_nilai_jurnal = mysqli_query($con, "UPDATE `ikgm_nilai_jurnal` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_ikgm_nilai_kasus = mysqli_query($con, "UPDATE `ikgm_nilai_kasus` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_ikgm_nilai_responsi = mysqli_query($con, "UPDATE `ikgm_nilai_responsi` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_ikgm_nilai_test = mysqli_query($con, "UPDATE `ikgm_nilai_test` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                //M111 - Ilmu Kebidanan dan Penyakit Kandungan
                $update_obsgyn_nilai_cbd = mysqli_query($con, "UPDATE `obsgyn_nilai_cbd` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_obsgyn_nilai_jurnal = mysqli_query($con, "UPDATE `obsgyn_nilai_jurnal` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_obsgyn_nilai_minicex = mysqli_query($con, "UPDATE `obsgyn_nilai_minicex` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_obsgyn_nilai_test = mysqli_query($con, "UPDATE `obsgyn_nilai_test` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                //M112 - Kedokteran Forensik dan Medikolegal
                $update_forensik_nilai_jaga = mysqli_query($con, "UPDATE `forensik_nilai_jaga` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_forensik_nilai_referat = mysqli_query($con, "UPDATE `forensik_nilai_referat` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_forensik_nilai_substase = mysqli_query($con, "UPDATE `forensik_nilai_substase` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_forensik_nilai_test = mysqli_query($con, "UPDATE `forensik_nilai_test` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_forensik_nilai_visum = mysqli_query($con, "UPDATE `forensik_nilai_visum` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                //M113 - Ilmu Kesehatan Anak
                $update_ika_nilai_cbd = mysqli_query($con, "UPDATE `ika_nilai_cbd` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_ika_nilai_dops = mysqli_query($con, "UPDATE `ika_nilai_dops` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_ika_nilai_jurnal = mysqli_query($con, "UPDATE `ika_nilai_jurnal` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_ika_nilai_kasus = mysqli_query($con, "UPDATE `ika_nilai_kasus` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_ika_nilai_minicex = mysqli_query($con, "UPDATE `ika_nilai_minicex` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_ika_nilai_minipat = mysqli_query($con, "UPDATE `ika_nilai_minipat` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_ika_nilai_test = mysqli_query($con, "UPDATE `ika_nilai_test` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_ika_nilai_ujian = mysqli_query($con, "UPDATE `ika_nilai_ujian` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                //M114 - Ilmu Kesehatan Kulit dan Kelamin
                $update_kulit_nilai_cbd = mysqli_query($con, "UPDATE `kulit_nilai_cbd` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_kulit_nilai_test = mysqli_query($con, "UPDATE `kulit_nilai_test` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                //M121 - Komprehensip dan Kedokteran Keluarga
                //Komprehensip
                $update_kompre_nilai_cbd = mysqli_query($con, "UPDATE `kompre_nilai_cbd` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_kompre_nilai_laporan = mysqli_query($con, "UPDATE `kompre_nilai_laporan` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_kompre_nilai_presensi = mysqli_query($con, "UPDATE `kompre_nilai_presensi` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_kompre_nilai_sikap = mysqli_query($con, "UPDATE `kompre_nilai_sikap` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                //Kedokteran Keluarga
                $update_kdk_nilai_dops = mysqli_query($con, "UPDATE `kdk_nilai_dops` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_kdk_nilai_kasus = mysqli_query($con, "UPDATE `kdk_nilai_kasus` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_kdk_nilai_minicex = mysqli_query($con, "UPDATE `kdk_nilai_minicex` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_kdk_nilai_presensi = mysqli_query($con, "UPDATE `kdk_nilai_presensi` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
                $update_kdk_nilai_sikap = mysqli_query($con, "UPDATE `kdk_nilai_sikap` SET `dosen`='$dosen_baru' WHERE `dosen`='$dosen_lama'");
              }

              if ($_COOKIE['level'] == 1 or $_COOKIE['level'] == 2) $level = $_POST['user_level'];
              else $level = $_COOKIE['level'];
              if ($_POST['user_pass'] != "") {
                $user_password = MD5($_POST['user_pass']);
                $update_admin = mysqli_query($con, "UPDATE `admin`
        SET
        `nama`='$nama',
        `password`='$user_password',
        `level`='$level'
        WHERE `username`='$_POST[user_name]'");
              } else {
                $update_admin = mysqli_query($con, "UPDATE `admin`
        SET
        `nama`='$nama',
        `level`='$level'
        WHERE `username`='$_POST[user_name]'");
              }

              $update_dosen = mysqli_query($con, "UPDATE `dosen`
        SET
        `nama`='$nama',
        `gelar`='$_POST[user_gelar]',
        `kode_bagian`='$_POST[bagian]'
        WHERE `nip`='$_POST[user_name]'");



              if ($_COOKIE['level'] == 1 or $_COOKIE['level'] == 2)
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

            if ($_POST['hapus'] == "HAPUS") {
              $delete_admin = mysqli_query($con, "DELETE FROM `admin` WHERE `username`='$_POST[user_name]'");
              $delete_dosen = mysqli_query($con, "DELETE FROM `dosen` WHERE `nip`='$_POST[user_name]'");

              //tabel evaluasi_stase
              $update_evaluasi_stase_dosen1 = mysqli_query($con, "UPDATE `evaluasi_stase` SET `dosen1`='' WHERE `dosen1`='$_POST[user_name]'");
              $update_evaluasi_stase_dosen2 = mysqli_query($con, "UPDATE `evaluasi_stase` SET `dosen2`='' WHERE `dosen2`='$_POST[user_name]'");
              $update_evaluasi_stase_dosen3 = mysqli_query($con, "UPDATE `evaluasi_stase` SET `dosen3`='' WHERE `dosen3`='$_POST[user_name]'");
              //tabel internal_Mxxx
              $stase = mysqli_query($con, "SELECT `id` FROM `kepaniteraan` ORDER BY `id`");
              while ($data_stase = mysqli_fetch_array($stase)) {
                $internal_id = "internal_" . $data_stase['id'];
                $update_internal_id_dosen1 = mysqli_query($con, "UPDATE `$internal_id` SET `dosen1`='' WHERE `dosen1`='$_POST[user_name]'");
                $update_internal_id_dosen2 = mysqli_query($con, "UPDATE `$internal_id` SET `dosen2`='' WHERE `dosen2`='$_POST[user_name]'");
                $update_internal_id_dosen3 = mysqli_query($con, "UPDATE `$internal_id` SET `dosen3`='' WHERE `dosen3`='$_POST[user_name]'");
                $update_internal_id_dosen4 = mysqli_query($con, "UPDATE `$internal_id` SET `dosen4`='' WHERE `dosen4`='$_POST[user_name]'");
                $update_internal_id_dosen5 = mysqli_query($con, "UPDATE `$internal_id` SET `dosen5`='' WHERE `dosen5`='$_POST[user_name]'");
                $update_internal_id_dosen6 = mysqli_query($con, "UPDATE `$internal_id` SET `dosen6`='' WHERE `dosen6`='$_POST[user_name]'");
              }

              //tabel jurnal_ketrampilan
              $delete_jurnal_ketrampilan = mysqli_query($con, "DELETE FROM `jurnal_ketrampilan` WHERE `dosen`='$_POST[user_name]'");
              //tabel jurnal_penyakit
              $delete_jurnal_penyakit = mysqli_query($con, "DELETE FROM `jurnal_penyakit` WHERE `dosen`='$_POST[user_name]'");

              //delete nilai bagian
              //M091 - Ilmu Penyakit Dalam
              $delete_ipd_nilai_kasus = mysqli_query($con, "DELETE FROM `ipd_nilai_kasus` WHERE `dosen`='$_POST[user_name]'");
              $delete_ipd_nilai_minicex = mysqli_query($con, "DELETE FROM `ipd_nilai_minicex` WHERE `dosen`='$_POST[user_name]'");
              $delete_ipd_nilai_test = mysqli_query($con, "DELETE FROM `ipd_nilai_test` WHERE `dosen`='$_POST[user_name]'");
              $delete_ipd_nilai_ujian = mysqli_query($con, "DELETE FROM `ipd_nilai_ujian` WHERE `dosen`='$_POST[user_name]'");
              //M092 - Neurologi
              $delete_neuro_nilai_cbd = mysqli_query($con, "DELETE FROM `neuro_nilai_cbd` WHERE `dosen`='$_POST[user_name]'");
              $delete_neuro_nilai_jurnal = mysqli_query($con, "DELETE FROM `neuro_nilai_jurnal` WHERE `dosen`='$_POST[user_name]'");
              $delete_neuro_nilai_minicex = mysqli_query($con, "DELETE FROM `neuro_nilai_minicex` WHERE `dosen`='$_POST[user_name]'");
              $delete_neuro_nilai_spv = mysqli_query($con, "DELETE FROM `neuro_nilai_spv` WHERE `dosen`='$_POST[user_name]'");
              $delete_neuro_nilai_test = mysqli_query($con, "DELETE FROM `neuro_nilai_test` WHERE `dosen`='$_POST[user_name]'");
              //M093 - Ilmu Kesehatan Jiwa
              $delete_psikiatri_nilai_cbd = mysqli_query($con, "DELETE FROM `psikiatri_nilai_cbd` WHERE `dosen`='$_POST[user_name]'");
              $delete_psikiatri_nilai_jurnal = mysqli_query($con, "DELETE FROM `psikiatri_nilai_jurnal` WHERE `dosen`='$_POST[user_name]'");
              $delete_psikiatri_nilai_minicex = mysqli_query($con, "DELETE FROM `psikiatri_nilai_minicex` WHERE `dosen`='$_POST[user_name]'");
              $delete_psikiatri_nilai_osce = mysqli_query($con, "DELETE FROM `psikiatri_nilai_osce` WHERE `dosen`='$_POST[user_name]'");
              $delete_psikiatri_nilai_test = mysqli_query($con, "DELETE FROM `psikiatri_nilai_test` WHERE `dosen`='$_POST[user_name]'");
              //M105 - Ilmu Kesehatan THT-KL
              $delete_thtkl_nilai_jurnal = mysqli_query($con, "DELETE FROM `thtkl_nilai_jurnal` WHERE `dosen`='$_POST[user_name]'");
              $delete_thtkl_nilai_osce = mysqli_query($con, "DELETE FROM `thtkl_nilai_osce` WHERE `dosen`='$_POST[user_name]'");
              $delete_thtkl_nilai_presentasi = mysqli_query($con, "DELETE FROM `thtkl_nilai_presentasi` WHERE `dosen`='$_POST[user_name]'");
              $delete_thtkl_nilai_responsi = mysqli_query($con, "DELETE FROM `thtkl_nilai_responsi` WHERE `dosen`='$_POST[user_name]'");
              $delete_thtkl_nilai_test = mysqli_query($con, "DELETE FROM `thtkl_nilai_test` WHERE `dosen`='$_POST[user_name]'");
              //M095 - IKM-KP
              $delete_ikmkp_nilai_komprehensip = mysqli_query($con, "DELETE FROM `ikmkp_nilai_komprehensip` WHERE `dosen`='$_POST[user_name]'");
              $delete_ikmkp_nilai_p2ukm = mysqli_query($con, "DELETE FROM `ikmkp_nilai_p2ukm` WHERE `dosen`='$_POST[user_name]'");
              $delete_ikmkp_nilai_pkbi = mysqli_query($con, "DELETE FROM `ikmkp_nilai_pkbi` WHERE `dosen`='$_POST[user_name]'");
              $delete_ikmkp_nilai_test = mysqli_query($con, "DELETE FROM `ikmkp_nilai_test` WHERE `dosen`='$_POST[user_name]'");
              //M101 - Ilmu Bedah
              $delete_bedah_nilai_mentor = mysqli_query($con, "DELETE FROM `bedah_nilai_mentor` WHERE `dosen`='$_POST[user_name]'");
              $delete_bedah_nilai_test = mysqli_query($con, "DELETE FROM `bedah_nilai_test` WHERE `dosen`='$_POST[user_name]'");
              //M102 - Anestesi dan Intensive Care
              $delete_anestesi_nilai_dops = mysqli_query($con, "DELETE FROM `anestesi_nilai_dops` WHERE `dosen`='$_POST[user_name]'");
              $delete_anestesi_nilai_osce = mysqli_query($con, "DELETE FROM `anestesi_nilai_osce` WHERE `dosen`='$_POST[user_name]'");
              $delete_anestesi_nilai_test = mysqli_query($con, "DELETE FROM `anestesi_nilai_test` WHERE `dosen`='$_POST[user_name]'");
              //M103 - Radiologi
              $delete_radiologi_nilai_cbd = mysqli_query($con, "DELETE FROM `radiologi_nilai_cbd` WHERE `dosen`='$_POST[user_name]'");
              $delete_radiologi_nilai_jurnal = mysqli_query($con, "DELETE FROM `radiologi_nilai_jurnal` WHERE `dosen`='$_POST[user_name]'");
              $delete_radiologi_nilai_test = mysqli_query($con, "DELETE FROM `radiologi_nilai_test` WHERE `dosen`='$_POST[user_name]'");
              //M104 - Ilmu Kesehatan Mata
              $delete_mata_nilai_jurnal = mysqli_query($con, "DELETE FROM `mata_nilai_jurnal` WHERE `dosen`='$_POST[user_name]'");
              $delete_mata_nilai_minicex = mysqli_query($con, "DELETE FROM `mata_nilai_minicex` WHERE `dosen`='$_POST[user_name]'");
              $delete_mata_nilai_penyanggah = mysqli_query($con, "DELETE FROM `mata_nilai_penyanggah` WHERE `dosen`='$_POST[user_name]'");
              $delete_mata_nilai_presentasi = mysqli_query($con, "DELETE FROM `mata_nilai_presentasi` WHERE `dosen`='$_POST[user_name]'");
              $delete_mata_nilai_test = mysqli_query($con, "DELETE FROM `mata_nilai_test` WHERE `dosen`='$_POST[user_name]'");
              //M094 - IKFR
              $delete_ikfr_nilai_kasus = mysqli_query($con, "DELETE FROM `ikfr_nilai_kasus` WHERE `dosen`='$_POST[user_name]'");
              $delete_ikfr_nilai_minicex = mysqli_query($con, "DELETE FROM `ikfr_nilai_minicex` WHERE `dosen`='$_POST[user_name]'");
              $delete_ikfr_nilai_test = mysqli_query($con, "DELETE FROM `ikfr_nilai_test` WHERE `dosen`='$_POST[user_name]'");
              //M106 - IKGM
              $delete_ikgm_nilai_jurnal = mysqli_query($con, "DELETE FROM `ikgm_nilai_jurnal` WHERE `dosen`='$_POST[user_name]'");
              $delete_ikgm_nilai_kasus = mysqli_query($con, "DELETE FROM `ikgm_nilai_kasus` WHERE `dosen`='$_POST[user_name]'");
              $delete_ikgm_nilai_responsi = mysqli_query($con, "DELETE FROM `ikgm_nilai_responsi` WHERE `dosen`='$_POST[user_name]'");
              $delete_ikgm_nilai_test = mysqli_query($con, "DELETE FROM `ikgm_nilai_test` WHERE `dosen`='$_POST[user_name]'");
              //M111 - Ilmu Kebidanan dan Penyakit Kandungan
              $delete_obsgyn_nilai_cbd = mysqli_query($con, "DELETE FROM `obsgyn_nilai_cbd` WHERE `dosen`='$_POST[user_name]'");
              $delete_obsgyn_nilai_jurnal = mysqli_query($con, "DELETE FROM `obsgyn_nilai_jurnal` WHERE `dosen`='$_POST[user_name]'");
              $delete_obsgyn_nilai_minicex = mysqli_query($con, "DELETE FROM `obsgyn_nilai_minicex` WHERE `dosen`='$_POST[user_name]'");
              $delete_obsgyn_nilai_test = mysqli_query($con, "DELETE FROM `obsgyn_nilai_test` WHERE `dosen`='$_POST[user_name]'");
              //M112 - Kedokteran Forensik dan Medikolegal
              $delete_forensik_nilai_jaga = mysqli_query($con, "DELETE FROM `forensik_nilai_jaga` WHERE `dosen`='$_POST[user_name]'");
              $delete_forensik_nilai_referat = mysqli_query($con, "DELETE FROM `forensik_nilai_referat` WHERE `dosen`='$_POST[user_name]'");
              $delete_forensik_nilai_substase = mysqli_query($con, "DELETE FROM `forensik_nilai_substase` WHERE `dosen`='$_POST[user_name]'");
              $delete_forensik_nilai_test = mysqli_query($con, "DELETE FROM `forensik_nilai_test` WHERE `dosen`='$_POST[user_name]'");
              $delete_forensik_nilai_visum = mysqli_query($con, "DELETE FROM `forensik_nilai_visum` WHERE `dosen`='$_POST[user_name]'");
              //M113 - Ilmu Kesehatan Anak
              $delete_ika_nilai_cbd = mysqli_query($con, "DELETE FROM `ika_nilai_cbd` WHERE `dosen`='$_POST[user_name]'");
              $delete_ika_nilai_dops = mysqli_query($con, "DELETE FROM `ika_nilai_dops` WHERE `dosen`='$_POST[user_name]'");
              $delete_ika_nilai_jurnal = mysqli_query($con, "DELETE FROM `ika_nilai_jurnal` WHERE `dosen`='$_POST[user_name]'");
              $delete_ika_nilai_kasus = mysqli_query($con, "DELETE FROM `ika_nilai_kasus` WHERE `dosen`='$_POST[user_name]'");
              $delete_ika_nilai_minicex = mysqli_query($con, "DELETE FROM `ika_nilai_minicex` WHERE `dosen`='$_POST[user_name]'");
              $delete_ika_nilai_minipat = mysqli_query($con, "DELETE FROM `ika_nilai_minipat` WHERE `dosen`='$_POST[user_name]'");
              $delete_ika_nilai_test = mysqli_query($con, "DELETE FROM `ika_nilai_test` WHERE `dosen`='$_POST[user_name]'");
              $delete_ika_nilai_ujian = mysqli_query($con, "DELETE FROM `ika_nilai_ujian` WHERE `dosen`='$_POST[user_name]'");
              //M114 - Ilmu Kesehatan Kulit dan Kelamin
              $delete_kulit_nilai_cbd = mysqli_query($con, "DELETE FROM `kulit_nilai_cbd` WHERE `dosen`='$_POST[user_name]'");
              $delete_kulit_nilai_test = mysqli_query($con, "DELETE FROM `kulit_nilai_test` WHERE `dosen`='$_POST[user_name]'");
              //M121 - Komprehensip dan Kedokteran Keluarga
              //Komprehensip
              $delete_kompre_nilai_cbd = mysqli_query($con, "DELETE FROM `kompre_nilai_cbd` WHERE `dosen`='$_POST[user_name]'");
              $delete_kompre_nilai_laporan = mysqli_query($con, "DELETE FROM `kompre_nilai_laporan` WHERE `dosen`='$_POST[user_name]'");
              $delete_kompre_nilai_presensi = mysqli_query($con, "DELETE FROM `kompre_nilai_presensi` WHERE `dosen`='$_POST[user_name]'");
              $delete_kompre_nilai_sikap = mysqli_query($con, "DELETE FROM `kompre_nilai_sikap` WHERE `dosen`='$_POST[user_name]'");
              //Kedokteran Keluarga
              $delete_kdk_nilai_dops = mysqli_query($con, "DELETE FROM `kdk_nilai_dops` WHERE `dosen`='$_POST[user_name]'");
              $delete_kdk_nilai_kasus = mysqli_query($con, "DELETE FROM `kdk_nilai_kasus` WHERE `dosen`='$_POST[user_name]'");
              $delete_kdk_nilai_minicex = mysqli_query($con, "DELETE FROM `kdk_nilai_minicex` WHERE `dosen`='$_POST[user_name]'");
              $delete_kdk_nilai_presensi = mysqli_query($con, "DELETE FROM `kdk_nilai_presensi` WHERE `dosen`='$_POST[user_name]'");
              $delete_kdk_nilai_sikap = mysqli_query($con, "DELETE FROM `kdk_nilai_sikap` WHERE `dosen`='$_POST[user_name]'");

              echo "
		<script>
			window.location.href=\"edit_user_dosen.php?status=HAPUS\";
		</script>
		";
            }
            ?>
            <!-- BERAKHIR -->
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
  <script type="text/javascript" src="jquery.min.js"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      $('#form-checkbox').click(function() {
        if ($(this).is(':checked')) {
          $('#form-password').attr('type', 'text');
        } else {
          $('#form-password').attr('type', 'password');
        }
      });
    });
  </script>
</body>

</HTML>