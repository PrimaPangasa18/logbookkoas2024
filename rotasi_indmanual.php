<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Rotasi Individu Tambahan Logbook Koas Pendidikan Dokter FK-UNDIP</title>
	<link rel="shortcut icon" type="x-icon" href="images/undipsolid.png">
	<link rel="stylesheet" type="text/css" href="jquery_ui/jquery-ui.css">
	<link rel="stylesheet" href="select2/dist/css/select2.css" />
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
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">ROTASI INDIVIDU KEPANITERAAN (STASE) - TAMBAHAN/PENGGANTI</h2>
						<br>
						<?php
						echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\" enctype=\"multipart/form-data\">";

						$nim_mhsw = $_GET['user_name'];

						if (empty($_POST['submit']) and empty($_POST['batal']) and empty($_POST['simpan'])) {

						?>
							<center>
								<table class="table table-bordered" style="width: auto;">
									<tbody>
										<tr class="table-primary" style="border-width: 1px; border-color: #000;">
											<td style="width:300px;">
												<strong>Nama Mahasiswa <span class="text-danger">[NIM]</span></strong>
											</td>
											<td style="width:500px;">
												<?php
												$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT `nim`,`nama` FROM `biodata_mhsw` WHERE `nim`='$nim_mhsw'"));
												echo "<span style=\"color:blue; font-weight:600;\">$data_mhsw[nama]</span> - <span style=\"color:red; font-weight:600;\">$data_mhsw[nim]</span>";
												echo "<input type=\"hidden\" name=\"nim_mhsw\" value=\"$data_mhsw[nim]\" />";
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
										<tr class="table-primary">
											<td>
												<strong>Kepaniteraan (Stase)</strong>
											</td>
											<td>
												<?php
												echo "<select class=\"form-select\" name=\"stase\" id=\"stase\" required>";
												echo "<option value=\"\">< Pilihan Kepaniteraan (Stase) ></option>";
												echo "</select>";
												?>
											</td>
										</tr>
										<tr class="table-success">
											<td class="align-middle">
												<strong>Rencana Tanggal Mulai (<span class="text-danger">yyyy-mm-dd</span>)</strong>
											</td>
											<td>
												<?php
												echo "<input type=\"text\" id=\"input-tanggal\" class=\"form-select\" name=\"tgl_mulai\">";
												echo "<div id=\"tanggal\"></div>";
												echo "<div id=\"input_selesai\">";
												echo "<span>Edit Tanggal Selesai (yyyy-mm-dd):</span><p>";
												echo "</br>";
												echo "<input type=\"text\" id=\"input-selesai\" class=\"form-control\" name=\"tgl_selesai\" placeholder=\"Kosongi jika tidak ada perubahan!\">";
												echo "</div>";
												?>
											</td>
										</tr>
									</tbody>
								</table>
								<br><br>
								<button type="submit" class="btn btn-success" name="submit" value="SUBMIT">
									<i class="fas fa-save me-2"></i> SUBMIT
								</button>
							</center>
						<?php
						}
						?>
						<?php
						if (!empty($_POST['submit']) && empty($_POST['batal']) && empty($_POST['simpan'])) {
							// Fetch data
							$datamhsw = mysqli_fetch_array(mysqli_query($con, "SELECT `nim`,`nama` FROM `biodata_mhsw` WHERE `nim`='$_POST[nim_mhsw]'"));
							$datastase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kepaniteraan` WHERE `id`='$_POST[stase]'"));
							$pekan_stase = $datastase['hari_stase'] / 7;
							$tanggal_mulai = tanggal_indo($_POST['tgl_mulai']);
							$hari_tambah = $datastase['hari_stase'] - 1;
							$tambah_hari = '+' . $hari_tambah . ' days';
							$tgl_selesai = date('Y-m-d', strtotime($tambah_hari, strtotime($_POST['tgl_mulai'])));
							if (!empty($_POST['tgl_selesai'])) $tgl_selesai = $_POST['tgl_selesai'];
							$tanggal_selesai = tanggal_indo($tgl_selesai);
						?>
							<center>
								<table class="table table-bordered" style="width: auto;">
									<tr class="table-primary" style="border-width: 1px; border-color: #000;">
										<td style="width:300px; ">
											<span><strong>Nama Mahasiswa <span class="text-danger">[NIM]</span></strong></span>
										</td>
										<td style="width:500px;">
											&nbsp;
											<?php
											echo '<span style="color: blue; font-weight:600">' . htmlspecialchars($datamhsw['nama']) . '</span>';
											echo ' - <span style="color: red; font-weight:600"">' . htmlspecialchars($datamhsw['nim']) . '</span>';
											?>
											<input type="hidden" name="nim_mhsw" value="<?php echo htmlspecialchars($datamhsw['nim']); ?>" />
										</td>
									</tr>
									<tr class="table-success" style="border-width: 1px; border-color: #000;">
										<td>
											<span><strong>Semester Stase</strong></span>
										</td>
										<td>
											&nbsp;<span style="font-weight: 600; color:purple"><?php echo htmlspecialchars($_POST['semester']); ?></span>
										</td>
									</tr>
									<tr class="table-primary" style="border-width: 1px; border-color: #000;">
										<td>
											<span><strong>Kepaniteraan (STASE)</strong></span>
										</td>
										<td style="font-weight: 600;">
											&nbsp;<?php
													echo '<span style="color: darkgreen;">' . htmlspecialchars($datastase['kepaniteraan']) . '</span>';
													echo ' - Periode: ';
													echo '<span style="color: red;">' . htmlspecialchars($pekan_stase) . '</span>';
													echo ' pekan';
													echo ' (';
													echo '<span style="color: red;">' . htmlspecialchars($datastase['hari_stase']) . ' hari</span>';
													echo ')';
													?>

											<input type="hidden" name="stase" value="<?php echo htmlspecialchars($datastase['id']); ?>" />
										</td>
									</tr>
									<tr class="table-success" style="border-width: 1px; border-color: #000;">
										<td>
											<span><strong>Tanggal mulai kepaniteraan</strong></span>
										</td>
										<td>
											&nbsp;<span style="font-weight:600; color:darkblue"><?php echo htmlspecialchars($tanggal_mulai); ?></span>
											<input type="hidden" name="tglmulai" value="<?php echo htmlspecialchars($_POST['tgl_mulai']); ?>" />
										</td>
									</tr>
									<tr class="table-primary" style="border-width: 1px; border-color: #000;">
										<td>
											<span><strong>Tanggal selesai kepaniteraan</strong></span>
										</td>
										<td>
											&nbsp;<span style="font-weight: 600; color:darkblue"><?php echo htmlspecialchars($tanggal_selesai); ?></span>
											<input type="hidden" name="tglselesai" value="<?php echo htmlspecialchars($tgl_selesai); ?>" />
										</td>
									</tr>
								</table>
								<br><br>
								<button type="submit" class="btn btn-danger me-3" name="batal" value="BATAL">
									<i class="fa-solid fa-xmark me-2"></i>BATAL
								</button>
								&nbsp;&nbsp;
								<button type="submit" class="btn btn-success" name="simpan" value="SIMPAN">
									<i class="fa-solid fa-save me-2"></i>SIMPAN
								</button>

							</center>
						<?php
						}
						?>

						<?php
						if ($_POST['batal'] == "BATAL") {
							echo "
				<script>
					window.location.href=\"rotasi_indmanual_search.php\";
				</script>
				";
						}

						if ($_POST['simpan'] == "SIMPAN") {
							$stase = "stase_" . $_POST['stase'];
							$jmldata = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `$stase` WHERE `nim`='$_POST[nim_mhsw]'"));
							if ($jmldata >= 1) {
								$status_stase_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT `status` FROM `$stase` WHERE `nim`='$_POST[nim_mhsw]'"));
								if ($status_stase_mhsw['status'] == '0') {
									$update_stase = mysqli_query($con, "UPDATE `$stase`
						SET
						`rotasi`='9',`tgl_mulai`='$_POST[tglmulai]',
						`tgl_selesai`='$_POST[tglselesai]',`status`='0'
						WHERE `nim`='$_POST[nim_mhsw]'");
								} else {
									if ($_POST['tgl_mulai'] <= $tgl) {
										$update_stase = mysqli_query($con, "UPDATE `$stase`
							SET
							`rotasi`='9',`tgl_mulai`='$_POST[tglmulai]',
							`tgl_selesai`='$_POST[tglselesai]',`status`='1'
							WHERE `nim`='$_POST[nim_mhsw]'");
									} else {
										$update_stase = mysqli_query($con, "UPDATE `$stase`
							SET
							`rotasi`='9',`tgl_mulai`='$_POST[tglmulai]',
							`tgl_selesai`='$_POST[tglselesai]',`status`='0'
							WHERE `nim`='$_POST[nim_mhsw]'");
									}
								}
							} else {
								$insert_stase = mysqli_query($con, "INSERT INTO `$stase`
					( `nim`, `rotasi`,
						`tgl_mulai`, `tgl_selesai`, `status`)
					VALUES
					( '$_POST[nim_mhsw]','9',
						'$_POST[tglmulai]','$_POST[tglselesai]','0')");
							}
						?>
							<center>
								<table class="table table-bordered" style="width: auto;">
									<tbody>
										<tr class="table-primary" style="border-width: 1px; border-color: #000;">
											<td style="width:300px; "><strong>Nama Mahasiswa <span class="text-danger">[NIM] </span></strong></td>
											<?php
											$datamhsw = mysqli_fetch_array(mysqli_query($con, "SELECT `nim`,`nama` FROM `biodata_mhsw` WHERE `nim`='$_POST[nim_mhsw]'"));
											?>
											<td style="width:500px;">
												&nbsp;
												<?php
												echo '<span style="color: blue; font-weight:600">' . htmlspecialchars($datamhsw['nama']) . '</span>';
												echo ' - <span style="color: red; font-weight:600"">' . htmlspecialchars($datamhsw['nim']) . '</span>';
												?>
											</td>
										</tr>
										<?php
										$datastase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kepaniteraan` WHERE `id`='$_POST[stase]'"));
										?>
										<tr class="table-success" style="border-width: 1px; border-color: #000;">
											<td><strong>Semester Stase</strong></td>
											<td>&nbsp;<span style="font-weight: 600; color:purple"><?php echo htmlspecialchars($datastase['semester']); ?></span></td>
										</tr>
										<tr class="table-primary" style="border-width: 1px; border-color: #000;">
											<td><strong>Kepaniteraan (STASE)</strong></td>
											<?php
											$pekan_stase = $datastase['hari_stase'] / 7;
											?>
											<td style="font-weight: 600;">
												&nbsp;
												<?php
												echo '<span style="color: darkgreen;">' . htmlspecialchars($datastase['kepaniteraan']) . '</span>';
												echo ' - Periode: ';
												echo '<span style="color: red;">' . htmlspecialchars($pekan_stase) . '</span>';
												echo ' pekan';
												echo ' (';
												echo '<span style="color: red;">' . htmlspecialchars($datastase['hari_stase']) . ' hari</span>';
												echo ')';
												?>
											</td>
										</tr>
										<?php
										$tanggal_mulai = tanggal_indo($_POST['tglmulai']);
										?>
										<tr class="table-success" style="border-width: 1px; border-color: #000;">
											<td><strong>Tanggal mulai kepaniteraan</strong></td>
											<td>&nbsp;<span style="font-weight:600; color:darkblue"><?php echo htmlspecialchars($tanggal_mulai); ?></span></td>
										</tr>
										<?php
										$tanggal_selesai = tanggal_indo($_POST['tglselesai']);
										?>
										<tr class="table-primary" style="border-width: 1px; border-color: #000;">
											<td><strong>Tanggal selesai kepaniteraan</strong></td>
											<td>&nbsp;<span style="font-weight: 600; color:darkblue"><?php echo htmlspecialchars($tanggal_selesai); ?></span></td>
										</tr>
									</tbody>
								</table>
								<br><br>
								<span style="color:darkgreen; font-size:1.1em; ">
									<< Perubahan tersimpan!!>>
								</span>
								<br><br>
								<button type="submit" class="btn btn-primary me-3" name="kembali" value="KEMBALI">
									<i class="fa-solid fa-backward me-2"></i>KEMBALI
								</button>
							</center>
						<?php
						}
						echo "</form>";
						?>

						<?php
						if ($_POST['kembali'] == "KEMBALI") {
							echo "
				<script>
					window.location.href=\"rotasi_indmanual_search.php\";
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
	<script type="text/javascript" src="jquery.min.js"></script>
	<script type="text/javascript" src="jquery_ui/jquery-ui.js"></script>
	<script src="select2/dist/js/select2.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#input_selesai').hide();
			$('#input-tanggal').datepicker({
				dateFormat: 'yy-mm-dd'
			});
			$('#input-tanggal').change(function() {
				var tgl = $(this).val();
				var stase = $('#stase').val();
				$.ajax({
					type: 'POST',
					url: 'tanggal_view.php',
					data: {
						'tgl_mulai': tgl,
						'stase': stase
					},
					success: function(response) {
						$('#tanggal').html(response);
						$('#input_selesai').show();
					}
				});
			});
			$('#input-selesai').datepicker({
				dateFormat: 'yy-mm-dd'
			});
			$('#semester').change(function() {
				var smt = $(this).val();
				$.ajax({
					type: 'POST',
					url: 'semester_stase_manual.php',
					data: 'semester=' + smt,
					success: function(response) {
						$('#stase').html(response);
					}
				});
			});

			$("#semester").select2({
				placeholder: "< Pilihan Semester >"
			});

			$("#stase").select2({
				placeholder: "< Pilihan Kepaniteraan (Stase) >"
			});




		});
	</script>
</body>

</html>