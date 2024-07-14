<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Logbook Koas Pendidikan Dokter FK-UNDIP</title>
	<link rel="shortcut icon" type="x-icon" href="images/undipsolid.png">
	<link rel="stylesheet" href="style/style1.css" />
	<link rel="stylesheet" href="style/buttontopup.css">

	<!-- Link Bootstrap -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
	<!-- Link CDN Icon -->
	<link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />

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

				// echo "<img src=\"images/fkundip.jpg\" style=\"width:100%;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);\">";
				//Menampilkan nama dan gelar di navbar
				$nama = isset($_COOKIE['nama']) ? $_COOKIE['nama'] : 'User';
				$gelar = isset($_COOKIE['gelar']) ? $_COOKIE['gelar'] : '';
				if ($_COOKIE['level'] == 5) {
					$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT `foto` FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
					if ($data_mhsw[foto] == "profil_blank.png") {
						// echo "<div class=\"text_welcome\">Welcome ...... <br><br>
						// <font style=\"color:red\"><div class=\"blink\">Silakan update profile foto Anda dengan file tipe jpg/jpeg/gif/png ukuran maksimal 100kB!!!</div></font><br>
						// <a href=\"edit_usermhsw_action.php\"><b>Update Profile</b></a></div>";
					} //else echo "<div class=\"text_welcome\">Welcome ...... </div>";
				} else;
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
						<h3 class="fw-bold fs-4 mb-3">BERANDA</h3>
						<div class="row">
							<div class="col-12 col-md-4">
								<div class="card border-0">
									<div class="card-body py-4 rounded-2">
										<h5 class="mb-2 fw-bold">Memebers Progress</h5>
										<p class="mb-2 fw-bold">$72,540</p>
										<div class="mb-0">
											<span class="badge text-success me-2"> +9.0% </span>
											<span class="fw-bold"> Since Last Month </span>
										</div>
									</div>
								</div>
							</div>
							<div class="col-12 col-md-4">
								<div class="card border-0">
									<div class="card-body py-4 rounded-2">
										<h5 class="mb-2 fw-bold">Memebers Progress</h5>
										<p class="mb-2 fw-bold">$72,540</p>
										<div class="mb-0">
											<span class="badge text-success me-2"> +9.0% </span>
											<span class="fw-bold"> Since Last Month </span>
										</div>
									</div>
								</div>
							</div>
							<div class="col-12 col-md-4">
								<div class="card border-0">
									<div class="card-body py-4 rounded-2">
										<h5 class="mb-2 fw-bold">Memebers Progress</h5>
										<p class="mb-2 fw-bold">$72,540</p>
										<div class="mb-0">
											<span class="badge text-success me-2"> +9.0% </span>
											<span class="fw-bold"> Since Last Month </span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<h3 class="fw-bold fs-4 my-3">Avg. Agent Earnings</h3>
						<div class="row">
							<div class="col-12">
								<table class="table table-striped">
									<thead>
										<tr class="highlight">
											<th scope="col">#</th>
											<th scope="col">First</th>
											<th scope="col">Last</th>
											<th scope="col">Handle</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<th scope="row">1</th>
											<td>Mark</td>
											<td>Otto</td>
											<td>@mdo</td>
										</tr>
										<tr>
											<th scope="row">2</th>
											<td>Jacob</td>
											<td>Thornton</td>
											<td>@fat</td>
										</tr>
										<tr>
											<th scope="row">3</th>
											<td colspan="2">Larry the Bird</td>
											<td>@twitter</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="container-fluid">
					<div class="mb-3">
						<h3 class="fw-bold fs-4 mb-3">BERANDA</h3>
						<div class="row">
							<div class="col-12 col-md-4">
								<div class="card border-0">
									<div class="card-body py-4 rounded-2">
										<h5 class="mb-2 fw-bold">Memebers Progress</h5>
										<p class="mb-2 fw-bold">$72,540</p>
										<div class="mb-0">
											<span class="badge text-success me-2"> +9.0% </span>
											<span class="fw-bold"> Since Last Month </span>
										</div>
									</div>
								</div>
							</div>
							<div class="col-12 col-md-4">
								<div class="card border-0">
									<div class="card-body py-4 rounded-2">
										<h5 class="mb-2 fw-bold">Memebers Progress</h5>
										<p class="mb-2 fw-bold">$72,540</p>
										<div class="mb-0">
											<span class="badge text-success me-2"> +9.0% </span>
											<span class="fw-bold"> Since Last Month </span>
										</div>
									</div>
								</div>
							</div>
							<div class="col-12 col-md-4">
								<div class="card border-0">
									<div class="card-body py-4 rounded-2">
										<h5 class="mb-2 fw-bold">Memebers Progress</h5>
										<p class="mb-2 fw-bold">$72,540</p>
										<div class="mb-0">
											<span class="badge text-success me-2"> +9.0% </span>
											<span class="fw-bold"> Since Last Month </span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<h3 class="fw-bold fs-4 my-3">Avg. Agent Earnings</h3>
						<div class="row">
							<div class="col-12">
								<table class="table table-striped">
									<thead>
										<tr class="highlight">
											<th scope="col">#</th>
											<th scope="col">First</th>
											<th scope="col">Last</th>
											<th scope="col">Handle</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<th scope="row">1</th>
											<td>Mark</td>
											<td>Otto</td>
											<td>@mdo</td>
										</tr>
										<tr>
											<th scope="row">2</th>
											<td>Jacob</td>
											<td>Thornton</td>
											<td>@fat</td>
										</tr>
										<tr>
											<th scope="row">3</th>
											<td colspan="2">Larry the Bird</td>
											<td>@twitter</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
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