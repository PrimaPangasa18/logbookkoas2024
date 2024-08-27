<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Hapus Rotasi Kelompok Logbook Koas Pendidikan Dokter FK-UNDIP</title>
	<link rel="shortcut icon" type="x-icon" href="images/undipsolid.png">
	<link rel="stylesheet" href="select2/dist/css/select2.css" />
	<link rel="stylesheet" href="style/style1.css" />
	<link rel="stylesheet" href="style/buttonotoup2.css">

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
			if (!empty($_COOKIE['user']) and !empty($_COOKIE['pass']) and $_COOKIE['level'] == 1) {
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
						<h3 class="fw-bold fs-4 mb-3">ROTASI KEPANITERAAN (STASE)</h3>
						<br />
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color: #0a3967">
							ROTASI KELOMPOK KEPANITERAAN (STASE) - HAPUS ROTASI
						</h2>
						<br><br>
						<?php
						echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";

						$stase = mysqli_query($con, "SELECT * FROM `kepaniteraan` ORDER BY `id`");
						?>
						<center>
							<div class="container">

								<table class="table table-bordered" style="width: auto;">
									<tr class="table-primary" style="border-width: 1px; border-color: #000;">
										<td style=" width: 300px;">
											<strong>Kepaniteraan (STASE)</strong>
										</td>
										<td style="width: 400px;">
											<?php
											echo "<select class=\"form-select\" name=\"stase\" id=\"stase\" required>";
											if (empty($_POST['stase']) and empty($_GET['get_stase'])) {
												echo "<option value=\"\">< Pilihan Kepaniteraan (Stase) ></option>";
											} else {
												if (empty($_GET['get_stase'])) {
													$stase_pilihan = mysqli_fetch_array(mysqli_query($con, "SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$_POST[stase]'"));
													echo "<option value=\"$_POST[stase]\">$stase_pilihan[kepaniteraan]</option>";
												}
												if (empty($_POST['stase'])) {
													$stase_pilihan = mysqli_fetch_array(mysqli_query($con, "SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$_GET[get_stase]'"));
													echo "<option value=\"$_GET[get_stase]\">$stase_pilihan[kepaniteraan]</option>";
												}
											}
											while ($dat_stase = mysqli_fetch_array($stase)) {
												echo "<option value=\"$dat_stase[id]\">$dat_stase[kepaniteraan] - (Semester: $dat_stase[semester] | Periode: $dat_stase[hari_stase] hari)</option>";
											}
											echo "</select>";
											?>
										</td>
									</tr>
								</table>
								<br>
								<button type="submit" class="btn btn-success" name="submit" value="SUBMIT">
									<i class="fa-solid fa-floppy-disk me-2"></i>SUBMIT
								</button>

							</div>
							<?php
							if ($_POST['submit'] == "SUBMIT" or $_GET['get_submit'] == "SUBMIT") {
								if ($_POST['submit'] == "SUBMIT") $stase_id = "stase_" . $_POST['stase'];
								if ($_GET['get_submit'] == "SUBMIT") $stase_id = "stase_" . $_GET['get_stase'];
								$stase_all = mysqli_query($con, "SELECT DISTINCT `tgl_mulai`,`tgl_selesai` FROM `$stase_id` ORDER BY `tgl_mulai`");
								$no = 1;
								$kelas = "GANJIL";
							?>
								<br><br>
								<div class='container'>
									<table class='table table-bordered'>
										<thead class='table-primary'>
											<tr style="border-width: 1px; border-color: #000;">
												<th style='width:5%;text-align:center'>No</th>
												<th style='width:25%;text-align:center'>Tanggal Mulai</th>
												<th style='width:25%;text-align:center'>Tanggal Selesai</th>
												<th style='width:20%;text-align:center'>Jumlah Peserta Koas</th>
												<th style='width:25%;text-align:center'>Status</th>
											</tr>
										</thead>
										<tbody class="table-warning" style="border-width: 1px; border-color: #000;">
											<?php
											while ($data = mysqli_fetch_array($stase_all)) {
												$tanggal_mulai = tanggal_indo($data['tgl_mulai']);
												$tanggal_selesai = tanggal_indo($data['tgl_selesai']);
												$jml_mhsw = mysqli_num_rows(mysqli_query($con, "SELECT `nim` FROM `$stase_id` WHERE `tgl_mulai`='$data[tgl_mulai]'"));
											?>
												<tr class="<?php echo $kelas; ?>">
													<td style="text-align:center;"><strong><?php echo $no; ?></strong></td>
													<td style="text-align:center; font-weight:600; "><?php echo $tanggal_mulai; ?></td>
													<td style="text-align:center; font-weight:600; "><?php echo $tanggal_selesai; ?></td>
													<td style="text-align:center; font-weight:600; color:blueviolet"><?php echo $jml_mhsw; ?></td>
													<td style="text-align:center">
														<?php
														if (strtotime($tgl) > strtotime($data['tgl_mulai'])) {
															if (strtotime($tgl) < strtotime($data['tgl_selesai'])) {
																echo "<span style='color:darkgreen; font-weight:600;'>Sedang Berjalan</span>";
															} elseif (strtotime($tgl) > strtotime($data['tgl_selesai'])) {
																echo "<span style='color:darkblue; font-weight:600;'>Sudah Lewat</span>";
															}
														} else {
															echo "<font style='color:red; font-weight:600;'>Belum Dimulai</font><br><br>";
															echo "<a href='rotasi_delete.php?stase=" . $_POST['stase'] . "&tgl_mulai=" . $data['tgl_mulai'] . "'>
        														<button type='button' name='delete' class='btn btn-danger btn-sm' value='DELETE'>
           														 <i class='fa fa-trash'></i> DELETE
        														</button>
      															</a>";
														}
														?>
													</td>
												</tr>
											<?php
												if ($kelas == "GANJIL") $kelas = "GENAP";
												else $kelas = "GANJIL";
												$no++;
											}
											?>
										</tbody>
									</table>
								</div>
							<?php
							}
							?>
						</center>
					</div>
				</div>
			</main>


			<!-- End Content -->
			<button id="back-to-top" title="Back to Top">
				<i class="fa-solid fa-arrow-up" style="margin-bottom: 2px;"></i>
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
	<script src="javascript/buttontopup2.js"></script>
	<script type="text/javascript" src="jquery.min.js"></script>
	<script src="select2/dist/js/select2.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#stase").select2({
				placeholder: "< Pilihan Kepaniteraan (Stase) >"
			});
		});
	</script>
</body>

</HTML>