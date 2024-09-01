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


	<!-- Link Bootstrap -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
	<!-- Link CDN Icon -->
	<link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
	<div class="wrapper">
		<?php

		include "../config.php";
		include "../fungsi.php";

		error_reporting("E_ALL ^ E_NOTICE");

		if (empty($_COOKIE['user']) || empty($_COOKIE['pass'])) {
			echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
		} else {
			if (!empty($_COOKIE['user']) and !empty($_COOKIE['pass']) and ($_COOKIE['level'] == 5 or $_COOKIE['level'] == 4)) {
				if ($_COOKIE['level'] == 5) {
					include "menu5.php";
				}
				if ($_COOKIE['level'] == 4) {
					include "menu4.php";
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
		$foto_path = "../foto/" . $data_mhsw['foto'];
		$default_foto = "../foto/profil_blank.png";

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
						<img src="../images/undipsolid.png" alt="" style="width: 45px;">
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
								<a href="../logout.php" class="dropdown-item">
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
						<h3 class="fw-bold fs-4 mb-3">PENILAIAN KEPANITERAAN (STASE)ILMU KESEHATAN JIWA</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">NILAI UJIAN MINI-CEX - UJIAN KOMPETENSI KLINIK<br>KEPANITERAAN (STASE) ILMU KESEHATAN JIWA</h2>
						<br>
						<?php
						$id_stase = "M093";
						$id = $_GET['id'];
						$data_minicex = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `psikiatri_nilai_minicex` WHERE `id`='$id'"));
						$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_minicex[nim]'"));
						$stase_id = "stase_" . $id_stase;
						$data_stase_mhsw = mysqli_query($con, "SELECT * FROM `$stase_id` WHERE `nim`='$data_minicex[nim]'");
						$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);

						$tanggal_ujian = tanggal_indo($data_minicex['tgl_ujian']);
						$tanggal_approval = tanggal_indo($data_minicex['tgl_approval']);
						$awal_periode = tanggal_indo($data_minicex['tgl_awal']);
						$akhir_periode = tanggal_indo($data_minicex['tgl_akhir']);

						echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
						$tgl_mulai = $_GET['mulai'];
						$tgl_selesai = $_GET['selesai'];
						$approval = $_GET['approval'];
						$mhsw = $_GET['mhsw'];
						echo "<input type=\"hidden\" name=\"tgl_mulai\" value=\"$tgl_mulai\" />";
						echo "<input type=\"hidden\" name=\"tgl_selesai\" value=\"$tgl_selesai\" />";
						echo "<input type=\"hidden\" name=\"approval\" value=\"$approval\" />";
						echo "<input type=\"hidden\" name=\"mhsw\" value=\"$mhsw\" />";
						echo "<center>";
						echo "<table  class=\"table table-bordered\" style=\"width:70%\">";

						//Nama mahasiswa
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td style=\"width:40%\"><strong>Nama Mahasiswa Koas</strong></td>";
						echo "<td style=\"width:60%;font-weight:600; color:darkgreen\">$data_mhsw[nama]</td>";
						echo "</tr>";
						//NIM
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>NIM</strong></td>";
						echo "<td style=\" font-weight:600; color:red\">$data_mhsw[nim]</td>";
						echo "</tr>";
						//Periode Kegiatan
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Periode Kegiatan</strong></td>";
						echo "<td style=\"font-weight:600;\"><span style=\"color:darkblue\">$awal_periode</span> s.d. <span style=\"color:darkblue\">$akhir_periode</span></td>";
						echo "</tr>";
						//Tanggal Ujian
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Tanggal Ujian</strong></td>";
						echo "<td style=\"font-weight:600;\"><span style=\"color:darkblue\">$tanggal_ujian</span></td>";
						echo "</tr>";
						//Dosen Penilai/Penguji
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Dosen Penilai/Penguji</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_minicex[dosen]'"));
						echo "$data_dosen[nama], <span style=\"color:red\">$data_dosen[gelar]</span> (<span style=\"color:blue\">$data_dosen[nip]</span>)";
						echo "</select>";
						echo "</td>";
						echo "</tr>";
						//Tempat/Lokasi
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Tempat / Lokasi</strong></td>";
						echo "<td style=\"font-weight:600;\"><span style=\"color:green\">$data_minicex[lokasi]</span></td>";
						echo "</tr>";
						//Nama Pasien
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Nama Pasien</strong></td>";
						echo "<td style=\"font-weight:600;\">$data_minicex[nama_pasien]</td>";
						echo "</tr>";
						//Nama Pasien
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Umur Pasien</strong></td>";
						echo "<td style=\"font-weight:600;\"><span style=\"color:red\">$data_minicex[umur_pasien]</span> Tahun</td>";
						echo "</tr>";
						//Jenis Kelamin Pasien
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Jenis Kelamin Pasien</strong></td>";
						echo "<td style=\"font-weight:600;\">$data_minicex[jk_pasien]</td>";
						echo "</tr>";
						//Status Kasus
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Status Kasus</strong></td>";
						echo "<td style=\"font-weight:600;\">$data_minicex[status_kasus]</td>";
						echo "</tr>";
						echo "</table><br><br>";

						//Form nilai
						echo "<table border=2 style=\"width:70%;  background:rgba(255, 243, 205, 1);\" >";
						echo "<tr><td style=\"text-align:center; font-size:1.1em;\"><strong >Form Penilaian:</strong></td></tr>";
						echo "</table>";
						echo "<table class=\"table table-bordered\" style=\"width:70%\">";
						echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<th style=\"width:7%;text-align:center;\">No</th>";
						echo "<th style=\"width:78%;text-align:center;\">Aspek Yang Dinilai</th>";
						echo "<th style=\"width:15%;text-align:center;\">Nilai (0-100)</th>";
						echo "</thead>";
						//No A.1
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>A.1</strong></td>";
						echo "<td><strong>Kemampuan wawancara</strong></td>";
						echo "<td align=center class=\"td_mid\"><strong>$data_minicex[aspek_1]</strong></td>";
						echo "</tr>";
						//No A.2
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>A.2</strong></td>";
						echo "<td><strong>Pemeriksaan status mental</strong></td>";
						echo "<td align=center class=\"td_mid\"><strong>$data_minicex[aspek_2]</strong></td>";
						echo "</tr>";
						//No A.3
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>A.3</strong></td>";
						echo "<td><strong>Kemampuanan diagnosis</strong></td>";
						echo "<td align=center class=\"td_mid\"><strong>$data_minicex[aspek_3]</strong></td>";
						echo "</tr>";
						//No A.4
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>A.4</strong></td>";
						echo "<td><strong>Kemampuan terapi</strong></td>";
						echo "<td align=center class=\"td_mid\"><strong>$data_minicex[aspek_4]</strong></td>";
						echo "</tr>";
						//No A.5
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>A.5</strong></td>";
						echo "<td><strong>Kemampuan konseling</strong></td>";
						echo "<td align=center class=\"td_mid\"><strong>$data_minicex[aspek_5]</strong></td>";
						echo "</tr>";
						//No A.6
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>A.6</strong></td>";
						echo "<td><strong>Profesionalisme dan etika</strong></td>";
						echo "<td align=center class=\"td_mid\"><strong>$data_minicex[aspek_6]</strong></td>";
						echo "</tr>";
						//No B
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>B</strong></td>";
						echo "<td><strong>Teori</strong></td>";
						echo "<td align=center class=\"td_mid\"><strong>$data_minicex[aspek_7]</strong></td>";
						echo "</tr>";

						//Rata-Rata Nilai
						if ($data_osce['nilai_rata'] >= 80) {
							$nilai_huruf = "A";
							$warna = "green";
						} elseif ($data_osce['nilai_rata'] >= 70) {
							$nilai_huruf = "B";
							$warna = "blue";
						} else {
							$nilai_huruf = "C";
							$warna = "red";
						}
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=2 align=right class=\"td_mid\"><strong>Rata-Rata Nilai <span class=\"text-danger\">(Nilai Total / 7)</span></strong></td>";
						echo "<td align=center class=\"td_mid\"><strong style=\"color:blue\">$data_minicex[nilai_rata]</strong></td>";
						echo "</tr>";
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=2 align=right class=\"td_mid\"><strong>Konversi Nilai ke Huruf</strong></td>";
						echo "<td align=center class=\"td_mid\"><strong style=\"color: $warna;\">$nilai_huruf</strong></td>";
						echo "</tr>";
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\"><td colspan=3>
						<font style=\"font-size:0.9em; font-weight:700;\">";
						echo "<span>Keterangan:<br>";
						echo "Nilai Batas Lulus (NBL) = <span class=\"text-danger\">70</span><br>";
						echo "Nilai A <span style=\"color:green\">= 80.00 - 100.00 (SUPERIOR)</span><br>";
						echo "Nilai B <span style=\"color:blue\">= 70.00 - 79.99 (LULUS)</span><br>";
						echo "Nilai C <span style=\"color:red\"> < 70.00 (TIDAK LULUS)</span>";
						echo "</font></td></tr>";
						echo "</table><br>";

						//Umpan Balik
						echo "<table class=\"table table-bordered\" style=\"width:70%\">";
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
						<td colspan=2><strong>1. Waktu Penilaian MINI-CEX:</strong></td></tr>";
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td style=\"font-weight:600;\">&nbsp;&nbsp;&nbsp;&nbsp;Observasi</td>";
						echo "<td style=\"font-weight:600;\">";
						echo "$data_minicex[waktu_observasi] Menit";
						echo "</td>";
						echo "</tr>";
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td style=\"font-weight:600;\">&nbsp;&nbsp;&nbsp;&nbsp;Memberikan umpan balik</td>";
						echo "<td style=\"font-weight:600;\">";
						echo "$data_minicex[waktu_ub] Menit";
						echo "</td>";
						echo "</tr>";
						//Kepuasaan penilai terhadap minicex
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=2><strong>2. Kepuasaan penilai terhadap MINI-CEX: <span style=\"color:blue\">$data_minicex[kepuasan1]</span></strong></td>";
						echo "</tr>";
						//Kepuasaan peserta terhadap minicex
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=2><strong>3. Kepuasaan peserta ujian terhadap MINI-CEX: <span style=\"color:blue\">$data_minicex[kepuasan2]</span></strong></td>";
						echo "</tr>";

						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
						<td colspan=2 align=right><br><span style=\"font-weight:600;\">Status:</span> <strong style=\"color:darkgreen;\" >DISETUJUI</strong><br><span style=\"font-weight:600;\">pada tanggal <span style=\"color:darkblue;\">$tanggal_approval</span></span><br>";
						echo "<span style=\"font-weight:600;\">Dosen Penilai/Penguji:<p><span style=\"font-weight:500;\">$data_dosen[nama]</span>, <span style=\"font-weight:500; color:red\">$data_dosen[gelar]</span><br>NIP. <span style=\"font-weight:600; color:blue;\">$data_dosen[nip]</span></span>";
						echo "</td></tr>";
						echo "</table><br>";
						echo "<br>
						<button type=\"submit\" class=\"btn btn-primary\" name=\"back\" value=\"BACK\">
            					<i class=\"fa-solid fa-backward me-2\"></i> BACK
        						</button>";
						echo "<br><br></form>";
						echo "</center>";

						if ($_POST['back'] == "BACK") {
							if ($_COOKIE['level'] == 5)
								echo "
					<script>
					window.location.href=\"penilaian_psikiatri.php\";
					</script>
					";

							if ($_COOKIE['level'] == 4) {
								$tgl_mulai = $_POST['tgl_mulai'];
								$tgl_selesai = $_POST['tgl_selesai'];
								$approval = $_POST['approval'];
								$mhsw = $_POST['mhsw'];
								echo "
				<script>
					window.location.href=\"penilaian_psikiatri_dosen.php?mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\";
				</script>
				";
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

	<script src="../javascript/script1.js"></script>
	<script src="../javascript/buttontopup.js"></script>
	<script src="../jquery.min.js"></script>
</body>

</html>