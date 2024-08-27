<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Entry Nilai Manual Logbook Koas Pendidikan Dokter FK-UNDIP</title>
	<link rel="shortcut icon" type="x-icon" href="images/undipsolid.png">
	<link rel="stylesheet" href="style/style1.css" />
	<link rel="stylesheet" href="style/buttontopup.css">
	<link rel="stylesheet" href="select2/dist/css/select2.css" />
	<link rel="stylesheet" type="text/css" href="jquery_ui/jquery-ui.css">

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
			if (!empty($_COOKIE['user']) and !empty($_COOKIE['pass']) and $_COOKIE['level'] == 2) {
				if ($_COOKIE['level'] == 2) {
					include "menu2.php";
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
			<nav class="navbar navbar-expand px-4 py-3" style="background-color: #ff6f61; ">
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
			<main class="content px-3 py-4" style="background-image: url('images/undip_watermark_color.png'), url('images/undip_watermark_color.png'); ">
				<div class="container-fluid">
					<div class="mb-3">
						<h3 class="fw-bold fs-4 mb-3">ENTRY NILAI MANUAL</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">FILTER ENTRY NILAI MANUAL</h2>
						<br><br>
						<center>
							<div class="table-responsive">
								<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
									<table class="table table-bordered" style="width:auto;">
										<tr class="table-primary" style="border-width: 1px; border-color: #000;">
											<td style="width:300px"><strong>Nama / NIM Mahasiswa</strong></td>
											<td style="width:700px">
												<?php if ($_COOKIE['user'] == "kaprodi") { ?>
													<select class=" form-select select2" name="nim_mhsw" id="nim_mhsw" required>
														<option value="all">
															<< Pilih Mahasiswa>>
														</option>
														<?php
														$data_mhsw = mysqli_query($con, "SELECT `nim`,`nama` FROM `biodata_mhsw` ORDER BY `nama`");
														while ($data = mysqli_fetch_array($data_mhsw)) {
															echo "<option value=\"{$data['nim']}\">{$data['nama']} (NIM: {$data['nim']})</option>";
														}
														?>
													</select>
												<?php } else {
													$id_stase = substr($_COOKIE['user'], 5, 3);
													$stase_id = "stase_M" . $id_stase;
												?>
													<select class="form-select select2" name="nim_mhsw" id="nim_mhsw" required>
														<option value="">
															<< Pilih Mahasiswa>>
														</option>
														<?php
														$nim_mhsw = mysqli_query($con, "SELECT `nim` FROM `$stase_id` ORDER BY `id`");
														while ($data = mysqli_fetch_array($nim_mhsw)) {
															$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT `nim`,`nama` FROM `biodata_mhsw` WHERE `nim`='{$data['nim']}'"));
															echo "<option value=\"{$data_mhsw['nim']}\">{$data_mhsw['nama']} (NIM: {$data_mhsw['nim']})</option>";
														}
														?>
													</select>
												<?php } ?>
											</td>
										</tr>
										<tr class="table-success" style="border-width: 1px; border-color: #000;">
											<td><strong>Kepaniteraan / Stase</strong></td>
											<td>
												<?php if ($_COOKIE['user'] == "kaprodi") { ?>
													<select class="form-select select2" name="stase" id="stase" required>
														<option value="">
															<< Pilihan Kepaniteraan (Stase)>>
														</option>
														<?php
														$data_stase = mysqli_query($con, "SELECT * FROM `kepaniteraan` ORDER BY `id`");
														while ($data = mysqli_fetch_array($data_stase)) {
															echo "<option value=\"{$data['id']}\">{$data['kepaniteraan']}</option>";
														}
														?>
													</select>
												<?php } else {
													$id_stase = substr($_COOKIE['user'], 5, 3);
													$stase_id = "M" . $id_stase;
													$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kepaniteraan` WHERE `id`='$stase_id'"));
												?>
													<select class="form-select select2" name="stase" id="stase" required>
														<option value="<?php echo $data_stase['id']; ?>"><?php echo $data_stase['kepaniteraan']; ?></option>
													</select>
												<?php } ?>
											</td>
										</tr>
										<tr class="table-primary" style="border-width: 1px; border-color: #000;">
											<td><strong>Jenis Nilai</strong></td>
											<td>
												<select class="form-select select2" name="jenis_nilai" id="jenis_nilai" required>
													<option value="">
														<< Jenis Nilai>>
													</option>
													<?php
													$filter_test = filter_nilai($stase_id);
													$jenis_test = mysqli_query($con, "SELECT * FROM `jenis_test` WHERE $filter_test ORDER BY `id`");
													while ($test = mysqli_fetch_array($jenis_test)) {
														echo "<option  value=\"{$test['id']}\">{$test['jenis_test']}</option>";
													}
													?>
												</select>
											</td>
										</tr>
										<tr class="table-success" style="border-width: 1px; border-color: #000;">
											<td><strong>Status Ujian/Test</strong></td>
											<td>
												<select class="form-select select2" name="status_ujian" id="status_ujian" required>
													<option value="">
														<< Status Ujian/Test>>
													</option>
													<?php
													$data_status = mysqli_query($con, "SELECT * FROM `status_ujian` ORDER BY `id`");
													while ($status = mysqli_fetch_array($data_status)) {
														echo "<option value=\"{$status['id']}\">{$status['status_ujian']}</option>";
													}
													?>
												</select>
											</td>
										</tr>
										<tr class="table-primary" style="border-width: 1px; border-color: #000;">
											<td><strong>Tanggal Ujian/Test</strong></td>
											<td>
												<input type="text" class="form-select input-tanggal" name="tgl_ujian" placeholder="yyyy-mm-dd" style="border:2px solid grey;border-radius:5px;" required />
											</td>
										</tr>
										<tr class="table-success" style="border-width: 1px; border-color: #000;">
											<td><strong>Tanggal Yudisium Nilai</strong></td>
											<td>
												<input type="text" class="form-select input-tanggal" name="tgl_approval" placeholder="yyyy-mm-dd" style="border:2px solid grey;border-radius:5px;" required />
											</td>
										</tr>
										<tr class="table-primary" style="border-width: 1px; border-color: #000;">
											<td><strong>Nilai Ujian</strong></td>
											<td>
												<input type="number" step="0.01" min="0" max="100" name="nilai" style="width:20%; height:40px;font-size:0.85em;text-align:center;border:2px solid grey;border-radius:5px; " placeholder="0-100" required />
											</td>
										</tr>
										<tr class="table-success" style="border-width: 1px; border-color: #000;">
											<td><strong>Catatan Khusus</strong></td>
											<td>
												<textarea row="3" name="catatan" style="width:100%;font-size:0.9em;font-family:Poppins;padding:0 0 0 7px;border:2px solid grey;border-radius:5px;"></textarea>
											</td>
										</tr>
									</table>
									<br>
									<button type="submit" class="btn btn-success" name="submit" value="SUBMIT"><i class="fa-solid fa-file me-2"></i>SUBMIT</button>
								</form>
							</div>
						</center>
						<br><br>

						<?php

						if ($_POST['submit'] == "SUBMIT") {
							//Stase yang entry nilai:
							//Stase Neurologi (M092)
							//Stase Psikiatri (M093)
							//Stase IKM-KP (M095)
							//Stase THT-KL (M105)
							//Stase Anestesi (M102)
							//Stase Radiologi (M103)
							//Stase Mata (M104)
							//Stase IKFR (M094)
							//Stase IKGM (M106)
							//Stase Forensik (M112)
							//Stase Kulit dan Kelamin (M114)
							//Stase Ilmu Penyakit Dalam (M091)
							//Stase Ilmu Kebidanan dan Penyakit Kandungan (M111)
							//Stase Ilmu Bedah (M101)
							//Stase Ilmu Kesehatan Anak (M113)

							if (
								$stase_id == "M092" or $stase_id == "M093" or $stase_id == "M095" or $stase_id == "M105" or $stase_id == "M102"
								or $stase_id == "M103" or $stase_id == "M104" or $stase_id == "M094" or $stase_id == "M106" or $stase_id == "M112"
								or $stase_id == "M114" or $stase_id == "M091" or $stase_id == "M111" or $stase_id == "M101" or $stase_id == "M113"
							) {
								if ($stase_id == "M095") {
									$db_tabel = "`ikmkp_nilai_test`";
									$kordik_id = "K095";
								}
								if ($stase_id == "M105") {
									$db_tabel = "`thtkl_nilai_test`";
									$kordik_id = "K105";
								}
								if ($stase_id == "M092") {
									$db_tabel = "`neuro_nilai_test`";
									$kordik_id = "K092";
								}
								if ($stase_id == "M093") {
									$db_tabel = "`psikiatri_nilai_test`";
									$kordik_id = "K093";
								}
								if ($stase_id == "M102") {
									$db_tabel = "`anestesi_nilai_test`";
									$kordik_id = "K102";
								}
								if ($stase_id == "M103") {
									$db_tabel = "`radiologi_nilai_test`";
									$kordik_id = "K103";
								}
								if ($stase_id == "M104") {
									$db_tabel = "`mata_nilai_test`";
									$kordik_id = "K104";
								}
								if ($stase_id == "M094") {
									$db_tabel = "`ikfr_nilai_test`";
									$kordik_id = "K094";
								}
								if ($stase_id == "M106") {
									$db_tabel = "`ikgm_nilai_test`";
									$kordik_id = "K106";
								}
								if ($stase_id == "M112") {
									$db_tabel = "`forensik_nilai_test`";
									$kordik_id = "K112";
								}
								if ($stase_id == "M114") {
									$db_tabel = "`kulit_nilai_test`";
									$kordik_id = "K114";
								}
								if ($stase_id == "M091") {
									$db_tabel = "`ipd_nilai_test`";
									$kordik_id = "K091";
								}
								if ($stase_id == "M111") {
									$db_tabel = "`obsgyn_nilai_test`";
									$kordik_id = "K111";
								}
								if ($stase_id == "M101") {
									$db_tabel = "`bedah_nilai_test`";
									$kordik_id = "K101";
								}
								if ($stase_id == "M113") {
									$db_tabel = "`ika_nilai_test`";
									$kordik_id = "K113";
								}

								$nim = $_POST['nim_mhsw'];
								$id_stase = $_POST['stase'];
								$jenis_nilai = $_POST['jenis_nilai'];
								$nama_test = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `jenis_test` WHERE `id`='$jenis_nilai'"));
								$status_ujian = $_POST['status_ujian'];
								$nama_status_ujian = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `status_ujian` WHERE `id`='$status_ujian'"));
								$nilai = $_POST['nilai'];
								if ($_POST['catatan'] == "" or empty($_POST['catatan'])) $catatan = "-";
								else $catatan = addslashes($_POST['catatan']);
								$tgl_ujian = $_POST['tgl_ujian'];
								$tgl_approval = $_POST['tgl_approval'];
								$dosen = mysqli_fetch_array(mysqli_query($con, "SELECT `username` FROM `admin` WHERE `stase`='$kordik_id'"));
								$nama_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT `nama` FROM `biodata_mhsw` WHERE `nim`='$nim'"));
								echo '<center>';
								echo '<table class="table table-bordered" style="width:70%">';
								echo '<tr class="table-primary" style="border-width: 1px; border-color: #000;">';
								echo '<td style="width:20%"><strong>Nama / NIM Mahasiswa</strong></td>';
								echo '<td style="width:50%; font-weight:600;">' . $nama_mhsw['nama'] . ' (NIM: <span style="color:red">' . $nim . '</span>)</td>';
								echo '</tr>';
								echo '<tr  class="table-warning" style="border-width: 1px; border-color: #000;">';
								echo '<td><strong>Kepaniteraan / Stase</strong></td>';
								$kepaniteraan = mysqli_fetch_array(mysqli_query($con, "SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$id_stase'"));
								echo '<td style="color: darkgreen ;font-weight:600;">' . $kepaniteraan['kepaniteraan'] . '</td>';
								echo '</tr>';
								echo '<tr class="table-primary" style="border-width: 1px; border-color: #000;">';
								echo '<td><strong>Jenis Nilai</strong></td>';
								echo '<td style="color: darkred ;font-weight:600;">' . $nama_test['jenis_test'] . '</td>';
								echo '</tr>';
								echo '<tr class="table-warning" style="border-width: 1px; border-color: #000;">';
								echo '<td><strong>Status Ujian</strong></td>';
								echo '<td style="color: purple ;font-weight:600;">' . $nama_status_ujian['status_ujian'] . '</td>';
								echo '</tr>';
								echo '<tr class="table-primary" style="border-width: 1px; border-color: #000;">';
								echo '<td><strong>Tanggal Ujian/Test</strong></td>';
								$tanggal_ujian = tanggal_indo($tgl_ujian);
								echo '<td style="color: darkblue ;font-weight:600;">' . $tanggal_ujian . '</td>';
								echo '</tr>';
								echo '<tr class="table-warning" style="border-width: 1px; border-color: #000;">';
								echo '<td><strong>Tanggal Yudisium Nilai</strong></td>';
								$tanggal_approval = tanggal_indo($tgl_approval);
								echo '<td style="color: darkred ;font-weight:600;">' . $tanggal_approval . '</td>';
								echo '</tr>';
								echo '<tr class="table-primary" style="border-width: 1px; border-color: #000;">';
								echo '<td><strong>Nilai Ujian</strong></td>';
								$nilai_ujian = number_format($nilai, 2);
								echo '<td style="font-weight:600;">' . $nilai_ujian . '</td>';
								echo '</tr>';
								echo '<tr class="table-warning" style="border-width: 1px; border-color: #000;">';
								echo '<td><strong>Catatan Khusus</strong></td>';
								echo '<td><span style="font-weight:600;">' . $catatan . '</span></td>';
								echo '</tr>';

								$cek_nim_stase = mysqli_num_rows(mysqli_query($con, "SELECT `id` FROM $db_tabel WHERE `nim`='$nim' AND `jenis_test`='$jenis_nilai' AND `status_ujian`='$status_ujian'"));
								if ($cek_nim_stase > 0) {
									$nilai_update = mysqli_query($con, "UPDATE $db_tabel
						SET `dosen`='$dosen[username]',`nilai`='$nilai_ujian',`catatan`='$catatan',
						`tgl_test`='$tgl_ujian',`tgl_approval`='$tgl_approval',`status_approval`='1'
						WHERE `nim`='$nim' AND `jenis_test`='$jenis_nilai' AND `status_ujian`='$status_ujian'");

									if (!$nilai_update) {
										echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
										echo "<td><strong>Status</strong></td>";
										echo "<td><font style=\"color:red; font-weight:600;\">ERROR</font></td>";
										echo "</tr>";
									} else {
										echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
										echo "<td><strong>Status</strong></td>";
										echo "<td><font style=\"color:green; font-weight:600;\">UPDATED</font></td>";
										echo "</tr>";
									}
								} else {
									$nilai_insert = mysqli_query($con, "INSERT INTO $db_tabel
						( `nim`, `dosen`, `jenis_test`,`status_ujian`,
							`nilai`, `catatan`,
							`tgl_test`, `tgl_approval`, `status_approval`)
						VALUES
						( '$nim','$dosen[username]','$jenis_nilai','$status_ujian',
							'$nilai_ujian','$catatan',
							'$tgl_ujian','$tgl_approval','1')");

									if (!$nilai_insert) {
										echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
										echo "<td><strong>Status</strong></td>";
										echo "<td><font style=\"color:red;font-weight:600;\">ERROR</font></td>";
										echo "</tr>";
									} else {
										echo "<tr class=\"table-primary\" style=\"border-width: 1px; border-color: #000;\">";
										echo "<td><strong>Status</strong></td>";
										echo "<td><font style=\"color:green;font-weight:600;\">ISSUED</font></td>";
										echo "</tr>";
									}
								}
								echo "</table><br><br>";
							}
							//Stase yang tidak entry nilai
							else {
								echo "<font style=\"color:red;font-weight:600;\">Catatan: Untuk Kepaniteraan/Stase ini tidak ada kegiatan <span style=\"color:blue;\">" . $nama_test['jenis_test'] . "</span>!!</font>";
							}
							echo '</center>';
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
	<script type="text/javascript" src="jquery.min.js"></script>
	<script type="text/javascript" src="jquery_ui/jquery-ui.js"></script>
	<script src="select2/dist/js/select2.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('.input-tanggal').datepicker({
				dateFormat: 'yy-mm-dd'
			});
			$("#nim_mhsw").select2({
				placeholder: "<< Pilih Mahasiswa >>",
				allowClear: true
			});
			$("#stase").select2({
				placeholder: "<< Pilihan Kepaniteraan (Stase) >>",
				allowClear: true
			});
			$("#dosen").select2({
				placeholder: "< Nama Dosen/Residen >",
				allowClear: true
			});
			$("#jenis_nilai").select2({
				placeholder: "<< Jenis Nilai >>",
				allowClear: true
			});
			$("#status_ujian").select2({
				placeholder: "<< Jenis Ujian/Test >>",
				allowClear: true
			});
		});
	</script>
</body>

</html>