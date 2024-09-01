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
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">ROTASI INTERNAL KEPANITERAAN (STASE)</h2>
						<br><br>
						<?php
						$id_stase = $_GET['id'];
						$id_internal = "internal_" . $id_stase;
						$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
						$stase_id = "stase_" . $id_stase;
						$data_stase_mhsw = mysqli_query($con, "SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'");
						$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);

						$tgl_mulai = tanggal_indo($datastase_mhsw['tgl_mulai']);
						$tgl_selesai = tanggal_indo($datastase_mhsw['tgl_selesai']);

						$cek_internal = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `$id_internal` WHERE `nim`='$_COOKIE[user]'"));
						if ($cek_internal == 0) {
							$rotasi = mysqli_query($con, "SELECT * FROM `rotasi_internal` WHERE `stase`='$id_stase' ORDER BY `id`");
							$insert_internal = mysqli_query($con, "INSERT INTO `$id_internal` (`nim`) VALUES ('$_COOKIE[user]')");
							$i = 1;
							while ($data_rotasi = mysqli_fetch_array($rotasi)) {
								$rotasi_i = "rotasi" . $i;
								$update_internal = mysqli_query($con, "UPDATE `$id_internal` SET `$rotasi_i`='$data_rotasi[id]' WHERE `nim`='$_COOKIE[user]'");
								$i++;
							}
						}

						$rotasi_internal = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `$id_internal` WHERE `nim`='$_COOKIE[user]'"));
						$rotasi = mysqli_query($con, "SELECT * FROM `rotasi_internal` WHERE `stase`='$id_stase' ORDER BY `id`");
						?>
						<div class="table-responsive">
							<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
								<input type="hidden" name="id_stase" value="<?php echo $id_stase; ?>">
								<center>
									<table class="table table-bordered" style="width: 700px;">
										<tr class="table-primary" style="border-width: 1px; border-color: #000;">
											<td style=" width: 300px;"><strong>Kepaniteraan (STASE)</strong></td>
											<td style="width: 400px; color:darkgreen; font-weight:700">&nbsp; <?php echo $data_stase['kepaniteraan']; ?></td>
										</tr>
										<tr class="table-warning" style="border-width: 1px; border-color: #000;">
											<td style=" width: 300px;"><strong>Tanggal mulai kepaniteraan (STASE)</strong></td>
											<td style="width: 400px; color:darkblue; font-weight:700">&nbsp; <?php echo $tgl_mulai; ?></td>
										</tr>
										<tr class="table-warning" style="border-width: 1px; border-color: #000;">
											<td style=" width: 300px;"><strong>Tanggal selesai kepaniteraan (STASE)</strong></td>
											<td style="width: 400px; color:darkblue; font-weight:700">&nbsp; <?php echo $tgl_selesai; ?></td>
										</tr>
									</table>
								</center>
								<br>
								<table class="table table-bordered">
									<thead>
										<tr class="table-primary" style="border-width: 1px; border-color: #000;">
											<th class="text-center">No</th>
											<th class="text-center">Rotasi Internal</th>
											<th class="text-center">Lama Pelaksanaan</th>
											<th class="text-center">Tanggal Mulai</th>
											<th class="text-center">Tanggal Selesai</th>
											<th class="text-center">Status Approval</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$i = 1;
										$kelas = "ganjil";
										while ($data = mysqli_fetch_array($rotasi)) {
											echo "<tr class='table-warning'\"$kelas\" style='border-width: 1px; border-color: #000;'>";
											echo "<td align='center'style='font-weight:700;'>$i</td>";
											echo "<td style='font-weight:600;'>{$data['internal']}</td>";
											echo "<td align='center' style='font-weight:700; color:darkblue'>{$data['hari']} hari</td>";
											$tgl_i = "tgl" . $i;
											if (!is_null($rotasi_internal[$tgl_i])) {
												$tgl_mulai = tanggal_indo($rotasi_internal[$tgl_i]);
												echo "<td align='center'><font style='color:darkgreen ; font-weight:700'>$tgl_mulai</font></td>";

												$hari_tambah = $data['hari'] - 1;
												$tambah_hari = '+' . $hari_tambah . ' days';
												$tglselesai = date('Y-m-d', strtotime($tambah_hari, strtotime($rotasi_internal[$tgl_i])));
												$tgl_selesai = tanggal_indo($tglselesai);
												echo "<td align='center'><font style='color:darkgreen ; font-weight:700'>$tgl_selesai</font></td>";
											} else {
												echo "<td align='center'><font style='color:red ; font-weight:700'><< empty >></font></td>";
												echo "<td align='center'><font style='color:red; font-weight:700'><< empty >></font></td>";
											}
											$id_status = "status" . $i;
											$id_dosen = "dosen" . $i;
											if ($rotasi_internal[$id_status] == 0) {
												echo "<td align='center'>";
												if (!is_null($rotasi_internal[$id_dosen])) echo "<font style='color:red; font-weight:700'>Unapproved</font><p>";
												echo "<br>";
												echo "<a href='edit_rotasi_internal.php?stase=$id_stase&id_i=$i'><button type='button' class='btn btn-primary btn-sm' name='edit' value='EDIT'><i class='fa-solid fa-pen me-2'></i> EDIT</button></a>";
												if (!is_null($rotasi_internal[$id_dosen])) {
													$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip` LIKE '%$rotasi_internal[$id_dosen]%'"));
													echo "<br><br><span style='font-weight: 600;'>Approval oleh: </span>";
													echo "<br>";
													echo "<span style='font-weight: 600; color: darkblue;'>{$data_dosen['nama']}</span>, <span style='font-weight: 600; color: red;'>{$data_dosen['gelar']}</span><br><span style='font-weight: 600; color: darkgreen;'>({$data_dosen['nip']})</span>";
													echo "<p><a href='approve_internal.php?stase=$id_stase&id=$i&dosen={$rotasi_internal[$id_dosen]}'><button type='button' class='btn btn-success btn-sm' name='approve' value='APPROVE'><i class='fa-solid fa-notes-medical me-2'></i> APPROVE</button></a>";
												}
												echo "</td>";
											} else {
												echo "<td align='center'><font style='color:darkgreen; font-weight:700'>Approved</font>";
												echo "<p><a href='edit_rotasi_internal.php?stase=$id_stase&id_i=$i'><button type='button' class='btn btn-primary btn-sm' name='edit' value='EDIT'><i class='fa-solid fa-pen me-2'></i> EDIT</button></a></p>";
											}

											echo "</tr>";
											$i++;
											if ($kelas == "ganjil") $kelas = "genap";
											else $kelas = "ganjil";
										}
										?>
									</tbody>
								</table>
							</form>
						</div>

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
</body>

</html>