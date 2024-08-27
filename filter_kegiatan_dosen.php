<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Daftar Kegiatan Logbook Koas Pendidikan Dokter FK-UNDIP</title>
	<link rel="shortcut icon" type="x-icon" href="images/undipsolid.png">
	<link rel="stylesheet" href="style/style1.css" />
	<link rel="stylesheet" href="style/buttontopup.css">
	<link rel="stylesheet" href="select2/dist/css/select2.css" />
	<link rel="stylesheet" type="text/css" href="jquery_ui/jquery-ui.css">
	<link rel="stylesheet" href="style/filterkegiatan.css">

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
			if (!empty($_COOKIE['user']) and !empty($_COOKIE['pass']) and ($_COOKIE['level'] == 4 or $_COOKIE['level'] == 6)) {
				if ($_COOKIE['level'] == 4) {
					include "menu4.php";
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
							<span class="navbar-text me-2">Halo, <?php echo $nama . ' , <span class="gelar" style="color:red">' . $gelar . '</span>'; ?></span>
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
			<main class="content px-3 py-4">
				<div class="container-fluid">
					<div class="mb-3">
						<h3 class="fw-bold fs-4 mb-3">DAFTAR KEGIATAN DOSEN/RESIDEN</h3>
						<br><br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color: #0a3967">
							FILTER TAMPIL DAFTAR KEGIATAN DOSEN/RESIDEN
						</h2>
						<br>

						<!-- ISI DISINI -->
						<div class="container mt-5">
							<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
								<center>
									<table class="table table-bordered" style="width: auto; ">
										<tbody>
											<tr style="border-width: 1px; border-color: #000;" class="table-primary">
												<td style="width: 400px"><strong>Kepaniteraan <span class="text-danger">(stase)</span></strong></td>
												<td>
													<select class="form-select" name="stase" id="stase">
														<option value="all">Semua Kepaniteraan (Stase)</option>
														<?php
														$data_stase = mysqli_query($con, "SELECT * FROM `kepaniteraan` ORDER BY `id`");
														while ($data = mysqli_fetch_array($data_stase)) {
															echo "<option value=\"$data[id]\">$data[kepaniteraan]</option>";
														}
														?>
													</select>
												</td>
											</tr>
											<tr style="border-width: 1px; border-color: #000;" class="table-success">
												<td><strong>Nama Mahasiswa</strong></td>
												<td>
													<select class="form-select" name="mhsw" id="mhsw">
														<option value="all">Semua Mahasiswa</option>
														<?php
														$data_mhsw = mysqli_query($con, "SELECT `nim`,`nama` FROM `biodata_mhsw` ORDER BY `nama`");
														while ($data1 = mysqli_fetch_array($data_mhsw)) {
															echo "<option value=\"$data1[nim]\">$data1[nama]</option>";
														}
														?>
													</select>
												</td>
											</tr>
											<tr style="border-width: 1px; border-color: #000;" class="table-primary">
												<td><strong>Status Approval</strong></td>
												<td>
													<select class="form-select" name="appstatus" id="appstatus">
														<option value="all">Semua Status</option>
														<option value="1">Approved</option>
														<option value="0">Unapproved</option>
													</select>
												</td>
											</tr>
											<tr style="border-width: 1px; border-color: #000;" class="table-success">
												<td><strong>Tanggal kegiatan <span class="text-danger">(yyyy-mm-dd)</span></strong></td>
												<td>
													<input type="text" class="form-select input-tanggal" name="tgl_kegiatan" style="font-size: 1em;" placeholder="Tanggal Kegiatan">
												</td>
											</tr>
										</tbody>
									</table>
									<br>
									<div class="d-grid gap-1 col-1 mx-auto">
										<button type="submit" class="submit-btn" name="submit" value="SUBMIT">
											<i class="fas fa-magnifying-glass me-2"></i> SEARCH
										</button>
									</div>
									<?php
									if ($_POST['submit'] == "SUBMIT") {
										echo "
            						<script>
               						 window.location.href = \"daftar_kegiatan_dosen.php?mhsw=\"+\"$_POST[mhsw]\"+\"&stase=\"+\"$_POST[stase]\"+\"&tgl_kegiatan=\"+\"$_POST[tgl_kegiatan]\"+\"&appstatus=\"+\"$_POST[appstatus]\";
          							  </script>";
									}
									?>
								</center>
							</form>
						</div>
						<!-- Berakhir -->
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
	<script type="text/javascript" src="jquery_ui/jquery-ui.js"></script>
	<script src="select2/dist/js/select2.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('.input-tanggal').datepicker({
				dateFormat: 'yy-mm-dd'
			});
			$("#stase").select2({
				placeholder: "< Kepaniteraan (Stase) >",
				allowClear: true
			});
			$("#mhsw").select2({
				placeholder: "< Nama Mahasiswa >",
				allowClear: true
			});
			$("#appstatus").select2({
				placeholder: "< Status Approval >",
				allowClear: true
			});
		});
	</script>
</body>

</HTML>