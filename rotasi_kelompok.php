<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Rotasi Kelompok Normal Logbook Koas Pendidikan Dokter FK-UNDIP</title>
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
						<h3 class="fw-bold fs-4 mb-3">ROTASI KEPANITERAAN (STASE)</h3>
						<br />
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color: #0a3967">
							ROTASI KELOMPOK KEPANITERAAN (STASE) - NORMAL
						</h2>
						<br><br>
						<?php
						echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\" enctype=\"multipart/form-data\">";

						if (empty($_POST['submit'])) {
							$stase = mysqli_query($con, "SELECT * FROM `kepaniteraan` ORDER BY `id`");
						}
						?>
						<div class="container">
							<center>
								<div class="table-responsive">
									<table class="table table-bordered" style="width: auto;">
										<tr class="table-primary" style="border-width: 1px; border-color: #000;">
											<td style="width: 300px;">
												<strong>Jenjang Semester Koas</strong>
											</td>
											<td style="width: 500px;">
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
										<tr class="table-success" style="border-width: 1px; border-color: #000;">
											<td>
												<strong>Import Data Koas</strong>
											</td>
											<td>
												<?php
												echo "<input type=\"file\" class=\"form-control\" id=\"daftar_koas\" name=\"daftar_koas\" accept=\".csv\" required ><br>";
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
									<div id="stase"></div>
									<br>
									<button type="submit" class="btn btn-success" name="submit" value="SUBMIT"><i class="fa-solid fa-floppy-disk me-2"></i>SUBMIT</button>
								</div>
							</center>
						</div>
						<?php
						if (!empty($_POST['submit'])) {
							$delete_dummy_mhsw = mysqli_query($con, "DELETE FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]'");
							$file = $_FILES['daftar_koas']['tmp_name'];
							$handle = fopen($file, "r");
							$separator = $_POST['separator'];
							$id = 0;
							while (($filesop = fgetcsv($handle, 1000, $separator)) !== false) {
								if ($id > 0 && $filesop[0] != "") {
									$nim = $filesop[1];
									$nama = $filesop[2];
									$insert_temp = mysqli_query($con, "INSERT INTO `daftar_koas_temp` (`id`, `nim`, `nama`, `username`) VALUES ('$id', '$nim', '$nama', '$_COOKIE[user]')");
								}
								if ($filesop[0] != "") $id++;
							}

							echo "<table class=\"table table-bordered\" style=\"border-width: 1px; border-color: #000;\">";
							echo "<tr class=\"table-primary\">";
							echo "<td style=\"width:40%;\"><span style=\"font-size:1.0em;font-weight:600;\">Rotasi stase semester</span></td>";
							echo "<td style=\"width:60%; font-weight:600;\">$_POST[semester]</td>";
							echo "</tr>";
							echo "<tr class=\"table-success\">";
							echo "<td style=\"\"><span style=\"font-size:1.0em;font-weight:600;\">Jumlah rotasi stase</span></td>";
							echo "<td style=\"font-weight:600;\">$_POST[jml_stase]</td>";
							echo "</tr>";
							echo "<tr class=\"table-primary\">";
							echo "<td colspan=\"2\" style=\"\"><span style=\"font-size:1.0em;font-weight:600;\">Urutan rotasi kepaniteraan (stase):</span></td>";
							echo "</tr>";
							$no = 1;
							$tgl_selesai_stase = "2000-01-01";
							while ($no <= $_POST['jml_stase']) {
								$stase = $_POST['stase' . $no];
								$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kepaniteraan` WHERE `id`='$stase'"));
								$pekan_stase = $data_stase['hari_stase'] / 7;
								$tgl_mulai_stase = $_POST['tgl_mulai' . $no];
								$tglmulai_stase = tanggal_indo($tgl_mulai_stase);
								$hari_tambah = $data_stase['hari_stase'] - 1;
								$tambah_hari = '+' . $hari_tambah . ' days';
								$tgl_selesai_stase = date('Y-m-d', strtotime($tambah_hari, strtotime($tgl_mulai_stase)));
								if (!empty($_POST['tgl_selesai' . $no])) $tgl_selesai_stase = $_POST['tgl_selesai' . $no];
								$tglselesai_stase = tanggal_indo($tgl_selesai_stase);

								echo "<tr class=\"table-success\" style=\"width:75%\">";
								echo "<td style=\"\"><span style=\"font-size:1.0em;font-weight:600;\">Urutan ke-<span style=\"color:red;\">$no</span></span></td>";
								if ($stase != "") {
									echo "<td style=\"font-weight:600;\"><b><span style=\"color:darkgreen;\">$data_stase[kepaniteraan]</span> - Periode: <span style=\"color:darkred;\">$pekan_stase pekan</span> ($data_stase[hari_stase] hari)</b><br>";
									echo "<span style=\"color:darkblue;\">Mulai tanggal: $tglmulai_stase<br>";
									echo "Selesai tanggal: $tglselesai_stase</span></td>";

									$data_mhsw_koas = mysqli_query($con, "SELECT * FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]'");
									while ($data_mhsw = mysqli_fetch_array($data_mhsw_koas)) {
										$stase_id = "stase_" . $stase;
										$jml_akun = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `admin` WHERE `username`='$data_mhsw[nim]'"));
										$jml_mhsw = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `$stase_id` WHERE `nim`='$data_mhsw[nim]'"));

										if ($jml_akun >= 1) {
											if ($jml_mhsw >= 1) {
												$status_stase_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT `status` FROM `$stase_id` WHERE `nim`='$data_mhsw[nim]'"));
												if ($status_stase_mhsw['status'] == '0') {
													$update_stase = mysqli_query($con, "UPDATE `$stase_id` SET `rotasi`='$no', `tgl_mulai`='$tgl_mulai_stase', `tgl_selesai`='$tgl_selesai_stase', `status`='0' WHERE `nim`='$data_mhsw[nim]'");
												} else {
													if ($tgl_mulai_stase <= $tgl) {
														$update_stase = mysqli_query($con, "UPDATE `$stase_id` SET `rotasi`='$no', `tgl_mulai`='$tgl_mulai_stase', `tgl_selesai`='$tgl_selesai_stase', `status`='1' WHERE `nim`='$data_mhsw[nim]'");
													} else {
														$update_stase = mysqli_query($con, "UPDATE `$stase_id` SET `rotasi`='$no', `tgl_mulai`='$tgl_mulai_stase', `tgl_selesai`='$tgl_selesai_stase', `status`='0' WHERE `nim`='$data_mhsw[nim]'");
													}
												}
											} else {
												$insert_stase = mysqli_query($con, "INSERT INTO `$stase_id` (`nim`, `rotasi`, `tgl_mulai`, `tgl_selesai`, `status`) VALUES ('$data_mhsw[nim]', '$no', '$tgl_mulai_stase', '$tgl_selesai_stase', '0')");
											}
										}
									}
								} else {
									echo "<td style=\"\"><span style=\"color:red;font-weight:600;\"><< BELUM TERJADWAL >></font></td>";
								}

								echo "</tr>";
								$no++;
							}
							echo "</table><br><br>";

							echo "<h5>Daftar Mahasiswa Peserta Rotasi Kepaniteraan (Stase)</h5>";
							echo "<table class=\"table table-bordered\" style=\"width:100%\" id=\"freeze\">";
							echo "<thead style=\"border-width: 1px; border-color: #000;\">";
							echo "<tr class=\"table-primary\">";
							echo "<th style=\"width:7%; text-align:center;\">No</th>";
							echo "<th style=\"width:13%;text-align:center;\">NIM</th>";
							echo "<th style=\"width:55%;text-align:center;\">Nama</th>";
							echo "<th style=\"width:25%;text-align:center;\">Status</th>";
							echo "</tr>";
							echo "</thead>";

							$daftar_koas = mysqli_query($con, "SELECT * FROM `daftar_koas_temp` WHERE `username`='$_COOKIE[user]' ORDER BY `nama`");
							$kelas = "ganjil";
							$no = 1;
							while ($dat_ko = mysqli_fetch_array($daftar_koas)) {
								echo "<tr class=\"$kelas table-warning\" style=\"border-width: 1px; border-color: #000;\">";
								echo "<td align=\"center\" style=\font-weight:600;\">$no</td>";
								echo "<td style=\"color:purple;font-weight:600;\">$dat_ko[nim]</td>";
								echo "<td style=\"color:darkblue;font-weight:600;\">$dat_ko[nama]</td>";
								$jml_akun = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `admin` WHERE `username`='$dat_ko[nim]'"));
								if ($jml_akun >= 1) {
									echo "<td align=center><span style=\"color:darkgreen;font-weight:600;\">Terdaftar</span></td>";
								} else {
									echo "<td align=center><span style=\"color:red;font-weight:600;\">Belum punya akun</span></td>";
								}
								echo "</tr>";
								$kelas = ($kelas == "genap") ? "ganjil" : "genap";
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

			$('#input-tanggal').datepicker({
				dateFormat: 'yy-mm-dd'
			});

			$('#semester').change(function() {
				var smt = $(this).val();
				$.ajax({
					type: 'POST',
					url: 'semester_stase.php',
					data: 'semester=' + smt,
					success: function(response) {
						$('#stase').html(response);
					}
				});
			});

			$("#semester").select2({
				placeholder: "< Pilihan Semester >",
				allowClear: true
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