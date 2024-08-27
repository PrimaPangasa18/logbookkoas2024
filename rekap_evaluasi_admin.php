<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Rekap Individu Evaluasi Harian Logbook Koas Pendidikan Dokter FK-UNDIP</title>
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
						<h3 class="fw-bold fs-4 mb-3">REKAP INDIVIDU EVALUASI</h3>
						<br />
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color: #0a3967">
							REKAP INDIVIDU EVALUASI KEPANITERAAN (STASE)
						</h2>
						<br><br>
						<?php
						$mhsw_nim = $_GET['user_name'];
						$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$mhsw_nim'"));
						?>
						<center>
							<table class="table table-bordered" style="width: auto;">
								<tr class="table-primary" style="border-width: 1px; border-color: #000;">
									<td style="width: 300px;"><strong>Nama Mahasiswa: </strong></td>
									<td style="width: 300px; font-weight:600;color:darkblue"><?php echo $data_mhsw['nama']; ?></td>
								</tr>
								<tr class=" table-success" style="border-width: 1px; border-color: #000;">
									<td><strong>NIM: </strong></td>
									<td style="font-weight:600;color:darkred"><?php echo $data_mhsw['nim']; ?></td>
								</tr>
							</table>
						</center>
						<br>
						<table class=" table table-bordered">
							<?php
							$stase = mysqli_query($con, "SELECT * FROM `kepaniteraan` ORDER BY `id` ASC");

							$kelas_baris = "table-primary";
							$no = 1;

							while ($data_stase = mysqli_fetch_array($stase)) {
								$stase_id = "stase_" . $data_stase['id'];
								$jml_data = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `$stase_id` WHERE `nim`='$mhsw_nim'"));

								if ($no == 1) {
									echo "<tr class=\"$kelas_baris\" style=\"border-width: 1px; border-color: #000;\">";
								}

								if ($jml_data > 0) {
									$data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `$stase_id` WHERE `nim`='$mhsw_nim'"));
									$tglmulai = tanggal_indo($data['tgl_mulai']);
									$tglselesai = tanggal_indo($data['tgl_selesai']);

									if (strtotime($data['tgl_mulai']) <= strtotime($tgl) && strtotime($tgl) <= strtotime($data['tgl_selesai'])) {
										echo "<td align=\"center\"><a href=\"rekap_evalstase_admin.php?id={$data_stase['id']}&nim={$mhsw_nim}\"><button type=\"button\" class=\"btn btn-success btn-sm\"><i class=\"fa fa-hourglass-half me-2\"></i><font style=\"color:white; font-size:1.1em;\">{$data_stase['kepaniteraan']}</font></button></a></td>";
										echo "<td align=\"center\"><font style=\"color:green; font-size:1.1em;\">{$tglmulai} - {$tglselesai}<br>(Aktif)</font></td>";
										echo "<td align=\"center\"><font style=\"color:green; font-size:1.1em;\">Aktif</font></td>";
										$update = mysqli_query($con, "UPDATE `$stase_id` SET `status`='1' WHERE `nim`='$mhsw_nim'");
									} elseif (strtotime($tgl) > strtotime($data['tgl_selesai'])) {
										echo "<td align=\"center\"><a href=\"rekap_evalstase_admin.php?id={$data_stase['id']}&nim={$mhsw_nim}\"><button type=\"button\" class=\"btn btn-info btn-sm\"><i class=\"fa fa-check-circle me-2\"></i><font style=\"color:black;\">{$data_stase['kepaniteraan']}</font></button></a></td>";
										echo "<td align=\"center\"><font style=\"color:blue; font-size:1.1em;\">{$tglmulai} - {$tglselesai}<br>(Sudah Terlewat)</font></td>";
										echo "<td align=\"center\"><font style=\"color:blue; font-size:1.1em;\">Sudah Terlewat</font></td>";
										$update = mysqli_query($con, "UPDATE `$stase_id` SET `status`='2' WHERE `nim`='$mhsw_nim'");
									} elseif (strtotime($tgl) < strtotime($data['tgl_mulai'])) {
										echo "<td align=\"center\"><a href=\"rekap_evalstase_admin.php?id={$data_stase['id']}&nim={$mhsw_nim}\"><button type=\"button\" class=\"btn btn-danger btn-sm\"><i class=\"fa fa-times-circle me-2\"></i><font style=\"color:grey; font-size:1.1em;\">{$data_stase['kepaniteraan']}</font></button></a></td>";
										echo "<td align=\"center\"><font style=\"color:grey; font-size:1.1em;\">{$tglmulai} - {$tglselesai}<br>(Belum aktif)</font></td>";
										echo "<td align=\"center\"><font style=\"color:grey; font-size:1.1em;\">Belum aktif</font></td>";
										$update = mysqli_query($con, "UPDATE `$stase_id` SET `status`='0' WHERE `nim`='$mhsw_nim'");
									}
								} else {
									echo "<td align=\"center\"><a href=\"rekap_evalstase_admin.php?id={$data_stase['id']}&nim={$mhsw_nim}\"><button type=\"button\" class=\"btn btn-secondary btn-sm\"><i class=\"fa fa-times-circle me-2\"></i><font style=\"color:white;\">{$data_stase['kepaniteraan']}</font></button></a></td>";
									echo "<td align=\"center\"><font style=\"color:red; font-size:1.1em;\">-<br>(Belum terjadwal)</font></td>";
									echo "<td align=\"center\"><font style=\"color:red; font-size:1.1em;\">Belum terjadwal</font></td>";
								}

								if ($no == 2) {
									echo "</tr>";
									$kelas_baris = ($kelas_baris == "table-primary") ? "table-success" : "table-primary";
									$no = 0;
								}
								$no++;
							}

							if ($no == 2) echo "<td colspan=\"3\">&nbsp;</td></tr>";
							?>

						</table>
						<br>
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
</body>

</HTML>