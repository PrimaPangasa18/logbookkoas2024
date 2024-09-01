<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Rekap Individu Cetak Nilai Bagian Logbook Koas Pendidikan Dokter FK-UNDIP</title>
	<link rel="shortcut icon" type="x-icon" href="images/undipsolid.png">
	<link rel="stylesheet" href="select2/dist/css/select2.css" />
	<link rel="stylesheet" href="style/style1.css" />
	<link rel="stylesheet" href="style/buttontopup.css">
	<!-- <link rel="stylesheet" href="menu.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="mytable.css" type="text/css" media="screen" /> -->


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
			if (!empty($_COOKIE['user']) and !empty($_COOKIE['pass']) and ($_COOKIE['level'] == 1 or $_COOKIE['level'] == 2 or $_COOKIE['level'] == 3)) {
				if ($_COOKIE['level'] == 1) {
					include "menu1.php";
				}
				if ($_COOKIE['level'] == 2) {
					include "menu2.php";
				}
				if ($_COOKIE['level'] == 3) {
					include "menu3.php";
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
						<h3 class="fw-bold fs-4 mb-3">CETAK NILAI BAGIAN / KEPANITERAAN (STASE)</h3>
						<br><br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">CETAK NILAI BAGIAN / KEPANITERAAN (STASE)</h2>
						<br>
						<?php
						echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
						?>
						<div class="container">
							<center>
								<div class="table-responsive">
									<table class="table table-bordered" style="width: 800px;">
										<tr class="table-primary" style="border-width: 1px; border-color: #000;">
											<td style="width: 300px;">
												<strong>Kepaniteraan <span class="text-danger">(STASE)</span></strong>
											</td>
											<td style="width: 500px;">
												<?php
												echo "<select class=\"form-select\" name=\"bagian\" id=\"bagian\" required>";
												$data_bagian = mysqli_query($con, "SELECT * FROM `kepaniteraan` ORDER BY `id`");
												echo "<option value=\"\">< Pilihan Bagian / Kepaniteraan (Stase) ></option>";
												while ($bagian = mysqli_fetch_array($data_bagian)) {
													echo "<option value=\"$bagian[id]\">$bagian[kepaniteraan]</option>";
												}
												echo "</select>";
												?>
											</td>
										</tr>
										<tr class="table-success" style="border-width: 1px; border-color: #000;">
											<td style="width: 300px;">
												<strong>Nama <span class="text-danger">(NIM Mahasiswa)</span></strong>
											</td>
											<td style="width: 500px;">
												<?php
												echo "<select class=\"form-select\" name=\"mhsw\" id=\"mhsw\" required>";
												$mhsw = mysqli_query($con, "SELECT `nim`,`nama` FROM `biodata_mhsw` ORDER BY `nama`");
												echo "<option value=\"\">< Pilihan Mahasiswa ></option>";
												while ($data1 = mysqli_fetch_array($mhsw)) {
													echo "<option value=\"$data1[nim]\">$data1[nama] ($data1[nim])</option>";
												}
												echo "</select>";
												?>
											</td>
										</tr>
									</table>

									<br>
									<button type="submit" class="btn btn-success" name="submit" value="SUBMIT">
										<i class="fa-solid fa-floppy-disk me-2"></i>SUBMIT
									</button>
							</center>
						</div>
						<br><br>


						<?php
						echo '</form>';
						?>
						<?php
						if ($_POST['submit'] == "SUBMIT") {
							echo '<center>';
							echo "<table class=\"table table-bordered\" style=\"width:auto\">";
							echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
							echo "<td>";
							$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT `nama` FROM `biodata_mhsw` WHERE `nim`='$_POST[mhsw]'"));
							echo "<strong>Nama Mahasiswa : </strong> ";
							echo "</td>";
							echo "<td style=\"font-weight:600;\">";
							echo "&nbsp; <span style=\" color:darkblue\">$data_mhsw[nama] </span> - <span style=\" color:darkred\">(NIM: $_POST[mhsw])</span>";
							echo "</td>";
							echo "</tr>";
							echo "</table>";
							echo '</center>';
							echo "<br>";


							if ($_POST['bagian'] == "M091") {
								echo '<center>';
								echo '<span style="font-size:0.9em; font-weight:500; color:red;">Klik tombol di bawah ini untuk keperluan cetak</span>';
								echo '<br><br>';
								echo '<h5><b>Penilaian Kepaniteraan (STASE) Ilmu Penyakit Dalam:</b></h5>';
								echo '<br>';
								echo '<div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 10px;">';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_ipd/cetak_minicex.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Mini-Cex</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_ipd/cetak_kasus.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Penyajian Kasus Besar</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_ipd/cetak_ujian.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Ujian Akhir</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_ipd/cetak_nilai_test.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Test (Ujian MCQ)</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_ipd/cetak_nilai_ipd.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Rekap Nilai Kepaniteraan (Stase) Ilmu Penyakit Dalam</a></div>';
								echo '</div>';
								echo '</center>';
							}

							if ($_POST['bagian'] == "M092") {
								echo '<center>';
								echo '<span style="font-size:0.9em; font-weight:500; color:red;">Klik tombol di bawah ini untuk keperluan cetak</span>';
								echo '<br><br>';
								echo '<h5><b>Penilaian Kepaniteraan (STASE) Neurologi:</b></h5>';
								echo '<br>';
								echo '<div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 10px;">';
								echo '<div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">';
								echo '<div style="margin: 5px;"><a class="btn btn-primary" href="bag_neuro/cetak_cbd.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Kasus CBD</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_neuro/cetak_jurnal.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Journal Reading</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_neuro/cetak_spv.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Ujian SPV</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_neuro/cetak_minicex.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Ujian MINI-CEX</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_neuro/cetak_nilai_test.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Test</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_neuro/cetak_nilai_neuro.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Rekap Nilai Kepaniteraan (Stase) Neurologi</a></div>';
								echo '</div>';
								echo '</center>';
							}

							if ($_POST['bagian'] == "M093") {
								echo '<center>';
								echo '<span style="font-size:0.9em; font-weight:500; color:red;">Klik tombol di bawah ini untuk keperluan cetak</span>';
								echo '<br><br>';
								echo '<h5><b>Penilaian Kepaniteraan (STASE) Ilmu Kesehatan Jiwa:</b></h5>';
								echo '<br>';
								echo '<div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">';
								echo '<div style="margin: 5px;"><a class="btn btn-primary" href="bag_psikiatri/cetak_jurnal.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Journal Reading</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_psikiatri/cetak_cbd.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai CBD</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_psikiatri/cetak_minicex.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Ujian MINI-CEX</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_psikiatri/cetak_osce.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Ujian OSCE</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_psikiatri/cetak_nilai_test.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai-Nilai Test</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_psikiatri/cetak_nilai_psikiatri.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Rekap Nilai Kepaniteraan (Stase) Ilmu Kesehatan Jiwa</a></div>';
								echo '</div>';
								echo '</center>';
							}

							if ($_POST['bagian'] == "M094") {
								echo '<center>';
								echo '<span style="font-size:0.9em; font-weight:500; color:red;">Klik tombol di bawah ini untuk keperluan cetak</span>';
								echo '<br><br>';
								echo '<h5><b>Penilaian Kepaniteraan (STASE) IKFR:</b></h5>';
								echo '<br>';
								echo '<div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_ikfr/cetak_kasus.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Diskusi Kasus</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_ikfr/cetak_minicex.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Ujian Mini-Cex</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_ikfr/cetak_nilai_test.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Pre-Test, Post-Test, Sikap/Perilaku, dan Ujian OSCE</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_ikfr/cetak_nilai_ikfr.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Rekap Nilai Kepaniteraan (Stase) IKFR</a></div>';
								echo '</div>';
								echo '</center>';
							}

							if ($_POST['bagian'] == "M095") {
								echo '<center>';
								echo '<span style="font-size:0.9em; font-weight:500; color:red;">Klik tombol di bawah ini untuk keperluan cetak</span>';
								echo '<br><br>';
								echo '<h5><b>Penilaian Kepaniteraan (STASE) IKM-KP:</b></h5>';
								echo '<br>';
								echo '<div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_ikmkp/cetak_pkbi.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Kegiatan di PKBI</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_ikmkp/cetak_p2ukm.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Kegiatan di P2UKM Mlonggo</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_ikmkp/cetak_nilai_test.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Test</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_ikmkp/cetak_komprehensip.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Ujian Komprehensip</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_ikmkp/cetak_nilai_ikmkp.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Rekap Nilai Kepaniteraan (Stase) IKM-KP</a></div>';
								echo '</div>';
								echo '</center>';
							}

							if ($_POST['bagian'] == "M096") {
								echo '<center>';
								echo '<span style="font-size:0.9em; font-weight:500; color:red;">Klik tombol di bawah ini untuk keperluan cetak</span>';
								echo '<br><br>';
								echo '<h5><b>Penilaian Kepaniteraan (Stase) Ilmu Jantung dan Pembuluh Darah:</b></h5>';
								echo '<br>';
								echo "<span style=\"font-size:0.9em; font-weight:500; color:red;\" >(Masih dalam penyusunan !!)</span>";
								echo '</center>';
							}

							if ($_POST['bagian'] == "M101") {
								echo '<center>';
								echo '<span style="font-size:0.9em; font-weight:500; color:red;">Klik tombol di bawah ini untuk keperluan cetak</span>';
								echo '<br><br>';
								echo '<h5><b>Penilaian Kepaniteraan (STASE) Ilmu Bedah:</b></h5>';
								echo '<br>';
								echo '<div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; margin-top: 10px;">';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_bedah/cetak_nilai_mentor.php?cbd=1&nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Mentoring di RS Jejaring</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_bedah/cetak_nilai_test.php?cbd=2&nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Pre-Test, Post-Test, Skill Lab dan OSCE</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_bedah/cetak_nilai_bedah.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Rekap Nilai Kepaniteraan (Stase) Ilmu Bedah</a></div>';
								echo '</div>';
								echo '</center>';
							}

							if ($_POST['bagian'] == "M102") {
								echo '<center>';
								echo '<span style="font-size:0.9em; font-weight:500; color:red;">Klik tombol di bawah ini untuk keperluan cetak</span>';
								echo '<br><br>';
								echo '<h5><b>Penilaian Kepaniteraan (STASE) Anestesi dan Intensive Care:</b></h5>';
								echo '<br>';
								echo '<div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; margin-top: 10px;">';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_anestesi/cetak_dops.php?cbd=1&nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai DOPS</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_anestesi/cetak_osce.php?cbd=2&nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Ujian OSCE</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_anestesi/cetak_nilai_test.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai-Nilai Test dan Sikap/Perilaku</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_anestesi/cetak_nilai_anestesi.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Rekap Nilai Kepaniteraan (Stase) Anestesi dan Intensive Care</a></div>';
								echo '</div>';
								echo '</center>';
							}

							if ($_POST['bagian'] == "M103") {
								echo '<center>';
								echo '<span style="font-size:0.9em; font-weight:500; color:red;">Klik tombol di bawah ini untuk keperluan cetak</span>';
								echo '<br><br>';
								echo '<h5><b>Penilaian Kepaniteraan (STASE) Radiologi:</b></h5>';
								echo '<br>';
								echo '<div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; margin-top: 10px;">';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_radiologi/cetak_cbd.php?cbd=1&nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Kasus CBD-Radiodiagnostik</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_radiologi/cetak_cbd.php?cbd=2&nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Kasus CBD-Radioterapi</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_radiologi/cetak_jurnal.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Journal Reading</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_radiologi/cetak_nilai_test.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai-Nilai Test dan Sikap/Perilaku</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_radiologi/cetak_nilai_radiologi.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Rekap Nilai Kepaniteraan (Stase) Radiologi</a></div>';
								echo '</div>';
								echo '</center>';
							}

							if ($_POST['bagian'] == "M104") {
								echo '<center>';
								echo '<span style="font-size:0.9em; font-weight:500; color:red;">Klik tombol di bawah ini untuk keperluan cetak</span>';
								echo '<br><br>';
								echo '<h5><b>Penilaian Kepaniteraan (STASE) Ilmu Kesehatan Mata:</b></h5>';
								echo '<br>';
								echo '<div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; margin-top: 10px;">';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_mata/cetak_presentasi.php?cbd=1&nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Presentasi Kasus Besar</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_mata/cetak_jurnal.php?cbd=2&nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Presentasi Journal Reading</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_mata/cetak_nilai_penyanggah.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Penyanggah</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_mata/cetak_minicex.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Ujian Mini-Cex</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_mata/cetak_nilai_test.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Test-Test (MCQ dan OSCE/OSCA)</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_mata/cetak_nilai_mata.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Rekap Nilai Kepaniteraan (Stase) Ilmu Kesehatan Mata</a></div>';
								echo '</div>';
								echo '</center>';
							}

							if ($_POST['bagian'] == "M105") {
								echo '<center>';
								echo '<span style="font-size:0.9em; font-weight:500; color:red;">Klik tombol di bawah ini untuk keperluan cetak</span>';
								echo '<br><br>';
								echo '<h5><b>Penilaian Kepaniteraan (STASE) Ilmu Kesehatan THT-KL:</b></h5>';
								echo '<br>';
								echo '<div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; margin-top: 10px;">';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_thtkl/cetak_presentasi.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Presentasi Kasus</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_thtkl/cetak_responsi.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Responsi Kasus Kecil</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_thtkl/cetak_jurnal.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Journal Reading</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_thtkl/cetak_nilai_test.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Test</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_thtkl/cetak_osce.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Ujian OSCE</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_thtkl/cetak_nilai_thtkl.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Rekap Nilai Kepaniteraan (Stase) Ilmu Kesehatan THT-KL</a></div>';
								echo '</div>';
								echo '</center>';
							}

							if ($_POST['bagian'] == "M106") {
								echo '<center>';
								echo '<span style="font-size:0.9em; font-weight:500; color:red;">Klik tombol di bawah ini untuk keperluan cetak</span>';
								echo '<br><br>';
								echo '<h5><b>Penilaian Kepaniteraan (STASE) IKGM:</b></h5>';
								echo '<br>';
								echo '<div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; margin-top: 10px;">';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_ikgm/cetak_kasus.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Laporan Kasus</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_ikgm/cetak_jurnal.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Journal Reading</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_ikgm/cetak_responsi.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Responsi Kasus Kecil</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_ikgm/cetak_nilai_test.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Ujian OSCA dan Nilai Sikap dan Perilaku</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_ikgm/cetak_nilai_ikgm.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Rekap Nilai Kepaniteraan (Stase) IKGM</a></div>';
								echo '</div>';
								echo '</center>';
							}

							if ($_POST['bagian'] == "M111") {
								echo '<center>';
								echo '<span style="font-size:0.9em; font-weight:500; color:red;">Klik tombol di bawah ini untuk keperluan cetak</span>';
								echo '<br><br>';
								echo '<h5><b>Penilaian Kepaniteraan (Stase) Ilmu Kebidanan dan Penyakit Kandungan:</b></h5>';
								echo '<br>';
								echo "<a href=\"bag_obsgyn/cetak_minicex.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai MINI-CEX (Mini Clinical Examination)</a><br>";
								echo "<a href=\"bag_obsgyn/cetak_cbd.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Case-Based Discussion (CBD)</a><br>";
								echo "<a href=\"bag_obsgyn/cetak_jurnal.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Journal Reading</a><br>";
								echo "<a href=\"bag_obsgyn/cetak_nilai_test.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Nilai Test (DOPS/OSCE, MCQ, dan MINI-PAT)</a><br>";
								echo "<a href=\"bag_obsgyn/cetak_nilai_obsgyn.php?nim=$_POST[mhsw]\" target=\"_BLANK\">Cetak Rekap Nilai Kepaniteraan (Stase) Ilmu Kebidanan dan Penyakit Kandungan</a>";
								echo '</div>';
								echo '</center>';
							}

							if ($_POST['bagian'] == "M112") {
								echo '<center>';
								echo '<span style="font-size:0.9em; font-weight:500; color:red;">Klik tombol di bawah ini untuk keperluan cetak</span>';
								echo '<br><br>';
								echo '<h5><b>Penilaian Kepaniteraan (Stase) Kedokteran Forensik dan Medikolegal:</b></h5>';
								echo '<br>';
								echo '<div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; margin-top: 10px;">';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_obsgyn/cetak_minicex.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai MINI-CEX (Mini Clinical Examination)</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_obsgyn/cetak_cbd.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Case-Based Discussion (CBD)</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_obsgyn/cetak_jurnal.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Journal Reading</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_obsgyn/cetak_nilai_test.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Test (DOPS/OSCE, MCQ, dan MINI-PAT)</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_obsgyn/cetak_nilai_obsgyn.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Rekap Nilai Kepaniteraan (Stase) Ilmu Kebidanan dan Penyakit Kandungan</a></div>';
								echo '</div>';
								echo '</center>';
							}

							if ($_POST['bagian'] == "M113") {
								echo '<center>';
								echo '<span style="font-size:0.9em; font-weight:500; color:red;">Klik tombol di bawah ini untuk keperluan cetak</span>';
								echo '<br><br>';
								echo '<h5><b>Penilaian Kepaniteraan (Stase) Ilmu Kesehatan Anak:</b></h5>';
								echo '<br>';
								echo '<div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; margin-top: 10px;">';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_ika/cetak_minicex.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai MINI-CEX (Mini Clinical Examination)</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_ika/cetak_dops.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Direct Observation of Procedural Skill (DOPS)</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_ika/cetak_cbd.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Case-Based Discussion (CBD) - Kasus Poliklinik</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_ika/cetak_kasus.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Penyajian Kasus Besar</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_ika/cetak_jurnal.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Penyajian Journal Reading</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_ika/cetak_minipat.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Mini Peer Assesment Tool (Mini-PAT)</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_ika/cetak_ujian.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Ujian Akhir Kepaniteraan</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_ika/cetak_nilai_test.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Ujian/Test (Pre-Test, Post-Test, dan Ujian OSCE)</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_ika/cetak_nilai_ika.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Rekap Nilai Kepaniteraan (Stase) Ilmu Kesehatan Anak</a></div>';
								echo '</div>';
								echo '</center>';
							}

							if ($_POST['bagian'] == "M114") {
								echo '<center>';
								echo '<span style="font-size:0.9em; font-weight:500; color:red;">Klik tombol di bawah ini untuk keperluan cetak</span>';
								echo '<br><br>';
								echo '<h5><b>Penilaian Kepaniteraan (Stase) Ilmu Kesehatan Kulit dan Kelamin:</b></h5>';
								echo '<br>';
								echo '<div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; margin-top: 10px;">';
								echo '<div style="margin: 5px;"><a class="btn btn-primary" href="bag_kulit/cetak_nilai_cbd.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Ujian Kasus</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_kulit/cetak_nilai_test.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai-Nilai OSCE, Ujian Teori, dan Perilaku</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_kulit/cetak_nilai_kulit.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Rekap Nilai Kepaniteraan (Stase) Ilmu Kesehatan Kulit dan Kelamin</a></div>';
								echo '</div>';
								echo '</center>';
							}

							if ($_POST['bagian'] == "M121") {
								echo '<center>';
								echo '<span style="font-size:0.9em; font-weight:500; color:red;">Klik tombol di bawah ini untuk keperluan cetak</span>';
								echo '<br><br>';
								echo '<h5><b>Penilaian Kepaniteraan Komprehensip:</b></h5>';
								echo '<br>';
								echo '<div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; margin-top: 10px;">';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_kompre/cetak_laporan.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Laporan</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_kompre/cetak_nilai_sikap.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Sikap/Perilaku</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_kompre/cetak_nilai_cbd.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Case Based Discussion (CBD)</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_kompre/cetak_nilai_presensi.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Presensi / Kehadiran</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_kompre/cetak_nilai_kompre.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Rekap Nilai Akhir Kepaniteraan Komprehensip</a></div>';
								echo '</div>';
								echo '</center>';
								echo '<br><br>';
								echo '<center>';
								echo "<tr>";
								echo "<td>";
								echo '<h5><b>Penilaian Kedokteran Keluarga:</b></h5>';
								echo '<br>';
								echo '<div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; margin-top: 10px;">';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_kdk/cetak_laporan_kasus.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Laporan Kasus (Nilai Dosen Pembimbing Lapangan)</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_kdk/cetak_nilai_sikap.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Sikap di Puskesmas dan Klinik Pratama</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_kdk/cetak_nilai_dops.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai DOPS di Puskesmas dan Klinik Pratama</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_kdk/cetak_nilai_minicex.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Mini-CEX di Puskesmas dan Klinik Pratama</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_kdk/cetak_nilai_presensi.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Nilai Presensi / Kehadiran di Puskesmas dan Klinik Pratama</a></div>';
								echo '<div style="margin: 5px;"><a class="btn btn-primary " href="bag_kdk/cetak_nilai_kdk.php?nim=' . $_POST['mhsw'] . '" target="_blank">Cetak Rekap Nilai Akhir Kepaniteraan Kedokteran Keluarga</a></div>';
								echo '</div>';
								echo '</center>';
							}
						}
						?>
					</div>
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
	<script type="text/javascript" src="jquery.min.js"></script>
	<script src="select2/dist/js/select2.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#bagian").select2({
				placeholder: "< Pilihan Bagian / Kepaniteraan (Stase) >",
				allowClear: true
			});
			$("#mhsw").select2({
				placeholder: "< Pilihan Mahasiswa >",
				allowClear: true
			});

		});
	</script>
</body>

</html>