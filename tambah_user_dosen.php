<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>On-line Tambah User Logbook Koas Pendidikan Dokter FK-UNDIP</title>
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
    set_time_limit(0);

    error_reporting("E_ALL ^ E_NOTICE");

    if (empty($_COOKIE['user']) || empty($_COOKIE['pass'])) {
      echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
    } else {
      if (!empty($_COOKIE['user']) and !empty($_COOKIE['pass']) and $_COOKIE['level'] == 1) {
        if ($_COOKIE['level'] == 1) {
          include "menu1.php";
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
            <h3 class="fw-bold fs-4 mb-3">TAMBAH USER</h3>
            <br />
            <h2 class="fw-bold fs-4 mb-3 text-center" style="color: #0a3967">
              TAMBAH USER DOSEN/RESIDEN
            </h2>
            <br><br>
            <?php
            echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
            ?>
            <div class="container">
              <center>
                <table class="table table-bordered" style="width: 800px;">
                  <tr class="table-primary" style="border-width: 1px; border-color: #000;">
                    <td style="width: 300px;">
                      <strong>Level User</strong>
                    </td>
                    <td style="width: 500px;">
                      <?php
                      echo "<select name=\"user_level\" class=\"form-select\"  style=\"font-size:1em;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" required>";
                      $action_level = mysqli_query($con, "SELECT * FROM `level` ORDER BY `id` ASC");
                      echo "<option value=\"\">< Pilih Level User ></option>";
                      while ($for_level = mysqli_fetch_array($action_level)) {
                        echo "<option value=\"$for_level[id]\">$for_level[id] - $for_level[level]</option>";
                      }
                      echo "</select>";
                      ?>
                    </td>
                  </tr>
                  <tr class="table-success" style="border-width: 1px; border-color: #000;">
                    <td style="width: 300px;">
                      <strong>New username <span class="text-danger">(NIP)</span></strong>
                    </td>
                    <td style="width: 500px;">
                      <?php
                      echo "<input type=\"text\" name=\"user_name\" placeholder=\"Masukkan NIP\" style=\"font-size:1em;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" class=\"form-control\" required>";
                      ?>
                    </td>
                  </tr>
                  <tr class="table-primary" style="border-width: 1px; border-color: #000;">
                    <td style="width: 300px;">
                      <strong>Password</strong>
                    </td>
                    <td style="width: 500px;">
                      <?php
                      echo "<input type=\"password\" placeholder=\"Buat Password\" id=\"form-password\" style=\"font-size:1em;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" name=\"user_pass\" class=\"form-control\" required>";
                      ?>
                      <br>
                      <input type="checkbox" id="form-checkbox" style="width: 18px; height: 15px ;transform: scale(1.0); ">&nbsp; Show password
                    </td>
                  </tr>
                  <tr class="table-success" style="border-width: 1px; border-color: #000;">
                    <td style="width: 300px;">
                      <strong>Nama Lengkap</strong>
                    </td>
                    <td style="width: 500px;">
                      <?php
                      echo "<input type=\"text\" name=\"user_surename\" placeholder=\"Masukkan Nama Lengkap\" style=\"font-size:1em;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" class=\"form-control\" required>";
                      ?>
                    </td>
                  </tr>
                  <tr class="table-primary" style="border-width: 1px; border-color: #000;">
                    <td style="width: 300px;">
                      <strong>Gelar</strong>
                    </td>
                    <td style="width: 500px;">
                      <?php
                      echo "<input type=\"text\" name=\"user_gelar\" placeholder=\"Masukkan Gelar Dosen/Residen\" style=\"font-size:1em;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" class=\"form-control\" required>";
                      ?>
                    </td>
                  </tr>
                  <tr class="table-success" style="border-width: 1px; border-color: #000;">
                    <td style="width: 300px;">
                      <strong>Bagian</strong>
                    </td>
                    <td style="width: 500px;">
                      <?php
                      echo "<select name=\"bagian\" class=\"form-select\" style=\"font-size:1em;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" required>";
                      $action_bag = mysqli_query($con, "SELECT * FROM `bagian_ilmu` ORDER BY `id` ASC");
                      echo "<option value=\"\">< Pilih Bagian Ilmu ></option>";
                      while ($for_bag = mysqli_fetch_array($action_bag)) {
                        echo "<option value=\"$for_bag[id]\">$for_bag[id] - $for_bag[bagian]</option>";
                      }
                      echo "</select>";
                      ?>
                    </td>
                  </tr>
                </table>
                <br>
                <button type="submit" class="btn btn-success" name="submit" value="SUBMIT">
                  <i class="fa-solid fa-user-plus me-2"></i>TAMBAH
                </button>
              </center>
            </div>
            <?php
            echo '</form>';

            if (isset($_POST['submit']) && $_POST['submit'] == "SUBMIT") {
              $jml_username = mysqli_num_rows(mysqli_query($con, "SELECT `username` FROM `admin` WHERE `username`='$_POST[user_name]'"));
              if ($jml_username >= 1) {
                echo "
        <script type=\"text/javascript\">
          alert(\"Username (NIP) tersebut sudah ada dalam data user!!\");
          window.location.href = \"tambah_user.php\";
        </script>";
              } else {
                $user_password = MD5($_POST['user_pass']);
                $nama = addslashes($_POST['user_surename']);
                $tambah_admin = mysqli_query($con, "INSERT INTO `admin` (`nama`, `username`, `password`, `level`) VALUES ('$nama', '$_POST[user_name]', '$user_password', '$_POST[user_level]')");
                $tambah_dosen = mysqli_query($con, "INSERT INTO `dosen` (`nip`, `nama`, `gelar`, `kode_bagian`,`pin`,`qr`) VALUES ('$_POST[user_name]', '$nama', '$_POST[user_gelar]', '$_POST[bagian]', '', '')");
                echo "<center><br><font style=\"color:green; font-weight: 600;\">User baru berhasil ditambahkan!</font>";
              }
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