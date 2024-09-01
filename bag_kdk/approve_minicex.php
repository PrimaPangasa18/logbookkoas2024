<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Approval MiniCex Logbook Koas Pendidikan Dokter FK-UNDIP</title>
	<link rel="shortcut icon" type="x-icon" href="../images/undipsolid.png">
	<link rel="stylesheet" href="../style/style1.css" />
	<link rel="stylesheet" href="../style/buttontopup.css">
	<link rel="stylesheet" href="../qr_code.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../text-security-master/dist/text-security.css" type="text/css" media="screen" />

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
			<main class="content px-3 py-4" style="background-image: url('images/undip_watermark_color.png'), url('images/undip_watermark_color.png'); ">
				<div class="container-fluid">
					<div class="mb-3">
						<h3 class="fw-bold fs-4 mb-3">PENILAIAN KEPANITERAAN KEDOKTERAN KELUARGA</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">APPROVAL PENILAIAN MINI-CEX</h2>
						<br>
						<?php
						$id = $_GET['id'];
						$id_state = "M121";
						$data_minicex = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kdk_nilai_minicex` WHERE `id`='$id'"));
						$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_minicex[nim]'"));

						echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
						echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
						echo "<center>";
						echo "<table  class=\"table table-bordered\" style=\"width:70%\">";

						//Instansi
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td style=\"width:40%\"><strong>Instansi</strong></td>";
						echo "<td style=\"width:60%;font-weight:600;\">$data_minicex[instansi]</td>";
						echo "</tr>";
						//Lokasi Puskesmas/Klinik
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Nama Puskesmas / Klinik Pratama</strong></td>";
						echo "<td style=\"font-weight:600;\">$data_minicex[lokasi]</td>";
						echo "</tr>";
						//DPJP
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Nama Penilai/DPJP</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_minicex[dosen]'"));
						echo "$data_dosen[nama], <span style=\"color:red\">$data_dosen[gelar]</span> (<span style=\"color:blue\">$data_dosen[nip]</span>)";
						echo "</td>";
						echo "</tr>";
						//Nama dokter muda/koas
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Nama dokter muda</strong></td>";
						echo "<td style=\"font-weight:600; color:darkgreen\">$data_mhsw[nama]</td>";
						echo "</tr>";
						//NIM
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>NIM</strong></td>";
						echo "<td style=\" font-weight:600; color:red\">$data_mhsw[nim]</td>";
						echo "</tr>";
						//Tahap
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Tahap</strong></td>";
						echo "<td style=\" font-weight:600;\">Kepaniteraan Kedokteran Keluarga</td>";
						echo "</tr>";
						//Ujian ke-
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Ujian ke-</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						if ($data_minicex['no_ujian'] == "1")
							echo "<input type=\"radio\" name=\"no_ujian\" value=\"1\" checked/>&nbsp;&nbsp;1";
						else echo "<input type=\"radio\" name=\"no_ujian\" value=\"1\" />&nbsp;&nbsp;1";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_minicex['no_ujian'] == "2")
							echo "<input type=\"radio\" name=\"no_ujian\" value=\"2\" checked/>&nbsp;&nbsp;2";
						else echo "<input type=\"radio\" name=\"no_ujian\" value=\"2\" />&nbsp;&nbsp;2";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_minicex['no_ujian'] == "3")
							echo "<input type=\"radio\" name=\"no_ujian\" value=\"3\" checked/>&nbsp;&nbsp;3";
						else echo "<input type=\"radio\" name=\"no_ujian\" value=\"3\" />&nbsp;&nbsp;3";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_minicex['no_ujian'] == "4")
							echo "<input type=\"radio\" name=\"no_ujian\" value=\"4\" checked/>&nbsp;&nbsp;4";
						else echo "<input type=\"radio\" name=\"no_ujian\" value=\"4\" />&nbsp;&nbsp;4";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_minicex['no_ujian'] == "5")
							echo "<input type=\"radio\" name=\"no_ujian\" value=\"5\" checked/>&nbsp;&nbsp;5";
						else echo "<input type=\"radio\" name=\"no_ujian\" value=\"5\" />&nbsp;&nbsp;5";
						echo "</td>";
						echo "</tr>";
						//Problem Pasien/Diagnosis
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Problem Pasien/Diagnosis</strong></td>";
						echo "<td><textarea name=\"diagnosis\" style=\" width: 100%;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" value=\"$data_minicex[diagnosis]\" required>$data_minicex[diagnosis]</textarea></td>";
						echo "</tr>";
						//Situasi Ruangan
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Situasi Ruangan</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						if ($data_minicex['situasi_ruangan'] == "Rawat Jalan")
							echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Jalan\" checked/>&nbsp;&nbsp;Rawat Jalan";
						else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Jalan\" />&nbsp;&nbsp;Rawat Jalan";
						echo "<br>";
						if ($data_minicex['situasi_ruangan'] == "Rawat Inap")
							echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Inap\" checked/>&nbsp;&nbsp;Rawat Inap";
						else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Inap\" />&nbsp;&nbsp;Rawat Inap";
						echo "<br>";
						if ($data_minicex['situasi_ruangan'] == "IRD")
							echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"IRD\" checked/>&nbsp;&nbsp;IRD";
						else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"IRD\" />&nbsp;&nbsp;IRD";
						echo "<br>";
						if ($data_minicex['situasi_ruangan'] == "Lain-lain")
							echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Lain-lain\" checked/>&nbsp;&nbsp;Lain-lain";
						else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Lain-lain\" />&nbsp;&nbsp;Lain-lain";
						echo "</td>";
						echo "</tr>";
						//Umur Pasien
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Umur Pasien</strong></td>";
						echo "<td style=\"font-weight:600;\"><input type=\"number\" step=\"1\" min=\"0\" max=\"150\" name=\"umur_pasien\" style=\"text-align:center;width:20%; height:30px;border:0.2px solid black;border-radius:3px;font-size:1.0em\" value=\"$data_minicex[umur_pasien]\" required/>&nbsp;&nbsp;Tahun</td>";
						echo "</tr>";
						//Jenis Kelamin Pasien
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Jenis Kelamin Pasien</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						if ($data_minicex['jk_pasien'] == "Laki-Laki")
							echo "<input type=\"radio\" name=\"jk_pasien\" value=\"Laki-Laki\" checked/>&nbsp;&nbsp;Laki-Laki";
						else echo "<input type=\"radio\" name=\"jk_pasien\" value=\"Laki-Laki\" />&nbsp;&nbsp;Laki-Laki";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_minicex['jk_pasien'] == "Perempuan")
							echo "<input type=\"radio\" name=\"jk_pasien\" value=\"Perempuan\" checked />&nbsp;&nbsp;Perempuan";
						else echo "<input type=\"radio\" name=\"jk_pasien\" value=\"Perempuan\" />&nbsp;&nbsp;Perempuan";
						echo "</td>";
						echo "</tr>";
						//Status Pasien
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Status Pasien</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						if ($data_minicex['status_pasien'] == "Baru")
							echo "<input type=\"radio\" name=\"status_pasien\" value=\"Baru\" checked/>&nbsp;&nbsp;Baru";
						else echo "<input type=\"radio\" name=\"status_pasien\" value=\"Baru\" />&nbsp;&nbsp;Baru";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_minicex['status_pasien'] == "Follow-up")
							echo "<input type=\"radio\" name=\"status_pasien\" value=\"Follow-up\" checked/>&nbsp;&nbsp;Follow-up";
						else echo "<input type=\"radio\" name=\"status_pasien\" value=\"Follow-up\" />&nbsp;&nbsp;Follow-up";
						echo "</td>";
						echo "</tr>";
						//Tingkat Kesulitan
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Tingkat Kesulitan</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						if ($data_minicex['tingkat_kesulitan'] == "Rendah")
							echo "<input type=\"radio\" name=\"tingkat_kesulitan\" value=\"Rendah\" checked/>&nbsp;&nbsp;Rendah";
						else echo "<input type=\"radio\" name=\"tingkat_kesulitan\" value=\"Rendah\" />&nbsp;&nbsp;Rendah";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_minicex['tingkat_kesulitan'] == "Sedang")
							echo "<input type=\"radio\" name=\"tingkat_kesulitan\" value=\"Sedang\" checked/>&nbsp;&nbsp;Sedang";
						else echo "<input type=\"radio\" name=\"tingkat_kesulitan\" value=\"Sedang\" />&nbsp;&nbsp;Sedang";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_minicex['tingkat_kesulitan'] == "Tinggi")
							echo "<input type=\"radio\" name=\"tingkat_kesulitan\" value=\"Tinggi\" checked/>&nbsp;&nbsp;Tinggi";
						else echo "<input type=\"radio\" name=\"tingkat_kesulitan\" value=\"Tinggi\" />&nbsp;&nbsp;Tinggi";
						echo "</td>";
						echo "</tr>";
						//Fokus Kasus
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Fokus Kasus</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						if ($data_minicex['fokus_kasus'] == "Anamnesis")
							echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Anamnesis\" checked/>&nbsp;&nbsp;Anamnesis<br>";
						else echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Anamnesis\" />&nbsp;&nbsp;Anamnesis<br>";
						if ($data_minicex['fokus_kasus'] == "Pemeriksaan fisik")
							echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Pemeriksaan fisik\" checked/>&nbsp;&nbsp;Pemeriksaan fisik<br>";
						else echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Pemeriksaan fisik\" />&nbsp;&nbsp;Pemeriksaan fisik<br>";
						if ($data_minicex['fokus_kasus'] == "Diagnosis")
							echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Diagnosis\" checked/>&nbsp;&nbsp;Diagnosis<br>";
						else echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Diagnosis\" />&nbsp;&nbsp;Diagnosis<br>";
						if ($data_minicex['fokus_kasus'] == "Terapi")
							echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Terapi\" checked/>&nbsp;&nbsp;Terapi<br>";
						else echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Terapi\" />&nbsp;&nbsp;Terapi<br>";
						if ($data_minicex['fokus_kasus'] == "Konseling")
							echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Konseling\" checked/>&nbsp;&nbsp;Konseling";
						else echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Konseling\" />&nbsp;&nbsp;Konseling";
						echo "</td>";
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
						echo "<th style=\"width:20%;text-align:center;\">Status Observasi</th>";
						echo "<th style=\"width:20%;text-align:center;\">Nilai (0-100)</th>";
						echo "</thead>";
						//No 1
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>1</strong></td>";
						echo "<td><strong>Kemampuan wawancara medis</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_1'] == "1")
							echo "<input type=\"radio\" name=\"observasi_1\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
						else echo "<input type=\"radio\" name=\"observasi_1\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_minicex['observasi_1'] == "0")
							echo "<input type=\"radio\" name=\"observasi_1\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
						else echo "<input type=\"radio\" name=\"observasi_1\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_1\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_1]\" id=\"aspek_1\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 2
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>2</strong></td>";
						echo "<td><strong>Kemampuan pemeriksaan fisik</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_2'] == "1")
							echo "<input type=\"radio\" name=\"observasi_2\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
						else echo "<input type=\"radio\" name=\"observasi_2\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_minicex['observasi_2'] == "0")
							echo "<input type=\"radio\" name=\"observasi_2\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
						else echo "<input type=\"radio\" name=\"observasi_2\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_2\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_2]\" id=\"aspek_2\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 3
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>3</strong></td>";
						echo "<td><strong>Kualitas humanistik/profesionalisme</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_3'] == "1")
							echo "<input type=\"radio\" name=\"observasi_3\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
						else echo "<input type=\"radio\" name=\"observasi_3\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_minicex['observasi_3'] == "0")
							echo "<input type=\"radio\" name=\"observasi_3\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
						else echo "<input type=\"radio\" name=\"observasi_3\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_3\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_3]\" id=\"aspek_3\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 4
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>4</strong></td>";
						echo "<td><strong>Keputusan klinis/diagnostis</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_4'] == "1")
							echo "<input type=\"radio\" name=\"observasi_4\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
						else echo "<input type=\"radio\" name=\"observasi_4\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_minicex['observasi_4'] == "0")
							echo "<input type=\"radio\" name=\"observasi_4\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
						else echo "<input type=\"radio\" name=\"observasi_4\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_4\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_4]\" id=\"aspek_4\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 5
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>5</strong></td>";
						echo "<td><strong>Kemampuan mengelola pasien</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_5'] == "1")
							echo "<input type=\"radio\" name=\"observasi_5\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
						else echo "<input type=\"radio\" name=\"observasi_5\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_minicex['observasi_5'] == "0")
							echo "<input type=\"radio\" name=\"observasi_5\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
						else echo "<input type=\"radio\" name=\"observasi_5\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_5\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_5]\" id=\"aspek_5\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 6
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>6</strong></td>";
						echo "<td><strong>Kemampuan konseling</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_6'] == "1")
							echo "<input type=\"radio\" name=\"observasi_6\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
						else echo "<input type=\"radio\" name=\"observasi_6\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_minicex['observasi_6'] == "0")
							echo "<input type=\"radio\" name=\"observasi_6\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
						else echo "<input type=\"radio\" name=\"observasi_6\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_6\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_6]\" id=\"aspek_6\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 7
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>7</strong></td>";
						echo "<td><strong>Organisasi/efisiensi</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_7'] == "1")
							echo "<input type=\"radio\" name=\"observasi_7\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
						else echo "<input type=\"radio\" name=\"observasi_7\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_minicex['observasi_7'] == "0")
							echo "<input type=\"radio\" name=\"observasi_7\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
						else echo "<input type=\"radio\" name=\"observasi_7\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_7\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_7]\" id=\"aspek_7\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 8
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>8</strong></td>";
						echo "<td><strong>Kompetensi klinis keseluruhan</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_8'] == "1")
							echo "<input type=\"radio\" name=\"observasi_8\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
						else echo "<input type=\"radio\" name=\"observasi_8\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_minicex['observasi_8'] == "0")
							echo "<input type=\"radio\" name=\"observasi_8\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
						else echo "<input type=\"radio\" name=\"observasi_8\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_8\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_8]\" id=\"aspek_8\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//Rata Nilai
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=right colspan=3><strong>Rata-Rata Nilai <span class=\"text-danger\">(Jumlah Nilai / Jumlah Observasi)</span></strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_rata\" style=\"width:50%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"nilai_rata\" value=\"$data_minicex[nilai_rata]\" required disabled/></td>";
						echo "</tr>";
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
						<td colspan=4><font style=\"font-size:0.9em; font-weight:600;\">Keterangan: Nilai Batas Lulus (NBL) = <span class=\"text-danger\">70</span></font></td></tr>";
						echo "</table><br><br>";

						//Umpan Balik
						echo "<table  class=\"table table-bordered\" style=\"width:70%\">";
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
						<td colspan=2 align=center><strong>UMPAN BALIK KOMPETENSI KLINIS</strong></td></tr>";
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center style=\"width:50%\"><strong style=\"color:blue\">Sudah bagus</strong></td>";
						echo "<td align=center style=\"width:50%\"><strong style=\"color:red\">Perlu perbaikan</strong></td>";
						echo "</tr>";
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><textarea name=\"ub_bagus\" rows=5 style=\"width:100%;font-family:Poppins;font-size:1em\" >$data_minicex[ub_bagus]</textarea></td>";
						echo "<td><textarea name=\"ub_perbaikan\" rows=5 style=\"width:100%;font-family:Poppins;font-size:1em\" >$data_minicex[ub_perbaikan]</textarea></td>";
						echo "</tr>";
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=2><strong>Action plan yang disetujui bersama:</strong>
						<br><textarea name=\"action_plan\" rows=5 style=\"width:100%;margin-top:10px;font-family:Poppins;font-size:1em\" >$data_minicex[action_plan]</textarea></td>";
						echo "</tr>";
						echo "</table><br><br>";

						//Catatan
						echo "<table  class=\"table table-bordered\" style=\"width:70%\">";
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
						<td colspan=2 align=center><strong>Catatan:</strong></td></tr>";
						echo "<tr  class=\"table-success\" style=\"border-width: 1px; border-color: #000;\"><td colspan=2><strong>1. Waktu Mini-CEX:</td></strong></tr>";
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td style=\"width:30%;padding:5px 5px 5px 25px; font-weight:600;\">Observasi</td>";
						echo "<td style=\"width:70%;  font-weight:600;\">";
						echo ": <input type=\"number\" step=\"5\" min=\"0\" max=\"1500\" name=\"waktu_observasi\"  style=\"width:10%;font-family:Poppins;font-size:0.9em;text-align:center;\" value=\"$data_minicex[waktu_observasi]\" required/>&nbsp;&nbsp;Menit<br>";
						echo "</td>";
						echo "</tr>";
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td style=\"width:30%;padding:5px 5px 5px 25px; font-weight:600;\">Memberikan umpan balik</td>";
						echo "<td style=\"width:70%;  font-weight:600;\">";
						echo ": <input type=\"number\" step=\"5\" min=\"0\" max=\"1500\" name=\"waktu_ub\"  style=\"width:10%;font-family:Poppins;font-size:0.9em;text-align:center;\" value=\"$data_minicex[waktu_ub]\" required/>&nbsp;&nbsp;Menit<br>";
						echo "</td>";
						echo "</tr>";
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\"><td colspan=2><strong>2. Kepuasan penilai terhadap Mini-CEX:</strong></td></tr>";
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\"><td colspan=2 style=\"padding:5px 5px 5px 25px; font-weight:600;\">";
						if ($data_minicex['kepuasan_penilai'] == "Kurang sekali")
							echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Kurang sekali\" checked />&nbsp;&nbsp;Kurang sekali&nbsp;&nbsp;&nbsp;&nbsp;";
						else echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Kurang sekali\" />&nbsp;&nbsp;Kurang sekali&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_minicex['kepuasan_penilai'] == "Kurang")
							echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Kurang\" checked />&nbsp;&nbsp;Kurang&nbsp;&nbsp;&nbsp;&nbsp;";
						else echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Kurang\" />&nbsp;&nbsp;Kurang&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_minicex['kepuasan_penilai'] == "Cukup")
							echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Cukup\" checked />&nbsp;&nbsp;Cukup&nbsp;&nbsp;&nbsp;&nbsp;";
						else echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Cukup\" />&nbsp;&nbsp;Cukup&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_minicex['kepuasan_penilai'] == "Baik")
							echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Baik\" checked />&nbsp;&nbsp;Baik&nbsp;&nbsp;&nbsp;&nbsp;";
						else echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Baik\" />&nbsp;&nbsp;Baik&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_minicex['kepuasan_penilai'] == "Baik sekali")
							echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Baik sekali\" checked />&nbsp;&nbsp;Baik sekali";
						else echo "<input type=\"radio\" name=\"kepuasan_penilai\" value=\"Baik sekali\" />&nbsp;&nbsp;Baik sekali";
						echo "</td></tr>";
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\"><td colspan=2><strong>3. Kepuasan residen terhadap Mini-CEX:</strong></td></tr>";
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\"><td colspan=2 style=\"padding:5px 5px 5px 25px; font-weight:600;\">";
						if ($data_minicex['kepuasan_residen'] == "Kurang sekali")
							echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Kurang sekali\" checked/>&nbsp;&nbsp;Kurang sekali&nbsp;&nbsp;&nbsp;&nbsp;";
						else echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Kurang sekali\" />&nbsp;&nbsp;Kurang sekali&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_minicex['kepuasan_residen'] == "Kurang")
							echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Kurang\" checked/>&nbsp;&nbsp;Kurang&nbsp;&nbsp;&nbsp;&nbsp;";
						else echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Kurang\" />&nbsp;&nbsp;Kurang&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_minicex['kepuasan_residen'] == "Cukup")
							echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Cukup\" checked/>&nbsp;&nbsp;Cukup&nbsp;&nbsp;&nbsp;&nbsp;";
						else echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Cukup\" />&nbsp;&nbsp;Cukup&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_minicex['kepuasan_residen'] == "Baik")
							echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Baik\" checked/>&nbsp;&nbsp;Baik&nbsp;&nbsp;&nbsp;&nbsp;";
						else echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Baik\" />&nbsp;&nbsp;Baik&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_minicex['kepuasan_residen'] == "Baik sekali")
							echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Baik sekali\" checked/>&nbsp;&nbsp;Baik sekali";
						else echo "<input type=\"radio\" name=\"kepuasan_residen\" value=\"Baik sekali\" />&nbsp;&nbsp;Baik sekali";
						echo "</td></tr>";
						echo "</table><br>";
						?>
						<div class="container mt-5" style="width: 1000px;">
							<center>
								<span style="font-weight:800; font-size:1.5em">APPROVAL</span>
								<br><br>
								<span class="text-danger" style="font-weight:500;">Pilih salah satu cara untuk memasukkan kode: Password/OTP/QR Code</span>
							</center>
							<br>
							<table class="table table-bordered">
								<tbody>
									<tr class="table-primary" style="border-width: 1px; border-color: #000;">
										<td><strong>Nama Dosen/Dokter/Residen</strong></td>
										<td>
											<span style="font-weight: 600; color: darkblue;"><?php echo $data_dosen['nama']; ?></span>,
											<span style="font-weight: 600; color: darkgreen;"><?php echo $data_dosen['gelar']; ?></span>
											<span style="font-weight: 600; color: darkred;"><?php echo '[' . $data_dosen['nip'] . ']'; ?></span>
										</td>
									</tr>
									<tr class="table-warning" style="border-width: 1px; border-color: #000;">
										<td><strong>Password Approval</strong></td>
										<td>
											<div class="position-relative">
												<input type="password" name="dosenpass" class="form-control" autocomplete="off" readonly onfocus="this.removeAttribute('readonly');" id="dosenpass" />
												<i class="fa fa-eye position-absolute" id="toggleEye" onclick="togglePasswordVisibility()" style="top: 50%; right: 10px; transform: translateY(-50%); cursor: pointer;"></i>
											</div>
										</td>
									</tr>
									<tr class="table-primary" style="border-width: 1px; border-color: #000;">
										<td><strong>Masukkan OTP Dosen/Dokter/Residen</strong></td>
										<td>
											<input name="dosenpin" autocomplete="off" type="text" class="form-control" />
										</td>
									</tr>
									<tr class="table-warning" style="border-width: 1px; border-color: #000;">
										<td><strong>Scanning QR Code</strong><br><small class="text-danger" style="font-weight: 600;">(gunakan smartphone)</small></td>
										<td>
											<input type="text" name="dosenqr" placeholder="Tracking QR-Code" class="form-control" />
											<label class="form-label mt-2">
												<input type="file" accept="image/*" capture="environment" class="form-control" onchange="openQRCamera(this);" tabindex="-1">
											</label>
										</td>
									</tr>
								</tbody>
							</table>
							<br><br>
							<center>
								<div>
									<button type="submit" class="btn btn-success" name="approve" value="APPROVE"><i class="fa-solid fa-check-to-slot me-2"></i>APPROVE</button>
									&nbsp;&nbsp;&nbsp;
									<button type="submit" class="btn btn-danger" name="cancel" value="CANCEL"><i class="fa-solid fa-xmark me-2"></i>CANCEL</button>
								</div>
							</center>
						</div>
						<?php
						echo "</form>";

						if ($_POST['cancel'] == "CANCEL") {
							echo "
			<script>
				window.location.href=\"penilaian_kdk.php\";
			</script>
			";
						}

						if ($_POST['approve'] == "APPROVE") {
							$minicex = mysqli_fetch_array(mysqli_query($con, "SELECT `dosen` FROM `kdk_nilai_minicex` WHERE `id`='$_POST[id]'"));
							$user_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `password` FROM `admin` WHERE `username`='$minicex[dosen]'"));
							$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `pin`,`qr` FROM `dosen` WHERE `nip`='$minicex[dosen]'"));
							$dosenpass_md5 = md5($_POST['dosenpass']);
							if (($_POST['dosenpass'] != "" and $dosenpass_md5 == $user_dosen['password'])
								or ($_POST['dosenpin'] != "" and $_POST['dosenpin'] == $data_dosen['pin'])
								or ($_POST['dosenqr'] != "" and $_POST['dosenqr'] == $data_dosen['qr'])
							) {
								$aspek1 = number_format($_POST['observasi_1'] * $_POST['aspek_1'], 2);
								$aspek2 = number_format($_POST['observasi_2'] * $_POST['aspek_2'], 2);
								$aspek3 = number_format($_POST['observasi_3'] * $_POST['aspek_3'], 2);
								$aspek4 = number_format($_POST['observasi_4'] * $_POST['aspek_4'], 2);
								$aspek5 = number_format($_POST['observasi_5'] * $_POST['aspek_5'], 2);
								$aspek6 = number_format($_POST['observasi_6'] * $_POST['aspek_6'], 2);
								$aspek7 = number_format($_POST['observasi_7'] * $_POST['aspek_7'], 2);
								$aspek8 = number_format($_POST['observasi_8'] * $_POST['aspek_8'], 2);

								$lokasi = addslashes($_POST['lokasi']);
								$diagnosis = addslashes($_POST['diagnosis']);
								$ub_bagus = addslashes($_POST['ub_bagus']);
								$ub_perbaikan = addslashes($_POST['ub_perbaikan']);
								$action_plan = addslashes($_POST['action_plan']);

								$jml_observasi = $_POST['observasi_1'] + $_POST['observasi_2'] + $_POST['observasi_3'] + $_POST['observasi_4'] + $_POST['observasi_5'] + $_POST['observasi_6'] + $_POST['observasi_7'] + $_POST['observasi_8'];
								$nilai_total = $_POST['observasi_1'] * $_POST['aspek_1'] + $_POST['observasi_2'] * $_POST['aspek_2'] + $_POST['observasi_3'] * $_POST['aspek_3'] + $_POST['observasi_4'] * $_POST['aspek_4'] + $_POST['observasi_5'] * $_POST['aspek_5'] + $_POST['observasi_6'] * $_POST['aspek_6'] + $_POST['observasi_7'] * $_POST['aspek_7'] + $_POST['observasi_8'] * $_POST['aspek_8'];
								if ($jml_observasi == 0) $nilai_rata = 0;
								else $nilai_rata = $nilai_total / $jml_observasi;
								$nilai_rata = number_format($nilai_rata, 2);

								$update_minicex = mysqli_query($con, "UPDATE `kdk_nilai_minicex` SET
					`no_ujian`='$_POST[no_ujian]',
					`diagnosis`='$diagnosis',`situasi_ruangan`='$_POST[situasi_ruangan]',
					`umur_pasien`='$_POST[umur_pasien]',`jk_pasien`='$_POST[jk_pasien]',
					`status_pasien`='$_POST[status_pasien]',`tingkat_kesulitan`='$_POST[tingkat_kesulitan]',
					`fokus_kasus`='$_POST[fokus_kasus]',
					`aspek_1`='$aspek1',`observasi_1`='$_POST[observasi_1]',
					`aspek_2`='$aspek2',`observasi_2`='$_POST[observasi_2]',
					`aspek_3`='$aspek3',`observasi_3`='$_POST[observasi_3]',
					`aspek_4`='$aspek4',`observasi_4`='$_POST[observasi_4]',
					`aspek_5`='$aspek5',`observasi_5`='$_POST[observasi_5]',
					`aspek_6`='$aspek6',`observasi_6`='$_POST[observasi_6]',
					`aspek_7`='$aspek7',`observasi_7`='$_POST[observasi_7]',
					`aspek_8`='$aspek8',`observasi_8`='$_POST[observasi_8]',
					`nilai_rata`='$nilai_rata',`ub_bagus`='$_POST[ub_bagus]',`ub_perbaikan`='$_POST[ub_perbaikan]',
					`action_plan`='$action_plan',
					`waktu_observasi`='$_POST[waktu_observasi]',`waktu_ub`='$_POST[waktu_ub]',
					`kepuasan_penilai`='$_POST[kepuasan_penilai]',`kepuasan_residen`='$_POST[kepuasan_residen]',
					`tgl_approval`='$tgl',`status_approval`='1'
					WHERE `id`='$_POST[id]'");
								echo "
					<script>
						alert(\"Approval SUKSES !\");
						window.location.href=\"penilaian_kdk.php\";
					</script>
					";
							} else {
								echo "
				<script>
				alert(\"Approval GAGAL !\");
				window.location.href = \"approve_minicex.php?id=\"+\"$_POST[id]\";
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
	<script src="../qr_packed.js"></script>
	<script type="text/javascript">
		function openQRCamera(node) {
			var reader = new FileReader();
			reader.onload = function() {
				node.value = "";
				qrcode.callback = function(res) {
					if (res instanceof Error) {
						alert("No QR code found. Please make sure the QR code is within the camera's frame and try again.");
					} else {
						node.parentNode.previousElementSibling.value = res;
					}
				};
				qrcode.decode(reader.result);
			};
			reader.readAsDataURL(node.files[0]);
		}
	</script>

	<script>
		function sum() {
			var aspek1 = document.getElementById('aspek_1').value;
			var aspek2 = document.getElementById('aspek_2').value;
			var aspek3 = document.getElementById('aspek_3').value;
			var aspek4 = document.getElementById('aspek_4').value;
			var aspek5 = document.getElementById('aspek_5').value;
			var aspek6 = document.getElementById('aspek_6').value;
			var aspek7 = document.getElementById('aspek_7').value;
			var aspek8 = document.getElementById('aspek_8').value;
			var observasi1 = $("input[name=observasi_1]:checked").val();
			var observasi2 = $("input[name=observasi_2]:checked").val();
			var observasi3 = $("input[name=observasi_3]:checked").val();
			var observasi4 = $("input[name=observasi_4]:checked").val();
			var observasi5 = $("input[name=observasi_5]:checked").val();
			var observasi6 = $("input[name=observasi_6]:checked").val();
			var observasi7 = $("input[name=observasi_7]:checked").val();
			var observasi8 = $("input[name=observasi_8]:checked").val();

			var total = parseInt(observasi1) * parseFloat(aspek1) + parseInt(observasi2) * parseFloat(aspek2) + parseInt(observasi3) * parseFloat(aspek3) + parseInt(observasi4) * parseFloat(aspek4) + parseInt(observasi5) * parseFloat(aspek5) + parseInt(observasi6) * parseFloat(aspek6) + parseInt(observasi7) * parseFloat(aspek7) + parseInt(observasi8) * parseFloat(aspek8);
			var pembagi = parseInt(observasi1) + parseInt(observasi2) + parseInt(observasi3) + parseInt(observasi4) + parseInt(observasi5) + parseInt(observasi6) + parseInt(observasi7) + parseInt(observasi8);
			var result = parseFloat(total) / parseInt(pembagi);

			if (!isNaN(result)) {
				document.getElementById('nilai_rata').value = number_format(result, 2);
			}

			function number_format(number, decimals, decPoint, thousandsSep) {
				number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
				var n = !isFinite(+number) ? 0 : +number
				var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
				var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
				var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
				var s = ''

				var toFixedFix = function(n, prec) {
					var k = Math.pow(10, prec)
					return '' + (Math.round(n * k) / k)
						.toFixed(prec)
				}

				// @todo: for IE parseFloat(0.55).toFixed(0) = 0;
				s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.')
				if (s[0].length > 3) {
					s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
				}
				if ((s[1] || '').length < prec) {
					s[1] = s[1] || ''
					s[1] += new Array(prec - s[1].length + 1).join('0')
				}

				return s.join(dec)
			}
		}
	</script>
	<script>
		function togglePasswordVisibility() {
			const passwordField = document.getElementById("dosenpass");
			const toggleEye = document.getElementById("toggleEye");

			if (passwordField.type === "password") {
				passwordField.type = "text";
				toggleEye.classList.remove("fa-eye");
				toggleEye.classList.add("fa-eye-slash");
			} else {
				passwordField.type = "password";
				toggleEye.classList.remove("fa-eye-slash");
				toggleEye.classList.add("fa-eye");
			}
		}
	</script>
</body>

</html>