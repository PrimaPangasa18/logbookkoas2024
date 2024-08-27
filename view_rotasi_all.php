<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>On-line Rotasi Angkatan Logbook Koas Pendidikan Dokter FK-UNDIP</title>
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
            <br />
            <h2 class="fw-bold fs-4 mb-3 text-center" style="color: #0a3967">
              ROTASI ANGKATAN
            </h2>
            <br><br>
            <?php

            $angkatan = $_GET['angk'];
            if ($angkatan == "all") {
              $mhsw = mysqli_query($con, "SELECT `nim`,`nama` FROM `biodata_mhsw` ORDER BY `nama`");
              $angkatan = "Semua Angkatan";
            } else
              $mhsw = mysqli_query($con, "SELECT `nim`,`nama` FROM `biodata_mhsw` WHERE `angkatan`='$angkatan' ORDER BY `nama`");
            $jml_mhsw = mysqli_num_rows($mhsw);
            ?>
            <center>
              <table class="table table-bordered" style="width:auto">
                <tr class="table-primary" style="border-width: 1px; border-color: #000;">
                  <td style="width:200px"><strong>Angkatan</strong></td>
                  <td style="width:300px; color:purple; font-weight:600;">&nbsp; <?= $angkatan ?></td>
                </tr>
                <tr class="table-success" style="border-width: 1px; border-color: #000;">
                  <td><strong>Jumlah Mahasiswa</strong></td>
                  <td style="color:darkgreen; font-weight:600;">&nbsp; <?= $jml_mhsw ?> Mahasiswa/i</td>
                </tr>
              </table>
            </center>
            <br>
            <div class="table-responsive">
              <table class="table table-bordered" style="width:100%;" id="freeze">
                <thead>
                  <tr>
                    <td colspan="20" align="center">
                      <!-- Keterangan -->
                      <div class="alert alert-warning" role="alert">
                        <span style="font-size: 1.2em; font-family:'Poppins' sans-serif ; font-weight:600">Keterangan:</span>
                        <br><br>
                        <div class="container-fluid">
                          <div class="table-responsive">
                            <table class="table table-borderless table-warning">
                              <tbody>
                                <tr>
                                  <td><b>M091</b>: <span class="text-success" style="font-weight: 500;">Ilmu Penyakit Dalam</span></td>
                                  <td><b>M101</b>: <span class="text-success" style="font-weight: 500;">Ilmu Bedah</span></td>
                                  <td><b>M111</b>: <span class="text-success" style="font-weight: 500;">Ilmu Kebidanan dan Penyakit Kandungan</span></td>
                                  <td><b>M121</b>: <span class="text-success" style="font-weight: 500;">Komprehensip dan Kedokteran Keluarga</span></td>
                                </tr>
                                <tr>
                                  <td><b>M092</b>: <span style="font-weight: 500; color:red">Neurologi</span></td>
                                  <td><b>M102</b>: <span style="font-weight: 500; color:red">Anestesi dan Intensive Care</span></td>
                                  <td><b>M112</b>: <span style="font-weight: 500; color:red">Kedokteran Forensik dan Medikolegal</span></td>
                                  <td><b>M093</b>: <span style="font-weight: 500; color:red">Ilmu Kesehatan Jiwa</span></td>
                                </tr>
                                <tr>
                                  <td><b>M103</b>: <span style="font-weight: 500; color:purple">Radiologi</span></td>
                                  <td><b>M113</b>: <span style="font-weight: 500; color:purple">Ilmu Kesehatan Anak</span></td>
                                  <td><b>M094</b>: <span style="font-weight: 500; color:purple">IKFR</span></td>
                                  <td><b>M104</b>: <span style="font-weight: 500; color:purple">Ilmu Kesehatan Mata</span></td>
                                </tr>
                                <tr>
                                  <td><b>M114</b>: <span style="font-weight: 500; color:darkblue">Ilmu Kesehatan Kulit dan Kelamin</span></td>
                                  <td><b>M095</b>: <span style="font-weight: 500; color:darkblue">IKM-KP</span></td>
                                  <td><b>M105</b>: <span style="font-weight: 500; color:darkblue">Ilmu Kesehatan THT-KL</span></td>
                                  <td><b>M096</b>: <span style="font-weight: 500; color:darkblue">Ilmu Jantung dan Pembuluh Darah</span></td>
                                </tr>
                                <tr>
                                  <td colspan="4"><b>M106</b>: <span style="font-weight: 500; color:brown">IKGM</span></td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                      <!-- End Keterangan -->
                    </td>
                  </tr>
                  <tr class="table-primary">
                    <th style="width:3%;border-width: 1px; border-color: #000;">No</th>
                    <th style="width:12%;border-width: 1px; border-color: #000;">Nama (NIM)</th>
                    <?php
                    $stase1 = mysqli_query($con, "SELECT * FROM `kepaniteraan` ORDER BY `id`");
                    while ($data_stase1 = mysqli_fetch_array($stase1)) {
                      echo "<th style=\"width:5%;border-width: 1px; border-color: #000;\">{$data_stase1['id']}</th>";
                    }
                    ?>
                  </tr>
                </thead>
                <tbody style=" border-width: 1px; border-color: #000;">
                  <?php
                  $no = 1;
                  $kelas = 'ganjil';
                  while ($data_mhsw = mysqli_fetch_array($mhsw)) {
                    echo "<tr class=\"$kelas;\">";
                    echo "<td style='text-align: center;font-weight: 500;background-color:rgba(123, 205, 91, 0.2)'>$no</td>";
                    echo "<td style='font-weight: 500; background-color:rgba(123, 205, 91, 0.2)'>{$data_mhsw['nama']}<br><span style='color: red;'>{$data_mhsw['nim']}</span></td>";

                    $stase2 = mysqli_query($con, "SELECT * FROM `kepaniteraan` ORDER BY `id`");
                    while ($data_stase2 = mysqli_fetch_array($stase2)) {
                      $stase_id = "stase_" . $data_stase2['id'];
                      $jml_stase_mhsw = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `$stase_id` WHERE `nim`='{$data_mhsw['nim']}'"));
                      $stase_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `$stase_id` WHERE `nim`='{$data_mhsw['nim']}'"));
                      if ($jml_stase_mhsw >= 1) {
                        $tglmulai = tanggal_indo_skt($stase_mhsw['tgl_mulai']);
                        $tglselesai = tanggal_indo_skt($stase_mhsw['tgl_selesai']);
                        echo "<td align=\"center\" style=\"padding:5px 0;background-color:rgba(123, 205, 91, 0.2)\"><span style=\"font-size:0.7em; font-weight:500; color:blue;\">$tglmulai</span><br><span style=\"font-size:0.7em; font-weight:500; \">sd.</span><br><span style=\"font-size:0.7em; font-weight:500; color:red;\">$tglselesai</span></td>";
                      } else {
                        echo "<td style=\"background-color:rgba(252, 15, 0, 0.2)\">&nbsp;</td>";
                      }
                    }
                    echo "</tr>";
                    $kelas = ($kelas == 'ganjil') ? 'genap' : 'ganjil';
                    $no++;
                  }
                  ?>
                </tbody>
              </table>
            </div>
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
  <script type="text/javascript" src="freezeheader/js/jquery.freezeheader.js"></script>
  <script>
    $(document).ready(function() {
      $("#freeze").freezeHeader();
    });
  </script>

</body>

</HTML>