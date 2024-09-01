<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Approval Kasus Besar Logbook Koas Pendidikan Dokter FK-UNDIP</title>
	<link rel="shortcut icon" type="x-icon" href="../images/undipsolid.png">
	<link rel="stylesheet" href="../style/style1.css" />
	<link rel="stylesheet" href="../style/buttontopup.css">
	<link rel="stylesheet" href="../qr_code.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="../text-security-master/dist/text-security.css" type="text/css" media="screen" />
	<link rel="stylesheet" type="text/css" href="../jquery_ui/jquery-ui.css">

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
						<h3 class="fw-bold fs-4 mb-3">PENILAIAN KEPANITERAAN (STASE) ILMU KESEHATAN ANAK</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">APPROVAL NILAI PENYAJIAN KASUS BESAR<br>KEPANITERAAN (STASE) ILMU KESEHATAN ANAK</h2>
						<br>
						<?php
						$id = $_GET['id'];
						$data_kasus = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `ika_nilai_kasus` WHERE `id`='$id'"));
						$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_kasus[nim]'"));

						echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
						echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
						echo "<center>";
						echo "<table  class=\"table table-bordered\" style=\"width:70%\">";

						//Nama mahasiswa koas
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td style=\"width:40%\"><strong>Nama Mahasiswa</strong></td>";
						echo "<td style=\"width:60%;font-weight:600; color:darkgreen\">$data_mhsw[nama]</td>";
						echo "</tr>";
						//NIM
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>NIM</strong></td>";
						echo "<td style=\" font-weight:600; color:red\">$data_mhsw[nim]</td>";
						echo "</tr>";
						//Periode Kegiatan
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td class=\"td_mid\"><strong>Periode Stase <span class=\"text-danger\">(yyyy-mm-dd)</span><strong></td>";
						echo "<td class=\"td_mid\">
						<input type=\"text\" class=\"tgl_awal\" name=\"tgl_awal\" style=\"font-size:1em;font-family:Poppins;border:0.5px solid black;border-radius:5px; text-align:center\" value=\"$data_kasus[tgl_awal]\" />";
						echo " <span style=\" font-weight:600;\">s.d.</span> <input type=\"text\" class=\"tgl_akhir\" name=\"tgl_akhir\" style=\"font-size:1em;font-family:Poppins;border:0.5px solid black;border-radius:5px; text-align:center\" value=\"$data_kasus[tgl_akhir]\" /></td>";
						echo "</tr>";
						//Judul Kasus
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Kasus</strong></td>";
						echo "<td><textarea name=\"kasus\" style=\" width: 100%;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" required>$data_kasus[kasus]</textarea></td>";
						echo "</tr>";
						//Tanggal Penyajian
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td class=\"td_mid\"><strong>Tanggal Penyajian <span class=\"text-danger\">(yyyy-mm-dd)</span><strong></td>";
						echo "<td class=\"td_mid\">
						<input type=\"text\" class=\"form-select tgl_penyajian\" name=\"tgl_penyajian\" style=\"font-size:1em;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" value=\"$data_kasus[tgl_penyajian]\" /></td>";
						echo "</tr>";
						//Dosen Penilai
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Dosen Penilai</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_kasus[dosen]'"));
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
						echo "<th style=\"width:55%;text-align:center;\">Komponen Penilaian</th>";
						echo "<th style=\"width:20%;text-align:center;\">Bobot</th>";
						echo "<th style=\"width:20%;text-align:center;\">Nilai (0-100)</th>";
						echo "</thead>";
						//Aspek Penyajian
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=4 align=center><strong>Aspek Penyajian:</strong></td>";
						echo "</tr>";
						//No 1
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>1</strong></td>";
						echo "<td><strong>Slide</strong></td>";
						echo "<td align=center><strong>5%</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek1\" style=\"width:50%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek1\" value=\"$data_kasus[aspek_1]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 2
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>2</strong></td>";
						echo "<td><strong>Sikap dan suara</strong></td>";
						echo "<td align=center><strong>10%</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek2\" style=\"width:50%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek2\" value=\"$data_kasus[aspek_2]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//Aspek Naskah
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=4 align=center><strong>Aspek Naskah:</strong></td>";
						echo "</tr>";
						//No 3
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>3</strong></td>";
						echo "<td><strong>Kesesuaian format penulisan</strong></td>";
						echo "<td align=center><strong>2.5%</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek3\" style=\"width:50%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek3\" value=\"$data_kasus[aspek_3]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 4
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>4</strong></td>";
						echo "<td><strong>Ringkasan kasus</strong></td>";
						echo "<td align=center><strong>5%</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek4\" style=\"width:50%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek4\" value=\"$data_kasus[aspek_4]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 5
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>5</strong></td>";
						echo "<td><strong>Pembahasan dan aplikasi EBM <span class=\"text-danger\">(PICO-VIA)</span></strong></td>";
						echo "<td align=center><strong>5%</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek5\" style=\"width:50%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek5\" value=\"$data_kasus[aspek_5]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 6
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>6</strong></td>";
						echo "<td><strong>Kepustakaan</strong></td>";
						echo "<td align=center><strong>2.5%</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek6\" style=\"width:50%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek6\" value=\"$data_kasus[aspek_6]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//Aspek Diskusi
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=4 align=center><strong>Aspek Diskusi:</strong></td>";
						echo "</tr>";
						//No 7
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>7</strong></td>";
						echo "<td><strong>Anamnesis <span class=\"text-danger\">(<i>sacred seven, fundamental four</i>)</span></strong></td>";
						echo "<td align=center><strong>10%</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek7\" style=\"width:50%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek7\" value=\"$data_kasus[aspek_7]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 8
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>8</strong></td>";
						echo "<td><strong>Pemeriksaan Fisik <span class=\"text-danger\">(<i>status lokalis dan generalis</i>)</span></strong></td>";
						echo "<td align=center><strong>10%</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek8\" style=\"width:50%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek8\" value=\"$data_kasus[aspek_8]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 9
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>9</strong></td>";
						echo "<td><strong>Pemeriksaan Penunjang <span class=\"text-danger\">(jenis dan interpretasi hasil)</span></strong></td>";
						echo "<td align=center><strong>10%</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek9\" style=\"width:50%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek9\" value=\"$data_kasus[aspek_9]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 10
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>10</strong></td>";
						echo "<td><strong>Diagnosis Banding/Diagnosis Kerja</strong></td>";
						echo "<td align=center><strong>10%</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek10\" style=\"width:50%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek10\" value=\"$data_kasus[aspek_10]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 11
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>11</strong></td>";
						echo "<td><strong>Tatalaksana</strong></td>";
						echo "<td align=center><strong>10%</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek11\" style=\"width:50%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek11\" value=\"$data_kasus[aspek_11]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 12
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>12</strong></td>";
						echo "<td><strong>Komplikasi, Pencegahan, Prognosis</strong></td>";
						echo "<td align=center><strong>5%</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek12\" style=\"width:50%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek12\" value=\"$data_kasus[aspek_12]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 13
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>13</strong></td>";
						echo "<td><strong>Tumbuh kembang <span class=\"text-danger\">(nutrisi, imunisasi, perkembangan)</span></strong></td>";
						echo "<td align=center><strong>10%</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek13\" style=\"width:50%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek13\" value=\"$data_kasus[aspek_13]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 14
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>14</strong></td>";
						echo "<td><strong>Diskusi lain</strong></td>";
						echo "<td align=center><strong>5%</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek14\" style=\"width:50%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek14\" value=\"$data_kasus[aspek_14]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//Nilai Rata-Rata
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=3 align=right><strong>Rata-Rata Nilai <span class=\"text-danger\">(Jumlah Bobot x Nilai)</span></strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_rata\" style=\"width:50%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"nilai_rata\" value=\"$data_kasus[nilai_rata]\" required disabled/></td>";
						echo "</tr>";
						echo "</table><br>";

						//Umpan Balik
						echo "<table class=\"table table-bordered\" style=\"width:70%\">";
						echo "<tr  class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Umpan Balik:</strong><br>
						<textarea name=\"umpan_balik\" rows=5 style=\"width:100%;margin-top:10px;font-family:Poppins;font-size:1em\" >$data_kasus[umpan_balik]</textarea></td>";
						echo "</tr>";
						echo "<tr  class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Saran:</strong><br>
						<textarea name=\"saran\" rows=5 style=\"width:100%;margin-top:10px;font-family:Poppins;font-size:1em\" >$data_kasus[saran]</textarea></td>";
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
				window.location.href=\"penilaian_ika.php\";
			</script>
			";
						}

						if ($_POST['approve'] == "APPROVE") {
							$dosen_kasus = mysqli_fetch_array(mysqli_query($con, "SELECT `dosen` FROM `ika_nilai_kasus` WHERE `id`='$_POST[id]'"));
							$user_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `password` FROM `admin` WHERE `username`='$dosen_kasus[dosen]'"));
							$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `pin`,`qr` FROM `dosen` WHERE `nip`='$dosen_kasus[dosen]'"));
							$dosenpass_md5 = md5($_POST['dosenpass']);
							if (($_POST['dosenpass'] != "" and $dosenpass_md5 == $user_dosen['password'])
								or ($_POST['dosenpin'] != "" and $_POST['dosenpin'] == $data_dosen['pin'])
								or ($_POST['dosenqr'] != "" and $_POST['dosenqr'] == $data_dosen['qr'])
							) {
								$aspek1 = number_format($_POST['aspek1'], 2);
								$aspek2 = number_format($_POST['aspek2'], 2);
								$aspek3 = number_format($_POST['aspek3'], 2);
								$aspek4 = number_format($_POST['aspek4'], 2);
								$aspek5 = number_format($_POST['aspek5'], 2);
								$aspek6 = number_format($_POST['aspek6'], 2);
								$aspek7 = number_format($_POST['aspek7'], 2);
								$aspek8 = number_format($_POST['aspek8'], 2);
								$aspek9 = number_format($_POST['aspek9'], 2);
								$aspek10 = number_format($_POST['aspek10'], 2);
								$aspek11 = number_format($_POST['aspek11'], 2);
								$aspek12 = number_format($_POST['aspek12'], 2);
								$aspek13 = number_format($_POST['aspek13'], 2);
								$aspek14 = number_format($_POST['aspek14'], 2);

								$kasus = addslashes($_POST['kasus']);
								$umpan_balik = addslashes($_POST['umpan_balik']);
								$saran = addslashes($_POST['saran']);

								$nilai_rata = 0.05 * $_POST['aspek1'] + 0.1 * $_POST['aspek2'] + 0.025 * $_POST['aspek3'] + 0.05 * $_POST['aspek4'] + 0.05 * $_POST['aspek5'] + 0.025 * $_POST['aspek6'] + 0.1 * $_POST['aspek7'] + 0.1 * $_POST['aspek8'] + 0.1 * $_POST['aspek9'] + 0.1 * $_POST['aspek10'] + 0.1 * $_POST['aspek11'] + 0.05 * $_POST['aspek12'] + 0.1 * $_POST['aspek13'] + 0.05 * $_POST['aspek14'];
								$nilai_rata = number_format($nilai_rata, 2);

								$update_kasus = mysqli_query($con, "UPDATE `ika_nilai_kasus` SET
					`tgl_awal`='$_POST[tgl_awal]',`tgl_akhir`='$_POST[tgl_akhir]',
					`kasus`='$kasus',
					`aspek_1`='$aspek1',`aspek_2`='$aspek2',
					`aspek_3`='$aspek3',`aspek_4`='$aspek4',
					`aspek_5`='$aspek5',`aspek_6`='$aspek6',
					`aspek_7`='$aspek7',`aspek_8`='$aspek8',
					`aspek_9`='$aspek9',`aspek_10`='$aspek10',
					`aspek_11`='$aspek11',`aspek_12`='$aspek12',
					`aspek_13`='$aspek13',`aspek_14`='$aspek14',
					`nilai_rata`='$nilai_rata',
					`umpan_balik`='$umpan_balik',`saran`='$saran',
					`tgl_penyajian`='$_POST[tgl_penyajian]',
					`tgl_approval`='$tgl',`status_approval`='1'
					WHERE `id`='$_POST[id]'");

								echo "
					<script>
					alert(\"Approval SUKSES !\");
					window.location.href = \"penilaian_ika.php\";
	        </script>
					";
							} else {
								echo "
				<script>
				alert(\"Approval GAGAL !\");
				window.location.href = \"approve_kasus.php?id=\"+\"$_POST[id]\";
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
	<script type="text/javascript" src="../jquery_ui/jquery-ui.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('.tgl_penyajian').datepicker({
				dateFormat: 'yy-mm-dd'
			});
			$('.tgl_awal').datepicker({
				dateFormat: 'yy-mm-dd'
			});
			$('.tgl_akhir').datepicker({
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
			var aspek12 = document.getElementById('aspek12').value;
			var aspek13 = document.getElementById('aspek13').value;
			var aspek14 = document.getElementById('aspek14').value;

			var result = 0.05 * parseFloat(aspek1) + 0.1 * parseFloat(aspek2) + 0.025 * parseFloat(aspek3) + 0.05 * parseFloat(aspek4) + 0.05 * parseFloat(aspek5) + 0.025 * parseFloat(aspek6) + 0.1 * parseFloat(aspek7) + 0.1 * parseFloat(aspek8) + 0.1 * parseFloat(aspek9) + 0.1 * parseFloat(aspek10) + 0.1 * parseFloat(aspek11) + 0.05 * parseFloat(aspek12) + 0.1 * parseFloat(aspek13) + 0.05 * parseFloat(aspek14);
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