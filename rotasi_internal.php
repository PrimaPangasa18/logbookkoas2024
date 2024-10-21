<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Rotasi Stase Logbook Koas Pendidikan Dokter FK-UNDIP</title>
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
						<h3 class="fw-bold fs-4 mb-3">ROTASI KEPANITERAAN (STASE)</h3>
						<br />
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color: #0a3967">
							ROTASI INDIVIDU KEPANITERAAN (STASE)
						</h2>
						<br><br>
						<?php
						if ($_COOKIE['level'] == 5) {
							$nim_mhsw = $_COOKIE['user'];
						} else {
							$nim_mhsw = $_GET['user_name'];
							// Informasi data mahasiswa
							$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT `nim`, `nama` FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw'"));
						?>
							<center>
								<table class="table table-bordered" style="width: 800px;">
									<tr class="table-primary" style="border-width: 1px; border-color: #000;">
										<td style="width:300px"><strong>Nama Mahasiswa</strong></td>
										<td style="width:500px; font-weight:600"><?= $data_mhsw['nama']; ?></td>
									</tr>
									<tr class="table-success" style="border-width: 1px; border-color: #000;">
										<td style="width:300px"><strong>NIM</strong></td>
										<td style="width:500px; font-weight:600"><?= $data_mhsw['nim']; ?></td>
									</tr>
								</table>
							</center>
							<br><br>
						<?php
						}
						//hapus order by semester
						$semester = mysqli_query($con, "SELECT DISTINCT `semester` FROM `kepaniteraan`");
						?>
						<table class="table table-bordered table-primary" style="width:100%">
							<tr class="table-warning" style="border-width: 1px; border-color: #000;">
								<td colspan="6"> <strong>Catatan Penting:</strong><br><br>
									<span style="font-weight: 500;">- Evaluasi akhir kepaniteraan (stase) <span class="text-danger" style="font-weight:bold;">wajib</span> diisi sebagai syarat pemrosesan Grade Ketuntasan tiap kepaniteraan (stase)</span>
								</td>
							</tr>
							<tr>
								<td colspan="6" style="border-width: 1px; border-color: #000;">&nbsp;</td>
							</tr>
							<?php
							while ($data_smt = mysqli_fetch_array($semester)) {
							?>
								<tr>
									<td colspan="6" style="border-width: 1px; border-color: #000;"><b>SEMESTER <?= $data_smt['semester']; ?></b></td>
								</tr>
								<tr>
									<th style="text-align: center; border-width: 1px; border-color: #000;">Urutan</th>
									<th style="text-align: center; border-width: 1px; border-color: #000;">Kepaniteraan (Stase)</th>
									<th style="text-align: center; border-width: 1px; border-color: #000;">Tanggal Mulai</th>
									<th style="text-align: center; border-width: 1px; border-color: #000;">Tanggal Selesai</th>
									<th style="text-align: center; border-width: 1px; border-color: #000;">Status</th>
									<th style="text-align: center; border-width: 1px; border-color: #000;">Evaluasi</th>
								</tr>
								<?php
								$stase_smt = mysqli_query($con, "SELECT * FROM `kepaniteraan` WHERE `semester`= '$data_smt[semester]' ORDER BY `id`");
								$hapus_dummy = mysqli_query($con, "DELETE FROM `dummy_rotasi` WHERE `username`='$_COOKIE[user]'");
								while ($data_stase = mysqli_fetch_array($stase_smt)) {
									$stase_id = "stase_" . $data_stase['id'];
									$urutan_stase = mysqli_query($con, "SELECT `rotasi` FROM `$stase_id` WHERE `nim`='$nim_mhsw'");
									$jml_mhsw = mysqli_num_rows($urutan_stase);
									$urutan_rotasi = mysqli_fetch_array($urutan_stase);
									$urutan = ($jml_mhsw == 0) ? 8 : $urutan_rotasi['rotasi'];
									$insert_dummy = mysqli_query($con, "INSERT INTO `dummy_rotasi` (`id`, `urutan`, `username`) VALUES ('$data_stase[id]', '$urutan', '$_COOKIE[user]')");
								}
								$rotasi_stase = mysqli_query($con, "SELECT * FROM `dummy_rotasi` ORDER BY `urutan`, `id`");
								$no = 1;
								while ($data_rotasi = mysqli_fetch_array($rotasi_stase)) {
									$id_stase = $data_rotasi['id'];
									$stase_id = "stase_" . $data_rotasi['id'];
									$nama_stase = mysqli_fetch_array(mysqli_query($con, "SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$data_rotasi[id]'"));
								?>
									<tr class="table-success" style="border-width: 1px; border-color: #000;">
										<td style="text-align: center;font-weight:600"><?= ($data_rotasi['urutan'] == 9) ? '#' : $no; ?></td>
										<td style="font-weight:600;"><?= $nama_stase['kepaniteraan']; ?></td>
										<?php
										if ($data_rotasi['urutan'] == 8) {
										?>
											<td colspan="4">
												<font style="color: red;"><strong>
														<< BELUM DIJADWALKAN ATAU DIJADWAL ULANG>>
													</strong>
												</font>
											</td>
										<?php
										} else {
											$datastase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `$stase_id` WHERE `nim`='$nim_mhsw'"));
											$tgl_mulai = tanggal_indo($datastase['tgl_mulai']);
											$tgl_selesai = tanggal_indo($datastase['tgl_selesai']);
										?>
											<td style="text-align: center; font-weight:600; color:purple; "><?= $tgl_mulai; ?></td>
											<td style="text-align: center; font-weight:600; color:purple; "><?= $tgl_selesai; ?></td>
											<td>
												<strong>
													<font style="color:<?= ($datastase['status'] == "0") ? 'blue' : (($datastase['status'] == "1") ? 'green' : 'blue'); ?>"><?= ($datastase['status'] == "0") ? 'Belum Aktif' : (($datastase['status'] == "1") ? 'Aktif' : 'Sudah Lewat'); ?></font>
												</strong>
											</td>
											<td>
												<?php
												if ($_COOKIE['level'] == "5") {
													if ($datastase['evaluasi'] == "0") {
												?>
														<font style="color:red;"><strong>Belum</strong></font>
														&nbsp;&nbsp;&nbsp;&nbsp;
														<a href="isi_evaluasi.php?id=<?= $id_stase; ?>" name="entry_<?= $id_stase; ?>">
															<button type="button" class="btn btn-primary btn-sm" value="ENTRY">
																<i class="fa-solid fa-pen-to-square me-2"></i> ENTRY
															</button>
														</a>


													<?php
													} else {
													?>
														<font style="color:green"><strong>Sudah &nbsp;</strong></font>
														<a href="edit_evaluasi.php?id=<?= $id_stase; ?>&nim=<?= $nim_mhsw; ?>&menu=rotasi">
															<button type="button" class="btn btn-warning btn-sm" name="edit_<?= $id_stase; ?>" value="EDIT">
																<i class="fa fa-pencil-alt me-2"></i> EDIT
															</button>
														<?php
													}
												} else {
													if ($datastase['evaluasi'] == "0") {
														?>
															<font style="color:red"><strong>Belum</strong></font>
														<?php
													} else {
														?>
															<font style="color:darkgreen"><strong>Sudah</strong></font>
															<a href="lihat_evaluasi.php?id=<?= $id_stase; ?>&nim=<?= $nim_mhsw; ?>&menu=rotasi">
																<button type="button" class="btn btn-success btn-sm" name="lihat_<?= $id_stase; ?>">
																	<i class="fa fa-eye"></i> LIHAT
																</button>
															</a>

													<?php
													}
												}
													?>
											</td>
										<?php
										}
										?>
									</tr>
								<?php
									$no++;
								}
								?>
								<tr>
									<td colspan="6" style="border-width: 1px; border-color: #000;">&nbsp;</td>
								</tr>
							<?php
							}
							?>
						</table>
						<br><br>
						<?php
						$hapus_dummy = mysqli_query($con, "DELETE FROM `dummy_rotasi` WHERE `username`='$_COOKIE[user]'");
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
</body>

</html>