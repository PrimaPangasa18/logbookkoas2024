<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Approval Nilai Sikap Logbook Koas Pendidikan Dokter FK-UNDIP</title>
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
			<main class="content px-3 py-4" style="background-image: url('../images/undip_watermark_color.png'), url('../images/undip_watermark_color.png'); ">
				<div class="container-fluid">
					<div class="mb-3">
						<h3 class="fw-bold fs-4 mb-3">PENILAIAN KEPANITERAAN KOMPREHENSIP</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">APPROVAL PENILAIAN SIKAP/ PERILAKU</h2>
						<br>
						<?php
						$id = $_GET['id'];
						$id_stase = "M121";
						$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
						$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
						$stase_id = "stase_" . $id_stase;
						$data_stase_mhsw = mysqli_query($con, "SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'");
						$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);
						$data_sikap = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kompre_nilai_sikap` WHERE `id`='$id'"));

						echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
						echo "<input type=\"hidden\" name=\"id_stase\" value=\"$id_stase\">";
						echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
						echo "<center>";
						echo "<table  class=\"table table-bordered\" style=\"width:70%\">";

						//Instansi
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td style=\"width:40%\"><strong>Instansi</strong></td>";
						echo "<td style=\"width:60%;font-weight:600;\">$data_sikap[instansi]</td>";
						echo "</tr>";
						//Lokasi Puskesmas/Rumah Sakit
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Nama Rumah Sakit / Puskesmas</strong></td>";
						echo "<td style=\"font-weight:600;\">$data_sikap[lokasi]</td>";
						echo "</tr>";
						//Nama dokter muda/koas
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Nama dokter muda</strong></td>";
						echo "<td style=\"width:60%;font-weight:600; color:darkgreen\">$data_mhsw[nama]</td>";
						echo "</tr>";
						//NIM
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>NIM</strong></td>";
						echo "<td style=\" font-weight:600; color:red\">$data_mhsw[nim]</td>";
						echo "</tr>";
						//Tgl mulai kegiatan
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Tanggal mulai kegiatan</strong></td>";
						$mulai = tanggal_indo($data_sikap['tgl_mulai']);
						echo "<td style=\"font-weight:600;\">$mulai</td>";
						echo "</tr>";
						//Tgl selesai kegiatan
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Tanggal selesai kegiatan</strong></td>";
						$selesai = tanggal_indo($data_sikap['tgl_selesai']);
						echo "<td style=\"font-weight:600;\">$selesai</td>";
						echo "</tr>";
						//Dokter Pembimbing
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Dosen Pembimbing</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_sikap[dosen]'"));
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
						echo "<th style=\"width:55%;text-align:center;\">Aspek Yang Dinilai</th>";
						echo "<th style=\"width:20%;text-align:center;\">Bobot</th>";
						echo "<th style=\"width:20%;text-align:center;\">Nilai (0-100)</th>";
						echo "</thead>";
						//No 1
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>1</strong></td>";
						echo "<td><strong>DISIPLIN</strong><br><span class=\"text-danger\" style=\"font-weight:600;\">(tepat waktu, mengikuti tata tertib)</span></td>";
						echo "<td align=center><strong>15%</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_1\" style=\"width:50%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek_1\" value=\"$data_sikap[aspek_1]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 2
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>2</strong></td>";
						echo "<td><strong>KERJASAMA</strong><br><span class=\"text-danger\" style=\"font-weight:600;\">(dengan teman, pembimbing dan tenaga kesehatan lain)</span></td>";
						echo "<td align=center><strong>15%</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_2\" style=\"width:50%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek_2\" value=\"$data_sikap[aspek_2]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 3
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>3</strong></td>";
						echo "<td><strong>KETELITIAN</strong><br><span class=\"text-danger\" style=\"font-weight:600;\">(perhitungan, analisa, dan evaluasi)</span></td>";
						echo "<td align=center><strong>15%</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_3\" style=\"width:50%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek_3\" value=\"$data_sikap[aspek_3]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 4
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>4</strong></td>";
						echo "<td><strong>INISIATIF / KREATIVITAS</strong><br><span class=\"text-danger\" style=\"font-weight:600;\">(mengambil keputusan, menyelesaikan masalan, dll)</span></td>";
						echo "<td align=center><strong>15%</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_4\" style=\"width:50%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek_4\" value=\"$data_sikap[aspek_4]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 5
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>5</strong></td>";
						echo "<td><strong>SOPAN SANTUN</strong><br><span class=\"text-danger\" style=\"font-weight:600;\">(dengan pasien, pengunjung dan tenaga kesehatan lain)</span></td>";
						echo "<td align=center><strong>15%</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_5\" style=\"width:50%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek_5\" value=\"$data_sikap[aspek_5]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 6
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>6</strong></td>";
						echo "<td><strong>TANGGUNG JAWAB</strong><br><span class=\"text-danger\" style=\"font-weight:600;\">(menyelesaikan tugas kelompok, tugas individu dan tugas lain dari pembimbing)</span></td>";
						echo "<td align=center><strong>15%</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_6\" style=\"width:50%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek_6\" value=\"$data_sikap[aspek_6]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 7
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>7</strong></td>";
						echo "<td><strong>KERAMAHAN</strong><br><span class=\"text-danger\" style=\"font-weight:600;\">(dengan pasien, pengunjung dan tenaga kesehatan lain)</span></td>";
						echo "<td align=center><strong>10%</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_7\" style=\"width:50%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek_7\" value=\"$data_sikap[aspek_7]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//Rata-Rata Nilai
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=3 align=right><strong>Rata-Rata Nilai <span class=\"text-danger\">(Jumlah Bobot x Nilai)</span></strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_rata\" style=\"width:50%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"nilai_rata\" value=\"$data_sikap[nilai_rata]\" required disabled/></td>";
						echo "</tr>";
						echo "</table><br>";

						//Umpan Balik
						echo "<table class=\"table table-bordered\" style=\"width:70%\">";
						echo "<tr  class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Umpan Balik:</strong><br>
						<textarea name=\"umpan_balik\" rows=5 style=\"width:100%;margin-top:10px;font-family:Poppins;font-size:1em\" >$data_sikap[umpan_balik]</textarea></td>";
						echo "</tr>";
						echo "<tr  class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Saran:</strong><br>
						<textarea name=\"saran\" rows=5 style=\"width:100%;margin-top:10px;font-family:Poppins;font-size:1em\" >$data_sikap[saran]</textarea></td>";
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
				window.location.href=\"penilaian_kompre.php\";
			</script>
			";
						}

						if ($_POST['approve'] == "APPROVE") {
							$sikap = mysqli_fetch_array(mysqli_query($con, "SELECT `dosen` FROM `kompre_nilai_sikap` WHERE `id`='$_POST[id]'"));
							$user_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `password` FROM `admin` WHERE `username`='$sikap[dosen]'"));
							$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `pin`,`qr` FROM `dosen` WHERE `nip`='$sikap[dosen]'"));
							$dosenpass_md5 = md5($_POST['dosenpass']);
							if (($_POST['dosenpass'] != "" and $dosenpass_md5 == $user_dosen['password'])
								or ($_POST['dosenpin'] != "" and $_POST['dosenpin'] == $data_dosen['pin'])
								or ($_POST['dosenqr'] != "" and $_POST['dosenqr'] == $data_dosen['qr'])
							) {
								$lokasi = addslashes($_POST['lokasi']);

								$aspek1 = number_format($_POST['aspek_1'], 2);
								$aspek2 = number_format($_POST['aspek_2'], 2);
								$aspek3 = number_format($_POST['aspek_3'], 2);
								$aspek4 = number_format($_POST['aspek_4'], 2);
								$aspek5 = number_format($_POST['aspek_5'], 2);
								$aspek6 = number_format($_POST['aspek_6'], 2);
								$aspek7 = number_format($_POST['aspek_7'], 2);

								$nilai_rata = 0.15 * $_POST['aspek_1'] + 0.15 * $_POST['aspek_2'] + 0.15 * $_POST['aspek_3'] + 0.15 * $_POST['aspek_4'] + 0.15 * $_POST['aspek_5'] + 0.15 * $_POST['aspek_6'] + 0.1 * $_POST['aspek_7'];
								$nilai_rata = number_format($nilai_rata, 2);

								$umpan_balik = addslashes($_POST['umpan_balik']);
								$saran = addslashes($_POST['saran']);

								$update_sikap = mysqli_query($con, "UPDATE `kompre_nilai_sikap` SET
					`aspek_1`='$aspek1',`aspek_2`='$aspek2',
					`aspek_3`='$aspek3',`aspek_4`='$aspek4',
					`aspek_5`='$aspek5',`aspek_6`='$aspek6',
					`aspek_7`='$aspek7',`nilai_rata`='$nilai_rata',
					`umpan_balik`='$umpan_balik',`saran`='$saran',
					`tgl_approval`='$tgl',`status_approval`='1'
					WHERE `id`='$_POST[id]'");
								echo "
					<script>
					alert(\"Approval SUKSES !\");
					window.location.href = \"penilaian_kompre.php\";
		       </script>
					";
							} else {
								echo "
				<script>
				alert(\"Approval GAGAL !\");
				window.location.href = \"approve_sikap.php?id=\"+\"$_POST[id]\";
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

			var result = 0.15 * parseFloat(aspek1) + 0.15 * parseFloat(aspek2) + 0.15 * parseFloat(aspek3) + 0.15 * parseFloat(aspek4) + 0.15 * parseFloat(aspek5) + 0.15 * parseFloat(aspek6) + 0.1 * parseFloat(aspek7);
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