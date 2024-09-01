<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Informasi Logbook Koas Pendidikan Dokter FK-UNDIP</title>
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
						<h3 class="fw-bold fs-4 mb-3">EDIT PENYAKIT</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">EDIT PENYAKIT</h2>
						<br>
						<?php
						echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
						echo "<input type=\"hidden\" name=\"id\" value=\"$_GET[id]\">";
						$data_penyakit = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `daftar_penyakit` WHERE `id`='$_GET[id]'"));
						echo "<center>";
						echo "<table class=\"table table-bordered\" style=\"width:75%\">";

						// Nama Penyakit
						echo "<tr class=\"table-primary\"  style=\"border-width: 1px; border-color: #000;\">";
						echo "<td  style=\"width:30%\"><strong>Nama Penyakit</strong></td>";
						echo "<td  style=\"width:70%\">";
						echo "<textarea name=\"penyakit\" rows=\"1\" class=\"form-control\" style=\"border:0.5px solid grey;border-radius:5px; font-weight:600;\">$data_penyakit[penyakit]</textarea>";
						echo "</td>";
						echo "</tr>";

						// Level Penyakit SKDI
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Level Penyakit <span class=\"text-danger\">(SKDI)</span> <strong></td>";
						echo "<td>";
						echo "<select class=\"form-select\" name=\"skdi_level\" id=\"skdi_level\" style=\"border:0.5px solid grey;border-radius:5px; font-weight:600;\">";
						if ($data_penyakit['skdi_level'] == '-') echo "<option value=\"-\"> - </option>";
						else echo "<option value=\"$data_penyakit[skdi_level]\">Level $data_penyakit[skdi_level]</option>";
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

						// Level Penyakit Kemenkes
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Level Penyakit <span class=\"text-danger\">(Kepmenkes)</span> </td>";
						echo "<td>";
						echo "<select class=\"form-select\" name=\"k_level\" id=\"k_level\" style=\"border:0.5px solid grey;border-radius:5px; font-weight:600;\">";
						if ($data_penyakit['k_level'] == '-') echo "<option value=\"-\"> - </option>";
						else echo "<option value=\"$data_penyakit[k_level]\">Level $data_penyakit[k_level]</option>";
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

						// Level Penyakit IPSG
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Level Penyakit <span class=\"text-danger\">(IPSG)</span> </strong></td>";
						echo "<td>";
						echo "<select class=\"form-select\" name=\"ipsg_level\" id=\"ipsg_level\" style=\"border:0.5px solid grey;border-radius:5px; font-weight:600;\">";
						if ($data_penyakit['ipsg_level'] == '-') echo "<option value=\"-\"> - </option>";
						else echo "<option value=\"$data_penyakit[ipsg_level]\">Level $data_penyakit[ipsg_level]</option>";
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

						// Level Penyakit Muatan Lokal
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Level Penyakit <span class=\"text-danger\">(Muatan Lokal)</span></strong> </td>";
						echo "<td>";
						echo "<select class=\"form-select\" name=\"kml_level\" id=\"kml_level\" style=\"border:0.5px solid grey;border-radius:5px; font-weight:600;\">";
						if ($data_penyakit['kml_level'] == '-') echo "<option value=\"-\"> - </option>";
						else echo "<option value=\"$data_penyakit[kml_level]\">Level $data_penyakit[kml_level]</option>";
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

						// Stase
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
							echo "<td align=center style=\"vertical-align:middle;width:5%;font-weight:600;\">";
							if ($data_penyakit[$nama_cek] == '1') {
								echo "<input type=\"checkbox\"  style=\"width:40px; height:20px\" name=\"$nama_cek\" value=\"1\" id=\"$nama_cek\" checked></td>";
								echo "<td style=\"vertical-align:middle;width:80%; font-weight:600;\">$data[kepaniteraan]</td>";
								echo "<td style=\"vertical-align:middle;width:15%; \">";
								echo "<input type=\"text\" class=\"form-control\" style=\"width:100%;text-align:center; border:2px solid black;border-radius:5px;\" name=\"$target\" id=\"$target\" value=\"$data_penyakit[$target]\"></td>";
							} else {
								echo "<input type=\"checkbox\" style=\"width:40px; height:20px\" name=\"$nama_cek\" value=\"1\" id=\"$nama_cek\"></td>";
								echo "<td style=\"vertical-align:middle;width:80%; font-weight:600;\">$data[kepaniteraan]</td>";
								echo "<td style=\"vertical-align:middle;width:15%\">";
								echo "<input type=\"text\" class=\"form-control\" style=\"width:100%;text-align:center; border:2px solid black;border-radius:5px;\" name=\"$target\" id=\"$target\"></td>";
							}
							echo "</tr>";
						}
						echo "</table>";
						echo "</td>";
						echo "</tr>";
						echo "</table>";
						echo "<br>";
						echo "<span style=\"font-size:1.0em; color:red; font-weight:600;\">Ctt: Bila tidak disi, maka target jumlah kegiatan adalah nol <span style=\"color:blue;\">(0)</span>.</span>";
						echo "<br>";
						echo "<br><br>";
						echo "<button type=\"submit\" class=\"btn btn-secondary me-3\" name=\"batal\"  value=\"BATAL\"><i class=\"fa fa-circle-xmark me-2\"></i> BATAL</button>";
						echo "<button type=\"submit\" class=\"btn btn-danger me-3\" name=\"hapus\" value=\"HAPUS\"><i class=\"fa fa-trash me-2\"></i> HAPUS</button>";
						echo "<button type=\"submit\" class=\"btn btn-success\" name=\"ubah\" value=\"UBAH\"><i class=\"fa fa-edit me-2\"></i> UBAH</button>";
						echo "</form>";
						echo "</center>";

						if ($_POST['batal'] == "BATAL") {
							echo "
			<script>
				window.location.href=\"cari_penyakit.php\";
			</script>
			";
						}

						if ($_POST['hapus'] == "HAPUS") {
							$delete_penyakit = mysqli_query($con, "DELETE FROM `daftar_penyakit` WHERE `id`='$_POST[id]'");
							echo "
			<script>
				window.location.href=\"cari_penyakit.php?status=HAPUS\";
			</script>
			";
						}

						if ($_POST['ubah'] == "UBAH") {
							$daftar_stase = mysqli_query($con, "SELECT * FROM `kepaniteraan` ORDER BY `id`");
							while ($data = mysqli_fetch_array($daftar_stase)) {
								$nama_cek = "include_" . $data['id'];
								$target = "target_" . $data['id'];
								if ($_POST[$nama_cek] == '') $_POST[$nama_cek] = '0';
								if ($_POST[$target] == '') $_POST[$target] = '0';
							}
							$update_penyakit = mysqli_query($con, "UPDATE `daftar_penyakit`
				SET
				`penyakit`='$_POST[penyakit]',`skdi_level`='$_POST[skdi_level]',`k_level`='$_POST[k_level]',
				`ipsg_level`='$_POST[ipsg_level]',`kml_level`='$_POST[kml_level]',
				`include_M091`='$_POST[include_M091]',`target_M091`='$_POST[target_M091]',
				`include_M105`='$_POST[include_M105]',`target_M105`='$_POST[target_M105]',
				`include_M092`='$_POST[include_M092]',`target_M092`='$_POST[target_M092]',
				`include_M093`='$_POST[include_M093]',`target_M093`='$_POST[target_M093]',
				`include_M095`='$_POST[include_M095]',`target_M095`='$_POST[target_M095]',
				`include_M102`='$_POST[include_M102]',`target_M102`='$_POST[target_M102]',
				`include_M101`='$_POST[include_M101]',`target_M101`='$_POST[target_M101]',
				`include_M104`='$_POST[include_M104]',`target_M104`='$_POST[target_M104]',
				`include_M103`='$_POST[include_M103]',`target_M103`='$_POST[target_M103]',
				`include_M106`='$_POST[include_M106]',`target_M106`='$_POST[target_M106]',
				`include_M094`='$_POST[include_M094]',`target_M094`='$_POST[target_M094]',
				`include_M114`='$_POST[include_M114]',`target_M114`='$_POST[target_M114]',
				`include_M112`='$_POST[include_M112]',`target_M112`='$_POST[target_M112]',
				`include_M113`='$_POST[include_M113]',`target_M113`='$_POST[target_M113]',
				`include_M111`='$_POST[include_M111]',`target_M111`='$_POST[target_M111]',
				`include_M121`='$_POST[include_M121]',`target_M121`='$_POST[target_M121]'
				WHERE `id`='$_POST[id]'");

							echo "
			<script>
				window.location.href=\"cari_penyakit.php?status=UBAH&id=\"+\"$_POST[id]\";
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
</body>

</html>