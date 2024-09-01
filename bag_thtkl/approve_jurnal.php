<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Approval Jurnal Logbook Koas Pendidikan Dokter FK-UNDIP</title>
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
						<h3 class="fw-bold fs-4 mb-3">PENILAIAN KEPANITERAAN (STASE) ILMU KESEHATAN THT-KL</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">APPROVAL NILAI JOURNAL READING<br>KEPANITERAAN (STASE) ILMU KESEHATAN THT-KL</h2>
						<br>
						<<?php
							$id = $_GET['id'];
							$data_jurnal = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `thtkl_nilai_jurnal` WHERE `id`='$id'"));
							$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$data_jurnal[nim]'"));

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
							//Journal Reading Ke-
							echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
							echo "<td><strong>Journal Reading Ke-</strong></td>";
							echo "<td style=\" font-weight:600;\">$data_jurnal[jurnal_ke]</td>";
							echo "</tr>";
							//Dosen Pembimbing/Penguji
							echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
							echo "<td><strong>Dosen Pembimbing</strong></td>";
							echo "<td style=\"font-weight:600;\">";
							$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_jurnal[dosen]'"));
							echo "$data_dosen[nama], <span style=\"color:red\">$data_dosen[gelar]</span> (<span style=\"color:blue\">$data_dosen[nip]</span>)";
							echo "</td>";
							echo "</tr>";
							//Nama Jurnal
							echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
							echo "<td><strong>Nama Jurnal</strong></td>";
							echo "<td><textarea name=\"nama_jurnal\" style=\" width: 100%;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" placeholder=\"<< Nama Jurnal >>\" required>$data_jurnal[nama_jurnal]</textarea></td>";
							echo "</tr>";
							//Judul Artikel
							echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
							echo "<td><strong>Judul Artikel</strong></td>";
							echo "<td><textarea name=\"judul_paper\" style=\" width: 100%;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" placeholder=\"<< Judul Artikel >>\" required>$data_jurnal[judul_paper]</textarea></td>";
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
							//No 1
							echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
							echo "<td align=center class=\"td_mid\"><strong>1</strong></td>";
							echo "<td><strong>Menerjemahkan Journal Reading</strong></td>";
							echo "<td align=center><strong>30%<strong></td>";
							echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek1\" style=\"width:55%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_jurnal[aspek_1]\" id=\"aspek1\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
							echo "</tr>";
							//No 2
							echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
							echo "<td align=center class=\"td_mid\"><strong>2</strong></td>";
							echo "<td><strong>Relevansi Pembicaraan</strong></td>";
							echo "<td align=center><strong>50%</strong></td>";
							echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek2\" style=\"width:55%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_jurnal[aspek_2]\" id=\"aspek2\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
							echo "</tr>";
							//No 3
							echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
							echo "<td align=center class=\"td_mid\"><strong>3</strong></td>";
							echo "<td><strong>Keterampilan Berkomunikasi</strong></td>";
							echo "<td align=center><strong>20%</strong></td>";
							echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek3\" style=\"width:55%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_jurnal[aspek_3]\" id=\"aspek3\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
							echo "</tr>";
							//Nilai Rata-Rata
							echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
							echo "<td colspan=3 align=right><strong>Rata-Rata Nilai <span class=\"text-danger\">(Jumlah Bobot x Nilai)</span></strong></td>";
							echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_rata\" style=\"width:55%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" value=\"$data_jurnal[nilai_rata]\" id=\"nilai_rata\" required disabled/></td>";
							echo "</tr>";
							echo "</table><br>";

							//Umpan Balik
							echo "<table class=\"table table-bordered\" style=\"width:70%\">";
							echo "<tr  class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
							echo "<td><strong>Umpan Balik:</strong><br>
						<textarea name=\"umpan_balik\" rows=5 style=\"width:100%;margin-top:10px;font-family:Poppins;font-size:1em\" >$data_jurnal[umpan_balik]</textarea></td>";
							echo "</tr>";
							echo "<tr  class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
							echo "<td><strong>Saran:</strong><br>
						<textarea name=\"saran\" rows=5 style=\"width:100%;margin-top:10px;font-family:Poppins;font-size:1em\" >$data_jurnal[saran]</textarea></td>";
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
				window.location.href=\"penilaian_thtkl.php\";
			</script>
			";
					}

					if ($_POST['approve'] == "APPROVE") {
						$dosen_jurnal = mysqli_fetch_array(mysqli_query($con, "SELECT `dosen` FROM `thtkl_nilai_jurnal` WHERE `id`='$_POST[id]'"));
						$user_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `password` FROM `admin` WHERE `username`='$dosen_jurnal[dosen]'"));
						$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `pin`,`qr` FROM `dosen` WHERE `nip`='$dosen_jurnal[dosen]'"));
						$dosenpass_md5 = md5($_POST['dosenpass']);
						if (($_POST['dosenpass'] != "" and $dosenpass_md5 == $user_dosen['password'])
							or ($_POST['dosenpin'] != "" and $_POST['dosenpin'] == $data_dosen['pin'])
							or ($_POST['dosenqr'] != "" and $_POST['dosenqr'] == $data_dosen['qr'])
						) {
							$aspek1 = number_format($_POST['aspek1'], 2);
							$aspek2 = number_format($_POST['aspek2'], 2);
							$aspek3 = number_format($_POST['aspek3'], 2);
							$nama_jurnal = addslashes($_POST['nama_jurnal']);
							$judul_paper = addslashes($_POST['judul_paper']);

							$nilai_rata = 0.3 * $_POST['aspek1'] + 0.5 * $_POST['aspek2'] + 0.2 * $_POST['aspek3'];
							$nilai_rata = number_format($nilai_rata, 2);

							$umpan_balik = addslashes($_POST['umpan_balik']);
							$saran = addslashes($_POST['saran']);

							$update_jurnal = mysqli_query($con, "UPDATE `thtkl_nilai_jurnal` SET
					`nama_jurnal`='$nama_jurnal',
					`judul_paper`='$judul_paper',
					`aspek_1`='$aspek1',
					`aspek_2`='$aspek2',
					`aspek_3`='$aspek3',
					`nilai_rata`='$nilai_rata',
					`umpan_balik`='$umpan_balik',`saran`='$saran',
					`tgl_approval`='$tgl',`status_approval`='1'
					WHERE `id`='$_POST[id]'");

							echo "
					<script>
					alert(\"Approval SUKSES !\");
					window.location.href = \"penilaian_thtkl.php\";
	        </script>
					";
						} else {
							echo "
				<script>
				alert(\"Approval GAGAL !\");
				window.location.href = \"approve_jurnal.php?id=\"+\"$_POST[id]\";
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
			var aspek1 = document.getElementById('aspek1').value;
			var aspek2 = document.getElementById('aspek2').value;
			var aspek3 = document.getElementById('aspek3').value;
			var result = 0.3 * parseFloat(aspek1) + 0.5 * parseFloat(aspek2) + 0.2 * parseFloat(aspek3);
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