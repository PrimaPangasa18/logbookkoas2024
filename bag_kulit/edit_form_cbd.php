<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Edit Ujian Kasus Logbook Koas Pendidikan Dokter FK-UNDIP</title>
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
						<h3 class="fw-bold fs-4 mb-3">PENILAIAN KEPANITERAAN (STASE) ILMU KESEHATAN KULIT DAN KELAMIN</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">FORMULIR NILAI UJIAN KASUS</h2>
						<br>
						<?php
						$id = $_GET['id'];
						$id_stase = "M114";
						$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
						$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
						$stase_id = "stase_" . $id_stase;
						$data_stase_mhsw = mysqli_query($con, "SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'");
						$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);
						$data_cbd = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kulit_nilai_cbd` WHERE `id`='$id'"));

						echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
						echo "<input type=\"hidden\" name=\"id_stase\" value=\"$id_stase\">";
						echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
						echo "<center>";
						echo "<table  class=\"table table-bordered\" style=\"width:70%\">";

						//Judul Kasus
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td style=\"width:40%\"><strong>Judul Kasus</strong></td>";
						echo "<td style=\"width:60%\"><textarea name=\"kasus\" style=\" width: 100%;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" value=\"$data_cbd[kasus]\" required>$data_cbd[kasus]</textarea></td>";
						echo "</tr>";
						//Nama dokter muda/koas
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Nama dokter muda</strong></td>";
						echo "<td style=\"width:60%;font-weight:600; color:darkgreen\">$data_mhsw[nama]</td>";
						echo "</tr>";
						//NIM
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>NIM</strong></td>";
						echo "<td style=\" font-weight:600; color:red\">$data_mhsw[nim]</td>";
						echo "</tr>";
						//Pengajuan
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=2><strong>Diajukan pada:</strong></td>";
						echo "</tr>";
						//Tgl isi kegiatan
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>&nbsp;&nbsp;&nbsp;&nbsp;Tanggal isi kegiatan <span class=\"text-danger\">(yyyy-mm-dd)</span></strong></td>";
						echo "<td><input type=\"text\" name=\"tgl_isi\" class=\"form-select tgl_isi\" style=\"font-size:1em;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" value=\"$data_cbd[tgl_isi]\" required/></td>";
						echo "</tr>";

						//Jam kegiatan
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>&nbsp;&nbsp;&nbsp;&nbsp;Jam isi kegiatan</strong></td>";
						echo "<td style=\"font-weight:600;\">";
						$jam_isi = substr($data_cbd['jam_isi'], 0, 2);
						$menit_isi = substr($data_cbd['jam_isi'], 3, 2);
						$lokal_isi = substr($data_cbd['jam_isi'], 6, strlen($data_cbd['jam_isi']) - 6);
						echo "<select class=\"form-select\" name=\"jam_isi\" id=\"jam_isi\" required>";
						$jam = mysqli_query($con, "SELECT `jam` FROM `jam` ORDER BY `id`");
						echo "<option value=\"$jam_isi\">$jam_isi</option>";
						while ($dat_jam = mysqli_fetch_array($jam))
							echo "<option value=\"$dat_jam[jam]\">$dat_jam[jam]</option>";
						echo "</select>&nbsp;&nbsp;:&nbsp;&nbsp;";
						echo "<select class=\"form-select\" name=\"menit_isi\" id=\"menit_isi\" required>";
						$menit = mysqli_query($con, "SELECT `menit` FROM `menit` ORDER BY `id`");
						echo "<option value=\"$menit_isi\">$menit_isi</option>";
						while ($dat_menit = mysqli_fetch_array($menit))
							echo "<option value=\"$dat_menit[menit]\">$dat_menit[menit]</option>";
						echo "</select>&nbsp;&nbsp;-&nbsp;&nbsp;";
						echo "<select class=\"form-select\" name=\"lokal_isi\" id=\"lokal_isi\" required>";
						echo "<option value=\"$lokal_isi\">$lokal_isi</option>";
						echo "<option value=\"WIB\">WIB</option>";
						echo "<option value=\"WITA\">WITA</option>";
						echo "<option value=\"WIT\">WIT</option>";
						echo "</select>";
						echo "</td>";
						echo "</tr>";
						//Dosen Pembimbing Lapangan
						echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Dosen Pembimbing Lapangan</strong></td>";
						echo "<td>";
						echo "<select class=\"form-select\" style=\"font-size:1em;font-family:Poppins;border:0.5px solid black;border-radius:5px;\" name=\"dosen\" id=\"dosen\" required>";
						$dosen = mysqli_query($con, "SELECT `username`,`nama` FROM `admin` WHERE `level`='4' OR (`level`='6' AND `stase`='$id_stase') ORDER BY `nama`");
						$datadosen = mysqli_fetch_array(mysqli_query($con, "SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data_cbd[dosen]'"));
						echo "<option value=\"$data_cbd[dosen]\">$datadosen[nama], $datadosen[gelar] ($datadosen[nip])</option>";
						while ($data = mysqli_fetch_array($dosen)) {
							$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$data[username]'"));
							echo "<option value=\"$data[username]\">$data_dosen[nama], $data_dosen[gelar] ($data_dosen[nip])</option>";
						}
						echo "</select>";
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
						echo "<th style=\"width:65%;text-align:center;\">Aspek Yang Dinilai</th>";
						echo "<th style=\"width:15%;text-align:center;\">Bobot</th>";
						echo "<th style=\"width:15%;text-align:center;\">Nilai (0-100)</th>";
						echo "</thead>";
						//No 1
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>1</strong></td>";
						echo "<td><strong>Kemampuan Anamnesis dan Pemeriksaan Fisik</strong></td>";
						echo "<td align=center><strong>25%</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_1\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek_1\" value=\"$data_cbd[aspek_1]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 2
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>2</strong></td>";
						echo "<td><strong>Kemampuan Keputusan klinis <span class=\"text-danger\">(usulan pemeriksaan penunjang level FKTP dan penegakan diagnosis)</span></strong></td>";
						echo "<td align=center><strong>25%</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_2\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek_2\" value=\"$data_cbd[aspek_2]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 3
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>3</strong></td>";
						echo "<td><strong>Kemampuan Penatalaksanaan Pasien (resep)</strong></td>";
						echo "<td align=center><strong>25%</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_3\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek_3\" value=\"$data_cbd[aspek_3]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//No 4
						echo "<tr class=\"table-warning\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td align=center class=\"td_mid\"><strong>4</strong></td>";
						echo "<td><strong>Kemampuan edukasi, komunikasi dan profesionalisme</strong></td>";
						echo "<td align=center><strong>25%</strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"aspek_4\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"aspek_4\" value=\"$data_cbd[aspek_4]\" onkeyup=\"sum();\" onchange=\"sum();\" required/></td>";
						echo "</tr>";
						//Rata-Rata Nilai
						echo "<tr class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td colspan=3 align=right><strong>Rata-Rata Nilai <span class=\"text-danger\">(Jumlah Bobot x Nilai)</span></strong></td>";
						echo "<td align=center><input type=\"number\" step=\"0.01\" min=\"0\" max=\"100\" name=\"nilai_rata\" style=\"width:60%;border:0.2px solid black;border-radius:3px;font-size:0.85em;text-align:center\" id=\"nilai_rata\" value=\"$data_cbd[nilai_rata]\" required disabled/></td>";
						echo "</tr>";
						echo "</table><br>";

						//Umpan Balik
						echo "<table class=\"table table-bordered\" style=\"width:70%\">";
						echo "<tr  class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Umpan Balik:</strong><br>
						<textarea name=\"umpan_balik\" rows=5 style=\"width:100%;margin-top:10px;font-family:Poppins;font-size:1em\" >$data_cbd[umpan_balik]</textarea></td>";
						echo "</tr>";
						echo "<tr  class=\"table-success\" style=\"border-width: 1px; border-color: #000;\">";
						echo "<td><strong>Saran:</strong><br>
						<textarea name=\"saran\" rows=5 style=\"width:100%;margin-top:10px;font-family:Poppins;font-size:1em\" >$data_cbd[saran]</textarea></td>";
						echo "</tr>";
						echo "</table><br>";

						echo "<br>
						<button type=\"submit\" class=\"btn btn-danger\" style=\"margin-right:40px\" name=\"cancel\" value=\"CANCEL\" formnovalidate>
            					<i class=\"fas fa-times me-2\"></i> CANCEL
        						</button>";
						echo "<button type=\"submit\" class=\"btn btn-success\" name=\"ubah\" value=\"UBAH\">
            			<i class=\"fas fa-edit me-2\"></i> UBAH
        			</button>
      				</form><br><br>";
						echo "</center>";

						if ($_POST['cancel'] == "CANCEL") {
							echo "
				<script>
					window.location.href=\"penilaian_kulit.php\";
				</script>
				";
						}

						if ($_POST['ubah'] == "UBAH") {
							$kasus = addslashes($_POST['kasus']);
							$jam_isi = $_POST['jam_isi'] . ":" . $_POST['menit_isi'] . " " . $_POST['lokal_isi'];
							$aspek_1 = number_format($_POST['aspek_1'], 2);
							$aspek_2 = number_format($_POST['aspek_2'], 2);
							$aspek_3 = number_format($_POST['aspek_3'], 2);
							$aspek_4 = number_format($_POST['aspek_4'], 2);

							$umpan_balik = addslashes($_POST['umpan_balik']);
							$saran = addslashes($_POST['saran']);

							$nilai_rata = 0.25 * $_POST['aspek_1'] + 0.25 * $_POST['aspek_2'] + 0.25 * $_POST['aspek_3'] + 0.25 * $_POST['aspek_4'];
							$nilai_rata = number_format($nilai_rata, 2);

							$update_cbd = mysqli_query($con, "UPDATE `kulit_nilai_cbd` SET
					`dosen`='$_POST[dosen]',`kasus`='$kasus',
					`aspek_1`='$aspek_1',`aspek_2`='$aspek_2',
					`aspek_3`='$aspek_3',`aspek_4`='$aspek_4',`nilai_rata`='$nilai_rata',
					`umpan_balik`='$umpan_balik',`saran`='$saran',
					`tgl_isi`='$_POST[tgl_isi]',`jam_isi`='$jam_isi',
					`tgl_approval`='2000-01-01',`status_approval`='0'
					WHERE `id`='$_POST[id]'");

							echo "
					<script>
						window.location.href=\"penilaian_kulit.php\";
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
			$('.tgl_isi').datepicker({
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
			$("#kasus").select2({
				placeholder: "<< Pilihan Kasus >>",
				allowClear: true
			});
			$("#jam_isi").select2({
				placeholder: "< Jam >",
				allowClear: true
			});
			$("#menit_isi").select2({
				placeholder: "< Menit >",
				allowClear: true
			});
			$("#lokal_isi").select2({
				placeholder: "< Zona Waktu >",
				allowClear: true
			});
			$("#dosen").select2({
				placeholder: "<< Dosen Pembimbing Lapangan >>",
				allowClear: true
			});
		});
	</script>

	<script>
		function sum() {
			var aspek1 = document.getElementById('aspek_1').value;
			var aspek2 = document.getElementById('aspek_2').value;
			var aspek3 = document.getElementById('aspek_3').value;
			var aspek4 = document.getElementById('aspek_4').value;
			var result = 0.25 * parseFloat(aspek1) + 0.25 * parseFloat(aspek2) + 0.25 * parseFloat(aspek3) + 0.25 * parseFloat(aspek4);
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