<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>On-line Informasi Logbook Koas Pendidikan Dokter FK-UNDIP</title>
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

    error_reporting("E_ALL ^ E_NOTICE");

    if (empty($_COOKIE['user']) || empty($_COOKIE['pass'])) {
      echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
    } else {
      if (!empty($_COOKIE['user']) and !empty($_COOKIE['pass'])) {
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
    <!-- Main Content -->
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
            <h3 class="fw-bold fs-4 mb-3">ROTASI KEPANITERAAN (STASE)</h3>
            <br>
            <h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">UPDATE DATA JADWAL KOAS</h2>
            <br>
            <?php
            $data_koas = mysqli_query($con, "SELECT * FROM `update_jadwal_koas_temp`");
            $no = 0;

            // Mulai tabel
            echo '<table border="1" cellpadding="5" cellspacing="0" class="table table-bordered table-striped">';
            echo '<tr align=center><th>No</th><th>NIM</th><th>Stase</th><th>Status</th><th>Pesan</th></tr>';

            while ($data = mysqli_fetch_array($data_koas)) {
              $nim_mhsw = $data['nim'];
              for ($i = 1; $i < 7; $i++) {
                $stase_i = "stase" . $i;
                $mulai_i = "mulai" . $i;
                $selesai_i = "selesai" . $i;
                $stase = $data[$stase_i];
                $tgl_mulai = $data[$mulai_i];
                $tgl_selesai = $data[$selesai_i];
                $stase_id = "stase_" . $stase;

                $rotasi = $i;

                if ($stase != "") {
                  $ada_jadwal = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `$stase_id` WHERE `nim`='$nim_mhsw'"));
                  if ($ada_jadwal > 0) {
                    $update_jadwal = mysqli_query($con, "UPDATE `$stase_id` SET `rotasi`='$rotasi',`tgl_mulai`='$tgl_mulai',`tgl_selesai`='$tgl_selesai',`status`='0',`evaluasi`='0' WHERE `nim`='$nim_mhsw'");
                    $status = "UPDATED";
                  } else {
                    $insert_jadwal = mysqli_query($con, "INSERT INTO `$stase_id` ( `nim`, `rotasi`, `tgl_mulai`, `tgl_selesai`, `status`, `evaluasi`) VALUES ( '$nim_mhsw','$rotasi','$tgl_mulai','$tgl_selesai', '0','0')");
                    $status = "INSERTED";
                  }

                  // Menentukan pesan berdasarkan status
                  if ($insert_jadwal || $update_jadwal) {
                    $pesan = "$nim_mhsw - $stase_id - $status";
                  } else {
                    $pesan = "$nim_mhsw - $stase_id - ERROR";
                  }

                  // Menampilkan hasil dalam tabel
                  echo "<tr align=center><td>{$no}</td><td>$nim_mhsw</td><td>$stase_id</td><td>$status</td><td>$pesan</td></tr>";
                }
              }
              $no++;
            }

            // Menutup tabel
            echo '</table>';
            echo "<div style='text-align: center; color: blue; font-size:0.9em; font-weight: 600;'>
        <br><br>
        << Update data jumlah $no mahasiswa koas >>
        <br><br>
      </div>";
            ?>
            <center>
              <!-- Tambahkan form dengan method POST -->
              <form method="POST">
                <button type="submit" class="btn btn-primary me-3" name="kembali" value="KEMBALI">
                  <i class="fa-solid fa-backward me-2"></i>KEMBALI
                </button>
              </form>
            </center>

            <?php
            // Pastikan $_POST['kembali'] dicek hanya jika form sudah dikirimkan
            if (isset($_POST['kembali']) && $_POST['kembali'] == "KEMBALI") {
              echo "
    <script>
        window.location.href=\"rotasi_tambah_jadwal.php\";
    </script>
    ";
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
</body>

</html>