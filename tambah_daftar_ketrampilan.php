<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Tambah Keterampilan Logbook Koas Pendidikan Dokter FK-UNDIP</title>
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
			if (!empty($_COOKIE['user']) and !empty($_COOKIE['pass']) and $_COOKIE['level'] == 2) {
				include "menu2.php";
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
						<h3 class="fw-bold fs-4 mb-3">TAMBAH DAFTAR KETRAMPILAN</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">TAMBAH DAFTAR KETRAMPILAN</h2>
						<br>
						<?php
						if (empty($_POST['submit'])) {
							echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
							echo "<center>";
							echo "<table class=\"table table-bordered\" style=\"width:75%\">";
							//Nama Ketrampilan
							echo "<tr class=\"table-primary\"  style=\"border-width: 1px; border-color: #000;\">";
							echo "<td style=\"width:30%\"><strong>Nama Ketrampilan</strong></td>";
							echo "<td style=\"width:70%\">";
							echo "<textarea name=\"ketrampilan\" rows=\"1\" class=\"form-control\" style=\"border:0.5px solid grey;border-radius:5px; font-weight:600;\" placeholder=\"Ketikkan nama ketrampilan\"></textarea>";
							echo "</td>";
							echo "</tr>";
							//Level Ketrampilan SKDI
							echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
							echo "<td><strong>Level Ketrampilan <span class=\"text-danger\">(SKDI)</span> <strong> </td>";
							echo "<td>";
							echo "<select class=\"form-select\" name=\"skdi_level\" id=\"skdi_level\" style=\"border:0.5px solid grey;border-radius:5px; font-weight:600;\">";
							echo "<option value=\"-\"> - </option>";
							echo "<option value=\"1\">Level 1</option>";
							echo "<option value=\"2\">Level 2</option>";
							echo "<option value=\"3\">Level 3</option>";
							echo "<option value=\"3A\">Level 3A</option>";
							echo "<option value=\"3B\">Level 3B</option>";
							echo "<option value=\"4A\">Level 4A</option>";
							echo "<option value=\"MKM\">Masalah Kesehatan Masyarakat</option>";
							echo "<option value=\"U\">Ujian</option>";
							echo "</select>";
							echo "</td>";
							echo "</tr>";
							//Level Ketrampilan Kemenkes
							echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
							echo "<td><strong>Level Ketrampilan (Kepmenkes) <span class=\"text-danger\">(Kepmenkes)</span> </td>";
							echo "<td>";
							echo "<select class=\"form-select\" name=\"k_level\" id=\"k_level\" style=\"border:0.5px solid grey;border-radius:5px; font-weight:600;\">";
							echo "<option value=\"-\"> - </option>";
							echo "<option value=\"1\">Level 1</option>";
							echo "<option value=\"2\">Level 2</option>";
							echo "<option value=\"3\">Level 3</option>";
							echo "<option value=\"3A\">Level 3A</option>";
							echo "<option value=\"3B\">Level 3B</option>";
							echo "<option value=\"4A\">Level 4A</option>";
							echo "<option value=\"MKM\">Masalah Kesehatan Masyarakat</option>";
							echo "<option value=\"U\">Ujian</option>";
							echo "</select>";
							echo "</td>";
							echo "</tr>";
							//Level Ketrampilan IPSG
							echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
							echo "<td><strong>Level Ketrampilan (IPSG) <span class=\"text-danger\">(IPSG)</span> </strong> </td>";
							echo "<td>";
							echo "<select class=\"form-select\" name=\"ipsg_level\" id=\"ipsg_level\" style=\"border:0.5px solid grey;border-radius:5px; font-weight:600;\">";
							echo "<option value=\"-\"> - </option>";
							echo "<option value=\"1\">Level 1</option>";
							echo "<option value=\"2\">Level 2</option>";
							echo "<option value=\"3\">Level 3</option>";
							echo "<option value=\"3A\">Level 3A</option>";
							echo "<option value=\"3B\">Level 3B</option>";
							echo "<option value=\"4A\">Level 4A</option>";
							echo "<option value=\"MKM\">Masalah Kesehatan Masyarakat</option>";
							echo "<option value=\"U\">Ujian</option>";
							echo "</select>";
							echo "</td>";
							echo "</tr>";
							//Level Ketrampilan Muatan Lokal
							echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
							echo "<td><strong>Level Ketrampilan (Muatan Lokal) <span class=\"text-danger\">(Muatan Lokal)</span></strong> </td>";
							echo "<td>";
							echo "<select class=\"form-select\" name=\"kml_level\" id=\"kml_level\" style=\"border:0.5px solid grey;border-radius:5px; font-weight:600;\">";
							echo "<option value=\"-\"> - </option>";
							echo "<option value=\"1\">Level 1</option>";
							echo "<option value=\"2\">Level 2</option>";
							echo "<option value=\"3\">Level 3</option>";
							echo "<option value=\"3A\">Level 3A</option>";
							echo "<option value=\"3B\">Level 3B</option>";
							echo "<option value=\"4A\">Level 4A</option>";
							echo "<option value=\"MKM\">Masalah Kesehatan Masyarakat</option>";
							echo "<option value=\"U\">Ujian</option>";
							echo "</select>";
							echo "</td>";
							echo "</tr>";
							//Stase
							echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
							echo "<td><strong>Kepaniteraan <span class=\"text-danger\">(STASE)</span><strong></td>";
							echo "<td>";
							$daftar_stase = mysqli_query($con, "SELECT * FROM `kepaniteraan` ORDER BY `id`");
							echo "<table class=\"table table-bordered\" style=\"width:100%;\">";
							echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
							<td colspan=2 align=center style=\"font-weight:600;\">Include</td><td align=center style=\"font-weight:600;\">Target <font style=\"color:red\">*</font></td></tr>";
							while ($data = mysqli_fetch_array($daftar_stase)) {
								echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
								$nama_cek = "include_" . $data['id'];
								$target = "target_" . $data['id'];
								echo "<td  align=center style=\"vertical-align:middle;width:5%;font-weight:600;\">
							<input type=\"checkbox\" style=\"width:40px; height:20px\" name=\"$nama_cek\" value=\"1\" id=\"$nama_cek\"></td>";
								echo "<td style=\"vertical-align:middle;width:80%; font-weight:600;\">$data[kepaniteraan]</td>";
								echo "<td style=\"vertical-align:middle;width:15%\">
								<input type=\"text\" class =\"form-control\" style=\"width:100%;text-align:center; border:2px solid black;border-radius:5px;\" name=\"$target\" id=\"$target\"></td>";
								echo "</tr>";
							}
							echo "</table>";
							echo "</td>";
							echo "</tr>";
							echo "</table>";
							echo "<br>";
							echo "<span style=\"font-size:1.0em; color:red; font-weight:600;\">Ctt: Bila tidak disi, maka target jumlah kegiatan adalah nol <span style=\"color:blue;\">(0)</span>.</span>";
							echo "<br><br>";
							echo "<button type=\"submit\" class=\"btn btn-success me-3\" name=\"submit\"  value=\"SUBMIT\"><i class=\"fa-solid fa-floppy-disk me-2\"></i> SUBMIT</button></form>";
							echo "</center>";
						}

						if ($_POST['submit'] == "SUBMIT") {
							$cek_id = 1;
							while ($cek_id != 0) {
								$random_id = rand(10000, 99999);
								$id_ketrampilan = "TAM" . $random_id;
								$cek_id = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `daftar_ketrampilan` WHERE `id`='$id_ketrampilan'"));
							}
							$daftar_stase = mysqli_query($con, "SELECT * FROM `kepaniteraan` ORDER BY `id`");
							while ($data = mysqli_fetch_array($daftar_stase)) {
								$nama_cek = "include_" . $data['id'];
								$target = "target_" . $data['id'];
								if ($_POST[$nama_cek] == '') $_POST[$nama_cek] = '0';
								if ($_POST[$target] == '') $_POST[$target] = '0';
							}
							$insert_ketrampilan = mysqli_query($con, "INSERT INTO `daftar_ketrampilan`
				(`id`, `ketrampilan`, `skdi_level`, `k_level`,
					`ipsg_level`, `kml_level`,
					`include_M091`, `target_M091`,
					`include_M105`, `target_M105`,
					`include_M092`, `target_M092`,
					`include_M093`, `target_M093`,
					`include_M095`, `target_M095`,
					`include_M102`, `target_M102`,
					`include_M101`, `target_M101`,
					`include_M104`, `target_M104`,
					`include_M103`, `target_M103`,
					`include_M106`, `target_M106`,
					`include_M094`, `target_M094`,
					`include_M114`, `target_M114`,
					`include_M112`, `target_M112`,
					`include_M113`, `target_M113`,
					`include_M111`, `target_M111`,
					`include_M121`, `target_M121`)
				VALUES
				('$id_ketrampilan','$_POST[ketrampilan]','$_POST[skdi_level]','$_POST[k_level]',
				'$_POST[ipsg_level]','$_POST[kml_level]',
				'$_POST[include_M091]', '$_POST[target_M091]',
				'$_POST[include_M105]', '$_POST[target_M105]',
				'$_POST[include_M092]', '$_POST[target_M092]',
				'$_POST[include_M093]', '$_POST[target_M093]',
				'$_POST[include_M095]', '$_POST[target_M095]',
				'$_POST[include_M102]', '$_POST[target_M102]',
				'$_POST[include_M101]', '$_POST[target_M101]',
				'$_POST[include_M104]', '$_POST[target_M104]',
				'$_POST[include_M103]', '$_POST[target_M103]',
				'$_POST[include_M106]', '$_POST[target_M106]',
				'$_POST[include_M094]', '$_POST[target_M094]',
				'$_POST[include_M114]', '$_POST[target_M114]',
				'$_POST[include_M112]', '$_POST[target_M112]',
				'$_POST[include_M113]', '$_POST[target_M113]',
				'$_POST[include_M111]', '$_POST[target_M111]',
				'$_POST[include_M121]', '$_POST[target_M121]')");
							echo "<center>";
							echo "<br>
							<span style=\"color:darkblue;font-weight:600;\">Nama keterampilan berhasil ditambahkan!<span><br>";
							echo "<br>";
							echo "<table class=\"table table-bordered\" style=\"width:auto;\">";
							echo "<thead  class=\"table-success\"><tr style =\"border-width: 1px; border-color: #000;\"><th colspan=\"2\" class=\"text-center\">DATA KETERAMPILAN</th></tr></thead>";
							echo "<tbody style =\"border-width: 1px; border-color: #000;\">";
							echo "<tr class=\"table-warning\" style =\"border-width: 1px; border-color: #000;\">";
							echo "<td style=\"width:30%;\"><strong>Nama Ketrampilan<strong></td>";
							echo "<td style=\"width:50%;font-weight:600;\">$_POST[ketrampilan]</td>";
							echo "</tr>";
							echo "<tr  class=\"table-warning\" style =\"border-width: 1px; border-color: #000;\">";
							echo "<td><strong>Level SKDI</strong></td>";
							echo "<td>$_POST[skdi_level]</td>";
							echo "</tr>";
							echo "<tr  class=\"table-warning\" style =\"border-width: 1px; border-color: #000;\">";
							echo "<td><strong>Level Kepmenkes</strong></td>";
							echo "<td>$_POST[k_level]</td>";
							echo "</tr>";
							echo "<tr  class=\"table-warning\" style =\"border-width: 1px; border-color: #000;\">";
							echo "<td><strong>Level IPSG</strong></td>";
							echo "<td>$_POST[ipsg_level]</td>";
							echo "</tr>";
							echo "<tr  class=\"table-warning\" style =\"border-width: 1px; border-color: #000;\">";
							echo "<td><strong>Level Muatan Lokal</strong></td>";
							echo "<td>$_POST[kml_level]</td>";
							echo "</tr>";
							echo "</tbody>";
							echo "<thead class=\"text-center\" ><tr class=\"table-success\" style =\"border-width: 1px; border-color: #000;\">
							<th colspan=2 align=center>KEPANITERAAN (STASE)</th></tr></thead>";
							echo "<tbody>";
							$no = 1;
							$daftar_stase = mysqli_query($con, "SELECT * FROM `kepaniteraan` ORDER BY `id`");
							while ($data = mysqli_fetch_array($daftar_stase)) {
								$nama_cek = "include_" . $data['id'];
								$target = "target_" . $data['id'];
								echo "<tr class=\"table-secondary\" style =\"border-width: 1px; border-color: #000;\">";
								echo "<td style=\"font-weight:600;\">$no. $data[kepaniteraan]</td>";
								if ($_POST[$nama_cek] == '1') echo "<td style=\"font-weight:600;\">Included, Target: $_POST[$target]</td>";
								else echo "<td style=\"font-weight:600; color:red;\"> - </td>";
								echo "</tr>";
								$no++;
							}
							echo "</tbody>";
							echo "</table>";
							echo "</center>";
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
	<script src="jquery.min.js"></script>

</body>

</html>