<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Rekap Umum Evaluasi Stase Logbook Koas Pendidikan Dokter FK-UNDIP</title>
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
						<h3 class="fw-bold fs-4 mb-3">REKAP EVALUASI AKHIR STASE - UMUM</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">REKAP EVALUASI AKHIR STASE - UMUM<br>EVALUASI DOSEN</h2>
						<br>
						<?php
						$id_stase = $_GET['stase'];
						$angkatan_filter = $_GET['angk'];
						$stase_id = "stase_" . $id_stase;
						$include_id = "include_" . $id_stase;
						$target_id = "target_" . $id_stase;
						$tgl_awal = $_GET['tglawal'];
						$tgl_akhir = $_GET['tglakhir'];

						$filterstase = "`stase`=" . "'$id_stase'";
						$filtertgl = " AND (`tanggal`>=" . "'$tgl_awal'" . " AND `tanggal`<=" . "'$tgl_akhir')";
						$filter = $filterstase . $filtertgl;

						$mhsw = mysqli_query($con, "SELECT `nim` FROM `$stase_id` WHERE `tgl_mulai`>='$tgl_awal' AND `tgl_selesai`<='$tgl_akhir' ORDER BY `nim`");
						$jml_mhsw = mysqli_num_rows($mhsw);
						$stase = mysqli_fetch_array(mysqli_query($con, "SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$id_stase'"));

						//--------------------
						echo "<center>";
						echo "<table class=\"table table-bordered\" style=\"width:50%\">";
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td style=\"width:30%\"><strong>Kepaniteraan (STASE)</strong></td>
						<td style=\"width:70%; font-weight:600; color:darkgreen\">: $stase[kepaniteraan]</td>";
						echo "</tr>";
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td> <strong>Angkatan </strong></td>";
						if ($angkatan_filter == "all") echo "<td style=\"color:darkred; font-weight:600;\">: Semua Angkatan</td>";
						else echo "<td style=\"color:darkred; font-weight:600;\">: Angkatan $angkatan_filter</td>";
						echo "</tr>";
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td> <strong>Jumlah Mahasiswa </strong></td>
						<td style=\"font-weight:600;\">: <span class=\"text-danger\">$jml_mhsw</span> Orang</td>";
						echo "</tr>";
						$tglawal = tanggal_indo($tgl_awal);
						$tglakhir = tanggal_indo($tgl_akhir);
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Periode Kegiatan</strong></td>
						<td style=\"font-weight:600;\">: <span style=\"color:darkblue\">$tglawal</span> s.d <span style=\"color:darkblue\">$tglakhir</span></td>";
						echo "</tr>";
						echo "</table><br><br>";
						//------------------
						$delete_dummy = mysqli_query($con, "DELETE FROM `dummy_evaluasi_dosen` WHERE `username`='$_COOKIE[user]'");

						while ($nim_mhsw = mysqli_fetch_array($mhsw)) {
							$data_dosen = mysqli_query($con, "SELECT `dosen1`,`tatap_muka1`,`dosen2`,`tatap_muka2`,`dosen3`,`tatap_muka3` FROM `evaluasi_stase` WHERE `nim`='$nim_mhsw[nim]' AND `stase`='$id_stase'");
							$nip_dosen = mysqli_fetch_array($data_dosen);
							$jml_data_evaluasi = mysqli_num_rows($data_dosen);
							if ($jml_data_evaluasi > 0) {
								$jml_dosen1 = mysqli_num_rows(mysqli_query($con, "SELECT `nip` FROM `dummy_evaluasi_dosen` WHERE `nip`='$nip_dosen[dosen1]' AND `username`='$_COOKIE[user]'"));
								if ($jml_dosen1 < 1) {
									$insert_dosen = mysqli_query($con, "INSERT INTO `dummy_evaluasi_dosen`
							(`nip`, `jml_review`,`jml_jam`,`username`)
							VALUES
							('$nip_dosen[dosen1]','1','$nip_dosen[tatap_muka1]','$_COOKIE[user]')");
								} else {
									$jml_review_asal = mysqli_fetch_array(mysqli_query($con, "SELECT `jml_review` FROM `dummy_evaluasi_dosen` WHERE `nip`='$nip_dosen[dosen1]' AND `username`='$_COOKIE[user]'"));
									$jml_jam_asal = mysqli_fetch_array(mysqli_query($con, "SELECT `jml_jam` FROM `dummy_evaluasi_dosen` WHERE `nip`='$nip_dosen[dosen1]' AND `username`='$_COOKIE[user]'"));
									$jml_review = $jml_review_asal['jml_review'] + 1;
									$jml_jam = $jml_jam_asal['jml_jam'] + $nip_dosen['tatap_muka1'];
									$update_dosen = mysqli_query($con, "UPDATE `dummy_evaluasi_dosen`
							SET `jml_review`='$jml_review',`jml_jam`='$jml_jam' WHERE `nip`='$nip_dosen[dosen1]' AND `username`='$_COOKIE[user]'");
								}
								$jml_dosen2 = mysqli_num_rows(mysqli_query($con, "SELECT `nip` FROM `dummy_evaluasi_dosen` WHERE `nip`='$nip_dosen[dosen2]' AND `username`='$_COOKIE[user]'"));
								if ($jml_dosen2 < 1) {
									$insert_dosen = mysqli_query($con, "INSERT INTO `dummy_evaluasi_dosen`
							(`nip`, `jml_review`,`jml_jam`,`username`)
							VALUES
							('$nip_dosen[dosen2]','1','$nip_dosen[tatap_muka2]','$_COOKIE[user]')");
								} else {
									$jml_review_asal = mysqli_fetch_array(mysqli_query($con, "SELECT `jml_review` FROM `dummy_evaluasi_dosen` WHERE `nip`='$nip_dosen[dosen2]' AND `username`='$_COOKIE[user]'"));
									$jml_jam_asal = mysqli_fetch_array(mysqli_query($con, "SELECT `jml_jam` FROM `dummy_evaluasi_dosen` WHERE `nip`='$nip_dosen[dosen2]' AND `username`='$_COOKIE[user]'"));
									$jml_review = $jml_review_asal['jml_review'] + 1;
									$jml_jam = $jml_jam_asal['jml_jam'] + $nip_dosen['tatap_muka2'];
									$update_dosen = mysqli_query($con, "UPDATE `dummy_evaluasi_dosen`
							SET `jml_review`='$jml_review',`jml_jam`='$jml_jam' WHERE `nip`='$nip_dosen[dosen2]' AND `username`='$_COOKIE[user]'");
								}
								$jml_dosen3 = mysqli_num_rows(mysqli_query($con, "SELECT `nip` FROM `dummy_evaluasi_dosen` WHERE `nip`='$nip_dosen[dosen3]' AND `username`='$_COOKIE[user]'"));
								if ($jml_dosen3 < 1) {
									$insert_dosen = mysqli_query($con, "INSERT INTO `dummy_evaluasi_dosen`
							(`nip`, `jml_review`, `jml_jam`,`username`)
							VALUES
							('$nip_dosen[dosen3]','1','$nip_dosen[tatap_muka3]','$_COOKIE[user]')");
								} else {
									$jml_review_asal = mysqli_fetch_array(mysqli_query($con, "SELECT `jml_review` FROM `dummy_evaluasi_dosen` WHERE `nip`='$nip_dosen[dosen3]' AND `username`='$_COOKIE[user]'"));
									$jml_jam_asal = mysqli_fetch_array(mysqli_query($con, "SELECT `jml_jam` FROM `dummy_evaluasi_dosen` WHERE `nip`='$nip_dosen[dosen3]' AND `username`='$_COOKIE[user]'"));
									$jml_review = $jml_review_asal['jml_review'] + 1;
									$jml_jam = $jml_jam_asal['jml_jam'] + $nip_dosen['tatap_muka3'];
									$update_dosen = mysqli_query($con, "UPDATE `dummy_evaluasi_dosen`
							SET `jml_review`='$jml_review',`jml_jam`='$jml_jam' WHERE `nip`='$nip_dosen[dosen3]' AND `username`='$_COOKIE[user]'");
								}
							}
						}

						$daftar_dosen = mysqli_query($con, "SELECT `nip` FROM `dummy_evaluasi_dosen` WHERE `username`='$_COOKIE[user]'");

						echo "<table class=\"table table-bordered\" style=\"width:90%\" id=\"freeze\">";
						echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
					<th style=\"width:5%;text-align:center;\">No</th>
					<th style=\"width:45%;text-align:center;\">Nama Dosen/Dokter</th>
					<th style=\"width:20%;text-align:center;\">NIP/NIK</th>
					<th style=\"width:15%;text-align:center;\">Jumlah Review</th>
					<th style=\"width:15%;text-align:center;\">Jumlah Jam</th>
					</thead>";

						$id = 1;
						$kelas = "ganjil";
						while ($nip_dosen = mysqli_fetch_array($daftar_dosen)) {
							$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$nip_dosen[nip]'"));
							echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
							echo "<td style=\"text-align:center; font-weight:600;\">$id</td>";
							echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red;\">$data_dosen[gelar]</span></td>";
							$jml_review = mysqli_fetch_array(mysqli_query($con, "SELECT `jml_review`,`jml_jam` FROM `dummy_evaluasi_dosen` WHERE `nip`='$nip_dosen[nip]' AND`username`='$_COOKIE[user]'"));
							echo "<td align=center style=\"font-weight:600; color:blue;\">$data_dosen[nip]</td>";
							echo "<td align=center style=\"font-weight:600; color:darkblue;\">$jml_review[jml_review]</td>";
							echo "<td align=center style=\"font-weight:600; color:darkblue;\">$jml_review[jml_jam]</td>";
							echo "</tr>";
							if ($kelas == "ganjil") $kelas = "genap";
							else $kelas = "ganjil";
							$id++;
						}

						echo "</table>";
						?>

					</div>
			</main>
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
	<script src="jquery.min.js"></script>
	<script type="text/javascript" src="freezeheader/js/jquery.freezeheader.js"></script>
	<script>
		$(document).ready(function() {
			$("#freeze").freezeHeader();
			$("#freeze1").freezeHeader();
		});
	</script>

</body>

</html>