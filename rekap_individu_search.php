<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>On-line Rekap Individu Stase Logbook Koas Pendidikan Dokter FK-UNDIP</title>
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
      if (!empty($_COOKIE['user']) and !empty($_COOKIE['pass']) and ($_COOKIE['level'] == 1 or $_COOKIE['level'] == 2 or $_COOKIE['level'] == 3)) {
        if ($_COOKIE['level'] == 1) {
          include "menu1.php";
        }
        if ($_COOKIE['level'] == 2) {
          include "menu2.php";
        }
        if ($_COOKIE['level'] == 3) {
          include "menu3.php";
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
            <h3 class="fw-bold fs-4 mb-3">REKAP INDIVIDU LOGBOOK</h3>
            <br />
            <h2 class="fw-bold fs-4 mb-3 text-center" style="color: #0a3967">
              REKAP INDIVIDU JURNAL KEPANITERAAN (STASE)
            </h2>
            <br><br>
            <?php
            // Form
            echo '<form method="POST" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '">';
            ?>
            <div class="container mt-5">
              <div class="text-center mb-4">
                <center>
                  <table class="table table-bordered" style="width: auto;">
                    <tr class="table-primary" style="border-width: 1px; border-color: #000;">
                      <td class="align-middle" style="width:200px"><span style="font-size: 17px;"><strong>Cari Mahasiswa: </strong></span></td>
                      <td class="align-middle" style="width: 400px;"><input type="text" class="form-control" name="kunci" id="kunci" style="display: inline-block;" placeholder="masukkan NIM atau nama mahasiswa" /></td>
                    </tr>
                  </table>
                </center>
                <br>
                <button type="submit" class="btn btn-success" name="cari" value="CARI">
                  <i class="fa-solid fa-magnifying-glass me-2"></i>CARI
                </button>
              </div>
              <br>
              <?php
              if (isset($_POST['cari']) && $_POST['cari'] === "CARI") {
                // Secure the input
                $kunci = mysqli_real_escape_string($con, $_POST['kunci']);
                $query = "SELECT * FROM `admin` WHERE `level`='5' AND (`username` LIKE '%$kunci%' OR `nama` LIKE '%$kunci%')";
                $user_kunci = mysqli_query($con, $query);
                $jml = mysqli_num_rows($user_kunci);

                // Check if any results found
                if ($jml >= 1) {
                  echo '<div class="text-center mb-4">';
                  echo '<small class="text-danger" style="font-weight:700">(Klik tombol Username untuk melihat rekap jurnal kepaniteraan/stase per individu)</small>';
                  echo '<p></p>';
                  echo '<table class="table table-bordered" ';
                  echo '<thead>';
                  echo '<tr class="table-success">';
                  echo '<th style="width:5px;text-align:center">No</th>';
                  echo '<th style="width:150px;text-align:center">Username (NIM)</th>';
                  echo '<th style="width:250px;text-align:center">Nama Mahasiswa</th>';
                  echo '</tr>';
                  echo '</thead>';
                  echo '<tbody>';
                  $no = 1;
                  while ($data = mysqli_fetch_array($user_kunci)) {
                    $biodata_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$data[username]'"));
                    echo '<tr class="table-secondary">';
                    echo '<td style="text-align:center;font-weight:700">' . htmlspecialchars($no) . '</td>';
                    echo '<td style="text-align:center"><a href="rekap_individu_admin.php?user_name=' . urlencode($data['username']) . '" class="btn btn-outline-primary">' . htmlspecialchars($data['username']) . '</a></td>';
                    echo '<td style="text-align:center;font-weight:700">' . htmlspecialchars($biodata_mhsw['nama']) . '</td>';
                    echo '</tr>';
                    $no++;
                  }
                  echo '</tbody>';
                  echo '</table>';
                  echo '</div>';
                } else {
                  echo '<div class="text-center mb-4">';
                  echo '<span style="font-size:1.0em; font-weight:700; color:#dc3545">Tidak ada USER dengan kata kunci "<i style="color:blue">' . htmlspecialchars($_POST['kunci']) . '</i>" !</span>';
                  echo '</div>';
                }
              }
              ?>

            </div>
            </form>
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
      <footer class="footer py-3">
        <div class="container-fluid">
          <div class="row text-body-secondary">
            <div class="col-12 col-md-6 text-start mb-3 mb-md-0">
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
            <div class="col-12 col-md-6 text-end text-body-secondary mb-3 mb-md-0">
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
            <div class="col-12 text-center mt-3 mt-md-0" style="color: #0A3967;">
              <a href="https://play.google.com/store/apps/details?id=logbook.koas.logbookkoas&hl=in" target="blank">
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
</body>

</HTML>