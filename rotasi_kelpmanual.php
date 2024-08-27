<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Rotasi Kelompok Tambahan Logbook Koas Pendidikan Dokter FK-UNDIP</title>
	<link rel="shortcut icon" type="x-icon" href="images/undipsolid.png">
	<link rel="stylesheet" type="text/css" href="jquery_ui/jquery-ui.css">
	<link rel="stylesheet" href="select2/dist/css/select2.css" />
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
						<h3 class="fw-bold fs-4 mb-3">ROTASI KEPANITERAAN (STASE)</h3>
						<br />
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color: #0a3967">
							ROTASI KELOMPOK KEPANITERAAN (STASE) - TAMBAHAN/PENGGANTI
						</h2>
						<br><br>
						<?php
						echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\" enctype=\"multipart/form-data\">";

						if (empty($_POST['submit'])) {
							$stase = mysqli_query($con, "SELECT * FROM `kepaniteraan` ORDER BY `id`");
						}
						?>
						<center>
							<div class="container">
								<div class="table-responsive">
									<table class="table table-bordered" style="width: auto;">
										<tr class="table-primary" style="border-width: 1px; border-color: #000;">
											<td style="width: 300px;">
												<strong>Kepaniteraan (STASE)</strong>
											</td>
											<td style="width: 500px;">
												<?php
												echo "<select class=\"form-select\" name=\"stase\" id=\"stase\" required>";
												echo "<option value=\"\">< Pilihan Kepaniteraan (Stase) ></option>";
												while ($dat_stase = mysqli_fetch_array($stase)) {
													echo "<option value=\"$dat_stase[id]\">$dat_stase[kepaniteraan] - (Semester: $dat_stase[semester] | Periode: $dat_stase[hari_stase] hari)</option>";
												}
												echo "</select>";
												?>
											</td>
										</tr>
										<tr class="table-success" style="border-width: 1px; border-color: #000;">
											<td>
												<strong>Rencana Tanggal Mulai <span class="text-danger">(yyyy-mm-dd)</span></strong>
											</td>
											<td>
												<?php
												echo "<input type=\"text\" id=\"input-tanggal\" class=\"form-select\" name=\"tgl_mulai\" placeholder=\"Tanggal Mulai\" required>";
												echo "<div id=\"tanggal\"></div>";
												echo "<div id=\"input_selesai\">";
												echo "<font style=\"font-size:0.8em; font-weight:700;\" class=\"text-black\">Edit Tanggal Selesai  <span class=\"text-danger\">(yyyy-mm-dd):</span></font>";
												echo "<p>";
												echo "</p>";
												echo "<input type=\"text\" id=\"input-selesai\" class=\"form-select\" name=\"tgl_selesai\" placeholder=\"Kosongi jika tidak ada perubahan!\">";
												echo "</div>";
												?>
											</td>
										</tr>
										<tr class="table-success" style="border-width: 1px; border-color: #000;">
											<td>
												<strong>Import Data Koas</strong>
											</td>
											<td>
												<?php
												echo "<input type=\"file\" class=\"form-control\" id=\"daftar_koas\" name=\"daftar_koas\" accept=\".csv\" required><br>";
												echo "<font style=\"font-size:0.8em; font-weight:700;\" class=\"text-danger\">Import file dalam format <i>csv</i> (*.csv) dengan separator ( , ) atau ( ; ) => no - nim - nama</font>";
												?>
											</td>
										</tr>
										<tr class="table-primary" style="border-width: 1px; border-color: #000;">
											<td>
												<strong>Separator file csv:<br><span class="text-danger" style="font-weight: 700;">koma --> ( , )</span> atau <span class="text-danger" style="font-weight: 700;">titik koma --> ( ; )</span></strong>
											</td>
											<td>
												<?php
												echo "<select class=\"form-select\" id=\"separator\" name=\"separator\" required>";
												echo "<option value=\"\">< Pilihan Separator ></option>";
												echo "<option value=\",\">Koma --> ( , )</option>";
												echo "<option value=\";\">Titik Koma --> ( ; )</option>";
												echo "</select>";
												?>
											</td>
										</tr>


									</table>
									<br>
									<button type="submit" class="btn btn-success" name="submit" value="SUBMIT">
										<i class="fa-solid fa-floppy-disk me-2"></i>SUBMIT
									</button>
								</div>
						</center>
						<?php
						if (!empty($_POST['submit'])) {
							$delete_dummy_mhsw = mysqli_query($con, "DELETE FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]'");
							$file = $_FILES['daftar_koas']['tmp_name'];
							$handle = fopen($file, "r");
							$separator = $_POST['separator'];
							$id = 0;
							while (($filesop = fgetcsv($handle, 1000, $separator)) !== false) {
								if ($id > 0 and $filesop[0] != "") {
									$nim = $filesop[1];
									$nama = $filesop[2];
									$insert_temp = mysqli_query($con, "INSERT INTO `daftar_koas_temp`
                (`id`, `nim`, `nama`, `username`)
                VALUES ('$id', '$nim', '$nama', '$_COOKIE[user]')");
								}
								if ($filesop[0] != "") $id++;
							}

							echo "<table class=\"table table-bordered\" style=\"width:75%\">";
							echo "<tr class=\"table-primary\">";
							echo "<td style=\" width:40%;\"><span style=\"font-size:1.0em;font-weight:600;\">Kepaniteraan (Stase)</span></td>";
							$stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kepaniteraan` WHERE `id`='$_POST[stase]'"));
							echo "<td style=\" width:60%; font-weight:600;\">$stase[kepaniteraan] (Semester: $stase[semester] | Periode: $stase[hari_stase] hari)</td>";
							echo "</tr>";
							$tanggal_mulai = tanggal_indo($_POST['tgl_mulai']);
							$hari_tambah = $stase['hari_stase'] - 1;
							$tambah_hari = '+' . $hari_tambah . ' days';
							$tgl_selesai = date('Y-m-d', strtotime($tambah_hari, strtotime($_POST['tgl_mulai'])));
							if (!empty($_POST['tgl_selesai'])) $tgl_selesai = $_POST['tgl_selesai'];
							$tanggal_selesai = tanggal_indo($tgl_selesai);
							echo "<tr class=\"table-success\">";
							echo "<td style=\"\"><span style=\"font-size:1.0em;font-weight:600;\">Tanggal Mulai Kepaniteraan (Stase)</span></td>";
							echo "<td style=\"font-weight:600;\">$tanggal_mulai</td>";
							echo "</tr>";
							echo "<tr class=\"table-primary\">";
							echo "<td style=\"\"><span style=\"font-size:1.0em;font-weight:600;\">Tanggal Selesai Kepaniteraan (Stase)</span></td>";
							echo "<td style=\"font-weight:600;\">$tanggal_selesai</td>";
							echo "</tr>";
							echo "</table><br><br>";

							$daftar_mhsw = mysqli_query($con, "SELECT * FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]' ORDER BY `nama`");
							echo "<h5>Daftar Mahasiswa Peserta Kepaniteraan (Stase)</h5>";
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze\">";
							echo "<thead style=\"border-width: 1px; border-color: #000;\">";
							echo "<tr class=\"table-primary\">";
							echo "<th style=\"width:7%;text-align:center;\">No</th>";
							echo "<th style=\"width:13%;text-align:center;\">NIM</th>";
							echo "<th style=\"width:55%;text-align:center;\">Nama</th>";
							echo "<th style=\"width:25%;text-align:center;\">Status</th>";
							echo "</tr>";
							echo "</thead>";
							echo "<tbody>";

							$no = 1;
							$kelas = "ganjil";
							while ($data_mhsw = mysqli_fetch_array($daftar_mhsw)) {
								echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
								echo "<td align=\"center\" style=\font-weight:600;>$no</td>";
								echo "<td style=\color:purple;font-weight:600;\">$data_mhsw[nim]</td>";
								echo "<td style=\color:darkblue;font-weight:600;\">$data_mhsw[nama]</td>";

								$stase_id = "stase_" . $_POST['stase'];
								$jml_akun = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `admin` WHERE `username`='$data_mhsw[nim]'"));
								$jml_mhsw = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `$stase_id` WHERE `nim`='$data_mhsw[nim]'"));
								if ($jml_akun >= 1) {
									echo "<td align=center><span style=\"color:darkgreen;font-weight:600;\">Terdaftar</span></td>";
									if ($jml_mhsw >= 1) {
										$status_stase_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT `status` FROM `$stase_id` WHERE `nim`='$data_mhsw[nim]'"));
										if ($status_stase_mhsw['status'] == '0') {
											$update_stase = mysqli_query($con, "UPDATE `$stase_id`
                        SET `rotasi`='9', `tgl_mulai`='$_POST[tgl_mulai]', `tgl_selesai`='$tgl_selesai', `status`='0'
                        WHERE `nim`='$data_mhsw[nim]'");
										} else {
											if ($_POST['tgl_mulai'] <= $tgl) {
												$update_stase = mysqli_query($con, "UPDATE `$stase_id`
                            SET `rotasi`='9', `tgl_mulai`='$_POST[tgl_mulai]', `tgl_selesai`='$tgl_selesai', `status`='1'
                            WHERE `nim`='$data_mhsw[nim]'");
											} else {
												$update_stase = mysqli_query($con, "UPDATE `$stase_id`
                            SET `rotasi`='9', `tgl_mulai`='$_POST[tgl_mulai]', `tgl_selesai`='$tgl_selesai', `status`='0'
                            WHERE `nim`='$data_mhsw[nim]'");
											}
										}
									} else {
										$insert_stase = mysqli_query($con, "INSERT INTO `$stase_id`
                    (`nim`, `rotasi`, `tgl_mulai`, `tgl_selesai`, `status`)
                    VALUES ('$data_mhsw[nim]', '9', '$_POST[tgl_mulai]', '$tgl_selesai', '0')");
									}
								} else {
									echo "<td align=center><span style=\"color:red;font-weight:600;\">Belum punya akun</span></td>";
								}
								echo "</tr>";
								if ($kelas == "ganjil") $kelas = "genap";
								else $kelas = "ganjil";
								$no++;
							}
							echo "</table>";
							$delete_dummy_mhsw = mysqli_query($con, "DELETE FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]'");
							echo "</form>";
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
	<script type="text/javascript" src="freezeheader/js/jquery.freezeheader.js"></script>
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
			$("#stase").select2({
				placeholder: "< Pilihan Kepaniteraan (Stase) >"
			});
			$("#separator").select2({
				placeholder: "< Pilihan Separator >",
				allowClear: true
			});
			$("#freeze").freezeHeader();



		});
	</script>
</body>

</HTML>