<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line View Kasus Logbook Koas Pendidikan Dokter FK-UNDIP</title>
	<link rel="shortcut icon" type="x-icon" href="../images/undipsolid.png">
	<link rel="stylesheet" href="../style/style1.css" />
	<link rel="stylesheet" href="../style/buttontopup.css">


	<!-- Link Bootstrap -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
	<!-- Link CDN Icon -->
	<link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
	<div class="wrapper">
		<?php

		include "../config.php";
		include "../fungsi.php";

		error_reporting("E_ALL ^ E_NOTICE");

		if (empty($_COOKIE['user']) || empty($_COOKIE['pass'])) {
			echo "
		<script>
			window.location.href=\"accessdenied.php\";
		</script>
		";
		} else {
			if (!empty($_COOKIE['user']) and !empty($_COOKIE['pass']) and ($_COOKIE['level'] == 5 or $_COOKIE['level'] == 4)) {
				if ($_COOKIE['level'] == 5) {
					include "menu5.php";
				}
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
		if ($_COOKIE['level'] != 5) {
			$data_nim = $_GET['nim'];
			$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_nim'"));
		} else {
			$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
		}

		// Menentukan path gambar
		$foto_path = "../foto/" . $data_mhsw['foto'];
		$default_foto = "../foto/profil_blank.png";

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
						<img src="../images/undipsolid.png" alt="" style="width: 45px;">
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
								<a href="../logout.php" class="dropdown-item">
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
			<main class="content px-3 py-4" style="background-image: url('../images/undip_watermark_color.png'), url('../images/undip_watermark_color.png'); ">
				<div class="container-fluid">
					<div class="mb-3">
						<h3 class="fw-bold fs-4 mb-3">PENILAIAN KEPANITERAAN (STASE) IKGM</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">NILAI LAPORAN KASUS<br>KEPANITERAAN (STASE) IKGM</h2>
						<br>
						<?php
						$id = $_GET['id'];
						$data_kasus = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `ikgm_nilai_kasus` WHERE `id`='$id'"));
						$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_kasus[nim]'"));

						echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
						$tgl_mulai = $_GET['mulai'];
						$tgl_selesai = $_GET['selesai'];
						$approval = $_GET['approval'];
						$mhsw = $_GET['mhsw'];
						echo "<input type=\"hidden\" name=\"tgl_mulai\" value=\"$tgl_mulai\" />";
						echo "<input type=\"hidden\" name=\"tgl_selesai\" value=\"$tgl_selesai\" />";
						echo "<input type=\"hidden\" name=\"approval\" value=\"$approval\" />";
						echo "<input type=\"hidden\" name=\"mhsw\" value=\"$mhsw\" />";

						echo "<center>";
						echo "<table  class=\"table table-bordered\" style=\"width:70%\">";

						//Nama mahasiswa
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td style=\"width:40%\"><strong>Nama Mahasiswa Koas</strong></td>";
						echo "<td style=\"width:60%;font-weight:600; color:darkgreen\">$data_mhsw[nama]</td>";
						echo "</tr>";
						//NIM
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>NIM</strong></td>";
						echo "<td style=\" font-weight:600; color:red\">$data_mhsw[nim]</td>";
						echo "</tr>";
						///Tanggal Presentasi
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						$tanggal_ujian = tanggal_indo($data_kasus['tgl_ujian']);
						echo "<td><strong>Tanggal Ujian/Presentasi</strong></td>";
						echo "<td style=\"font-weight:600;\">$tanggal_ujian</td>";
						echo "</tr>";
						//Dosen Pembimbing
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Dosen Pembimbing</strong></td>";
						$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_kasus[dosen]'"));
						echo "<td style=\"font-weight:600;\">
						$data_dosen[nama], <span style=\"color:red\">$data_dosen[gelar]</span> (<span style=\"color:blue\">$data_dosen[nip]</span>)</td>";
						echo "</tr>";
						//Nama/Judul Kasus
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Nama/Judul Kasus</strong></td>";
						echo "<td><textarea name=\"nama_kasus\" style=\" width: 100%;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" disabled>$data_kasus[nama_kasus]</textarea></td>";
						echo "</tr>";
						echo "</table><br><br>";

						//Form nilai
						echo "<table border=2 style=\"width:70%;  background:rgba(255, 243, 205, 1);\" >";
						echo "<tr><td style=\"text-align:center; font-size:1.1em;\"><strong >Form Penilaian:</strong></td></tr>";
						echo "</table>";
						echo "<table class=\"table table-bordered\" style=\"width:70%\">";
						echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<th style=\"width:5%;text-align:center;\">No</th>";
						echo "<th style=\"width:55%;text-align:center;\">Aspek Yang Dinilai</th>";
						echo "<th style=\"width:40%;text-align:center;\">Penilaian</th>";
						echo "</thead>";
						//No 1
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>1</strong></td>";
						echo "<td><strong>Kehadiran Presentasi</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						if ($data_kasus['aspek_1'] == "0")
							echo "<input type=\"radio\" name=\"aspek_1\" value=\"0\" checked/>&nbsp;&nbsp;0 - Tidak Hadir<br>";
						else echo "<input type=\"radio\" name=\"aspek_1\" value=\"0\" disabled />&nbsp;&nbsp;0 - Tidak Hadir<br>";
						if ($data_kasus['aspek_1'] == "1")
							echo "<input type=\"radio\" name=\"aspek_1\" value=\"1\" checked/>&nbsp;&nbsp;1 - Hadir, terlambat<br>";
						else echo "<input type=\"radio\" name=\"aspek_1\" value=\"1\" disabled />&nbsp;&nbsp;1 - Hadir, terlambat<br>";
						if ($data_kasus['aspek_1'] == "2")
							echo "<input type=\"radio\" name=\"aspek_1\" value=\"2\" checked/>&nbsp;&nbsp;2 - Hadir tepat waktu<br>";
						else echo "<input type=\"radio\" name=\"aspek_1\" value=\"2\" disabled />&nbsp;&nbsp;2 - Hadir tepat waktu<br>";
						echo "</td>";
						echo "</tr>";
						//No 2
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>2</strong></td>";
						echo "<td><strong>Aktifitas saat diskusi<br>(<span class=\"text-danger\">Dinilai dari frekuensi mengajukan masukan / komentar / pendapat / jawaban</span>)</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						if ($data_kasus['aspek_2'] == "1")
							echo "<input type=\"radio\" name=\"aspek_2\" value=\"1\" checked/>&nbsp;&nbsp;1 - Pasif<br>";
						else echo "<input type=\"radio\" name=\"aspek_2\" value=\"1\" disabled />&nbsp;&nbsp;1 - Pasif<br>";
						if ($data_kasus['aspek_2'] == "2")
							echo "<input type=\"radio\" name=\"aspek_2\" value=\"2\" checked/>&nbsp;&nbsp;2 - Kurang aktif<br>";
						else echo "<input type=\"radio\" name=\"aspek_2\" value=\"2\" disabled />&nbsp;&nbsp;2 - Kurang aktif<br>";
						if ($data_kasus['aspek_2'] == "3")
							echo "<input type=\"radio\" name=\"aspek_2\" value=\"3\" checked/>&nbsp;&nbsp;3 - Cukup aktif<br>";
						else echo "<input type=\"radio\" name=\"aspek_2\" value=\"3\" disabled />&nbsp;&nbsp;3 - Cukup aktif<br>";
						if ($data_kasus['aspek_2'] == "4")
							echo "<input type=\"radio\" name=\"aspek_2\" value=\"4\" checked/>&nbsp;&nbsp;4 - Lebih aktif<br>";
						else echo "<input type=\"radio\" name=\"aspek_2\" value=\"4\" disabled />&nbsp;&nbsp;4 - Lebih aktif<br>";
						if ($data_kasus['aspek_2'] == "5")
							echo "<input type=\"radio\" name=\"aspek_2\" value=\"5\" checked/>&nbsp;&nbsp;5 - Sangat aktif";
						else echo "<input type=\"radio\" name=\"aspek_2\" value=\"5\" disabled />&nbsp;&nbsp;5 - Sangat aktif";
						echo "</td>";
						echo "</tr>";
						//No 3
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>3</strong></td>";
						echo "<td><strong>Ketrampilan presentasi / berkomunikasi<br>(<span class=\"text-danger\">Dinilai dari kemampuan berinteraksi dengan peserta lain dan menjaga etika</span>)<strong></td>";
						echo "<td style=\"font-weight:600;\">";
						if ($data_kasus['aspek_3'] == "1")
							echo "<input type=\"radio\" name=\"aspek_3\" value=\"1\" checked/>&nbsp;&nbsp;1 - Sangat kurang<br>";
						else echo "<input type=\"radio\" name=\"aspek_3\" value=\"1\" disabled />&nbsp;&nbsp;1 - Sangat kurang<br>";
						if ($data_kasus['aspek_3'] == "2")
							echo "<input type=\"radio\" name=\"aspek_3\" value=\"2\" checked/>&nbsp;&nbsp;2 - Kurang<br>";
						else echo "<input type=\"radio\" name=\"aspek_3\" value=\"2\" disabled />&nbsp;&nbsp;2 - Kurang<br>";
						if ($data_kasus['aspek_3'] == "3")
							echo "<input type=\"radio\" name=\"aspek_3\" value=\"3\" checked/>&nbsp;&nbsp;3 - Cukup<br>";
						else echo "<input type=\"radio\" name=\"aspek_3\" value=\"3\" disabled />&nbsp;&nbsp;3 - Cukup<br>";
						if ($data_kasus['aspek_3'] == "4")
							echo "<input type=\"radio\" name=\"aspek_3\" value=\"4\" checked/>&nbsp;&nbsp;4 - Baik<br>";
						else echo "<input type=\"radio\" name=\"aspek_3\" value=\"4\" disabled />&nbsp;&nbsp;4 - Baik<br>";
						if ($data_kasus['aspek_3'] == "5")
							echo "<input type=\"radio\" name=\"aspek_3\" value=\"5\" checked/>&nbsp;&nbsp;5 - Sangat baik";
						else echo "<input type=\"radio\" name=\"aspek_3\" value=\"5\" disabled />&nbsp;&nbsp;5 - Sangat baik";
						echo "</td>";
						echo "</tr>";
						//No 4
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>4</strong></td>";
						echo "<td><strong>Kelengkapan laporan dan materi presentasi<br>(<span class=\"text-danger\">Dinilai dari materi presentasi yang disiapkan dan disajikan, serta kelengkapan isi dan kerapian makalah / laporan yang dikumpulkan</span>)</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						if ($data_kasus['aspek_4'] == "1")
							echo "<input type=\"radio\" name=\"aspek_4\" value=\"1\" checked/>&nbsp;&nbsp;1 - Sangat kurang<br>";
						else echo "<input type=\"radio\" name=\"aspek_4\" value=\"1\" disabled />&nbsp;&nbsp;1 - Sangat kurang<br>";
						if ($data_kasus['aspek_4'] == "2")
							echo "<input type=\"radio\" name=\"aspek_4\" value=\"2\" checked/>&nbsp;&nbsp;2 - Kurang<br>";
						else echo "<input type=\"radio\" name=\"aspek_4\" value=\"2\" disabled />&nbsp;&nbsp;2 - Kurang<br>";
						if ($data_kasus['aspek_4'] == "3")
							echo "<input type=\"radio\" name=\"aspek_4\" value=\"3\" checked/>&nbsp;&nbsp;3 - Cukup<br>";
						else echo "<input type=\"radio\" name=\"aspek_4\" value=\"3\" disabled />&nbsp;&nbsp;3 - Cukup<br>";
						if ($data_kasus['aspek_4'] == "4")
							echo "<input type=\"radio\" name=\"aspek_4\" value=\"4\" checked/>&nbsp;&nbsp;4 - Baik<br>";
						else echo "<input type=\"radio\" name=\"aspek_4\" value=\"4\" disabled />&nbsp;&nbsp;4 - Baik<br>";
						if ($data_kasus['aspek_4'] == "5")
							echo "<input type=\"radio\" name=\"aspek_4\" value=\"5\" checked/>&nbsp;&nbsp;5 - Sangat baik";
						else echo "<input type=\"radio\" name=\"aspek_4\" value=\"5\" disabled />&nbsp;&nbsp;5 - Sangat baik";
						echo "</td>";
						echo "</tr>";
						//Total Nilai
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=2 align=right><strong>Total Nilai <span class=\"text-danger\">(10 x Jumlah Poin / 1.7)</span></strong></td>";
						echo "<td align=center style=\"font-weight:600; color:blue;\">$data_kasus[nilai_total]</td>";
						echo "</tr>";
						echo "</table><br><br>";

						//Catatan
						echo "<table class=\"table table-bordered\" style=\"width:70%\">";
						echo "<tr  class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center ><strong>Catatan Dosen Pembimbing Terhadap Kegiatan:</strong><br></td>";
						echo "</tr>";
						echo "<tr  class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Catatan:</strong><br>
						<textarea name=\"catatan\" rows=5 style=\"width:100%;margin-top:10px;font-family:Poppins;font-size:1em\" disabled>$data_kasus[catatan]</textarea></td>";
						echo "</tr>";

						$tanggal_approval = tanggal_indo($data_kasus['tgl_approval']);
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">
						<td colspan=4 align=right><br><span style=\"font-weight:600;\">Status:</span> <strong style=\"color:darkgreen;\" >DISETUJUI</strong><br><span style=\"font-weight:600;\">pada tanggal <span style=\"color:darkblue;\">$tanggal_approval</span></span><br>";
						echo "<span style=\"font-weight:600;\">Dosen Pembimbing:<p><span style=\"font-weight:500;\">$data_dosen[nama]</span>, <span style=\"font-weight:500; color:red\">$data_dosen[gelar]</span><br>NIP. <span style=\"font-weight:600; color:blue;\">$data_dosen[nip]</span></span>";
						echo "</td></tr>";
						echo "</table><br>";
						echo "<br>
						<button type=\"submit\" class=\"btn btn-primary\" name=\"back\" value=\"BACK\">
            					<i class=\"fa-solid fa-backward me-2\"></i> BACK
        						</button>";
						echo "<br><br></form>";
						echo "</center>";

						if ($_POST['back'] == "BACK") {
							if ($_COOKIE['level'] == 5)
								echo "
					<script>
					window.location.href=\"penilaian_ikgm.php\";
					</script>
					";

							if ($_COOKIE['level'] == 4) {
								$tgl_mulai = $_POST['tgl_mulai'];
								$tgl_selesai = $_POST['tgl_selesai'];
								$approval = $_POST['approval'];
								$mhsw = $_POST['mhsw'];
								echo "
				<script>
					window.location.href=\"penilaian_ikgm_dosen.php?mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\";
				</script>
				";
							}
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

	<script src="../javascript/script1.js"></script>
	<script src="../javascript/buttontopup.js"></script>
	<script src="../jquery.min.js"></script>
</body>

</html>