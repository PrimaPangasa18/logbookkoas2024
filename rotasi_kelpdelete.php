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

			echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">ROTASI KELOMPOK KEPANITERAAN (STASE) - HAPUS ROTASI</font></h4><br>";
			echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";

			$stase = mysqli_query($con, "SELECT * FROM `kepaniteraan` ORDER BY `id`");
			?>
			<table border="0">
				<tr class="ganjil">
					<td style="padding:5px 5px 5px 5px;width:300px;">
						<font style="font-size:1.0em">Kepaniteraan (Stase)</font>
					</td>
					<td style="padding:5px 5px 5px 5px">
						<?php
						echo "<select class=\"select_artwide\" name=\"stase\" id=\"stase\" required>";
						if (empty($_POST[stase]) and empty($_GET[get_stase])) echo "<option value=\"\">< Pilihan Kepaniteraan (Stase) ></option>";
						else {
							if (empty($_GET[get_stase])) {
								$stase_pilihan = mysqli_fetch_array(mysqli_query($con, "SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$_POST[stase]'"));
								echo "<option value=\"$_POST[stase]\">$stase_pilihan[kepaniteraan]</option>";
							}
							if (empty($_POST[stase])) {
								$stase_pilihan = mysqli_fetch_array(mysqli_query($con, "SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$_GET[get_stase]'"));
								echo "<option value=\"$_GET[get_stase]\">$stase_pilihan[kepaniteraan]</option>";
							}
						}
						while ($dat_stase = mysqli_fetch_array($stase)) {
							echo "<option value=\"$dat_stase[id]\">$dat_stase[kepaniteraan] - (Semester: $dat_stase[semester] | Periode: $dat_stase[hari_stase] hari)</option>";
						}
						echo "</select>";
						?>
					</td>
				</tr>

			</table><br><br>
			<input type="submit" class="submit1" name="submit" value="SUBMIT" />
			<?php
			if ($_POST[submit] == "SUBMIT" or $_GET[get_submit] == "SUBMIT") {
				if ($_POST[submit] == "SUBMIT") $stase_id = "stase_" . $_POST['stase'];
				if ($_GET[get_submit] == "SUBMIT") $stase_id = "stase_" . $_GET['get_stase'];
				$stase_all = mysqli_query($con, "SELECT DISTINCT `tgl_mulai`,`tgl_selesai` FROM `$stase_id` ORDER BY `tgl_mulai`");
				$no = 1;
				$kelas = "GANJIL";
				echo "<br><br><table style=\"width:100%\">";
				echo "<thead>";
				echo "<th style=\"width:5%\">No</th>";
				echo "<th style=\"width:25%\">Tanggal Mulai</th>";
				echo "<th style=\"width:25%\">Tanggal Selesai</th>";
				echo "<th style=\"width:20%\">Jumlah Peserta Koas</th>";
				echo "<th style=\"width:25%\">Status</th>";
				while ($data = mysqli_fetch_array($stase_all)) {
					echo "<tr class=$kelas>";
					echo "<td align=\"center\">$no</td>";
					$tanggal_mulai = tanggal_indo($data[tgl_mulai]);
					echo "<td align=\"center\">$tanggal_mulai</td>";
					$tanggal_selesai = tanggal_indo($data[tgl_selesai]);
					echo "<td align=\"center\">$tanggal_selesai</td>";
					$jml_mhsw = mysqli_num_rows(mysqli_query($con, "SELECT `nim` FROM `$stase_id` WHERE `tgl_mulai`='$data[tgl_mulai]'"));
					echo "<td align=\"center\">$jml_mhsw</td>";
					if (strtotime($tgl) > strtotime($data[tgl_mulai])) {
						if (strtotime($tgl) < strtotime($data[tgl_selesai])) {
							echo "<td align=\"center\"><font style=\"color:green\"><i>Sedang Berjalan</i></font></td>";
						}
						if (strtotime($tgl) > strtotime($data[tgl_selesai])) {
							echo "<td align=\"center\"><font style=\"color:blue\"><i>Sudah Lewat</i></font></td>";
						}
					} else {
						echo "<td align=\"center\"><font style=\"color:red\"><i>Belum Dimulai</i></font><br>";
						echo "<a href=\"rotasi_delete.php?stase=$_POST[stase]&tgl_mulai=$data[tgl_mulai]\"><input type=\"button\" name=\"delete\" style=\"color:red\" value=\"DELETE\"></a></td>";
					}
					echo "</tr>";
					if ($kelas == "GANJIL") $kelas = "GENAP";
					else $kelas = "GANJIL";
					$no++;
				}
				echo "</table>";
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
	<script src="select2/dist/js/select2.js"></script>
	<!-- <script type="text/javascript">
		$(document).ready(function() {
			$("#stase").select2({
				placeholder: "< Pilihan Kepaniteraan (Stase) >"
			});
		});
	</script> -->
</body>

</HTML>