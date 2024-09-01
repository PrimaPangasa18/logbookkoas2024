<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>On-line Upload Nilai Logbook Koas Pendidikan Dokter FK-UNDIP</title>
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
            <h3 class="fw-bold fs-4 mb-3">IMPORT NILAI MAHASISWA</h3>
            <br>
            <h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">IMPORT NILAI MAHASISWA</h2>
            <br>
            <?php
            $stase_id = $_POST['stase'];
            $jenis_nilai = $_POST['jenis_nilai'];
            $nama_test = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `jenis_test` WHERE `id`='$jenis_nilai'"));
            $status_ujian = $_POST['status_ujian'];
            $nama_status_ujian = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `status_ujian` WHERE `id`='$status_ujian'"));
            $tgl_ujian = $_POST['tgl_ujian'];
            $tanggal_ujian = tanggal_indo($tgl_ujian);
            $tgl_approval = $_POST['tgl_approval'];
            $tanggal_yudisium = tanggal_indo($tgl_approval);

            if (isset($_POST['import']) and !empty($_FILES['import_nilai']['tmp_name'])) {
              $file = $_FILES['import_nilai']['tmp_name'];
              $handle = fopen($file, "r");
              $separator = $_POST['separator'];
              $kepaniteraan = mysqli_fetch_array(mysqli_query($con, "SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$stase_id'"));

              echo "<center><h4>Import Nilai Kepaniteraan/Stase $kepaniteraan[kepaniteraan]</h4><br>";
              echo "<center>";
              echo "<table class=\"table table-bordered\" style=\"width:40%\">";
              echo "<tr  style=\"border-width: 1px; border-color: #000;\" class=\"table-primary\"><td style=\"width:50%\"><strong>Jenis Penilaian</strong></td><td style=\"width:50%; font-weight:600; color:darkblue\">: $nama_test[jenis_test]</td></tr>";
              echo "<tr style=\"border-width: 1px; border-color: #000;\" class=\"table-success\"><td><strong>Status Ujian</strong></td><td style=\"font-weight:600; color:darkred\">: $nama_status_ujian[status_ujian]</td></tr>";
              echo "<tr style=\"border-width: 1px; border-color: #000;\" class=\"table-primary\"><td><strong>Tanggal Ujian</td></strong><td style=\"font-weight:600; color:purple\">: $tanggal_ujian</td></tr>";
              echo "<tr style=\"border-width: 1px; border-color: #000;\" class=\"table-success\"><td><strong>Tanggal Yudisium Nilai</strong></td><td style=\"font-weight:600; color:darkblue\">: $tanggal_yudisium</td></tr>";
              echo "</table><br><br>";
              echo "</center>";


              //Stase yang upload nilai:
              //Stase Neurologi (M092)
              //Stase Ilmu Kesehatan Jiwa (M093)
              //Stase IKM-KP (M095)
              //Stase THT-KL (M105)
              //Stase Anestesi (M102)
              //Stase Radiologi (M103)
              //Stase Ilmu Kesehatan Mata (M104)
              //Stase IKFR (M094)
              //Stase IKGM (M106)
              //Stase Forensik (M112)
              //Stase Kulit dan Kelamin (M114)
              //Stase Ilmu Penyakit Dalam (M091)
              //Stase Ilmu Kebidanan dan Penyakit Kandungan (M111)
              //Stase Ilmu Bedah (M101)
              //Stase Ilmu Kesehatan Anak (M113)

              if (
                $stase_id == "M092" or $stase_id == "M093" or $stase_id == "M095" or $stase_id == "M105" or $stase_id == "M102"
                or $stase_id == "M103" or $stase_id == "M104" or $stase_id == "M094" or $stase_id == "M106" or $stase_id == "M112"
                or $stase_id == "M114" or $stase_id == "M091" or $stase_id == "M111" or $stase_id == "M101" or $stase_id == "M113"
              ) {
                echo "<center>";
                echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze\">";
                echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
                     	<tr>
                    <th style=\"width:5%;text-align:center;\">No</th>
                     <th style=\"width:30%;text-align:center;\">Nama Mahasiswa</th>
                     <th style=\"width:15%;text-align:center;\">NIM</th>
                     <th style=\"width:15%;text-align:center;\">Nilai</th>
                    <th style=\"width:15%;text-align:center;\">Status</th>
                    <th style=\"width:20%;text-align:center;\">Catatan</th>
                    	</tr>
                   </thead>";

                if ($stase_id == "M095") {
                  $db_tabel = "`ikmkp_nilai_test`";
                  $id_kordik = "K095";
                }
                if ($stase_id == "M105") {
                  $db_tabel = "`thtkl_nilai_test`";
                  $id_kordik = "K105";
                }
                if ($stase_id == "M092") {
                  $db_tabel = "`neuro_nilai_test`";
                  $id_kordik = "K092";
                }
                if ($stase_id == "M093") {
                  $db_tabel = "`psikiatri_nilai_test`";
                  $id_kordik = "K093";
                }
                if ($stase_id == "M102") {
                  $db_tabel = "`anestesi_nilai_test`";
                  $id_kordik = "K102";
                }
                if ($stase_id == "M103") {
                  $db_tabel = "`radiologi_nilai_test`";
                  $id_kordik = "K103";
                }
                if ($stase_id == "M104") {
                  $db_tabel = "`mata_nilai_test`";
                  $id_kordik = "K104";
                }
                if ($stase_id == "M094") {
                  $db_tabel = "`ikfr_nilai_test`";
                  $id_kordik = "K094";
                }
                if ($stase_id == "M106") {
                  $db_tabel = "`ikgm_nilai_test`";
                  $id_kordik = "K106";
                }
                if ($stase_id == "M112") {
                  $db_tabel = "`forensik_nilai_test`";
                  $id_kordik = "K112";
                }
                if ($stase_id == "M114") {
                  $db_tabel = "`kulit_nilai_test`";
                  $id_kordik = "K114";
                }
                if ($stase_id == "M091") {
                  $db_tabel = "`ipd_nilai_test`";
                  $id_kordik = "K091";
                }
                if ($stase_id == "M111") {
                  $db_tabel = "`obsgyn_nilai_test`";
                  $id_kordik = "K111";
                }
                if ($stase_id == "M101") {
                  $db_tabel = "`bedah_nilai_test`";
                  $id_kordik = "K101";
                }
                if ($stase_id == "M113") {
                  $db_tabel = "`ika_nilai_test`";
                  $id_kordik = "K113";
                }

                $kelas = "ganjil";
                $header = 1;
                while (($filesop = fgetcsv($handle, 1000, $separator)) !== false) {
                  if ($header > 1) {
                    $no = $filesop[0];
                    $nama = $filesop[1];
                    $nim = $filesop[2];
                    $nilai = number_format($filesop[3], 2);
                    if ($filesop[4] != "") $catatan = addslashes($filesop[4]);
                    else $catatan = "-";
                    $cek_nim = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `admin` WHERE `username`='$nim'"));

                    if ($cek_nim > 0) {
                      $dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `username` FROM `admin` WHERE `stase`='$id_kordik'"));
                      $cek_nim_stase = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM $db_tabel WHERE `nim`='$nim' AND `jenis_test`='$jenis_nilai' AND `status_ujian`='$status_ujian'"));

                      if ($cek_nim_stase > 0) {
                        $nilai_update = mysqli_query($con, "UPDATE $db_tabel
                SET `dosen`='$dosen[username]',`nilai`='$nilai',`catatan`='$catatan',`tgl_test`='$tgl_ujian',`tgl_approval`='$tgl_approval',`status_approval`='1' WHERE `nim`='$nim' AND `jenis_test`='$jenis_nilai' AND `status_ujian`='$status_ujian'");

                        echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
                        echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
                        echo "<td style=\"text-align:center; font-weight:600;\">$nama</td>";
                        echo "<td style=\"text-align:center; font-weight:600; color:darkred;\">$nim</td>";
                        echo "<td style=\"text-align:center; font-weight:600; color:darkgreen;\">$nilai</td>";
                        if (!$nilai_update) echo "<td align=center><span style=\"color=red; font-weight:600;\">ERROR</span></td>";
                        else echo "<td  style=\font-weight:600;\">$nama_status_ujian<br><span style=\"color:green;\">UPDATED</span> </td>";
                        echo "<td style=\"text-align:center;font-weight:600;\"><span>$catatan</span></td>";
                        echo "</tr>";
                      } else {
                        $nilai_insert = mysqli_query($con, "INSERT INTO $db_tabel
                  ( `nim`, `dosen`,
                    `jenis_test`,`status_ujian`,
                    `nilai`, `catatan`,
                    `tgl_test`, `tgl_approval`, `status_approval`)
                  VALUES
                  ( '$nim','$dosen[username]',
                    '$jenis_nilai','$status_ujian',
                    '$nilai','$catatan',
                    '$tgl_ujian','$tgl_approval','1')");

                        echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
                        echo "<td style=\"text-align:center; font-weight:600;\">$no</td>";
                        echo "<td style=\"text-align:center; font-weight:600;\">$nama</td>";
                        echo "<td style=\"text-align:center; font-weight:600; color:darkred;\">$nim</td>";
                        echo "<td style=\"text-align:center; font-weight:600; color:darkgreen;\">$nilai</td>";
                        if (!$nilai_insert) echo "<td align=center><span style=\"color=red; font-weight:600;\">ERROR</span></td>";
                        else echo "<td style=\font-weight:600;\">$nama_status_ujian[status_ujian]<br> <span style=\"color:green;\">ISSUED</span> </td>";
                        echo "<td style=\"text-align:center;font-weight:600;\">$catatan</td>";
                        echo "</tr>";
                      }
                    } else {
                      echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">
                      <td style=\"text-align:center;font-weight:600;\">$no</td>
                      <td colspan=5><span style=\"color:red;font-weight:600;\">Data <span style=\"color:black;\">$nama</span> (NIM: <span style=\"color:blue;\">$nim</span>) tidak dikenali pada database sistem E-Logbook Koas. Silakan cek NIM-nya!!!</span></td></tr>";
                    }
                    if ($kelas == "ganjil") $kelas = "genap";
                    else $kelas = "ganjil";
                  } else {
                    $header = 2;
                  }
                }
                echo "</table>";
                echo "</center>";
              }
              //Stase yang tidak upload nilai
              else {
                echo "<center>";
                echo "<font style=\"color:red;font-weight:600;\">Catatan: Untuk Kepaniteraan/Stase ini tidak ada kegiatan <span style=\"color:blue;\">" . $nama_test['jenis_test'] . "</span>!!</font>";
                echo "</center>";
              }
            } else {
              echo "<center>";
              echo "<font style=\"color:red;font-weight:600;\">Maaf! Ada kesalahan input file!!!</font>";
              echo "</center>";
            }
            echo "<center>";
            echo "<br><form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">
      <button type=\"submit\" class=\"btn btn-primary me-2\" name=\"back\" value=\"BACK\">
        <i class=\"fa-solid fa-delete-left\"></i> BACK
      </button>
      </form>";
            echo "</center>";

            if ($_POST['back'] == "BACK")
              echo "
		<script>
			window.location.href=\"upload_nilai.php\";
		</script>
		";


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
  <script type="text/javascript" src="../freezeheader/js/jquery.freezeheader.js"></script>
  <script>
    $(document).ready(function() {
      $("#freeze").freezeHeader();
    });
  </script>

</body>

</html>