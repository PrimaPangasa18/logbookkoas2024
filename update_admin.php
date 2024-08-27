<!DOCTYPE html>
<html lang="en">

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
    set_time_limit(0);

    error_reporting("E_ALL ^ E_NOTICE");

    if (empty($_COOKIE['user']) || empty($_COOKIE['pass'])) {
      echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
    } else {
      if (!empty($_COOKIE['user']) and !empty($_COOKIE['pass']) and ($_COOKIE['level'] == 1 or $_COOKIE['level'] == 2)) {
        if ($_COOKIE['level'] == 1) {
          include "menu1.php";
        }
        if ($_COOKIE['level'] == 2) {
          include "menu2.php";
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
              <span class="navbar-text me-2">Halo, <?php echo $nama . ' , <span class="gelar" style="color:red">' . $gelar . '</span>'; ?></span>
              <a href="#" class="nav-icon pe-md-0" data-bs-toggle="dropdown">
                <img src="<?php echo $foto_path; ?>" class="avatar img-fluid rounded-circle" alt="" />
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
            <h3 class="fw-bold fs-4 mb-3">UPDATE PROFIL</h3>
            <br />
            <h2 class="fw-bold fs-4 mb-3 text-center" style="color: #0a3967">
              SILAHKAN UPDATE PROFIL
            </h2>
            <br><br>

            <!-- DISINI -->
            <?php
            if (empty($_POST['cancel']) and empty($_POST['simpan']) and empty($_POST['hapus'])) {
              $data_user = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `admin` WHERE `username`='$_COOKIE[user]'"));
              $dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data_user[username]'"));
              $bagian = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `bagian_ilmu` WHERE `id`='$dosen[kode_bagian]'"));
              $level_user = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `level` WHERE `id`='$data_user[level]'"));
            }
            ?>
            <div class="table-container">
              <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>">
                <table class="table table-bordered ">
                  <tr class="table-primary" style="border-width: 1px; border-color: #000;">
                    <td><strong>Username</strong></td>
                    <td>
                      <strong><?= $data_user['username'] ?><br></strong>
                      <p class="text-danger" style="font-weight: 600;"> Note: (Username tidak bisa diubah, NIP/NIDK/NIM/ID dari user)</p>
                      <input type="hidden" name="user_name" value="<?= $data_user['username'] ?>" />
                    </td>
                  </tr>
                  <tr class="table-success" style="border-width: 1px; border-color: #000;">
                    <td>Password Baru</td>
                    <td>
                      <input type="password" id="form-password" name="user_pass" class="form-control">
                      <br>
                      <label for="form-checkbox">
                        <input type="checkbox" id="form-checkbox" style="width: 18px; height: 15px ;transform: scale(1.3); ">&nbsp; Show password
                      </label>
                      <br><br>
                      <p class="text-danger" style="font-weight: 600;">Note: (Jika kosong/tidak diisi, password tidak berubah)</p>
                    </td>
                  </tr>
                  <tr class="table-primary" style="border-width: 1px; border-color: #000;">
                    <td><strong>Nama Lengkap</strong></td>
                    <td><strong><?= $dosen['nama'] ?></strong></td>
                  </tr>
                  <tr class="table-success" style="border-width: 1px; border-color: #000;">
                    <td>Nama Lengkap Baru</td>
                    <td><input type="text" name="user_surename" value="<?= $dosen['nama'] ?>" class="form-control"></td>
                  </tr>
                  <tr class="table-primary" style="border-width: 1px; border-color: #000;">
                    <td><strong>Gelar</strong></td>
                    <td><strong><?= $dosen['gelar'] ?></strong></td>
                  </tr>
                  <tr class="table-success" style="border-width: 1px; border-color: #000;">
                    <td>Gelar Baru</td>
                    <td><input type="text" name="user_gelar" value="<?= $dosen['gelar'] ?>" class="form-control"></td>
                  </tr>
                  <tr class="table-primary" style="border-width: 1px; border-color: #000;">
                    <td><strong>Bagian</strong></td>
                    <td><strong><?= $bagian['bagian'] ?></strong></td>
                  </tr>
                  <tr class="table-success" style="border-width: 1px; border-color: #000;">
                    <td>Bagian Baru</td>
                    <td>
                      <select name="bagian" class="form-select">
                        <option value="<?= $bagian['id'] ?>"><?= $bagian['id'] ?> - <?= $bagian['bagian'] ?></option>
                        <?php
                        $action_bag = mysqli_query($con, "SELECT * FROM `bagian_ilmu` ORDER BY `id` ASC");
                        while ($for_bag = mysqli_fetch_array($action_bag)) {
                          echo "<option value=\"$for_bag[id]\">$for_bag[id] - $for_bag[bagian]</option>";
                        }
                        ?>
                      </select>
                    </td>
                  </tr>
                </table>
                <br><br>
                <div class="button-group">
                  <button type="submit" class="btn btn-danger" name="cancel" value="CANCEL">
                    <i class="fa-solid fa-circle-xmark me-2"></i>
                    CANCEL
                  </button>
                  <button type="submit" class="btn btn-success" name="simpan" value="SIMPAN">
                    <i class="fa-solid fa-floppy-disk me-2"></i>
                    SIMPAN
                  </button>
                </div>

              </form>
            </div>
            <?php
            if ($_POST['cancel'] == "CANCEL") {
              echo "<script>window.location.href='profil_dosen.php';</script>";
            }

            if ($_POST['simpan'] == "SIMPAN") {
              if ($_POST['user_pass'] != "") {
                $user_password = MD5($_POST['user_pass']);
                $update_admin = mysqli_query($con, "UPDATE admin SET nama='$_POST[user_surename]', password='$user_password', level='$_COOKIE[level]' WHERE username='$_POST[user_name]'");
              } else {
                $update_admin = mysqli_query($con, "UPDATE admin SET nama='$_POST[user_surename]', level='$_COOKIE[level]' WHERE username='$_POST[user_name]'");
              }

              $update_dosen = mysqli_query($con, "UPDATE dosen SET nama='$_POST[user_surename]', gelar='$_POST[user_gelar]', kode_bagian='$_POST[bagian]' WHERE nip='$_POST[user_name]'");
              echo "<script>window.location.href='profil_dosen.php';</script>";
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