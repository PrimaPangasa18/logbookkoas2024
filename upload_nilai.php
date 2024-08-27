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
	<link rel="stylesheet" href="select2/dist/css/select2.css" />
	<link rel="stylesheet" type="text/css" href="jquery_ui/jquery-ui.css">

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
						<h3 class="fw-bold fs-4 mb-3">UPLOAD NILAI</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">FILTER UPLOAD NILAI</h2>
						<br>
						<center>
							<div class="container">
								<form name="myForm" id="myForm" onsubmit="return validateForm()" action="csvimport_nilai.php" method="post" enctype="multipart/form-data" required>
									<div class="table-responsive">
										<table class="table table-bordered" style="width: auto;">
											<tr style="border-width: 1px; border-color: #000;" class="table-primary">
												<td style="width: 300px;"><strong>Kepaniteraan / Stase</strong></td>
												<td style="width: 600px;">
													<?php if ($_COOKIE['user'] == "kaprodi") { ?>
														<select class="form-select" name="stase" id="stase" required>
															<option value="">
																<< Pilihan Kepaniteraan (Stase)>>
															</option>
															<?php
															$data_stase = mysqli_query($con, "SELECT * FROM `kepaniteraan` ORDER BY `id`");
															while ($data = mysqli_fetch_array($data_stase)) {
																echo "<option value=\"$data[id]\">$data[kepaniteraan]</option>";
															}
															?>
														</select>
													<?php } else {
														$id_stase = substr($_COOKIE['user'], 5, 3);
														$stase_id = "M" . "$id_stase";
														$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kepaniteraan` WHERE `id`='$stase_id'"));
													?>
														<select class="form-select" name="stase" id="stase" required>
															<option value="<?= $data_stase['id'] ?>"><?= $data_stase['kepaniteraan'] ?></option>
														</select>
													<?php } ?>
												</td>
											</tr>
											<tr style="border-width: 1px; border-color: #000;" class="table-warning">
												<td><strong>Jenis Nilai</strong></td>
												<?php $filter_test = filter_nilai($stase_id); ?>
												<td>
													<select class="form-select" name="jenis_nilai" id="jenis_nilai" required>
														<option value="">
															<< Jenis Nilai>>
														</option>
														<?php
														$jenis_test = mysqli_query($con, "SELECT * FROM `jenis_test` WHERE $filter_test ORDER BY `id`");
														while ($test = mysqli_fetch_array($jenis_test)) {
															echo "<option value=\"$test[id]\">$test[jenis_test]</option>";
														}
														?>
													</select>
												</td>
											</tr>
											<tr style="border-width: 1px; border-color: #000;" class="table-primary">
												<td><strong>Status Ujian/Test</strong></td>
												<td>
													<select class="form-select" name="status_ujian" id="status_ujian" required>
														<option value="">
															<< Status Ujian/Test>>
														</option>
														<?php
														$data_status = mysqli_query($con, "SELECT * FROM `status_ujian` ORDER BY `id`");
														while ($status = mysqli_fetch_array($data_status)) {
															echo "<option value=\"$status[id]\">$status[status_ujian]</option>";
														}
														?>
													</select>
												</td>
											</tr>
											<tr style="border-width: 1px; border-color: #000;" class="table-warning">
												<td><strong>Tanggal Ujian/Test</strong></td>
												<td><input type="text" class="form-select" name="tgl_ujian" placeholder="yyyy-mm-dd" required></td>
											</tr>
											<tr style="border-width: 1px; border-color: #000;" class="table-primary">
												<td><strong>Tanggal Yudisium Nilai</strong></td>
												<td><input type="text" class="form-select" name="tgl_approval" placeholder="yyyy-mm-dd" required></td>
											</tr>
											<tr style="border-width: 1px; border-color: #000;" class="table-warning">
												<td colspan="2">
													<div style="text-align: center;">
														<span style="font-weight:600;">Upload file nilai dalam format csv dengan header: <br>
															<span style="color: blue;">no - nama - nim - nilai - catatan</span></span><br><br>
													</div>
													<span style="font-size: 0.9em; font-weight:600;">Catatan:<br>
														Kolom <span class="text-danger">no</span> diisi nomor urut.<br>
														Kolom <span class="text-danger">nama</span> diisi nama mahasiswa koas.<br>
														Kolom <span class="text-danger">nim</span> diisi nim mahasiswa koas.<br>
														Kolom <span class="text-danger">nilai</span> diisi nilai mahasiswa koas, dalam format desimal dengan dua angka di belakang titik <span class="text-danger">(0.00 - 100.00)</span>.<br>
														Kolom <span class="text-danger">catatan</span> diisi catatan khusus mengenai nilai ujian/test mahasiswa koas.<br>
													</span>
													<br>
													Import file: <input type="file" class="form-control" id="import_nilai" name="import_nilai" accept=".csv" required><br>
													Separator file csv:
													<select class="form-select" name="separator" required>
														<option value="">
															< Pilihan Separator>
														</option>
														<option value=",">Koma --> ( , )</option>
														<option value=";">Titik Koma --> ( ; )</option>
													</select>
													<br>
												</td>
											</tr>

										</table>
										<br>
										<button type="submit" class="btn btn-success" name="import" value="Import"><i class="fa-solid fa-cloud-arrow-up me-2"></i>Import</button>
									</div>
								</form>
							</div>
						</center>
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
	<script type="text/javascript" src="jquery.min.js"></script>
	<script type="text/javascript" src="jquery_ui/jquery-ui.js"></script>
	<script src="select2/dist/js/select2.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('.input-tanggal').datepicker({
				dateFormat: 'yy-mm-dd'
			});
			$("#stase").select2({
				placeholder: "<< Pilihan Kepaniteraan (Stase) >>",
				allowClear: true
			});
			$("#dosen").select2({
				placeholder: "< Nama Dosen/Residen >",
				allowClear: true
			});
			$("#jenis_nilai").select2({
				placeholder: "<< Jenis Nilai >>",
				allowClear: true
			});
			$("#status_ujian").select2({
				placeholder: "<< Status Ujian/Test >>",
				allowClear: true
			});
		});
	</script>

</body>

</html>