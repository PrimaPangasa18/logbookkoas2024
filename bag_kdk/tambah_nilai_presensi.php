<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Tambah Nilai Presensi Logbook Koas Pendidikan Dokter FK-UNDIP</title>
	<link rel="shortcut icon" type="x-icon" href="../images/undipsolid.png">
	<link rel="stylesheet" href="../style/style1.css" />
	<link rel="stylesheet" href="../style/buttontopup.css">
	<link rel="stylesheet" href="../select2/dist/css/select2.css" />
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
			<main class="content px-3 py-4" style="background-image: url('../images/undip_watermark_color.png'), url('../images/undip_watermark_color.png'); ">
				<div class="container-fluid">
					<div class="mb-3">
						<h3 class="fw-bold fs-4 mb-3">PENILAIAN KEPANITERAAN KEDOKTERAN KELUARGA</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">FORMULIR PENILAIAN PRESENSI / KEHADIRAN</h2>
						<br>
						<?php
						$id_stase = "M121";
						$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
						$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
						$stase_id = "stase_" . $id_stase;
						$data_stase_mhsw = mysqli_query($con, "SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'");
						$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);

						echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
						echo "<input type=\"hidden\" name=\"id_stase\" value=\"$id_stase\">";
						echo "<input type=\"hidden\" name=\"nim\" value=\"$data_mhsw[nim]\">";
						echo "<center>";
						echo "<table  class=\"table table-bordered\" style=\"width:70%\">";

						//Instansi
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td style=\"width:40%\"><strong>Instansi</strong></td>";
						echo "<td style=\"width:60%\" ><select class=\"form-select\" style=\"font-size:1em;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" name=\"instansi\" id=\"instansi\" required>";
						echo "<option value=\"\"><< Pilihan Instansi >></option>";
						echo "<option value=\"Puskesmas\">Puskesmas</option>";
						echo "<option value=\"Klinik Pratama\">Klinik Pratama</option>";
						echo "</select></td>";
						echo "</tr>";
						//Lokasi Puskesmas/Klinik
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Nama Puskesmas / Klinik</strong></td>";
						echo "<td><textarea name=\"lokasi\" style=\" width: 100%;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" placeholder=\"<< Lokasi Kegiatan Kepaniteraan KDK  >>\" required></textarea></td>";
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
						echo "<td><strong>Tanggal mulai kegiatan <span class=\"text-danger\">(yyyy-mm-dd)</span></strong></td>";
						echo "<td class=\"td_mid\">
						<input type=\"text\" class=\"form-select tgl_mulai\" name=\"tgl_mulai\" style=\"font-size:1em;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" placeholder=\"yyyy-mm-dd\" /></td>";
						echo "</tr>";
						//Tgl selesai kegiatan
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Tanggal selesai kegiatan <span class=\"text-danger\">(yyyy-mm-dd)</span></strong></td>";
						echo "<td class=\"td_mid\">
						<input type=\"text\" class=\"form-select tgl_selesai\" name=\"tgl_selesai\" style=\"font-size:1em;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" placeholder=\"yyyy-mm-dd\" /></td>";
						echo "</tr>";
						//Dokter Pembimbing
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Dosen Pembimbing</strong></td>";
						echo "<td>";
						echo "<select class=\"form-select\" style=\"font-size:1em;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" name=\"dosen\" id=\"dosen\" required>";
						$dosen = mysqli_query($con, "SELECT `username`,`nama` FROM `admin` WHERE `level`='4' OR (`level`='6' AND `stase`='$id_stase') ORDER BY `nama`");
						echo "<option value=\"\"><< Dokter Pembimbing >></option>";
						while ($data = mysqli_fetch_array($dosen)) {
							$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data[username]'"));
							echo "<option value=\"$data[username]\">$data_dosen[nama], $data_dosen[gelar] ($data_dosen[nip])</option>";
						}
						echo "</select>";
						echo "</td>";
						echo "</tr>";
						echo "</table><br><br>";

						//Hari kegiatan
						//Jumlah Hari Kerja
						echo "<table border=2 style=\"width:70%;  background:rgba(255, 243, 205, 1);\" >";
						echo "<tr><td colspan=3 style=\"text-align:center; font-size:1.1em;\"><strong >Jumlah Hari Kegiatan:</strong></td></tr>";
						echo "</table>";
						echo "<table class=\"table table-bordered\" style=\"width:70%\">";
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td style=\"width:4%\" align=center><strong>A.</strong></td>";
						echo "<td style=\"width:36%\"><strong>Jumlah hari kerja</strong><br>";
						echo "<font style=\"font-size:0.8em; font-weight:600;\"><span class=\"text-danger\">(Hari kerja)</span></td>";
						echo "<td style=\"width:30%; font-weight:600;\"> <input type=\"number\" step=\"1\" min=\"0\" max=\"100\" name=\"hari_kerja\" style=\"width:20%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"hari_kerja\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/>&nbsp;&nbsp;hari</td>";
						echo "</tr>";
						//Jumlah hari tidak masuk dengan ijin
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>B.</strong></td>";
						echo "<td><strong>Jumlah hari tidak masuk DENGAN IJIN</strong><br>";
						echo "<font style=\"font-size:0.8em; font-weight:600;\"><span class=\"text-danger\">(Hari ijin)</span></td>";
						echo "<td style=\"font-weight:600;\"> <input type=\"number\" step=\"1\" min=\"0\" max=\"100\" name=\"hari_ijin\" style=\"width:20%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"hari_ijin\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/>&nbsp;&nbsp;Hari</td>";
						echo "</tr>";
						//Jumlah hari tidak masuk tanpa ijin
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>C.</strong></td>";
						echo "<td><strong>Jumlah hari tidak masuk TANPA IJIN</strong><br>";
						echo "<font style=\"font-size:0.8em; font-weight:600;\"><span class=\"text-danger\">(Hari alpa)</span></td>";
						echo "<td style=\"font-weight:600;\"> <input type=\"number\" step=\"1\" min=\"0\" max=\"100\" name=\"hari_alpa\" style=\"width:20%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"hari_alpa\" value=\"0\" onkeyup=\"sum();\" onchange=\"sum();\" required/>&nbsp;&nbsp;Hari</td>";
						echo "</tr>";
						//Jumlah Hari Masuk
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center><strong>D.</strong></td>";
						echo "<td><strong>Jumlah hari masuk kegiatan</strong><br>";
						echo "<font style=\"font-size:0.8em; font-weight:600;\"><span class=\"text-danger\">(Hari masuk = hari kerja - (hari ijin + hari alpa))</span></td>";
						echo "<td style=\"font-weight:600;\"> <input type=\"number\" step=\"1\" min=\"0\" max=\"100\" name=\"hari_masuk\" style=\"width:20%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"hari_masuk\" value=\"0\" required/>&nbsp;&nbsp;Hari</td>";
						echo "</tr>";
						echo "</table><br><br>";

						//Form nilai
						echo "<table border=2 style=\"width:70%;  background:rgba(255, 243, 205, 1);\" >";
						echo "<tr><td style=\"text-align:center; font-size:1.1em;\"><strong >Form Penilaian:</strong></td></tr>";
						echo "</table>";
						echo "<table class=\"table table-bordered\" style=\"width:70%\">";
						echo "<thead class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<th style=\"width:5%;text-align:center;\">No</th>";
						echo "<th style=\"width:75%;text-align:center;\">Unsur Penilaian</th>";
						echo "<th style=\"width:20%;text-align:center;\">Skor</th>";
						echo "</thead>";
						//No 1
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>1</strong></td>";
						echo "<td><strong>Bila setiap hari masuk dan kegiatan memenuhi (skor maksimal 100)</strong><br>
						<font style=\"font-size:0.8em; font-weight:600;\"><span class=\"text-danger\">Rumus skor = 100 x (Jml hari masuk)/(Jml hari kerja)</span></font></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_masuk\" style=\"width:50%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"nilai_masuk\" value=\"0\" required/></td>";
						echo "</tr>";
						//No 2
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>2</strong></td>";
						echo "<td><strong>Bila tidak masuk TANPA IJIN, nilai dipotong 5 / hari</strong><br>
						<font style=\"font-size:0.8em; font-weight:600;\"><span class=\"text-danger\">Rumus skor pengurangan = 5 x Jml hari tidak masuk tanpa ijin</span></font></td>";
						echo "<td align=center><input type=\"number\" step=\"5\" min=\"-100\" max=\"0\" name=\"nilai_absen\" style=\"width:50%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"nilai_absen\" value=\"0\" required/></td>";
						echo "</tr>";
						//No 3
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>3</strong></td>";
						echo "<td><strong>Bila tidak masuk DENGAN IJIN, nilai dipotong 2 / hari</strong><br>
						<font style=\"font-size:0.8em; font-weight:600;\"><span class=\"text-danger\">Rumus skor pengurangan = 2 x Jml hari tidak masuk dengan ijin</span></font></td>";
						echo "<td align=center><input type=\"number\" step=\"2\" min=\"-100\" max=\"0\" name=\"nilai_ijin\" style=\"width:50%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"nilai_ijin\" value=\"0\" required/></td>";
						echo "</tr>";

						//No 4
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=right colspan=2><strong>Total Nilai <span class=\"text-danger\">(Skor 1 + Skor 2 + Skor 3)</span></strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_total\" style=\"width:50%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"nilai_total\" value=\"0\" required/></td>";
						echo "</tr>";
						echo "</table><br>";

						echo "<br>
						<button type=\"submit\" class=\"btn btn-danger\" style=\"margin-right:40px\" name=\"cancel\" value=\"CANCEL\" formnovalidate>
            					<i class=\"fas fa-times me-2\"></i> CANCEL
        						</button>";
						echo "<button type=\"submit\" class=\"btn btn-success\" name=\"usulkan\" value=\"USULKAN\">
            			<i class=\"fas fa-add me-2\"></i> USULKAN
        			</button>
      				</form><br><br>";
						echo "</center>";

						if ($_POST['cancel'] == "CANCEL") {
							echo "
				<script>
					window.location.href=\"penilaian_kdk.php\";
				</script>
				";
						}

						if ($_POST['usulkan'] == "USULKAN") {
							$lokasi = addslashes($_POST['lokasi']);
							$nilai_total = number_format($_POST['nilai_total'], 2);
							$nilai_masuk = number_format($_POST['nilai_masuk'], 2);
							$nilai_absen = number_format($_POST['nilai_absen'], 2);
							$nilai_ijin = number_format($_POST['nilai_ijin'], 2);

							$insert_presensi = mysqli_query($con, "INSERT INTO `kdk_nilai_presensi`
				( `nim`, `dosen`, `lokasi`,`instansi`, `tgl_mulai`,`tgl_selesai`,
					`hari_kerja`, `hari_masuk`, `hari_ijin`, `hari_alpa`,
					`nilai_masuk`, `nilai_absen`, `nilai_ijin`, `nilai_total`,
					`tgl_isi`, `tgl_approval`, `status_approval`)
				VALUES
				( '$_POST[nim]', '$_POST[dosen]', '$lokasi', '$_POST[instansi]', '$_POST[tgl_mulai]','$_POST[tgl_selesai]',
					'$_POST[hari_kerja]','$_POST[hari_masuk]','$_POST[hari_ijin]','$_POST[hari_alpa]',
					'$nilai_masuk', '$nilai_absen', '$nilai_ijin', '$nilai_total',
				  '$tgl', '2000-01-01', '0')");
							echo "
				<script>
					window.location.href=\"penilaian_kdk.php\";
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
	<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
	<script src="../select2/dist/js/select2.js"></script>
	<script type="text/javascript" src="../jquery_ui/jquery-ui.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('.tgl_mulai').datepicker({
				dateFormat: 'yy-mm-dd'
			});
		});
		$(document).ready(function() {
			$('.tgl_selesai').datepicker({
				dateFormat: 'yy-mm-dd'
			});
		});
	</script>
	<script type="text/javascript" src="../freezeheader/js/jquery.freezeheader.js"></script>
	<script>
		$(document).ready(function() {
			$("#freeze").freezeHeader();
			$("#freeze1").freezeHeader();
		});
	</script>
	<script>
		$(document).ready(function() {
			$("#dosen").select2({
				placeholder: "<< Dokter Pembimbing >>",
				allowClear: true
			});
		});
	</script>

	<script>
		function sum() {
			var hari_kerja = document.getElementById('hari_kerja').value;
			var hari_ijin = document.getElementById('hari_ijin').value;
			var hari_alpa = document.getElementById('hari_alpa').value;

			var result = parseInt(hari_kerja) - parseInt(hari_ijin) - parseInt(hari_alpa);
			if (!isNaN(result)) {
				document.getElementById('hari_masuk').value = result;
			}
			var result1 = 100 * (parseInt(result) / parseInt(hari_kerja));
			if (!isNaN(result1)) {
				document.getElementById('nilai_masuk').value = number_format(result1, 2);
			}
			var result2 = -5 * parseInt(hari_alpa);
			if (!isNaN(result2)) {
				document.getElementById('nilai_absen').value = number_format(result2, 2);
			}
			var result3 = -2 * parseInt(hari_ijin);
			if (!isNaN(result3)) {
				document.getElementById('nilai_ijin').value = number_format(result3, 2);
			}
			var result4 = parseFloat(result1) + parseInt(result2) + parseInt(result3);
			if (!isNaN(result4)) {
				document.getElementById('nilai_total').value = number_format(result4, 2);
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