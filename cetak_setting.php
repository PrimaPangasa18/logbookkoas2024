<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>On-line Cetak Rekap Jurnal Logbook Koas Pendidikan Dokter FK-UNDIP</title>
  <link rel="shortcut icon" type="x-icon" href="images/undipsolid.png">
  <link rel="stylesheet" href="style/style1.css" />
  <link rel="stylesheet" href="style/buttontopup.css">
  <link rel="stylesheet" href="select2/dist/css/select2.css" />
  <link rel="stylesheet" type="text/css" href="jquery_ui/jquery-ui.css">

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
      if (!empty($_COOKIE['user']) and !empty($_COOKIE['pass']) and ($_COOKIE['level'] == 1 or $_COOKIE['level'] == 2 or $_COOKIE['level'] == 3 or $_COOKIE['level'] == 5)) {
        if ($_COOKIE['level'] == 1) {
          include "menu1.php";
        }
        if ($_COOKIE['level'] == 2) {
          include "menu2.php";
        }
        if ($_COOKIE['level'] == 3) {
          include "menu3.php";
        }
        if ($_COOKIE['level'] == 5) {
          include "menu5.php";
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
            <h3 class="fw-bold fs-4 mb-3">
              <?php
              if ($_COOKIE['level'] == 5)
                echo "REKAP LOGBOOK";
              else
                echo "REKAP INDIVIDU LOGBOOK";
              ?>
            </h3>
            <br>
            <h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">
              <?php
              if ($_COOKIE['level'] == 5)
                echo "REKAP LOGBOOK KEPANITERAAN (STASE)";
              else
                echo "REKAP INDIVIDU JURNAL KEPANITERAAN (STASE)";
              ?>
            </h2>
            <br>
            <?php
            if ($_COOKIE['level'] == 5) $mhsw_nim = $_COOKIE['user'];
            else $mhsw_nim = $_GET['nim'];
            $jenis = $_GET['jenis'];
            $cetak = $_GET['cetak'];
            $id_stase = $_GET['id'];
            $data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$mhsw_nim'"));

            echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
            echo "<input type=\"hidden\" name=\"mhsw_nim\" value=\"$mhsw_nim\" />";
            echo "<input type=\"hidden\" name=\"jenis\" value=\"$jenis\" />";
            echo "<input type=\"hidden\" name=\"cetak\" value=\"$cetak\" />";
            echo "<input type=\"hidden\" name=\"id_stase\" value=\"$id_stase\" />";

            echo "<center>";
            echo "<div class=\"table-responsive\">";
            echo "<strong style=\"color:blue\">SETTING CETAK LOGBOOK</strong><br><br>";
            echo "<table  class=\"table table-bordered\" style=\"width:70%\">";
            //Jumlah baris/halaman
            echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
            echo "<td class=\"td_mid\" style=\"width:40%\"><strong>Jumlah baris/halaman</strong></td>";
            echo "<td style=\"font-weight:600; width:60%\">";
            echo "<select class=\"form-select\" style=\"font-size:1em;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" name=\"baris_cetak\" id=\"baris_cetak\" required>";
            echo "<option value=\"50\">50 baris (maksimal)</option>";
            for ($i = 0; $i <= 25; $i++) {
              $value = 25 + $i;
              echo "<option value=\"$value\">$value baris</option>";
            }
            echo "</select>";
            echo "</td>";
            echo "</tr>";
            //Dosen wali
            echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
            echo "<td class=\"td_mid\">";
            echo "<strong>Dosen Wali</strong>";
            echo "</td>";
            echo "<td style=\"font-weight:600;\">";
            $dosen_wali = mysqli_fetch_array(mysqli_query($con, "SELECT `nama`,`gelar` FROM `dosen` WHERE `nip`='$data_mhsw[dosen_wali]'"));
            echo "<select class=\"form-select\" style=\"font-size:1em;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" name=\"dosen_wali\" id=\"dosen_wali\" required>";
            $dosen = mysqli_query($con, "SELECT `username`,`nama` FROM `admin` WHERE `level`='4' ORDER BY `nama`");
            if ($data_mhsw['dosen_wali'] == "")
              echo "<option value=\"\">< Pilihan Dosen Wali ></option>";
            else
              echo "<option value=\"$data_mhsw[dosen_wali]\">$dosen_wali[nama], $dosen_wali[gelar] (NIP. $data_mhsw[dosen_wali])</option>";
            while ($dat9 = mysqli_fetch_array($dosen)) {
              $data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$dat9[username]'"));
              echo "<option value=\"$dat9[username]\">$data_dosen[nama], $data_dosen[gelar] (NIP. $data_dosen[nip])</option>";
            }
            echo "</select>";
            echo "</td>";
            echo "</tr>";
            //Tanggal cetak
            echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
            echo "<td>";
            echo "<strong>Tanggal Cetak <span class=\"text-danger\">(yyyy-mm-dd)</span></strong>";
            echo "</td>";
            echo "<td>";
            echo "<input type=\"text\" id=\"input-tanggal\" name=\"tgl_cetak\" class=\"form-select\" style=\"font-size:1em;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" value=\"$tgl\" />";
            echo "</td>";
            echo "</tr>";
            echo "</table>";
            echo "</div>";

            echo "<br><br>
						<button type=\"submit\" class=\"btn btn-primary\"  name=\"print\" value=\"CETAK PDF\" formnovalidate>
            					<i class=\"fas fa-print me-2\"></i> CETAK PDF
        						</button>";
            echo "</form><br><br>";
            echo "</center>";

            if ($_POST['print'] == "CETAK PDF") {
              $update_dosenwali = mysqli_query($con, "UPDATE `biodata_mhsw` SET `dosen_wali`='$_POST[dosen_wali]' WHERE `nim`='$_POST[mhsw_nim]'");
              echo "
    		<script>
    			window.location.href=\"cetak_rekap_indstase.php?jenis=\"+\"$_POST[jenis]\"+\"&cetak=\"+\"$_POST[cetak]\"+\"&id=\"+\"$_POST[id_stase]\"+\"&nim=\"+\"$_POST[mhsw_nim]\"+\"&baris_cetak=\"+\"$_POST[baris_cetak]\"+\"&tgl_cetak=\"+\"$_POST[tgl_cetak]\";
    		</script>
    		";
            }

            echo "<br><br></fieldset>";
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
  <script src="select2/dist/js/select2.js"></script>
  <script type="text/javascript" src="jquery_ui/jquery-ui.js"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      $("#baris_cetak").select2({
        placeholder: "< Pilihan Baris/Halaman >",
        allowClear: true
      });
      $("#dosen_wali").select2({
        placeholder: "< Pilihan Dosen Wali >",
        allowClear: true
      });
      $('#input-tanggal').datepicker({
        dateFormat: 'yy-mm-dd'
      });
    });
  </script>
</body>

</html>