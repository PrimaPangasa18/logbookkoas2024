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
		<!-- End Sidebar -->
		<div class="main">
			<!-- Start Navbar -->
			<nav class="navbar navbar-expand px-4 py-3">
				<form action="#" class="d-none d-sm-inline-block">
					<div class="input-group input-group-navbar">

					</div>
				</form>
				<div class="navbar-collapse collapse">
					<ul class="navbar-nav ms-auto">
						<li class="nav-item dropdown d-flex align-item-center">
							<span class="navbar-text me-2">Halo, <?php echo $nama . ' , <span class="gelar" style="color:red">' . $gelar . '</span>'; ?></span>
							<a href="#" class="nav-icon pe-md-0" data-bs-toggle="dropdown">
								<img src="images/account.png" class="avatar img-fluid" alt="" />
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
			<main class="content px-3 py-4">
				<div class="container-fluid">
					<div class="mb-3">
						<h3 class="fw-bold fs-4 mb-3">INFORMASI E-LOGBOOK KOAS</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">File Download Untuk Informasi Penting</h2>
						<br>
						<table class="table table-bordered table-striped info-table">
							<thead class="thead-dark">
								<tr>
									<th scope="col">No</th>
									<th scope="col">Item Informasi</th>
									<th scope="col">File Download</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<th scope="row">1</th>
									<td class="text-left">Modul Program Kepaniteraan (Stase) Ilmu Penyakit Dalam</td>
									<td><a href="file_download/Pedoman_Stase_Ilmu_Penyakit_Dalam.pdf" class="btn btn-primary" target="_blank">
											<i class="fa-solid fa-download me-2"></i> Download
										</a>
									</td>
								</tr>
								<tr>
									<th scope="row">2</th>
									<td class="text-left">Modul Program Kepaniteraan (Stase) Neurologi</td>
									<td><a href="file_download/Pedoman_Stase_Neurologi.pdf" class="btn btn-primary" target="_blank">
											<i class="fa-solid fa-download me-2"></i> Download
										</a>
									</td>
								</tr>
								<tr>
									<th scope="row">3</th>
									<td class="text-left">Modul Program Kepaniteraan (Stase) Ilmu Kesehatan Jiwa</td>
									<td><a href="file_download/Pedoman_Stase_Ilmu_Kesehatan_Jiwa.pdf" class="btn btn-primary" target="_blank">
											<i class="fa-solid fa-download me-2"></i> Download
										</a>
									</td>
								</tr>
								<tr>
									<th scope="row">4</th>
									<td class="text-left">Modul Program Kepaniteraan (Stase) IKFR</td>
									<td><a href="file_download/Pedoman_Stase_IKFR.pdf" class="btn btn-primary" target="_blank">
											<i class="fa-solid fa-download me-2"></i> Download
										</a>
									</td>
								</tr>
								<tr>
									<th scope="row">5</th>
									<td class="text-left">Modul Program Kepaniteraan (Stase) IKM-KP</td>
									<td><a href="file_download/Pedoman_Stase_IKM_KP.pdf" class="btn btn-primary" target="_blank">
											<i class="fa-solid fa-download me-2"></i> Download
										</a>
									</td>
								</tr>
								<tr>
									<th scope="row">6</th>
									<td class="text-left">Modul Program Kepaniteraan (Stase) Ilmu Jantung dan Pembuluh Darah</td>
									<td><a href="#" class="btn btn-warning">
											<i class="fa-solid fa-exclamation me-1"></i> Masih dalam Penyusunan
										</a>
									</td>
								</tr>
								<tr>
									<th scope="row">7</th>
									<td class="text-left">Modul Program Kepaniteraan (Stase) Ilmu Bedah</td>
									<td><a href="file_download/Pedoman_Stase_Ilmu_Bedah.pdf  " class="btn btn-primary" target="_blank">
											<i class="fa-solid fa-download me-2"></i> Download
										</a>
									</td>
								</tr>
								<tr>
									<th scope="row">8</th>
									<td class="text-left">Modul Program Kepaniteraan (Stase) Anestesi dan Intensive Care</td>
									<td><a href="file_download/Pedoman_Stase_Anestesi_dan_Intensive_Care.pdf" class="btn btn-primary" target="_blank">
											<i class="fa-solid fa-download me-2"></i> Download
										</a>
									</td>
								</tr>
								<tr>
									<th scope="row">9</th>
									<td class="text-left">Modul Program Kepaniteraan (Stase) Radiologi </td>
									<td><a href="file_download/Pedoman_Stase_Radiologi.pdf " class="btn btn-primary" target="_blank">
											<i class="fa-solid fa-download me-2"></i> Download
										</a>
									</td>
								</tr>
								<tr>
									<th scope="row">10</th>
									<td class="text-left">Modul Program Kepaniteraan (Stase) Ilmu Kesehatan Mata</td>
									<td><a href="file_download/Pedoman_Stase_Ilmu_Kesehatan_Mata.pdf" class="btn btn-primary" target="_blank">
											<i class="fa-solid fa-download me-2"></i> Download
										</a>
									</td>
								</tr>
								<tr>
									<th scope="row">11</th>
									<td class="text-left">Modul Program Kepaniteraan (Stase) Ilmu Kesehatan THT-KL</td>
									<td><a href="file_download/Pedoman_Stase_Ilmu_Kesehatan_THT_KL.pdf" class="btn btn-primary" target="_blank">
											<i class="fa-solid fa-download me-2"></i> Download
										</a>
									</td>
								</tr>
								<tr>
									<th scope="row">12</th>
									<td class="text-left">Modul Program Kepaniteraan (Stase) IKGM </td>
									<td><a href="file_download/Pedoman_Stase_IKGM.pdf" class="btn btn-primary" target="_blank">
											<i class="fa-solid fa-download me-2"></i> Download
										</a>
									</td>
								</tr>
								<tr>
									<th scope="row">13</th>
									<td class="text-left">Modul Program Kepaniteraan (Stase) Ilmu Kebidanan dan Penyakit Kandungan </td>
									<td><a href="file_download/Pedoman_Stase_Ilmu_Kebidanan_dan_Penyakit_Kandungan.pdf " class="btn btn-primary" target="_blank">
											<i class="fa-solid fa-download me-2"></i> Download
										</a>
									</td>
								</tr>
								<tr>
									<th scope="row">14</th>
									<td class="text-left">Modul Program Kepaniteraan (Stase) Kedokteran Forensik dan Medikolegal </td>
									<td><a href="file_download/Pedoman_Stase_Kedokteran_Forensik_dan_Medikolegal.pdf " class="btn btn-primary" target="_blank">
											<i class="fa-solid fa-download me-2"></i> Download
										</a>
									</td>
								</tr>
								<tr>
									<th scope="row">15</th>
									<td class="text-left">Modul Program Kepaniteraan (Stase) Ilmu Kesehatan Anak </td>
									<td><a href="file_download/Pedoman_Stase_Ilmu_Kesehatan_Anak.pdf " class="btn btn-primary" target="_blank">
											<i class="fa-solid fa-download me-2"></i> Download
										</a>
									</td>
								</tr>
								<tr>
									<th scope="row">16</th>
									<td class="text-left">Modul Program Kepaniteraan (Stase) Ilmu Kesehatan Kulit dan Kelamin </td>
									<td><a href="file_download/Pedoman_Stase_Ilmu_Kesehatan_Kulit_dan_Kelamin.pdf " class="btn btn-primary" target="_blank">
											<i class="fa-solid fa-download me-2"></i> Download
										</a>
									</td>
								</tr>
								<tr>
									<th scope="row">17</th>
									<td class="text-left">Modul Program Kepaniteraan (Stase) Komprehensip dan Kedokteran Keluarga </td>
									<td><a href="file_download/Pedoman_Stase_Kedokteran_Keluarga.pdf " class="btn btn-primary" target="_blank">
											<i class="fa-solid fa-download me-2"></i> Download
										</a>
									</td>
								</tr>
								<tr>
									<th scope="row">18</th>
									<td class="text-left">Modul Program Kepaniteraan (Stase) Komprehensip</td>
									<td><a href="file_download/Pedoman_Stase_Komprehensip.pdf" class="btn btn-primary" target="_blank">
											<i class="fa-solid fa-download me-2"></i> Download
										</a>
									</td>
								</tr>
							</tbody>
						</table>

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