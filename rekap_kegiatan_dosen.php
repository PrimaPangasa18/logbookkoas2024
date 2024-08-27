<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Rekap Kegiatan Logbook Koas Pendidikan Dokter FK-UNDIP</title>
	<link rel="shortcut icon" type="x-icon" href="images/undipsolid.png">
	<link rel="stylesheet" href="style/style1.css" />
	<link rel="stylesheet" href="style/buttonotoup2.css">
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
			if (!empty($_COOKIE['user']) and !empty($_COOKIE['pass']) and ($_COOKIE['level'] == 2 or $_COOKIE['level'] == 4 or $_COOKIE['level'] == 6)) {
				if ($_COOKIE['level'] == 2) {
					include "menu2.php";
				}
				if ($_COOKIE['level'] == 4) {
					include "menu4.php";
				}
				if ($_COOKIE['level'] == 6) {
					include "menu6.php";
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
			<main class="content px-3 py-4" style="background-image: url('images/undip_watermark_color.png'), url('images/undip_watermark_color.png'); ">
				<div class="container-fluid">
					<div class="mb-3">
						<h3 class="fw-bold fs-4 mb-3">REKAP KEGIATAN DOSEN/RESIDEN</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">REKAP KEGIATAN DOSEN/RESIDEN</h2>
						<br>
						<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
							<?php
							if ($_COOKIE['level'] == "2") {
								$dosen = $_GET['dosen'];
							} else {
								$dosen = $_COOKIE['user'];
							}
							$filter_dosen = "`dosen`='" . $dosen . "'";
							$stase = $_GET['stase'];
							if ($stase == "all") {
								$filter_stase = "";
							} else {
								$filter_stase = " AND `stase`='" . $stase . "'";
							}
							$appstatus = $_GET['appstatus'];
							if ($appstatus == "all") {
								$filter_status = "";
							} else {
								$filter_status = " AND `status`='" . $appstatus . "'";
							}
							$tgl_mulai = $_GET['tgl_mulai'];
							$tgl_akhir = $_GET['tgl_akhir'];
							$filter_tgl = " AND `tanggal` between '" . $tgl_mulai . "' AND '" . $tgl_akhir . "'";

							$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$dosen'"));
							if ($stase != "all") {
								$nama_stase = mysqli_fetch_array(mysqli_query($con, "SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$stase'"));
							}
							$tanggal_mulai = tanggal_indo($tgl_mulai);
							$tanggal_akhir = tanggal_indo($tgl_akhir);
							?>

							<center>
								<table class="table table-bordered" style="width: auto;">
									<tr class="table-primary" style="border-width: 1px; border-color: #000;">
										<td style="width: 300px;"><strong>Nama Dosen/Residen</strong></td>
										<td style="width: 500px; color:darkred; font-weight:700">&nbsp; <?php echo $data_dosen['nama'] . ', ' . $data_dosen['gelar']; ?></td>
									</tr>
									<tr class="table-success" style="border-width: 1px; border-color: #000;">
										<td style="width: 300px;"><strong>Username Dosen/Residen</strong></td>
										<td style="width: 500px; color:darkblue; font-weight:700">&nbsp; <?php echo $dosen; ?></td>
									</tr>
									<tr class="table-primary" style="border-width: 1px; border-color: #000;">
										<td style="width: 300px;"><strong>Kepaniteraan (STASE)</strong></td>
										<td style="width: 500px; color:darkgreen; font-weight:700">&nbsp;
											<?php echo $stase != "all" ? $nama_stase['kepaniteraan'] : "Semua kepaniteraan (stase)"; ?>
										</td>
									</tr>
									<tr class="table-success" style="border-width: 1px; border-color: #000;">
										<td style="width: 300px;"><strong>Periode Tanggal</strong></td>
										<td style="width: 500px; font-weight:700">&nbsp;
											<span style="color: purple;"><?php echo $tanggal_mulai; ?></span> s.d.
											<span style="color: purple;"><?php echo $tanggal_akhir; ?></span>
										</td>

									</tr>
								</table>
							</center>
							<br>
							<center>
								<span class="text-danger" style="font-weight: 600;">Untuk melihat rekap jurnal per kategori bisa menekan tombol dibawah</span>
								<br><br>
								<div class="container">
									<div class="row">
										<!-- Alert Info -->
										<div class="col-md-6">
											<div class="alert alert-info me-4" role="alert">
												<strong>Rekap Kegiatan Jurnal Penyakit:</strong>
												<br><br>
												<a href="#penyakit_nonbed" class="btn btn-primary mb-2 me-4">Kategori Non-Bedside Teaching</a>

												<a href="#penyakit_bed" class="btn btn-primary mb-2">Kategori Bedside Teaching</a>
											</div>
										</div>

										<!-- Alert Warning -->
										<div class="col-md-6">
											<div class="alert alert-warning me-4" role="alert">
												<strong>Rekap Kegiatan Jurnal Ketrampilan:</strong>
												<br><br>
												<a href="#ketrampilan_nonbed" class="btn btn-success mb-2 me-4">Kategori Non-Bedside Teaching</a>

												<a href="#ketrampilan_bed" class="btn btn-success mb-2">Kategori Bedside Teaching</a>
											</div>
										</div>
									</div>
								</div>
								<br>
							</center>

							<center>
								<span class="text-danger" style="display: block; margin-bottom: 15px; font-weight:600">JADWAL KELAS KEGIATAN:</span>
								<div class="alert alert-info" role="alert" style="width: 60%; margin: auto; padding: 15px; border-radius: 5px;">
									<strong style="font-weight: 700; display: block; margin-bottom: 10px;">Kelas Kegiatan:</strong>
									<?php
									$kelas = mysqli_query($con, "SELECT * FROM `kelas` ORDER BY `id`");
									$no = 1;
									while ($data_kelas = mysqli_fetch_array($kelas)) {
										// echo "<span style='font-weight: 500;'>$no. </span>";
										echo "<span style='font-weight: 500; color: darkgreen;'>Kelas {$data_kelas['kelas']} </span>: ";
										echo "<span style='color: darkblue; font-weight: 500;'>Jam mulai kegiatan: </span>";
										echo "<span style='color: black; font-weight: 500;'>[{$data_kelas['jam_mulai']} - {$data_kelas['jam_selesai']}]</span><br>";
										$no++;
									}
									?>
								</div>

								<br><br>
							</center>

							<!-- Kegiatan 1: Kategori Non-Bedside Teaching -->
							<center>
								<h4 style="font-size:1.5em;font-family:'Poppins', sans-serif;font-weight:800; color:darkgreen; margin-bottom:50px">REKAP KEGIATAN JURNAL PENYAKIT</h4>
							</center>
							<span id="penyakit_nonbed" class="text-danger" style="font-family:'Poppins', sans-serif; font-weight: 600;">Kegiatan Jurnal Penyakit Kategori Non-Bedside Teaching</span>
							<br>
							<?php
							$tgl_filter1 = "SELECT DISTINCT `tanggal` FROM `jurnal_penyakit` WHERE " . $filter_dosen . $filter_stase . $filter_status . $filter_tgl . " AND (`kegiatan`!='3' AND `kegiatan`!='4' AND `kegiatan`!='7') ORDER BY `tanggal`";
							$tgl_kegiatan1 = mysqli_query($con, $tgl_filter1);
							$jml_kegiatan1 = mysqli_num_rows($tgl_kegiatan1);
							?>
							<table style="width:100%;" id="freeze1" class="table table-bordered">
								<thead class="table-primary">
									<tr style="border-width: 1px; border-color: #000;">
										<th style="width:5%;text-align: center;">No</th>
										<th style="width:15%;text-align: center;">Tanggal</th>
										<th style="width:50%;text-align: center;">Kegiatan Dosen / Kegiatan Mahasiswa/i - Lokasi</th>
										<th style="width:15%;text-align: center;">Jumlah Koas</th>
										<th style="width:15%;text-align: center;">Rerata Waktu</th>
									</tr>
								</thead>
								<tbody class="table-warning" style="border-width: 1px; border-color: #000;">
									<?php
									$no = 1;
									if ($jml_kegiatan1 >= 1) {
										$baris = "ganjil";
										while ($data_tgl1 = mysqli_fetch_array($tgl_kegiatan1)) {
											$kelas_row = $baris == "ganjil" ? "ganjil" : "genap";
									?>
											<tr class="<?php echo $kelas_row; ?>" style="border-width: 1px; border-color: #000;">
												<td align="center" style="border-width: 1px; border-color: #000;"><strong><?php echo $no; ?></strong></td>
												<td style="border-width: 1px; border-color: #000; color:darkblue; font-weight:600"><?php echo tanggal_indo($data_tgl1['tanggal']); ?></td>
												<td colspan="3" style="border-width: 1px; border-color: #000; padding: 0;">
													<table class="tabel_normal" style="width:100%; border-collapse: collapse;">
														<?php
														$filter_kelas1 = "SELECT DISTINCT `kelas` FROM `jurnal_penyakit` WHERE " . $filter_dosen . $filter_stase . $filter_status . " AND `tanggal`='$data_tgl1[tanggal]' AND (`kegiatan`!='3' AND `kegiatan`!='4' AND `kegiatan`!='7') ORDER BY `kelas`";
														$kelas1 = mysqli_query($con, $filter_kelas1);
														while ($data_kelas1 = mysqli_fetch_array($kelas1)) {
															$kelas = mysqli_fetch_array(mysqli_query($con, "SELECT `kelas` FROM `kelas` WHERE `id`='$data_kelas1[kelas]'"));
															$filter_kegiatan1 = "SELECT DISTINCT `kegiatan`,`lokasi` FROM `jurnal_penyakit` WHERE " . $filter_dosen . $filter_stase . $filter_status . " AND `tanggal`='$data_tgl1[tanggal]' AND (`kegiatan`!='3' AND `kegiatan`!='4' AND `kegiatan`!='7') AND `kelas`='$data_kelas1[kelas]' ORDER BY `kegiatan`";
															$kegiatan1 = mysqli_query($con, $filter_kegiatan1);
															while ($data_kegiatan1 = mysqli_fetch_array($kegiatan1)) {
																$kegiatan = mysqli_fetch_array(mysqli_query($con, "SELECT `kegiatan`,`kegiatan_dosen` FROM `kegiatan` WHERE `id`='$data_kegiatan1[kegiatan]'"));
																$lokasi = mysqli_fetch_array(mysqli_query($con, "SELECT `lokasi` FROM `lokasi` WHERE `id`='$data_kegiatan1[lokasi]'"));
														?>
																<tr>
																	<td class="td_normal" style="border-bottom-width:1px"><b>&nbsp;<?php echo $kegiatan['kegiatan_dosen']; ?> - Kelas <?php echo $kelas['kelas']; ?></b><br>
																		<span style="font-weight: 500; color:purple">&nbsp;Kegiatan Mahasiswa:</span> <span style="font-weight: 500; color:brown"><?php echo $kegiatan['kegiatan']; ?></span>.<br>
																		<span style="font-weight: 500; color:darkgreen">&nbsp;Lokasi: <?php echo $lokasi['lokasi']; ?></span>.
																	</td>
																	<td class="td_normal" align="center" style=" color: red;font-weight: 500;width:18.75%; border-left-width:1px; border-bottom-width:1px">
																		<?php
																		$filter_koas = "SELECT `nim`,`jam_awal`,`jam_akhir` FROM `jurnal_penyakit` WHERE " . $filter_dosen . $filter_stase . $filter_status . " AND `tanggal`='$data_tgl1[tanggal]' AND `kegiatan`='$data_kegiatan1[kegiatan]' AND `kelas`='$data_kelas1[kelas]'";
																		$koas = mysqli_query($con, $filter_koas);
																		$jml_koas = mysqli_num_rows($koas);
																		echo $jml_koas . " mahasiswa/i";
																		?>
																	</td>
																	<td class="td_normal" align="center" style="font-weight: 500;width:18.75%; border-left-width:1px;  border-bottom-width:1px">
																		<?php
																		$tot_waktu = 0;
																		while ($data_koas = mysqli_fetch_array($koas)) {
																			$waktu_awal = strtotime($data_koas['jam_awal']);
																			$waktu_akhir = strtotime($data_koas['jam_akhir']);
																			$waktu = abs($waktu_akhir - $waktu_awal);
																			$tot_waktu = $tot_waktu + $waktu;
																		}
																		$rata_waktu = floor($tot_waktu / $jml_koas);
																		$rata_menit = number_format($rata_waktu / 60, 0);
																		echo $rata_menit . " menit";
																		?>
																	</td>
																</tr>
														<?php
															}
														}
														?>
													</table>
												</td>
											</tr>

										<?php
											$no++;
											$baris = $baris == "ganjil" ? "genap" : "ganjil";
										}
									} else {
										?>
										<tr>
											<td colspan="5" align="center" style="color: red; font-weight:500">
												<< E M P T Y>>
													<br>
											</td>
										</tr>
									<?php
									}
									?>
								</tbody>
							</table>
							<br>
							<hr style="border: 2px solid ; color:blue; margin: 20px 0;">
							<!-- Kegiatan 2: Kategori Bedside Teaching -->
							<span id="penyakit_bed" class="text-danger" style="font-family:'Poppins', sans-serif; font-weight: 600;">Kegiatan Jurnal Penyakit Kategori Bedside Teaching</span>
							<br>
							<?php
							$tgl_filter1 = "SELECT DISTINCT `tanggal` FROM `jurnal_penyakit` WHERE " . $filter_dosen . $filter_stase . $filter_status . $filter_tgl . " AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='7') ORDER BY `tanggal`";
							$tgl_kegiatan1 = mysqli_query($con, $tgl_filter1);
							$jml_kegiatan1 = mysqli_num_rows($tgl_kegiatan1);
							?>
							<table style="width:100%;" id="freeze2" class="table table-bordered">
								<thead class="table-success">
									<tr style="border-width: 1px; border-color: #000;">
										<th style="width:5%;text-align: center;">No</th>
										<th style="width:15%;text-align: center;">Tanggal</th>
										<th style="width:50%;text-align: center;">Kegiatan Dosen / Kegiatan Mahasiswa/i - Lokasi</th>
										<th style="width:15%;text-align: center;">Jumlah Koas</th>
										<th style="width:15%;text-align: center;">Rerata Waktu</th>
									</tr>
								</thead>
								<tbody class="table-warning" style="border-width: 1px; border-color: #000;">
									<?php
									$no = 1;
									if ($jml_kegiatan1 >= 1) {
										$baris = "ganjil";
										while ($data_tgl1 = mysqli_fetch_array($tgl_kegiatan1)) {
											$kelas_row = $baris == "ganjil" ? "ganjil" : "genap";
									?>
											<tr class="<?php echo $kelas_row; ?>" style="border-width: 1px; border-color: #000;">
												<td align="center" style="border-width: 1px; border-color: #000;"><strong><?php echo $no; ?></strong></td>
												<td style="border-width: 1px; border-color: #000; color:darkblue; font-weight:600"><?php echo tanggal_indo($data_tgl1['tanggal']); ?></td>
												<td colspan="3" style="border-width: 1px; border-color: #000; padding: 0;">
													<table class="tabel_normal" style="width:100%; border-collapse: collapse; ">
														<?php
														$filter_kelas1 = "SELECT DISTINCT `kelas` FROM `jurnal_penyakit` WHERE " . $filter_dosen . $filter_stase . $filter_status . " AND `tanggal`='$data_tgl1[tanggal]' AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='7') ORDER BY `kelas`";
														$kelas1 = mysqli_query($con, $filter_kelas1);
														while ($data_kelas1 = mysqli_fetch_array($kelas1)) {
															$kelas = mysqli_fetch_array(mysqli_query($con, "SELECT `kelas` FROM `kelas` WHERE `id`='$data_kelas1[kelas]'"));
															$filter_kegiatan1 = "SELECT DISTINCT `kegiatan`,`lokasi` FROM `jurnal_penyakit` WHERE " . $filter_dosen . $filter_stase . $filter_status . " AND `tanggal`='$data_tgl1[tanggal]' AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='7') AND `kelas`='$data_kelas1[kelas]' ORDER BY `kegiatan`";
															$kegiatan1 = mysqli_query($con, $filter_kegiatan1);
															while ($data_kegiatan1 = mysqli_fetch_array($kegiatan1)) {
																$kegiatan = mysqli_fetch_array(mysqli_query($con, "SELECT `kegiatan`,`kegiatan_dosen` FROM `kegiatan` WHERE `id`='$data_kegiatan1[kegiatan]'"));
																$lokasi = mysqli_fetch_array(mysqli_query($con, "SELECT `lokasi` FROM `lokasi` WHERE `id`='$data_kegiatan1[lokasi]'"));
														?>
																<tr>
																	<td class="td_normal" style="font-weight: 500; border-bottom-width:1px"><b>&nbsp;<?php echo $kegiatan['kegiatan_dosen']; ?> - Kelas <?php echo $kelas['kelas']; ?></b><br>
																		<span style="font-weight: 500; color:purple">&nbsp;Kegiatan Mahasiswa:</span> <span style="font-weight: 500; color:brown"><?php echo $kegiatan['kegiatan']; ?></span>.<br>
																		<span style=" font-weight: 500; color:darkgreen">&nbsp;Lokasi: <?php echo $lokasi['lokasi']; ?> </span>.
																	</td>
																	<td class="td_normal" align="center" style="color: red;font-weight: 500;width:18.75%; border-left-width:1px; border-bottom-width:1px">
																		<?php
																		$filter_koas = "SELECT `nim`,`jam_awal`,`jam_akhir` FROM `jurnal_penyakit` WHERE " . $filter_dosen . $filter_stase . $filter_status . " AND `tanggal`='$data_tgl1[tanggal]' AND `kegiatan`='$data_kegiatan1[kegiatan]' AND `kelas`='$data_kelas1[kelas]'";
																		$koas = mysqli_query($con, $filter_koas);
																		$jml_koas = mysqli_num_rows($koas);
																		echo $jml_koas . " mahasiswa/i";
																		?>
																	</td>
																	<td class="td_normal" align="center" style="font-weight: 500;width:18.75%;width:18.75%; border-left-width:1px; border-bottom-width:1px">
																		<?php
																		$tot_waktu = 0;
																		while ($data_koas = mysqli_fetch_array($koas)) {
																			$waktu_awal = strtotime($data_koas['jam_awal']);
																			$waktu_akhir = strtotime($data_koas['jam_akhir']);
																			$waktu = abs($waktu_akhir - $waktu_awal);
																			$tot_waktu = $tot_waktu + $waktu;
																		}
																		$rata_waktu = floor($tot_waktu / $jml_koas);
																		$rata_menit = number_format($rata_waktu / 60, 0);
																		echo $rata_menit . " menit";
																		?>
																	</td>
																</tr>
														<?php
															}
														}
														?>
													</table>
												</td>
											</tr>
										<?php
											$no++;
											$baris = $baris == "ganjil" ? "genap" : "ganjil";
										}
									} else {
										?>
										<tr>
											<td colspan="5" align="center" style="color: red; font-weight:500">
												<< E M P T Y>>
													<br>
											</td>
										</tr>
									<?php
									}
									?>
								</tbody>
							</table>
							<br>
							<hr style="border: 2px solid ; color:blue; margin: 20px 0;">
							<br>
							<center>
								<h4 style="font-size:1.5em;font-family:'Poppins', sans-serif;font-weight:800; color:darkgreen; margin-bottom:50px">REKAP KEGIATAN JURNAL KETERAMPILAN</h4>
							</center>
							<!-- Kegiatan 1: Kategori Non-Bedside Teaching -->
							<span id="penyakit_nonbed" class="text-danger" style="font-family:'Poppins', sans-serif; font-weight: 600;">Kegiatan Jurnal Ketrampilan Kategori Non-Bedside Teaching</span>
							<br>
							<?php
							$tgl_filter1 = "SELECT DISTINCT `tanggal` FROM `jurnal_ketrampilan` WHERE " . $filter_dosen . $filter_stase . $filter_status . $filter_tgl . " AND (`kegiatan`!='3' AND `kegiatan`!='4' AND `kegiatan`!='7') ORDER BY `tanggal`";
							$tgl_kegiatan1 = mysqli_query($con, $tgl_filter1);
							$jml_kegiatan1 = mysqli_num_rows($tgl_kegiatan1);
							?>
							<table style="width:100%;" id="freeze3" class="table table-bordered">
								<thead class="table-primary">
									<tr style="border-width: 1px; border-color: #000;">
										<th style="width:5%;text-align: center;">No</th>
										<th style="width:15%;text-align: center;">Tanggal</th>
										<th style="width:50%;text-align: center;">Kegiatan Dosen / Kegiatan Mahasiswa/i - Lokasi</th>
										<th style="width:15%;text-align: center;">Jumlah Koas</th>
										<th style="width:15%;text-align: center;">Rerata Waktu</th>
									</tr>
								</thead>
								<tbody class="table-warning" style="border-width: 1px; border-color: #000;">
									<?php
									$no = 1;
									if ($jml_kegiatan1 >= 1) {
										$baris = "ganjil";
										while ($data_tgl1 = mysqli_fetch_array($tgl_kegiatan1)) {
											$kelas_row = $baris == "ganjil" ? "ganjil" : "genap";
									?>
											<tr class="<?= $kelas_row ?>" style="border-width: 1px; border-color: #000;">
												<td align="center" style="border-width: 1px; border-color: #000;"><strong><?= $no ?></strong></td>
												<td style="border-width: 1px; border-color: #000; color:darkblue; font-weight:600">
													<?= tanggal_indo($data_tgl1['tanggal']) ?>
												</td>
												<td colspan="3" style="border-width: 1px; border-color: #000; padding: 0;">
													<table class="tabel_normal" style="width:100%; border-collapse: collapse;">
														<?php
														$filter_kelas1 = "SELECT DISTINCT `kelas` FROM `jurnal_ketrampilan` WHERE " . $filter_dosen . $filter_stase . $filter_status . " AND `tanggal`='$data_tgl1[tanggal]' AND (`kegiatan`!='3' AND `kegiatan`!='4' AND `kegiatan`!='7') ORDER BY `kelas`";
														$kelas1 = mysqli_query($con, $filter_kelas1);
														while ($data_kelas1 = mysqli_fetch_array($kelas1)) {
															$kelas = mysqli_fetch_array(mysqli_query($con, "SELECT `kelas` FROM `kelas` WHERE `id`='$data_kelas1[kelas]'"));
															$filter_kegiatan1 = "SELECT DISTINCT `kegiatan`,`lokasi` FROM `jurnal_ketrampilan` WHERE " . $filter_dosen . $filter_stase . $filter_status . " AND `tanggal`='$data_tgl1[tanggal]' AND (`kegiatan`!='3' AND `kegiatan`!='4' AND `kegiatan`!='7') AND `kelas`='$data_kelas1[kelas]' ORDER BY `kegiatan`";
															$kegiatan1 = mysqli_query($con, $filter_kegiatan1);
															while ($data_kegiatan1 = mysqli_fetch_array($kegiatan1)) {
																$kegiatan = mysqli_fetch_array(mysqli_query($con, "SELECT `kegiatan`,`kegiatan_dosen` FROM `kegiatan` WHERE `id`='$data_kegiatan1[kegiatan]'"));
																$lokasi = mysqli_fetch_array(mysqli_query($con, "SELECT `lokasi` FROM `lokasi` WHERE `id`='$data_kegiatan1[lokasi]'"));
														?>
																<tr>
																	<td class="td_normal" style=" font-weight: 500;border-bottom-width:1px"><b>&nbsp;<?= $kegiatan['kegiatan_dosen'] ?> - Kelas <?= $kelas['kelas'] ?></b><br>
																		<span style="font-weight: 500; color:purple">&nbsp;Kegiatan Mahasiswa:</span> <span style="font-weight: 500; color:brown"> <?= $kegiatan['kegiatan'] ?></span>.<br>
																		<span style=" font-weight: 500; color:darkgreen">&nbsp;Lokasi: <?= $lokasi['lokasi'] ?></span>.
																	</td>
																	<td class="td_normal" align="center" style="color:red;font-weight: 500;width:18.75%; border-left-width:1px; border-bottom-width:1px">
																		<?php
																		$filter_koas = "SELECT `nim`,`jam_awal`,`jam_akhir` FROM `jurnal_ketrampilan` WHERE " . $filter_dosen . $filter_stase . $filter_status . " AND `tanggal`='$data_tgl1[tanggal]' AND `kegiatan`='$data_kegiatan1[kegiatan]' AND `kelas`='$data_kelas1[kelas]'";
																		$koas = mysqli_query($con, $filter_koas);
																		$jml_koas = mysqli_num_rows($koas);
																		echo $jml_koas . " mahasiswa/i";
																		?>
																	</td>
																	<td class="td_normal" align="center" style="font-weight: 500;width:18.75%; border-left-width:1px; border-bottom-width:1px">
																		<?php
																		$tot_waktu = 0;
																		while ($data_koas = mysqli_fetch_array($koas)) {
																			$waktu_awal = strtotime($data_koas['jam_awal']);
																			$waktu_akhir = strtotime($data_koas['jam_akhir']);
																			$waktu = abs($waktu_akhir - $waktu_awal);
																			$tot_waktu = $tot_waktu + $waktu;
																		}
																		$jam = number_format(floor($tot_waktu / (60 * 60)), 2);
																		$menit = number_format(floor(($tot_waktu - $jam * (60 * 60)) / 60), 2);
																		$rata_waktu = floor($tot_waktu / $jml_koas);
																		$rata_jam = number_format(floor($rata_waktu / (60 * 60)), 2);
																		$rata_menit = number_format($rata_waktu / 60, 0);
																		echo $rata_menit . " menit";
																		?>
																	</td>
																</tr>
														<?php
															}
														}
														?>
													</table>
												</td>
											</tr>
										<?php
											$no++;
											$baris = ($baris == "ganjil") ? "genap" : "ganjil";
										}
									} else {
										?>
										<tr>
											<td colspan="5" align="center" style="color: red; font-weight:500">
												<< E M P T Y>>
											</td>
										</tr>
									<?php
									}
									?>
								</tbody>
							</table>
							<br>
							<hr style="border: 2px solid ; color:blue; margin: 20px 0;">
							<!-- Kegiatan 2: Kategori Bedside Teaching -->
							<span id="ketrampilan_bed" class="text-danger" style="font-family:'Poppins', sans-serif; font-weight: 600;">Kegiatan Jurnal Ketrampilan Bedside Teaching</span>
							<?php
							$tgl_filter1 = "SELECT DISTINCT `tanggal` FROM `jurnal_ketrampilan` WHERE " . $filter_dosen . $filter_stase . $filter_status . $filter_tgl . " AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='7') ORDER BY `tanggal`";
							$tgl_kegiatan1 = mysqli_query($con, $tgl_filter1);
							$jml_kegiatan1 = mysqli_num_rows($tgl_kegiatan1);

							?>
							<table style="width:100%" id="freeze4" class=" table table-bordered">
								<thead class="table-success">
									<tr style="border-width: 1px; border-color: #000;">
										<th style="width:5%;text-align: center;">No</th>
										<th style="width:15%;text-align: center;">Tanggal</th>
										<th style="width:50%;text-align: center;">Kegiatan Dosen / Kegiatan Mahasiswa/i - Lokasi</th>
										<th style="width:15%;text-align: center;">Jumlah Koas</th>
										<th style="width:15%;text-align: center;">Rerata Waktu</th>
									</tr>
								</thead>
								<tbody class="table-warning" style="border-width: 1px; border-color: #000;">
									<?php
									$no = 1;
									if ($jml_kegiatan1 >= 1) {
										$baris = "ganjil";
										while ($data_tgl1 = mysqli_fetch_array($tgl_kegiatan1)) {
											$kelas_row = $baris == "ganjil" ? "ganjil" : "genap";
									?>
											<tr class="<?= $kelas_row ?>" style="border-width: 1px; border-color: #000;">
												<td align="center" style="border-width: 1px; border-color: #000;"><strong><?= $no ?></strong></td>
												<td style="border-width: 1px; border-color: #000; color:darkblue; font-weight:600">
													<?= tanggal_indo($data_tgl1['tanggal']) ?>
												</td>
												<td colspan="3" style="border-width: 1px; border-color: #000; padding: 0;">
													<table class="tabel_normal" style=" width: 100%;font-weight: 500; border-collapse: collapse;">
														<?php
														$filter_kelas1 = "SELECT DISTINCT `kelas` FROM `jurnal_ketrampilan` WHERE " . $filter_dosen . $filter_stase . $filter_status . " AND `tanggal`='$data_tgl1[tanggal]' AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='7') ORDER BY `kelas`";
														$kelas1 = mysqli_query($con, $filter_kelas1);
														while ($data_kelas1 = mysqli_fetch_array($kelas1)) {
															$kelas = mysqli_fetch_array(mysqli_query($con, "SELECT `kelas` FROM `kelas` WHERE `id`='$data_kelas1[kelas]'"));
															$filter_kegiatan1 = "SELECT DISTINCT `kegiatan`,`lokasi` FROM `jurnal_ketrampilan` WHERE " . $filter_dosen . $filter_stase . $filter_status . " AND `tanggal`='$data_tgl1[tanggal]' AND (`kegiatan`='3' OR `kegiatan`='4' OR `kegiatan`='7') AND `kelas`='$data_kelas1[kelas]' ORDER BY `kegiatan`";
															$kegiatan1 = mysqli_query($con, $filter_kegiatan1);
															while ($data_kegiatan1 = mysqli_fetch_array($kegiatan1)) {
														?>
																<tr>
																	<td class="td_normal" style="border-bottom-width:1px"><b>&nbsp;<?= $kegiatan['kegiatan_dosen'] ?> - Kelas <?= $kelas['kelas'] ?></b><br>
																		<span style="font-weight: 500; color:purple">&nbsp;Kegiatan Mahasiswa:</span> <span style="font-weight: 500; color:brown"><?= $kegiatan['kegiatan'] ?></span>.<br>
																		<span style=" font-weight: 500; color:darkgreen">&nbsp;Lokasi: <?= $lokasi['lokasi'] ?></span>.
																	</td>
																	<td class="td_normal" align="center" style="color:red;font-weight: 500;width:18.75%;border-left-width:1px; border-bottom-width:1px">
																		<?= $jml_koas ?> mahasiswa/i
																	</td>
																	<td class="td_normal" align="center" style="font-weight: 500;width:18.75%;border-left-width:1px; border-bottom-width:1px">
																		<?php
																		$tot_waktu = 0;
																		while ($data_koas = mysqli_fetch_array($koas)) {
																			$waktu_awal = strtotime($data_koas['jam_awal']);
																			$waktu_akhir = strtotime($data_koas['jam_akhir']);
																			$waktu = abs($waktu_akhir - $waktu_awal);
																			$tot_waktu = $tot_waktu + $waktu;
																		}
																		$jam = number_format(floor($tot_waktu / (60 * 60)), 2);
																		$menit = number_format(floor(($tot_waktu - $jam * (60 * 60)) / 60), 2);
																		$rata_waktu = floor($tot_waktu / $jml_koas);
																		$rata_jam = number_format(floor($rata_waktu / (60 * 60)), 2);
																		$rata_menit = number_format($rata_waktu / 60, 0);
																		echo $rata_menit . " menit";
																		?>
																	</td>
																</tr>
														<?php
															}
														}
														?>
													</table>
												</td>
											</tr>
										<?php
											$no++;
											$baris = $baris == "ganjil" ? "genap" : "ganjil";
										}
									} else {
										?>
										<tr>
											<td colspan="5" align="center" style="color: red; font-weight:500">
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
			</main>
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
	<script type="text/javascript" src="freezeheader/js/jquery.freezeheader.js"></script>
	<script>
		$(document).ready(function() {
			$("#freeze1").freezeHeader();
			$("#freeze2").freezeHeader();
			$("#freeze3").freezeHeader();
			$("#freeze4").freezeHeader();
		});
	</script>
</body>

</html>