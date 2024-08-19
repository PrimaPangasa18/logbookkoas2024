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
	<link rel="stylesheet" type="text/css" href="../jquery_ui/jquery-ui.css">
	<link rel="stylesheet" href="../select2/dist/css/select2.css" />

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
						<h3 class="fw-bold fs-4 mb-3">PENILAIAN KEPANITERAAN (STASE) ILMU KESEHATAN MATA</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">APPROVAL NILAI UJIAN MINI-CEX<br>KEPANITERAAN (STASE) ILMU KESEHATAN MATA</h2>
						<br>
						<?php
						$id_stase = "M104";
						$id = $_GET['id'];
						$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
						$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
						$stase_id = "stase_" . $id_stase;
						$data_stase_mhsw = mysqli_query($con, "SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'");
						$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);
						$data_minicex = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `mata_nilai_minicex` WHERE `id`='$id'"));

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
						//Periode Stase
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						$mulai_stase = tanggal_indo($datastase_mhsw['tgl_mulai']);
						$selesai_stase = tanggal_indo($datastase_mhsw['tgl_selesai']);
						echo "<td class=\"td_mid\"><strong>Periode Kepaniteraan (STASE)</strong></td>";
						echo "<td class=\"td_mid\" style=\"font-weight:600;\"><span style=\"color:darkblue\">$mulai_stase</span> s.d. <span style=\"color:darkblue\">$selesai_stase</span></td>";
						echo "</tr>";
						//Tanggal Ujian
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td class=\"td_mid\"><strong>Tanggal Ujian <span class=\"text-danger\">(yyyy-mm-dd)</span></strong></td>";
						echo "<td class=\"td_mid\"><input type=\"text\" class=\"form-select tanggal_ujian\" name=\"tanggal_ujian\" style=\"font-size:1em;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" value=\"$data_minicex[tgl_ujian]\" /></td>";
						echo "</tr>";
						//Waktu Observasi
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td class=\"td_mid\"><strong>Waktu Observasi</strong></td>";
						echo "<td class=\"td_mid\" style=\"font-weight:600;\"><input type=\"number\" step=\"5\" min=\"0\" max=\"3600\" name=\"waktu_obs\" style=\"text-align:center;width:20%; height:30px;border:0.2px solid black;border-radius:3px;font-size:1.0em\" value=\"$data_minicex[waktu_obs]\" required/>&nbsp;&nbsp;Menit</td>";
						echo "</tr>";
						//Waktu Umpan Balik
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td class=\"td_mid\"><strong>Waktu Umpan Balik</strong></td>";
						echo "<td class=\"td_mid\" style=\"font-weight:600;\"><input type=\"number\" step=\"5\" min=\"0\" max=\"3600\" name=\"waktu_ub\" style=\"text-align:center;width:20%; height:30px;border:0.2px solid black;border-radius:3px;font-size:1.0em\" value=\"$data_minicex[waktu_ub]\" required/>&nbsp;&nbsp;Menit</td>";
						echo "</tr>";
						//Dosen Penguji
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Dosen Penguji</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_minicex[dosen]'"));
						echo "$data_dosen[nama], <span style=\"color:red\">$data_dosen[gelar]</span> (<span style=\"color:blue\">$data_dosen[nip]</span>)";
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
						echo "<th style=\"width:58%;text-align:center;\">Komponen Penilaian Ketrampilan</th>";
						echo "<th style=\"width:22%;text-align:center;\">Observasi</th>";
						echo "<th style=\"width:20%;text-align:center;\">Nilai (0-100)</th>";
						echo "</thead>";
						//KOMUNIKASI
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=4 align=center><strong>1. KOMUNIKASI</strong></td>";
						echo "</tr>";
						//No 1.1.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>1.1.</strong></td>";
						echo "<td><strong>Memperkenalkan diri dan menjelaskan perannya kepada pasien</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_11'] == "1")
							echo "<input type=\"radio\" name=\"observasi11\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type=\"radio\" name=\"observasi11\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
						else
							echo "<input type=\"radio\" name=\"observasi11\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type=\"radio\" name=\"observasi11\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
						echo "</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek11\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_11]\" id=\"aspek11\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 1.2.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>1.2.</strong></td>";
						echo "<td><strong>Memperlihatkan kontak mata yang baik</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_12'] == "1")
							echo "<input type=\"radio\" name=\"observasi12\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type=\"radio\" name=\"observasi12\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
						else
							echo "<input type=\"radio\" name=\"observasi12\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<input type=\"radio\" name=\"observasi12\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
						echo "</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek12\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_12]\" id=\"aspek12\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 1.3.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>1.3.</strong></td>";
						echo "<td><strong>Mendengarkan pasien tanpa menginterupsi</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_13'] == "1")
							echo "<input type=\"radio\" name=\"observasi13\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi13\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
						else
							echo "<input type=\"radio\" name=\"observasi13\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi13\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
						echo "</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek13\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_13]\" id=\"aspek13\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 1.4.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>1.4.</strong></td>";
						echo "<td><strong>Mengekspresikan perhatiannya kepada pasien</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_14'] == "1")
							echo "<input type=\"radio\" name=\"observasi14\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi14\" value=\"0\" />&nbsp;&nbsp;Tidak";
						else
							echo "<input type=\"radio\" name=\"observasi14\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi14\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
						echo "</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek14\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_14]\" id=\"aspek14\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 1.5.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>1.5.</strong></td>";
						echo "<td><strong>Bertanya dengan pertanyaan terbuka</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_15'] == "1")
							echo "<input type=\"radio\" name=\"observasi15\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi15\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
						else
							echo "<input type=\"radio\" name=\"observasi15\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi15\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
						echo "</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek15\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_15]\" id=\"aspek15\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 1.6.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>1.6.</strong></td>";
						echo "<td><strong>Memberi kesempatan kepada pasien untuk bertanya</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_16'] == "1")
							echo "<input type=\"radio\" name=\"observasi16\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi16\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
						else
							echo "<input type=\"radio\" name=\"observasi16\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi16\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
						echo "</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek16\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_16]\" id=\"aspek16\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 1.7.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>1.7.</strong></td>";
						echo "<td><strong>Menjelaskan perencanaan selanjutnya dengan baik</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_17'] == "1")
							echo "<input type=\"radio\" name=\"observasi17\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi17\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
						else
							echo "<input type=\"radio\" name=\"observasi17\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi17\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
						echo "</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek17\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_17]\" id=\"aspek17\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";

						//2. PROFESIONALISME
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=4 align=center><strong>2. PROFESIONALISME</strong></td>";
						echo "</tr>";
						//No 2.1.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>2.1.</strong></td>";
						echo "<td><strong>Mengenakan pakaian yang pantas</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_21'] == "1")
							echo "<input type=\"radio\" name=\"observasi21\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi21\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
						else
							echo "<input type=\"radio\" name=\"observasi21\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi21\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
						echo "</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek21\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_21]\" id=\"aspek21\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 2.2.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>2.2.</strong></td>";
						echo "<td><strong>Sopan / hormat pada pasien</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_22'] == "1")
							echo "<input type=\"radio\" name=\"observasi22\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi22\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
						else
							echo "<input type=\"radio\" name=\"observasi22\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi22\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
						echo "</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek22\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_22]\" id=\"aspek22\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 2.3.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>2.3.</strong></td>";
						echo "<td><strong>Memperlihatkan sikap profesional <span class=\"text-danger\">(memanggil nama pasien, memperlihatkan keseriusan dan kompeten)</span><strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_23'] == "1")
							echo "<input type=\"radio\" name=\"observasi23\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi23\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
						else
							echo "<input type=\"radio\" name=\"observasi23\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi23\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
						echo "</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek23\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_23]\" id=\"aspek23\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 2.4.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>2.4.</strong></td>";
						echo "<td><strong>Menghargai kebebasan / kerahasiaan pribadi</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_24'] == "1")
							echo "<input type=\"radio\" name=\"observasi24\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi24\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
						else
							echo "<input type=\"radio\" name=\"observasi24\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi24\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
						echo "</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek24\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_24]\" id=\"aspek24\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";

						//KETRAMPILAN KLINIK
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=4 align=center><strong>3. KETRAMPILAN KLINIK</strong></td>";
						echo "</tr>";
						//No 3.1.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>3.1.</strong></td>";
						echo "<td><strong>Mencuci tangan sebelum dan sesudah  memeriksa pasien</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_31'] == "1")
							echo "<input type=\"radio\" name=\"observasi31\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi31\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
						else
							echo "<input type=\"radio\" name=\"observasi31\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi31\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
						echo "</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek31\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_31]\" id=\"aspek31\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 3.2.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>3.2.</strong></td>";
						echo "<td><strong>Pemeriksaan visus</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_32'] == "1")
							echo "<input type=\"radio\" name=\"observasi32\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi32\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
						else
							echo "<input type=\"radio\" name=\"observasi32\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi32\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
						echo "</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek32\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_32]\" id=\"aspek32\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 3.3.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>3.3.</strong></td>";
						echo "<td><strong>Pemeriksaan segmen anterior</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_33'] == "1")
							echo "<input type=\"radio\" name=\"observasi33\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi33\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
						else
							echo "<input type=\"radio\" name=\"observasi33\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi33\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
						echo "</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek33\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_33]\" id=\"aspek33\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 3.4.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>3.4.</strong></td>";
						echo "<td><strong>Pemeriksaan funduskopi</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_34'] == "1")
							echo "<input type=\"radio\" name=\"observasi34\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi34\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
						else
							echo "<input type=\"radio\" name=\"observasi34\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi34\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
						echo "</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek34\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_34]\" id=\"aspek34\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 3.5.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>3.5.</strong></td>";
						echo "<td><strong>Pemeriksaan tonometri</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_35'] == "1")
							echo "<input type=\"radio\" name=\"observasi35\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi35\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
						else
							echo "<input type=\"radio\" name=\"observasi35\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi35\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
						echo "</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek35\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_35]\" id=\"aspek35\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 3.6.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>3.6.</strong></td>";
						echo "<td><strong>Pemeriksaan lapang pandang</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_36'] == "1")
							echo "<input type=\"radio\" name=\"observasi36\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi36\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
						else
							echo "<input type=\"radio\" name=\"observasi36\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi36\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
						echo "</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek36\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_36]\" id=\"aspek36\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";

						//CLINICAL REASONING
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=4 align=center><strong>4. CLINICAL REASONING</strong></td>";
						echo "</tr>";
						//No 4.1.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>4.1.</strong></td>";
						echo "<td><strong>Diagnosis</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_41'] == "1")
							echo "<input type=\"radio\" name=\"observasi41\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi41\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
						else
							echo "<input type=\"radio\" name=\"observasi41\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi41\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
						echo "</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek41\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_41]\" id=\"aspek41\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 4.2.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>4.2.</strong></td>";
						echo "<td><strong>Tatalaksana</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_42'] == "1")
							echo "<input type=\"radio\" name=\"observasi42\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi42\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
						else
							echo "<input type=\"radio\" name=\"observasi42\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi42\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
						echo "</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek42\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_42]\" id=\"aspek42\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 4.3.
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>5.3.</strong></td>";
						echo "<td><strong>Edukasi</strong></td>";
						echo "<td align=center style=\"font-weight:600;\">";
						if ($data_minicex['observasi_43'] == "1")
							echo "<input type=\"radio\" name=\"observasi43\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi43\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Tidak";
						else
							echo "<input type=\"radio\" name=\"observasi43\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;Ya&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			        <input type=\"radio\" name=\"observasi43\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;Tidak";
						echo "</td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek43\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_minicex[aspek_43]\" id=\"aspek43\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";

						//Nilai Total
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=3 align=right><strong>Nilai Rata-Rata <span class=\"text-danger\">(Jumlah Nilai / Jumlah Observasi)</span></strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_rata\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"nilai_rata\" value=\"$data_minicex[nilai_rata]\" required disabled/></td>";
						echo "</tr>";

						//Komentar / Saran
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=4><strong>Komentar / Saran:</strong><br>
						<textarea name=\"saran\" rows=5 style=\"width:100%;margin-top:10px;font-family:Poppins;font-size:1em\">$data_minicex[saran]</textarea></td>";
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
				window.location.href=\"penilaian_mata.php\";
			</script>
			";
						}

						if ($_POST['approve'] == "APPROVE") {
							$dosen_minicex = mysqli_fetch_array(mysqli_query($con, "SELECT `dosen` FROM `mata_nilai_minicex` WHERE `id`='$_POST[id]'"));
							$user_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `password` FROM `admin` WHERE `username`='$dosen_minicex[dosen]'"));
							$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `pin`,`qr` FROM `dosen` WHERE `nip`='$dosen_minicex[dosen]'"));
							$dosenpass_md5 = md5($_POST['dosenpass']);
							if (($_POST['dosenpass'] != "" and $dosenpass_md5 == $user_dosen['password'])
								or ($_POST['dosenpin'] != "" and $_POST['dosenpin'] == $data_dosen['pin'])
								or ($_POST['dosenqr'] != "" and $_POST['dosenqr'] == $data_dosen['qr'])
							) {
								$aspek11 = number_format($_POST['observasi11'] * $_POST['aspek11'], 2);
								$aspek12 = number_format($_POST['observasi12'] * $_POST['aspek12'], 2);
								$aspek13 = number_format($_POST['observasi13'] * $_POST['aspek13'], 2);
								$aspek14 = number_format($_POST['observasi14'] * $_POST['aspek14'], 2);
								$aspek15 = number_format($_POST['observasi15'] * $_POST['aspek15'], 2);
								$aspek16 = number_format($_POST['observasi16'] * $_POST['aspek16'], 2);
								$aspek17 = number_format($_POST['observasi17'] * $_POST['aspek17'], 2);

								$aspek21 = number_format($_POST['observasi21'] * $_POST['aspek21'], 2);
								$aspek22 = number_format($_POST['observasi22'] * $_POST['aspek22'], 2);
								$aspek23 = number_format($_POST['observasi23'] * $_POST['aspek23'], 2);
								$aspek24 = number_format($_POST['observasi24'] * $_POST['aspek24'], 2);

								$aspek31 = number_format($_POST['observasi31'] * $_POST['aspek31'], 2);
								$aspek32 = number_format($_POST['observasi32'] * $_POST['aspek32'], 2);
								$aspek33 = number_format($_POST['observasi33'] * $_POST['aspek33'], 2);
								$aspek34 = number_format($_POST['observasi34'] * $_POST['aspek34'], 2);
								$aspek35 = number_format($_POST['observasi35'] * $_POST['aspek35'], 2);
								$aspek36 = number_format($_POST['observasi36'] * $_POST['aspek36'], 2);

								$aspek41 = number_format($_POST['observasi41'] * $_POST['aspek41'], 2);
								$aspek42 = number_format($_POST['observasi42'] * $_POST['aspek42'], 2);
								$aspek43 = number_format($_POST['observasi43'] * $_POST['aspek43'], 2);

								$obs_komunikasi = $_POST['observasi11'] + $_POST['observasi12'] + $_POST['observasi13'] + $_POST['observasi14'] + $_POST['observasi15'] + $_POST['observasi16'] + $_POST['observasi17'];
								$asp_komunikasi = $_POST['observasi11'] * $_POST['aspek11'] + $_POST['observasi12'] * $_POST['aspek12'] + $_POST['observasi13'] * $_POST['aspek13'] + $_POST['observasi14'] * $_POST['aspek14'] + $_POST['observasi15'] * $_POST['aspek15'] + $_POST['observasi16'] * $_POST['aspek16'] + $_POST['observasi17'] * $_POST['aspek17'];

								$obs_profesionalisme = $_POST['observasi21'] + $_POST['observasi22'] + $_POST['observasi23'] + $_POST['observasi24'];
								$asp_profesionalisme = $_POST['observasi21'] * $_POST['aspek21'] + $_POST['observasi22'] * $_POST['aspek22'] + $_POST['observasi23'] * $_POST['aspek23'] + $_POST['observasi24'] * $_POST['aspek24'];

								$obs_ketrampilan = $_POST['observasi31'] + $_POST['observasi32'] + $_POST['observasi33'] + $_POST['observasi34'] + $_POST['observasi35'] + $_POST['observasi36'];
								$asp_ketrampilan = $_POST['observasi31'] * $_POST['aspek31'] + $_POST['observasi32'] * $_POST['aspek32'] + $_POST['observasi33'] * $_POST['aspek33'] + $_POST['observasi34'] * $_POST['aspek34'] + $_POST['observasi35'] * $_POST['aspek35'] + $_POST['observasi36'] * $_POST['aspek36'];

								$obs_clinical = $_POST['observasi41'] + $_POST['observasi42'] + $_POST['observasi43'];
								$asp_clinical = $_POST['observasi41'] * $_POST['aspek41'] + $_POST['observasi42'] * $_POST['aspek42'] + $_POST['observasi43'] * $_POST['aspek43'];

								$jml_aspek = $asp_komunikasi + $asp_profesionalisme + $asp_ketrampilan + $asp_clinical;
								$jml_obs = $obs_komunikasi + $obs_profesionalisme + $obs_ketrampilan + $obs_clinical;


								if ($jml_obs == 0) $rata_nilai = 0;
								else $rata_nilai = $jml_aspek / $jml_obs;
								$rata_nilai = number_format($rata_nilai, 2);

								$saran = addslashes($_POST['saran']);

								$update_minicex = mysqli_query($con, "UPDATE `mata_nilai_minicex` SET
					`tgl_ujian`='$_POST[tanggal_ujian]',
					`aspek_11`='$aspek11',`observasi_11`='$_POST[observasi11]',
					`aspek_12`='$aspek12',`observasi_12`='$_POST[observasi12]',
					`aspek_13`='$aspek13',`observasi_13`='$_POST[observasi13]',
					`aspek_14`='$aspek14',`observasi_14`='$_POST[observasi14]',
					`aspek_15`='$aspek15',`observasi_15`='$_POST[observasi15]',
					`aspek_16`='$aspek16',`observasi_16`='$_POST[observasi16]',
					`aspek_17`='$aspek17',`observasi_17`='$_POST[observasi17]',
					`aspek_21`='$aspek21',`observasi_21`='$_POST[observasi21]',
					`aspek_22`='$aspek22',`observasi_22`='$_POST[observasi22]',
					`aspek_23`='$aspek23',`observasi_23`='$_POST[observasi23]',
					`aspek_24`='$aspek24',`observasi_24`='$_POST[observasi24]',
					`aspek_31`='$aspek31',`observasi_31`='$_POST[observasi31]',
					`aspek_32`='$aspek32',`observasi_32`='$_POST[observasi32]',
					`aspek_33`='$aspek33',`observasi_33`='$_POST[observasi33]',
					`aspek_34`='$aspek34',`observasi_34`='$_POST[observasi34]',
					`aspek_35`='$aspek35',`observasi_35`='$_POST[observasi35]',
					`aspek_36`='$aspek36',`observasi_36`='$_POST[observasi36]',
					`aspek_41`='$aspek41',`observasi_41`='$_POST[observasi41]',
					`aspek_42`='$aspek42',`observasi_42`='$_POST[observasi42]',
					`aspek_43`='$aspek43',`observasi_43`='$_POST[observasi43]',
					`saran`='$saran',`waktu_obs`='$_POST[waktu_obs]',`waktu_ub`='$_POST[waktu_ub]',
					`nilai_rata`='$rata_nilai',`tgl_approval`='$tgl',`status_approval`='1'
					WHERE `id`='$_POST[id]'");


								echo "
					<script>
					alert(\"Approval SUKSES !\");
					window.location.href = \"penilaian_mata.php\";
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
	<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
	<script src="../select2/dist/js/select2.js"></script>
	<script>
		$(document).ready(function() {
			$("#dosen").select2({
				placeholder: "<< Dosen Penguji >>",
				allowClear: true
			});

		});
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
			var aspek11 = document.getElementById('aspek11').value;
			var aspek12 = document.getElementById('aspek12').value;
			var aspek13 = document.getElementById('aspek13').value;
			var aspek14 = document.getElementById('aspek14').value;
			var aspek15 = document.getElementById('aspek15').value;
			var aspek16 = document.getElementById('aspek16').value;
			var aspek17 = document.getElementById('aspek17').value;
			var observasi11 = $("input[name=observasi11]:checked").val();
			var observasi12 = $("input[name=observasi12]:checked").val();
			var observasi13 = $("input[name=observasi13]:checked").val();
			var observasi14 = $("input[name=observasi14]:checked").val();
			var observasi15 = $("input[name=observasi15]:checked").val();
			var observasi16 = $("input[name=observasi16]:checked").val();
			var observasi17 = $("input[name=observasi17]:checked").val();

			var total1 = parseInt(observasi11) * parseFloat(aspek11) + parseInt(observasi12) * parseFloat(aspek12) + parseInt(observasi13) * parseFloat(aspek13) + parseInt(observasi14) * parseFloat(aspek14) + parseInt(observasi15) * parseFloat(aspek15) + parseInt(observasi16) * parseFloat(aspek16) + parseInt(observasi17) * parseFloat(aspek17);
			var pembagi1 = parseInt(observasi11) + parseInt(observasi12) + parseInt(observasi13) + parseInt(observasi14) + parseInt(observasi15) + parseInt(observasi16) + parseInt(observasi17);

			var aspek21 = document.getElementById('aspek21').value;
			var aspek22 = document.getElementById('aspek22').value;
			var aspek23 = document.getElementById('aspek23').value;
			var aspek24 = document.getElementById('aspek24').value;
			var observasi21 = $("input[name=observasi21]:checked").val();
			var observasi22 = $("input[name=observasi22]:checked").val();
			var observasi23 = $("input[name=observasi23]:checked").val();
			var observasi24 = $("input[name=observasi24]:checked").val();

			var total2 = parseInt(observasi21) * parseFloat(aspek21) + parseInt(observasi22) * parseFloat(aspek22) + parseInt(observasi23) * parseFloat(aspek23) + parseInt(observasi24) * parseFloat(aspek24);
			var pembagi2 = parseInt(observasi21) + parseInt(observasi22) + parseInt(observasi23) + parseInt(observasi24);

			var aspek31 = document.getElementById('aspek31').value;
			var aspek32 = document.getElementById('aspek32').value;
			var aspek33 = document.getElementById('aspek33').value;
			var aspek34 = document.getElementById('aspek34').value;
			var aspek35 = document.getElementById('aspek35').value;
			var aspek36 = document.getElementById('aspek36').value;
			var observasi31 = $("input[name=observasi31]:checked").val();
			var observasi32 = $("input[name=observasi32]:checked").val();
			var observasi33 = $("input[name=observasi33]:checked").val();
			var observasi34 = $("input[name=observasi34]:checked").val();
			var observasi35 = $("input[name=observasi35]:checked").val();
			var observasi36 = $("input[name=observasi36]:checked").val();

			var total3 = parseInt(observasi31) * parseFloat(aspek31) + parseInt(observasi32) * parseFloat(aspek32) + parseInt(observasi33) * parseFloat(aspek33) + parseInt(observasi34) * parseFloat(aspek34) + parseInt(observasi35) * parseFloat(aspek35) + parseInt(observasi36) * parseFloat(aspek36);
			var pembagi3 = parseInt(observasi31) + parseInt(observasi32) + parseInt(observasi33) + parseInt(observasi34) + parseInt(observasi35) + parseInt(observasi36);

			var aspek41 = document.getElementById('aspek41').value;
			var aspek42 = document.getElementById('aspek42').value;
			var aspek43 = document.getElementById('aspek43').value;
			var observasi41 = $("input[name=observasi41]:checked").val();
			var observasi42 = $("input[name=observasi42]:checked").val();
			var observasi43 = $("input[name=observasi43]:checked").val();

			var total4 = parseInt(observasi41) * parseFloat(aspek41) + parseInt(observasi42) * parseFloat(aspek42) + parseInt(observasi43) * parseFloat(aspek43);
			var pembagi4 = parseInt(observasi41) + parseInt(observasi42) + parseInt(observasi43);

			var result_total = parseFloat(total1) + parseFloat(total2) + parseFloat(total3) + parseFloat(total4);
			var pembagi_total = parseInt(pembagi1) + parseInt(pembagi2) + parseInt(pembagi3) + parseInt(pembagi4);
			var result = parseFloat(result_total) / parseInt(pembagi_total);

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