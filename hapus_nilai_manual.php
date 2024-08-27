<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>On-line Hapus Nilai Manual Logbook Koas Pendidikan Dokter FK-UNDIP</title>
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
			<main class="content px-3 py-4">
				<div class="container-fluid">
					<div class="mb-3">
						<h3 class="fw-bold fs-4 mb-3">HAPUS NILAI MANUAL</h3>
						<br>
						<h2 class="fw-bold fs-4 mb-3 text-center" style="color:#0A3967">FILTER HAPUS NILAI MANUAL</h2>
						<br><br>
						<?php
						echo "<form method=\"POST\" action=\"$_SERVER[PHP_SELF]\">";
						echo "<center>";
						echo "<table class='table table-bordered' style='width:auto;'>";
						echo "<tr class='table-primary'  style='border-width: 1px; border-color: #000;'>";
						echo "<td style='width:300px'><strong>Nama / NIM Mahasiswa</strong></td>";
						echo "<td style='width:500px'>";
						if ($_COOKIE['user'] == "kaprodi") {
							echo "<select class='form-select' name='nim_mhsw' id='nim_mhsw' required>";
							$data_mhsw = mysqli_query($con, "SELECT `nim`,`nama` FROM `biodata_mhsw` ORDER BY `nama`");
							echo "<option value=''><< Pilih Mahasiswa >></option>";
							while ($data = mysqli_fetch_array($data_mhsw))
								echo "<option value='$data[nim]'>$data[nama] (NIM: $data[nim])</option>";
							echo "</select>";
						} else {
							$id_stase = substr($_COOKIE['user'], 5, 3);
							$stase_id = "stase_M" . "$id_stase";
							echo "<select class='form-select' name='nim_mhsw' id='nim_mhsw' required>";
							$nim_mhsw = mysqli_query($con, "SELECT `nim` FROM `$stase_id` ORDER BY `id`");
							echo "<option value=''><< Pilih Mahasiswa >></option>";
							while ($data = mysqli_fetch_array($nim_mhsw)) {
								$data_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT `nim`,`nama` FROM `biodata_mhsw` WHERE `nim`='$data[nim]'"));
								echo "<option value='$data_mhsw[nim]'>$data_mhsw[nama] (NIM: $data_mhsw[nim])</option>";
							}
							echo "</select>";
						}
						echo "</td>";
						echo "</tr>";
						echo "<tr class='table-success'  style='border-width: 1px; border-color: #000;'>";
						echo "<td><strong>Kepaniteraan / Stase</strong></td>";
						echo "<td>";
						if ($_COOKIE['user'] == "kaprodi") {
							echo "<select class='form-select' name='stase' id='stase' required>";
							$data_stase = mysqli_query($con, "SELECT * FROM `kepaniteraan` ORDER BY `id`");
							echo "<option value=''><< Pilihan Kepaniteraan (Stase) >></option>";
							while ($data = mysqli_fetch_array($data_stase))
								echo "<option value='$data[id]'>$data[kepaniteraan]</option>";
							echo "</select>";
						} else {
							$id_stase = substr($_COOKIE['user'], 5, 3);
							$stase_id = "M" . "$id_stase";
							$data_stase = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `kepaniteraan` WHERE `id`='$stase_id'"));
							echo "<select class='form-select' name='stase' id='stase' required>";
							echo "<option value='$data_stase[id]'>$data_stase[kepaniteraan]</option>";
							echo "</select>";
						}
						echo "</td>";
						echo "</tr>";
						echo "<tr class='table-primary'  style='border-width: 1px; border-color: #000;'>";
						echo "<td><strong>Jenis Nilai</strong></td>";
						$filter_test = filter_nilai($stase_id);
						echo "<td>";
						$jenis_test = mysqli_query($con, "SELECT * FROM `jenis_test` WHERE $filter_test ORDER BY `id`");
						echo "<select class='form-select' name='jenis_nilai' id='jenis_nilai' required>";
						echo "<option value=''><< Jenis Nilai >></option>";
						while ($test = mysqli_fetch_array($jenis_test)) {
							echo "<option value='$test[id]'>$test[jenis_test]</option>";
						}
						echo "</select>";
						echo "</td>";
						echo "</tr>";
						echo "<tr class='table-success'  style='border-width: 1px; border-color: #000;'>";
						echo "<td><strong>Status Ujian/Test</strong></td>";
						echo "<td>";
						$data_status = mysqli_query($con, "SELECT * FROM `status_ujian` ORDER BY `id`");
						echo "<select class='form-select' name='status_ujian' id='status_ujian' required>";
						echo "<option value=''><< Status Ujian/Test >></option>";
						while ($status = mysqli_fetch_array($data_status)) {
							echo "<option value='$status[id]'>$status[status_ujian]</option>";
						}
						echo "</select>";
						echo "</td>";
						echo "</tr>";
						echo "</table><br>";
						echo "</center>";
						echo "<br><center><button type='submit' class='btn btn-success' name='view' value='PREVIEW'><i class='fa-solid fa-file-circle-check me-2'></i> PREVIEW</button></center><br><br>";


						if ($_POST['view'] == "PREVIEW" and empty($_POST['hapus'])) {
							$nim = $_POST['nim_mhsw'];
							$id_stase = $_POST['stase'];
							$jenis_nilai = $_POST['jenis_nilai'];
							$nama_test = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `jenis_test` WHERE `id`='$jenis_nilai'"));
							$status_ujian = $_POST['status_ujian'];
							$nama_status_ujian = mysqli_fetch_array(mysqli_query($con, "SELECT * FROM `status_ujian` WHERE `id`='$status_ujian'"));

							echo "<input type=\"hidden\" name=\"nim_mhsw\" value=\"$nim\">";
							echo "<input type=\"hidden\" name=\"stase\" value=\"$id_stase\">";
							echo "<input type=\"hidden\" name=\"jenis_nilai\" value=\"$jenis_nilai\">";
							echo "<input type=\"hidden\" name=\"status_ujian\" value=\"$status_ujian\">";

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
							//Stase Kulit (M114)
							//Stase IPD (M091)
							//Stase Obsgyn (M111)
							//Stase Bedah (M101)
							//Stase Ilmu Kesehatan Anak (M113)

							if (
								$id_stase == "M092" or $id_stase == "M093" or $id_stase == "M095" or $id_stase == "M105" or $id_stase == "M102"
								or $id_stase == "M103" or $id_stase == "M104" or $id_stase == "M094" or $id_stase == "M106" or $id_stase == "M112"
								or $id_stase == "M114" or $id_stase == "M091" or $id_stase == "M111" or $id_stase == "M101" or $id_stase == "M113"
							) {
								if ($id_stase == "M095") $db_tabel = "`ikmkp_nilai_test`";
								if ($id_stase == "M105") $db_tabel = "`thtkl_nilai_test`";
								if ($id_stase == "M092") $db_tabel = "`neuro_nilai_test`";
								if ($id_stase == "M093") $db_tabel = "`psikiatri_nilai_test`";
								if ($id_stase == "M102") $db_tabel = "`anestesi_nilai_test`";
								if ($id_stase == "M103") $db_tabel = "`radiologi_nilai_test`";
								if ($id_stase == "M104") $db_tabel = "`mata_nilai_test`";
								if ($id_stase == "M094") $db_tabel = "`ikfr_nilai_test`";
								if ($id_stase == "M106") $db_tabel = "`ikgm_nilai_test`";
								if ($id_stase == "M112") $db_tabel = "`forensik_nilai_test`";
								if ($id_stase == "M114") $db_tabel = "`kulit_nilai_test`";
								if ($id_stase == "M091") $db_tabel = "`ipd_nilai_test`";
								if ($id_stase == "M111") $db_tabel = "`obsgyn_nilai_test`";
								if ($id_stase == "M101") $db_tabel = "`bedah_nilai_test`";
								if ($id_stase == "M113") $db_tabel = "`ika_nilai_test`";

								$data_nilai = mysqli_query($con, "SELECT * FROM $db_tabel WHERE `nim`='$nim' AND `jenis_test`='$jenis_nilai' AND `status_ujian`='$status_ujian'");
								$jml_data_nilai = mysqli_num_rows($data_nilai);

								if ($jml_data_nilai > 0) {
									$data = mysqli_fetch_array($data_nilai);
									$tanggal_ujian = tanggal_indo($data['tgl_test']);
									$tanggal_approval = tanggal_indo($data['tgl_approval']);

									$nama_mhsw = mysqli_fetch_array(mysqli_query($con, "SELECT `nama` FROM `biodata_mhsw` WHERE `nim`='$nim'"));
									echo "<center>";
									echo "<table class='table table-bordered' style='width:auto;'>";
									echo "<tr class='table-primary'  style='border-width: 1px; border-color: #000;'>";
									echo "<td style=\"width:300px\"><strong>Nama / NIM Mahasiswa</strong></td>";
									echo "<td style=\"width:500px; font-weight:600;\">$nama_mhsw[nama] (NIM: <span style=\"color:red;\">$nim</span>)</td>";
									echo "</tr>";
									echo "<tr class='table-warning'  style='border-width: 1px; border-color: #000;'>";
									echo "<td><strong>Kepaniteraan / Stase</strong></td>";
									$kepaniteraan = mysqli_fetch_array(mysqli_query($con, "SELECT `kepaniteraan` FROM `kepaniteraan` WHERE `id`='$id_stase'"));
									echo "<td style=\"color: darkgreen ;font-weight:600;\">$kepaniteraan[kepaniteraan]</td>";
									echo "</tr>";
									echo "<tr class='table-primary'  style='border-width: 1px; border-color: #000;'>";
									echo "<td><strong>Jenis Nilai</strong></td>";
									echo "<td style=\"color: darkred ;font-weight:600;\">$nama_test[jenis_test]</td>";
									echo "</tr>";
									echo "<tr class='table-warning'  style='border-width: 1px; border-color: #000;'>";
									echo "<td><strong>Status Ujian/Test</strong></td>";
									echo "<td style=\"color: purple ;font-weight:600;\">$nama_status_ujian[status_ujian]</td>";
									echo "</tr>";
									echo "<tr class='table-primary'  style='border-width: 1px; border-color: #000;'>";
									echo "<td><strong>Tanggal Ujian/Test</strong></td>";
									echo "<td style=\"color: darkblue ;font-weight:600;\">$tanggal_ujian</td>";
									echo "</tr>";
									echo "<tr class='table-primary'  style='border-width: 1px; border-color: #000;'>";
									echo "<td><strong>Tanggal Yudisium Nilai</strong></td>";
									echo "<td style=\"color: darkred ;font-weight:600;\">$tanggal_approval</td>";
									echo "</tr>";
									echo "<tr class='table-warning'  style='border-width: 1px; border-color: #000;'>";
									echo "<td><strong>Nilai Ujian</strong></td>";
									$nilai_ujian = number_format($data['nilai'], 2);
									echo "<td style=\"font-weight:600;\">$nilai_ujian</td>";
									echo "</tr>";
									echo "<tr class='table-primary'  style='border-width: 1px; border-color: #000;'>";
									echo "<td><strong>Catatan Khusus</strong></td>";
									echo "<td><span style=\"font-weight:600;\">$data[catatan]</span></td>";
									echo "</tr>";
									echo "</table><br>";
									echo "</center>";
									echo "<center>";
									echo "<button type='submit' class='btn btn-success' name='cancel'value='CANCEL' formnovalidate><i class='fa-solid fa-circle-xmark me-2'></i> CANCEL</button>";
									echo "&nbsp;&nbsp;&nbsp;&nbsp;";
									echo "<button type='submit' class='btn btn-danger' name='hapus' value='HAPUS' formnovalidate><i class='fa-solid fa-trash me-2'></i> HAPUS</button>";
									echo "</center><br>";
								} else {
									echo "<center>";
									echo "<font style=\"color:red; font-weight:600;\"> Data nilai tidak ditemukan!! </font>";
									echo "</center>";
								}
							}
							//Hapus manual nilai selainnya
							else {
								echo "<center>";
								echo "<font style=\"color:red;font-weight:600;\">Catatan: Untuk Kepaniteraan/Stase ini tidak ada kegiatan <span style=\"color:blue;\">" . $nama_test['jenis_test'] . "</span>!!</font>";
								echo "</center>";
							}
						}

						if ($_POST['hapus'] == "HAPUS" and empty($_POST['view'])) {
							$nim = $_POST['nim_mhsw'];
							$id_stase = $_POST['stase'];
							$jenis_nilai = $_POST['jenis_nilai'];
							$status_ujian = $_POST['status_ujian'];

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
							//Stase Kulit (M114)
							//Stase IPD (M091)
							//Stase Obsgyn (M111)
							//Stase Bedah (M101)
							//Stase Ilmu Kesehatan Anak (M113)

							if (
								$id_stase == "M092" or $id_stase == "M093" or $id_stase == "M095" or $id_stase == "M105" or $id_stase == "M102"
								or $id_stase == "M103" or $id_stase == "M104" or $id_stase == "M094" or $id_stase == "M106" or $id_stase == "M112"
								or $id_stase == "M114" or $id_stase == "M091" or $id_stase == "M111" or $id_stase == "M101" or $id_stase == "M113"
							) {
								if ($id_stase == "M095") $db_tabel = "`ikmkp_nilai_test`";
								if ($id_stase == "M105") $db_tabel = "`thtkl_nilai_test`";
								if ($id_stase == "M092") $db_tabel = "`neuro_nilai_test`";
								if ($id_stase == "M093") $db_tabel = "`psikiatri_nilai_test`";
								if ($id_stase == "M102") $db_tabel = "`anestesi_nilai_test`";
								if ($id_stase == "M103") $db_tabel = "`radiologi_nilai_test`";
								if ($id_stase == "M104") $db_tabel = "`mata_nilai_test`";
								if ($id_stase == "M094") $db_tabel = "`ikfr_nilai_test`";
								if ($id_stase == "M106") $db_tabel = "`ikgm_nilai_test`";
								if ($id_stase == "M112") $db_tabel = "`forensik_nilai_test`";
								if ($id_stase == "M114") $db_tabel = "`kulit_nilai_test`";
								if ($id_stase == "M091") $db_tabel = "`ipd_nilai_test`";
								if ($id_stase == "M111") $db_tabel = "`obsgyn_nilai_test`";
								if ($id_stase == "M101") $db_tabel = "`bedah_nilai_test`";
								if ($id_stase == "M113") $db_tabel = "`ika_nilai_test`";

								$hapus_nilai = mysqli_query($con, "DELETE FROM $db_tabel WHERE `nim`='$nim' AND `jenis_test`='$jenis_nilai' AND `status_ujian`='$status_ujian'");
								echo "<center>";
								echo "<font style=\"color:red; font-weight:600;\">Data nilai berhasil dihapus!!</font>";
								echo "<center>";
							}
							//Stase selainnya
							else {
								echo "<center>";
								echo "<font style=\"color:red;font-weight:600;\">Catatan: Untuk Kepaniteraan/Stase ini tidak ada kegiatan <span style=\"color:blue;\">" . $nama_test['jenis_test'] . "</span>!!</font>";
								echo "</center>";
							}
						}

						if ($_POST['cancel'] == "CANCEL") {
							echo "
			<script>
				window.location.href=\"hapus_nilai_manual.php\";
			</script>
			";
						}
						echo "</form>";
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