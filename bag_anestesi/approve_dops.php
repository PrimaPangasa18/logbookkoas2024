<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Approval DOPS Logbook Koas Pendidikan Dokter FK-UNDIP</title>
	<link rel="shortcut icon" type="x-icon" href="../images/undipsolid.png">
	<link rel="stylesheet" href="../style/style1.css" />
	<link rel="stylesheet" href="../style/buttontopup.css">
	<link rel="stylesheet" href="../qr_code.css" type="text/css" media="screen" />
	<link rel="stylesheet" type="text/css" href="../jquery_ui/jquery-ui.css">
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
						<h3 class="fw-bold fs-4 mb-3">PENILAIAN KEPANITERAAN (STASE) ANESTESI DAN INTENSIVE CARE</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">APPROVAL NILAI DIRECT OBSERVATION OF PROCEDURAL SKILL (DOPS)<br>KEPANITERAAN (STASE) ANESTESI DAN INTENSIVE CARE</h2>
						<br>
						<?php
						$id = $_GET['id'];
						$data_dops = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `anestesi_nilai_dops` WHERE `id`='$id'"));
						$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_dops[nim]'"));

						echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
						echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
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
						//Tanggal Ujian/Presentasi
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td class=\"td_mid\"><strong>Tanggal Ujian/Presentasi <span class=\"text-danger\">(yyyy-mm-dd)</span></strong></td>";
						echo "<td class=\"td_mid\"><input type=\"text\" class=\"form-select tanggal_ujian\" name=\"tanggal_ujian\" style=\"font-size:1em;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" value=\"$data_dops[tgl_ujian]\" /></td>";
						echo "</tr>";
						//Dosen Penilai
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Dosen Penilai</strong></td>";
						$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_dops[dosen]'"));
						echo "<td style=\"font-weight:600;\">
						$data_dosen[nama], <span style=\"color:red\">$data_dosen[gelar]</span> (<span style=\"color:blue\">$data_dosen[nip]</span>)</td>";
						echo "</tr>";
						//Situasi Ruangan
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Situasi Ruangan</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						if ($data_dops['situasi_ruangan'] == "IRD")
							echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"IRD\" checked/>&nbsp;&nbsp;IRD";
						else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"IRD\" />&nbsp;&nbsp;IRD";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_dops['situasi_ruangan'] == "Bangsal")
							echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Bangsal\" checked/>&nbsp;&nbsp;Bangsal";
						else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Bangsal\" />&nbsp;&nbsp;Bangsal";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_dops['situasi_ruangan'] == "Lapangan/Lain-lain")
							echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Lapangan/Lain-lain\" checked/>&nbsp;&nbsp;Lapangan/Lain-lain";
						else echo "<input type=\"radio\" name=\"situasi_ruangan\" value=\"Lapangan/Lain-lain\" />&nbsp;&nbsp;Lapangan/Lain-lain";
						echo "</td>";
						echo "</tr>";
						//Jenis Tindak Medik
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Jenis Tindak Medik</strong></td>";
						echo "<td><textarea name=\"tindak_medik\" style=\" width: 100%;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" required>$data_dops[tindak_medik]</textarea></td>";
						echo "</tr>";
						//Jumlah tindak medik serupa yang pernah diobservasi penilai
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Jumlah tindak medik serupa yang pernah diobservasi penilai</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						if ($data_dops['obs_penilai'] == "0")
							echo "<input type=\"radio\" name=\"obs_penilai\" value=\"0\" checked/>&nbsp;&nbsp;0";
						else echo "<input type=\"radio\" name=\"obs_penilai\" value=\"0\" />&nbsp;&nbsp;0";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_dops['obs_penilai'] == "1")
							echo "<input type=\"radio\" name=\"obs_penilai\" value=\"1\" checked/>&nbsp;&nbsp;1";
						else echo "<input type=\"radio\" name=\"obs_penilai\" value=\"1\" />&nbsp;&nbsp;1";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_dops['obs_penilai'] == "2")
							echo "<input type=\"radio\" name=\"obs_penilai\" value=\"2\" checked/>&nbsp;&nbsp;2";
						else echo "<input type=\"radio\" name=\"obs_penilai\" value=\"2\" />&nbsp;&nbsp;2";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_dops['obs_penilai'] == "3")
							echo "<input type=\"radio\" name=\"obs_penilai\" value=\"3\" checked/>&nbsp;&nbsp;3";
						else echo "<input type=\"radio\" name=\"obs_penilai\" value=\"3\" />&nbsp;&nbsp;3";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_dops['obs_penilai'] == "5-9")
							echo "<input type=\"radio\" name=\"obs_penilai\" value=\"5-9\" checked/>&nbsp;&nbsp;5-9";
						else echo "<input type=\"radio\" name=\"obs_penilai\" value=\"5-9\" />&nbsp;&nbsp;5-9";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_dops['obs_penilai'] == ">9")
							echo "<input type=\"radio\" name=\"obs_penilai\" value=\">9\" checked/>&nbsp;&nbsp;>9";
						else echo "<input type=\"radio\" name=\"obs_penilai\" value=\">9\" />&nbsp;&nbsp;>9";
						echo "</td>";
						echo "</tr>";
						//Jumlah tindak medik serupa yang pernah dilakukan mahasiswa
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Jumlah tindak medik serupa yang pernah dilakukan mahasiswa</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						if ($data_dops['obs_mhsw'] == "0")
							echo "<input type=\"radio\" name=\"obs_mhsw\" value=\"0\" checked/>&nbsp;&nbsp;0";
						else echo "<input type=\"radio\" name=\"obs_mhsw\" value=\"0\" />&nbsp;&nbsp;0";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_dops['obs_mhsw'] == "1")
							echo "<input type=\"radio\" name=\"obs_mhsw\" value=\"1\" checked/>&nbsp;&nbsp;1";
						else echo "<input type=\"radio\" name=\"obs_mhsw\" value=\"1\" />&nbsp;&nbsp;1";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_dops['obs_mhsw'] == "2")
							echo "<input type=\"radio\" name=\"obs_mhsw\" value=\"2\" checked/>&nbsp;&nbsp;2";
						else echo "<input type=\"radio\" name=\"obs_mhsw\" value=\"2\" />&nbsp;&nbsp;2";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_dops['obs_mhsw'] == "3")
							echo "<input type=\"radio\" name=\"obs_mhsw\" value=\"3\" checked/>&nbsp;&nbsp;3";
						else echo "<input type=\"radio\" name=\"obs_mhsw\" value=\"3\" />&nbsp;&nbsp;3";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_dops['obs_mhsw'] == "5-9")
							echo "<input type=\"radio\" name=\"obs_mhsw\" value=\"5-9\" checked/>&nbsp;&nbsp;5-9";
						else echo "<input type=\"radio\" name=\"obs_mhsw\" value=\"5-9\" />&nbsp;&nbsp;5-9";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_dops['obs_mhsw'] == ">9")
							echo "<input type=\"radio\" name=\"obs_mhsw\" value=\">9\" checked/>&nbsp;&nbsp;>9";
						else echo "<input type=\"radio\" name=\"obs_mhsw\" value=\">9\" />&nbsp;&nbsp;>9";
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
						echo "<td><strong>Mempunyai pengetahuan tentang indikasi relevansi anatomic dan teknik tindak medik</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_dops['observasi_1'] == "1")
							echo "<input type=\"radio\" name=\"observasi1\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
						else echo "<input type=\"radio\" name=\"observasi1\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_dops['observasi_1'] == "0")
							echo "<input type=\"radio\" name=\"observasi1\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
						else echo "<input type=\"radio\" name=\"observasi1\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek1\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_dops[aspek_1]\" id=\"aspek1\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 2
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>2</strong></td>";
						echo "<td><strong>Mendapat persetujuan tindak medik</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_dops['observasi_2'] == "1")
							echo "<input type=\"radio\" name=\"observasi2\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
						else echo "<input type=\"radio\" name=\"observasi2\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_dops['observasi_2'] == "0")
							echo "<input type=\"radio\" name=\"observasi2\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
						else echo "<input type=\"radio\" name=\"observasi2\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek2\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_dops[aspek_2]\" id=\"aspek2\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 3
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>3</strong></td>";
						echo "<td><strong>Mampu mengajukan persiapan yang sesuai sebelum tindak medik</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_dops['observasi_3'] == "1")
							echo "<input type=\"radio\" name=\"observasi3\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
						else echo "<input type=\"radio\" name=\"observasi3\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_dops['observasi_3'] == "0")
							echo "<input type=\"radio\" name=\"observasi3\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
						else echo "<input type=\"radio\" name=\"observasi3\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek3\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_dops[aspek_3]\" id=\"aspek3\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 4
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>4</strong></td>";
						echo "<td><strong>Mampu memberikan analgesic yang sesuai atau sedasi yang aman</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_dops['observasi_4'] == "1")
							echo "<input type=\"radio\" name=\"observasi4\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
						else echo "<input type=\"radio\" name=\"observasi4\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_dops['observasi_4'] == "0")
							echo "<input type=\"radio\" name=\"observasi4\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
						else echo "<input type=\"radio\" name=\"observasi4\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek4\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_dops[aspek_4]\" id=\"aspek4\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 5
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>5</strong></td>";
						echo "<td><strong>Kemampuan secara teknik</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_dops['observasi_5'] == "1")
							echo "<input type=\"radio\" name=\"observasi5\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
						else echo "<input type=\"radio\" name=\"observasi5\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_dops['observasi_5'] == "0")
							echo "<input type=\"radio\" name=\"observasi5\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
						else echo "<input type=\"radio\" name=\"observasi5\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek5\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_dops[aspek_5]\" id=\"aspek5\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 6
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>6</strong></td>";
						echo "<td><strong>Melakukan tindakan aseptic</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_dops['observasi_6'] == "1")
							echo "<input type=\"radio\" name=\"observasi6\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
						else echo "<input type=\"radio\" name=\"observasi6\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_dops['observasi_6'] == "0")
							echo "<input type=\"radio\" name=\"observasi6\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
						else echo "<input type=\"radio\" name=\"observasi6\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek6\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_dops[aspek_6]\" id=\"aspek6\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 7
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>7</strong></td>";
						echo "<td><strong>Mencari bantuan bila diperlukan</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_dops['observasi_7'] == "1")
							echo "<input type=\"radio\" name=\"observasi7\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
						else echo "<input type=\"radio\" name=\"observasi7\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_dops['observasi_7'] == "0")
							echo "<input type=\"radio\" name=\"observasi7\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
						else echo "<input type=\"radio\" name=\"observasi7\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek7\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_dops[aspek_7]\" id=\"aspek7\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 8
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>8</strong></td>";
						echo "<td><strong>Tatalaksana paska tindakan</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_dops['observasi_8'] == "1")
							echo "<input type=\"radio\" name=\"observasi8\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
						else echo "<input type=\"radio\" name=\"observasi8\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_dops['observasi_8'] == "0")
							echo "<input type=\"radio\" name=\"observasi8\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
						else echo "<input type=\"radio\" name=\"observasi8\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek8\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_dops[aspek_8]\" id=\"aspek8\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 9
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>9</strong></td>";
						echo "<td><strong>Kecakapan komunikasi</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_dops['observasi_9'] == "1")
							echo "<input type=\"radio\" name=\"observasi9\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
						else echo "<input type=\"radio\" name=\"observasi9\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_dops['observasi_9'] == "0")
							echo "<input type=\"radio\" name=\"observasi9\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
						else echo "<input type=\"radio\" name=\"observasi9\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek9\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_dops[aspek_9]\" id=\"aspek9\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 10
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>10</strong></td>";
						echo "<td><strong>Mempertimbangkan kondisi pasien / profesionalisme</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_dops['observasi_10'] == "1")
							echo "<input type=\"radio\" name=\"observasi10\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
						else echo "<input type=\"radio\" name=\"observasi10\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_dops['observasi_10'] == "0")
							echo "<input type=\"radio\" name=\"observasi10\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
						else echo "<input type=\"radio\" name=\"observasi10\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek10\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_dops[aspek_10]\" id=\"aspek10\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 11
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>11</strong></td>";
						echo "<td><strong>Kemampuan secara keseluruhan dalam melakukan tindak medik</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_dops['observasi_11'] == "1")
							echo "<input type=\"radio\" name=\"observasi11\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya";
						else echo "<input type=\"radio\" name=\"observasi11\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya";
						echo "&nbsp;&nbsp;&nbsp;&nbsp;";
						if ($data_dops['observasi_11'] == "0")
							echo "<input type=\"radio\" name=\"observasi11\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak</td>";
						else echo "<input type=\"radio\" name=\"observasi11\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek11\" style=\"width:60%;font-size:0.85em;text-align:center\" value=\"$data_dops[aspek_11]\" id=\"aspek11\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//Rata Nilai
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=right colspan=3><strong>Rata-Rata Nilai <span class=\"text-danger\">(Jumlah Nilai / Jumlah Observasi)</span></strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_rata\" style=\"width:55%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"nilai_rata\" value=\"$data_dops[nilai_rata]\" required disabled/></td>";
						echo "</tr>";
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
						<td colspan=4><font style=\"font-size:0.9em; font-weight:600;\">Keterangan: Nilai Batas Lulus (NBL) = <span class=\"text-danger\">70</span></font></td></tr>";
						echo "</table><br><br>";

						//Umpan Balik
						echo "<table class=\"table table-bordered\" style=\"width:70%\">";
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">
						<td colspan=2 align=center><strong>UMPAN BALIK TERHADAP KECAKAPAN TINDAK MEDIK</strong></td></tr>";
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center style=\"width:50%; font-weight:600;color:blue\">Sudah Bagus</td>";
						echo "<td align=center style=\"width:50%; font-weight:600;color:red\">Perlu Diperbaiki</td>";
						echo "</tr>";
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><textarea name=\"ub_bagus\" rows=5 style=\"width:100%;font-family:Poppins;border:0.2px solid black;border-radius:3px;font-size:1em;\">$data_dops[ub_bagus]</textarea></td>";
						echo "<td><textarea name=\"ub_perbaikan\" rows=5 style=\"width:100%;font-family:Poppins;border:0.2px solid black;border-radius:3px;font-size:1em;\" placeholder=\"<<Aspek Diperbaiki>>\"></textarea></td>";
						echo "</tr>";
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=2><strong>Saran:</strong><br><textarea name=\"saran\" rows=5 style=\"width:100%;margin-top:10px;font-family:Poppins;border:0.2px solid black;border-radius:3px;font-size:1em;\" >$data_dops[ub_perbaikan]</textarea></td>";
						echo "</tr>";

						///Catatan
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
						<td colspan=2><strong>Catatan:</strong></td></tr>";
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">
						<td colspan=2><strong>Waktu Penilaian Diskusi Kasus:</strong></td></tr>";
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>&nbsp;&nbsp;Observasi</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						echo "<input type=\"number\" step=\"5\" min=\"0\" max=\"1500\" name=\"waktu_observasi\" style=\"width:20%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_dops[waktu_observasi]\" required/>&nbsp;&nbsp;Menit<br>";
						echo "</td>";
						echo "</tr>";
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>&nbsp;&nbsp;Memberikan umpan balik</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						echo "<input type=\"number\" step=\"5\" min=\"0\" max=\"1500\" name=\"waktu_ub\" style=\"width:20%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_dops[waktu_ub]\" required/>&nbsp;&nbsp;Menit<br>";
						echo "</td>";
						echo "</tr>";
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
				window.location.href=\"penilaian_anestesi.php\";
			</script>
			";
						}

						if ($_POST['approve'] == "APPROVE") {
							$dosen_dops = mysqli_fetch_array(mysqli_query($con, "SELECT `dosen` FROM `anestesi_nilai_dops` WHERE `id`='$_POST[id]'"));
							$user_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `password` FROM `admin` WHERE `username`='$dosen_dops[dosen]'"));
							$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `pin`,`qr` FROM `dosen` WHERE `nip`='$dosen_dops[dosen]'"));
							$dosenpass_md5 = md5($_POST['dosenpass']);
							if (($_POST['dosenpass'] != "" and $dosenpass_md5 == $user_dosen['password'])
								or ($_POST['dosenpin'] != "" and $_POST['dosenpin'] == $data_dosen['pin'])
								or ($_POST['dosenqr'] != "" and $_POST['dosenqr'] == $data_dosen['qr'])
							) {
								$aspek1 = number_format($_POST['observasi1'] * $_POST['aspek1'], 2);
								$aspek2 = number_format($_POST['observasi2'] * $_POST['aspek2'], 2);
								$aspek3 = number_format($_POST['observasi3'] * $_POST['aspek3'], 2);
								$aspek4 = number_format($_POST['observasi4'] * $_POST['aspek4'], 2);
								$aspek5 = number_format($_POST['observasi5'] * $_POST['aspek5'], 2);
								$aspek6 = number_format($_POST['observasi6'] * $_POST['aspek6'], 2);
								$aspek7 = number_format($_POST['observasi7'] * $_POST['aspek7'], 2);
								$aspek8 = number_format($_POST['observasi8'] * $_POST['aspek8'], 2);
								$aspek9 = number_format($_POST['observasi9'] * $_POST['aspek9'], 2);
								$aspek10 = number_format($_POST['observasi10'] * $_POST['aspek10'], 2);
								$aspek11 = number_format($_POST['observasi11'] * $_POST['aspek11'], 2);

								$tindak_medik = addslashes($_POST['tindak_medik']);
								$ub_bagus = addslashes($_POST['ub_bagus']);
								$ub_perbaikan = addslashes($_POST['ub_perbaikan']);
								$saran = addslashes($_POST['saran']);
								$jml_observasi = $_POST['observasi1'] + $_POST['observasi2'] + $_POST['observasi3'] + $_POST['observasi4'] + $_POST['observasi5'] + $_POST['observasi6'] + $_POST['observasi7'] + $_POST['observasi8'] + $_POST['observasi9'] + $_POST['observasi10'] + $_POST['observasi11'];
								$nilai_total = $_POST['observasi1'] * $_POST['aspek1'] + $_POST['observasi2'] * $_POST['aspek2'] + $_POST['observasi3'] * $_POST['aspek3'] + $_POST['observasi4'] * $_POST['aspek4'] + $_POST['observasi5'] * $_POST['aspek5'] + $_POST['observasi6'] * $_POST['aspek6'] + $_POST['observasi7'] * $_POST['aspek7']
									+ $_POST['observasi8'] * $_POST['aspek8'] + $_POST['observasi9'] * $_POST['aspek9'] + $_POST['observasi10'] * $_POST['aspek10'] + $_POST['observasi11'] * $_POST['aspek11'];
								if ($jml_observasi == 0) $nilai_rata = 0;
								else $nilai_rata = $nilai_total / $jml_observasi;
								$nilai_rata = number_format($nilai_rata, 2);

								$update_dops = mysqli_query($con, "UPDATE `anestesi_nilai_dops` SET
					`tgl_ujian`='$_POST[tanggal_ujian]',
					`situasi_ruangan`='$_POST[situasi_ruangan]',`tindak_medik`='$tindak_medik',
					`obs_penilai`='$_POST[obs_penilai]', `obs_mhsw`='$_POST[obs_mhsw]',
					`aspek_1`='$aspek1',`observasi_1`='$_POST[observasi1]',
					`aspek_2`='$aspek2',`observasi_2`='$_POST[observasi2]',
					`aspek_3`='$aspek3',`observasi_3`='$_POST[observasi3]',
					`aspek_4`='$aspek4',`observasi_4`='$_POST[observasi4]',
					`aspek_5`='$aspek5',`observasi_5`='$_POST[observasi5]',
					`aspek_6`='$aspek6',`observasi_6`='$_POST[observasi6]',
					`aspek_7`='$aspek7',`observasi_7`='$_POST[observasi7]',
					`aspek_8`='$aspek8',`observasi_8`='$_POST[observasi8]',
					`aspek_9`='$aspek9',`observasi_9`='$_POST[observasi9]',
					`aspek_10`='$aspek10',`observasi_10`='$_POST[observasi10]',
					`aspek_11`='$aspek11',`observasi_11`='$_POST[observasi11]',
					`ub_bagus`='$ub_bagus',`ub_perbaikan`='$ub_perbaikan',`saran`='$saran',
					`waktu_observasi`='$_POST[waktu_observasi]',`waktu_ub`='$_POST[waktu_ub]',
					`nilai_rata`='$nilai_rata', `tgl_approval`='$tgl',`status_approval`='1'
					WHERE `id`='$_POST[id]'");

								echo "
					<script>
					alert(\"Approval SUKSES !\");
					window.location.href = \"penilaian_anestesi.php\";
	        </script>
					";
							} else {
								echo "
				<script>
				alert(\"Approval GAGAL !\");
				window.location.href = \"approve_dops.php?id=\"+\"$_POST[id]\";
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
	<script type="text/javascript" src="../jquery_ui/jquery-ui.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('.tanggal_ujian').datepicker({
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
			var aspek8 = document.getElementById('aspek8').value;
			var aspek9 = document.getElementById('aspek9').value;
			var aspek10 = document.getElementById('aspek10').value;
			var aspek11 = document.getElementById('aspek11').value;

			var observasi1 = $("input[name=observasi1]:checked").val();
			var observasi2 = $("input[name=observasi2]:checked").val();
			var observasi3 = $("input[name=observasi3]:checked").val();
			var observasi4 = $("input[name=observasi4]:checked").val();
			var observasi5 = $("input[name=observasi5]:checked").val();
			var observasi6 = $("input[name=observasi6]:checked").val();
			var observasi7 = $("input[name=observasi7]:checked").val();
			var observasi8 = $("input[name=observasi8]:checked").val();
			var observasi9 = $("input[name=observasi9]:checked").val();
			var observasi10 = $("input[name=observasi10]:checked").val();
			var observasi11 = $("input[name=observasi11]:checked").val();

			var total1 = parseInt(observasi1) * parseFloat(aspek1) + parseInt(observasi2) * parseFloat(aspek2) + parseInt(observasi3) * parseFloat(aspek3) + parseInt(observasi4) * parseFloat(aspek4) + parseInt(observasi5) * parseFloat(aspek5) + parseInt(observasi6) * parseFloat(aspek6) + parseInt(observasi7) * parseFloat(aspek7);
			var total2 = parseInt(observasi8) * parseFloat(aspek8) + parseInt(observasi9) * parseFloat(aspek9) + parseInt(observasi10) * parseFloat(aspek10) + parseInt(observasi11) * parseFloat(aspek11);
			var total = parseFloat(total1) + parseFloat(total2);

			var pembagi1 = parseInt(observasi1) + parseInt(observasi2) + parseInt(observasi3) + parseInt(observasi4) + parseInt(observasi5) + parseInt(observasi6) + parseInt(observasi7);
			var pembagi2 = parseInt(observasi8) + parseInt(observasi9) + parseInt(observasi10) + parseInt(observasi11);
			var pembagi = parseInt(pembagi1) + parseInt(pembagi2);

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