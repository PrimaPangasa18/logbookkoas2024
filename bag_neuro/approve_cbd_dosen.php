<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Approval CBD Logbook Koas Pendidikan Dokter FK-UNDIP</title>
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
			<nav class="navbar navbar-expand px-4 py-3">
				<form action="#" class="d-none d-sm-inline-block">
					<div class="input-group input-group-navbar">
						<img src="../images/undipsolid.png" alt="" style="width: 45px;">
					</div>
				</form>
				<div class="navbar-collapse collapse">
					<ul class="navbar-nav ms-auto">
						<li class="nav-item dropdown d-flex align-item-center">
							<span class="navbar-text me-2">Halo, <?php echo $nama . ' , <span class="gelar" style="color:red">' . $gelar . '</span>'; ?></span>
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
			<main class="content px-3 py-4">
				<div class="container-fluid">
					<div class="mb-3">
						<h3 class="fw-bold fs-4 mb-3">PENILAIAN KEPANITERAAN (STASE) NEUROLOGI</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">APPROVAL NILAI KASUS CBD<br>KEPANITERAAN (STASE) NEUROLOGI</h2>
						<br>
						<?php
						$id_stase = "M092";
						$id = $_GET['id'];
						$data_cbd = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `neuro_nilai_cbd` WHERE `id`='$id'"));
						$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_cbd[nim]'"));

						echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
						echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";

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
						//Periode Kegiatan
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td class=\"td_mid\"><strong>Periode Kegiatan <span class=\"text-danger\">(yyyy-mm-dd)</span><strong></td>";
						echo "<td class=\"td_mid\">
						<input type=\"text\" class=\"periode_awal\" name=\"periode_awal\" style=\"font-size:1em;font-family:Poppins;border:0.5px solid black;border-radius:5px; text-align:center\" value=\"$data_cbd[tgl_awal]\" />";
						echo " <span style=\" font-weight:600;\">s.d.</span> <input type=\"text\" class=\"periode_akhir\" name=\"periode_akhir\" style=\"font-size:1em;font-family:Poppins;border:0.5px solid black;border-radius:5px; text-align:center\" value=\"$data_cbd[tgl_akhir]\" /></td>";
						echo "</tr>";
						//Tanggal Ujian
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td class=\"td_mid\"><strong>Tanggal Ujian <span class=\"text-danger\">(yyyy-mm-dd)</span></strong></td>";
						echo "<td class=\"td_mid\"><input type=\"text\" class=\"form-select tanggal_ujian\" name=\"tanggal_ujian\" style=\"font-size:1em;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" value=\"$data_cbd[tgl_ujian]\" /></td>";
						echo "</tr>";
						//Kasus CBD ke-
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Kasus CBD ke-</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						if ($data_cbd['kasus_ke'] == "1")
							echo "<input type=\"radio\" name=\"kasus_ke\" value=\"1\" checked/>&nbsp;&nbsp;1";
						else echo "<input type=\"radio\" name=\"kasus_ke\" value=\"1\" />&nbsp;&nbsp;1";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_cbd['kasus_ke'] == "2")
							echo "<input type=\"radio\" name=\"kasus_ke\" value=\"2\" checked/>&nbsp;&nbsp;2";
						else echo "<input type=\"radio\" name=\"kasus_ke\" value=\"2\" />&nbsp;&nbsp;2";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_cbd['kasus_ke'] == "3")
							echo "<input type=\"radio\" name=\"kasus_ke\" value=\"3\" checked/>&nbsp;&nbsp;3";
						else echo "<input type=\"radio\" name=\"kasus_ke\" value=\"3\" />&nbsp;&nbsp;3";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_cbd['kasus_ke'] == "4")
							echo "<input type=\"radio\" name=\"kasus_ke\" value=\"4\" checked/>&nbsp;&nbsp;4";
						else echo "<input type=\"radio\" name=\"kasus_ke\" value=\"4\" />&nbsp;&nbsp;4";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_cbd['kasus_ke'] == "5")
							echo "<input type=\"radio\" name=\"kasus_ke\" value=\"5\" checked/>&nbsp;&nbsp;5";
						else echo "<input type=\"radio\" name=\"kasus_ke\" value=\"5\" />&nbsp;&nbsp;5";
						echo "</td>";
						echo "</tr>";
						//Dosen Penilai/Penguji
						echo "<tr>";
						echo "<td><strong>Dosen Penilai/Penguji</strong></td>";
						$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_cbd[dosen]'"));
						echo "<td style=\"font-weight:600;\">$data_dosen[nama], <span style=\"color:red\">$data_dosen[gelar]</span> (<span style=\"color:blue;\">$data_dosen[nip]</span>)";
						echo "</tr>";
						//Situasi Ruangan
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Situasi Ruangan</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						if ($data_cbd['situasi_ruangan'] == "IRD")
							echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"IRD\" checked/>&nbsp;&nbsp;IRD";
						else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"IRD\" />&nbsp;&nbsp;IRD";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_cbd['situasi_ruangan'] == "Rawat Jalan")
							echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Jalan\" checked/>&nbsp;&nbsp;Rawat Jalan";
						else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Rawat Jalan\" />&nbsp;&nbsp;Rawat Jalan";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_cbd['situasi_ruangan'] == "Lain-lain")
							echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Lain-lain\" checked/>&nbsp;&nbsp;Lain-lain";
						else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Lain-lain\" />&nbsp;&nbsp;Lain-lain";
						echo "</td>";
						echo "</tr>";
						//Problem/Diagnosis Pasien
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Problem/Diagnosis Pasien</strong></td>";
						echo "<td><textarea name=\"diagnosis\" style=\" width: 100%;font-family:Poppins;border:0.5px solid black;border-radius:5px;\"  required>$data_cbd[diagnosis]</textarea></td>";
						echo "</tr>";
						//Fokus Kasus
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Fokus Kasus</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						if ($data_cbd['fokus_kasus'] == "Pembuatan Rekam Medik")
							echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Pembuatan Rekam Medik\" checked/>&nbsp;&nbsp;Pembuatan Rekam Medik<br>";
						else echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Pembuatan Rekam Medik\" />&nbsp;&nbsp;Pembuatan Rekam Medik<br>";
						if ($data_cbd['fokus_kasus'] == "Clinical Assesment")
							echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Clinical Assesment\" checked/>&nbsp;&nbsp;Clinical Assesment<br>";
						else echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Clinical Assesment\" />&nbsp;&nbsp;Clinical Assesment<br>";
						if ($data_cbd['fokus_kasus'] == "Tata Laksana")
							echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Tata Laksana\" checked/>&nbsp;&nbsp;Tata Laksana<br>";
						else echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Tata Laksana\" />&nbsp;&nbsp;Tata Laksana<br>";
						if ($data_cbd['fokus_kasus'] == "Profesionalisme")
							echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Profesionalisme\" checked/>&nbsp;&nbsp;Profesionalisme<br>";
						else echo "<input type=\"radio\" name=\"fokus_kasus\" value=\"Profesionalisme\" />&nbsp;&nbsp;Profesionalisme<br>";
						echo "</td>";
						echo "</tr>";
						//Tingkat Kerumitan
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Tingkat Kerumitan</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						if ($data_cbd['tingkat_kerumitan'] == "Rendah")
							echo "<input type=\"radio\" name=\"tingkat_kerumitan\" value=\"Rendah\" checked/>&nbsp;&nbsp;Rendah";
						else echo "<input type=\"radio\" name=\"tingkat_kerumitan\" value=\"Rendah\" />&nbsp;&nbsp;Rendah";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_cbd['tingkat_kerumitan'] == "Sedang")
							echo "<input type=\"radio\" name=\"tingkat_kerumitan\" value=\"Sedang\" checked/>&nbsp;&nbsp;Sedang";
						else echo "<input type=\"radio\" name=\"tingkat_kerumitan\" value=\"Sedang\" />&nbsp;&nbsp;Sedang";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_cbd['tingkat_kerumitan'] == "Tinggi")
							echo "<input type=\"radio\" name=\"tingkat_kerumitan\" value=\"Tinggi\" checked/>&nbsp;&nbsp;Tinggi";
						else echo "<input type=\"radio\" name=\"tingkat_kerumitan\" value=\"Tinggi\" />&nbsp;&nbsp;Tinggi";
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
						echo "<td align=center><strong>1</strong></td>";
						echo "<td><strong>Penulisan/pembuatan rekam medik</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_cbd['observasi_1'] == "1")
							echo "<input type=\"radio\" name=\"observasi1\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
						else echo "<input type=\"radio\" name=\"observasi1\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_cbd['observasi_1'] == "0")
							echo "<input type=\"radio\" name=\"observasi1\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
						else echo "<input type=\"radio\" name=\"observasi1\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek1\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_cbd[aspek_1]\" id=\"aspek1\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 2
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>2</strong></td>";
						echo "<td><strong>Penilaian klinis (<span class=\"text-danger\">clinical assesment</span>)</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_cbd['observasi_2'] == "1")
							echo "<input type=\"radio\" name=\"observasi2\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
						else echo "<input type=\"radio\" name=\"observasi2\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_cbd['observasi_2'] == "0")
							echo "<input type=\"radio\" name=\"observasi2\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
						else echo "<input type=\"radio\" name=\"observasi2\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek2\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_cbd[aspek_2]\" id=\"aspek2\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 3
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>3</strong></td>";
						echo "<td><strong>Investigasi dan rujukan (<span class=\"text-danger\">investigation and referral</span>)</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_cbd['observasi_3'] == "1")
							echo "<input type=\"radio\" name=\"observasi3\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
						else echo "<input type=\"radio\" name=\"observasi3\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_cbd['observasi_3'] == "0")
							echo "<input type=\"radio\" name=\"observasi3\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
						else echo "<input type=\"radio\" name=\"observasi3\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek3\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\"value=\"$data_cbd[aspek_3]\" id=\"aspek3\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 4
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>4</strong></td>";
						echo "<td><strong>Tata laksana</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_cbd['observasi_4'] == "1")
							echo "<input type=\"radio\" name=\"observasi4\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
						else echo "<input type=\"radio\" name=\"observasi4\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_cbd['observasi_4'] == "0")
							echo "<input type=\"radio\" name=\"observasi4\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
						else echo "<input type=\"radio\" name=\"observasi4\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek4\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_cbd[aspek_4]\" id=\"aspek4\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 5
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>5</strong></td>";
						echo "<td><strong>Pemantauan dan rencana selanjutnya (<span class=\"text-danger\">follow up and future planning</span>)</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_cbd['observasi_5'] == "1")
							echo "<input type=\"radio\" name=\"observasi5\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
						else echo "<input type=\"radio\" name=\"observasi5\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_cbd['observasi_5'] == "0")
							echo "<input type=\"radio\" name=\"observasi5\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
						else echo "<input type=\"radio\" name=\"observasi5\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek5\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_cbd[aspek_5]\" id=\"aspek5\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 6
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>6</strong></td>";
						echo "<td><strong>Profesionalisme</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_cbd['observasi_6'] == "1")
							echo "<input type=\"radio\" name=\"observasi6\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
						else echo "<input type=\"radio\" name=\"observasi6\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_cbd['observasi_6'] == "0")
							echo "<input type=\"radio\" name=\"observasi6\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
						else echo "<input type=\"radio\" name=\"observasi6\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek6\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_cbd[aspek_6]\" id=\"aspek6\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 7
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>7</strong></td>";
						echo "<td><strong>Penilaian klinis secara keseluruhan</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_cbd['observasi_7'] == "1")
							echo "<input type=\"radio\" name=\"observasi7\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
						else echo "<input type=\"radio\" name=\"observasi7\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_cbd['observasi_7'] == "0")
							echo "<input type=\"radio\" name=\"observasi7\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
						else echo "<input type=\"radio\" name=\"observasi7\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek7\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_cbd[aspek_7]\" id=\"aspek7\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//Rata Nilai
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=right colspan=3><strong>Rata-Rata Nilai <span class=\"text-danger\">(Jumlah Nilai / Jumlah Observasi)</span></strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_rata\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_cbd[nilai_rata]\" id=\"nilai_rata\" required disabled/></td>";
						echo "</tr>";
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
						<td colspan=4><font style=\"font-size:0.9em; font-weight:600;\">Keterangan: Nilai Batas Lulus (NBL) = <span class=\"text-danger\">70</span></font></td></tr>";
						echo "</table><br><br>";

						//Umpan Balik
						echo "<table  class=\"table table-bordered\" style=\"width:70%\">";
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
						<td colspan=2 align=center><strong>UMPAN BALIK TERHADAP DISKUSI KASUS</strong></td></tr>";
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center style=\"width:50%\"><strong style=\"color:blue\">Sudah bagus</strong></td>";
						echo "<td align=center style=\"width:50%\"><strong style=\"color:red\">Perlu perbaikan</strong></td>";
						echo "</tr>";
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><textarea name=\"ub_bagus\" rows=5 style=\"width:100%;font-family:Poppins;font-size:1em\" >$data_cbd[ub_bagus]</textarea></td>";
						echo "<td><textarea name=\"ub_perbaikan\" rows=5 style=\"width:100%;font-family:Poppins;font-size:1em\" >$data_cbd[ub_perbaikan]</textarea></td>";
						echo "</tr>";
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=2><strong>Saran:</strong>
						<br><textarea name=\"saran\" rows=5 style=\"width:100%;margin-top:10px;font-family:Poppins;font-size:1em\">$data_cbd[saran]</textarea></td>";
						echo "</tr>";
						///Catatan
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
						<td colspan=2><b>Catatan:</b></td></tr>";
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
						<td colspan=2><strong>Waktu Penilaian Kasus CBD:</strong></td></tr>";
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td>&nbsp;&nbsp;<strong>Observasi</strong></td>";
						echo "<td>";
						echo "<input type=\"number\" step=\"5\" min=\"0\" max=\"1500\" name=\"waktu_observasi\" style=\"width:20%;font-family:Poppins;font-size:0.9em;text-align:center;\" value=\"$data_cbd[waktu_observasi]\" required/>&nbsp;&nbsp;Menit<br>";
						echo "</td>";
						echo "</tr>";
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td>&nbsp;&nbsp;<strong>Memberikan umpan balik</strong></td>";
						echo "<td>";
						echo "<input type=\"number\" step=\"5\" min=\"0\" max=\"1500\" name=\"waktu_ub\" style=\"width:20%;font-family:Poppins;font-size:0.9em;text-align:center;\"value=\"$data_cbd[waktu_ub]\" required/>&nbsp;&nbsp;Menit<br>";
						echo "</td>";
						echo "</tr>";
						echo "</table><br>";

						echo "<br>
						<button type=\"submit\" class=\"btn btn-danger\" style=\"margin-right:40px\" name=\"cancel\" value=\"CANCEL\" formnovalidate>
            					<i class=\"fas fa-times me-2\"></i> CANCEL
        						</button>";
						echo "<button type=\"submit\" class=\"btn btn-success\" name=\"approve\" value=\"APPROVE\">
            			<i class=\"fa-solid fa-check-to-slot\"></i> APPROVE
        			</button>
      				</form><br><br>";
						echo "</center>";

						if ($_POST['cancel'] == "CANCEL") {
							$tgl_mulai = $_POST['tgl_mulai'];
							$tgl_selesai = $_POST['tgl_selesai'];
							$approval = $_POST['approval'];
							$mhsw = $_POST['mhsw'];
							echo "
			<script>
				window.location.href=\"penilaian_neuro_dosen.php?mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\";
			</script>
			";
						}

						if ($_POST['approve'] == "APPROVE") {
							$tgl_mulai = $_POST['tgl_mulai'];
							$tgl_selesai = $_POST['tgl_selesai'];
							$approval = $_POST['approval'];
							$mhsw = $_POST['mhsw'];
							$aspek1 = number_format($_POST['observasi1'] * $_POST['aspek1'], 2);
							$aspek2 = number_format($_POST['observasi2'] * $_POST['aspek2'], 2);
							$aspek3 = number_format($_POST['observasi3'] * $_POST['aspek3'], 2);
							$aspek4 = number_format($_POST['observasi4'] * $_POST['aspek4'], 2);
							$aspek5 = number_format($_POST['observasi5'] * $_POST['aspek5'], 2);
							$aspek6 = number_format($_POST['observasi6'] * $_POST['aspek6'], 2);
							$aspek7 = number_format($_POST['observasi7'] * $_POST['aspek7'], 2);

							$diagnosis = addslashes($_POST['diagnosis']);
							$ub_bagus = addslashes($_POST['ub_bagus']);
							$ub_perbaikan = addslashes($_POST['ub_perbaikan']);
							$saran = addslashes($_POST['saran']);
							$jml_observasi = $_POST['observasi1'] + $_POST['observasi2'] + $_POST['observasi3'] + $_POST['observasi4'] + $_POST['observasi5'] + $_POST['observasi6'] + $_POST['observasi7'];
							$nilai_total = $_POST['observasi1'] * $_POST['aspek1'] + $_POST['observasi2'] * $_POST['aspek2'] + $_POST['observasi3'] * $_POST['aspek3'] + $_POST['observasi4'] * $_POST['aspek4'] + $_POST['observasi5'] * $_POST['aspek5'] + $_POST['observasi6'] * $_POST['aspek6'] + $_POST['observasi7'] * $_POST['aspek7'];
							if ($jml_observasi == 0) $nilai_rata = 0;
							else $nilai_rata = $nilai_total / $jml_observasi;
							$nilai_rata = number_format($nilai_rata, 2);

							$update_cbd = mysqli_query($con, "UPDATE `neuro_nilai_cbd` SET
				`tgl_awal`='$_POST[periode_awal]',`tgl_akhir`='$_POST[periode_akhir]',`tgl_ujian`='$_POST[tanggal_ujian]',
				`kasus_ke`='$_POST[kasus_ke]',`diagnosis`='$diagnosis',`situasi_ruangan`='$_POST[situasi_ruangan]',
				`tingkat_kerumitan`='$_POST[tingkat_kerumitan]',`fokus_kasus`='$_POST[fokus_kasus]',
				`aspek_1`='$aspek1',`observasi_1`='$_POST[observasi1]',
				`aspek_2`='$aspek2',`observasi_2`='$_POST[observasi2]',
				`aspek_3`='$aspek3',`observasi_3`='$_POST[observasi3]',
				`aspek_4`='$aspek4',`observasi_4`='$_POST[observasi4]',
				`aspek_5`='$aspek5',`observasi_5`='$_POST[observasi5]',
				`aspek_6`='$aspek6',`observasi_6`='$_POST[observasi6]',
				`aspek_7`='$aspek7',`observasi_7`='$_POST[observasi7]',
				`ub_bagus`='$ub_bagus',`ub_perbaikan`='$ub_perbaikan',`saran`='$saran',
				`waktu_observasi`='$_POST[waktu_observasi]',`waktu_ub`='$_POST[waktu_ub]',
				`nilai_rata`='$nilai_rata',`tgl_approval`='$tgl',`status_approval`='1'
				WHERE `id`='$_POST[id]'");

							echo "
				<script>
				window.location.href = \"penilaian_neuro_dosen.php?mulai=$tgl_mulai&selesai=$tgl_selesai&approval=$approval&mhsw=$mhsw\";
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

	<script src="../javascript/script1.js"></script>
	<script src="../javascript/buttontopup.js"></script>
	<script src="../jquery.min.js"></script>
	<script type="text/javascript" src="../jquery_ui/jquery-ui.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('.tanggal_ujian').datepicker({
				dateFormat: 'yy-mm-dd'
			});
			$('.periode_awal').datepicker({
				dateFormat: 'yy-mm-dd'
			});
			$('.periode_akhir').datepicker({
				dateFormat: 'yy-mm-dd'
			});
		});
	</script>

	<script>
		function sum() {
			var aspek1 = document.getElementById('aspek1').value;
			var aspek2 = document.getElementById('aspek2').value;
			var aspek3 = document.getElementById('aspek3').value;
			var aspek4 = document.getElementById('aspek4').value;
			var aspek5 = document.getElementById('aspek5').value;
			var aspek6 = document.getElementById('aspek6').value;
			var aspek7 = document.getElementById('aspek7').value;
			var observasi1 = $("input[name=observasi1]:checked").val();
			var observasi2 = $("input[name=observasi2]:checked").val();
			var observasi3 = $("input[name=observasi3]:checked").val();
			var observasi4 = $("input[name=observasi4]:checked").val();
			var observasi5 = $("input[name=observasi5]:checked").val();
			var observasi6 = $("input[name=observasi6]:checked").val();
			var observasi7 = $("input[name=observasi7]:checked").val();

			var total = parseInt(observasi1) * parseFloat(aspek1) + parseInt(observasi2) * parseFloat(aspek2) + parseInt(observasi3) * parseFloat(aspek3) + parseInt(observasi4) * parseFloat(aspek4) + parseInt(observasi5) * parseFloat(aspek5) + parseInt(observasi6) * parseFloat(aspek6) + parseInt(observasi7) * parseFloat(aspek7);
			var pembagi = parseInt(observasi1) + parseInt(observasi2) + parseInt(observasi3) + parseInt(observasi4) + parseInt(observasi5) + parseInt(observasi6) + parseInt(observasi7);
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
</body>

</html>