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
			if (!empty($_COOKIE['user']) and !empty($_COOKIE['pass']) and $_COOKIE['level'] == 4) {
				if ($_COOKIE['level'] == 4) {
					include "menu4.php";
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

			echo "<div class=\"text_header\">PENILAIAN BAGIAN / KEPANITERAAN (STASE)</div>";

			echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

			echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">PENILAIAN BAGIAN / KEPANITERAAN (STASE)</font></h4><br>";
			echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";

			echo "<table>";
			echo "<tr class=\"ganjil\">";
			echo "<td class=\"td_mid\">Kepaniteraan (stase)</td>";
			echo "<td class=\"td_mid\">";
			echo "<select class=\"select_artwide\" name=\"stase\" id=\"stase\">";
			$data_stase = mysqli_query($con, "SELECT * FROM `kepaniteraan` WHERE `id`!='M121' ORDER BY `id`");
			echo "<option value=\"\">< Pilihan Bagian / Kepaniteraan (Stase) ></option>";
			while ($data = mysqli_fetch_array($data_stase))
				echo "<option value=\"$data[id]\">$data[kepaniteraan]</option>";
			echo "<option value=\"M121_kompre\">Kepaniteraan Komprehensip</option>";
			echo "<option value=\"M121_kdk\">Kepaniteraan Kedokteran Keluarga</option>";
			echo "</select>";
			echo "</td>";
			echo "</tr>";
			echo "<tr class=\"ganjil\">";
			echo "<td class=\"td_mid\">Tanggal mulai isi penilaian (yyyy-mm-dd)</td><td class=\"td_mid\"><input type=\"text\" class=\"tanggal_mulai\" name=\"tanggal_mulai\" style=\"font-size:1em;font-family:TAHOMA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" /></td>";
			echo "</tr>";
			echo "<tr class=\"ganjil\">";
			echo "<td class=\"td_mid\">Tanggal selesai isi penilaian (yyyy-mm-dd)</td><td class=\"td_mid\"><input type=\"text\" class=\"tanggal_selesai\" name=\"tanggal_selesai\" style=\"font-size:1em;font-family:TAHOMA;padding:0 0 0 7px;height:27px;border:0.5px solid grey;border-radius:5px;\" /></td>";
			echo "</tr>";
			echo "<tr class=\"ganjil\">";
			echo "<td class=\"td_mid\">Status Approval</td>";
			echo "<td class=\"td_mid\">";
			echo "<select class=\"select_art\" name=\"approval\" id=\"approval\">";
			echo "<option value=\"all\">Semua Status</option>";
			echo "<option value=\"0\">Belum Disetujui</option>";
			echo "<option value=\"1\">Sudah Disetujui</option>";
			echo "</select>";
			echo "</td>";
			echo "</tr>";
			echo "<tr class=\"ganjil\">";
			echo "<td class=\"td_mid\">Nama/NIM Mahasiswa</td>";
			echo "<td class=\"td_mid\">";
			echo "<select class=\"select_artwide\" name=\"mhsw\" id=\"mhsw\" required>";
			$mhsw = mysqli_query($con, "SELECT `nim`,`nama` FROM `biodata_mhsw` ORDER BY `nama`");
			echo "<option value=\"all\">Semua Mahasiswa</option>";
			while ($data1 = mysqli_fetch_array($mhsw))
				echo "<option value=\"$data1[nim]\">$data1[nama] ($data1[nim])</option>";
			echo "</select>";
			echo "</td>";
			echo "</tr>";
			echo "</table>";
			echo "<br><br><input type=\"submit\" class=\"submit1\" name=\"submit\" value=\"SUBMIT\">";
			echo "</form>";


			if ($_POST[submit] == "SUBMIT") {
				$bag = $_POST[stase];
				$mulai = $_POST[tanggal_mulai];
				$selesai = $_POST[tanggal_selesai];
				$approval = $_POST[approval];
				$mhsw = $_POST[mhsw];

				if ($bag == "M091") {
					echo "
				<script>
					window.location.href=\"bag_ipd/penilaian_ipd_dosen.php?mulai=\"+\"$mulai\"+\"&selesai=\"+\"$selesai\"+\"&approval=\"+\"$approval\"+\"&mhsw=\"+\"$mhsw\";
				</script>
				";
				}

				if ($bag == "M092") {
					echo "
				<script>
					window.location.href=\"bag_neuro/penilaian_neuro_dosen.php?mulai=\"+\"$mulai\"+\"&selesai=\"+\"$selesai\"+\"&approval=\"+\"$approval\"+\"&mhsw=\"+\"$mhsw\";
				</script>
				";
				}

				if ($bag == "M093") {
					echo "
				<script>
					window.location.href=\"bag_psikiatri/penilaian_psikiatri_dosen.php?mulai=\"+\"$mulai\"+\"&selesai=\"+\"$selesai\"+\"&approval=\"+\"$approval\"+\"&mhsw=\"+\"$mhsw\";
				</script>
				";
				}

				if ($bag == "M094") {
					echo "
				<script>
					window.location.href=\"bag_ikfr/penilaian_ikfr_dosen.php?mulai=\"+\"$mulai\"+\"&selesai=\"+\"$selesai\"+\"&approval=\"+\"$approval\"+\"&mhsw=\"+\"$mhsw\";
				</script>
				";
				}

				if ($bag == "M095") {
					echo "
				<script>
					window.location.href=\"bag_ikmkp/penilaian_ikmkp_dosen.php?mulai=\"+\"$mulai\"+\"&selesai=\"+\"$selesai\"+\"&approval=\"+\"$approval\"+\"&mhsw=\"+\"$mhsw\";
				</script>
				";
				}

				if ($bag == "M096") {
					/*echo "
				<script>
					window.location.href=\"bag_ikmkp/penilaian_ikmkp_dosen.php?mulai=\"+\"$mulai\"+\"&selesai=\"+\"$selesai\"+\"&approval=\"+\"$approval\"+\"&mhsw=\"+\"$mhsw\";
				</script>
				";*/
					echo "
				<script>
					window.alert(\"Sorry ... under-construction!!\");
				</script>
				";
				}

				if ($bag == "M101") {
					echo "
				<script>
					window.location.href=\"bag_bedah/penilaian_bedah_dosen.php?mulai=\"+\"$mulai\"+\"&selesai=\"+\"$selesai\"+\"&approval=\"+\"$approval\"+\"&mhsw=\"+\"$mhsw\";
				</script>
				";
				}

				if ($bag == "M102") {
					echo "
				<script>
					window.location.href=\"bag_anestesi/penilaian_anestesi_dosen.php?mulai=\"+\"$mulai\"+\"&selesai=\"+\"$selesai\"+\"&approval=\"+\"$approval\"+\"&mhsw=\"+\"$mhsw\";
				</script>
				";
				}

				if ($bag == "M103") {
					echo "
				<script>
					window.location.href=\"bag_radiologi/penilaian_radiologi_dosen.php?mulai=\"+\"$mulai\"+\"&selesai=\"+\"$selesai\"+\"&approval=\"+\"$approval\"+\"&mhsw=\"+\"$mhsw\";
				</script>
				";
				}

				if ($bag == "M104") {
					echo "
				<script>
					window.location.href=\"bag_mata/penilaian_mata_dosen.php?mulai=\"+\"$mulai\"+\"&selesai=\"+\"$selesai\"+\"&approval=\"+\"$approval\"+\"&mhsw=\"+\"$mhsw\";
				</script>
				";
				}

				if ($bag == "M105") {
					echo "
				<script>
					window.location.href=\"bag_thtkl/penilaian_thtkl_dosen.php?mulai=\"+\"$mulai\"+\"&selesai=\"+\"$selesai\"+\"&approval=\"+\"$approval\"+\"&mhsw=\"+\"$mhsw\";
				</script>
				";
				}


				if ($bag == "M106") {
					echo "
				<script>
					window.location.href=\"bag_ikgm/penilaian_ikgm_dosen.php?mulai=\"+\"$mulai\"+\"&selesai=\"+\"$selesai\"+\"&approval=\"+\"$approval\"+\"&mhsw=\"+\"$mhsw\";
				</script>
				";
				}

				if ($bag == "M111") {
					echo "
				<script>
					window.location.href=\"bag_obsgyn/penilaian_obsgyn_dosen.php?mulai=\"+\"$mulai\"+\"&selesai=\"+\"$selesai\"+\"&approval=\"+\"$approval\"+\"&mhsw=\"+\"$mhsw\";
				</script>
				";
				}

				if ($bag == "M112") {
					echo "
				<script>
					window.location.href=\"bag_forensik/penilaian_forensik_dosen.php?mulai=\"+\"$mulai\"+\"&selesai=\"+\"$selesai\"+\"&approval=\"+\"$approval\"+\"&mhsw=\"+\"$mhsw\";
				</script>
				";
				}

				if ($bag == "M113") {
					echo "
				<script>
					window.location.href=\"bag_ika/penilaian_ika_dosen.php?mulai=\"+\"$mulai\"+\"&selesai=\"+\"$selesai\"+\"&approval=\"+\"$approval\"+\"&mhsw=\"+\"$mhsw\";
				</script>
				";
				}

				if ($bag == "M114") {
					echo "
				<script>
					window.location.href=\"bag_kulit/penilaian_kulit_dosen.php?mulai=\"+\"$mulai\"+\"&selesai=\"+\"$selesai\"+\"&approval=\"+\"$approval\"+\"&mhsw=\"+\"$mhsw\";
				</script>
				";
				}

				if ($bag == "M121_kdk") {
					echo "
				<script>
					window.location.href=\"bag_kdk/penilaian_kdk_dosen.php?mulai=\"+\"$mulai\"+\"&selesai=\"+\"$selesai\"+\"&approval=\"+\"$approval\"+\"&mhsw=\"+\"$mhsw\";
				</script>
				";
				}
				if ($bag == "M121_kompre") {
					echo "
				<script>
					window.location.href=\"bag_kompre/penilaian_kompre_dosen.php?mulai=\"+\"$mulai\"+\"&selesai=\"+\"$selesai\"+\"&approval=\"+\"$approval\"+\"&mhsw=\"+\"$mhsw\";
				</script>
				";
				}
			}

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
	<script type="text/javascript" src="jquery_ui/jquery-ui.js"></script>
	<!-- <script type="text/javascript">
		$(document).ready(function() {
			$("#stase").select2({
				placeholder: "< Pilihan Bagian / Kepaniteraan (Stase) >",
				allowClear: true
			});
			$("#approval").select2({
				placeholder: "< Pilihan Status Approval >",
				allowClear: true
			});
			$("#mhsw").select2({
				placeholder: "< Pilihan Mahasiswa >",
				allowClear: true
			});
			$('.tanggal_mulai').datepicker({
				dateFormat: 'yy-mm-dd'
			});
			$('.tanggal_selesai').datepicker({
				dateFormat: 'yy-mm-dd'
			});
		});
	</script> -->
</body>

</HTML>