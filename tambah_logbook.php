<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Isi Jurnal Logbook Koas Pendidikan Dokter FK-UNDIP</title>
	<link rel="shortcut icon" type="x-icon" href="images/undipsolid.png">
	<link rel="stylesheet" href="select2/dist/css/select2.css" />
	<link rel="stylesheet" href="style/style1.css" />
	<link rel="stylesheet" href="style/buttontopup.css">
	<link rel="stylesheet" href="style/informasi.css">

	<!-- Link Bootstrap -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
	<!-- Link CDN Icon -->
	<link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
	<div class="wrapper">
		<?php

		include "config.php";
		include "fungsi.php";

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
		$foto_path = "foto/" . $data_mhsw['foto'];
		$default_foto = "foto/profil_blank.png";

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
						<img src="images/undipsolid.png" alt="" style="width: 45px;">
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
								<a href="logout.php" class="dropdown-item">
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
						<h3 class="fw-bold fs-4 mb-3">ISI LOGBOOK - PENYAKIT</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">ISI LOGBOOK KEPANITERAAN (STASE) - PENYAKIT</h2>
						<br>
						<?php
						echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";

						$id_stase = $_GET['id'];
						$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kepaniteraan` WHERE `id`='$id_stase'"));
						$stase_id = "stase_" . $id_stase;
						$data_stase_mhsw = mysqli_query($con, "SELECT * FROM `$stase_id` WHERE `nim`='$_COOKIE[user]'");
						$datastase_mhsw = mysqli_fetch_array($data_stase_mhsw);

						$tgl_mulai = tanggal_indo($datastase_mhsw['tgl_mulai']);
						$tgl_selesai = tanggal_indo($datastase_mhsw['tgl_selesai']);
						$tgl_isi = tanggal_indo($tgl);
						$mulai = date_create($datastase_mhsw['tgl_mulai']);
						$selesai = date_create($datastase_mhsw['tgl_selesai']);
						$sekarang = date_create($tgl);
						$jmlhari_stase = $data_stase['hari_stase'];
						$hari_skrg = date_diff($mulai, $sekarang);
						$jmlhari_skrg = $hari_skrg->days + 1;
						?>
						<center>
							<table class="table table-bordered" style="width:auto;">
								<tr class="table-primary" style="border-width: 1px; border-color: #000;">
									<!-- Kepaniteraan/Stase -->
									<td style="width:30%;" class="td_mid">
										<span><strong>Kepaniteraan/Stase</strong></span>
									</td>
									<td style="width:70%;">
										<?php
										echo "<input type=\"hidden\" name=\"stase\" value=\"$id_stase\">";
										echo "<span style=\" font-weight: 600; color: darkgreen;\">$data_stase[kepaniteraan]</span>";
										?>
									</td>
								</tr>

								<tr class="table-success" style="border-width: 1px; border-color: #000;">
									<!-- Tanggal/Hari ke- -->
									<td>
										<span><strong>Tanggal/Hari</strong></span>
									</td>
									<td style="font-weight: 600;">
										<?php
										echo "<input type=\"hidden\" name=\"jmlhari_skrg\" value=\"$jmlhari_skrg\">";
										echo "<span style=\"color: darkblue;\">$tgl_isi</span> / Hari ke-<span style=\"color: red;\">$jmlhari_skrg</span> dari <span style=\"color: red;\">$jmlhari_stase</span> hari masa kepaniteraan (stase)";

										?>
									</td>
								</tr>
							</table>
						</center>
						<br>


						<!-- Kelas Kegiatan -->
						<td>
							<div class="alert alert-info" role="alert" style="width: 60%; margin: auto;">
								<center>
									<span style="color:blue"><strong>Kelas Waktu Kegiatan</strong></span>
								</center>
								<br>
								<?php
								echo "<div class=\"row\" style=\"font-size:1.0em; font-weight: 600;\">";
								$kelas = mysqli_query($con, "SELECT `keterangan` FROM `kelas` ORDER BY `id`");
								$no = 1;
								while ($data_kelas = mysqli_fetch_array($kelas)) {
									echo "<div class=\"col-md-4 mb-2\">$no. $data_kelas[keterangan] WIB</div>";
									if ($no % 3 == 0) {
										echo "<div class=\"w-100\"></div>";
									}
									$no++;
								}
								echo "<div class=\"w-100\"></div><div class=\"col-12 mt-2\" style=\"color: red;\"><span>(Ctt: Untuk kegiatan melewati batas waktu kelas, buat kegiatan yang sama dengan kelas waktu yang berbeda!)</span></div>";

								echo "</div>";
								?>
							</div>
						</td>
						<br><br>
						<div class="table-responsive">
							<center>

								<table class="table table-bordered" style="width: auto; ">
									<tr class="table-primary" style="border-width: 1px; border-color: #000;">
										<!-- Pilih Kelas Waktu Kegiatan -->
										<td style="width:300px" class="td_mid">
											<span><strong>Pilih Kelas Waktu Kegiatan</strong></span>
										</td>
										<td style="width:500px">
											<div id="my_kelas" role="dialog" class="fix_style">
												<?php
												echo "<select class=\"form-select\" name=\"kelas\" id=\"kelas\" required>";
												$kelas = mysqli_query($con, "SELECT * FROM kelas ORDER BY id");
												echo "<option value=\"\">< Pilihan Kelas Waktu Kegiatan ></option>";
												while ($dat_kelas = mysqli_fetch_array($kelas))
													echo "<option value=\"$dat_kelas[id]\">Kelas $dat_kelas[kelas]</option>";
												echo "</select>";
												echo "<br><br>";
												?>
											</div>
											<div id="jam_mulai_selesai">
											</div>
										</td>
									</tr>
									<tr class="table-success" style="border-width: 1px; border-color: #000;">
										<!-- Lokasi -->
										<td>
											<span><strong>Lokasi</strong></span>
										</td>
										<td>
											<div id="my_lokasi" role="dialog" class="fix_style">
												<?php
												echo "<select class=\"form-select\" name=\"lokasi\" id=\"lokasi\" required>";
												$lokasi = mysqli_query($con, "SELECT * FROM `lokasi` ORDER BY `id`");
												echo "<option value=\"\">< Pilihan Lokasi ></option>";
												while ($dat5 = mysqli_fetch_array($lokasi))
													echo "<option value=\"$dat5[id]\">$dat5[lokasi]</option>";
												echo "</select>";
												?>
											</div>
										</td>
									</tr>
									<tr class="table-primary" style="border-width: 1px; border-color: #000;">
										<!-- Kegiatan -->
										<td>
											<span><strong>Kegiatan <span class="text-danger">(Level)</span></strong></span>
										</td>
										<td>
											<div id="my_kegiatan" role="dialog" class="fix_style">
												<?php
												echo "<select class=\"form-select\" name=\"kegiatan\" id=\"kegiatan\" required>";
												$kegiatan = mysqli_query($con, "SELECT * FROM `kegiatan` ORDER BY `id`");
												echo "<option value=\"\">< Pilihan Kegiatan ></option>";
												while ($dat5_1 = mysqli_fetch_array($kegiatan))
													echo "<option value=\"$dat5_1[id]\">$dat5_1[kegiatan] [$dat5_1[level]]</option>";
												echo "</select>";
												?>
											</div>
										</td>
									</tr>

								</table>

							</center>
							<center>
								<div class="alert alert-warning" role="alert" style="width: 60%; margin: auto;">
									<!-- Penyakit (Level)-->
									<span style="font-weight: 600; font-family:'Poppins'; color:darkblue">Penyakit (Level SKDI/Kepmenkes/IPSG/Muatan Lokal)</span>
								</div>
							</center>
							<br><br>
							<center>
								<table class="table table-bordered" style="width:auto;">
									<tr class="table-primary" style="border-width: 1px; border-color: #000;">
										<!-- Penyakit 1 -->
										<td style="width:300px" class="td_mid">
											<span><strong>Penyakit - 1</strong></span>
										</td>
										<td style="width:500px">
											<div id="my_penyakit1" role="dialog" class="fix_style">
												<?php
												$id_include = "include_" . $id_stase;
												$action_penyakit1 = mysqli_query($con, "SELECT * FROM `daftar_penyakit` WHERE `$id_include`='1' ORDER BY `penyakit` ASC");
												echo "<select name=\"penyakit1\" id=\"penyakit1\"  class=\"form-select\" required>";
												echo "<option value=\"\">< Pilihan Penyakit (Level SKDI/Kepmenkes/IPSG/Muatan Lokal)></option>";
												while ($for_peny1 = mysqli_fetch_array($action_penyakit1)) {
													echo "<option value=\"$for_peny1[id]\">$for_peny1[penyakit] (Level: $for_peny1[skdi_level]/$for_peny1[k_level]/$for_peny1[ipsg_level]/$for_peny1[kml_level])</option>";
												}
												echo "</select>";
												echo "<font style=\"font-size:1.0em; margin-top: 10px; display: block;color:red; font-weight:500;\"><span>&nbsp;&nbsp;(Wajib isi)</span></font>";
												?>
											</div>
										</td>
									</tr>
									<tr class="table-primary" style="border-width: 1px; border-color: #000;">
										<!-- Penyakit 2 -->
										<td style="width:300px" class="td_mid">
											<span><strong>Penyakit - 2</strong></span>
										</td>
										<td style="width:500px">
											<div id="my_penyakit2" role="dialog" class="fix_style">
												<?php
												$action_penyakit2 = mysqli_query($con, "SELECT * FROM `daftar_penyakit` WHERE `$id_include`='1' ORDER BY `penyakit` ASC");
												echo "<select name=\"penyakit2\" id=\"penyakit2\"  class=\"form-select\"";
												echo "<option value=\"\">< Pilihan Penyakit (Level SKDI/Kepmenkes/IPSG/Muatan Lokal)></option>";
												while ($for_peny2 = mysqli_fetch_array($action_penyakit2)) {
													echo "<option value=\"$for_peny2[id]\">$for_peny2[penyakit] (Level: $for_peny2[skdi_level]/$for_peny2[k_level]/$for_peny2[ipsg_level]/$for_peny2[kml_level])</option>";
												}
												echo "</select>";
												echo "<font style=\"font-size:1.0em;margin-top: 10px; display: block; color:purple; font-weight:500;\"><span>&nbsp;&nbsp;(Tambahan - optional)</span></font>";
												?>
											</div>
										</td>
									</tr>
									<tr class="table-primary" style="border-width: 1px; border-color: #000;">
										<!-- Penyakit 3 -->
										<td style="width:300px" class="td_mid">
											<span><strong>Penyakit - 3</strong></span>
										</td>
										<td style="width:500px">
											<div id="my_penyakit3" role="dialog" class="fix_style">
												<?php
												$action_penyakit3 = mysqli_query($con, "SELECT * FROM `daftar_penyakit` WHERE `$id_include`='1' ORDER BY `penyakit` ASC");
												echo "<select name=\"penyakit3\" id=\"penyakit3\" class=\"form-select\">";
												echo "<option value=\"\">< Pilihan Penyakit (Level SKDI/Kepmenkes/IPSG/Muatan Lokal)></option>";
												while ($for_peny3 = mysqli_fetch_array($action_penyakit3)) {
													echo "<option value=\"$for_peny3[id]\">$for_peny3[penyakit] (Level: $for_peny3[skdi_level]/$for_peny3[k_level]/$for_peny3[ipsg_level]/$for_peny3[kml_level])</option>";
												}
												echo "</select>";
												echo "<font style=\"font-size:1.0em;margin-top: 10px; display: block;color:purple; font-weight:500;\"><span>&nbsp;&nbsp;(Tambahan - optional)</span></font>";
												?>
											</div>
										</td>
									</tr>
									<tr class="table-primary" style="border-width: 1px; border-color: #000;">
										<!-- Penyakit 4 -->
										<td style="width:300px" class="td_mid">
											<span><strong>Penyakit - 4</strong></span>
										</td>
										<td style="width:500px">
											<div id="my_penyakit4" role="dialog" class="fix_style">
												<?php
												$action_penyakit4 = mysqli_query($con, "SELECT * FROM `daftar_penyakit` WHERE `$id_include`='1' ORDER BY `penyakit` ASC");
												echo "<select name=\"penyakit4\" id=\"penyakit4\" class=\"form-select\">";
												echo "<option value=\"\">< Pilihan Penyakit (Level SKDI/Kepmenkes/IPSG/Muatan Lokal)></option>";
												while ($for_peny4 = mysqli_fetch_array($action_penyakit4)) {
													echo "<option value=\"$for_peny4[id]\">$for_peny4[penyakit] (Level: $for_peny4[skdi_level]/$for_peny4[k_level]/$for_peny4[ipsg_level]/$for_peny4[kml_level])</option>";
												}
												echo "</select>";
												echo "<font style=\"font-size:1.0em;margin-top: 10px; display: block;color:purple; font-weight:500;\"><span>&nbsp;&nbsp;(Tambahan - optional)</span></font>";
												?>
											</div>
										</td>
									</tr>
									<tr class="table-success" style="border-width: 1px; border-color: #000;">
										<!-- Penyakit 4 -->
										<td style="width:300px" class="td_mid">
											<span><strong>Dosen/Residen</strong></span>
										</td>
										<td style="width:500px">
											<div id="my_dosen" role="dialog" class="fix_style">
												<?php
												echo "<select class=\"select_artwide\" name=\"dosen\" id=\"dosen\" required>";
												$dosen = mysqli_query($con, "SELECT `username`,`nama` FROM `admin` WHERE `level`='4' OR (`level`='6' AND `stase`='$id_stase') ORDER BY `nama`");
												echo "<option value=\"\">< Pilihan Dosen/Residen ></option>";
												while ($dat9 = mysqli_fetch_array($dosen)) {
													$data_dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `nip`,`nama`,`gelar` FROM `dosen` WHERE `nip`='$dat9[username]'"));
													echo "<option value=\"$dat9[username]\">$data_dosen[nama], $data_dosen[gelar] ($data_dosen[nip])</option>";
												}
												echo "</select></font>";
												?>
											</div>
										</td>
									</tr>
								</table>
								<br><br>
								<button type="submit" class="btn btn-danger me-3" name="batal" value="BATAL" formnovalidate>
									<i class="fa-solid fa-xmark me-2"></i> BATAL
								</button>
								<button type="submit" class="btn btn-success" name="tambahkan" value="TAMBAHKAN">
									<i class="fa-solid fa-plus me-2"></i> TAMBAHKAN
								</button>
							</center>
						</div>


						<?php
						echo "</form>";

						if ($_POST['batal'] == "BATAL") {
							echo "
				<script>
					window.location.href=\"edit_logbook.php?id=\"+\"$_POST[stase]\";
					</script>
				";
						}

						if ($_POST['tambahkan'] == "TAMBAHKAN") {
							$jam_awal = $_POST['jam_mulai'] . ":" . $_POST['menit_mulai'] . ":" . "00";
							$jam_akhir = $_POST['jam_selesai'] . ":" . $_POST['menit_selesai'] . ":" . "00";
							$jam_mulai_kegiatan = strtotime($jam_awal);
							$kelas = $_POST['kelas'];
							$angkatan_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT `angkatan`,`grup` FROM `biodata_mhsw` WHERE `nim`='$_COOKIE[user]'"));
							$cek_evaluasi = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM `evaluasi` WHERE `nim`='$_COOKIE[user]' AND `tanggal`='$tgl'"));
							if ($cek_evaluasi >= 1) $evaluasi = 1;
							else $evaluasi = 0;
							$tambah_db = mysqli_query($con, "INSERT INTO `jurnal_penyakit`
				( `nim`, `angkatan`,`grup`,`hari`,
					`tanggal`, `stase`, `jam_awal`,
					`jam_akhir`, `kelas`,`lokasi`, `kegiatan`,
					 `penyakit1`, `penyakit2`,
					`penyakit3`, `penyakit4`,
					`dosen`,`status`,`evaluasi`)
				VALUES
				( '$_COOKIE[user]','$angkatan_mhsw[angkatan]','$angkatan_mhsw[grup]','$_POST[jmlhari_skrg]',
					'$tgl','$_POST[stase]','$jam_awal',
					'$jam_akhir','$kelas','$_POST[lokasi]','$_POST[kegiatan]',
					'$_POST[penyakit1]','$_POST[penyakit2]',
					'$_POST[penyakit3]','$_POST[penyakit4]',
					'$_POST[dosen]','0','$evaluasi')");
							echo "
				<script>
					window.location.href=\"edit_logbook.php?id=\"+\"$_POST[stase]\";
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

	<script src="javascript/script1.js"></script>
	<script src="javascript/buttontopup.js"></script>
	<script src="jquery.min.js"></script>
	<script src="select2/dist/js/select2.js"></script>


	<script>
		$(document).ready(function() {

			$('#kelas').change(function() {
				var kls = $(this).val();
				$.ajax({
					type: 'POST',
					url: 'jam_mulai_selesai.php',
					data: 'kelas=' + kls,
					success: function(response) {
						$('#jam_mulai_selesai').html(response);
					}
				});
			});

			$("#kelas").select2({
				dropdownParent: $('#my_kelas'),
				placeholder: "< Pilihan Kelas Waktu Kegiatan >",
				allowClear: true
			});


			$("#lokasi").select2({
				dropdownParent: $('#my_lokasi'),
				placeholder: "< Lokasi >",
				allowClear: true
			});

			$("#kegiatan").select2({
				dropdownParent: $('#my_kegiatan'),
				placeholder: "< Kegiatan >",
				allowClear: true
			});

			$("#penyakit1").select2({
				dropdownParent: $('#my_penyakit1'),
				placeholder: "< Pilihan Penyakit (Level SKDI/Kepmenkes/IPSG/Muatan Lokal) >",
				allowClear: true
			});

			$("#penyakit2").select2({
				dropdownParent: $('#my_penyakit2'),
				placeholder: "< Pilihan Penyakit (Level SKDI/Kepmenkes/IPSG/Muatan Lokal) >",
				allowClear: true
			});

			$("#penyakit3").select2({
				dropdownParent: $('#my_penyakit3'),
				placeholder: "< Pilihan Penyakit (Level SKDI/Kepmenkes/IPSG/Muatan Lokal) >",
				allowClear: true
			});

			$("#penyakit4").select2({
				dropdownParent: $('#my_penyakit4'),
				placeholder: "< Pilihan Penyakit (Level SKDI/Kepmenkes/IPSG/Muatan Lokal) >",
				allowClear: true
			});

			$("#dosen").select2({
				dropdownParent: $('#my_dosen'),
				placeholder: "< Pilihan Dosen/Residen >",
				allowClear: true
			});
		});
	</script>
</body>

</html>