<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Rotasi Internal Logbook Koas Pendidikan Dokter FK-UNDIP</title>
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
			if (!empty($_COOKIE['user']) and !empty($_COOKIE['pass']) and ($_COOKIE['level'] == 1 or $_COOKIE['level'] == 2 or $_COOKIE['level'] == 3)) {
				if ($_COOKIE['level'] == 1) {
					include "menu1.php";
				}
				if ($_COOKIE['level'] == 2) {
					include "menu2.php";
				}
				if ($_COOKIE['level'] == 3) {
					include "menu3.php";
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
						<h3 class="fw-bold fs-4 mb-3">ROTASI INTERNAL KEPANITERAAN (STASE)</h3>
						<br />
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color: #0a3967">
							ROTASI INTERNAL
						</h2>
						<br><br>
						<?php

						$id_stase = $_GET['id'];
						$nim = $_GET['mhsw'];
						$id_internal = "internal_" . $id_stase;
						$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
						$stase_id = "stase_" . $id_stase;
						$data_stase_mhsw = mysqli_query($con, "SELECT * FROM `$stase_id` WHERE `nim`='$nim'");
						$jml_stase_mhsw = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `$stase_id` WHERE `nim`='$nim'"));
						$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);
						$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$nim'"));
						?>
						<center>
							<table class="table table-bordered" style="width: auto;">
								<tr class="table-primary" style="border-width: 1px; border-color: #000;">
									<td style="width: 300px;"><strong>Nama Mahasiswa</strong></td>
									<td style="width: 500px; color:brown; font-weight:700">&nbsp; <?php echo $data_mhsw['nama']; ?> <span class="text-danger">(NIM: <?php echo $data_mhsw['nim']; ?>)</span></td>
								</tr>
								<tr class="table-success" style="border-width: 1px; border-color: #000;">
									<td style="width: 300px;"><strong>Kepaniteraan (STASE)</strong></td>
									<td style="width: 500px; color:purple; font-weight:700">&nbsp; <?php echo $data_stase['kepaniteraan']; ?></td>
								</tr>
								<?php
								if ($jml_stase_mhsw > 0) {
									$tgl_mulai = tanggal_indo($datastase_mhsw['tgl_mulai']);
									$tgl_selesai = tanggal_indo($datastase_mhsw['tgl_selesai']);
								} else {
									$tgl_mulai = '<span class="text-danger" style="font-weight:600">BELUM TERJADWAL</span>';
									$tgl_selesai = '<span class="text-danger" style="font-weight:600">BELUM TERJADWAL</span>';
								}
								?>
								<tr class="table-primary" style="border-width: 1px; border-color: #000;">
									<td style="width: 300px;"><strong>Tanggal mulai kepaniteraan (STASE)</strong></td>
									<td style="width: 500px; color:darkblue; font-weight:700">&nbsp; <?php echo $tgl_mulai; ?></td>
								</tr>
								<tr class="table-success" style="border-width: 1px; border-color: #000;">
									<td style="width: 300px;"><strong>Tanggal selesai kepaniteraan (STASE)</strong></td>
									<td style="width: 500px; color:darkblue; font-weight:700">&nbsp; <?php echo $tgl_selesai; ?></td>
								</tr>
							</table>
						</center>
						<br>
						<hr style="border: 2px solid ; margin: 20px 0; color:darkblue">
						<br>
						<?php
						$cek_internal = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `$id_internal` WHERE `nim`='$nim'"));
						if ($cek_internal == 0) {
						?>
							<table class="table table-bordered">
								<thead class="thead-primary" style="border-width: 1px; border-color: #000;">
									<tr>
										<th style="width:5%">No</th>
										<th style="width:20%">Rotasi Internal</th>
										<th style="width:15%">Lama Pelaksanaan</th>
										<th style="width:15%">Tanggal Mulai</th>
										<th style="width:15%">Tanggal Selesai</th>
										<th style="width:30%">Status Approval</th>
									</tr>
								</thead>
								<tbody style="border-width: 1px; border-color: #000;">
									<tr>
										<td colspan="6" class="text-center" style="padding:10px">
											<span class="text-danger">Status rotasi internal stase belum aktif!</span>
										</td>
									</tr>
								</tbody>
							</table>
						<?php
						} else {
						?>
							<table class="table table-bordered">
								<thead class="table-primary" style="border-width: 1px; border-color: #000;">
									<tr>
										<th style="width:5%;text-align: center;">No</th>
										<th style="width:20%;text-align: center;">Rotasi Internal</th>
										<th style="width:15%;text-align: center;">Lama Pelaksanaan</th>
										<th style="width:15%;text-align: center;">Tanggal Mulai</th>
										<th style="width:15%;text-align: center; ">Tanggal Selesai</th>
										<th style="width:30% ; text-align: center;">Status Approval</th>
									</tr>
								</thead>
								<tbody style="border-width: 1px; border-color: #000;">
									<?php
									$rotasi_internal = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `$id_internal` WHERE `nim`='$nim'"));
									$rotasi = mysqli_query($con, "SELECT * FROM `rotasi_internal` WHERE `stase`='$id_stase' ORDER BY `id`");
									$i = 1;
									while ($data = mysqli_fetch_array($rotasi)) {
										$tgl_i = "tgl" . $i;
										$id_status = "status" . $i;
										$id_dosen = "dosen" . $i;
										$row_class = ($i % 2 == 0) ? 'table-warning' : 'table-success';
									?>
										<tr class="<?php echo $row_class; ?>" style="border-width: 1px; border-color: #000;">
											<td class="text-center"><strong><?php echo $i; ?></strong></td>
											<td style="font-weight: 600; color:darkblue"><?php echo $data['internal']; ?></td>
											<td class="text-center" style="font-weight: 600; color:purple"><?php echo $data['hari']; ?> hari</td>
											<td class="text-center">
												<?php
												if (!is_null($rotasi_internal[$tgl_i])) {
													$tgl_mulai = tanggal_indo($rotasi_internal[$tgl_i]);
													echo $tgl_mulai;
												} else {
													echo '<span class="text-danger" style="font-weight: 600;"><< Empty >></span>';
												}
												?>
											</td>
											<td class="text-center">
												<?php
												if (!is_null($rotasi_internal[$tgl_i])) {
													$hari_tambah = $data['hari'] - 1;
													$tambah_hari = '+' . $hari_tambah . ' days';
													$tglselesai = date('Y-m-d', strtotime($tambah_hari, strtotime($rotasi_internal[$tgl_i])));
													$tgl_selesai = tanggal_indo($tglselesai);
													echo $tgl_selesai;
												} else {
													echo '<span class="text-danger" style="font-weight: 600;"><< Empty >></span>';
												}
												?>
											</td>
											<td class="text-center">
												<?php
												if ($rotasi_internal[$id_status] == 0) {
													echo '<span class="text-danger" style="font-weight: 600;">Unapproved</span>';
												} else {
													echo '<span class="text-success" style="font-weight: 600;">Approved</span>';
												}
												if (!is_null($rotasi_internal[$id_dosen])) {
													$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$rotasi_internal[$id_dosen]'"));
													echo '<br><br><span style="font-weight: 600; color:purple;">Approval oleh</span><br><br><span style="font-weight: 600;">' . $data_dosen['nama'] . '</span>, <span style="color:red; font-weight:600;">' . $data_dosen['gelar'] . '</span> (<span style="color:blue; font-weight:600;">' . $data_dosen['nip'] . '</span>)';
												} else {
													echo '<br><br><span  style="font-weight: 600; color:purple;">Approval oleh:</span><br><br><span class="text-danger"style="font-weight: 600;"><< BELUM DIPILIH >></span>';
												}
												?>
											</td>
										</tr>
									<?php
										$i++;
									}
									?>
								</tbody>
							</table>
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
</body>

</HTML>