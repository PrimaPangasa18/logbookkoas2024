<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Rotasi Individu Normal Logbook Koas Pendidikan Dokter FK-UNDIP</title>
	<link rel="shortcut icon" type="x-icon" href="images/undipsolid.png">
	<link rel="stylesheet" type="text/css" href="jquery_ui/jquery-ui.css">
	<link rel="stylesheet" href="select2/dist/css/select2.css" />
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
						<h3 class="fw-bold fs-4 mb-3">ROTASI KEPANITERAAN</h3>
						<br />
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color: #0a3967">
							ROTASI INDIVIDU KEPANITERAAN (STASE) - NORMAL
						</h2>
						<br><br>
						<?php
						echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\" enctype=\"multipart/form-data\">";
						if (empty($_POST['submit'])) {
							$stase = mysqli_query($con, "SELECT * FROM `kepaniteraan` ORDER BY `id`");
						}
						?>
						<center>
							<div class="container">
								<table class="table table-bordered" style="width: auto;">
									<tr class="table-primary" style="border-width: 1px; border-color: #000;">
										<td style=" width: 300px;">
											<strong>Nama Mahasiswa <span class="text-danger">[NIM]</span></strong>
										</td>
										<td style=" width: 400px;">
											<?php
											echo "<select class=\"form-select\" name=\"nim\" id=\"nim\" required>";
											$data_nim = mysqli_query($con, "SELECT `nim`,`nama` FROM `biodata_mhsw` ORDER BY `nama`");
											echo "<option value=\"\">< Nama Mahasiswa ></option>";
											while ($data = mysqli_fetch_array($data_nim)) {
												echo "<option value=\"$data[nim]\">$data[nama] [NIM: $data[nim]]</option>";
											}
											echo "</select>";
											?>
										</td>
									</tr>
									<tr class="table-success" style="border-width: 1px; border-color: #000;">
										<td>
											<strong>Jenjang Semester Koas</strong>
										</td>
										<td>
											<?php
											echo "<select class=\"form-select\" name=\"semester\" id=\"semester\" required>";
											echo "<option value=\"\">< Pilihan Semester ></option>";
											echo "<option value=\"9\">Semester IX (Sembilan)</option>";
											echo "<option value=\"10\">Semester X (Sepuluh)</option>";
											echo "<option value=\"11\">Semester XI (Sebelas)</option>";
											echo "<option value=\"12\">Semester XII (Dua belas)</option>";
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
						</center>


						<?php
						echo '</form>';
						?>
						<br><br>
						<?php
						if (!empty($_POST['submit'])) {
							// Ambil data dari database
							$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT `nim`, `nama` FROM `biodata_mhsw` WHERE `nim`='$_POST[nim]'"));
							$semester = $_POST['semester'];
							$jumlah_stase = $_POST['jml_stase'];

						?>
							<div class="container">
								<h2 class="fw-bold fs-4 mb-3 text-center" style="color: #0a3967">
									HASIL ROTASI INDIVIDU
								</h2>
								<center>
									<table class="table table-bordered" style="width: auto;">
										<!-- Nama Mahasiswa -->
										<tr class="table-primary" style="border-width: 1px; border-color: #000;">
											<td style="width:300px;"><strong>Nama Mahasiswa <span class="text-danger">[NIM]</span></strong></td>
											<td style="width:500px; font-weight:700;">
												<?php
												$nama = htmlspecialchars($data_mhsw['nama']);
												$nim = htmlspecialchars($data_mhsw['nim']);
												echo $nama . ' <span style="color:#dc3545;;">[NIM: ' . $nim . ']</span>';
												?>
											</td>
										</tr>

										<!-- Jenjang Semester Koas -->
										<tr class="table-success" style="border-width: 1px; border-color: #000;">
											<td><strong>Rotasi stase semester</strong></td>
											<td style="font-weight:700"><?php echo htmlspecialchars($semester); ?></td>
										</tr>

										<!-- Jumlah Rotasi Stase -->
										<tr class="table-success" style="border-width: 1px; border-color: #000;">
											<td><strong>Jumlah rotasi stase</strong></td>
											<td style="font-weight:700"><?php echo htmlspecialchars($jumlah_stase); ?></td>
										</tr>

										<!-- Urutan Rotasi Kepaniteraan (Stase) -->
										<tr class="table-success" style="border-width: 1px; border-color: #000;">
											<td colspan="2"><strong>Urutan rotasi kepaniteraan (stase):</strong></td>
										</tr>

										<?php
										// Menghitung urutan stase
										$no = 1;
										$tgl_selesai_stase = "2000-01-01";
										while ($no <= $jumlah_stase) {
											$stase = $_POST['stase' . $no];
											$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kepaniteraan` WHERE `id`='$stase'"));
											$pekan_stase = $data_stase['hari_stase'] / 7;
											$tgl_mulai_stase = $_POST['tgl_mulai' . $no];
											$tglmulai_stase = tanggal_indo($tgl_mulai_stase);
											$hari_tambah = $data_stase['hari_stase'] - 1;
											$tambah_hari = '+' . $hari_tambah . ' days';
											$tgl_selesai_stase = date('Y-m-d', strtotime($tambah_hari, strtotime($tgl_mulai_stase)));
											if (!empty($_POST['tgl_selesai' . $no])) $tgl_selesai_stase = $_POST['tgl_selesai' . $no];
											$tglselesai_stase = tanggal_indo($tgl_selesai_stase);

											// Menampilkan informasi stase
										?>
											<tr class="table-info" style="border-width: 1px; border-color: #000;">>
												<td>Urutan ke-<?php echo htmlspecialchars($no); ?></td>
												<?php if ($stase != "") : ?>
													<td>
														<b><?php echo htmlspecialchars($data_stase['kepaniteraan']) . ' - Periode: ' . htmlspecialchars($pekan_stase) . ' pekan (' . htmlspecialchars($data_stase['hari_stase']) . ' hari)'; ?></b><br>
														<i>Mulai tanggal: <?php echo htmlspecialchars($tglmulai_stase); ?><br>
															Selesai tanggal: <?php echo htmlspecialchars($tglselesai_stase); ?></i>
													</td>
												<?php else : ?>
													<td>
														<font style="color:red">
															<< BELUM TERJADWAL>>
														</font>
													</td>
												<?php endif; ?>
											</tr>
										<?php
											$no++;
										}
										?>
									</table>
								</center>
							</div>
						<?php
						}
						?>

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
	<script type="text/javascript" src="jquery_ui/jquery-ui.js"></script>
	<script src="select2/dist/js/select2.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {

			$('#input-tanggal').datepicker({
				dateFormat: 'yy-mm-dd'
			});

			$('#semester').change(function() {
				var smt = $(this).val();
				$.ajax({
					type: 'POST',
					url: 'semester_stase.php',
					data: 'semester=' + smt,
					success: function(response) {
						$('#stase').html(response);
					}
				});
			});

			$("#semester").select2({
				placeholder: "< Pilihan Semester >"
			});

			$("#nim").select2({
				placeholder: "< Nama Mahasiswa >"
			});
		});
	</script>
</body>

</HTML>