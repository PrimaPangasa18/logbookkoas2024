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
			echo "<div class=\"text_header\">PENILAIAN BAGIAN / KEPANITERAAN (STASE) LOGBOOK</div>";
			echo "<br><br><br><fieldset class=\"fieldset_art\">
	    <legend align=left><font style=\"color:black;font-style:italic;font-size:0.825em;\">[user: $_COOKIE[nama], $_COOKIE[gelar]]</font></legend>";

			echo "<center><h4><font style=\"color:#006400;text-shadow:1px 1px black;\">PENILAIAN BAGIAN / KEPANITERAAN (STASE)</font></h4><br>";

			$sekarang = date_create($tgl);
			$stase = mysqli_query($con, "SELECT * FROM `kepaniteraan` ORDER BY `id` ASC");

			$kelas_baris = "ganjil";
			$no = 1;
			echo "<table border=\"1\">";
			while ($data_stase = mysqli_fetch_array($stase)) {
				$stase_id = "stase_" . $data_stase[id];
				$jml_data = mysqli_num_rows(mysqli_query($con, "SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'"));
				if ($no == 1) {
					echo "<tr class=\"$kelas_baris\">";
					$baris++;
				}
				if ($jml_data > 0) {
					$data = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'"));
					$tglmulai = tanggal_indo($data[tgl_mulai]);
					$tglselesai = tanggal_indo($data[tgl_selesai]);
					if ($data_stase[id] == "M091") echo "<td align=center style=\"width:400px\"><a href=\"bag_ipd/penilaian_ipd.php\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
					if ($data_stase[id] == "M092") echo "<td align=center style=\"width:400px\"><a href=\"bag_neuro/penilaian_neuro.php\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
					if ($data_stase[id] == "M093") echo "<td align=center style=\"width:400px\"><a href=\"bag_psikiatri/penilaian_psikiatri.php\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
					if ($data_stase[id] == "M094") echo "<td align=center style=\"width:400px\"><a href=\"bag_ikfr/penilaian_ikfr.php\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
					if ($data_stase[id] == "M095") echo "<td align=center style=\"width:400px\"><a href=\"bag_ikmkp/penilaian_ikmkp.php\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
					//if ($data_stase[id]=="M096") echo "<td align=center style=\"width:400px\"><a href=\"bag_ijp/penilaian_ijp.php\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
					if ($data_stase[id] == "M096") echo "<td align=center style=\"width:400px\"><a href=\"/\" onclick=\"return false;\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:grey\">$data_stase[kepaniteraan]</a><br></font>";
					if ($data_stase[id] == "M101") echo "<td align=center style=\"width:400px\"><a href=\"bag_bedah/penilaian_bedah.php\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
					if ($data_stase[id] == "M102") echo "<td align=center style=\"width:400px\"><a href=\"bag_anestesi/penilaian_anestesi.php\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
					if ($data_stase[id] == "M103") echo "<td align=center style=\"width:400px\"><a href=\"bag_radiologi/penilaian_radiologi.php\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
					if ($data_stase[id] == "M104") echo "<td align=center style=\"width:400px\"><a href=\"bag_mata/penilaian_mata.php\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
					if ($data_stase[id] == "M105") echo "<td align=center style=\"width:400px\"><a href=\"bag_thtkl/penilaian_thtkl.php\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
					if ($data_stase[id] == "M106") echo "<td align=center style=\"width:400px\"><a href=\"bag_ikgm/penilaian_ikgm.php\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
					if ($data_stase[id] == "M111") echo "<td align=center style=\"width:400px\"><a href=\"bag_obsgyn/penilaian_obsgyn.php\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
					if ($data_stase[id] == "M112") echo "<td align=center style=\"width:400px\"><a href=\"bag_forensik/penilaian_forensik.php\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
					if ($data_stase[id] == "M113") echo "<td align=center style=\"width:400px\"><a href=\"bag_ika/penilaian_ika.php\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
					if ($data_stase[id] == "M114") echo "<td align=center style=\"width:400px\"><a href=\"bag_kulit/penilaian_kulit.php\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:green\">$data_stase[kepaniteraan]</a><br></font>";
					if ($data_stase[id] == "M121") {
						echo "<td align=center style=\"width:400px\"><a href=\"bag_kompre/penilaian_kompre.php\" title=\"Penilaian Komprehensip\"><font style=\"color:green\">Komprehensip</font></a> dan ";
						echo "<a href=\"bag_kdk/penilaian_kdk.php\" title=\"Penilaian Kedokteran Keluarga\"><font style=\"color:green\">Kedokteran Keluarga</font></a><br>";
					}
					echo "<font style=\"font-size:0.75em\"><i>Skedul: $tglmulai - $tglselesai</i></font></td>";
				} else echo "<td align=center style=\"width:400px\"><a href=\"/\" onclick=\"return false;\" title=\"Penilaian $data_stase[kepaniteraan]\"><font style=\"color:grey\">$data_stase[kepaniteraan]</a><br></font><font style=\"color:red;font-size:0.75em\"><i>Skedul: - (Belum terjadwal)</i></font></td>";

				if ($no == 2) {
					echo "</tr>";
					if ($kelas_baris == "ganjil") $kelas_baris = "genap";
					else $kelas_baris = "ganjil";
					$no = 0;
				}
				$no++;
			}
			if ($no == 2) echo "<td>&nbsp;</td></tr>";

			echo "</table><br>";

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
</body>

</HTML>