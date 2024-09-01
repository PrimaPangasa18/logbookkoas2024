<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Biodata Logbook Koas Pendidikan Dokter FK-UNDIP</title>
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
				if ($_COOKIE['level'] == 2) {
					include "menu2.php";
				}
				if ($_COOKIE['level'] == 3) {
					include "menu3.php";
				}
				if ($_COOKIE['level'] == 4) {
					include "menu4.php";
				}
				if ($_COOKIE['level'] == 5) {
					include "menu5.php";
				}
				if ($_COOKIE['level'] == 6) {
					include "menu6.php";
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

		$kota_lahir = mysqli_fetch_array(mysqli_query($con, "SELECT `kota` FROM `kota` WHERE `id_kota`='$data_mhsw[kota_lahir]'"));
		$tgl_lahir = tanggal_indo($data_mhsw['tanggal_lahir']);

		$kota_alamat = mysqli_fetch_array(mysqli_query($con, "SELECT `kota` FROM `kota` WHERE `id_kota`='$data_mhsw[kota_alamat]'"));
		$prop_alamat = mysqli_fetch_array(mysqli_query($con, "SELECT `prop` FROM `prop` WHERE `id_prop`='$data_mhsw[prop_alamat]'"));

		$kota_ortu = mysqli_fetch_array(mysqli_query($con, "SELECT `kota` FROM `kota` WHERE `id_kota`='$data_mhsw[kota_ortu]'"));
		$prop_ortu = mysqli_fetch_array(mysqli_query($con, "SELECT `prop` FROM `prop` WHERE `id_prop`='$data_mhsw[prop_ortu]'"));

		$dosen_wali = mysqli_fetch_array(mysqli_query($con, "SELECT `nama`, `gelar` FROM `dosen` WHERE `nip`='$data_mhsw[dosen_wali]'"));

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
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">BIODATA MAHASISWA</h2>
						<br>
						<!-- ISI DISINI -->

						<div class="text-center">
							<img src="<?php echo $foto_path; ?>" class="img-thumbnail" style="width:150px;height:auto; box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);">
						</div>
						<br><br><br>
						<div class="container mt-4">
							<form>
								<center>
									<table class="table table-bordered" style="width: auto;">
										<tbody>
											<tr class="table-primary">
												<td style="width: 400px;"><strong>Nama Peserta</strong></td>
												<td style="width: 500px; font-weight:500; color:darkgreen"><?php echo $data_mhsw['nama']; ?></td>
											</tr>
											<tr class="table-success">
												<td style="width: 400px;"><strong>NIM Peserta</strong></td>
												<td style="width: 500px; font-weight:500; color:darkgreen"><?php echo $data_mhsw['nim']; ?></td>
											</tr>
											<tr class="table-primary">
												<td style="width: 400px;"><strong>Tempat, Tgl Lahir</strong></td>
												<td style="width: 500px; font-weight:500; color:darkgreen">
													<?php
													if ($data_mhsw['tanggal_lahir'] == "2000-01-01") {
														echo "&nbsp;";
													} else {
														echo $kota_lahir['kota'] . ", " . $tgl_lahir;
													}
													?>
												</td>
											</tr>
											<tr class="table-success">
												<td style="width: 400px;"><strong>Alamat</strong></td>
												<td style="width: 500px; font-weight:500; color:darkgreen"><?php echo $data_mhsw['alamat'] . " - " . $kota_alamat['kota'] . " - " . $prop_alamat['prop']; ?></td>
											</tr>
											<tr class="table-primary">
												<td style="width: 400px;"><strong>No HP</strong></td>
												<td style="width: 500px; font-weight:500; color:darkgreen"><?php echo $data_mhsw['no_hp']; ?></td>
											</tr>
											<tr class="table-success">
												<td style="width: 400px;"><strong>Email</strong></td>
												<td style="width: 500px; font-weight:500; color:darkgreen"><?php echo $data_mhsw['email']; ?></td>
											</tr>
											<tr class="table-primary">
												<td style="width: 400px;"><strong>Nama Orang Tua</strong></td>
												<td style="width: 500px; font-weight:500; color:darkgreen"><?php echo $data_mhsw['nama_ortu']; ?></td>
											</tr>
											<tr class="table-success">
												<td style="width: 400px;"><strong>Alamat Orang Tua</strong></td>
												<td style="width: 500px; font-weight:500; color:darkgreen"><?php echo $data_mhsw['alamat_ortu'] . " - " . $kota_ortu['kota'] . " - " . $prop_ortu['prop']; ?></td>
											</tr>
											<tr class="table-primary">
												<td style="width: 400px;"><strong>No HP Orang Tua</strong></td>
												<td style="width: 500px; font-weight:500; color:darkgreen"><?php echo $data_mhsw['no_hportu']; ?></td>
											</tr>
											<tr class="table-success">
												<td style="width: 400px;"><strong>Dosen Wali</strong></td>
												<td style="width: 500px; font-weight:500; color:darkgreen"><?php echo $dosen_wali['nama'] . ", " . $dosen_wali['gelar'] . " (NIP. " . $data_mhsw['dosen_wali'] . ")"; ?></td>
											</tr>
										</tbody>
									</table>
								</center>
							</form>
						</div>
						<!-- END DISINI -->
					</div>
			</main>
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
</body>

</html>