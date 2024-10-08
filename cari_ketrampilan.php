<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>On-line Edit Keterampilan Logbook Koas Pendidikan Dokter FK-UNDIP</title>
  <link rel="shortcut icon" type="x-icon" href="images/undipsolid.png">
  <link rel="stylesheet" href="style/style1.css" />
  <link rel="stylesheet" href="style/buttontopup.css">
  <link rel="stylesheet" href="style/informasi.css">

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
      if (!empty($_COOKIE['user']) and !empty($_COOKIE['pass']) and $_COOKIE['level'] == 2) {
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
            <h3 class="fw-bold fs-4 mb-3">CARI DAN EDIT KETRAMPILAN</h3>
            <br>
            <h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">CARI DAN EDIT KETRAMPILAN</h2>
            <br>
            <?php
            echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
            ?>
            <br><br>
            <div class="text-center mb-4">
              <center>
                <table class="table table-bordered" style="width: auto;">
                  <tr class="table-primary" style="border-width: 1px; border-color: #000;">
                    <td class="align-middle" style="width:300px">
                      <span style="font-size: 17px;"><strong>Cari Nama Ketrampilan:</strong></span>
                    </td>
                    <td class="align-middle" style="width: 500px;">
                      <input type="text" class="form-control" name="kunci" style="display: inline-block;" placeholder="ketikkan nama ketrampilan atau id ketrampilan" />
                    </td>
                  </tr>
                </table>
              </center>
              <br><br>
              <button type="submit" class="btn btn-success" name="cari" value="CARI">
                <i class="fa-solid fa-magnifying-glass me-2"></i>CARI
              </button>
              <br><br>
            </div>
            <?php
            echo "</form>";
            ?>
            <?php
            $id = $_GET['id'];

            if ($_POST['cari'] == "CARI") {
              $ketrampilan_kunci = mysqli_query($con, "SELECT * FROM `daftar_ketrampilan` WHERE (`ketrampilan` LIKE '%$_POST[kunci]%' OR `id` LIKE '%$_POST[kunci]%') ORDER BY `ketrampilan`");
              $jml = mysqli_num_rows($ketrampilan_kunci);
              if ($jml >= 1) {
                echo '<div class="text-center mb-4">';
                echo '<small class="text-danger" style="font-weight:700">(Klik ID Keterampilan untuk edit keterampilan)</small>';
                echo '<p></p>';
                echo '<center>';
                echo '<table class="table table-bordered" style="width:60%; ">';
                echo '<thead>';
                echo '<tr class="table-success" style ="border-width: 1px; border-color: #000;">';
                echo '<th style="width:7%; text-align:center">No</th>';
                echo '<th style="width:20%; text-align:center">ID Keterampilan</th>';
                echo '<th style="width:73%; text-align:center">Nama Keterampilan</th>';
                echo '</tr>';
                echo '</thead>';
                $no = 1;
                while ($data = mysqli_fetch_array($ketrampilan_kunci)) {
                  echo '<tr class="table-secondary" style ="border-width: 1px; border-color: #000;">';
                  echo '<td style="text-align:center;font-weight:700;">' . htmlspecialchars($no) . '</td>';
                  echo '<td style="text-align:center"><a href="edit_daftar_ketrampilan.php?id=' . urlencode($data['id']) . '" class="btn btn-outline-primary">' . htmlspecialchars($data['id']) . '</a></td>';
                  echo '<td tyle="text-align:center;font-weight:700;">' . htmlspecialchars($data['ketrampilan']) . '</td>';
                  echo '</tr>';
                  $no++;
                }
                echo '</tbody>';
                echo '</table>';
                echo '<center>';
                echo '</div>';
              } else {
                echo "<center>";
                echo '<div class="text-center mb-4">';
                echo '<span style="font-size:1.0em; color:red; ;font-weight:600;">Tidak ada nama keterampilan dengan kata kunci "<span style="color:blue">' . htmlspecialchars($kunci) . '</span>" !!!</span>';
                echo '</div>';
                echo "</center>";
              }
            }

            if ($_GET['status'] == "HAPUS") {
              echo "<center>";
              echo "<span style=\"color:darkred;font-weight:600;\">Data keterampilan tersebut telah dihapus!!</span>";
              echo "</center>";
            }
            if ($_GET['status'] == "UBAH") {
              echo "<center>";
              echo "<span style=\"color:darkblue;font-weight:600;\">Data keterampilan tersebut telah diedit dan disimpan!!!</span><br><br>";
              echo "</center>";
              $data_ketrampilan = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `daftar_ketrampilan` WHERE `id`='$id'"));
              echo "<center>";
              echo "<table class=\"table table-bordered\" style=\"width:auto;\" >";
              echo "<thead class=\"table-success\"><tr style =\"border-width: 1px; border-color: #000;\"><th colspan=\"2\" class=\"text-center\">DATA PENYAKIT</th></tr></thead>";
              echo "<tbody style =\"border-width: 1px; border-color: #000;\">";
              echo "<tr class=\"table-warning\"style =\"border-width: 1px; border-color: #000;\" >";
              echo "<td style=\"width:30%;\"><strong>Nama Ketrampilan</strong></td>";
              echo "<td style=\"width:50%;font-weight:600;\">$data_ketrampilan[ketrampilan]</td>";
              echo "</tr>";
              echo "<tr class=\"table-warning\" style =\"border-width: 1px; border-color: #000;\">";
              echo "<td><strong>Level SKDI</strong></td>";
              echo "<td style=\"font-weight:600;\">$data_ketrampilan[skdi_level]</td>";
              echo "</tr>";
              echo "<tr class=\"table-warning\" style =\"border-width: 1px; border-color: #000;\">";
              echo "<td><strong>Level Kepmenkes</strong></td>";
              echo "<td style=\"font-weight:600;\">$data_ketrampilan[k_level]</td>";
              echo "</tr>";
              echo "<tr class=\"table-warning\" style =\"border-width: 1px; border-color: #000;\">";
              echo "<td><strong>Level IPSG</strong></td>";
              echo "<td style=\"font-weight:600;\">$data_ketrampilan[ipsg_level]</td>";
              echo "</tr>";
              echo "<tr class=\"table-warning\" style =\"border-width: 1px; border-color: #000;\">";
              echo "<td><strong>Level Muatan Lokal</strong></td>";
              echo "<td style=\"font-weight:600;\">$data_ketrampilan[kml_level]</td>";
              echo "</tr>";
              echo "<thead class=\"table-success\"><tr style =\"border-width: 1px; border-color: #000;\">
              <th colspan=\"2\" class=\"text-center\">KEPANITERAAN (STASE)</th></tr></thead>";
              echo "<tbody>";
              $no = 1;
              $daftar_stase = mysqli_query($con, "SELECT * FROM `kepaniteraan` ORDER BY `id`");
              while ($data = mysqli_fetch_array($daftar_stase)) {
                $nama_cek = "include_" . $data['id'];
                $target = "target_" . $data['id'];
                echo "<tr class=\"table-secondary\" style =\"border-width: 1px; border-color: #000;\">";
                echo "<td style=\" font-weight:600;\">$no. $data[kepaniteraan]</td>";
                if ($data_ketrampilan[$nama_cek] == '1') echo "<td style=\"font-weight:600;\">Included, Target: $data_ketrampilan[$target]</td>";
                else echo "<td style=\"font-weight:600; color:red;\"> - </td>";
                echo "</tr>";
                $no++;
              }
            }
            ?>

          </div>
      </main>
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
</body>

</html>