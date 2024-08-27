<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Rekap Umum Evaluasi Harian Logbook Koas Pendidikan Dokter FK-UNDIP</title>
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
						<h3 class="fw-bold fs-4 mb-3">REKAP UMUM EVALUASI</h3>
						<br />
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color: #0a3967">
							REKAP UMUM EVALUASI KEPANITERAAN (STASE)
						</h2>
						<br><br>
						<?php
						$grup_filter = $_GET['grup'];
						$stase_filter = $_GET['stase'];
						$angkatan_filter = $_GET['angk'];
						$tgl_awal = $_GET['tglawal'];
						$tgl_akhir = $_GET['tglakhir'];
						$stase_id = "stase_" . $stase_filter;
						$target_id = "target_" . $stase_filter;
						$include_id = "include_" . $stase_filter;

						$filterstase = "`stase`=" . "'$stase_filter'";
						$filtertgl = " AND (`tanggal`>=" . "'$tgl_awal'" . " AND `tanggal`<=" . "'$tgl_akhir')";

						$hari_kurang = 28 - 1;
						$kurang_hari = '-' . $hari_kurang . ' days';
						$tgl_batas = date('Y-m-d', strtotime($kurang_hari, strtotime($tgl_akhir)));

						if ($grup_filter == "1") $filtergrup = "`tgl_mulai`<=" . "'$tgl_batas'";
						if ($grup_filter == "2") $filtergrup = "`tgl_mulai`>" . "'$tgl_batas'" . " AND `tgl_mulai`<=" . "'$tgl_akhir'";
						if ($grup_filter == "all") $filtergrup = "`tgl_mulai`<=" . "'$tgl_akhir'";

						$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$stase_filter'"));
						$kepaniteraan = $data_stase['kepaniteraan'];
						$filter = $filterstase . $filtertgl;

						$mhsw = mysqli_query($con, "SELECT `nim` FROM `$stase_id` WHERE $filtergrup");
						$i = 1;
						while ($data_mhsw = mysqli_fetch_array($mhsw)) {
							//cek Angkatan
							$angkatan = mysqli_fetch_array(mysqli_query($con, "SELECT `angkatan` FROM `biodata_mhsw` WHERE `nim`='$data_mhsw[nim]'"));
							if ($angkatan_filter == $angkatan['angkatan'] or $angkatan_filter == "all") {
								$mhsw_nim[$i] = $data_mhsw['nim'];
								$i++;
							}
						}
						$jml_mhsw = $i - 1;
						?>
						<center>
							<table class="table table-bordered" style="width:auto;">
								<tr class="table-primary" style="border-width: 1px; border-color: #000;">
									<td style="width:200px"><strong>Kepaniteraan (Stase)</strong></td>
									<td style="width:500px">&nbsp; <span style="font-weight: 600; color:darkgreen;"><?php echo $data_stase['kepaniteraan']; ?></span></td>
								</tr>
								<tr class="table-success" style="border-width: 1px; border-color: #000;">
									<td><strong>Angkatan</strong></td>
									<td style="font-weight: 600; color:purple">&nbsp; <?php echo ($angkatan_filter == "all") ? "Semua Angkatan" : "Angkatan $angkatan_filter"; ?></td>
								</tr>
								<tr class="table-primary" style="border-width: 1px; border-color: #000;">
									<td><strong>Grup</strong></td>
									<td style="font-weight: 600; color: red;">&nbsp;
										<?php
										if ($grup_filter == "all") {
											echo "Semua";
										} else {
											echo ($grup_filter == '1') ? "Senior" : "Yunior";
										}
										?>
									</td>
								</tr>
								<tr class="table-success" style="border-width: 1px; border-color: #000;">
									<td><strong>Jumlah Mahasiswa</strong></td>
									<td style="font-weight: 600;">&nbsp; <span class=" text-danger"><?php echo $jml_mhsw; ?></span> Orang</td>
								</tr>
								<?php
								$tglawal = tanggal_indo($tgl_awal);
								$tglakhir = tanggal_indo($tgl_akhir);
								?>
								<tr class="table-primary" style="border-width: 1px; border-color: #000;">
									<td><strong>Periode Kegiatan</strong></td>
									<td style="font-weight: 600;">&nbsp; <span style="color: darkblue;"><?php echo $tglawal; ?></span> s.d <span style="color: darkblue;"><?php echo $tglakhir; ?></span></td>

								</tr>
							</table>
						</center>
						<br><br>

						<table id="freeze" class="table table-bordered" style="width:100%;">
							<thead class="table-primary" style="border-width: 1px; border-color: #000;">
								<tr style="text-align: center;">
									<th style="width:5%;">No</th>
									<th style="width:12%;">Tanggal</th>
									<th style="width:21%;">Nama Mahasiswa (NIM)<br>Kepaniteraan/Stase</th>
									<th style="width:30%;">Evaluasi</th>
									<th style="width:30%;">Rencana Hari Berikutnya</th>
								</tr>
							</thead>
							<tbody class="table-warning" style="border-width: 1px; border-color: #000;">
								<?php
								$kelas = "ganjil";
								$no_i = 1;

								for ($no = 1; $no <= $jml_mhsw; $no++) {
									$eval = mysqli_query($con, "SELECT * FROM `evaluasi` WHERE $filter AND `nim`='$mhsw_nim[$no]' ORDER BY `tanggal`");

									while ($data = mysqli_fetch_array($eval)) {
										$tanggalisi = tanggal_indo($data['tanggal']);
								?>
										<tr class="<?php echo $kelas; ?>">
											<td class="text-center"><strong><?php echo $no_i; ?></strong></td>
											<td style="text-align: center; color:darkblue; font-weight: 600;"><?php echo $tanggalisi; ?></td>
											<?php
											$mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT `nama` FROM `biodata_mhsw` WHERE `nim`='$mhsw_nim[$no]'"));
											$nama_stase = mysqli_fetch_array(mysqli_query($con, "SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$data[stase]'"));
											?>
											<td style="font-weight: 600;">
												<?php
												$nama_mhsw = $mhsw['nama'];
												$nim_mhsw = $mhsw_nim[$no];
												$kepaniteraan = $nama_stase['kepaniteraan'];
												?>
												<?php echo $nama_mhsw; ?> <span style="color: red;"><?php echo "($nim_mhsw)"; ?></span><br><br>
												<span style="color: black;">Kepaniteraan/Stase:</span><br>
												<b style="color: darkgreen;"><?php echo $kepaniteraan; ?></b>
											</td>

											<td style="font-weight: 600;"><?php echo $data['evaluasi']; ?></td>
											<td style="font-weight: 600;"><?php echo $data['rencana']; ?></td>
										</tr>
									<?php
										$kelas = ($kelas == "ganjil") ? "genap" : "ganjil";
										$no_i++;
									}
								}

								if ($no_i == 1) {
									?>
									<tr>
										<td colspan="5" class="text-center" style="font-weight: 600; color:red">
											<< E M P T Y>>
										</td>
									</tr>
								<?php
								}
								?>
							</tbody>
						</table>
						<br><br>

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
	<script type="text/javascript" src="freezeheader/js/jquery.freezeheader.js"></script>
	<script>
		$(document).ready(function() {
			$("#freeze").freezeHeader();
		});
	</script>
</body>

</HTML>