<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Isi Jurnal Logbook Koas Pendidikan Dokter FK-UNDIP</title>
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
			<nav class="navbar navbar-expand px-4 py-3">
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
						<h3 class="fw-bold fs-4 mb-3">ISI LOGBOOK</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">ISI LOGBOOK (STASE)</h2>
						<br>
						<?php
						$id_stase = $_GET['id'];
						$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
						$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
						$stase_id = "stase_" . $id_stase;
						$data_stase_mhsw = mysqli_query($con, "SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'");
						$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);

						$tgl_mulai = tanggal_indo($datastase_mhsw['tgl_mulai']);
						$mulai = date_create($datastase_mhsw['tgl_mulai']);
						$tgl_selesai = tanggal_indo($datastase_mhsw['tgl_selesai']);
						$tgl_isi = tanggal_indo($tgl);
						$sekarang = date_create($tgl);
						$jmlhari_stase = $data_stase['hari_stase'];
						$hari_skrg = date_diff($mulai, $sekarang);
						$jmlhari_skrg = $hari_skrg->days + 1;
						?>
						<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
							<input type="hidden" name="id_stase" value="<?php echo $id_stase; ?>">
							<center>
								<table class="table table-bordered" style="width: auto;">
									<tr class="table-primary" style="border-width: 1px; border-color: #000;">
										<td style=" width: 300px;"><strong>Kepaniteraan (STASE)</strong></td>
										<td style="width: 500px; color:purple; font-weight:700">&nbsp; <?php echo $data_stase['kepaniteraan']; ?></td>
									</tr>
									<tr class="table-success" style="border-width: 1px; border-color: #000;">
										<td style=" width: 300px;"><strong>Tanggal mulai kepaniteraan (STASE)</strong></td>
										<td style="width: 500px; color:darkblue; font-weight:700">&nbsp; <?php echo $tgl_mulai; ?></td>
									</tr>
									<tr class="table-primary" style="border-width: 1px; border-color: #000;">
										<td style=" width: 300px;"><strong>Tanggal selesai kepaniteraan (STASE)</strong></td>
										<td style="width: 500px; color:darkblue; font-weight:700">&nbsp; <?php echo $tgl_selesai; ?></td>
									</tr>
									<tr class="table-success" style="border-width: 1px; border-color: #000;">
										<td style=" width: 300px;"><strong>Tanggal pengisian log book</strong></td>
										<td style="width: 500px; color:purple; font-weight:700">&nbsp; <?php echo $tgl_isi ?></td>
									</tr>
									<tr class="table-primary" style="border-width: 1px; border-color: #000;">
										<td style=" width: 300px;"><strong>Hari pengisian log book</strong></td>
										<td style="width: 500px; color:black; font-weight:700">&nbsp; Hari ke- <span class="text-danger"><?php echo $jmlhari_skrg ?></span> dari <span class="text-danger"><?php echo $jmlhari_stase ?></span> hari masa kepaniteraan (STASE)</td>
									</tr>
								</table>
							</center>
							<br>
							<center>
								<span class="text-danger" style="font-size: 0.9em; font-family:'Poppins', sans-serif; font-weight:600">Tekan tombol dibawah ini untuk melihat pengisian jurnal/evaluasi</span>
								<br><br>
								<a href="#penyakit" class="btn btn-success me-3">Pengisian Jurnal Penyakit</a>
								<a href="#trampil" class="btn btn-primary me-3">Pengisian Jurnal Ketrampilan Klinik</a>
								<br><br>
								<a href="#evaluasi" class="btn" style="background-color: purple; color: white;">Pengisian Evaluasi Diri dan Rencana Esok Hari</a>
								<br><br>
							</center>
							<a id="penyakit" style="font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;">Jurnal Penyakit</a><br><br>
							<?php
							$log_penyakit = mysqli_query($con, "SELECT * FROM `jurnal_penyakit` WHERE `stase`='$id_stase' AND `tanggal`='$tgl' AND `nim`='$_COOKIE[user]' ORDER BY `jam_awal`");
							?>
							<table style="width:100%;" id="freeze" class="table table-bordered">
								<thead class="table-primary">
									<tr style="border-width: 1px; border-color: #000;">
										<th style="width:8%;">Jam</th>
										<th style="width:12%;">Lokasi</th>
										<th style="width:25%;">Kegiatan (Level Penguasaan)</th>
										<th style="width:25%;">Penyakit (Level SKDI/<br>Kepmenkes/IPSG/Muatan Lokal)</th>
										<th style="width:20%;">Dosen/Residen</th>
										<th style="width:10%;">Status</th>
									</tr>
								</thead>
								<tbody class="table-warning" style="border-width: 1px; border-color: #000;">
									<?php
									$kelas = "ganjil";
									while ($data = mysqli_fetch_array($log_penyakit)) {
										$kelas_row = $kelas == "ganjil" ? "ganjil" : "genap";
									?>
										<tr class="<?php echo $kelas_row; ?>">
											<td style="font-weight: 500;"><?php echo $data['jam_awal'] . " - " . $data['jam_akhir']; ?></td>
											<?php
											$lokasi = mysqli_fetch_array(mysqli_query($con, "SELECT `lokasi` FROM `lokasi` WHERE `id`='$data[lokasi]'"));
											?>
											<td style="font-weight: 500; color:darkgreen"><?php echo $lokasi['lokasi']; ?></td>
											<?php
											$kegiatan = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kegiatan` WHERE `id`='$data[kegiatan]'"));
											?>
											<td style="font-weight: 500;">
												<span style="color: darkblue;"><?php echo $kegiatan['kegiatan']; ?></span>
												<span style="color: red;">(<?php echo $kegiatan['level']; ?>)</span>
											</td>
											<td style="font-weight: 500;">
												<?php
												$penyakit1 = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `daftar_penyakit` WHERE `id`='$data[penyakit1]'"));
												$penyakit1_kapital = strtoupper($penyakit1['penyakit']);
												?>
												<span style=" color:black;"><?php echo $penyakit1_kapital . " (Level: " . $penyakit1['skdi_level'] . "/" . $penyakit1['k_level'] . "/" . $penyakit1['ipsg_level'] . "/" . $penyakit1['kml_level'] . ")"; ?></span><br>
												<?php
												if ($data['penyakit2'] != "") {
													$penyakit2 = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `daftar_penyakit` WHERE `id`='$data[penyakit2]'"));
													$penyakit2_kapital = strtoupper($penyakit2['penyakit']);
												?>
													<span style="color:brown;"><?php echo $penyakit2_kapital . " (Level: " . $penyakit2['skdi_level'] . "/" . $penyakit2['k_level'] . "/" . $penyakit2['ipsg_level'] . "/" . $penyakit2['kml_level'] . ")"; ?></span><br>
												<?php
												}
												if ($data['penyakit3'] != "") {
													$penyakit3 = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `daftar_penyakit` WHERE `id`='$data[penyakit3]'"));
													$penyakit3_kapital = strtoupper($penyakit3['penyakit']);
												?>
													<span style="color:blue;"><?php echo $penyakit3_kapital . " (Level: " . $penyakit3['skdi_level'] . "/" . $penyakit3['k_level'] . "/" . $penyakit3['ipsg_level'] . "/" . $penyakit3['kml_level'] . ")"; ?></span><br>
												<?php
												}
												if ($data['penyakit4'] != "") {
													$penyakit4 = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `daftar_penyakit` WHERE `id`='$data[penyakit4]'"));
													$penyakit4_kapital = strtoupper($penyakit4['penyakit']);
												?>
													<span style="color:green;"><?php echo $penyakit4_kapital . " (Level: " . $penyakit4['skdi_level'] . "/" . $penyakit4['k_level'] . "/" . $penyakit4['ipsg_level'] . "/" . $penyakit4['kml_level'] . ")"; ?></span>
												<?php
												}
												?>
											</td>
											<?php
											$dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='$data[dosen]'"));
											?>
											<td style="font-weight: 500;">
												<span style="color: darkblue;"><?php echo $dosen['nama']; ?></span>,
												<span style="color: darkgreen;"><?php echo $dosen['gelar']; ?></span>
												<span style="color: darkred;">(<?php echo $dosen['nip']; ?>)</span>
												<br><br>

												<?php
												if ($data['status'] == '0') {
												?>
													<a href="approve.php?id=<?php echo $data['id']; ?>">
														<button type="button" class="btn btn-outline-success btn-sm" name="approve" value="APPROVE">
															<i class="fa-solid fa-notes-medical me-1"></i> APPROVE
														</button>
													</a>
												<?php
												}
												?>
											</td>
											<td align="center">
												<?php
												if ($data['status'] == '0') {
												?>
													<a href="edit_isi.php?id=<?php echo $data['id']; ?>">
														<button type="button" class="btn btn-warning btn-sm" name="edit" value="EDIT"><i class="fa-regular fa-pen-to-square me-2"></i>EDIT</button>
													</a><br><br>
													<a href="hapus_isi.php?id=<?php echo $data['id']; ?>">
														<button type="button" class="btn btn-danger btn-sm" name="hapus" value="HAPUS"><i class="fa-solid fa-trash me-2"></i>HAPUS</button>
													</a>

												<?php
												} else {
												?>
													<span style="color:green; font-weight:600">Approved</span>
												<?php
												}
												?>
											</td>
										</tr>
									<?php
										$kelas = $kelas == "ganjil" ? "genap" : "ganjil";
									}
									?>
								</tbody>
							</table>

							<br>
							<center>
								<span class="text-danger" style="font-size: 0.9em; font-family:'Poppins', sans-serif; font-weight:600">Klik Tambah untuk menambah Jurnal Penyakit</span><br><br>
								<a href="tambah_logbook.php?id=<?php echo $id_stase; ?>"><button type="button" class="btn btn-primary" name="tambah" value="TAMBAH"><i class="fa-solid fa-folder-plus me-2"></i>TAMBAH</button>
							</center>
							<br>
							<hr style="border: 2px solid ; margin: 20px 0;">
							<?php
							// Jurnal Ketrampilan Klinik
							?>
							<br>
							<a id="trampil" style="font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;">Jurnal Ketrampilan Klinik</a>
							<br><br>
							<?php
							$log_ketrampilan = mysqli_query($con, "SELECT * FROM `jurnal_ketrampilan` WHERE `stase`='$id_stase' AND `tanggal`='$tgl' AND `nim`='$_COOKIE[user]' ORDER BY `jam_awal`");
							?>
							<table class="table table-bordered" id="freeze1">
								<thead class="table-success">
									<tr style="border-width: 1px; border-color: #000;">
										<th style="width:8% ; text-align: center;">Jam</th>
										<th style="width:12% ; text-align: center;">Lokasi</th>
										<th style="width:25% ; text-align: center;">Kegiatan (Level Penguasaan)</th>
										<th style="width:25% ; text-align: center;">Ketrampilan (Level SKDI/<br>Kepmenkes/IPSG/Muatan Lokal)</th>
										<th style="width:20% ; text-align: center;">Dosen/Residen</th>
										<th style="width:10% ; text-align: center;">Status</th>
									</tr>
								</thead>
								<tbody style="border-width: 1px; border-color: #000;" class="table-warning">
									<?php
									$kelas = "ganjil";
									while ($data2 = mysqli_fetch_array($log_ketrampilan)) {
										$kelas_row = $kelas == "ganjil" ? "ganjil" : "genap";
									?>
										<tr class="<?php echo $kelas_row; ?>">
											<td style="font-weight: 600;"><?php echo htmlspecialchars($data2['jam_awal']) . " - " . htmlspecialchars($data2['jam_akhir']); ?></td>

											<?php
											$lokasi = mysqli_fetch_array(mysqli_query($con, "SELECT `lokasi` FROM `lokasi` WHERE `id`='{$data2['lokasi']}'"));
											?>
											<td style="font-weight: 600; color:darkgreen;"><?php echo htmlspecialchars($lokasi['lokasi']); ?></td>

											<?php
											$kegiatan = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kegiatan` WHERE `id`='{$data2['kegiatan']}'"));
											?>
											<td style="font-weight: 600;">
												<span style=" color: darkblue;"><?php echo htmlspecialchars($kegiatan['kegiatan']); ?></span>
												<span style="color: red;">(<?php echo htmlspecialchars($kegiatan['level']); ?>)</span>
											</td>

											<td style="font-weight: 600;">
												<?php
												$ketrampilan1 = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `daftar_ketrampilan` WHERE `id`='{$data2['ketrampilan1']}'"));
												$ketrampilan1_kapital = strtoupper($ketrampilan1['ketrampilan']);
												?>
												<span style="color:black;"><?php echo htmlspecialchars($ketrampilan1_kapital . " (Level: " . $ketrampilan1['skdi_level'] . "/" . $ketrampilan1['k_level'] . "/" . $ketrampilan1['ipsg_level'] . "/" . $ketrampilan1['kml_level'] . ")"); ?></span><br>

												<?php
												if ($data2['ketrampilan2'] != "") {
													$ketrampilan2 = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `daftar_ketrampilan` WHERE `id`='{$data2['ketrampilan2']}'"));
													$ketrampilan2_kapital = strtoupper($ketrampilan2['ketrampilan']);
												?>
													<span style="color:brown;"><?php echo htmlspecialchars($ketrampilan2_kapital . " (Level: " . $ketrampilan2['skdi_level'] . "/" . $ketrampilan2['k_level'] . "/" . $ketrampilan2['ipsg_level'] . "/" . $ketrampilan2['kml_level'] . ")"); ?></span><br>
												<?php
												}
												if ($data2['ketrampilan3'] != "") {
													$ketrampilan3 = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `daftar_ketrampilan` WHERE `id`='{$data2['ketrampilan3']}'"));
													$ketrampilan3_kapital = strtoupper($ketrampilan3['ketrampilan']);
												?>
													<span style="color:blue;"><?php echo htmlspecialchars($ketrampilan3_kapital . " (Level: " . $ketrampilan3['skdi_level'] . "/" . $ketrampilan3['k_level'] . "/" . $ketrampilan3['ipsg_level'] . "/" . $ketrampilan3['kml_level'] . ")"); ?></span><br>
												<?php
												}
												if ($data2['ketrampilan4'] != "") {
													$ketrampilan4 = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `daftar_ketrampilan` WHERE `id`='{$data2['ketrampilan4']}'"));
													$ketrampilan4_kapital = strtoupper($ketrampilan4['ketrampilan']);
												?>
													<span style="color:green;"><?php echo htmlspecialchars($ketrampilan4_kapital . " (Level: " . $ketrampilan4['skdi_level'] . "/" . $ketrampilan4['k_level'] . "/" . $ketrampilan4['ipsg_level'] . "/" . $ketrampilan4['kml_level'] . ")"); ?></span>
												<?php
												}
												?>
											</td>

											<?php
											$dosen = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `dosen` WHERE `nip`='{$data2['dosen']}'"));
											?>
											<td style="font-weight: 600;">
												<span style="color: darkblue;"><?php echo htmlspecialchars($dosen['nama']); ?></span>,
												<span style="color: darkgreen;"><?php echo htmlspecialchars($dosen['gelar']); ?></span>
												<span style="color: darkred;">(<?php echo htmlspecialchars($dosen['nip']); ?>)</span>
												<br><br>

												<?php
												if ($data2['status'] == '0') {
												?>
													<a href="approve2.php?id=<?php echo htmlspecialchars($data2['id']); ?>">
														<button type="button" class="btn btn-outline-success btn-sm">
															<i class="fa-solid fa-notes-medical me-1"></i> APPROVE
														</button>
													</a>
												<?php
												}
												?>
											</td>

											<td class="text-center">
												<?php
												if ($data2['status'] == '0') {
												?>
													<a href="edit_isi2.php?id=<?php echo htmlspecialchars($data2['id']); ?>">
														<button type="button" class="btn btn-warning btn-sm">
															<i class="fa-regular fa-pen-to-square me-2"></i> EDIT
														</button>
													</a><br><br>
													<a href="hapus_isi2.php?id=<?php echo htmlspecialchars($data2['id']); ?>">
														<button type="button" class="btn btn-danger btn-sm">
															<i class="fa-solid fa-trash me-2"></i> HAPUS
														</button>
													</a>
												<?php
												} else {
												?>
													<span style="color:green;">Approved</span>
												<?php
												}
												?>
											</td>
										</tr>
									<?php
										$kelas = $kelas == "ganjil" ? "genap" : "ganjil";
									}
									?>
								</tbody>
							</table>

							<br>
							<center>
								<span class="text-danger" style="font-size: 0.9em; font-family:'Poppins', sans-serif; font-weight:600">Klik Tambah untuk menambah Jurnal Keterampilan</span><br><br>
								<a href="tambah_logbook2.php?id=<?php echo $id_stase; ?>"><button type="button" class="btn btn-primary" name="tambah" value="TAMBAH"><i class="fa-solid fa-folder-plus me-2"></i>TAMBAH</button>
							</center>
							<br>
							<hr style="border: 2px solid ; margin: 20px 0;">
							<?php
							//Evaluasi Jurnal

							$jml_evaluasi = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `evaluasi` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase' AND `tanggal`='$tgl'"));
							if ($jml_evaluasi > 0) {
								$evaluasi = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `evaluasi` WHERE `nim`='$_COOKIE[user]' AND `stase`='$id_stase' AND `tanggal`='$tgl'"));
								$id_eval = $evaluasi['id'];
								$eval = $evaluasi['evaluasi'];
								$renc = $evaluasi['rencana'];
							} else {
								$id_eval = "0";
								$eval = "";
								$renc = "";
							}
							?>
							<!-- Evaluasi -->
							<center>
								<div class="container">
									<input type="hidden" name="id_eval" value="<?php echo htmlspecialchars($id_eval); ?>">

									<div class="alert alert-info" style="width:75%">
										<a id="evaluasi">
											<font style="font-size:1.2em;font-family:'Poppins', sans-serif;font-weight:800;">Evaluasi Diri dan Rencana Esok Hari</font>
										</a>
									</div>

									<div class="alert alert-warning" style="width:75%">
										<?php if ($id_eval == "0") : ?>
											<a>
												<font style="font-size:0.9em;font-family:'Poppins', sans-serif;font-weight:800; color:red">
													<span>Catatan Log-book Anda hari ini tidak akan masuk ke data base rekapitulasi<br>jika belum mengisi evaluasi diri dan rencana kegiatan esok hari</span>
												</font>
											</a>
											<br><br>
										<?php else : ?>
											<font style=" font-size:0.9em;font-family:'Poppins', sans-serif;font-weight:800; color:darkgreen">
												<span>Evaluasi diri dan rencana esok hari sudah diisi.<br>Anda bisa menggantinya dengan mengedit <span class="text-danger">text area di bawah</span> dan tekan tombol <span class="text-danger">ENTRY</span>.</span>
											</font>
											<br><br>
										<?php endif; ?>
									</div>
									<table border="1" style="width:75%">
										<tr>
											<td style="padding:5px">
												<font style="font-size:0.875em;font-weight:bold">Evaluasi Diri:</font>
												<br>
												<font style="font-size:0.75em">(maksimal 500 karakter, termasuk spasi)</font>
												<br>
												<textarea class="ckeditor" name="evaluasi" required autofocus="0"><?php echo htmlspecialchars($eval); ?></textarea>
												<br>
											</td>
										</tr>
										<tr>
											<td style="padding:5px">
												<font style="font-size:0.875em;font-weight:bold">Rencana Esok Hari:</font>
												<br>
												<font style="font-size:0.75em">(maksimal 500 karakter, termasuk spasi)</font>
												<br>
												<textarea class="ckeditor" name="rencana" required autofocus="0"><?php echo htmlspecialchars($renc); ?></textarea>
												<br>
											</td>
										</tr>
									</table>
									<tr>
										<br>
										<button type="submit" class="btn btn-success" name="entry" value="ENTRY"><i class="fa-solid fa-floppy-disk me-2"></i>SAVE</button>
										<br>
									</tr>
								</div>
						</form>
						</center>


						<?php
						if ($_POST['entry'] == "ENTRY") {
							$angkatan_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT `angkatan`,`grup` FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
							if ($_POST['id_eval'] == "0") {
								$insert_evaluasi = mysqli_query($con, "INSERT INTO `evaluasi`
					(	`nim`, `angkatan`,`grup`,`stase`, `tanggal`,
						`evaluasi`, `rencana`)
					VALUES
					(	'$_COOKIE[user]','$angkatan_mhsw[angkatan]','$angkatan_mhsw[grup]','$_POST[id_stase]','$tgl',
						'$_POST[evaluasi]','$_POST[rencana]')");
							} else {
								$update_evaluasi = mysqli_query($con, "UPDATE `evaluasi`
					SET `evaluasi`='$_POST[evaluasi]',`rencana`='$_POST[rencana]' WHERE `id`='$_POST[id_eval]'");
							}
							$update_penyakit = mysqli_query($con, "UPDATE `jurnal_penyakit`
				SET `evaluasi`='1' WHERE `nim`='$_COOKIE[user]' AND `tanggal`='$tgl'");
							$update_ketrampilan = mysqli_query($con, "UPDATE `jurnal_ketrampilan`
				SET `evaluasi`='1' WHERE `nim`='$_COOKIE[user]' AND `tanggal`='$tgl'");

							echo "
				<script>
					window.location.href=\"edit_logbook.php?id=\"+\"$_POST[id_stase]\";
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
	<script src="jquery.min.js"></script>
	<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="freezeheader/js/jquery.freezeheader.js"></script>
	<script>
		$(document).ready(function() {
			$("#freeze").freezeHeader();
			$("#freeze1").freezeHeader();
		});
	</script>
</body>

</html>