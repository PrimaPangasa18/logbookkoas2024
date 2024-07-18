<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Rotasi Stase Logbook Koas Pendidikan Dokter FK-UNDIP</title>
	<link rel="shortcut icon" type="x-icon" href="images/undipsolid.png">
	<link rel="stylesheet" href="style/style1.css" />
	<link rel="stylesheet" href="style/buttontopup.css">
	<link rel="stylesheet" href="mytable.css" type="text/css" media="screen" />

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
		// Menentukan path gambar
		$foto_path = "foto/" . $data_mhsw['foto'];
		$default_foto = "images/account.png";

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

					</div>
				</form>
				<div class="navbar-collapse collapse">
					<ul class="navbar-nav ms-auto">
						<li class="nav-item dropdown d-flex align-item-center">
							<span class="navbar-text me-2">Halo, <?php echo $nama . ' , <span class="gelar" style="color:red">' . $gelar . '</span>'; ?></span>
							<a href="#" class="nav-icon pe-md-0" data-bs-toggle="dropdown">
								<img src="<?php echo $foto_path; ?>" class="avatar img-fluid rounded-circle" alt="" />
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
			<?php
			echo "<div class=\"text_header\">ROTASI KEPANITERAAN (STASE)</div>";

			echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

			echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">ROTASI INDIVIDU KEPANITERAAN (STASE) - NORMAL</font></h4><br>";
			echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\" enctype=\"multipart/form-data\">";

			if (empty($_POST[submit])) {
				$stase = mysqli_query($con, "SELECT * FROM `kepaniteraan` ORDER BY `id`");
			}
			?>
			<table border="0">
				<tr class="ganjil">
					<td style="padding:5px 5px 5px 5px;width:300px;">
						<font style="font-size:1.0em">Nama Mahasiswa [NIM]</font>
					</td>
					<td style="padding:5px 5px 5px 5px">
						<?php
						echo "<select class=\"select_artwide\" name=\"nim\" id=\"nim\" required>";
						$data_nim = mysqli_query($con, "SELECT `nim`,`nama` FROM `biodata_mhsw` ORDER BY `nama`");
						echo "<option value=\"\">< Nama Mahasiswa ></option>";
						while ($data = mysqli_fetch_array($data_nim)) {
							echo "<option value=\"$data[nim]\">$data[nama] [NIM: $data[nim]]</option>";
						}
						echo "</select>";
						?>
					</td>
				</tr>
				<tr class="ganjil">
					<td style="padding:5px 5px 5px 5px">
						<font style="font-size:1.0em">Jenjang Semester Koas</font>
					</td>
					<td style="padding:5px 5px 5px 5px">
						<?php
						echo "<select class=\"select_artwide\" name=\"semester\" id=\"semester\" required>";
						echo "<option value=\"\">< Pilihan Semester ></option>";
						echo "<option value=\"9\">Semester IX (Sembilan)</option>";
						echo "<option value=\"10\">Semester X (Sepuluh)</option>";
						echo "<option value=\"11\">Semester XI (Sebelas)</option>";
						echo "<option value=\"12\">Semester XII (Dua belas)</option>";
						echo "</select>";
						?>
					</td>
				</tr>


			</table><br>
			<div id="stase">
			</div><br><br>
			<?php
			echo "<input type=\"submit\" class=\"submit1\" name=\"submit\" value=\"SUBMIT\">";


			if (!empty($_POST[submit])) {
				echo "<table border=\"0\">";
				echo "<tr class=\"ganjil\">";
				echo "<td style=\"padding:5px 5px 5px 5px;width:275px;\"><font style=\"font-size:1.0em\">Nama Mahasiswa [NIM]</font></td>";
				$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT `nim`,`nama` FROM `biodata_mhsw` WHERE `nim`='$_POST[nim]'"));
				echo "<td style=\"padding:5px 5px 5px 5px;width:700px;\">$data_mhsw[nama] [NIM: $data_mhsw[nim]]</td>";
				echo "</tr>";
				echo "<tr class=\"ganjil\">";
				echo "<td style=\"padding:5px 5px 5px 5px\"><font style=\"font-size:1.0em\">Rotasi stase semester</font></td>";
				echo "<td style=\"padding:5px 5px 5px 5px\">$_POST[semester]</td>";
				echo "</tr>";
				echo "<tr class=\"ganjil\">";
				echo "<td style=\"padding:5px 5px 5px 5px\"><font style=\"font-size:1.0em\">Jumlah rotasi stase</font></td>";
				echo "<td style=\"padding:5px 5px 5px 5px\">$_POST[jml_stase]</td>";
				echo "</tr>";
				echo "<tr class=\"ganjil\">";
				echo "<td colspan=\"2\" style=\"padding:5px 5px 5px 5px\"><font style=\"font-size:1.0em\">Urutan rotasi kepaniteraan (stase):</font></td>";
				echo "</tr>";
				$no = 1;
				$tgl_selesai_stase = "2000-01-01";
				while ($no <= $_POST[jml_stase]) {
					$stase = $_POST['stase' . $no];
					$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kepaniteraan` WHERE `id`='$stase'"));
					$pekan_stase = $data_stase[hari_stase] / 7;
					$tgl_mulai_stase = $_POST['tgl_mulai' . $no];
					$tglmulai_stase = tanggal_indo($tgl_mulai_stase);
					$hari_tambah = $data_stase['hari_stase'] - 1;
					$tambah_hari = '+' . $hari_tambah . ' days';
					$tgl_selesai_stase = date('Y-m-d', strtotime($tambah_hari, strtotime($tgl_mulai_stase)));
					if (!empty($_POST['tgl_selesai' . $no])) $tgl_selesai_stase = $_POST['tgl_selesai' . $no];
					$tglselesai_stase = tanggal_indo($tgl_selesai_stase);

					echo "<tr class=\"genap\">";
					echo "<td style=\"padding:5px 5px 5px 15px\"><font style=\"font-size:1.0em\">Urutan ke-$no</font></td>";
					if ($stase != "") {
						echo "<td style=\"padding:5px 5px 5px 5px\"><b>$data_stase[kepaniteraan] - Periode: $pekan_stase pekan ($data_stase[hari_stase] hari)</b><br>";
						echo "<i>Mulai tanggal: $tglmulai_stase<br>";
						echo "Selesai tanggal: $tglselesai_stase</i></td>";

						$stase_id = "stase_" . $stase;
						$jml_mhsw = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `$stase_id` WHERE `nim`='$data_mhsw[nim]'"));
						if ($jml_mhsw >= 1) {
							$status_stase_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT `status` FROM `$stase_id` WHERE `nim`='$data_mhsw[nim]'"));
							if ($status_stase_mhsw[status] == '0') {
								$update_stase = mysqli_query($con, "UPDATE `$stase_id`
								SET
								`rotasi`='$no',`tgl_mulai`='$tgl_mulai_stase',`tgl_selesai`='$tgl_selesai_stase',`status`='0'
								WHERE `nim`='$data_mhsw[nim]'");
							} else {
								if ($tgl_mulai_stase <= $tgl) {
									$update_stase = mysqli_query($con, "UPDATE `$stase_id`
									SET
									`rotasi`='$no',`tgl_mulai`='$tgl_mulai_stase',`tgl_selesai`='$tgl_selesai_stase',`status`='1'
									WHERE `nim`='$data_mhsw[nim]'");
								} else {
									$update_stase = mysqli_query($con, "UPDATE `$stase_id`
									SET
									`rotasi`='$no',`tgl_mulai`='$tgl_mulai_stase',`tgl_selesai`='$tgl_selesai_stase',`status`='0'
									WHERE `nim`='$data_mhsw[nim]'");
								}
							}
						} else {
							$insert_stase = mysqli_query($con, "INSERT INTO `$stase_id`
							( `nim`, `rotasi`,
								`tgl_mulai`, `tgl_selesai`, `status`)
							VALUES
							( '$data_mhsw[nim]','$no',
								'$tgl_mulai_stase','$tgl_selesai_stase','0')");
						}
					} else {
						echo "<td style=\"padding:5px 5px 5px 5px\"><font style=\"color:red\"><< BELUM TERJADWAL >></font></td>";
					}

					echo "</tr>";
					$no++;
				}
				echo "</table><br>";
			}


			echo "</form>";
			echo "</fieldset>";
			?>
			<!-- End Content -->
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
	<script type="text/javascript" src="jquery_ui/jquery-ui.js"></script>
	<script src="select2/dist/js/select2.js"></script>
	<!-- <script type="text/javascript">
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
				placeholder: "< Pilihan Semester >"
			});

			$("#nim").select2({
				placeholder: "< Nama Mahasiswa >"
			});




		});
	</script> -->
</body>

</HTML>