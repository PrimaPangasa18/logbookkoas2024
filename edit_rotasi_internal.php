<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Edit Rotasi Logbook Koas Pendidikan Dokter FK-UNDIP</title>
	<link rel="shortcut icon" type="x-icon" href="images/undipsolid.png">
	<link rel="stylesheet" href="select2/dist/css/select2.css" />
	<link rel="stylesheet" type="text/css" href="jquery_ui/jquery-ui.css">
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
			if (!empty($_COOKIE['user']) and !empty($_COOKIE['pass']) and $_COOKIE['level'] == 5) {
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
						<h3 class="fw-bold fs-4 mb-3">EDIT ROTASI INTERNAL</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">EDIT ROTASI INTERNAL</h2>
						<br>
						<center>
							<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
								<?php
								$id_stase = $_GET['stase'];
								$id_i = $_GET['id_i'];
								$id_internal = "internal_" . $id_stase;
								$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
								$stase_id = "stase_" . $id_stase;
								$data_stase_mhsw = mysqli_query($con, "SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'");
								$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);

								$tgl_mulai = tanggal_indo($datastase_mhsw['tgl_mulai']);
								$tgl_selesai = tanggal_indo($datastase_mhsw['tgl_selesai']);
								$internal_stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `$id_internal` WHERE `nim`='$_COOKIE[user]'"));
								$id_rotasi = $id_stase . $id_i;
								$data_rotasi = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `rotasi_internal` WHERE `id`='$id_rotasi'"));
								$id_dosen = "dosen" . $id_i;
								$id_tgl = "tgl" . $id_i;
								?>

								<input type="hidden" name="id_stase" value="<?php echo htmlspecialchars($id_stase); ?>">
								<input type="hidden" name="id_internal" value="<?php echo htmlspecialchars($id_internal); ?>">
								<input type="hidden" name="id_i" value="<?php echo htmlspecialchars($id_i); ?>">


								<table class="table table-bordered" style="width: auto;">
									<tr class="table-primary" style="border-width: 1px; border-color: #000;">
										<td style="width:auto"><strong>Kepaniteraan (STASE)</strong></td>
										<td style="width:auto; color:darkgreen; font-weight:700">&nbsp;<?php echo htmlspecialchars($data_stase['kepaniteraan']); ?></td>
									</tr>
									<tr class=" table-warning" style="border-width: 1px; border-color: #000;">
										<td style="width:auto"><strong>Tanggal mulai kepaniteraan (STASE)</strong></td>
										<td style="width:auto; color:darkgreen; font-weight:700">&nbsp;<?php echo htmlspecialchars($tgl_mulai); ?></td>
									</tr>
									<tr class="table-warning" style="border-width: 1px; border-color: #000;">
										<td style="width:auto"><strong>Tanggal selesai kepaniteraan (STASE)</strong></td>
										<td style="width:auto; color:darkgreen; font-weight:700">&nbsp;<?php echo htmlspecialchars($tgl_selesai); ?></td>
									</tr>
								</table>

								<br><br>

								<table class="table table-bordered" style="width: auto;">
									<tr class="table-primary" style="border-width: 1px; border-color: #000;">
										<td style="width:auto"><strong>Internal Stase</strong></td>
										<td style="width:auto; color:darkgreen; font-weight:700"><?php echo htmlspecialchars($data_rotasi['internal']); ?></td>
									</tr>
									<tr class="table-warning" style="border-width: 1px; border-color: #000;">
										<td style="width:auto"><strong>Lama Pelaksanaan</strong></td>
										<td style="width:auto; color:darkgreen; font-weight:700"><?php echo htmlspecialchars($data_rotasi['hari']); ?> hari</td>
									</tr>
									<tr class="table-warning" style="border-width: 1px; border-color: #000;">
										<td style="width:auto"><strong>Tanggal Mulai <span class="text-danger">(yyyy-mm-dd)</span></strong></td>
										<td style="width:auto; color:darkgreen; font-weight:700">
											<input type="text" class="form-select input-tanggal" name="tgl_mulai" value="<?php echo htmlspecialchars($internal_stase[$id_tgl]); ?>" required />
										</td>
									</tr>
									<tr class="table-warning" style="border-width: 1px; border-color: #000;">
										<td style="width:auto"><strong>Approval oleh</strong></td>
										<td style="width:auto;">
											<select class="form-select" name="dosen" id="dosen" required>
												<option value="">Pilih Dosen/Residen/Petugas</option>
												<?php
												$dosen = mysqli_query($con, "SELECT `username`, `nama` FROM `admin` WHERE `level`='4' OR (`level`='6' AND `stase`='$id_stase') ORDER BY `nama`");
												while ($dat = mysqli_fetch_array($dosen)) {
													$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `nip`, `nama`, `gelar` FROM `dosen` WHERE `nip`='$dat[username]'"));
													echo "<option value=\"" . htmlspecialchars($dat['username']) . "\">" . htmlspecialchars($data_dosen['nama']) . ", " . htmlspecialchars($data_dosen['gelar']) . " (" . htmlspecialchars($data_dosen['nip']) . ")</option>";
												}
												?>
											</select>
										</td>
									</tr>
								</table>

								<br><br>
								<button type="submit" class="btn btn-danger" name="batal" value="BATAL" formnovalidate><i class="fa-solid fa-xmark me-2"></i>BATAL</button>
								&nbsp;&nbsp;&nbsp;
								<button type="submit" class="btn btn-success" name="submit" value="SUBMIT"><i class="fa-solid fa-floppy-disk me-2"></i>SIMPAN</button>

							</form>
						</center>

						<?php

						if ($_POST['batal'] == "BATAL") {
							echo "
			<script>
				window.location.href=\"internal_stase.php?id=\"+\"$_POST[id_stase]\";
			</script>
			";
						}

						if ($_POST['submit'] == "SUBMIT") {
							$id_tgl = "tgl" . $_POST['id_i'];
							$id_dosen = "dosen" . $_POST['id_i'];
							$id_status = "status" . $_POST['id_i'];
							$update_internal = mysqli_query($con, "UPDATE `$_POST[id_internal]` SET
				`$id_tgl`='$_POST[tgl_mulai]',`$id_dosen`='$_POST[dosen]',`$id_status`='0'
				WHERE `nim`='$_COOKIE[user]'");
							echo "
			<script>
				window.location.href=\"internal_stase.php?id=\"+\"$_POST[id_stase]\";
			</script>
			";
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
	<script src="select2/dist/js/select2.js"></script>
	<script type="text/javascript" src="jquery_ui/jquery-ui.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('.input-tanggal').datepicker({
				dateFormat: 'yy-mm-dd'
			});
			$("#dosen").select2({
				placeholder: "< Pilihan Dosen/Residen/Petugas >",
				allowClear: true
			});
		});
	</script>
</body>

</html>