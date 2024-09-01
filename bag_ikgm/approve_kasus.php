<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Approval Kasus Logbook Koas Pendidikan Dokter FK-UNDIP</title>
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
						<h3 class="fw-bold fs-4 mb-3">PENILAIAN KEPANITERAAN (STASE) IKGM</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">APPROVAL NILAI LAPORAN KASUS<br>KEPANITERAAN (STASE) IKGM</h2>
						<br>
						<?php
						$id = $_GET['id'];
						$data_kasus = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `ikgm_nilai_kasus` WHERE `id`='$id'"));
						$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_kasus[nim]'"));

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
						///Tanggal Presentasi
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td class=\"td_mid\"><strong>Tanggal Ujian/Presentasi <span class=\"text-danger\">(yyyy-mm-dd)</span></strong></td>";
						echo "<td class=\"td_mid\"><input type=\"text\" class=\"form-select tanggal_ujian\" name=\"tanggal_ujian\" style=\"font-size:1em;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" value=\"$data_kasus[tgl_ujian]\" /></td>";
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
						echo "<td><textarea name=\"nama_kasus\" style=\" width: 100%;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" required>$data_kasus[nama_kasus]</textarea></td>";
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
							echo "<input type=\"radio\" name=\"aspek_1\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;0 - Tidak Hadir<br>";
						else echo "<input type=\"radio\" name=\"aspek_1\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;0 - Tidak Hadir<br>";
						if ($data_kasus['aspek_1'] == "1")
							echo "<input type=\"radio\" name=\"aspek_1\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;1 - Hadir, terlambat<br>";
						else echo "<input type=\"radio\" name=\"aspek_1\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;1 - Hadir, terlambat<br>";
						if ($data_kasus['aspek_1'] == "2")
							echo "<input type=\"radio\" name=\"aspek_1\" value=\"2\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;2 - Hadir tepat waktu<br>";
						else echo "<input type=\"radio\" name=\"aspek_1\" value=\"2\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;2 - Hadir tepat waktu<br>";
						echo "</td>";
						echo "</tr>";
						//No 2
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>2</strong></td>";
						echo "<td><strong>Aktifitas saat diskusi<br>(<span class=\"text-danger\">Dinilai dari frekuensi mengajukan masukan / komentar / pendapat / jawaban</span>)</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						if ($data_kasus['aspek_2'] == "1")
							echo "<input type=\"radio\" name=\"aspek_2\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;1 - Pasif<br>";
						else echo "<input type=\"radio\" name=\"aspek_2\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;1 - Pasif<br>";
						if ($data_kasus['aspek_2'] == "2")
							echo "<input type=\"radio\" name=\"aspek_2\" value=\"2\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;2 - Kurang aktif<br>";
						else echo "<input type=\"radio\" name=\"aspek_2\" value=\"2\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;2 - Kurang aktif<br>";
						if ($data_kasus['aspek_2'] == "3")
							echo "<input type=\"radio\" name=\"aspek_2\" value=\"3\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;3 - Cukup aktif<br>";
						else echo "<input type=\"radio\" name=\"aspek_2\" value=\"3\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;3 - Cukup aktif<br>";
						if ($data_kasus['aspek_2'] == "4")
							echo "<input type=\"radio\" name=\"aspek_2\" value=\"4\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;4 - Lebih aktif<br>";
						else echo "<input type=\"radio\" name=\"aspek_2\" value=\"4\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;4 - Lebih aktif<br>";
						if ($data_kasus['aspek_2'] == "5")
							echo "<input type=\"radio\" name=\"aspek_2\" value=\"5\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;5 - Sangat aktif";
						else echo "<input type=\"radio\" name=\"aspek_2\" value=\"5\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;5 - Sangat aktif";
						echo "</td>";
						echo "</tr>";
						//No 3
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>3</strong></td>";
						echo "<td><strong>Ketrampilan presentasi / berkomunikasi<br>(<span class=\"text-danger\">Dinilai dari kemampuan berinteraksi dengan peserta lain dan menjaga etika</span>)<strong></td>";
						echo "<td style=\"font-weight:600;\">";
						if ($data_kasus['aspek_3'] == "1")
							echo "<input type=\"radio\" name=\"aspek_3\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;1 - Sangat kurang<br>";
						else echo "<input type=\"radio\" name=\"aspek_3\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;1 - Sangat kurang<br>";
						if ($data_kasus['aspek_3'] == "2")
							echo "<input type=\"radio\" name=\"aspek_3\" value=\"2\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;2 - Kurang<br>";
						else echo "<input type=\"radio\" name=\"aspek_3\" value=\"2\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;2 - Kurang<br>";
						if ($data_kasus['aspek_3'] == "3")
							echo "<input type=\"radio\" name=\"aspek_3\" value=\"3\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;3 - Cukup<br>";
						else echo "<input type=\"radio\" name=\"aspek_3\" value=\"3\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;3 - Cukup<br>";
						if ($data_kasus['aspek_3'] == "4")
							echo "<input type=\"radio\" name=\"aspek_3\" value=\"4\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;4 - Baik<br>";
						else echo "<input type=\"radio\" name=\"aspek_3\" value=\"4\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;4 - Baik<br>";
						if ($data_kasus['aspek_3'] == "5")
							echo "<input type=\"radio\" name=\"aspek_3\" value=\"5\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;5 - Sangat baik";
						else echo "<input type=\"radio\" name=\"aspek_3\" value=\"5\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;5 - Sangat baik";
						echo "</td>";
						echo "</tr>";
						//No 4
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>4</strong></td>";
						echo "<td><strong>Kelengkapan laporan dan materi presentasi<br>(<span class=\"text-danger\">Dinilai dari materi presentasi yang disiapkan dan disajikan, serta kelengkapan isi dan kerapian makalah / laporan yang dikumpulkan</span>)</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						if ($data_kasus['aspek_4'] == "1")
							echo "<input type=\"radio\" name=\"aspek_4\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;1 - Sangat kurang<br>";
						else echo "<input type=\"radio\" name=\"aspek_4\" value=\"1\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;1 - Sangat kurang<br>";
						if ($data_kasus['aspek_4'] == "2")
							echo "<input type=\"radio\" name=\"aspek_4\" value=\"2\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;2 - Kurang<br>";
						else echo "<input type=\"radio\" name=\"aspek_4\" value=\"2\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;2 - Kurang<br>";
						if ($data_kasus['aspek_4'] == "3")
							echo "<input type=\"radio\" name=\"aspek_4\" value=\"3\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;3 - Cukup<br>";
						else echo "<input type=\"radio\" name=\"aspek_4\" value=\"3\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;3 - Cukup<br>";
						if ($data_kasus['aspek_4'] == "4")
							echo "<input type=\"radio\" name=\"aspek_4\" value=\"4\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;4 - Baik<br>";
						else echo "<input type=\"radio\" name=\"aspek_4\" value=\"4\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;4 - Baik<br>";
						if ($data_kasus['aspek_4'] == "5")
							echo "<input type=\"radio\" name=\"aspek_4\" value=\"5\" onkeyup=\"sum();\" onchange=\"sum();\" checked/>&nbsp;&nbsp;5 - Sangat baik";
						else echo "<input type=\"radio\" name=\"aspek_4\" value=\"5\" onkeyup=\"sum();\" onchange=\"sum();\" />&nbsp;&nbsp;5 - Sangat baik";
						echo "</td>";
						echo "</tr>";
						//Total Nilai
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=2 align=right><strong>Total Nilai <span class=\"text-danger\">(10 x Jumlah Poin / 1.7)</span></strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_total\" style=\"width:30%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"nilai_total\" value=\"$data_kasus[nilai_total]\" required disabled/></td>";
						echo "</tr>";
						echo "</table><br>";

						//Catatan
						echo "<table class=\"table table-bordered\" style=\"width:70%\">";
						echo "<tr  class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center ><strong>Catatan Dosen Pembimbing Terhadap Kegiatan:</strong><br></td>";
						echo "</tr>";
						echo "<tr  class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Catatan:</strong><br>
						<textarea name=\"catatan\" rows=5 style=\"width:100%;margin-top:10px;font-family:Poppins;font-size:1em\" >$data_kasus[catatan]</textarea></td>";
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
				window.location.href=\"penilaian_ikgm.php\";
			</script>
			";
						}

						if ($_POST['approve'] == "APPROVE") {
							$dosen_kasus = mysqli_fetch_array(mysqli_query($con, "SELECT `dosen` FROM `ikgm_nilai_kasus` WHERE `id`='$_POST[id]'"));
							$user_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `password` FROM `admin` WHERE `username`='$dosen_kasus[dosen]'"));
							$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `pin`,`qr` FROM `dosen` WHERE `nip`='$dosen_kasus[dosen]'"));
							$dosenpass_md5 = md5($_POST['dosenpass']);
							if (($_POST['dosenpass'] != "" and $dosenpass_md5 == $user_dosen['password'])
								or ($_POST['dosenpin'] != "" and $_POST['dosenpin'] == $data_dosen['pin'])
								or ($_POST['dosenqr'] != "" and $_POST['dosenqr'] == $data_dosen['qr'])
							) {
								$nama_kasus = addslashes($_POST['nama_kasus']);
								$catatan = addslashes($_POST['catatan']);

								$nilai_total = ($_POST['aspek_1'] + $_POST['aspek_2'] + $_POST['aspek_3'] + $_POST['aspek_4']) / 1.7;
								$nilai_total = number_format(10 * $nilai_total, 2);

								$update_kasus = mysqli_query($con, "UPDATE `ikgm_nilai_kasus` SET
					`tgl_ujian`='$_POST[tanggal_ujian]',`nama_kasus`='$nama_kasus',
					`aspek_1`='$_POST[aspek_1]',`aspek_2`='$_POST[aspek_2]',
					`aspek_3`='$_POST[aspek_3]',`aspek_4`='$_POST[aspek_4]',
					`catatan`='$catatan',`nilai_total`='$nilai_total',
					`tgl_approval`='$tgl',`status_approval`='1'
					WHERE `id`='$_POST[id]'");

								echo "
					<script>
					alert(\"Approval SUKSES !\");
					window.location.href = \"penilaian_ikgm.php\";
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
			var aspek1 = $("input[name=aspek_1]:checked").val();
			var aspek2 = $("input[name=aspek_2]:checked").val();
			var aspek3 = $("input[name=aspek_3]:checked").val();
			var aspek4 = $("input[name=aspek_4]:checked").val();

			var total = parseInt(aspek1) + parseInt(aspek2) + parseInt(aspek3) + parseInt(aspek4);
			var result = 10 * parseInt(total) / 1.7;

			if (!isNaN(result)) {
				document.getElementById('nilai_total').value = number_format(result, 2);
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