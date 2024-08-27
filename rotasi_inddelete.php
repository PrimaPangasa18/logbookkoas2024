<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Hapus Rotasi Logbook Koas Pendidikan Dokter FK-UNDIP</title>
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
						<h3 class="fw-bold fs-4 mb-3">ROTASI INDIVIDU KEPANITERAAN (STASE)</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">ROTASI INDIVIDU KEPANITERAAN (STASE) - HAPUS ROTASI</h2>
						<br>
						<?php
						$nim_mhsw = $_GET['user_name'];
						$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT `nim`,`nama` FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw'"));
						?>
						<center>
							<table class="table table-bordered" style="width: auto;">
								<tr class="table-primary" style="border-width: 1px; border-color: #000;">
									<td style="width:200px"><strong>Nama Mahasiswa</strong></td>
									<td style="width:400px;font-weight: 600; color:darkgreen"> <?php echo $data_mhsw['nama']; ?></td>
								</tr>
								<tr class="table-success" style="border-width: 1px; border-color: #000;">
									<td><strong>NIM</strong></td>
									<td style="font-weight: 600; color:darkred"> <?php echo $data_mhsw['nim']; ?></td>
								</tr>
							</table>
						</center>
						<br><br>
						<table class="table table-bordered">

							<!-- Semester IX -->
							<tr class="table-warning" style="border-width: 1px; border-color: #000;">
								<td colspan="5" style="color:#04202C"><b>SEMESETER IX (SEMBILAN)</b></td>
							</tr>
							<tr class="table-primary" style="border-width: 1px; border-color: #000;">
								<th style="width:20px; text-align:center">Urutan</th>
								<th style="width:500px;text-align:center">Kepaniteraan (STASE)</th>
								<th style="width:150px;text-align:center">Tanggal Mulai</th>
								<th style="width:150px;text-align:center">Tanggal Selesai</th>
								<th style="width:150px;text-align:center">Status</th>
							</tr>

							<?php
							$stase_ix = mysqli_query($con, "SELECT * FROM `kepaniteraan` WHERE `semester`='9' ORDER BY `id` ASC");
							$no = 1;
							while ($data_ix = mysqli_fetch_array($stase_ix)) {
								$stase_id = "stase_" . $data_ix['id'];
								$data_stase = mysqli_query($con, "SELECT * FROM `$stase_id` WHERE `nim`='$nim_mhsw'");
								$jml_jadwal = mysqli_num_rows($data_stase);
								echo '<tr class="table-success" style="border-width: 1px; border-color: #000;">';
								if ($jml_jadwal > 0) {
									$stase = mysqli_fetch_array($data_stase);
									echo "<td align=\"center\" style=\"font-weight:600\">$no</td>";
									echo "<td style=\"font-weight:600\">$data_ix[kepaniteraan]</td>";
									$tgl_mulai = tanggal_indo($stase['tgl_mulai']);
									echo "<td align=\"center\" style=\"color:darkblue;font-weight:600\">$tgl_mulai</td>";
									$tgl_selesai = tanggal_indo($stase['tgl_selesai']);
									echo "<td align=\"center\" style=\"color:darkblue;font-weight:600\">$tgl_selesai</td>";
									if ($stase['status'] == "0") {
										echo "<td  style=\"color:purple\">Belum Aktif<br>";
										echo "<td align=\"center\" style=\"color:purple;font-weight:600\">Belum Aktif<br><br>";
										echo "\<a href=\"rotasi_delete_ind.php?nim=$nim_mhsw&stase=$data_ix[id]\"  type=\"button\" name=\"delete\" class=\"btn btn-danger btn-sm\" value=\"DELETE\" >";
										echo "<i class=\"fa-solid fa-trash me-2\"></i>DELETE";
										echo "</a>";
										echo "</td>";
									}
									if ($stase['status'] == "1") echo "<td align=\"center\"><font style=\"color:darkgreen;font-weight:600\">Aktif</font></td>";
									if ($stase['status'] == "2") echo "<td align=\"center\"><font style=\"color:red;font-weight:600\">Sudah Lewat</font></td>";
								} else {
									echo "<td align=\"center\" style=\"font-weight:600\">$no</td>";
									echo "<td style=\"font-weight:600\">$data_ix[kepaniteraan]</td>";
									echo "<td colspan=3 align=\"center\" style=\"font-weight:600; color:red\" ><< Belum dijadwalkan >></td>";
								}
								echo '</tr>';
								$no++;
							}
							?>

						</table>
						<br>
						<table class="table table-bordered">

							<!-- Semester X -->
							<tr class="table-warning" style="border-width: 1px; border-color: #000;">
								<td colspan="5" style="color:#04202C"><b>SEMESETER X (SEPULUH)</b></td>
							</tr>
							<tr class="table-primary" style="border-width: 1px; border-color: #000;">
								<th style="width:20px;text-align:center">Urutan</th>
								<th style="width:500px;text-align:center">Kepaniteraan (Stase)</th>
								<th style="width:150px;text-align:center">Tanggal Mulai</th>
								<th style="width:150px;text-align:center">Tanggal Selesai</th>
								<th style="width:150px;text-align:center">Status</th>
							</tr>

							<?php
							$stase_x = mysqli_query($con, "SELECT * FROM `kepaniteraan` WHERE `semester`='10' ORDER BY `id` ASC");
							$no = 1;
							while ($data_x = mysqli_fetch_array($stase_x)) {
								$stase_id = "stase_" . $data_x['id'];
								$data_stase = mysqli_query($con, "SELECT * FROM `$stase_id` WHERE `nim`='$nim_mhsw'");
								$jml_jadwal = mysqli_num_rows($data_stase);
								echo '<tr  class="table-success" style="border-width: 1px; border-color: #000;">';
								if ($jml_jadwal > 0) {
									$stase = mysqli_fetch_array($data_stase);
									echo "<td  align=\"center\" style=\"font-weight:600\">$no</td>";
									echo "<td style=\"font-weight:600\">$data_x[kepaniteraan]</td>";
									$tgl_mulai = tanggal_indo($stase['tgl_mulai']);
									echo "<td align=\"center\" style=\"color:darkblue;font-weight:600\">$tgl_mulai</td>";
									$tgl_selesai = tanggal_indo($stase['tgl_selesai']);
									echo "<td align=\"center\" style=\"color:darkblue;font-weight:600\">$tgl_selesai</td>";
									if ($stase['status'] == "0") {
										echo "<td  style=\"color:purple\">Belum Aktif<br>";
										echo "<td align=\"center\" style=\"color:purple;font-weight:600\">Belum Aktif<br><br>";
										echo "\<a href=\"rotasi_delete_ind.php?nim=$nim_mhsw&stase=$data_x[id]\"  type=\"button\" name=\"delete\" class=\"btn btn-danger btn-sm\" value=\"DELETE\" >";
										echo "<i class=\"fa-solid fa-trash me-2\"></i>DELETE";
										echo "</a>";
										echo "</td>";
									}
									if ($stase['status'] == "1") echo "<td  align=\"center\"><font style=\"color:darkgreen;font-weight:600\">Aktif</font></td>";
									if ($stase['status'] == "2") echo "<td  align=\"center\"><font style=\"color:red;font-weight:600\">Sudah Lewat</font></td>";
								} else {
									echo "<td align=\"center\" style=\"font-weight:600\">$no</td>";
									echo "<td style=\"font-weight:600\">$data_x[kepaniteraan]</td>";
									echo "<td colspan=3 align=\"center\" style=\"font-weight:600; color:red\" ><< Belum dijadwalkan >></td>";
								}
								echo '</tr>';
								$no++;
							}
							?>

						</table>
						<br>
						<table class="table table-bordered">

							<!-- Semester XI -->
							<tr class="table-warning" style="border-width: 1px; border-color: #000;">
								<td colspan="5" style="color:#04202C"><b>SEMESETER XI (SEBELAS)</b></td>
							</tr>
							<tr class="table-primary" style="border-width: 1px; border-color: #000;">
								<th style="width:20px;text-align:center">Urutan</th>
								<th style="width:500px;text-align:center">Kepaniteraan (Stase)</th>
								<th style=" width:150px;text-align:center">Tanggal Mulai</th>
								<th style="width:150px;text-align:center">Tanggal Selesai</th>
								<th style=" width:150px;text-align:center">Status</th>
							</tr>

							<?php
							$stase_xi = mysqli_query($con, "SELECT * FROM `kepaniteraan` WHERE `semester`='11' ORDER BY `id` ASC");
							$no = 1;
							while ($data_xi = mysqli_fetch_array($stase_xi)) {
								$stase_id = "stase_" . $data_xi['id'];
								$data_stase = mysqli_query($con, "SELECT * FROM `$stase_id` WHERE `nim`='$nim_mhsw'");
								$jml_jadwal = mysqli_num_rows($data_stase);
								echo '<tr class="table-success" style="border-width: 1px; border-color: #000;">';
								if ($jml_jadwal > 0) {
									$stase = mysqli_fetch_array($data_stase);
									echo "<td align=\"center\" style=\"font-weight:600\">$no</td>";
									echo "<td style=\"font-weight:600\">$data_xi[kepaniteraan]</td>";
									$tgl_mulai = tanggal_indo($stase['tgl_mulai']);
									echo "<td align=\"center\" style=\"color:darkblue;font-weight:600\">$tgl_mulai</td>";
									$tgl_selesai = tanggal_indo($stase['tgl_selesai']);
									echo "<td align=\"center\" style=\"color:darkblue;font-weight:600\">$tgl_selesai</td>";
									if ($stase['status'] == "0") {
										echo "<td  style=\"color:purple\">Belum Aktif<br>";
										echo "<td align=\"center\" style=\"color:purple;font-weight:600\">Belum Aktif<br><br>";
										echo "\<a href=\"rotasi_delete_ind.php?nim=$nim_mhsw&stase=$data_xi[id]\"  type=\"button\" name=\"delete\" class=\"btn btn-danger btn-sm\" value=\"DELETE\" >";
										echo "<i class=\"fa-solid fa-trash me-2\"></i>DELETE";
										echo "</a>";
										echo "</td>";
									}
									if ($stase['status'] == "1") echo "<td  align=\"center\"><font style=\"color:darkgreen;font-weight:600\">Aktif</font></td>";
									if ($stase['status'] == "2") echo "<td  align=\"center\"><font style=\"color:red;font-weight:600\">Sudah Lewat</font></td>";
								} else {
									echo "<td align=\"center\" style=\"font-weight:600\">$no</td>";
									echo "<td style=\"font-weight:600\">$data_xi[kepaniteraan]</td>";
									echo "<td colspan=3 align=\"center\" style=\"font-weight:600; color:red\"><< Belum dijadwalkan >></td>";
								}
								echo '</tr>';
								$no++;
							}
							?>
						</table>
						<br>

						<table class="table table-bordered">

							<!-- Semester XII -->
							<tr class="table-warning" style="border-width: 1px; border-color: #000;">
								<td colspan="5" style="color:#04202C"><b>SEMESETER XII (DUA BELAS)</b></td>
							</tr>
							<tr class="table-primary" style="border-width: 1px; border-color: #000;">
								<th style="width:20px;text-align:center">Urutan</th>
								<th style="width:500px;text-align:center">Kepaniteraan (Stase)</th>
								<th style="width:150px;text-align:center">Tanggal Mulai</th>
								<th style="width:150px;text-align:center">Tanggal Selesai</th>
								<th style="width:150px;text-align:center">Status</th>
							</tr>

							<?php
							$stase_xii = mysqli_query($con, "SELECT * FROM `kepaniteraan` WHERE `semester`='12' ORDER BY `id` ASC");
							$no = 1;
							while ($data_xii = mysqli_fetch_array($stase_xii)) {
								$stase_id = "stase_" . $data_xii['id'];
								$data_stase = mysqli_query($con, "SELECT * FROM `$stase_id` WHERE `nim`='$nim_mhsw'");
								$jml_jadwal = mysqli_num_rows($data_stase);
								echo '<tr class="table-success" style="border-width: 1px; border-color: #000;">';
								if ($jml_jadwal > 0) {
									$stase = mysqli_fetch_array($data_stase);
									echo "<td align=\"center\" style=\"font-weight:600\">$no</td>";
									echo "<td style=\"font-weight:600\">$data_xii[kepaniteraan]</td>";
									$tgl_mulai = tanggal_indo($stase['tgl_mulai']);
									echo "<td align=\"center\" style=\"color:darkblue;font-weight:600\">$tgl_mulai</td>";
									$tgl_selesai = tanggal_indo($stase['tgl_selesai']);
									echo "<td align=\"center\" style=\"color:darkblue;font-weight:600\">$tgl_selesai</td>";
									if ($stase['status'] == "0") {
										echo "<td  style=\"color:purple\">Belum Aktif<br>";
										echo "<td align=\"center\" style=\"color:purple;font-weight:600\">Belum Aktif<br><br>";
										echo "\<a href=\"rotasi_delete_ind.php?nim=$nim_mhsw&stase=$data_xii[id]\"  type=\"button\" name=\"delete\" class=\"btn btn-danger btn-sm\" value=\"DELETE\" >";
										echo "<i class=\"fa-solid fa-trash me-2\"></i>DELETE";
										echo "</a>";
										echo "</td>";
									}
									if ($stase['status'] == "1") echo "<td align=\"center\"><font style=\"color:darkgreen;font-weight:600\">Aktif</font></td>";
									if ($stase['status'] == "2") echo "<td align=\"center\"><font style=\"color:red;font-weight:600\">Sudah Lewat</font></td>";
								} else {
									echo "<td align=\"center\" style=\"font-weight:600\">$no</td>";
									echo "<td style=\"font-weight:600\">$data_xii[kepaniteraan]</td>";
									echo "<td colspan=3 align=\"center\" style=\"font-weight:600; color:red\"><< Belum dijadwalkan >></td>";
								}
								echo '</tr>';
								$no++;
							}
							?>

						</table>
						<br>
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
</body>


</html>