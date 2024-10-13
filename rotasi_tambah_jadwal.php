<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Edit Rotasi Tambah Jadwal Koas Logbook Koas Pendidikan Dokter FK-UNDIP</title>
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
						<br />
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color: #0a3967">
							UPDATE DATA JADWAL KOAS
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

										<tr class="table-success" style="border-width: 1px; border-color: #000;">
											<td>
												<strong>Import Data Jadwal Koas</strong>
											</td>
											<td>
												<?php
												echo "<input type=\"file\" class=\"form-control\" id=\"daftar_koas\" name=\"daftar_koas\" accept=\".csv\" required ><br>";
												echo "<font style=\"font-size:0.8em; font-weight:700;\" class=\"text-danger\">Import file dalam format <i>csv</i> (*.csv) dengan separator ( , ) atau ( ; ) => <br> no - nim - angkatan - stase1 - mulai1 - selesai1 - stase2 - mulai2 - selesai2 - stase3 - mulai3 - selesai3 - stase4 - mulai4 - selesai4 - stase5 - mulai5 - selesai5 - stase6 - mulai6 - selesai6</font>";
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
							$truncate_table = mysqli_query($con, "TRUNCATE TABLE `update_jadwal_koas_temp`");
							$file = $_FILES['daftar_koas']['tmp_name'];
							$handle = fopen($file, "r");
							$separator = $_POST['separator'];
							$id = 0;
							while (($filesop = fgetcsv($handle, 1000, $separator)) !== false) {
								if ($id > 0 && $filesop[0] != "") {
									$no = $filesop[0];
									$nim = $filesop[1];
									$angkatan = $filesop[2];
									$stase1 = $filesop[3];
									$mulai1 = $filesop[4];
									$selesai1 = $filesop[5];
									$stase2 = $filesop[6];
									$mulai2 = $filesop[7];
									$selesai2 = $filesop[8];
									$stase3 = $filesop[9];
									$mulai3 = $filesop[10];
									$selesai3 = $filesop[11];
									$stase4 = $filesop[12];
									$mulai4 = $filesop[13];
									$selesai4 = $filesop[14];
									$stase5 = $filesop[15];
									$mulai5 = $filesop[16];
									$selesai5 = $filesop[17];
									$stase6 = $filesop[18];
									$mulai6 = $filesop[19];
									$selesai6 = $filesop[20];

									$insert_temp = mysqli_query($con, "INSERT INTO `update_jadwal_koas_temp` 
                                    (`no`, `nim`, `angkatan`, `stase1`, `mulai1`, `selesai1`, `stase2`, `mulai2`, `selesai2`, 
                                    `stase3`, `mulai3`, `selesai3`, `stase4`, `mulai4`, `selesai4`, `stase5`, `mulai5`, `selesai5`, 
                                    `stase6`, `mulai6`, `selesai6`) 
                                    VALUES ('$no', '$nim', '$angkatan', '$stase1', '$mulai1', '$selesai1', '$stase2', '$mulai2', '$selesai2', 
                                            '$stase3', '$mulai3', '$selesai3', '$stase4', '$mulai4', '$selesai4', '$stase5', '$mulai5', '$selesai5', 
                                            '$stase6', '$mulai6', '$selesai6')");
								}
								if ($filesop[0] != "") $id++;
							}
							echo "
                            <script>
                                window.location.href=\"update_tambah_jadwal.php\";
                            </script>
                            ";
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